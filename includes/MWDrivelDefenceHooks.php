<?php

namespace MediaWiki\Extension\MWDrivelDefence;

use Parser;
use PPFrame;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserIdentity;
use MediaWiki\Storage\EditResult;
use WikiPage;
use User;
use CommentStoreComment;
use MediaWiki\MediaWikiServices;
use Title;
use ContentHandler;

/**
 * Hooks for MWDrivelDefence extension
 */
class MWDrivelDefenceHooks {

	/**
	 * Register parser functions
	 *
	 * @param Parser $parser
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setFunctionHook( 'drivelcheck', [ self::class, 'renderDrivelCheck' ] );
	}

	/**
	 * Hook handler for PageContentSaveComplete
	 * Automatically analyzes pages when they are saved
	 *
	 * @param WikiPage $wikiPage
	 * @param UserIdentity $user
	 * @param string $summary
	 * @param int $flags
	 * @param RevisionRecord $revisionRecord
	 * @param EditResult $editResult
	 * @param int $baseRevId
	 */
	public static function onPageContentSaveComplete(
		WikiPage $wikiPage,
		UserIdentity $user,
		string $summary,
		int $flags,
		RevisionRecord $revisionRecord,
		EditResult $editResult,
		int $baseRevId
	): void {
		// Debug logging
		error_log( "MW Drivel Defence: Hook called for page " . $wikiPage->getTitle()->getFullText() );
		
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'main' );
		
		// Check if auto-analysis is enabled
		if ( !$config->get( 'MWDrivelDefenceAutoAnalyze' ) ) {
			return;
		}

		$title = $wikiPage->getTitle();
		
		// Check if this namespace should be analyzed
		$allowedNamespaces = $config->get( 'MWDrivelDefenceNamespaces' );
		if ( !in_array( $title->getNamespace(), $allowedNamespaces ) ) {
			return;
		}

		// Don't analyze talk pages themselves
		if ( $title->isTalkPage() ) {
			return;
		}

		// Get the page content from the revision
		$content = $revisionRecord->getContent( SlotRecord::MAIN );
		if ( !$content ) {
			return;
		}

		$text = ContentHandler::getContentText( $content );
		if ( !$text ) {
			return;
		}

		// Remove wiki markup for analysis
		$plainText = self::stripWikiMarkup( $text );
		$wordCount = self::countWords( $plainText );
		
		// Check minimum word count
		$minWords = $config->get( 'MWDrivelDefenceMinWords' );
		if ( $wordCount < $minWords ) {
			return;
		}

		// Perform analysis
		$analysis = self::analyzeText( $plainText, $config );
		
