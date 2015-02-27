<?php
/**
 * A class that represent an abbreviation.
 */
class glimpse_HtmlAbbr implements glimpse_HtmlToken {
	
	public $abbr;
	public $definition;
	public $url;
	public $lang;
	public $hrefLang;
	protected $htmlTag;
	
	/**
	 * @param String $abbr
	 * @param String $definition
	 * @param String $url
	 * @param String $lang
	 */
	public function __construct($abbr, $definition, $url = null, $lang = null, $hrefLang = null) {
		$this->abbr = $abbr;
		$this->definition = $definition;
		$this->url = $url;
		$this->lang = $lang;
		$this->hrefLang = $hrefLang;
		$this->htmlTag = 'abbr';
	}
	
	/**
	 * @return String
	 */
	public function getExpression()	{
		return $this->abbr;
	}
	
	/**
	 * @return String
	 */
	public function getReplacement() {
		$abbrTag = '<' . $this->htmlTag . ' title="' . $this->definition . '"';
		if (! is_null ( $this->lang )) {
			$abbrTag .= ' xml:lang="' . $this->lang . '"';
		}
		$abbrTag .= '>' . $this->abbr . '</' . $this->htmlTag . '>';
		// Should we surround this abbreviation with a link?
		if ( ! is_null ( $this->url ) ) {
			$startLink = '<a href="' . $this->url . '"';
			if (! is_null ( $this->lang )) {
				$startLink .= ' xml:lang="' . $this->lang . '"';
			}
			if ( ! is_null ( $this->hrefLang ) ) {
				$startLink .= ' hreflang="' . $this->hrefLang . '"';
			}
			$startLink .= '>';
			$abbrTag = $startLink . $abbrTag . '</a>';
		}
		return $abbrTag;
	}
	
	/**
	 * @return Array<String>
	 */
	public function getTags() {
		return array ( new glimpse_HtmlTag( "abbr" ), new glimpse_HtmlTag( "acronym" ) );
	}
	
	/**
	 * @param Array<String,String> $parameters
	 * @return glimpse_HtmlAbbr
	 */
	public static function instanceFromRequestParameters($parameters) {
		return new glimpse_HtmlAbbr($parameters['expr'], $parameters['defn'], $parameters['link'], $parameters['lang'], $parameters['hrefLang']);
	}
	
	/**
	 * @return String
	 */
	public static function getLabel() {
		return __("Abbreviations", "html-enhancer");
	}
}
