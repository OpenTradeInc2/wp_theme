<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.9
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;
?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
	<?php
	$price_lb = $product->get_attribute(15);
	$price_kg = $product->get_attribute(16);
	$weight_lb = $product->get_attribute(9);
	$weight_kg = $product->get_attribute(10);

	if($price_lb == null or $price_lb == ''){

		foreach ($product->get_attributes() as $atr){
			if($atr[name] == 'Price / lb'){
				$price_lb = $atr[value];
			}
			if($atr[name] == 'Price / Kg'){
				$price_kg = $atr[value];
			}
			if($atr[name] == 'Packaging Weight (lb)'){
				$weight_lb = $atr[value];
			}
			if($atr[name] == 'Packaging Weight (kg)'){
				$weight_kg = $atr[value];
			}
		}
	}
	?>
	<p class="price"><?php echo $product->get_price_by_measure_html(); ?></p>
	<p><b>Price per lb :</b><?php echo ' $'.$price_lb.'/lb.'?></p>
	<p><b>Price per kg :</b><?php echo ' $'.$price_kg.'/kg.'?></p>
	<p><b>Packaging Weight (lb): </b><?php echo $weight_lb?></p>
	<p><b>Packaging Weight (kg): </b><?php echo $weight_kg?></p>

	<meta itemprop="price" content="<?php echo esc_attr( $product->get_price_by_measure_html() ); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo esc_attr( get_woocommerce_currency() ); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
</div>
