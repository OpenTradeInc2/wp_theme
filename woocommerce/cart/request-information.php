<?php
	/*
	Template Name: Request Information
	*/
	get_header();
?>
	<h1 class="entry-title">Request Information</h1>
	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="" enctype="multipart/form-data">

		<h3 id="order_review_heading"><?php _e( 'Order Detail', 'woocommerce' ); ?></h3>

			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				<br><br>
				<input type="submit" value="Get Information" name="actionRequestInformation" class="checkout-button fusion-button button-default button-medium button default medium alt wc-forward">
			</div>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		
	</form>
<?php

	if(isset($_POST["actionRequestInformation"])) {
		$current_user = wp_get_current_user();
		$formatDate = date("Ymdhis");
		$productList = createOrderDB($current_user, $formatDate);
		sendEmail($productList, $current_user, $formatDate);
		redirect();
	}

	get_footer();
?>
<?php
	function createOrderDB($current_user, $formatDate){
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
				$newProduct  = array( 'productID' => $cart_item[product_id], 'productDescription'=>$post->post_title, 'productQuantity'=>$cart_item[quantity] );
				array_push($productList,$newProduct);
			}

		}
		return $productList;
	}
?>
<?php
function sendEmail($productList, $current_user, $formatDate){

	$to = get_option('open-trade-emails');

	//Asunto del email
	$subject='New Request Information';

	//La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
	$headers = 'Reply-to: '.'Michael'.' '.'Lin'.' <'.'michael.lin@opentradeinc.com'.'>' . "\r\n";


	//El mensaje a enviar. Se puede incluir HTML si enviamos el email en modo HTML y no texto.
	$tableLines = '';
	foreach($productList as $product){
		$tableLines .= '<tr><th>'.$product['productID'].'</th><td>'.$product['productDescription'].'</td><td>'.$product['productQuantity'].'</td></tr>';
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
?>
<?php
	function redirect() {
		ob_start();
		$url = get_permalink( get_page_by_title( 'Request Information Created' ) );
		header('Location: '.$url);
		ob_end_flush();
		die();
	}
?>

