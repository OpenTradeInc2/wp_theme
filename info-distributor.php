<?php
/*
Template Name: Information Distributor
*/

get_header();
get_registered_nav_menus();

$countries = WC()->countries->get_shipping_countries();
$country = $countries[$_GET["selectCountry"]];

$states = WC()->countries->get_states($_GET["selectCountry"]);

?>
<div id="content" class="" style="width:100%;">
    <h1 class="entry-title">Distributor Information</h1>
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="" enctype="multipart/form-data">
    <table>
        <tr>
            <th>Registration Type:</th>
            <td><?php echo $_GET["RegistrationType"]?></td>
        </tr>
        <tr>
            <th>Company Name:</th>
            <td><label id="lblCompanyName"><?php echo $_GET["nameDistributor"]?></label></td>
        </tr>
        <tr>
            <th>Address:</th>
            <td><label id="lblAddress"><?php echo $_GET["locationDistributor"]?></label></td>
        </tr>
        <tr>
            <th>Tax Id:</th>
            <td><label id="lblTaxId"><?php echo $_GET["taxIdDistributor"]?></label></td>
        </tr>
        <tr>
            <th>City:</th>
            <td><label id="lblCity"><?php echo $_GET["city"]?></label></td>
        </tr>
        <tr>
            <th>State:</th>
            <td><label id="lblState"><?php echo $states[$_GET["selectState"]]?></label></td>
        </tr>
        <tr>
            <th>ZIP/Postal Code:</th>
            <td><label id="lblZipcode"><?php echo $_GET["zip_postal"]?></label></td>
        </tr>
        <tr>
            <th>Country:</th>
            <td><label id="lblCountry"><?php echo $countries[$_GET["selectCountry"]]?></label></td>
        </tr>
        <tr>
            <th>Your First Name:</th>
            <td><label id="lblFirstName"><?php echo $_GET["first_name"]?></label></td>
        </tr>
        <tr>
            <th>Your Last Name:</th>
            <td><label id="lblLastName"><?php echo $_GET["last_name"]?></label></td>
        </tr>
        <tr>
            <th>Email:</th>
            <td><label id="lblEmail"><?php echo $_GET["email"]?></label></td>
        </tr>
        <tr>
            <th>Your Job Tittle:</th>
            <td><label id="lblJobTittle"><?php echo $_GET["tittle_job"]?></label></td>
        </tr>
    </table>
    <br/>
    <table align="center">
        <tr>
            <td align="center"><input id="doAction" class="gmw-submit gmw-submit-1" value="Register" type="submit" name="actionCreatePublicUserDistributor"></td>
        </tr>
    </table>
        </form>
</div>
<?php
do_action( 'avada_after_content' );
get_footer();
?>

<?php

if(isset($_POST["actionCreatePublicUserDistributor"])) {
    if(isset($_GET["nameDistributor"]) and $_GET["nameDistributor"] !== ""){

        global $wpdb;
        $distributorName = $_GET['nameDistributor'];
        $locationDistributor = $_GET['locationDistributor'];
        $taxIdDistributor =$_GET['taxIdDistributor'];
        $city = $_GET['city'];
        $state = $_GET['selectState'];
        $zipcode = $_GET['zip_postal'];
        $country = $_GET['selectCountry'];
        $current_user = getCurrentUser();

        if($wpdb->check_connection()){

            if(isset($_GET["email"]) and $_GET["email"] !== ""){
                $userLogin = get_user_by('login', $_GET["email"]);

                if(!$userLogin){
                    if(isset($_GET["email"]) and $_GET["email"] !== ""){
                        $email =email_exists($_GET["email"]);
                        if(!$email){

                            $date = getFormatDate();
                            $wpdb->query("INSERT INTO `ot_custom_distributor`
                            (`distributor_name`, `location`,`tax_id`, `added_by`, `added_date`, `status`, `email_administrator`, `city`,`state`,`zipcode`,`country`)
                            VALUES
                            ('".$distributorName."', '".$locationDistributor."', '".$taxIdDistributor."', ".$current_user->ID.", '".$date."', 'pending-approval', '".$_POST["email"]."', '".$city."', '".$state."', '".$zipcode."', '".$country."');");

                            $IdDistributor = $wpdb->insert_id;
                            createPublicUser($_GET["email"],$_GET["email"],$_GET["first_name"],$_GET["last_name"],$IdDistributor);
                            $fullName = $_GET["first_name"]." ".$_GET["last_name"];
                            sendEmail($current_user, $distributorName, $locationDistributor, $taxIdDistributor, $_GET["email"], $fullName, $_GET["email"]);
                            redirect();

                        }else{
                            showMessage("Email already exists!");
                            header("Refresh:0");
                        }
                    }else{
                        showMessage("Email is required!");
                        header("Refresh:0");
                    }
                }else{
                    showMessage("User Name already exists!");
                    header("Refresh:0");
                }
            }else{
                showMessage("Please set valid user name!");
                header("Refresh:0");
            }
        }else{
            showMessage("ERROR Data Base!");
            header("Refresh:0");
        }
    }
}

function showMessage($message){
    $_GET['message-error'] = $message;
    header("Refresh:0");
}

function createPublicUser($userName, $email, $first_name, $lastName, $distributorID){
    $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
    $user_id = wp_create_user( $userName, $random_password, $email );

    $fullName = $first_name." ".$lastName;

    wp_update_user(
        array(
            'ID'          =>    $user_id,
            'nickname'    =>    $fullName,
            'display_name' => $fullName
        )
    );

    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $lastName);

    $role =get_role( 'open-trade-contributor' );

    if($role == null){
        $newRole =get_role( 'administrator' );
        add_role('open-trade-contributor', 'OT Role', $newRole->capabilities);
        $role =get_role( 'open-trade-contributor' );
    }

    $user = new WP_User( $user_id );
    $user->set_role( $role->name );

    addPublicUserDistributor($user_id, $distributorID);

    return $user_id;
}

