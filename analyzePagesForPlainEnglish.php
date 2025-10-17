<?php
/**
 * MW Drivel Defence - Batch Analysis Script
 * 
 * This script analyzes pages for plain English compliance and posts results to talk pages.
 * Can be run manually or via cron job.
 * 
 * Usage:
 *   php maintenance/analyzePagesForPlainEnglish.php
 *   php maintenance/analyzePagesForPlainEnglish.php --page="Test_page"
 *   php maintenance/analyzePagesForPlainEnglish.php --namespace=0 --limit=10
 *   php maintenance/analyzePagesForPlainEnglish.php --recent=7 (pages modified in last 7 days)
 */

require_once __DIR__ . '/../../maintenance/Maintenance.php';

use MediaWiki\MediaWikiServices;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\User\User;
use MediaWiki\Title\Title;

class AnalyzePagesForPlainEnglish extends Maintenance {

    public function __construct() {
        parent::__construct();
        $this->addDescription( 'Analyze pages for plain English compliance and post results to talk pages' );
        $this->addOption( 'page', 'Analyze a specific page (e.g., "Test_page")', false, true );
        $this->addOption( 'namespace', 'Analyze pages in specific namespace (default: 0)', false, true );
        $this->addOption( 'limit', 'Maximum number of pages to analyze (default: 50)', false, true );
        $this->addOption( 'recent', 'Only analyze pages modified in last N days (default: disabled)', false, true );
        $this->addOption( 'force', 'Force re-analysis even if already analyzed', false, false );
        $this->addOption( 'dry-run', 'Show what would be analyzed without making changes', false, false );
    }

    public function execute() {
        $this->output( "=== MW Drivel Defence Batch Analysis ===\n\n" );
        
        // Get configuration
        $config = $this->getConfig();
        $namespaces = $config->get( 'MWDrivelDefenceNamespaces' );
        $minWords = $config->get( 'MWDrivelDefenceMinWords' );
        $maxSentenceLength = $config->get( 'MWDrivelDefenceMaxSentenceLength' );
        
        $this->output( "Configuration:\n" );
        $this->output( "  Allowed namespaces: " . implode( ', ', $namespaces ) . "\n" );
        $this->output( "  Minimum words: $minWords\n" );
        $this->output( "  Max sentence length: $maxSentenceLength\n\n" );
        
        // Get options
        $specificPage = $this->getOption( 'page' );
        $namespace = $this->getOption( 'namespace', 0 );
        $limit = $this->getOption( 'limit', 50 );
        $recentDays = $this->getOption( 'recent' );
        $force = $this->hasOption( 'force' );
        $dryRun = $this->hasOption( 'dry-run' );
        
        if ( $dryRun ) {
            $this->output( "🔍 DRY RUN MODE - No changes will be made\n\n" );
        }
        
        // Get pages to analyze
        $pages = [];
        
        if ( $specificPage ) {
            // Analyze specific page
            $title = Title::newFromText( $specificPage );
            if ( $title && $title->exists() ) {
                $pages[] = $title;
                $this->output( "Analyzing specific page: " . $title->getFullText() . "\n\n" );
            } else {
                $this->fatalError( "Page '$specificPage' does not exist\n" );
            }
        } else {
            // Get pages from database
            $pages = $this->getPagesToAnalyze( $namespace, $namespaces, $limit, $recentDays );
            $this->output( "Found " . count( $pages ) . " pages to analyze\n\n" );
        }
        
        // Analyze pages
        $analyzed = 0;
        $posted = 0;
        $skipped = 0;
        
        foreach ( $pages as $title ) {
            $result = $this->analyzePage( $title, $minWords, $maxSentenceLength, $force, $dryRun );
            
            switch ( $result['status'] ) {
                case 'analyzed':
                    $analyzed++;
                    if ( $result['posted'] ) {
                        $posted++;
                    }
                    break;
                case 'skipped':
                    $skipped++;
                    break;
            }
            
            // Don't overwhelm the system
            if ( !$dryRun ) {
                usleep( 100000 ); // 0.1 second delay
            }
        }
        
        // Summary
        $this->output( "\n=== Summary ===\n" );
        $this->output( "Pages analyzed: $analyzed\n" );
        $this->output( "Talk pages updated: $posted\n" );
        $this->output( "Pages skipped: $skipped\n" );
        
        if ( $dryRun ) {
            $this->output( "\n💡 This was a dry run. Use without --dry-run to make actual changes.\n" );
        }
    }
    
