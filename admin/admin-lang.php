<h3><?php _e("Foreign words", "ff-html-enhancer"); ?></h3>

<p>
<?php _e("For accessibilty purpose, every foreign word (a word that is not part of the main language of the page) must be surrounded by a tag with the appropriate <code>lang</code> attribute.", "ff-html-enhancer"); ?>
</p>

<table>
<tr>
	<th><?php _e("Foreign expression", "ff-html-enhancer"); ?></th>
	<th><?php _e("Language", "ff-html-enhancer"); ?></th>
	<th><?php _e("Translation", "ff-html-enhancer"); ?></th>
	<th></th>
</tr>

<?php
foreach ($tokens as $htmlLink) {
?>	
<tr id="tr-<?php echo $rowIndex ;?>">
	<td><input type="text" name="expr[]" maxsize="255" size="30" value="<?php echo $htmlLink->expression; ?>" /></td>
	<td>
		<select name="lang[]">
			<?php
			foreach ($langs as $code => $name) {
				echo '<option value="' . $code . '"' . glimpse_HtmlEnhancerPlugin::addSelectedAttribute($code, $htmlLink->lang) . '>' . $name . '</option>';
			}
			?>
		</select>
	</td>
	<td><input type="text" name="trans[]" maxsize="255" size="30" value="<?php echo $htmlLink->translation; ?>" /></td>
	<td><a href="javascript:;" onclick="deleteRow(<?php echo ($rowIndex++) ?>)"><?php _e("delete", "ff-html-enhancer"); ?></a>
</tr>
<?php	
}
for ($i=0 ; $i<5 ; $i++)
{
?>
<tr>
	<td><input type="text" name="expr[]" maxsize="255" size="30" /></td>
	<td>
		<select name="lang[]">
			<?php
			foreach ($langs as $code => $name) {
				echo '<option value="' . $code . '">' . $name . '</option>';
			}
			?>
		</select>
	</td>
	<td><input type="text" name="trans[]" maxsize="255" size="30" /></td>
</tr>
<?php } ?>
</table>
