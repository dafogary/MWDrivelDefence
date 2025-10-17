<?php

namespace MediaWiki\Extension\MWDrivelDefence;

use Html;
use SpecialPage;

/**
 * Special page for MWDrivel Defence plain English checker
 */
class SpecialMWDrivelDefence extends SpecialPage {

	public function __construct() {
		parent::__construct( 'MWDrivelDefence' );
	}

	/**
	 * @inheritDoc
	 */
	public function execute( $subPage ) {
		$this->setHeaders();
		$this->checkPermissions();

		$out = $this->getOutput();
		$out->addModules( 'ext.mwdriveldefence' );
		$out->addModuleStyles( 'ext.mwdriveldefence' );

		$out->setPageTitle( $this->msg( 'mwdriveldefence-title' )->text() );

		$this->showForm();
	}

	/**
	 * Show the main form
	 */
	private function showForm() {
		$out = $this->getOutput();

		$html = Html::openElement( 'div', [ 'class' => 'mwdriveldefence-container' ] );
		
		// Header with logo
		$html .= Html::element( 'h1', [ 'class' => 'mwdriveldefence-title' ],
			$this->msg( 'mwdriveldefence-title' )->text()
		);

		// Main form
		$html .= Html::openElement( 'form', [
			'id' => 'mwdriveldefence-form',
			'class' => 'mwdriveldefence-form'
		] );

		// Text input area
		$html .= Html::element( 'label', [
			'for' => 'mwdriveldefence-text',
			'class' => 'mwdriveldefence-label'
		], $this->msg( 'mwdriveldefence-text-label' )->text() );

		$html .= Html::element( 'textarea', [
			'id' => 'mwdriveldefence-text',
			'name' => 'text',
			'class' => 'mwdriveldefence-textarea',
			'rows' => 15,
			'cols' => 80,
			'placeholder' => $this->msg( 'mwdriveldefence-text-placeholder' )->text()
		] );

		// Options
		$html .= Html::openElement( 'div', [ 'class' => 'mwdriveldefence-options' ] );

		// Sentence reporting options
		$html .= Html::element( 'h3', [], $this->msg( 'mwdriveldefence-options-title' )->text() );

		$html .= Html::openElement( 'fieldset', [ 'class' => 'mwdriveldefence-fieldset' ] );
		$html .= Html::element( 'legend', [], $this->msg( 'mwdriveldefence-sentence-options' )->text() );

		$html .= Html::radio( 'sentences', false, [
			'id' => 'sentences-all',
			'value' => 'all',
			'checked' => true
		] );
		$html .= Html::element( 'label', [ 'for' => 'sentences-all' ],
			$this->msg( 'mwdriveldefence-sentences-all' )->text()
		);

		$html .= Html::element( 'br' );

		$html .= Html::radio( 'sentences', false, [
			'id' => 'sentences-long',
			'value' => 'long'
		] );
		$html .= Html::element( 'label', [ 'for' => 'sentences-long' ],
			$this->msg( 'mwdriveldefence-sentences-long' )->text()
		);

		$html .= Html::closeElement( 'fieldset' );

		// Alternative words option
		$html .= Html::check( 'alternative-words', true, [
			'id' => 'alternative-words',
			'value' => '1'
		] );
		$html .= Html::element( 'label', [ 'for' => 'alternative-words' ],
			$this->msg( 'mwdriveldefence-alternative-words' )->text()
		);

		// WCAG Accessibility Analysis Options
		$html .= Html::openElement( 'fieldset', [ 'class' => 'mwdriveldefence-fieldset' ] );
		$html .= Html::element( 'legend', [], $this->msg( 'mwdriveldefence-accessibility-options' )->text() );

		$html .= Html::check( 'wcag-analysis', true, [
			'id' => 'wcag-analysis',
			'value' => '1'
		] );
		$html .= Html::element( 'label', [ 'for' => 'wcag-analysis' ],
			$this->msg( 'mwdriveldefence-wcag-analysis' )->text()
		);

		$html .= Html::element( 'br' );

		$html .= Html::check( 'reading-level', true, [
			'id' => 'reading-level',
			'value' => '1'
		] );
		$html .= Html::element( 'label', [ 'for' => 'reading-level' ],
			$this->msg( 'mwdriveldefence-reading-level' )->text()
		);

		$html .= Html::element( 'br' );

		$html .= Html::check( 'dyslexia-friendly', true, [
			'id' => 'dyslexia-friendly',
			'value' => '1'
		] );
		$html .= Html::element( 'label', [ 'for' => 'dyslexia-friendly' ],
			$this->msg( 'mwdriveldefence-dyslexia-friendly' )->text()
		);

		$html .= Html::element( 'br' );

		$html .= Html::check( 'screen-reader', true, [
			'id' => 'screen-reader',
			'value' => '1'
		] );
		$html .= Html::element( 'label', [ 'for' => 'screen-reader' ],
			$this->msg( 'mwdriveldefence-screen-reader' )->text()
		);

		$html .= Html::closeElement( 'fieldset' );

		$html .= Html::closeElement( 'div' );

		// Buttons
		$html .= Html::openElement( 'div', [ 'class' => 'mwdriveldefence-buttons' ] );

		$html .= Html::element( 'button', [
			'type' => 'button',
			'id' => 'mwdriveldefence-analyze',
			'class' => 'mwdriveldefence-button mwdriveldefence-button-primary'
		], $this->msg( 'mwdriveldefence-analyze-button' )->text() );

		$html .= Html::element( 'button', [
			'type' => 'button',
			'id' => 'mwdriveldefence-reset',
			'class' => 'mwdriveldefence-button mwdriveldefence-button-secondary'
		], $this->msg( 'mwdriveldefence-reset-button' )->text() );

		$html .= Html::closeElement( 'div' );

		$html .= Html::closeElement( 'form' );

		// Results area
		$html .= Html::element( 'div', [
			'id' => 'mwdriveldefence-results',
			'class' => 'mwdriveldefence-results'
		] );

		$html .= Html::closeElement( 'div' );

		// Footer
		$html .= Html::openElement( 'div', [ 'class' => 'mwdriveldefence-footer' ] );
		
		// Build footer with proper links
		$copyrightText = 'Original Drivel Defence © 2006 J Rugg | A to Z of Alternative Words © 2001 ';
		$copyrightText .= Html::element( 'a', [
			'href' => 'http://www.plainenglish.co.uk/',
			'target' => '_blank',
			'title' => 'Opens a new browser window.'
		], 'Plain English Campaign' );
		$copyrightText .= ' | MW Drivel Defence developed by ';
		$copyrightText .= Html::element( 'a', [
			'href' => 'https://dafocreative.com',
			'target' => '_blank'
		], 'DAFO Creative Ltd' );
		$copyrightText .= ' | WCAG 2.2 AA accessibility analysis included';
		
		$html .= Html::rawElement( 'p', [ 'class' => 'mwdriveldefence-copyright' ], $copyrightText );
		
		// PayPal Donation Button
		$html .= Html::openElement( 'div', [ 'class' => 'mwdriveldefence-donation', 'style' => 'text-align: center; margin-top: 15px;' ] );
		$html .= Html::rawElement( 'p', [ 'style' => 'font-size: 0.9em; margin-bottom: 10px;' ], 
			'If you find this extension useful, please consider supporting its development:' );
		
		// PayPal donation form
		$donationForm = Html::openElement( 'form', [
			'action' => 'https://www.paypal.com/donate',
			'method' => 'post',
			'target' => '_top'
		] );
		$donationForm .= Html::element( 'input', [
			'type' => 'hidden',
			'name' => 'hosted_button_id',
			'value' => 'JJQRURDMD55J6'
		] );
		$donationForm .= Html::element( 'input', [
			'type' => 'image',
			'src' => 'https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif',
			'border' => '0',
			'name' => 'submit',
			'title' => 'PayPal - The safer, easier way to pay online!',
			'alt' => 'Donate with PayPal button'
		] );
		$donationForm .= Html::element( 'img', [
			'alt' => '',
			'border' => '0',
			'src' => 'https://www.paypal.com/en_GB/i/scr/pixel.gif',
			'width' => '1',
			'height' => '1'
		] );
		$donationForm .= Html::closeElement( 'form' );
		
		$html .= $donationForm;
		$html .= Html::closeElement( 'div' );
		
		$html .= Html::closeElement( 'div' );

		$out->addHTML( $html );
	}

	/**
	 * @inheritDoc
	 */
	public function getGroupName() {
		return 'wiki';
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription() {
		return $this->msg( 'mwdriveldefence-desc' );
	}
}