		// Only post if there are issues to report
		if ( $analysis['hasIssues'] ) {
			self::postToTalkPage( $wikiPage, $analysis, $user );
		}
	}

	/**
	 * Render the drivelcheck parser function
	 *
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @param array $args
	 * @return string
	 */
	public static function renderDrivelCheck( Parser $parser, PPFrame $frame, array $args ) {
		$text = $args[0] ?? '';
		$text = $parser->recursiveTagParse( $text, $frame );

		if ( empty( trim( $text ) ) ) {
			return '<div class="mwdriveldefence-inline-empty">' .
				wfMessage( 'mwdriveldefence-inline-empty' )->escaped() .
				'</div>';
		}

		$parser->getOutput()->addModules( 'ext.mwdriveldefence' );

		$uniqueId = 'drivelcheck-' . wfRandomString( 8 );

		return '<div class="mwdriveldefence-inline" id="' . $uniqueId . '">' .
			'<div class="mwdriveldefence-inline-text">' . htmlspecialchars( $text ) . '</div>' .
			'<button type="button" class="mwdriveldefence-inline-check" data-target="' . $uniqueId . '">' .
			wfMessage( 'mwdriveldefence-check-text' )->escaped() .
			'</button>' .
			'<div class="mwdriveldefence-inline-results" style="display: none;"></div>' .
			'</div>';
	}

	/**
	 * Strip wiki markup from text for analysis
	 *
	 * @param string $text
	 * @return string
	 */
	private static function stripWikiMarkup( $text ) {
		// Remove templates, links, and other markup
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

	/**
	 * Count words in text
	 *
	 * @param string $text
	 * @return int
	 */
	private static function countWords( $text ) {
		return str_word_count( $text );
	}

	/**
	 * Analyze text for plain English and accessibility issues
	 *
	 * @param string $text
	 * @param \Config $config
	 * @return array
	 */
	private static function analyzeText( $text, $config ) {
		$sentences = self::getSentences( $text );
		$maxSentenceLength = $config->get( 'MWDrivelDefenceMaxSentenceLength' );
		
		$totalWords = self::countWords( $text );
		$totalSentences = count( $sentences );
		$avgWordsPerSentence = $totalSentences > 0 ? round( $totalWords / $totalSentences, 1 ) : 0;
		
		$longSentences = 0;
		$alternativeWords = 0;
		
		foreach ( $sentences as $sentence ) {
			$wordCount = self::countWords( $sentence );
			if ( $wordCount > $maxSentenceLength ) {
				$longSentences++;
			}
			$alternativeWords += self::countAlternativeWords( $sentence );
		}
		
		// Accessibility analysis
		$accessibilityAnalysis = self::performAccessibilityAnalysis( $text );
		
		$hasIssues = $longSentences > 0 || $alternativeWords > 0 || $accessibilityAnalysis['hasAccessibilityIssues'];
		
		return [
			'hasIssues' => $hasIssues,
			'totalWords' => $totalWords,
			'totalSentences' => $totalSentences,
			'avgWordsPerSentence' => $avgWordsPerSentence,
			'longSentences' => $longSentences,
			'alternativeWords' => $alternativeWords,
			'maxSentenceLength' => $maxSentenceLength,
			'accessibility' => $accessibilityAnalysis
		];
	}

	/**
	 * Get sentences from text
	 *
	 * @param string $text
	 * @return array
	 */
	private static function getSentences( $text ) {
		// Simple sentence splitting - could be improved
		$sentences = preg_split( '/[.!?]+/', $text );
		$sentences = array_filter( array_map( 'trim', $sentences ) );
		return array_values( $sentences );
	}

	/**
	 * Count words that have alternatives (simplified version)
	 *
	 * @param string $text
	 * @return int
	 */
	private static function countAlternativeWords( $text ) {
		// Common words that could be simplified (subset of the full database)
		$problematicWords = [
			'accomplish' => 'do',
			'additional' => 'extra',
			'advantageous' => 'helpful',
			'alternative' => 'other',
			'approximately' => 'about',
			'commence' => 'start',
			'demonstrate' => 'show',
			'facilitate' => 'help',
			'implement' => 'carry out',
			'indicate' => 'show',
			'initiate' => 'start',
			'maintain' => 'keep',
			'necessitate' => 'need',
			'participate' => 'take part',
			'require' => 'need',
			'sufficient' => 'enough',
			'terminate' => 'end',
			'utilize' => 'use'
		];
		
		$count = 0;
		$words = str_word_count( strtolower( $text ), 1 );
		
		foreach ( $words as $word ) {
			if ( isset( $problematicWords[$word] ) ) {
				$count++;
			}
		}
		
		return $count;
	}

	/**
	 * Perform accessibility analysis on text
	 *
	 * @param string $text
	 * @return array
	 */
	private static function performAccessibilityAnalysis( $text ) {
		// Reading Level Analysis
		$readingLevel = self::calculateReadingLevel( $text );
		
		// Dyslexia-friendly analysis
		$dyslexiaAnalysis = self::analyzeDyslexiaFriendliness( $text );
		
		// Calculate overall accessibility score
		$accessibilityScore = self::calculateAccessibilityScore( $readingLevel, $dyslexiaAnalysis );
		
		$hasAccessibilityIssues = (
			$readingLevel['fleschKincaid'] > 8 ||
			$readingLevel['fleschReadingEase'] < 60 ||
			$dyslexiaAnalysis['longParagraphs'] > 0 ||
			$dyslexiaAnalysis['complexSentences'] > 2
		);
		
		return [
			'hasAccessibilityIssues' => $hasAccessibilityIssues,
			'readingLevel' => $readingLevel,
			'dyslexiaAnalysis' => $dyslexiaAnalysis,
			'accessibilityScore' => $accessibilityScore
		];
	}

	/**
	 * Calculate reading level metrics
	 *
	 * @param string $text
	 * @return array
	 */
	private static function calculateReadingLevel( $text ) {
		$sentences = self::getSentences( $text );
		$words = str_word_count( $text, 1 );
		$syllables = self::countSyllables( $text );
		
		$sentenceCount = count( $sentences );
		$wordCount = count( $words );
		
		if ( $sentenceCount === 0 || $wordCount === 0 ) {
			return [
				'fleschKincaid' => 0,
				'fleschReadingEase' => 100,
				'gunningFog' => 0
			];
		}
		
		$avgSentenceLength = $wordCount / $sentenceCount;
		$avgSyllablesPerWord = $syllables / $wordCount;
		
		// Flesch-Kincaid Grade Level
		$fleschKincaid = (0.39 * $avgSentenceLength) + (11.8 * $avgSyllablesPerWord) - 15.59;
		
		// Flesch Reading Ease
		$fleschReadingEase = 206.835 - (1.015 * $avgSentenceLength) - (84.6 * $avgSyllablesPerWord);
		
		// Gunning Fog Index (simplified)
		$complexWords = self::countComplexWords( $words );
		$percentComplexWords = ($complexWords / $wordCount) * 100;
		$gunningFog = 0.4 * ($avgSentenceLength + $percentComplexWords);
		
		return [
			'fleschKincaid' => round( max( 0, $fleschKincaid ), 1 ),
			'fleschReadingEase' => round( max( 0, min( 100, $fleschReadingEase ) ), 1 ),
			'gunningFog' => round( max( 0, $gunningFog ), 1 )
		];
	}

	/**
	 * Count syllables in text
	 *
	 * @param string $text
	 * @return int
	 */
	private static function countSyllables( $text ) {
		$words = str_word_count( strtolower( $text ), 1 );
		$totalSyllables = 0;
		
		foreach ( $words as $word ) {
			$syllables = preg_match_all( '/[aeiouy]+/', $word );
			if ( substr( $word, -1 ) === 'e' ) {
				$syllables--;
			}
			$totalSyllables += max( 1, $syllables );
		}
		
		return $totalSyllables;
	}

	/**
	 * Count complex words (3+ syllables)
	 *
	 * @param array $words
	 * @return int
	 */
	private static function countComplexWords( $words ) {
		$complexCount = 0;
		
		foreach ( $words as $word ) {
			$syllables = preg_match_all( '/[aeiouy]+/', strtolower( $word ) );
			if ( substr( strtolower( $word ), -1 ) === 'e' ) {
				$syllables--;
			}
			if ( max( 1, $syllables ) >= 3 ) {
				$complexCount++;
			}
		}
		
		return $complexCount;
	}

	/**
	 * Analyze dyslexia-friendliness of text
	 *
	 * @param string $text
	 * @return array
	 */
	private static function analyzeDyslexiaFriendliness( $text ) {
		$paragraphs = preg_split( '/\n\s*\n/', trim( $text ) );
		$sentences = self::getSentences( $text );
		
		$longParagraphs = 0;
		$complexSentences = 0;
		
		// Check paragraph lengths
		foreach ( $paragraphs as $paragraph ) {
			$paraWords = str_word_count( $paragraph );
			if ( $paraWords > 40 ) {
				$longParagraphs++;
			}
		}
		
		// Check sentence complexity
		foreach ( $sentences as $sentence ) {
			$sentenceWords = str_word_count( $sentence );
			$syllables = self::countSyllables( $sentence );
			$avgSyllablesPerWord = $sentenceWords > 0 ? $syllables / $sentenceWords : 0;
			
			if ( $sentenceWords > 20 || $avgSyllablesPerWord > 1.5 ) {
				$complexSentences++;
			}
		}
		
		return [
			'longParagraphs' => $longParagraphs,
			'complexSentences' => $complexSentences,
			'totalParagraphs' => count( $paragraphs ),
			'totalSentences' => count( $sentences )
		];
	}

	/**
	 * Calculate overall accessibility score
	 *
	 * @param array $readingLevel
	 * @param array $dyslexiaAnalysis
	 * @return int
	 */
	private static function calculateAccessibilityScore( $readingLevel, $dyslexiaAnalysis ) {
		$score = 100;
		
		// Reading level penalties
		if ( $readingLevel['fleschKincaid'] > 8 ) {
			$score -= min( 25, ( $readingLevel['fleschKincaid'] - 8 ) * 3 );
		}
		
		if ( $readingLevel['fleschReadingEase'] < 60 ) {
			$score -= min( 25, ( 60 - $readingLevel['fleschReadingEase'] ) / 2 );
		}
		
		// Dyslexia penalties
		$score -= min( 25, $dyslexiaAnalysis['longParagraphs'] * 5 );
		$score -= min( 25, $dyslexiaAnalysis['complexSentences'] * 2 );
		
		return max( 0, min( 100, round( $score ) ) );
	}

	/**
	 * Post analysis results to talk page
	 *
	 * @param WikiPage $wikiPage
	 * @param array $analysis
	 * @param User $user
	 */
	private static function postToTalkPage( WikiPage $wikiPage, array $analysis, User $user ) {
		$title = $wikiPage->getTitle();
		$talkTitle = $title->getTalkPage();
		
		if ( !$talkTitle || !$talkTitle->canExist() ) {
			return;
		}

		// Create the analysis summary
		$timestamp = wfTimestamp( TS_RFC2822 );
		$topicTitle = wfMessage( 'mwdriveldefence-talk-topic-title', $timestamp )->text();
		
		// Generate recommendations
		$recommendations = self::generateRecommendations( $analysis );
		
		// Generate accessibility summary
		$accessibilitySummary = '';
		if ( isset( $analysis['accessibility'] ) ) {
			$accessibilitySummary = self::generateAccessibilitySummary( $analysis['accessibility'] );
		}
		
		$analysisText = wfMessage( 'mwdriveldefence-talk-analysis-details',
			$analysis['totalWords'],
			$analysis['totalSentences'],
			$analysis['avgWordsPerSentence'],
			$analysis['maxSentenceLength'],
			$analysis['longSentences'],
			$analysis['alternativeWords'],
			$recommendations
		)->text();

		// Combine plain English and accessibility analysis
		$fullAnalysis = $analysisText;
		if ( $accessibilitySummary ) {
			$fullAnalysis .= "\n\n" . $accessibilitySummary;
		}

		$sectionContent = wfMessage( 'mwdriveldefence-talk-analysis-summary', $timestamp )->text() . $fullAnalysis;

		// Get or create the talk page
		$talkPage = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $talkTitle );
		
		try {
			// Check if talk page exists, if not create it
			if ( !$talkPage->exists() ) {
				$welcomeText = "== Welcome ==\n\nThis is the talk page for discussing improvements to the [[$title]] article.\n\n";
				$welcomeStatus = $talkPage->doUserEditContent(
					ContentHandler::makeContent( $welcomeText, $talkTitle ),
					$user,
					'Created talk page',
					EDIT_NEW
				);
				
				if ( !$welcomeStatus->isOK() ) {
					wfDebugLog( 'MWDrivelDefence', 'Failed to create talk page: ' . $welcomeStatus->getWikiText() );
					return;
				}
			}
			
			// Add new section to talk page
			$status = $talkPage->doUserEditContent(
				ContentHandler::makeContent( $sectionContent, $talkTitle ),
				$user,
				wfMessage( 'mwdriveldefence-talk-topic-title', $timestamp )->text(),
				EDIT_APPEND,
				false,
				[],
				$topicTitle
			);
			
			if ( !$status->isOK() ) {
				wfDebugLog( 'MWDrivelDefence', 'Failed to add talk page section: ' . $status->getWikiText() );
			}
		} catch ( Exception $e ) {
			wfDebugLog( 'MWDrivelDefence', 'Exception adding talk page section: ' . $e->getMessage() );
		}
	}

	/**
	 * Generate recommendations based on analysis
	 *
	 * @param array $analysis
	 * @return string
	 */
	private static function generateRecommendations( array $analysis ) {
		$recommendations = [];
		
		if ( $analysis['longSentences'] > 0 && $analysis['alternativeWords'] > 0 ) {
			$recommendations[] = wfMessage( 'mwdriveldefence-talk-both-issues',
				$analysis['longSentences'],
				$analysis['alternativeWords']
			)->text();
		} elseif ( $analysis['longSentences'] > 0 ) {
			$recommendations[] = wfMessage( 'mwdriveldefence-talk-sentence-issues',
				$analysis['longSentences'],
				$analysis['maxSentenceLength']
			)->text();
		} elseif ( $analysis['alternativeWords'] > 0 ) {
			$recommendations[] = wfMessage( 'mwdriveldefence-talk-word-issues',
				$analysis['alternativeWords']
			)->text();
		}
		
		if ( empty( $recommendations ) ) {
			$recommendations[] = wfMessage( 'mwdriveldefence-talk-no-issues' )->text();
		}
		
		return implode( "\n", $recommendations );
	}

	/**
	 * Generate accessibility analysis summary for talk page
	 *
	 * @param array $accessibility
	 * @return string
	 */
	private static function generateAccessibilitySummary( array $accessibility ) {
		$summary = "'''WCAG 2.2 AA Accessibility Analysis:'''\n";
		$summary .= "* Overall Accessibility Score: {$accessibility['accessibilityScore']}/100\n";
		
		// Reading Level Analysis
		$readingLevel = $accessibility['readingLevel'];
		$summary .= "* Reading Level:\n";
		$summary .= "** Flesch-Kincaid Grade Level: {$readingLevel['fleschKincaid']} (target: 8 or below)\n";
		$summary .= "** Flesch Reading Ease: {$readingLevel['fleschReadingEase']} (target: 60 or above)\n";
		$summary .= "** Gunning Fog Index: {$readingLevel['gunningFog']} (target: 12 or below)\n";
		
		// Dyslexia-Friendly Analysis
		$dyslexia = $accessibility['dyslexiaAnalysis'];
		$summary .= "* Dyslexia-Friendly Analysis:\n";
		$summary .= "** Long paragraphs (40+ words): {$dyslexia['longParagraphs']}/{$dyslexia['totalParagraphs']}\n";
		$summary .= "** Complex sentences: {$dyslexia['complexSentences']}/{$dyslexia['totalSentences']}\n";
		
		// Accessibility Recommendations
		$accessibilityRecommendations = [];
		
		if ( $readingLevel['fleschKincaid'] > 8 ) {
			$accessibilityRecommendations[] = "• Simplify language to reduce reading grade level";
		}
		
		if ( $readingLevel['fleschReadingEase'] < 60 ) {
			$accessibilityRecommendations[] = "• Use shorter sentences and simpler words to improve readability";
		}
		
		if ( $dyslexia['longParagraphs'] > 0 ) {
			$accessibilityRecommendations[] = "• Break long paragraphs into shorter sections (3-4 sentences max)";
		}
		
		if ( $dyslexia['complexSentences'] > 2 ) {
			$accessibilityRecommendations[] = "• Simplify complex sentences for dyslexia-friendly reading";
		}
		
		if ( $accessibility['accessibilityScore'] >= 80 ) {
			$accessibilityRecommendations[] = "✓ Content meets WCAG 2.2 AA accessibility standards";
		} elseif ( $accessibility['accessibilityScore'] >= 60 ) {
			$accessibilityRecommendations[] = "⚠ Content partially meets accessibility standards - improvements recommended";
		} else {
			$accessibilityRecommendations[] = "✗ Content needs significant accessibility improvements";
		}
		
		if ( !empty( $accessibilityRecommendations ) ) {
			$summary .= "\n'''Accessibility Recommendations:'''\n";
			$summary .= implode( "\n", $accessibilityRecommendations ) . "\n";
		}
		
		$summary .= "\n''This accessibility analysis helps ensure content is readable by users with disabilities, including those who use screen readers or have dyslexia.''";
		
		return $summary;
	}
}
