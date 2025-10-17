<?php
/**
 * MW Drivel Defence - Diagnostic Script
 * 
 * This script helps diagnose why automatic analysis isn't working
 * Run this from your MediaWiki maintenance directory:
 * php extensions/MWDrivelDefence/diagnostics.php
 */

require_once __DIR__ . '/../../maintenance/Maintenance.php';

class MWDrivelDefenceDiagnostics extends Maintenance {

    public function __construct() {
        parent::__construct();
        $this->addDescription( 'Diagnose MW Drivel Defence automatic analysis issues' );
        $this->addOption( 'page', 'Page title to test (e.g., "Test_page")', true, true );
    }

    public function execute() {
        $pageTitle = $this->getOption( 'page' );
        
        $this->output( "=== MW Drivel Defence Diagnostics ===\n\n" );
        
        // Check extension is loaded
        $this->output( "1. Extension Status:\n" );
        if ( class_exists( 'MediaWiki\\Extension\\MWDrivelDefence\\MWDrivelDefenceHooks' ) ) {
            $this->output( "   ✓ Extension classes loaded\n" );
        } else {
            $this->output( "   ✗ Extension classes NOT loaded\n" );
            return;
        }
        
        // Check configuration
        $this->output( "\n2. Configuration:\n" );
        $config = $this->getConfig();
        
        $autoAnalyze = $config->get( 'MWDrivelDefenceAutoAnalyze' );
        $this->output( "   Auto-analyze enabled: " . ( $autoAnalyze ? "✓ YES" : "✗ NO" ) . "\n" );
        
        $namespaces = $config->get( 'MWDrivelDefenceNamespaces' );
        $this->output( "   Allowed namespaces: " . implode( ', ', $namespaces ) . "\n" );
        
        $minWords = $config->get( 'MWDrivelDefenceMinWords' );
        $this->output( "   Minimum words: $minWords\n" );
        
        $maxSentence = $config->get( 'MWDrivelDefenceMaxSentenceLength' );
        $this->output( "   Max sentence length: $maxSentence\n" );
        
        // Check the specific page
        $this->output( "\n3. Page Analysis:\n" );
        $title = Title::newFromText( $pageTitle );
        
        if ( !$title || !$title->exists() ) {
            $this->output( "   ✗ Page '$pageTitle' does not exist\n" );
            return;
        }
        
        $this->output( "   Page title: " . $title->getFullText() . "\n" );
        $this->output( "   Namespace: " . $title->getNamespace() . " (" . $title->getNsText() . ")\n" );
        $this->output( "   Namespace allowed: " . ( in_array( $title->getNamespace(), $namespaces ) ? "✓ YES" : "✗ NO" ) . "\n" );
        $this->output( "   Is talk page: " . ( $title->isTalkPage() ? "✗ YES (skipped)" : "✓ NO" ) . "\n" );
        
        // Get page content
        $wikiPage = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $title );
        $content = $wikiPage->getContent();
        
        if ( !$content ) {
            $this->output( "   ✗ No content found\n" );
            return;
        }
        
        $text = \ContentHandler::getContentText( $content );
        $plainText = $this->stripWikiMarkup( $text );
        $wordCount = str_word_count( $plainText );
        
        $this->output( "   Raw content length: " . strlen( $text ) . " chars\n" );
        $this->output( "   Plain text length: " . strlen( $plainText ) . " chars\n" );
        $this->output( "   Word count: $wordCount\n" );
        $this->output( "   Meets minimum words: " . ( $wordCount >= $minWords ? "✓ YES" : "✗ NO" ) . "\n" );
        
        // Check talk page
        $this->output( "\n4. Talk Page:\n" );
        $talkTitle = $title->getTalkPage();
        
        if ( !$talkTitle || !$talkTitle->canExist() ) {
            $this->output( "   ✗ Talk page cannot exist\n" );
        } else {
            $this->output( "   Talk page title: " . $talkTitle->getFullText() . "\n" );
            $this->output( "   Talk page exists: " . ( $talkTitle->exists() ? "✓ YES" : "✗ NO" ) . "\n" );
        }
        
