<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

global $post, $product;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
?>



  <?php
	$price_lb = $product->get_attribute(16);
	$price_kg = $product->get_attribute(17);
	$weight_lb = $product->get_attribute(10);
	$weight_kg = $product->get_attribute(11);

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
	
	
	

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>
<h2 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h2>


<div class="infobar">
	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span></span>

	<?php endif; ?>
	
	<?php echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'woocommerce' ) . ' ', '</span>' ); ?>

</div>

<div class="prodetails">
	
	
	<div class="row">
    <div class="col-md-3" style="border-right: 0px; margin-left:-95px; margin-right: 30px; width: 19%; height: 110px; border-spacing: 150px 0px!important;">
	    
	    <div id="slider" class="fusion-flexslider">
		<ul class="slides">
			
						<?php
				$attachment_count   = count( $product->get_gallery_attachment_ids() );

				if ( $attachment_count > 0 ) {
					$gallery = '[product-gallery]';
				} else {
					$gallery = '[]';
				}

				if ( has_post_thumbnail() ) {

					$image_title 		= esc_attr( get_the_title( get_post_thumbnail_id() ) );
					$image_link  		= wp_get_attachment_url( get_post_thumbnail_id() );
					$image	   			= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
						'title' => $image_title
						) );
					$image_caption = get_post_field( 'post_excerpt', get_post_thumbnail_id() );

					// Avada Edit
					//echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<li><a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="iLightbox' . $gallery . '" data-title="%s" data-caption="%s">%s</a></li>', $image_link, $image_title, $image_title, $image_caption, $image ), $post->ID );
					
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_caption, $image ), $post->ID );
				} else {

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<li><img src="%s" alt="Placeholder" /></li>', wc_placeholder_img_src() ), $post->ID );

				}

				/**
				 * From product-thumbnails.php
				 */
				$attachment_ids = $product->get_gallery_attachment_ids();

				$loop = 0;
				// Avada Edit
				//$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

				foreach ( $attachment_ids as $attachment_id ) {

					// Avada Edit
					/*
					$classes = array( 'zoom' );

					if ( $loop == 0 || $loop % $columns == 0 )
						$classes[] = 'first';

					if ( ( $loop + 1 ) % $columns == 0 )
						$classes[] = 'last';
					*/
					$classes[] = 'image-'.$attachment_id;

					$image_link = wp_get_attachment_url( $attachment_id );

					if ( ! $image_link )
						continue;

					// Avada Edit
					// modified image size to shop_single from thumbnail
					$image	   = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_single' ) );
					$image_class = esc_attr( implode( ' ', $classes ) );
					$image_title = esc_attr( get_the_title( $attachment_id ) );
					$image_caption = get_post_field( 'post_excerpt', $attachment_id );


					// Avada Edit
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<li><a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="iLightbox' . $gallery . '" data-title="%s" data-caption="%s">%s</a></li>', $image_link, $image_title, $image_title, $image_caption, $image ), $attachment_id, $post->ID, $image_class );
					//echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>', $image_link, $image_class, $image_title, $image ), $attachment_id, $post->ID, $image_class );

					$loop++;
				}
			?>
		</ul> 
	</div>

	    
	    
    </div>
	<div class="col-md-3" style="border-left: 1px solid #DBE2EA; margin-left:0px; width:410px;">
	    
	    <div class="det_stock" style="margin-left: 80px;margin-top:5px; width:210px;" >
		<?php echo "<strong>In Stock:</strong> ".number_format($product->stock)." Units"; ?>
	</div>
	  <div class="" style="margin-left: 80px; width:230px;">
		  <strong style="font-weight: bold;color: #000000;">Warehouse Location: </strong><br>
		   <?php echo isa_woo_get_one_pa('Warehouse Location Address');  ?>
	  </div>

	    
    </div>
    <div class="col-md-3 last" style="width:250px; margin-left:40px;">
	    
	    <form method="post" enctype='multipart/form-data'>
	<p class="price"><?php echo $product->get_price_by_measure_html(); ?></p>
	<div class="variations_button">
	<?php woocommerce_quantity_input( array( 'min_value' => 1, 'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity() ) ); ?>
	<br>
		<button type="submit" class="single_add_to_cart_button fusion-button button button-default button-small alt" style="width:93px!important; padding: 2px 2px !important;border-radius: 2px;border-width:0px;border-style: solid;line-height: 5px;font-size: 14px;border-color: #ffffff;background: #ffbc41;color: #ffffff; font-family: 'Source Sans Pro';font-weight: 900;letter-spacing: 0px;text-transform: uppercase;display: inline-block;position: relative;box-sizing: border-box;"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->id ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->id ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
	</form>
	
    </div>
    
</div>	
		
		
	
  	
  </div>
</div>



<?php //echo do_shortcode('[gmw_single_location map_width="100%" additional_info="0" directions="0"]'); ?>

	<div class="summary entry-summary">
<!-- <div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>> -->



	
		


		<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			//do_action( 'woocommerce_single_product_summary' );
		?>
		
		
		<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>
		

	</div><!-- .summary -->


		

	


		<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
