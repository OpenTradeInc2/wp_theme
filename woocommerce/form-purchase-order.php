<?php
/*
Template Name: Purchase Order
*/
get_header();

?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<h3 id="order_review_heading"><?php _e( 'Order Detail', 'woocommerce' ); ?></h3>
	<?php if( isset($_POST['message-success']) ) {?>
		<p>Thank you for your purchase, soon we will contact you.</p>
	<?php } else {?>
	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		<?php add_action('woocommerce_after_checkout_form', 'createPurchase'); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
	<?php }?>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<?php
get_footer();
function createPurchase() {

	//--
	echo '
		<form action="" method="post" enctype="multipart/form-data">
		<label for="file">Please select File:</label>
		<input type="file" name="orderFile" id="orderFile" accept=".pdf"> 	
		<br><br>
		<input type="submit" value="Create Purchase Order" name="actionCreateOrder" class="checkout-button fusion-button button-default button-medium button default medium alt wc-forward">
	</form>';
	//--
	echo '</div>';

	if(isset($_POST["actionCreateOrder"])) {
		try {
			$file = $_FILES['orderFile'];
			$filename = $file['name'];
			$fileType = pathinfo($filename, PATHINFO_EXTENSION);

			if ($fileType != 'pdf') {
				throw new Exception( __( 'Invalid file type. Only pdf type are accepted.', 'woocommerce' ) );
			}

			$formatDate = date("Ymdhis");
			global $current_user;
			$current_user = wp_get_current_user();

			$target_dir = plugin_dir_path(__FILE__) . "uploads/";
			$fullPatch = getFullPatch($target_dir, $file, $formatDate, $current_user);

			if(uploadFile($file, $fullPatch)){
				$productList = createPurchaseOrderDB($current_user, $formatDate, $filename, $fullPatch);
				sendPurchaseOrderEmail($productList, $current_user, $formatDate,$fullPatch);
				wc_add_notice( 'Order Created', 'success' );
				WC()->cart->empty_cart();
				redirectPurchaseOrder();

			}else{
				throw new Exception( __( 'File was not uploaded.', 'woocommerce' ) );
			}

		}catch ( Exception $e ) {
			if ( ! empty( $e ) ) {
				wc_add_notice( $e->getMessage(), 'error' );
			}
		}
	}
}
?>
<?php
	function createPurchaseOrderDB($current_user,$formatDate, $filename, $fullPatch){
		global $wpdb;

		$productList = array();

		if($wpdb->check_connection()){

			$cart = WC()->cart;
			$totalAmount = $cart->total;

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
							".$cart->total.",
							'".$filename."',
							'".$fullPatch."');");

			$orderId = $wpdb->insert_id;

			foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {

				$wpdb->query("INSERT INTO `ot_custom_product_purchase_order`
								(`product_id`,
								`purchase_order_id`,
								`quantity`,
								`added_by`,
								`added_date`)
								VALUES
								(".$cart_item[product_id].",
								".$orderId.",
								".$cart_item[quantity].",
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

				$newProduct  = array( 'productID' => $cart_item[product_id], 'productDescription'=>$post->post_title, 'productQuantity'=>$cart_item[quantity], 'productPrice'=>$data->price, 'orderId'=>$orderId, 'totalAmount'=>$totalAmount, 'distributorName'=>$distributorName);
				array_push($productList,$newProduct);
			}
		}
		return $productList;
	}
?>
<?php
	function sendPurchaseOrderEmail($productList, $current_user, $formatDate, $fullPatch){

		$to = get_option('open-trade-emails');

		//Asunto del email
		$subject='New Purchase Order';

		//La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
		$headers = 'Reply-to: '.'Michael'.' '.'Lin'.' <'.'michael.lin@opentradeinc.com'.'>' . "\r\n";

		$attachments = array($fullPatch);

		//El mensaje a enviar. Se puede incluir HTML si enviamos el email en modo HTML y no texto.
		$tableLines = '';
		
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
?>
<?php
	function redirectPurchaseOrder() {
		ob_start();
		$url = get_permalink( get_page_by_title( 'Purchase Order Created' ) );
		header('Location: '.$url);
		ob_end_flush();
	die();
	}
?>