    private function getPagesToAnalyze( $namespace, $allowedNamespaces, $limit, $recentDays ) {
        $dbr = $this->getDB( DB_REPLICA );
        
        $conditions = [
            'page_namespace' => $allowedNamespaces,
            'page_is_redirect' => 0
        ];
        
        $options = [
            'LIMIT' => $limit,
            'ORDER BY' => 'page_touched DESC'
        ];
        
        if ( $recentDays ) {
            $cutoff = $dbr->timestamp( time() - ( $recentDays * 24 * 60 * 60 ) );
            $conditions[] = 'page_touched >= ' . $dbr->addQuotes( $cutoff );
        }
        
        $result = $dbr->select(
            'page',
            [ 'page_namespace', 'page_title' ],
            $conditions,
            __METHOD__,
            $options
        );
        
        $pages = [];
        foreach ( $result as $row ) {
            $title = Title::makeTitle( $row->page_namespace, $row->page_title );
            if ( $title && !$title->isTalkPage() ) {
                $pages[] = $title;
            }
        }
        
        return $pages;
    }
    
    private function analyzePage( $title, $minWords, $maxSentenceLength, $force, $dryRun ) {
        $this->output( "Analyzing: " . $title->getFullText() );
        
        // Check if already analyzed recently (unless forced)
        if ( !$force && !$dryRun ) {
            $talkTitle = $title->getTalkPageIfDefined();
            if ( $talkTitle && $talkTitle->exists() ) {
                $talkPage = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $talkTitle );
                $talkContent = $talkPage->getContent();
                
                if ( $talkContent ) {
                    $talkText = $talkContent->getText();
                    if ( strpos( $talkText, 'Plain English Analysis' ) !== false ) {
                        // Check if analysis is recent (within 30 days)
                        if ( preg_match( '/analyzed.*?(\d{4}-\d{2}-\d{2})/', $talkText, $matches ) ) {
                            $analysisDate = strtotime( $matches[1] );
                            if ( $analysisDate && ( time() - $analysisDate ) < ( 30 * 24 * 60 * 60 ) ) {
                                $this->output( " - SKIPPED (recently analyzed)\n" );
                                return [ 'status' => 'skipped', 'posted' => false ];
                            }
                        }
                    }
                }
            }
        }
        
