<?php
/**
 * A class that represent an acronym.
 */
class glimpse_HtmlAcronym extends glimpse_HtmlAbbr {
	
	/**
	 * @param String $abbr
	 * @param String $definition
	 * @param String $url
	 * @param String $lang
	 */
	public function __construct($abbr, $definition, $url = null, $lang = null, $hrefLang = null) {
		parent::__construct($abbr, $definition, $url, $lang, $hrefLang);
		$this->htmlTag = 'acronym';
	}
	
	/**
	 * @param Array<String,String> $parameters
	 * @return glimpse_HtmlAcronym
	 */
	public static function instanceFromRequestParameters($parameters) {
		return new glimpse_HtmlAcronym($parameters['expr'], $parameters['defn'], $parameters['link'], $parameters['lang'], $parameters['hrefLang']);
	}
	
	/**
	 * @return String
	 */
	public static function getLabel() {
		return __("Acronyms", "html-enhancer");
	}
}
