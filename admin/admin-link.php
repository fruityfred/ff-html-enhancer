<h3><?php _e("Links", "ff-html-enhancer"); ?></h3>

<p>
<?php _e("Some words may need a link to a page that explains it... Use this to automate the links for the words (or expression) you want.", "ff-html-enhancer"); ?>
</p>

<table>
<tr>
	<th><?php _e("Expression to link", "ff-html-enhancer"); ?></th>
	<th><?php _e("Language", "ff-html-enhancer"); ?></th>
	<th><?php _e("URL", "ff-html-enhancer"); ?></th>
	<th><?php _e("URL lang.", "ff-html-enhancer"); ?></th>
	<th></th>
</tr>

<?php
foreach ($tokens as $htmlLink) {
?>	
<tr id="tr-<?php echo $rowIndex ;?>">
	<td><input type="text" name="expr[]" maxsize="255" size="20" value="<?php echo $htmlLink->expression; ?>" /></td>
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
	<td><a href="javascript:;" onclick="deleteRow(<?php echo ($rowIndex++) ?>)"><?php _e("delete", "ff-html-enhancer"); ?></a>
</tr>
<?php	
}
for ($i=0 ; $i<5 ; $i++)
{
?>
<tr>
	<td><input type="text" name="expr[]" maxsize="255" size="20"/></td>
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
