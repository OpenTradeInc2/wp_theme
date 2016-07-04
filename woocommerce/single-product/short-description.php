<?php
/**
 * Single product short description
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version	 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

if ( ! $post->post_excerpt ) return;
?>
<div itemprop="description">
	<?php
	$post_excerpt = $post->post_excerpt;
	$post_excerpt = str_replace("<a","<a class='single_add_to_cart_button button alt pdfbuttons'",$post_excerpt)
	?>
	<?php echo apply_filters( 'woocommerce_short_description',  $post_excerpt) ?>
</div>