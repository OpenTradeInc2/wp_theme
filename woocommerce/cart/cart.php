<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version	 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

wc_print_notices();

do_action( 'woocommerce_before_cart' );

?>

<form name="checkout" method="post" action="" enctype="multipart/form-data">
<form enctype="multipart/form-data" action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">
	<div class="cart-collaterals">

		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<table class="shop_table cart" cellspacing="0">
			<thead>
			<tr>
				<?php // Avada edit ?>
				<th class="product-name" style="width: 25%!important;"><?php _e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-price-lb"><?php _e( 'Price/lb', 'woocommerce' ); ?></th>
				<th class="product-lbs"><?php _e( 'Lbs/Unit', 'woocommerce' ); ?></th>
				<!--<th class="product-price"><?php _e( 'Price', 'woocommerce' ); ?></th>-->
				<th class="product-subtotal"><?php _e( 'Total', 'woocommerce' ); ?></th>
				<th class="product-offer" style="text-align: center!important;"><?php _e( 'Offer', 'woocommerce' ); ?></th>
				<th class="product-remove">&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			$quantity = 0;
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product	 = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					?>
					<?php

					$attributes = get_post_meta($product_id, '_product_attributes', true);

					foreach ($attributes as $product_atributte) {

						if ($product_atributte["name"] == "Price / lb") {
							$price_lb = $product_atributte["value"];
						}
						if ($product_atributte["name"] == "Packaging Weight (lb)") {
							$weight_lb = $product_atributte["value"];
						}
					}
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td style="width: 25%!important;" class="product-name">
							<?php // Avada edit ?>
							<!-- <span class="product-thumbnail">
							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $_product->is_visible() )
								echo $thumbnail;
							else
								printf( '<a href="%s">%s</a>', $_product->get_permalink( $cart_item ), $thumbnail );
							?>
						</span>-->
							<div class="product-info">
								<?php
								if ( ! $_product->is_visible() )
									echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
								else
									// Avada edit
									echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="product-title" href="%s">%s</a>', $_product->get_permalink( $cart_item ), $_product->get_title() ), $cart_item, $cart_item_key );

								// Meta data
								echo WC()->cart->get_item_data( $cart_item );

								// Backorder notification
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
									echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
								?>
							</div>
						</td>
						
						<td class="product-quantity">						
							<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
							?>
						</td>
						
						<td class="product-price-lb">
							<?php echo '$'.$price_lb ?>
						</td>

						<td class="product-lbs">
							<?php echo $weight_lb ?>
						</td>

						<!--<td class="product-price">
							<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
						</td>-->

						<td class="product-subtotal">
							<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
							?>
						</td>

						<td class="product-offer" style="text-align: center!important;">
							&nbsp;<input type="text" name="offerValue[]" onblur="returnFormatCost('offerValue<?php echo $quantity;?>')" id="offerValue<?php echo $quantity;?>" placeholder="$0.00" style="height:40px!important; width:90px!important;"/>
							<script language="JavaScript">

								function returnFormatCost(idElement){

									var numero = document.getElementById(idElement).value;
									var numeroDecimal;
									if(numero.search("$") !== -1){
										numero = numero.replace("$","");
										numeroDecimal = number_format(numero, 2);
									}else{
										numeroDecimal = number_format(numero, 2);
									}
									document.getElementById(idElement).value = "$"+numeroDecimal;
								}

								function number_format(amount, decimals) {


									amount += ''; // por si pasan un numero en vez de un string
									amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

									decimals = decimals || 0; // por si la variable no fue fue pasada

									// si no es un numero o es igual a cero retorno el mismo cero
									if (isNaN(amount) || amount === 0)
										return parseFloat(0).toFixed(decimals);

									// si es mayor o menor que cero retorno el valor formateado como numero
									amount = '' + amount.toFixed(decimals);

									var amount_parts = amount.split('.'),
										regexp = /(\d+)(\d{3})/;

									while (regexp.test(amount_parts[0]))
										amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

									return amount_parts.join('.');
								}

							</script>
							<input type="hidden" name="productsId[]" id="productsId" value="<?php echo $cart_item[product_id]; ?>">
						</td>

						<?php // Avada edit ?>
						<td class="product-remove">
							<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
							?>
						</td>

					</tr>
					<?php
				}
				$quantity++;
			}

			do_action( 'woocommerce_cart_contents' );
			?>
			<?php // Avada edit ?>
			<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>
		<br>
		<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

			<?php do_action( 'woocommerce_before_cart_totals' ); ?>

			<table cellspacing="0" class="shop_table shop_table_responsive">

				<tr class="cart-subtotal">
					<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
					<td data-title="<?php _e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
				</tr>

				<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
					<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
						<td data-title="<?php wc_cart_totals_coupon_label( $coupon ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
					</tr>
				<?php endforeach; ?>

				<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

					<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

					<?php wc_cart_totals_shipping_html(); ?>

					<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

				<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

					<tr class="shipping">
						<th><?php _e( 'Shipping', 'woocommerce' ); ?></th>
						<td data-title="<?php _e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
					</tr>

				<?php endif; ?>

				<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
					<tr class="fee">
						<th><?php echo esc_html( $fee->name ); ?></th>
						<td data-title="<?php echo esc_html( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
					</tr>
				<?php endforeach; ?>

				<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) :
					$taxable_address = WC()->customer->get_taxable_address();
					$estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
						? sprintf( ' <small>(' . __( 'estimated for %s', 'woocommerce' ) . ')</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
						: '';

					if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
						<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
							<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
								<th><?php echo esc_html( $tax->label ) . $estimated_text; ?></th>
								<td data-title="<?php echo esc_html( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr class="tax-total">
							<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></th>
							<td data-title="<?php echo esc_html( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
						</tr>
					<?php endif; ?>
				<?php endif; ?>

				<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

				<tr class="order-total">
					<th><?php _e( 'Total', 'woocommerce' ); ?></th>
					<td data-title="<?php _e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
				</tr>

				<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

			</table>

			<?php do_action( 'woocommerce_after_cart_totals' ); ?>

		</div>
		<br>
		<label for="file">Please select File:</label>
		<input type="file" name="orderFile" id="orderFile" accept=".pdf" style="font-size: 12px!important;">
		<br>
		<br>
		<div action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

			<h2><span href="#" class="shipping-calculator-button"><?php _e( 'Enter ZIP Code', 'woocommerce' ); ?></span>
			</h2>

			<div class="avada-shipping-calculator-form">

				<p class="form-row form-row-wide">
					<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state" rel="calc_shipping_state">
						<option value=""><?php _e( 'Select a country&hellip;', 'woocommerce' ); ?></option>
						<?php
						foreach( WC()->countries->get_shipping_countries() as $key => $value )
							echo '<option value="' . esc_attr( $key ) . '"' . selected( WC()->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
						?>
					</select>
				</p>

				<div class="<?php if ( Avada()->settings->get( 'avada_styles_dropdowns' ) ): ?>avada-select-parent fusion-layout-column fusion-one-half fusion-spacing-yes<?php endif; ?>">
					<?php
					$current_cc = WC()->customer->get_shipping_country();
					$current_r  = WC()->customer->get_shipping_state();
					$states     = WC()->countries->get_states( $current_cc );

					// Hidden Input
					if ( is_array( $states ) && empty( $states ) ) {

						?><input type="hidden" name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php esc_attr_e( 'State / county', 'woocommerce' ); ?>" /><?php

						// Dropdown Input
					} elseif ( is_array( $states ) ) {

						?><span>
						<select name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php esc_attr_e( 'State / county', 'woocommerce' ); ?>">
							<option value=""><?php _e( 'Select a state&hellip;', 'woocommerce' ); ?></option>
							<?php
							foreach ( $states as $ckey => $cvalue )
								echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, false ) . '>' . __( esc_html( $cvalue ), 'woocommerce' ) .'</option>';
							?>
						</select>
						</span><?php

						// Standard Input
					} else {

						?><input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>" placeholder="<?php esc_attr_e( 'State / county', 'woocommerce' ); ?>" name="calc_shipping_state" id="calc_shipping_state" /><?php

					}
					?>
				</div>

				<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_city', false ) ) : ?>

					<p class="form-row form-row-wide">
						<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_city() ); ?>" placeholder="<?php esc_attr_e( 'City', 'woocommerce' ); ?>" name="calc_shipping_city" id="calc_shipping_city" />
					</p>

				<?php endif; ?>

				<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>

					<div class="form-row form-row-wide fusion-layout-column fusion-one-half fusion-spacing-yes fusion-column-last">
						<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" placeholder="<?php esc_attr_e( 'Postcode / ZIP', 'woocommerce' ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
					</div>

				<?php endif; ?>

				<p>
					<button type="submit" name="calc_shipping" value="1" class="fusion-button button-default button-small button small default"><?php _e( 'Enter', 'woocommerce' ); ?></button>
				</p>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>

		<div class="cart-totals-buttons">
			<div class="wc-proceed-to-checkout">
				<input type="submit" class="fusion-button button-default button-medium button default medium" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />
				<input type="submit" class="checkout-button fusion-button button-default button-medium button default medium alt wc-forward" name="purchase-order" id="purchase-order" value="Purchase Order" />
				<input type="submit" class="checkout-button fusion-button button-default button-medium button default medium alt wc-forward" name="post-offer" id="post-offer" value="Post Offer">
				<input type="submit" class="checkout-button fusion-button button-default button-medium button default medium alt wc-forward" name="request-information" id="request-information" value="Request Information">
			</div>
			<?php do_action( 'woocommerce_cart_actions' ); ?>
		</div>

		<?php do_action( 'woocommerce_cart_collaterals' ); ?>

	</div>
	<?php echo avada_woocommerce_cross_sell_display_OT(); ?>
</form>

</form>


<?php do_action( 'woocommerce_after_cart' );

if (isset($_POST['post-offer'])) {

	if ( is_user_logged_in() ) {
		try {
			if(verifyOfferValue()==true){
				if(verifyZipcode()==true){
					$current_user = wp_get_current_user();
					$countries = WC()->countries->get_countries();
					$country = $countries[$_POST["calc_shipping_country"]];

					if(count($countries)>0){
						$states = WC()->countries->get_states( $_POST["calc_shipping_country"]);
						$stateName = $states[$_POST["calc_shipping_state"]];
					}else{
						$stateName = "";
					}

					$zipCode_Postal = $_POST["calc_shipping_postcode"];
					$formatDate = date("Ymdhis");
					$productList = createOfferDB($_POST['productsId'],$_POST['offerValue'],$current_user,$formatDate, $country, $stateName, $zipCode_Postal);
					sendOfferEmail($productList, $current_user);
					redirectOffer();
				}else{
					wc_add_notice("The ZIP / Postcode is necessary",'error');
					header("Refresh:0");
				}

			}else{
				wc_add_notice("Must be exists at least one offer",'error');
				header("Refresh:0");
			}

		} catch (Exception $e) {
			wc_add_notice( 'Caught exception'.  $e->getMessage(),'error');
			header("Refresh:0");
		}
	} else {
		wc_add_notice('Please, Enter your login information!.','error');
		header("Refresh:0");
	}

}

function createOfferDB($products, $offers, $current_user, $formatDate, $country, $state, $zipcode){
	global $wpdb;

	if($wpdb->check_connection()){
		$position = 0;
		$productList = array();

		$totalAmount = WC()->cart->total;
		$totalOffer = 0;
		foreach ($offers as $offer){
			$totalOffer = $totalOffer + tofloat($offer);
		}

		$wpdb->query("INSERT INTO `ot_custom_offer_information`
                                    (`user_id`,
                                    `total_amount`,
                                    `total_offer`,
                                    `added_by`,
                                    `added_date`,
                                    `country`,
                                    `state`,
                                    `zipcode`)
                                  VALUES
                                    (".$current_user->ID.",
                                    ".$totalAmount.",
                                    ".$totalOffer.",
                                    ".$current_user->ID.",
                                    '".$formatDate."',
                                    '".$country."',
                                    '".$state."',
                                    '".$zipcode."');");

		$offerInformationId = $wpdb->insert_id;

		foreach ($products as $product){

			if($offers[$position] !== null and  $offers[$position] !== ''){
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					if($cart_item['product_id'] == $product){
						$cartProduct = $cart_item;
					}
				}
				$data = $cartProduct['data'];
				$post =$data->post;

				$wpdb->query("INSERT INTO `ot_custom_product_offer_information`
                                (`product_id`,
                                `offer_information_id`,
                                `quantity`,
                                `added_by`,
                                `added_date`,
                                `price`,
                                `offer`)
                                VALUES
                                (".$product.",
                                ".$offerInformationId.",
                                ".$cartProduct[quantity].",
                                ".$current_user->ID.",
                                '".$formatDate."',
                                ".$data->price.",
                                ".tofloat($offers[$position]).");");

				$newProduct  = array( 'productID' => $product, 'productDescription'=>$post->post_title, 'productQuantity'=>$cartProduct[quantity], 'offer'=>$offers[$position], 'offerInformationId'=>$offerInformationId, 'totalAmount'=>$totalAmount, 'totalOffer'=> $totalOffer, 'country'=> $country, 'state'=>$state, 'zipcode'=>$zipcode);
				array_push($productList,$newProduct);
			}
			$position = $position+1;
		}
	}
	return $productList;
}

function sendOfferEmail($productList, $current_user){

	global $wpdb;

	$to = get_option('open-trade-emails');

	//Asunto del email
	$subject='New Post Offer';

	//La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
	$headers = 'Reply-to: '.'Michael'.' '.'Lin'.' <'.'michael.lin@opentradeinc.com'.'>' . "\r\n";


	//El mensaje a enviar. Se puede incluir HTML si enviamos el email en modo HTML y no texto.
	$tableLines = '';
	$flag = true;
	$country = "";
	$state = "";
	$zipcode = "";
	foreach($productList as $product){
		$tableLines .= '<tr><th style="width:25%; border: 1px solid;" align="center">'.$product['productID'].'</th><td style="width:25%; border: 1px solid;" align="center">'.$product['productDescription'].'</td><td style="width:25%; border: 1px solid;" align="center">'.$product['productQuantity'].'</td><td style="width:25%; border: 1px solid;" align="center">'.$product['offer'].'</td></tr>';
		$offerInformationId = $product['offerInformationId'];
		$total = '$' . number_format($product['totalAmount'], 2);
		$totalOffer = '$' . number_format($product['totalOffer'], 2);
		if($flag == true){
			$country = $product["country"];
			$state = $product["state"];
			$zipcode = $product["zipcode"];
			$flag = false;
		}
	}


	$userData = $current_user->data;
	$formatDate = date("Y-m-d h:i:s");

	$message ='
        <html>
            <head>
            <font FACE="impact" SIZE=6 COLOR="red">O</font><font FACE="impact" SIZE=6 COLOR="black">PENTRADE</font>
            <br/>
                <h1>Post Offer</h1>
            </head>
            <body>
                <table>                    
                    <tr>
                        <th>Name:</th>
                        <td>'.$userData->display_name.'</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>'.$userData->user_email.'</td>
                    </tr>
                    <tr>
                        <th>Offer ID:</th>
                        <td>'.$offerInformationId.'</td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td>'.$total.'</td>
                    </tr>
                    <tr>
                        <th>Total Offer:</th>
                        <td>'.$totalOffer.'</td>
                    </tr>
                    <tr>
                        <th>User Location:</th>
                        <td>'.$country.' - '.$state.'</td>
                    </tr>
                    <tr>
                        <th>ZIP / Postal Code:</th>
                        <td>'.$zipcode.'</td>
                    </tr>
                </table>
                <br/>
                <table>
                    <tr>
                        <th style="width:25%; border: 1px solid;">Id</th>
                        <th style="width:25%; border: 1px solid;">Description</th>
                        <th style="width:25%; border: 1px solid;">Quantity</th>
                        <th style="width:25%; border: 1px solid;">Offer</th>
                    </tr>'.$tableLines.'              
                </table>
                <br/>
                <table>
                    <tr>
                        <th>Date:</th>
                        <td><label>'.$formatDate.'</label></td>
                    </tr>
                </table>
            </body>
        </html>';


	//Filtro para indicar que email debe ser enviado en modo HTML
	add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

	//Cambiamos el remitente del email que en Wordpress por defecto es tu email de admin
	add_filter('wp_mail_from','mqw_email_from');

	function mqw_email_from($content_type) {
		return 'info@opentradeinc.com';
	}

	add_filter( 'wp_mail_from_name', function( $name ) {
		return 'Opentradeinc';
	});

	//Por último enviamos el email
	wp_mail( $to, $subject, $message, $headers);

}

function redirectOffer() {
	ob_start();
	header('Location: '.get_site_url().'/post-offer-created/');
	ob_end_flush();
	die();
}

if (isset($_POST['purchase-order'])) {

	if ( is_user_logged_in() ) {
		try {

			if(verifyZipcode() == true){
				$current_user = wp_get_current_user();
				$countries = WC()->countries->get_countries();
				$country = $countries[$_POST["calc_shipping_country"]];

				if(count($countries)>0){
					$states = WC()->countries->get_states( $_POST["calc_shipping_country"]);
					$stateName = $states[$_POST["calc_shipping_state"]];
				}else{
					$stateName = "";
				}

				$zipCode_Postal = $_POST["calc_shipping_postcode"];

				$file = $_FILES['orderFile'];
				$filename = $file['name'];
				$fileType = pathinfo($filename, PATHINFO_EXTENSION);

				if ($fileType != 'pdf') {
					wc_add_notice('Invalid file type. Only pdf type are accepted.','error');
					header("Refresh:0");
				}

				$formatDate = date("Ymdhis");
				global $current_user;
				$current_user = wp_get_current_user();

				$target_dir = plugin_dir_path(__FILE__) . "uploads/";
				$fullPatch = getFullPatch($target_dir, $file, $formatDate, $current_user);

				if(uploadFile($file, $fullPatch)){
					$productList = createPurchaseOrderDB($current_user, $formatDate, $filename, $fullPatch, $country, $stateName, $zipCode_Postal);
					sendPurchaseOrderEmail($productList, $current_user, $formatDate,$fullPatch);
					wc_add_notice( 'Order Created', 'success' );
					WC()->cart->empty_cart();
					redirectPurchaseOrder();
				}else{
					wc_add_notice('File was not uploaded. '.$fullPatch,'error');
					header("Refresh:0");
				}
			}else{
				wc_add_notice("The ZIP / Postcode is necessary",'error');
				header("Refresh:0");
			}

		}catch ( Exception $e ) {
			if ( ! empty( $e ) ) {
				wc_add_notice( $e->getMessage(), 'error' );
			}
		}
	} else {
		wc_add_notice('Please, Enter your login information!.','error');
		header("Refresh:0");
	}
}

if (isset($_POST['request-information'])) {

	if ( is_user_logged_in() ) {

		if(verifyZipcode()==true){
			$current_user = wp_get_current_user();
			$countries = WC()->countries->get_countries();
			$country = $countries[$_POST["calc_shipping_country"]];

			if(count($countries)>0){
				$states = WC()->countries->get_states( $_POST["calc_shipping_country"]);
				$stateName = $states[$_POST["calc_shipping_state"]];
			}else{
				$stateName = "";
			}

			$zipCode_Postal = $_POST["calc_shipping_postcode"];

			$current_user = wp_get_current_user();
			$formatDate = date("Ymdhis");
			$productList = createRequestInformationDB($current_user, $formatDate, $country, $stateName, $zipCode_Postal);
			sendRequestInformationEmail($productList, $current_user);
			redirectRequestInformation();
		}else{
			wc_add_notice("The ZIP / Postcode is necessary",'error');
			header("Refresh:0");
		}

	} else {
		wc_add_notice('Please, Enter your login information!.','error');
		header("Refresh:0");
	}
}

function createRequestInformationDB($current_user, $formatDate, $country, $state, $zipcode){
	global $wpdb;

	$productList = array();

	if($wpdb->check_connection()){

		$wpdb->query("INSERT INTO `ot_custom_request_information`
								(`user_id`,														
								`added_by`,
								`added_date`)
							  VALUES
								(".$current_user->ID.",													
								".$current_user->ID.",
								'".$formatDate."');");

		$requestId = $wpdb->insert_id;

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			$wpdb->query("INSERT INTO `ot_custom_product_request_information`
									(`product_id`,
									`request_information_id`,
									`quantity`,
									`added_by`,
									`added_date`)
									VALUES
									(".$cart_item[product_id].",
									".$requestId.",
									".$cart_item[quantity].",
									".$current_user->ID.",
									'".$formatDate."');");

			$data = $cart_item['data'];
			$post =$data->post;
			$newProduct  = array( 'productID' => $cart_item[product_id], 'productDescription'=>$post->post_title, 'productQuantity'=>$cart_item[quantity], 'requestId'=>$requestId,'country'=> $country, 'state'=>$state, 'zipcode'=>$zipcode );
			array_push($productList,$newProduct);
		}

	}
	return $productList;
}

function sendRequestInformationEmail($productList, $current_user){

	$to = get_option('open-trade-emails');

	//Asunto del email
	$subject='New Request Information';

	//La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
	$headers = 'Reply-to: '.'Michael'.' '.'Lin'.' <'.'michael.lin@opentradeinc.com'.'>' . "\r\n";


	//El mensaje a enviar. Se puede incluir HTML si enviamos el email en modo HTML y no texto.
	$tableLines = '';
	$flag = true;
	$country = "";
	$state = "";
	$zipcode = "";
	foreach($productList as $product){
		$tableLines .= '<tr><th style="width:25%; border: 1px solid;" align="center">'.$product['productID'].'</th><td style="width:25%; border: 1px solid;" align="center">'.$product['productDescription'].'</td><td style="width:25%; border: 1px solid;" align="center">'.$product['productQuantity'].'</td></tr>';
		$requestId = $product['requestId'];
		if($flag == true){
			$country = $product["country"];
			$state = $product["state"];
			$zipcode = $product["zipcode"];
			$flag = false;
		}
	}

	$userData = $current_user->data;
	$formatDate = date("Y-m-d h:i:s");
	$message ='
   	<html>
    <head>
	<font FACE="impact" SIZE=6 COLOR="red">O</font><font FACE="impact" SIZE=6 COLOR="black">PENTRADE</font>
	<br/>
         <h1>Request Information</h1>
      </head>
      <body>
         <table>
            <tr>
               <th>Name:</th>
               <td>'.$userData->display_name.'</td>
            </tr>
            <tr>
               <th>Email:</th>
               <td>'.$userData->user_email.'</td>
            </tr>
            <tr>
               <th>Request Information ID:</th>
               <td>'.$requestId.'</td>
            </tr>
            <tr>
				<th>User Location:</th>
				<td>'.$country.' - '.$state.'</td>
			</tr>
			<tr>
				<th>ZIP / Postal Code:</th>
				<td>'.$zipcode.'</td>
			</tr>
         </table>
         <br/>
         <table>
            <tr>
               <th style="width:25%; border: 1px solid;">Id</th>
               <th style="width:25%; border: 1px solid;">Description</th>
               <th style="width:25%; border: 1px solid;">Quantity</th>    
            </tr>'.$tableLines.'         
         </table>
         <br/>
         <table>
            <tr>
               <th>Date:</th>
               <td><label>'.$formatDate.'</label></td>
            </tr>
         </table>
      </body>
   </html>';


	//Filtro para indicar que email debe ser enviado en modo HTML
	add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

	//Cambiamos el remitente del email que en Wordpress por defecto es tu email de admin
	add_filter('wp_mail_from','mqw_email_from');

	function mqw_email_from($content_type) {
		return 'info@opentradeinc.com';
	}

	add_filter( 'wp_mail_from_name', function( $name ) {
		return 'Opentradeinc';
	});

	//Por último enviamos el email
	wp_mail( $to, $subject, $message, $headers);

}

function redirectRequestInformation() {
	ob_start();
	header('Location: '.get_site_url().'/request-information-created/');
	ob_end_flush();
	die();
}

function createPurchaseOrderDB($current_user,$formatDate, $filename, $fullPatch, $country, $state, $zipcode){
	global $wpdb;

	$productList = array();

	if($wpdb->check_connection()){

		$cart = WC()->cart;
		$totalAmount = 0;//$cart->total;

		$wpdb->query("INSERT INTO `ot_custom_purchase_order`
							(`user_id`,
							`purchase_date`,						
							`added_by`,
							`added_date`,
							`total_amount`,
							`file_name`,
							`file_patch`)
						  VALUES
							(".$current_user->ID.",
							'".$formatDate."',						
							".$current_user->ID.",
							'".$formatDate."',							
							".$totalAmount.",
							'".$filename."',
							'".$fullPatch."');");

		$orderId = $wpdb->insert_id;

		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {

			$realQuantity = $_POST["cart"][$cart_item_key]["qty"];
			$wpdb->query("INSERT INTO `ot_custom_product_purchase_order`
								(`product_id`,
								`purchase_order_id`,
								`quantity`,
								`added_by`,
								`added_date`)
								VALUES
								(".$cart_item[product_id].",
								".$orderId.",
								".$realQuantity.",
								".$current_user->ID.",
								'".$formatDate."');");

			$data = $cart_item['data'];
			$post =$data->post;

			$product_attributes = get_post_meta($cart_item[product_id], '_product_attributes', true);
			$distributorName = "";
			foreach ($product_attributes as $product_attribute){
				if($product_attribute[name] == 'Distributor Name'){
					$distributorName = $product_attribute[value];
				}
			}

			$totalAmount = $totalAmount + ($realQuantity * $data->price);

			$newProduct  = array( 'productID' => $cart_item[product_id], 'productDescription'=>$post->post_title, 'productQuantity'=>$realQuantity, 'productPrice'=>$data->price, 'orderId'=>$orderId, 'totalAmount'=>$totalAmount, 'distributorName'=>$distributorName,'country'=> $country, 'state'=>$state, 'zipcode'=>$zipcode);
			array_push($productList,$newProduct);
		}

		$wpdb->query("update ot_custom_purchase_order set total_amount = ".$totalAmount." where purchase_order_id = ".$orderId.";");
	}
	return $productList;
}

function sendPurchaseOrderEmail($productList, $current_user, $formatDate, $fullPatch){

	$to = get_option('open-trade-emails');

	//Asunto del email
	$subject='New Purchase Order';

	//La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
	$headers = 'Reply-to: '.'Michael'.' '.'Lin'.' <'.'michael.lin@opentradeinc.com'.'>' . "\r\n";

	$attachments = array($fullPatch);

	//El mensaje a enviar. Se puede incluir HTML si enviamos el email en modo HTML y no texto.
	$tableLines = '';
	$flag = true;
	$country = "";
	$state = "";
	$zipcode = "";

	foreach($productList as $product){
		$tableLines .= '<tr>
								<th style="width:25%; border: 1px solid;" align="center">'.$product['productID'].'</th>
								<td style="width:25%; border: 1px solid;" align="center">'.$product['productDescription'].'</td>
								<td style="width:25%; border: 1px solid;" align="center">'.$product['productQuantity'].'</td>
								<td style="width:25%; border: 1px solid;" align="center">'.'$' . number_format($product['productPrice'],2).'</td>
								<td style="width:25%; border: 1px solid;" align="center">'.$product['distributorName'].'</td>
							</tr>';
		$orderId = $product['orderId'];
		$totalAmount = '$' . number_format($product['totalAmount'], 2);
		if($flag == true){
			$country = $product["country"];
			$state = $product["state"];
			$zipcode = $product["zipcode"];
			$flag = false;
		}
	}

	$userData = $current_user->data;
	$formatDate = date("Y-m-d h:i:s");
	$message ='
				<html>
				   <head>
					<font FACE="impact" SIZE=6 COLOR="red">O</font><font FACE="impact" SIZE=6 COLOR="black">PENTRADE</font>
					<br/>
					  <h1>Purchase Order</h1>
				   </head>
				   <body>
					  <table>
						 <tr>
							<th>Client Name:</th>
							<td>'.$userData->display_name.'</td>
						 </tr>
						 <tr>
							<th>Client Email:</th>
							<td>'.$userData->user_email.'</td>
						 </tr>
						 <tr>
							<th>Order ID:</th>
							<td>'.$orderId.'</td>
						 </tr>
						 <tr>
							<th>Order Amount:</th>
							<td>'.$totalAmount.'</td>
						 </tr>
						 <tr>
                        	<th>User Location:</th>
                        	<td>'.$country.' - '.$state.'</td>
                    	</tr>
                    	<tr>
                        	<th>ZIP / Postal Code:</th>
                        	<td>'.$zipcode.'</td>
                    	</tr>
					  </table>
					  <br/>
					  <table>
						 <tr>
							<th style="width:25%; border: 1px solid;">Id</th>
							<th style="width:25%; border: 1px solid;">Description</th>
							<th style="width:25%; border: 1px solid;">Quantity</th>  
							<th style="width:25%; border: 1px solid;">Price</th>  
							<th style="width:25%; border: 1px solid;">Distributor Name</th>  
						 </tr>'.$tableLines.'   
					  </table>
					  <br/>
					  <table>
						 <tr>
							<th>Date:</th>
							<td><label>'.$formatDate.'</label></td>
						 </tr>
					  </table>
				   </body>
				</html>';


	//Filtro para indicar que email debe ser enviado en modo HTML
	add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

	//Cambiamos el remitente del email que en Wordpress por defecto es tu email de admin
	add_filter('wp_mail_from','mqw_email_from');

	function mqw_email_from($content_type) {
		return 'info@opentradeinc.com';
	}

	add_filter( 'wp_mail_from_name', function( $name ) {
		return 'Opentradeinc';
	});

	//Por último enviamos el email
	wp_mail( $to, $subject, $message, $headers,$attachments);

}

function redirectPurchaseOrder() {
	ob_start();
	header('Location: '.get_site_url().'/purchase-order-sent/');
	ob_end_flush();
	die();
}

function verifyOfferValue(){
	$valueExit = false;
	foreach ($_POST["offerValue"] as $value){
		if($value != ""){
			$valueExit = true;
		}
	}

	return $valueExit;
}

function verifyZipcode(){
	$valueExit = false;

	if($_POST["calc_shipping_postcode"]!=null){
		$valueExit = true;
	}
	return $valueExit;
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