        // Simulate analysis
        $this->output( "\n5. Analysis Simulation:\n" );
        if ( $autoAnalyze && in_array( $title->getNamespace(), $namespaces ) && 
             !$title->isTalkPage() && $wordCount >= $minWords ) {
            
            $this->output( "   ✓ All conditions met for analysis\n" );
            
            // Perform actual analysis
            $sentences = $this->getSentences( $plainText );
            $totalSentences = count( $sentences );
            $avgWordsPerSentence = $totalSentences > 0 ? round( $wordCount / $totalSentences, 1 ) : 0;
            
            $longSentences = 0;
            foreach ( $sentences as $sentence ) {
                $sentenceWords = str_word_count( $sentence );
                if ( $sentenceWords > $maxSentence ) {
                    $longSentences++;
                }
            }
            
            $this->output( "   Total sentences: $totalSentences\n" );
            $this->output( "   Average words per sentence: $avgWordsPerSentence\n" );
            $this->output( "   Long sentences (>$maxSentence words): $longSentences\n" );
            
            $hasIssues = $longSentences > 0;
            $this->output( "   Has issues to report: " . ( $hasIssues ? "✓ YES" : "✗ NO" ) . "\n" );
            
            if ( !$hasIssues ) {
                $this->output( "\n   💡 TIP: Your page doesn't have issues that trigger posting!\n" );
                $this->output( "      Try adding a sentence with more than $maxSentence words.\n" );
            }
            
        } else {
            $this->output( "   ✗ Conditions NOT met for analysis\n" );
        }
        
        // Check hooks
        $this->output( "\n6. Hook Registration:\n" );
        $hookContainer = MediaWikiServices::getInstance()->getHookContainer();
        $handlers = $hookContainer->getHandlerDescriptions( 'PageContentSaveComplete' );
        
        $hookRegistered = false;
        foreach ( $handlers as $handler ) {
            if ( strpos( $handler, 'MWDrivelDefence' ) !== false ) {
                $this->output( "   ✓ Hook registered: $handler\n" );
                $hookRegistered = true;
            }
        }
        
        if ( !$hookRegistered ) {
            $this->output( "   ✗ PageContentSaveComplete hook NOT registered\n" );
        }
        
        $this->output( "\n=== Recommendations ===\n" );
        
        if ( !$autoAnalyze ) {
            $this->output( "• Add to LocalSettings.php: \$wgMWDrivelDefenceAutoAnalyze = true;\n" );
        }
        
        if ( !in_array( $title->getNamespace(), $namespaces ) ) {
            $this->output( "• Add namespace {$title->getNamespace()} to \$wgMWDrivelDefenceNamespaces\n" );
        }
        
        if ( $wordCount < $minWords ) {
            $this->output( "• Add more content (need $minWords+ words, have $wordCount)\n" );
        }
        
        if ( $longSentences == 0 ) {
            $this->output( "• Add sentences with more than $maxSentence words to trigger analysis\n" );
        }
    }
    
    private function stripWikiMarkup( $text ) {
        // Simple wiki markup stripping
        $text = preg_replace( '/\{\{[^}]*\}\}/', '', $text );
        $text = preg_replace( '/\[\[[^|\]]*\|([^\]]*)\]\]/', '$1', $text );
        $text = preg_replace( '/\[\[([^\]]*)\]\]/', '$1', $text );
        $text = preg_replace( '/\[http[^\s]+ ([^\]]*)\]/', '$1', $text );
        $text = preg_replace( '/<[^>]*>/', '', $text );
        $text = preg_replace( '/^[=]{1,6}([^=]+)[=]{1,6}$/m', '$1', $text );
        $text = preg_replace( '/^[*#:;]+ ?/m', '', $text );
        $text = preg_replace( "/'''([^']+)'''/", '$1', $text );
        $text = preg_replace( "/''([^']+)''/", '$1', $text );
        
        return trim( $text );
    }
    
    private function getSentences( $text ) {
        $sentences = preg_split( '/[.!?]+/', $text );
        $sentences = array_filter( array_map( 'trim', $sentences ) );
        return array_values( $sentences );
    }
}

$maintClass = MWDrivelDefenceDiagnostics::class;
require_once RUN_MAINTENANCE_IF_MAIN;