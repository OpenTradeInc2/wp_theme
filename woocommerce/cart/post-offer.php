<?php
/*
Template Name: Post Offer
*/
get_header();

?>
 <div id="content" class="fusion-portfolio-post" style="width:100%;">
    <h1 class="entry-title">Post Offer</h1>
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="" enctype="multipart/form-data">

        <h3 id="order_review_heading"><?php _e( 'Order Detail', 'woocommerce' ); ?></h3>

            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <table class="shop_table woocommerce-checkout-review-order-table" align="center">
                    <thead>
                    <tr>
                        <th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
                        <th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
                        <th class="product-total"><?php _e( 'Offer', 'woocommerce' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    do_action( 'woocommerce_review_order_before_cart_contents' );

                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            ?>
                            <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                                <td class="product-name">
                                    <?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;'; ?>
                                    <?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
                                    <?php echo WC()->cart->get_item_data( $cart_item ); ?>
                                </td>
                                <td class="product-total">
                                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                                </td>
                                <td class="offer">
                                    <?php $total = '$' . number_format($cart_item['line_subtotal'], 2); ?>
                                    <input class="gmw-address gmw-full-address gmw-address-1  " type="text" name="offerValue[]" id="offerId" value="<?php echo $total; ?>">
                                    <input type="hidden" name="productsId[]" value="<?php echo $cart_item[product_id]; ?>">
                                </td>
                            </tr>
                            <?php
                        }
                    }

                    do_action( 'woocommerce_review_order_after_cart_contents' );
                    ?>
                    </tbody>
                </table>
                <br><br>
                <input type="submit" value="Send Offer" name="actionCreateOffer" class="checkout-button fusion-button button-default button-medium button default medium alt wc-forward">
			<?php echo wp_get_current_user()->data->display_name?>
			<?php echo wp_get_current_user()->data->user_email?>
            </div>
            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
    </form>
	</div>
<?php

    if(isset($_POST["actionCreateOffer"])) {

        $result = true;

        foreach ($_POST['offerValue'] as $offer){
            if($offer == ''){
                $result = false;
            }
        }

        if($result){
            $current_user = wp_get_current_user();
            $formatDate = date("Ymdhis");
            $productList = createOfferDB($_POST['productsId'],$_POST['offerValue'],$current_user,$formatDate);
            sendEmail($productList, $current_user);
            redirect();
        } else{
            wc_add_notice( 'Please set a valid offer!', 'error' );
        }

    }

    get_footer();
?>
<?php
    function createOfferDB($products, $offers, $current_user, $formatDate){
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
                                    `added_date`)
                                  VALUES
                                    (".$current_user->ID.",
                                    ".$totalAmount.",
                                    ".$totalOffer.",
                                    ".$current_user->ID.",
                                    '".$formatDate."');");

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

                    $newProduct  = array( 'productID' => $product, 'productDescription'=>$post->post_title, 'productQuantity'=>$cartProduct[quantity], 'offer'=>$offers[$position], 'offerInformationId'=>$offerInformationId, 'totalAmount'=>$totalAmount, 'totalOffer'=> $totalOffer);
                    array_push($productList,$newProduct);
                }
                $position = $position+1;
            }
        }
        return $productList;
    }

    function sendEmail($productList, $current_user){

        $to = get_option('open-trade-emails');

        //Asunto del email
        $subject='New Post Offer';

        //La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
        $headers = 'Reply-to: '.'Michael'.' '.'Lin'.' <'.'michael.lin@opentradeinc.com'.'>' . "\r\n";


        //El mensaje a enviar. Se puede incluir HTML si enviamos el email en modo HTML y no texto.
        $tableLines = '';
        foreach($productList as $product){
            $tableLines .= '<tr><th style="width:25%; border: 1px solid;" align="center">'.$product['productID'].'</th><td style="width:25%; border: 1px solid;" align="center">'.$product['productDescription'].'</td><td style="width:25%; border: 1px solid;" align="center">'.$product['productQuantity'].'</td><td style="width:25%; border: 1px solid;" align="center">'.$product['offer'].'</td></tr>';
            $offerInformationId = $product['offerInformationId'];
            $total = '$' . number_format($product['totalAmount'], 2);
            $totalOffer = '$' . number_format($product['totalOffer'], 2);
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

    function redirect() {
        ob_start();
        $url = get_permalink( get_page_by_title( 'Post Offer Created' ) );
        header('Location: '.$url);
        ob_end_flush();
        die();
    }
?>