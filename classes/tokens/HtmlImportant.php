<?php
class glimpse_HtmlLink implements glimpse_HtmlToken {
	public $expression;
	public $url;
	public $lang;
	
	/**
	 * @param String $expression
	 * @param String $url
	 * @param String $lang
	 */
	public function __construct($expression, $url, $lang = null) {
		$this->expression = $expression;
		$this->url = $url;
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
		$link = '<a href="' . $this->url . '"';
		if (! is_null ( $this->lang )) {
			$link .= ' xml:lang="' . $this->lang . '" lang="' . $this->lang . '"';
		}
		$link .= '>' . $this->expression . '</a>';
		return $link;
	}
	
	/**
	 * @return Array<String>
	 */
	public function getTags() {
		return array ( new glimpse_HtmlTag( "a " ) );
	}
	
	/**
	 * @param Array<String,String> $parameters
	 * @return glimpse_HtmlLink
	 */
	public static function instanceFromRequestParameters($parameters) {
		return new glimpse_HtmlLink($parameters['expr'], $parameters['link'], $parameters['lang']);
	}
	
	/**
	 * @return String
	 */
	public static function getLabel() {
		return "Links";
	}
}