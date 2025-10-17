/**
 * DrivelDefence MediaWiki Extension JavaScript
 * Adapted from the original DrivelDefence application
 * Integrates with MediaWiki environment
 */

( function () {
	'use strict';

	var DrivelDefence = {
		
		/**
		 * Initialize the extension
		 */
		init: function () {
			// Initialize special page functionality
			if ( mw.config.get( 'wgCanonicalSpecialPageName' ) === 'MWDrivelDefence' ) {
				this.initSpecialPage();
			}
			
			// Initialize inline checkers
			this.initInlineCheckers();
		},

		/**
		 * Initialize the special page
		 */
		initSpecialPage: function () {
			var analyzeButton = document.getElementById( 'mwdriveldefence-analyze' );
			var resetButton = document.getElementById( 'mwdriveldefence-reset' );
			var textArea = document.getElementById( 'mwdriveldefence-text' );

			if ( analyzeButton ) {
				analyzeButton.addEventListener( 'click', this.analyzeText.bind( this ) );
			}

			if ( resetButton ) {
				resetButton.addEventListener( 'click', this.resetForm.bind( this ) );
			}

			// Set placeholder text
			if ( textArea && !textArea.value ) {
				textArea.value = mw.msg( 'mwdriveldefence-text-placeholder' );
				textArea.addEventListener( 'focus', function () {
					if ( this.value === mw.msg( 'mwdriveldefence-text-placeholder' ) ) {
						this.value = '';
					}
				} );
			}
		},

		/**
		 * Initialize inline checkers
		 */
		initInlineCheckers: function () {
			var inlineButtons = document.querySelectorAll( '.driveldefence-inline-check' );
			
			for ( var i = 0; i < inlineButtons.length; i++ ) {
				inlineButtons[i].addEventListener( 'click', this.checkInlineText.bind( this ) );
			}
		},

		/**
		 * Analyze text from the main form
		 */
		analyzeText: function () {
			var textArea = document.getElementById( 'mwdriveldefence-text' );
			var resultsDiv = document.getElementById( 'mwdriveldefence-results' );
			var sentencesAll = document.getElementById( 'sentences-all' );
			var altWordsCheck = document.getElementById( 'alternative-words' );
			var wcagAnalysis = document.getElementById( 'wcag-analysis' );
			var readingLevel = document.getElementById( 'reading-level' );
			var dyslexiaFriendly = document.getElementById( 'dyslexia-friendly' );
			var screenReader = document.getElementById( 'screen-reader' );

			if ( !textArea || !resultsDiv ) {
				return;
			}

			var text = textArea.value.trim();
			var placeholder = mw.msg( 'mwdriveldefence-text-placeholder' );
			
			if ( !text || text === placeholder ) {
				mw.notify( mw.msg( 'mwdriveldefence-no-text-error' ), { type: 'error' } );
				return;
			}

			// Clean text
			text = stripNonPrintables( text );
			
			// Get sentences
			var sentences = [];
			getSentences( sentences, text );

			// Generate report with accessibility analysis
			var reportHtml = this.generateReport( 
				sentences, 
				text, 
				sentencesAll ? sentencesAll.checked : true,
				altWordsCheck ? altWordsCheck.checked : true,
				false, // isInline
				wcagAnalysis ? wcagAnalysis.checked : true,
				readingLevel ? readingLevel.checked : true,
				dyslexiaFriendly ? dyslexiaFriendly.checked : true,
				screenReader ? screenReader.checked : true
			);

			resultsDiv.innerHTML = reportHtml;
			resultsDiv.classList.add( 'show' );
			
			// Scroll to results
			resultsDiv.scrollIntoView( { behavior: 'smooth' } );
		},

		/**
		 * Check inline text
		 */
		checkInlineText: function ( event ) {
			var button = event.target;
			var targetId = button.getAttribute( 'data-target' );
			var container = document.getElementById( targetId );
			
			if ( !container ) {
				return;
			}

			var textDiv = container.querySelector( '.driveldefence-inline-text' );
			var resultsDiv = container.querySelector( '.driveldefence-inline-results' );

			if ( !textDiv || !resultsDiv ) {
				return;
			}

			var text = textDiv.textContent.trim();
			
			if ( !text ) {
				mw.notify( mw.msg( 'driveldefence-no-text-error' ), { type: 'error' } );
				return;
			}

			// Clean text
			text = stripNonPrintables( text );
			
			// Get sentences
			var sentences = [];
			getSentences( sentences, text );

			// Generate compact report
			var reportHtml = this.generateReport( sentences, text, true, true, true );

			resultsDiv.innerHTML = reportHtml;
			resultsDiv.style.display = 'block';
		},

		/**
		 * Reset the form
		 */
		resetForm: function () {
			var textArea = document.getElementById( 'mwdriveldefence-text' );
			var resultsDiv = document.getElementById( 'mwdriveldefence-results' );
			
			if ( textArea ) {
				textArea.value = mw.msg( 'mwdriveldefence-text-placeholder' );
			}
			
			if ( resultsDiv ) {
				resultsDiv.innerHTML = '';
				resultsDiv.classList.remove( 'show' );
			}
		},

		/**
		 * Generate HTML report
		 */
		generateReport: function ( sentences, originalText, showAllSentences, checkAlternatives, isInline, wcagAnalysis, readingLevel, dyslexiaFriendly, screenReader ) {
			var html = '';
			var now = new Date();
			
			if ( !isInline ) {
				html += '<h2>' + mw.msg( 'mwdriveldefence-report-title' ) + '</h2>';
				html += '<p><em>Analysis completed on ' + now.toUTCString() + '</em></p>';
			}

			// WCAG Accessibility Analysis (if enabled)
			if ( wcagAnalysis && !isInline ) {
				var accessibilityResults = performWCAGAnalysis( originalText, document.documentElement.innerHTML );
				html += generateAccessibilityReport( accessibilityResults );
			}

			// Summary
			var summary = this.generateSummary( sentences, originalText, showAllSentences, checkAlternatives );
			html += summary.html;

			// Detailed analysis
			if ( !isInline ) {
				html += '<h3>' + mw.msg( 'mwdriveldefence-detailed-analysis' ) + '</h3>';
			}
			
			html += this.generateDetailedAnalysis( sentences, showAllSentences, checkAlternatives );

			if ( !isInline ) {
				html += '<p><em>' + mw.msg( 'mwdriveldefence-end-of-report' ) + '</em></p>';
			}

			return html;
		},

		/**
		 * Generate summary section
		 */
		generateSummary: function ( sentences, originalText, showAllSentences, checkAlternatives ) {
			var totalWords = countWords( originalText );
			var totalSentences = sentences.length;
			var avgWordsPerSentence = totalSentences > 0 ? Math.round( totalWords / totalSentences * 10 ) / 10 : 0;
			var longSentences = 0;
			var alternativeSuggestions = 0;

			// Count long sentences and alternative suggestions
			for ( var i = 0; i < sentences.length; i++ ) {
				var wordCount = countWords( sentences[i] );
				if ( wordCount >= 20 ) {
					longSentences++;
				}
				
				if ( checkAlternatives ) {
					alternativeSuggestions += this.countAlternativeSuggestions( sentences[i] );
				}
			}

			var html = '<div class="driveldefence-summary">';
			html += '<h3>' + mw.msg( 'driveldefence-summary-title' ) + '</h3>';
			html += '<ul>';
			html += '<li>' + mw.msg( 'driveldefence-word-count' ) + ' ' + totalWords + '</li>';
			html += '<li>' + mw.msg( 'driveldefence-sentence-count' ) + ' ' + totalSentences + '</li>';
			html += '<li>' + mw.msg( 'driveldefence-avg-words-per-sentence' ) + ' ' + avgWordsPerSentence + '</li>';
			html += '<li>' + mw.msg( 'driveldefence-sentences-over-20' ) + ' ' + longSentences + '</li>';
			
			if ( checkAlternatives ) {
				html += '<li>' + mw.msg( 'driveldefence-alternative-suggestions' ) + ' ' + alternativeSuggestions + '</li>';
			}
			
			html += '</ul>';
			html += '</div>';

			return { html: html, longSentences: longSentences, alternativeSuggestions: alternativeSuggestions };
		},

		/**
		 * Generate detailed analysis
		 */
		generateDetailedAnalysis: function ( sentences, showAllSentences, checkAlternatives ) {
			var html = '';

			for ( var i = 0; i < sentences.length; i++ ) {
				var sentence = sentences[i];
				var wordCount = countWords( sentence );
				var isLongSentence = wordCount >= 20;

				// Skip short sentences if only showing long ones
				if ( !showAllSentences && !isLongSentence ) {
					continue;
				}

				html += '<div class="driveldefence-sentence">';
				html += '<span class="driveldefence-word-count">[' + wordCount + ' words]</span>';
				
				if ( checkAlternatives ) {
					html += this.highlightAlternatives( sentence );
				} else {
					html += mw.html.escape( sentence );
				}
				
				html += '</div>';
			}

			return html;
		},

		/**
		 * Count alternative word suggestions in a sentence
		 */
		countAlternativeSuggestions: function ( sentence ) {
			var count = 0;
			for ( var i = 0; i < bg.itemCount; i++ ) {
				var badWord = bg.table[i].szBad.toLowerCase();
				var regex = new RegExp( '\\b' + badWord.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\b', 'gi' );
				var matches = sentence.match( regex );
				if ( matches ) {
					count += matches.length;
				}
			}
			return count;
		},

		/**
		 * Highlight words with alternatives
		 */
		highlightAlternatives: function ( sentence ) {
			var result = mw.html.escape( sentence );
			
			for ( var i = 0; i < bg.itemCount; i++ ) {
				var badWord = bg.table[i].szBad;
				var goodWord = bg.table[i].szGood;
				var regex = new RegExp( '\\b(' + badWord.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')\\b', 'gi' );
				
				result = result.replace( regex, function( match ) {
					return '<span class="driveldefence-alternative" title="Alternative: ' + 
						mw.html.escape( goodWord ) + '">' + match + '</span>';
				} );
			}
			
			return result;
		}
	};

	// Initialize when DOM is ready
	$( function () {
		DrivelDefence.init();
	} );

	// Export for global access
	window.DrivelDefence = DrivelDefence;

}() );
