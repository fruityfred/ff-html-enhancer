<h3><?php _e("Abbreviations", "ff-html-enhancer"); ?></h3>

<p>
<?php _e("An abbreviation may not be pronounced like a word, contrary to an acronym (ex.: DVD, XML).", "ff-html-enhancer"); ?>
</p>

<table>
<tr>
	<th><?php _e("Abbreviation", "ff-html-enhancer"); ?></th>
	<th><?php _e("Definition", "ff-html-enhancer"); ?></th>
	<th><?php _e("Language", "ff-html-enhancer"); ?></th>
	<th><?php _e("URL", "ff-html-enhancer"); ?></th>
	<th><?php _e("URL lang.", "ff-html-enhancer"); ?></th>
	<th></th>
</tr>

<?php
foreach ($tokens as $htmlLink) {
?>	
<tr id="tr-<?php echo $rowIndex ;?>">
	<td><input type="text" name="expr[]" maxsize="255" size="20" value="<?php echo $htmlLink->abbr; ?>" /></td>
	<td><input type="text" name="defn[]" maxsize="255" size="30" value="<?php echo $htmlLink->definition; ?>" /></td>
	<td>
		<select name="lang[]">
			<option value=""><?php _e("Same as page content", "ff-html-enhancer"); ?></option>
			<?php
			foreach ($langs as $code => $name) {
				echo '<option value="' . $code . '"' . glimpse_HtmlEnhancerPlugin::addSelectedAttribute($code, $htmlLink->lang) . '>' . $name . '</option>';
			}
			?>
		</select>
	</td>
	<td><input type="text" name="link[]" maxsize="255" size="40" value="<?php echo $htmlLink->url; ?>" /></td>
	<td>
		<select name="hrefLang[]">
			<option value=""><?php _e("Same as page content", "ff-html-enhancer"); ?></option>
			<?php
			foreach ($langs as $code => $name) {
				echo '<option value="' . $code . '"' . glimpse_HtmlEnhancerPlugin::addSelectedAttribute($code, $htmlLink->hrefLang) . '>' . $name . '</option>';
			}
			?>
		</select>
	</td>
	<td><a href="javascript:;" onclick="deleteRow(<?php echo ($rowIndex++) ?>)">delete</a>
</tr>
<?php	
}
for ($i=0 ; $i<5 ; $i++)
{
?>
<tr>
	<td><input type="text" name="expr[]" maxsize="255" size="20" /></td>
	<td><input type="text" name="defn[]" maxsize="255" size="30" /></td>
	<td>
		<select name="lang[]">
			<option value=""><?php _e("Same as page content", "ff-html-enhancer"); ?></option>
			<?php
			foreach ($langs as $code => $name) {
				echo '<option value="' . $code . '">' . $name . '</option>';
			}
			?>
		</select>
	</td>
	<td><input type="text" name="link[]" maxsize="255" size="40" /></td>
	<td>
		<select name="hrefLang[]">
			<option value=""><?php _e("Same as page content", "ff-html-enhancer"); ?></option>
			<?php
			foreach ($langs as $code => $name) {
				echo '<option value="' . $code . '">' . $name . '</option>';
			}
			?>
		</select>
	</td>
</tr>
<?php } ?>
</table>
