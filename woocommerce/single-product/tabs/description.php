<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version	 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post;

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', esc_html__( 'Product Description', 'woocommerce' ) ) );
?>

<div class="post-content">
	<?php if ( $heading ): ?>
	  <h3><?php echo $heading; ?></h3>
	<?php endif; ?>


		<table class="shop_attributes">
		<tbody><tr class="">
				<th>SKU Description</th>
				<td><?php echo isa_woo_get_one_pa('Distributor SKU Description');   ?></td>
			</tr>



			<tr class="alt">
				<th>Packaging Type</th>
				<td><p><?php echo isa_woo_get_one_pa('Packaging Type');   ?></p></td>
			</tr>
			<tr class="">
				<th>Packaging Unit</th>
				<td><p><?php echo isa_woo_get_one_pa('Packaging Unit');   ?></p></td>
			</tr>
			<tr class="">
				<th>Packaging Measure</th>
				<td><p><?php echo isa_woo_get_one_pa('Packaging Measure');   ?></p></td>
			</tr>
		</tbody></table>

</div>