function addPublicUserDistributor($userId, $distributorID){
    global $wpdb;

    $regType = $_GET["RegistrationType"];
    $tittle_job = $_GET["tittle_job"];

    if($wpdb->check_connection()){

        $user = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."users` WHERE `ID` = ".$userId.";");
        $wpdb->query("INSERT INTO `ot_custom_distributor_user`
                                    (`distributor_user_username`,
                                    `distributor_user_fullname`,
                                    `distributor_user_distributor_id`,
                                    `distributor_user_userid`,
                                    `distributor_user_added_by`,
                                    `distributor_user_added_date`,
                                    `status`,
                                    `distributor_user_reg_type`,
                                    `distributor_user_job_function`,
                                    `distributor_user_other_job`,
                                    `distributor_user_job_tittle`)
                             VALUES
                                    ('".$user[0]->user_login."',
                                     '".$user[0]->display_name."',
                                      ".$distributorID.",
                                      ".$userId.",
                                      ".getCurrentUser()->ID.",
                                      '".getFormatDate()."',
                                      'pending-approval',
                                      '".$regType."',
                                      '',
                                      '',
                                      '".$tittle_job."');");
    }
}

function sendEmail($current_user, $companyName, $location, $tax, $userLogin, $userName, $userEmail){

    $to = get_option('open-trade-emails');

    //Asunto del email
    $subject='New Company Is Registered';

    //La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
    $headers = 'Reply-to: '.'Michael'.' '.'Lin'.' <'.'michael.lin@opentradeinc.com'.'>' . "\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $formatDate = date("Y-m-d h:i:s");

    $message ='
            <html>
                <head>
                <font FACE="impact" SIZE=6 COLOR="red">O</font><font FACE="impact" SIZE=6 COLOR="black">PENTRADE</font>
                <br/>
                    <h1>Company Information</h1>
                </head>
                <body>
                    <table>                    
                        <tr>
                            <th>Company Name:</th>
                            <td>'.$companyName.'</td>
                        </tr>
                        <tr>
                            <th>Location:</th>
                            <td>'.$location.'</td>
                        </tr>
                        <tr>
                            <th>Tax Id:</th>
                            <td>'.$tax.'</td>
                        </tr>
                        <tr>
                            <th>User Name:</th>
                            <td>'.$userLogin.'</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>'.$userName.'</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>'.$userEmail.'</td>
                        </tr>                        
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

    $messageClient ='
            <html>
                <head>
                <font FACE="impact" SIZE=6 COLOR="red">O</font><font FACE="impact" SIZE=6 COLOR="black">PENTRADE</font>
                <br/>
                <p>Thank you for your registration, Your account is pending approval, when your account is approved will receive a new email with access information.</p>
                <br/>
                    <h1>Your Company Information</h1>
                </head>
                <body>
                    <table>                    
                        <tr>
                            <th>Company Name:</th>
                            <td>'.$companyName.'</td>
                        </tr>
                        <tr>
                            <th>Location:</th>
                            <td>'.$location.'</td>
                        </tr>
                        <tr>
                            <th>Tax Id:</th>
                            <td>'.$tax.'</td>
                        </tr>
                        <tr>
                            <th>User Name:</th>
                            <td>'.$userLogin.'</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>'.$userName.'</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>'.$userEmail.'</td>
                        </tr>                        
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
    wp_mail( $userEmail, $subject, $messageClient, $headers);


}

function redirect() {
    //$url = get_permalink(get_page_by_title('Company Registered'));
    header('Location: '.get_site_url().'/company-registered/');
}

?>
