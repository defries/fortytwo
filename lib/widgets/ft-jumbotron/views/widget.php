<?php
/**
 * FortyTwo Theme: Jumbotron Widget View
 *
 * Represents the view for the Jumbotron widget form in the backend.
 *
 * @package FortyTwo\Widgets
 * @author  Forsite Themes
 * @license GPL-2.0+
 * @link    http://forsitethemes/themes/fortytwo/
 */

/**
 *
 * @todo  This code needs better documentation
 * @var [type]
 */
$action_button_text = esc_html( $instance['button_text'] );
$action_button_link = esc_url( $instance['button_link'] );

?>


<div class="ft-jumbotron-detail">
	<span><?php echo esc_html( $instance['title'] ) ?></span>
	<p><?php echo esc_html( $instance['content'] ) ?></p>
</div>
<a class="btn ft-jumbotron-action" href="<?php echo $action_button_link ?>"><?php echo $action_button_text; ?></a>