<?php

/**
 * A class that represents a piece of text with its position
 * inside an HTML fragment.
 */
class glimpse_HtmlNeedle {
	
	public $expression;
	public $position;
	
	/**
	 * @param String $expression
	 * @param Integer $position
	 */
	public function __construct($expression, $position) {
		$this->expression = $expression;
		$this->position = $position;
	}
	
	/**
	 * @return Integer
	 */
	public function getLength() {
		return strlen ( $this->expression );
	}
}

/**
 * A class that represents an HTML tag, and optionnaly a CSS class name.
 */
class glimpse_HtmlTag {
	
	public $tagName;
	public $className;
	
	/**
	 * @param String $tagName
	 * @param String $className
	 */
	public function __construct($tagName, $className = null) {
		$this->tagName = $tagName;
		$this->className = $className;	
	}
}

interface glimpse_HtmlToken {
	
	/**
	 * @return String
	 */
	function getExpression();
	
	/**
	 * @return String
	 */
	function getReplacement();
	
	/**
	 * @return Array<glimpse_HtmlTag>
	 */
	function getTags();
	
	/**
	 * @param Array<String,String> $parameters
	 * @return glimpse_HtmlToken
	 */
	static function instanceFromRequestParameters($parameters);
	
	/**
	 * @return String
	 */
	static function getLabel();
}


class glimpse_HtmlEnhancer {
	
	/**
	 * @var Boolean $ignoreCase
	 */
	private $ignoreCase = false;
	
	/**
	 * @var Array<glimpse_HtmlToken> $ignoredContainers
	 */
	private $ignoredContainers = array();
	
	/**
	 * @var Array<Glimpse_HtmlNeedle> $needles
	 */
	private $needles;
	
	/**
	 */
	public function __construct() {
		$this->ignoredContainers = array(
			new glimpse_HtmlTag('blockquote'),
			new glimpse_HtmlTag('var'), new glimpse_HtmlTag('kbd'),
			new glimpse_HtmlTag('code'), new glimpse_HtmlTag('pre'),
			new glimpse_HtmlTag('h1'), new glimpse_HtmlTag('h2'),
			new glimpse_HtmlTag('h3'), new glimpse_HtmlTag('h4'),
			new glimpse_HtmlTag('h5'), new glimpse_HtmlTag('h6')
			);
	}
	
	/**
	 * @param String $html
	 * @param Array<glimpse_HtmlToken>
	 * @param Boolean $ignoreCase
	 * @return String
	 */
	public function process($html, $tokenArray, $ignoreCase = false) {
		$this->ignoreCase = $ignoreCase;
		foreach ( $tokenArray as $token ) {
			$this->beginProcess ( $html, $token->getExpression() );
			foreach ( $this->needles as $needle ) {
				if (! $this->isNeedleInsideTags ( $html, $needle, array_merge($this->ignoredContainers, $token->getTags ()) )) {
					$html = $this->replaceNeedle ( $html, $needle, $token->getReplacement () );
				}
			}
		}
		return $html;
	}

	/**
	 * @param glimpse_HtmlTag $tag
	 */
	public function addIgnoredContainer($tag) {
		$this->ignoredContainers[] = $tag;
	}
	
	/**
	 * @param String $html
	 * @param String $needle
	 */
	private function findNeedles($html, $needle) {
		$this->needles = array ( );
		$offset = 0;
		$nl = strlen ( $needle );
		while ( ($p = $this->strpos ( $html, $needle, $offset )) !== false ) {
			// Needle has been found...
			if ($p !== false) {
				// Check whether it is part of a word or not (hope it's not!)
				$prev = substr ( $html, $p - 1, 1 );
				$next = substr ( $html, $p + $nl, 1 );
				if (! $this->isLetter($prev) && ! $this->isLetter($next) && $prev != '-' && $next != '-') {
					$this->needles [] = new glimpse_HtmlNeedle ( $needle, $p );
				}
			}
			$offset = $p + 1;
		}
	}
	
	/**
	 * @param String $haystack
	 * @param String $needle
	 * @param Integer $offset
	 * @return Integer
	 */
	private function strpos($haystack, $needle, $offset = 0)
	{
		if ($this->ignoreCase) {
			return stripos($haystack, $needle, $offset);
		} else {
			 return strpos($haystack, $needle, $offset);
		}
	}
	
	/**
	 * @param String $html
	 * @param String $needle
	 */
	private function beginProcess($html, $needle) {
		$this->findNeedles ( $html, $needle );
	}
	
