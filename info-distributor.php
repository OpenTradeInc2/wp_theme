<?php
/*
Template Name: Information Distributor
*/

get_header();
get_registered_nav_menus();

session_start();
$countries = WC()->countries->get_shipping_countries();
$country = $countries[$_SESSION["selectCountry"]];

$states = WC()->countries->get_states($_SESSION["selectCountry"]);

?>
<div id="content" class="" style="width:100%;">
    <h1 class="entry-title">Confirm your registration information.</h1>
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="" enctype="multipart/form-data">
    <table align="left">
        <tr>
            <th style="width: 40%;">Registration Type:</th>
            <td><?php echo $_SESSION["RegistrationType"]?></td>
        </tr>
        <tr>
            <th style="width: 40%;">Company Name:</th>
            <td><label id="lblCompanyName"><?php echo $_SESSION["nameDistributor"]?></label></td>
        </tr>
        <tr>
            <th style="width: 40%;">Address:</th>
            <td><label id="lblAddress"><?php echo $_SESSION["locationDistributor"]?></label></td>
        </tr>
        <tr>
            <th style="width: 40%;">Tax Id:</th>
            <td><label id="lblTaxId"><?php echo $_SESSION["taxIdDistributor"]?></label></td>
        </tr>
        <tr>
            <th style="width: 40%;">City:</th>
            <td><label id="lblCity"><?php echo $_SESSION["city"]?></label></td>
        </tr>
        <tr>
            <th style="width: 40%;">State:</th>
            <td><label id="lblState"><?php echo $states[$_SESSION["selectState"]]?></label></td>
        </tr>
        <tr>
            <th style="width: 40%;">ZIP/Postal Code:</th>
            <td><label id="lblZipcode"><?php echo $_SESSION["zip_postal"]?></label></td>
        </tr>
        <tr>
            <th style="width: 40%;">Country:</th>
            <td><label id="lblCountry"><?php echo $countries[$_SESSION["selectCountry"]]?></label></td>
        </tr>
        <tr>
            <th style="width: 40%;">Your First Name:</th>
            <td><label id="lblFirstName"><?php echo $_SESSION["first_name"]?></label></td>
        </tr>
        <tr>
            <th style="width: 40%;">Your Last Name:</th>
            <td><label id="lblLastName"><?php echo $_SESSION["last_name"]?></label></td>
        </tr>
        <tr>
            <th style="width: 40%;">Email:</th>
            <td><label id="lblEmail"><?php echo $_SESSION["email"]?></label></td>
        </tr>
        <tr>
            <th style="width: 40%;">Your Job Tittle:</th>
            <td><label id="lblJobTittle"><?php echo $_SESSION["tittle_job"]?></label></td>
        </tr>
        <tr>
            <td><br></td>
            <td><br></td>
        </tr>
        <tr>
            <td align="left"><input id="doAction" class="gmw-submit gmw-submit-1" value="Confirm" type="submit" name="actionCreatePublicUserDistributor"></td>
            <td align="left"><input id="doBack" class="gmw-submit gmw-submit-1" value="Cancel" type="submit" name="actionCreatePublicUserDistributorBack"></td>
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
    if(isset($_SESSION["nameDistributor"]) and $_SESSION["nameDistributor"] !== ""){

        global $wpdb;
        $distributorName = $_SESSION['nameDistributor'];
        $locationDistributor = $_SESSION['locationDistributor'];
        $taxIdDistributor =$_SESSION['taxIdDistributor'];
        $city = $_SESSION['city'];
        $state = $_SESSION['selectState'];
        $zipcode = $_SESSION['zip_postal'];
        $country = $_SESSION['selectCountry'];
        $current_user = getCurrentUser();

        if($wpdb->check_connection()){

            if(isset($_SESSION["email"]) and $_SESSION["email"] !== ""){
                $type = '';
                if($_SESSION["RegistrationType"] == 'Distributor'){
                    $type = 'Distributor';
                }

                $date = getFormatDate();
                $wpdb->query("INSERT INTO `ot_custom_distributor`
                (`distributor_name`, `location`,`tax_id`, `added_by`, `added_date`, `status`, `email_administrator`, `city`,`state`,`zipcode`,`country`,`type`)
                VALUES
                ('".$distributorName."', '".$locationDistributor."', '".$taxIdDistributor."', ".$current_user->ID.", '".$date."', 'pending-approval', '".$_SESSION["email"]."', '".$city."', '".$state."', '".$zipcode."', '".$country."','".$type."');");

                $IdDistributor = $wpdb->insert_id;
                createPublicUser($_SESSION["email"],$_SESSION["email"],$_SESSION["first_name"],$_SESSION["last_name"],$IdDistributor);
                $fullName = $_SESSION["first_name"]." ".$_GET["last_name"];
                sendEmail($current_user, $distributorName, $locationDistributor, $taxIdDistributor, $_SESSION["email"], $fullName, $_SESSION["email"],$city, $state, $zipcode, $country);
                redirect();
            }
        }else{
            showMessage("ERROR Data Base!");
        }
    }
}

