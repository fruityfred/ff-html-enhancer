<?php
/**
 * A class that represent an expression to be surrounded with a custom
 * tag with, optionnally, a CSS class.
 */
class glimpse_HtmlCustom implements glimpse_HtmlToken {
	
	public $expression;
	public $tagName;
	public $cssClassName;
	public $lang;
	
	/**
	 * @param String $expression
	 * @param String $tagName
	 * @param String $cssClassName
	 * @param String $lang
	 */
	public function __construct($expression, $tagName, $cssClassName = null, $lang = null) {
		$this->expression = $expression;
		$this->tagName = $tagName;
		$this->cssClassName = $cssClassName;
		$this->lang = $lang;
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
		$html = '<' . $this->tagName;
		if ( ! is_null($this->cssClassName) ) {
			$html .= ' class="' . $this->cssClassName . '"';
		}
		if ( ! is_null($this->lang) ) {
			$html .= ' xml:lang="' . $this->lang . '" lang="' . $this->lang . '"';
		}
		return $html . '>' . $this->expression . '</' . $this->tagName . '>';
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
		return new glimpse_HtmlCustom($parameters['expr'], $parameters['tag'], $parameters['css'], $parameters['lang']);
	}
	
	/**
	 * @return String
	 */
	public static function getLabel() {
		return __("Custom tag/class replacement", "html-enhancer");
	}
}