        // Get page content
        $wikiPage = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $title );
        $content = $wikiPage->getContent();
        
        if ( !$content ) {
            $this->output( " - SKIPPED (no content)\n" );
            return [ 'status' => 'skipped', 'posted' => false ];
        }
        
        $text = $content->getText();
        $plainText = $this->stripWikiMarkup( $text );
        $wordCount = str_word_count( $plainText );
        
        if ( $wordCount < $minWords ) {
            $this->output( " - SKIPPED (only $wordCount words, need $minWords+)\n" );
            return [ 'status' => 'skipped', 'posted' => false ];
        }
        
        // Analyze the text
        $analysis = $this->performAnalysis( $plainText, $maxSentenceLength );
        
        $this->output( " - $wordCount words, {$analysis['longSentences']} long sentences" );
        
        // Check if there are issues to report
        $hasIssues = $analysis['longSentences'] > 0 || count( $analysis['complexWords'] ) > 0;
        
        if ( !$hasIssues ) {
            $this->output( " - SKIPPED (no issues found)\n" );
            return [ 'status' => 'analyzed', 'posted' => false ];
        }
        
        if ( $dryRun ) {
            $this->output( " - WOULD POST to talk page\n" );
            return [ 'status' => 'analyzed', 'posted' => true ];
        }
        
        // Post to talk page
        $posted = $this->postToTalkPage( $title, $analysis );
        
        if ( $posted ) {
            $this->output( " - ✓ POSTED to talk page\n" );
            return [ 'status' => 'analyzed', 'posted' => true ];
        } else {
            $this->output( " - ✗ FAILED to post\n" );
            return [ 'status' => 'analyzed', 'posted' => false ];
        }
    }
    
    private function performAnalysis( $text, $maxSentenceLength ) {
        $sentences = $this->getSentences( $text );
        $words = str_word_count( $text, 1 );
        
        $totalSentences = count( $sentences );
        $totalWords = count( $words );
        $avgWordsPerSentence = $totalSentences > 0 ? round( $totalWords / $totalSentences, 1 ) : 0;
        
        $longSentences = 0;
        $longSentenceExamples = [];
        
        foreach ( $sentences as $sentence ) {
            $sentenceWords = str_word_count( $sentence );
            if ( $sentenceWords > $maxSentenceLength ) {
                $longSentences++;
                if ( count( $longSentenceExamples ) < 3 ) {
                    $longSentenceExamples[] = trim( substr( $sentence, 0, 100 ) ) . ( strlen( $sentence ) > 100 ? '...' : '' );
                }
            }
        }
        
        // Find complex words (4+ syllables)
        $complexWords = [];
        foreach ( $words as $word ) {
            if ( $this->countSyllables( $word ) >= 4 && strlen( $word ) > 6 ) {
                $lowerWord = strtolower( $word );
                if ( !in_array( $lowerWord, $complexWords ) && count( $complexWords ) < 10 ) {
                    $complexWords[] = $lowerWord;
                }
            }
        }
        
        return [
            'totalWords' => $totalWords,
            'totalSentences' => $totalSentences,
            'avgWordsPerSentence' => $avgWordsPerSentence,
            'longSentences' => $longSentences,
            'longSentenceExamples' => $longSentenceExamples,
            'complexWords' => $complexWords
        ];
    }
    
    private function postToTalkPage( $title, $analysis ) {
        try {
            $talkTitle = $title->getTalkPageIfDefined();
            if ( !$talkTitle || !$talkTitle->canExist() ) {
                return false;
            }
            
            $date = date( 'F j, Y' );
            $summary = "Automatic plain English analysis";
            
            // Create analysis content
            $content = "\n\n== Plain English Analysis ==\n\n";
            $content .= "This page was automatically analyzed for plain English compliance on $date.\n\n";
            $content .= "'''Summary:'''\n";
            $content .= "* Total words: {$analysis['totalWords']}\n";
            $content .= "* Total sentences: {$analysis['totalSentences']}\n";
            $content .= "* Average words per sentence: {$analysis['avgWordsPerSentence']}\n";
            
            if ( $analysis['longSentences'] > 0 ) {
                $content .= "* Sentences over 25 words: {$analysis['longSentences']}\n";
            }
            
            if ( count( $analysis['complexWords'] ) > 0 ) {
                $content .= "* Complex words found: " . count( $analysis['complexWords'] ) . "\n";
            }
            
            $content .= "\n'''Recommendations:'''\n";
            
            if ( $analysis['longSentences'] > 0 ) {
                $content .= "⚠ Found {$analysis['longSentences']} sentences with more than 25 words. Consider breaking these into shorter sentences.\n";
                
                if ( !empty( $analysis['longSentenceExamples'] ) ) {
                    $content .= "\n'''Examples of long sentences:'''\n";
                    foreach ( $analysis['longSentenceExamples'] as $example ) {
                        $content .= "* \"$example\"\n";
                    }
                }
            }
            
            if ( !empty( $analysis['complexWords'] ) ) {
                $content .= "\n⚠ Consider using simpler alternatives for complex words:\n";
                $content .= "* " . implode( ', ', array_slice( $analysis['complexWords'], 0, 10 ) ) . "\n";
            }
            
            $content .= "\n''This analysis was generated automatically. For more detailed analysis, visit [[Special:MWDrivelDefence]].''\n";
            
            // Add signature with timestamp (like ~~~~)
            $utcTimestamp = gmdate( 'H:i, j F Y \(U\T\C\)' ); // UTC version
            $content .= "\n--[[User:MWDrivelDefence|MWDrivelDefence]] ([[User talk:MWDrivelDefence|talk]]) $utcTimestamp\n";
            
            // Get existing content or create new
            $wikiPage = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $talkTitle );
            $existingContent = '';
            
            if ( $talkTitle->exists() ) {
                $existingContentObj = $wikiPage->getContent();
                if ( $existingContentObj ) {
                    $existingContent = $existingContentObj->getText();
                    
                    // Remove old analysis section
                    $existingContent = preg_replace( 
                        '/\n*== Plain English Analysis ==.*?(?=\n== |\n\[\[Category:|\n{{|$)/s', 
                        '', 
                        $existingContent 
                    );
                }
            }
            
            $newContent = $existingContent . $content;
            $contentObj = new WikitextContent( $newContent );
            
            // Save the page
            $user = User::newSystemUser( 'MWDrivelDefence', [ 'steal' => true ] );
            $updater = $wikiPage->newPageUpdater( $user );
            $updater->setContent( SlotRecord::MAIN, $contentObj );
            $updater->saveRevision( CommentStoreComment::newUnsavedComment( $summary ) );
            
            return true;
            
        } catch ( Exception $e ) {
            $this->output( " - ERROR: " . $e->getMessage() );
            return false;
        }
    }
    
    private function stripWikiMarkup( $text ) {
        // Remove templates
        $text = preg_replace( '/\{\{[^}]*\}\}/', '', $text );
        // Remove links but keep text
        $text = preg_replace( '/\[\[[^|\]]*\|([^\]]*)\]\]/', '$1', $text );
        $text = preg_replace( '/\[\[([^\]]*)\]\]/', '$1', $text );
        // Remove external links but keep text
        $text = preg_replace( '/\[http[^\s]+ ([^\]]*)\]/', '$1', $text );
        // Remove HTML tags
        $text = preg_replace( '/<[^>]*>/', '', $text );
        // Remove headings markup but keep text
        $text = preg_replace( '/^[=]{1,6}([^=]+)[=]{1,6}$/m', '$1', $text );
        // Remove list markup
        $text = preg_replace( '/^[*#:;]+ ?/m', '', $text );
        // Remove bold/italic
        $text = preg_replace( "/'''([^']+)'''/", '$1', $text );
        $text = preg_replace( "/''([^']+)''/", '$1', $text );
        
        return trim( $text );
    }
    
    private function getSentences( $text ) {
        $sentences = preg_split( '/[.!?]+/', $text );
        $sentences = array_filter( array_map( 'trim', $sentences ) );
        return array_values( $sentences );
    }
    
    private function countSyllables( $word ) {
        $word = strtolower( preg_replace( '/[^a-z]/', '', $word ) );
        if ( strlen( $word ) <= 3 ) return 1;
        
        $syllables = preg_match_all( '/[aeiouy]+/', $word );
        if ( substr( $word, -1 ) === 'e' ) $syllables--;
        if ( substr( $word, -2 ) === 'le' && strlen( $word ) > 2 && !in_array( substr( $word, -3, 1 ), ['a','e','i','o','u','y'] ) ) $syllables++;
        
        return max( 1, $syllables );
    }
}

$maintClass = AnalyzePagesForPlainEnglish::class;
require_once RUN_MAINTENANCE_IF_MAIN;