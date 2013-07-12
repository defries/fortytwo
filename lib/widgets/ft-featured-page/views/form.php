<?php // This file is used to markup the administration form of the widget. ?>
<div class="ft-featured-page-admin">
<input type="hidden" <?php $this->echo_field_id( "icon" ) ?> value="<?php echo esc_attr( $icon ); ?>">
<table>
	<tr>
		<td class="<?php echo $this->get_field_id( "the-icon-selector" )?>">
				<div >
					<i class="icon-camera-retro icon-2x"></i>
				</div>
		</td>
		<td>
			<?php _e( 'Title', 'fortytwo' ); ?>:
			<input class="span2" type="text" <?php $this->echo_field_id( "title" ) ?>  value="<?php echo esc_attr( $title ); ?>">
		</td>
  </tr>
	<tr><td colspan="2">
			<?php _e( 'Content', 'fortytwo' ); ?>
		  <textarea rows="5" <?php $this->echo_field_id( "content" ) ?>
		  	><?php echo esc_textarea( $content ); ?></textarea>
  </td></tr>
	<tr><td colspan="2">
			<?php _e( 'Button text', 'fortytwo' ); ?>
			<input type="text" <?php $this->echo_field_id( "button_text" ) ?> value="<?php echo esc_attr( $button_text ); ?>"><br />
		  <?php _e( 'Button link', 'fortytwo' ); ?>
		  <input type="text" <?php $this->echo_field_id( "button_link" ) ?> value="<?php echo esc_attr( $button_link ); ?>">
		</td>
	</tr>
</table>
</div>
<script>
(function ($) {
	"use strict";
	$(document).ready(function () {
		var iconCollection = new window.FontAwesomeIconSelectorApp.IconCollection();
		iconCollection.on('change:selectedIcon', function(selectedIcon) {
			$('#<?php echo $this->get_field_id( "icon" )?>').val(selectedIcon.get('css'));
		});
		var iconList = new window.FontAwesomeIconSelectorApp.Views.IconListView({
			collection: iconCollection,
			selectedIconCss: '<?php echo $icon; ?>'||'icon-star',
			el: '.<?php echo $this->get_field_id( "the-icon-selector" )?>'
		});
	});
}(jQuery));
</script>