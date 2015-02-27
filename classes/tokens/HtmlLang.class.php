<?php
/**
 * A class that represent an foreign expression with its language.
 */
class glimpse_HtmlLang implements glimpse_HtmlToken {
	
	public $expression;
	public $lang;
	public $translation;
	
	/**
	 * @param String $expression
	 * @param String $lang
	 */
	public function __construct($expression, $lang, $translation = null) {
		$this->expression = $expression;
		$this->lang = $lang;
		$this->translation = $translation;
	}
	
	/**
	 * @return String
	 */
	public function getExpression()	{
		return $this->expression;
	}
	
	/**
	 * @return String
	 */
	public function getReplacement() {
		$html = '<span xml:lang="' . $this->lang . '">' . $this->expression . '</span>';
		if ( ! is_null($this->translation) ) {
			$lang = get_bloginfo('language');
			if (!$lang) $lang = "fr";
			$html = '<span xml:lang="'.$lang.'" title="'.$this->translation.'">' . $html . '</span>';
		}
		return $html; 
	}
	
	/**
	 * @return Array<String>
	 */
	public function getTags() {
		return array ( );
	}
	
	/**
	 * @param Array<String,String> $parameters
	 * @return glimpse_HtmlLang
	 */
	public static function instanceFromRequestParameters($parameters) {
		return new glimpse_HtmlLang($parameters['expr'], $parameters['lang'], $parameters['trans']);
	}
	
	/**
	 * @return String
	 */
	public static function getLabel() {
		return __("Foreign words", "html-enhancer");
	}
}
