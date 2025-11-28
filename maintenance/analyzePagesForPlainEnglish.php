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

require_once getenv( 'MW_INSTALL_PATH' ) !== false
    ? getenv( 'MW_INSTALL_PATH' ) . '/maintenance/Maintenance.php'
    : __DIR__ . '/../../../maintenance/Maintenance.php';

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
        $rawText = $content->getText();

        // Extract template parameter values
        $templateFields = [];
        if ( preg_match_all('/\{\{[^}]+\}\}/', $rawText, $templates) ) {
            foreach ($templates[0] as $template) {
                // Find all |param=value pairs
                if ( preg_match_all('/\|([^=|\n]+)=([^|\n}]*)/', $template, $matches, PREG_SET_ORDER) ) {
                    foreach ($matches as $m) {
                        $param = trim($m[1]);
                        $value = trim($m[2]);
                        if ($value !== '') {
                            $templateFields[] = $value;
                        }
                    }
                }
            }
        }

        // Combine template field values and visible prose
        $templateText = implode("\n", $templateFields);
        $plainText = $this->stripWikiMarkup( $rawText );
        $combinedText = trim($plainText . "\n" . $templateText);

        $wordCount = str_word_count( $combinedText );
        if ( $wordCount < $minWords ) {
            $this->output( " - SKIPPED (only $wordCount words, need $minWords+)\n" );
            return [ 'status' => 'skipped', 'posted' => false ];
        }

    // Analyze the text
    $analysis = $this->performAnalysis( $combinedText, $maxSentenceLength );
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
        
        // Calculate comprehensive reading level metrics
        $readingLevel = $this->calculateReadingLevel( $text, $totalWords, $totalSentences );
        
        // Analyze dyslexia-friendly factors
        $dyslexiaAnalysis = $this->analyzeDyslexiaFriendliness( $text, $sentences );
        
        // Analyze paragraph structure
        $paragraphAnalysis = $this->analyzeParagraphs( $text );
        
        // Calculate overall accessibility score
        $accessibilityScore = $this->calculateAccessibilityScore( $readingLevel, $dyslexiaAnalysis, $longSentences, count( $complexWords ) );
        
        // Get detailed recommendations
        $detailedRecommendations = $this->getDetailedRecommendations( $analysis = [
            'longSentences' => $longSentences,
            'complexWords' => $complexWords,
            'readingLevel' => $readingLevel,
            'dyslexiaAnalysis' => $dyslexiaAnalysis,
            'paragraphAnalysis' => $paragraphAnalysis
        ] );
        
        return [
            'totalWords' => $totalWords,
            'totalSentences' => $totalSentences,
            'avgWordsPerSentence' => $avgWordsPerSentence,
            'longSentences' => $longSentences,
            'longSentenceExamples' => $longSentenceExamples,
            'complexWords' => $complexWords,
            'readingLevel' => $readingLevel,
            'dyslexiaAnalysis' => $dyslexiaAnalysis,
            'paragraphAnalysis' => $paragraphAnalysis,
            'accessibilityScore' => $accessibilityScore,
            'detailedRecommendations' => $detailedRecommendations
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
            
            // Create comprehensive analysis content
            $content = "\n\n== Plain English & Accessibility Analysis ==\n\n";
            $content .= "This page was automatically analyzed for plain English compliance and WCAG 2.2 AA accessibility on $date.\n\n";
            
            // Overall score section
            $scoreEmoji = $analysis['accessibilityScore']['compliance'] === 'compliant' ? '✅' : 
                         ( $analysis['accessibilityScore']['compliance'] === 'partial' ? '⚠️' : '❌' );
            $content .= "'''Overall Accessibility Score: {$scoreEmoji} {$analysis['accessibilityScore']['score']}/100'''\n\n";
            
            // Basic Statistics
            $content .= "=== 📊 Content Statistics ===\n";
            $content .= "* '''Total words:''' {$analysis['totalWords']}\n";
            $content .= "* '''Total sentences:''' {$analysis['totalSentences']}\n";
            $content .= "* '''Total paragraphs:''' {$analysis['paragraphAnalysis']['total']}\n";
            $content .= "* '''Average words per sentence:''' {$analysis['avgWordsPerSentence']}\n";
            $content .= "* '''Average words per paragraph:''' {$analysis['paragraphAnalysis']['avgWordsPerParagraph']}\n\n";
            
            // Reading Level Analysis
            $content .= "=== 📖 Reading Level Analysis ===\n";
            $content .= "* '''Flesch-Kincaid Grade Level:''' {$analysis['readingLevel']['fleschKincaid']} ";
            $content .= $analysis['readingLevel']['fleschKincaid'] <= 8 ? "(✅ Target: ≤8)" : "(❌ Target: ≤8)";
            $content .= "\n* '''Flesch Reading Ease:''' {$analysis['readingLevel']['fleschEase']} ";
            $content .= $analysis['readingLevel']['fleschEase'] >= 60 ? "(✅ Target: ≥60)" : "(❌ Target: ≥60)";
            $content .= "\n* '''Gunning Fog Index:''' {$analysis['readingLevel']['gunningFog']} ";
            $content .= $analysis['readingLevel']['gunningFog'] <= 12 ? "(✅ Target: ≤12)" : "(❌ Target: ≤12)";
            $content .= "\n\n";
            
            // Dyslexia-Friendly Analysis
            $content .= "=== 🧠 Dyslexia-Friendly Design ===\n";
            $content .= "* '''Dyslexia Score:''' {$analysis['dyslexiaAnalysis']['score']}/100\n";
            if ( $analysis['paragraphAnalysis']['longParagraphs'] > 0 ) {
                $content .= "* '''Long paragraphs:''' {$analysis['paragraphAnalysis']['longParagraphs']} (over 40 words)\n";
            }
            if ( $analysis['longSentences'] > 0 ) {
                $content .= "* '''Long sentences:''' {$analysis['longSentences']} (over 25 words)\n";
            }
            if ( count( $analysis['complexWords'] ) > 0 ) {
                $content .= "* '''Complex words:''' " . count( $analysis['complexWords'] ) . " (4+ syllables)\n";
            }
            $content .= "\n";
            
            // Detailed Recommendations
            $content .= "=== 💡 Detailed Recommendations ===\n";
            foreach ( $analysis['detailedRecommendations'] as $rec ) {
                $icon = $rec['severity'] === 'error' ? '🚨' : 
                       ( $rec['severity'] === 'warning' ? '⚠️' : 
                       ( $rec['severity'] === 'success' ? '✅' : 'ℹ️' ) );
                
                $content .= "'''$icon {$rec['message']}'''\n";
                $content .= ": {$rec['suggestion']}\n\n";
            }
            
            // Examples section (if there are issues)
            if ( !empty( $analysis['longSentenceExamples'] ) ) {
                $content .= "=== 📝 Examples to Improve ===\n";
                $content .= "'''Long sentences found:'''\n";
                foreach ( $analysis['longSentenceExamples'] as $example ) {
                    $content .= "* \"$example\"\n";
                }
                $content .= "\n";
            }
            
            if ( !empty( $analysis['complexWords'] ) ) {
                $content .= "'''Complex words to consider simplifying:'''\n";
                $content .= "* " . implode( ', ', array_slice( $analysis['complexWords'], 0, 15 ) );
                if ( count( $analysis['complexWords'] ) > 15 ) {
                    $content .= " ''(and " . ( count( $analysis['complexWords'] ) - 15 ) . " more)''";
                }
                $content .= "\n\n";
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
    
    private function calculateReadingLevel( $text, $totalWords, $totalSentences ) {
        if ( $totalWords == 0 || $totalSentences == 0 ) {
            return [ 'fleschKincaid' => 0, 'fleschEase' => 0, 'gunningFog' => 0 ];
        }
        
        // Count total syllables
        $words = str_word_count( $text, 1 );
        $totalSyllables = 0;
        foreach ( $words as $word ) {
            $totalSyllables += $this->countSyllables( $word );
        }
        
        $avgWordsPerSentence = $totalWords / $totalSentences;
        $avgSyllablesPerWord = $totalSyllables / $totalWords;
        
        // Flesch-Kincaid Grade Level
        $fleschKincaid = ( 0.39 * $avgWordsPerSentence ) + ( 11.8 * $avgSyllablesPerWord ) - 15.59;
        
        // Flesch Reading Ease
        $fleschEase = 206.835 - ( 1.015 * $avgWordsPerSentence ) - ( 84.6 * $avgSyllablesPerWord );
        
        // Gunning Fog Index (simplified - counts words with 3+ syllables as complex)
        $complexWords = 0;
        foreach ( $words as $word ) {
            if ( $this->countSyllables( $word ) >= 3 ) {
                $complexWords++;
            }
        }
        $percentComplex = ( $complexWords / $totalWords ) * 100;
        $gunningFog = 0.4 * ( $avgWordsPerSentence + $percentComplex );
        
        return [
            'fleschKincaid' => round( max( 0, $fleschKincaid ), 1 ),
            'fleschEase' => round( max( 0, min( 100, $fleschEase ) ), 1 ),
            'gunningFog' => round( max( 0, $gunningFog ), 1 ),
            'totalSyllables' => $totalSyllables,
            'avgSyllablesPerWord' => round( $avgSyllablesPerWord, 2 )
        ];
    }
    
    private function analyzeDyslexiaFriendliness( $text, $sentences ) {
        $paragraphs = explode( "\n\n", $text );
        $longParagraphs = 0;
        $complexSentences = 0;
        $issues = [];
        
        // Check paragraph lengths (over 40 words is problematic)
        foreach ( $paragraphs as $paragraph ) {
            $paragraphWords = str_word_count( $paragraph );
            if ( $paragraphWords > 40 ) {
                $longParagraphs++;
            }
        }
        
        // Check sentence complexity (over 20 words or high syllable density)
        foreach ( $sentences as $sentence ) {
            $sentenceWords = str_word_count( $sentence );
            $words = str_word_count( $sentence, 1 );
            $syllables = 0;
            
            foreach ( $words as $word ) {
                $syllables += $this->countSyllables( $word );
            }
            
            $avgSyllables = $sentenceWords > 0 ? $syllables / $sentenceWords : 0;
            
            if ( $sentenceWords > 20 || $avgSyllables > 1.7 ) {
                $complexSentences++;
            }
        }
        
        // Generate recommendations
        if ( $longParagraphs > 0 ) {
            $issues[] = "$longParagraphs paragraphs exceed 40 words";
        }
        if ( $complexSentences > 0 ) {
            $issues[] = "$complexSentences sentences are too complex";
        }
        
        $score = 100;
        if ( $longParagraphs > 0 ) $score -= ( $longParagraphs * 10 );
        if ( $complexSentences > 0 ) $score -= ( $complexSentences * 5 );
        
        return [
            'score' => max( 0, $score ),
            'longParagraphs' => $longParagraphs,
            'complexSentences' => $complexSentences,
            'issues' => $issues
        ];
    }
    
    private function calculateAccessibilityScore( $readingLevel, $dyslexiaAnalysis, $longSentences, $complexWordCount ) {
        $score = 100;
        
        // Deduct points for reading level issues
        if ( $readingLevel['fleschKincaid'] > 8 ) {
            $score -= min( 20, ( $readingLevel['fleschKincaid'] - 8 ) * 2 );
        }
        if ( $readingLevel['fleschEase'] < 60 ) {
            $score -= min( 20, ( 60 - $readingLevel['fleschEase'] ) / 2 );
        }
        if ( $readingLevel['gunningFog'] > 12 ) {
            $score -= min( 15, ( $readingLevel['gunningFog'] - 12 ) * 1.5 );
        }
        
        // Deduct points for dyslexia issues
        $score -= ( 100 - $dyslexiaAnalysis['score'] ) * 0.3;
        
        // Deduct points for sentence and word complexity
        $score -= min( 20, $longSentences * 3 );
        $score -= min( 15, $complexWordCount * 1.5 );
        
        $finalScore = max( 0, round( $score ) );
        
        // Determine compliance level
        $compliance = 'needs-work';
        if ( $finalScore >= 80 ) {
            $compliance = 'compliant';
        } elseif ( $finalScore >= 60 ) {
            $compliance = 'partial';
        }
        
        return [
            'score' => $finalScore,
            'compliance' => $compliance,
            'breakdown' => [
                'readingLevel' => $readingLevel,
                'dyslexia' => $dyslexiaAnalysis['score']
            ]
        ];
    }
    
    private function analyzeParagraphs( $text ) {
        $paragraphs = preg_split( '/\n\s*\n/', $text );
        $paragraphs = array_filter( array_map( 'trim', $paragraphs ) );
        
        $totalParagraphs = count( $paragraphs );
        $longParagraphs = 0;
        $avgWordsPerParagraph = 0;
        
        $totalWords = 0;
        foreach ( $paragraphs as $paragraph ) {
            $words = str_word_count( $paragraph );
            $totalWords += $words;
            
            if ( $words > 40 ) { // Paragraphs over 40 words are considered long
                $longParagraphs++;
            }
        }
        
        $avgWordsPerParagraph = $totalParagraphs > 0 ? round( $totalWords / $totalParagraphs, 1 ) : 0;
        
        return [
            'total' => $totalParagraphs,
            'longParagraphs' => $longParagraphs,
            'avgWordsPerParagraph' => $avgWordsPerParagraph
        ];
    }
    
    private function getDetailedRecommendations( $analysis ) {
        $recommendations = [];
        
        // Reading level recommendations
        if ( $analysis['readingLevel']['fleschKincaid'] > 8 ) {
            $recommendations[] = [
                'type' => 'reading-level',
                'severity' => 'warning',
                'message' => "Reading level is Grade {$analysis['readingLevel']['fleschKincaid']} (target: Grade 8 or below)",
                'suggestion' => 'Use shorter sentences and simpler words to improve readability'
            ];
        }
        
        if ( $analysis['readingLevel']['fleschEase'] < 60 ) {
            $recommendations[] = [
                'type' => 'flesch-ease',
                'severity' => 'warning', 
                'message' => "Flesch Reading Ease score: {$analysis['readingLevel']['fleschEase']} (target: 60+)",
                'suggestion' => 'Simplify vocabulary and sentence structure for easier reading'
            ];
        }
        
        if ( $analysis['readingLevel']['gunningFog'] > 12 ) {
            $recommendations[] = [
                'type' => 'gunning-fog',
                'severity' => 'warning',
                'message' => "Gunning Fog Index: {$analysis['readingLevel']['gunningFog']} (target: 12 or below)",
                'suggestion' => 'Reduce complex words and lengthy sentences'
            ];
        }
        
        // Sentence structure recommendations
        if ( $analysis['longSentences'] > 0 ) {
            $severity = $analysis['longSentences'] > 3 ? 'error' : 'warning';
            $recommendations[] = [
                'type' => 'long-sentences',
                'severity' => $severity,
                'message' => "Found {$analysis['longSentences']} sentences over 25 words",
                'suggestion' => 'Break long sentences into 2-3 shorter ones. Use conjunctions like "and", "but", "because" to create natural breaks'
            ];
        }
        
        // Complex words recommendations  
        if ( count( $analysis['complexWords'] ) > 0 ) {
            $count = count( $analysis['complexWords'] );
            $severity = $count > 5 ? 'error' : 'warning';
            $recommendations[] = [
                'type' => 'complex-words',
                'severity' => $severity,
                'message' => "Found $count complex words (4+ syllables)",
                'suggestion' => 'Replace with simpler alternatives where possible. Examples: "demonstrate" → "show", "facilitate" → "help"'
            ];
        }
        
        // Dyslexia-friendly recommendations
        if ( $analysis['dyslexiaAnalysis']['score'] < 80 ) {
            if ( $analysis['paragraphAnalysis']['longParagraphs'] > 0 ) {
                $recommendations[] = [
                    'type' => 'paragraph-length',
                    'severity' => 'info',
                    'message' => "Found {$analysis['paragraphAnalysis']['longParagraphs']} paragraphs over 40 words",
                    'suggestion' => 'Break long paragraphs into shorter ones (3-4 sentences each) for better dyslexia accessibility'
                ];
            }
        }
        
        // Positive feedback when things are good
        if ( empty( $recommendations ) ) {
            $recommendations[] = [
                'type' => 'success',
                'severity' => 'success',
                'message' => 'Content meets plain English and accessibility guidelines',
                'suggestion' => 'Well done! Your content is clear and accessible to a wide audience'
            ];
        }
        
        return $recommendations;
    }
}

$maintClass = AnalyzePagesForPlainEnglish::class;
require_once RUN_MAINTENANCE_IF_MAIN;
