<?php
if ($_POST["submit"]) {
	glimpse_HtmlEnhancerPlugin::saveOptions($_POST);
}
?>

<script type="text/javascript">
	//<![CDATA[
	function deleteRow(index) {
		var row = document.getElementById('tr-' + index);
		row.parentNode.removeChild(row);
	}
	// -->
</script>

<div class=wrap>
<h2>HTML Enhancer</h2>
<p><?php _e("HTML Enhancer makes it easy to enhance HTML to make it richer and more accessible.", "ff-html-enhancer"); ?></p>
<p><?php _e("HTML Enhancer works kite that: you define a list of acronyms, abbreviations, expressions in a foreign language and key words. HTML Enhancer will then ensure that these expressions are surrounded with the appropriate HTML tag (abbr, acronym, a, span, ...)", "ff-html-enhancer"); ?></p>
	<?php
	$selectedType = $_REQUEST["type"];
	if (!$selectedType) $selectedType = 'link';
	$url = preg_replace('/&type=[a-z]+/', '', $_SERVER["REQUEST_URI"]);
	foreach (glimpse_HtmlEnhancer::getTokens() as $type => $label) {
		echo '<li style="float: left; list-style-type: none; padding: 0 1em;">';
		if ($type != $selectedType) {
			echo '<a href="' . $url . '&type=' . $type . '">' . __($label, "ff-html-enhancer") . '</a>';
		} else {
			_e($label, "ff-html-enhancer"); 
		}
		echo "</li>\n";
	}
	?>	
</ul>

<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" style="clear: both">

	<?php
	$langs = array(
		'fr' => __("french", "html-enhancer"),
		'en' => __("english", "html-enhancer"),
		'de' => __("german", "html-enhancer"),
		'es' => __("spanish", "html-enhancer"),
		'it' => __("italian", "html-enhancer")
	);
	$tokens = glimpse_HtmlEnhancerPlugin::getHtmlTokensArray($selectedType);
	$rowIndex = 0;
	include 'admin-' . escapeshellcmd($selectedType) . '.php';
	?>
	<input type="hidden" name="type" value="<?php echo $selectedType ?>" />
	<input type="submit" name="submit" value="<?php _e("Submit", "ff-html-enhancer"); ?>" />
	
</form>