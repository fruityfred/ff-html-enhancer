<h3><?php _e("Custom tag/class replacement", "ff-html-enhancer"); ?></h3>

<p>
<?php _e("Want to put all the occurences of your name into a <code>strong</code> element? :-)", "ff-html-enhancer"); ?>
</p>

<table>
<tr>
	<th><?php _e("Expression to surround", "ff-html-enhancer"); ?></th>
	<th><?php _e("Tag name", "ff-html-enhancer"); ?></th>
	<th><?php _e("CSS class", "ff-html-enhancer"); ?></th>
	<th><?php _e("Language", "ff-html-enhancer"); ?></th>
	<th></th>
</tr>

<?php
foreach ($tokens as $htmlLink) {
?>	
<tr id="tr-<?php echo $rowIndex ;?>">
	<td><input type="text" name="expr[]" maxsize="255" size="20" value="<?php echo $htmlLink->expression; ?>" /></td>
	<td><input type="text" name="tag[]" maxsize="255" size="30" value="<?php echo $htmlLink->tagName; ?>" /></td>
	<td><input type="text" name="css[]" maxsize="255" size="40" value="<?php echo $htmlLink->cssClassName; ?>" /></td>
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
	<td><a href="javascript:;" onclick="deleteRow(<?php echo ($rowIndex++) ?>)"><?php _e("delete", "ff-html-enhancer"); ?></a>
</tr>
<?php	
}
for ($i=0 ; $i<5 ; $i++)
{
?>
<tr>
	<td><input type="text" name="expr[]" maxsize="255" size="20" /></td>
	<td><input type="text" name="tag[]" maxsize="255" size="30" /></td>
	<td><input type="text" name="css[]" maxsize="255" size="40" /></td>
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
</tr>
<?php } ?>
</table>