else if (isset($_POST["actionCreatePublicUserDistributorBack"])){
    redirectBack();
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

    if($_SESSION["RegistrationType"] == 'Distributor'){
        $role = get_role( 'open-trade-contributor' );

        if($role == null){
            $newRole =get_role( 'administrator' );
            add_role('open-trade-contributor', 'OT Role', $newRole->capabilities);
            $role =get_role( 'open-trade-contributor' );
        }
    } else {
        $role =get_role( 'customer' );
    }

    $user = new WP_User( $user_id );
    $user->set_role( $role->name );

    addPublicUserDistributor($user_id, $distributorID);

    return $user_id;
}

function addPublicUserDistributor($userId, $distributorID){
    global $wpdb;

    $regType = $_SESSION["RegistrationType"];
    $tittle_job = $_SESSION["tittle_job"];

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

function sendEmail($current_user, $companyName, $location, $tax, $userLogin, $userName, $userEmail, $city, $state, $zipcode, $country){

    $to = get_option('open-trade-emails');

    //Asunto del email
    $subject='New Company Is Registered';
    $subjectClient='Your Company Is Registered';

    //La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
    $headers = 'Reply-to: '.'Michael'.' '.'Lin'.' <'.'michael.lin@Opentradeinc.com'.'>' . "\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $formatDate = date("Y-m-d h:i:s");

    $countries = WC()->countries->get_countries();
    $countryName = $countries[$country];
    $states = WC()->countries->get_states($country);
    $stateName = $states[$state];

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
                            <th>City:</th>
                            <td>'.$city.'</td>
                        </tr>
                        <tr>
                            <th>State:</th>
                            <td>'.$stateName.'</td>
                        </tr>
                        <tr>
                            <th>Zipcode:</th>
                            <td>'.$zipcode.'</td>
                        </tr> 
                        <tr>
                            <th>Country:</th>
                            <td>'.$countryName.'</td>
                        </tr>                         
                        <tr>
                            <th>Name:</th>
                            <td>'.$userName.'</td>
                        </tr>
                        <tr>
                            <th>UserName:</th>
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
                <p>Dear Customer,</p>                
                <p>Thank you for your registration. Below is a copy of the information that you have entered.</p>                
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
                            <th>City:</th>
                            <td>'.$city.'</td>
                        </tr>
                        <tr>
                            <th>State:</th>
                            <td>'.$stateName.'</td>
                        </tr>
                        <tr>
                            <th>Zipcode:</th>
                            <td>'.$zipcode.'</td>
                        </tr> 
                        <tr>
                            <th>Country:</th>
                            <td>'.$countryName.'</td>
                        </tr> 
                        <tr>
                            <th>User Name:</th>
                            <td>'.$userEmail.'</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>'.$userName.'</td>
                        </tr>                       
                    </table>                    
                    <br/>
                    <table>
                        <tr>
                            <th>Date:</th>
                            <td><label>'.$formatDate.'</label></td>
                        </tr>
                    </table>                   
                    <p>Your account information is being reviewed.  Upon approval, we will send a confirmation email to you with login ID and password.</p>                  
                    <p>Thank you.</p>
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
        return 'Opentrade';
    });

    //Por último enviamos el email
    wp_mail( $to, $subject, $message, $headers);
    wp_mail( $userEmail, $subjectClient, $messageClient, $headers);


}

function redirect() {
    session_destroy();
    header('Location: '.get_site_url().'/company-registered/');
}

function redirectBack() {
    header('Location: '.get_site_url().'/registration/');
}

?>