	/**
	 * @param String $html
	 * @param glimpse_HtmlNeedle $needle
	 * @param String $replacement
	 * @return String
	 */
	private function replaceNeedle($html, $needle, $replacement) {
		$html = substr_replace ( $html, $replacement, $needle->position, $needle->getLength () );
		$offset = strlen ( $replacement ) - $needle->getLength ();
		// Update needles' offset according to the changes that has just been applied.
		foreach ( $this->needles as $needle ) {
			$needle->position += $offset;
		}
		return $html;
	}
	
	/**
	 * @param Char $char
	 * @return Boolean
	 */
	private function isLetter($char) {
		$ord = ord ( strtolower ( $char ) );
		return ($ord >= ord ( 'a' ) && $ord <= ord ( 'z' ));
	}
	
	/**
	 * @param String $html
	 * @param glimpse_HtmlNeedle $needle
	 * @param glimpse_HtmlTag $tag
	 * @return Boolean
	 */
	private function isNeedleInside($html, $needle, $tag) {
		$p = $needle->position;
		
		// Check if the needle is part of an HTML tag.
		if ( $this->lastPosRev ( $html, '<', $p ) > $this->lastPosRev ( $html, '>', $p ) ) {
			return true;
		}
		
		$startElm = '<' . $tag->tagName;
		$endElm = '</' . trim ( $tag->tagName ) . '>';
		// Search backwards for a starting part of the HTML element $tag.
		$sap = $this->lastPosRev ( $html, $startElm, $p );
		if (! is_null ( $sap )) {
			// A starting part has been found: search (backards again) for
			// the closing part of the element.
			$eap = $this->lastPosRev ( $html, $endElm, $p );
			if ( is_null ( $eap ) || $eap < $sap) {
				// A closing part has been found but before the opening part:
				// this closing part belongs to another element, so position $p
				// is inside the element (remember: a starting part was been found).
				// Should we restrict to elements with a certain CSS class?
				if ( ! is_null($tag->className) ) {
					// Check whether the tag has the specified class attribute.
					// The check here is quite simple: it does not allow multiple
					// CSS classes to be set on the element.
					$classPos = strpos($html, ' class="'.$tag->className.'"', $sap);
					$closePos =  strpos($html, '>', $sap);
					return ($classPos !== false && $classPos < $closePos);
				}
				return true;
			}
		}
		return false;
	}
	
	/**
	 * @param String $html
	 * @param glimpse_HtmlNeedle $needle
	 * @param Array<glimpe_HtmlTag>
	 * @return Boolean
	 */
	private function isNeedleInsideTags($html, $needle, $tagArray) {
		foreach ( $tagArray as $tag ) {
			if ($this->isNeedleInside ( $html, $needle, $tag )) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * @param String $html
	 * @param String $needle
	 * @param Integer $offset
	 * @return Integer
	 */
	private function lastPosRev($haystack, $needle, $offset) {
		$nl = strlen ( $needle );
		$pos = null;
		$x = $offset;
		while ( $x > 0 ) {
			$sub = substr ( $haystack, $x, $nl ); 
			if (($this->ignoreCase && strcasecmp($sub, $needle) == 0) || (!$this->ignoreCase && strcmp($sub, $needle) == 0)) {
				$pos = $x;
				break;
			}
			$x --;
		}
		return $pos;
	}
	
	private static $tokenTypes = null;
	
	/**
	 * @return Array<String>
	 */
	private static function getTokenTypes() {
		if ( is_null(self::$tokenTypes) ) {
			self::$tokenTypes = array();
			$dir = dir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tokens');
			while ($entry = $dir->read()) {
				if (substr($entry, 0, 4) == 'Html' && substr($entry, -10) == '.class.php') {
					self::$tokenTypes[] = strtolower(substr($entry, 4, -10));
				}
			}
			$dir->close();
		}
		return self::$tokenTypes;
	}
	
	/**
	 * @return Array<String>
	 */
	public static function getTokens() {
		$tokens = array();
		foreach (self::getTokenTypes() as $type) {
			$className = 'glimpse_Html' . ucfirst($type);
			$tokens[$type] = call_user_func_array(array($className, 'getLabel'), array());
		}
		return $tokens;
	}
	
	/**
	 * @return void
	 */
	public static function loadTokenClasses() {
		foreach (self::getTokenTypes() as $type) {
			require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tokens' . DIRECTORY_SEPARATOR . 'Html' . ucfirst($type) . '.class.php';
		}
	}
}

glimpse_HtmlEnhancer::loadTokenClasses();

?>
