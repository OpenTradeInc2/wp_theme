<?php
/*
Template Name: Register Distributor
*/


get_header();

get_registered_nav_menus();

$urlPrivacyNotice = get_site_url().'/privacy';
$urlTermsService = get_site_url().'/tos';
$urlRegistration = get_site_url().'/register';
session_start();

?>
    <div class="" style="width:100%;">
        <h1 class="entry-title">Register your Company</h1>
        <p>Please register your company and user information below.</p>
        <?php

        if(isset($_GET["postback"])){
            if($_GET["postback"]=="1"){
                $_GET['email'] = $_SESSION['email'];
                $_GET['nameDistributor'] = $_SESSION['nameDistributor'];
                $_GET['locationDistributor'] = $_SESSION['locationDistributor'];
                $_GET['taxIdDistributor'] = $_SESSION['taxIdDistributor'];
                $_GET['city'] = $_SESSION['city'];
                $_GET['selectState'] = $_SESSION['selectState'];
                $_GET['zip_postal'] = $_SESSION['zip_postal'];
                $_GET['selectCountry'] = $_SESSION['selectCountry'];
                $_GET['first_name'] = $_SESSION['first_name'];
                $_GET['last_name'] = $_SESSION['last_name'];
                $_GET['email'] = $_SESSION['email'];
                $_GET['tittle_job'] = $_SESSION['tittle_job'];
            }
        }

        if (isset($_SESSION['message-error']) && $_SESSION['message-error'] !== "" ) {
            ?>
            <div id="message" class="woocommerce-error">
                <?php echo $_SESSION['message-error'] ?>
            </div>
            <?php
        }
        ?>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="" enctype="multipart/form-data">
            <!--
        <p>if you want to be part of our distributors, please enter the following information and soon we will contact you.</p>
        <table>
            <thead>
            <tr>
                <th scope="row"><label for="nameDistributor">Company Name: <span class="description">(required)</span></label></th>
                <td><input name="nameDistributor" id="nameDistributor" value="<?php echo $_POST['nameDistributor'];?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" type="text" ></td>
            </tr>
            <tr>
                <th scope="row"><label for="locationDistributor">Location: </label></th>
                <td><textarea rows="4" cols="50" name="locationDistributor"><?php echo $_POST['locationDistributor'];?></textarea></td>
            </tr>
            <tr>
                <th scope="row"><label for="locationDistributor">Tax Id: </label></th>
                <td><input type="text" name="taxIdDistributor" value="<?php echo $_POST['taxIdDistributor'];?>"></td>
            </tr>
            </thead>
        </table>
        -->
            <p style="background-color: #2b2b2b; font-size:15px; font-weight: bold;"><label style="color: white;">Step 1: Tells us which registration type describes you better.</label></p>
            <table align="center">
                <tr align="center">
                    <td style="width: 250px;">
                        <input type="radio" name="RegistrationType" value="End User" <?php if(isset($_SESSION["RegistrationType"]) && $_SESSION["RegistrationType"]== "End User"){echo "checked=true";}?> >End User
                    </td>
                    <td style="width: 250px;">
                        <input type="radio" name="RegistrationType" value="Distributor" <?php if(isset($_SESSION["RegistrationType"]) && $_SESSION["RegistrationType"]== "Distributor"){echo "checked=true";}?>>Distributor
                    </td>
                    <td style="width: 250px;">
                        <input type="radio" name="RegistrationType" value="Broker" <?php if(isset($_SESSION["RegistrationType"]) && $_SESSION["RegistrationType"]== "Broker"){echo "checked=true";}?>>Broker
                    </td>
                </tr>
            </table>

            <p style="background-color: #2b2b2b; font-size:15px; font-weight: bold;"><label style="color: white;">Step 2: Tell us your address.</label></p>
            <table align="center" style="width: 50%;">
                <tr>
                    <th style="width: 20%;">*Company Name:</th>
                    <td><input type="text" name="nameDistributor" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_SESSION['nameDistributor'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">*Address</th>
                    <td><textarea style="resize: none;" cols="58" class="gmw-address gmw-full-address gmw-address-1" rows="3" name="locationDistributor"><?php echo $_SESSION['locationDistributor'];?></textarea></td>
                </tr>
                <tr>
                    <th scope="row" style="width: 20%;"><label for="locationDistributor">Tax Id: </label></th>
                    <td><input type="text" name="taxIdDistributor" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_SESSION['taxIdDistributor'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">*City:</th>
                    <td><input type="text" name="city" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_SESSION['city'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">*State:</th>
                    <td>
                        <select name="selectState" class="gmw-distance-select gmw-distance-select-1" style="width: 100%;">
                            <option value="">Select a state</option>
                            <option value="AL" <?php if("AL" === $_SESSION['selectState']){echo "selected";}?>>AL - Alabama</option>
                            <option value="AK" <?php if("AK" === $_SESSION['selectState']){echo "selected";}?>>AK - Alaska</option>
                            <option value="AZ" <?php if("AZ" === $_SESSION['selectState']){echo "selected";}?>>AZ - Arizona</option>
                            <option value="AR" <?php if("AR" === $_SESSION['selectState']){echo "selected";}?>>AR - Arkansas</option>
                            <option value="CA" <?php if("CA" === $_SESSION['selectState']){echo "selected";}?>>CA - California</option>
                            <option value="CO" <?php if("CO" === $_SESSION['selectState']){echo "selected";}?>>CO - Colorado</option>
                            <option value="CT" <?php if("CT" === $_SESSION['selectState']){echo "selected";}?>>CT - Connecticut</option>
                            <option value="DE" <?php if("DE" === $_SESSION['selectState']){echo "selected";}?>>DE - Delaware </option>
                            <option value="FL" <?php if("FL" === $_SESSION['selectState']){echo "selected";}?>>FL - Florida</option>
                            <option value="GA" <?php if("GA" === $_SESSION['selectState']){echo "selected";}?>>GA - Georgia</option>
                            <option value="HI" <?php if("HI" === $_SESSION['selectState']){echo "selected";}?>>HI - Hawaii</option>
                            <option value="ID" <?php if("ID" === $_SESSION['selectState']){echo "selected";}?>>ID - Idaho</option>
                            <option value="IL" <?php if("IL" === $_SESSION['selectState']){echo "selected";}?>>IL - Illinois</option>
                            <option value="IN" <?php if("IN" === $_SESSION['selectState']){echo "selected";}?>>IN - Indiana</option>
                            <option value="IA" <?php if("IA" === $_SESSION['selectState']){echo "selected";}?>>IA - Iowa</option>
                            <option value="KS" <?php if("KS" === $_SESSION['selectState']){echo "selected";}?>>KS - Kansas</option>
                            <option value="KY" <?php if("KY" === $_SESSION['selectState']){echo "selected";}?>>KY - Kentucky</option>
                            <option value="LA" <?php if("LA" === $_SESSION['selectState']){echo "selected";}?>>LA - Luisiana</option>
                            <option value="ME" <?php if("ME" === $_SESSION['selectState']){echo "selected";}?>>ME - Maine</option>
                            <option value="MD" <?php if("MD" === $_SESSION['selectState']){echo "selected";}?>>MD - Maryland</option>
                            <option value="MA" <?php if("MA" === $_SESSION['selectState']){echo "selected";}?>>MA - Massachusetts</option>
                            <option value="MI" <?php if("MI" === $_SESSION['selectState']){echo "selected";}?>>MI - Michigan</option>
                            <option value="MN" <?php if("MN" === $_SESSION['selectState']){echo "selected";}?>>MN - Minesota</option>
                            <option value="MS" <?php if("MS" === $_SESSION['selectState']){echo "selected";}?>>MS - Misisipi</option>
                            <option value="MO" <?php if("MO" === $_SESSION['selectState']){echo "selected";}?>>MO - Misuri</option>
                            <option value="MT" <?php if("MT" === $_SESSION['selectState']){echo "selected";}?>>MT - Montana</option>
                            <option value="NE" <?php if("NE" === $_SESSION['selectState']){echo "selected";}?>>NE - Nebraska</option>
                            <option value="NV" <?php if("NV" === $_SESSION['selectState']){echo "selected";}?>>NV - Nevada</option>
                            <option value="NH" <?php if("NH" === $_SESSION['selectState']){echo "selected";}?>>NH - New Hampshire</option>
                            <option value="NJ" <?php if("NJ" === $_SESSION['selectState']){echo "selected";}?>>NJ - New Jersey</option>
                            <option value="NM" <?php if("NM" === $_SESSION['selectState']){echo "selected";}?>>NM - New México</option>
                            <option value="NY" <?php if("NY" === $_SESSION['selectState']){echo "selected";}?>>NY - New York</option>
                            <option value="NC" <?php if("NC" === $_SESSION['selectState']){echo "selected";}?>>NC - North Carolina</option>
                            <option value="ND" <?php if("ND" === $_SESSION['selectState']){echo "selected";}?>>ND - North Dakota</option>
                            <option value="OH" <?php if("OH" === $_SESSION['selectState']){echo "selected";}?>>OH - Ohio</option>
                            <option value="OK" <?php if("OK" === $_SESSION['selectState']){echo "selected";}?>>OK - Oklahoma</option>
                            <option value="OR" <?php if("OR" === $_SESSION['selectState']){echo "selected";}?>>OR - Oregon</option>
                            <option value="PA" <?php if("PA" === $_SESSION['selectState']){echo "selected";}?>>PA - Pennsylvania</option>
                            <option value="RI" <?php if("RI" === $_SESSION['selectState']){echo "selected";}?>>RI - Rhode Island</option>
                            <option value="SC" <?php if("SC" === $_SESSION['selectState']){echo "selected";}?>>SC - South Carolina</option>
                            <option value="SD" <?php if("SD" === $_SESSION['selectState']){echo "selected";}?>>SD - South Dakota</option>
                            <option value="TN" <?php if("TN" === $_SESSION['selectState']){echo "selected";}?>>TN - Tennessee</option>
                            <option value="TX" <?php if("TX" === $_SESSION['selectState']){echo "selected";}?>>TX - Texas</option>
                            <option value="UT" <?php if("UT" === $_SESSION['selectState']){echo "selected";}?>>UT - Utah</option>
                            <option value="VT" <?php if("VT" === $_SESSION['selectState']){echo "selected";}?>>VT - Vermont</option>
                            <option value="VA" <?php if("VA" === $_SESSION['selectState']){echo "selected";}?>>VA - Virginia</option>
                            <option value="WA" <?php if("WA" === $_SESSION['selectState']){echo "selected";}?>>WA - Washington</option>
                            <option value="WV" <?php if("WV" === $_SESSION['selectState']){echo "selected";}?>>WV - West Virginia</option>
                            <option value="WI" <?php if("WI" === $_SESSION['selectState']){echo "selected";}?>>WI - Wisconsin</option>
                            <option value="WY" <?php if("WY" === $_SESSION['selectState']){echo "selected";}?>>WY - Wyoming</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th style="width: 20%;">*ZIP/Postal Code:</th>
                    <td><input type="text" name="zip_postal" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_SESSION['zip_postal'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">*Country:</th>
                    <td>
                        <select name="selectCountry" style="width: 100%;" class="gmw-address gmw-full-address gmw-address-1  ">
                            <option value="US">United States</option>
                        </select>
                    </td>
                </tr>
            </table>

            <p style="background-color: #2b2b2b; font-size:15px; font-weight: bold;"><label style="color: white;">Step 3: Tell us who you are.</label></p>
            <table align="center" style="width: 50%;">
                <tr>
                    <th style="width: 20%;">*Your First Name:</th>
                    <td><input type="text" name="first_name" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_SESSION['first_name'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">*Your Last Name:</th>
                    <td><input type="text" name="last_name" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_SESSION['last_name'];?>"></td>
                </tr>
                <tr>
                    <th scope="row" style="width: 20%;"><label for="email">Email <span class="description">(required):</span></label></th>
                    <td><input name="email" id="email" type="email" class="input-text" value="<?php echo $_SESSION['email'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">Your Job Tittle:</th>
                    <td><input type="text" name="tittle_job" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_SESSION['tittle_job'];?>" class="gmw-address gmw-full-address gmw-address-1  "></td>
                </tr>

            </table>
            <br/>
            <div style="width: 585px; height: 150px; margin-left: 25%; background-color: #f8f8f8; border-width: 2px; border-color: #1e73be; border-style: solid;">
                <table align="center">
                    <tr>
                        <td colspan="2" align="center"><h1 style="font-size: 20px;">*TERMS OF REGISTRATION</h1></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="1%" valign="top"><input type="checkbox" name="cbxTermsConditions" id="cbxTermsConditions"></td>
                        <td valign="top">
                            I acknowledge that I have read and agree to the Opentrade <a href="<?php echo $urlTermsService;?>"><font style="font-weight: bold;">Terms of Service</font></a>, and understand
                                that information I submit will be used as described on this page and in the Opentrade <a href="<?php echo $urlPrivacyNotice;?>"><font style="font-weight: bold;">Privacy Policy.</font></a>
                        </td>
                    </tr>
                </table>
            </div>
            <br/>
            <table id="tbRegister" align="center">
                <tr>
                    <td align="center"><input id="doAction" class="gmw-submit gmw-submit-1" value="Register" type="submit" name="actionCreatePublicUserDistributor"></td>
                    <td align="center"><input id="cancel" class="gmw-submit gmw-submit-1" value="Cancel" type="submit" name="cancelPublicUserDistributor"></td>
                </tr>
            </table>

        </form>

        <!-- Scripts necesarios para poder aplicar el bootstrap -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

        <!-- div que incluye un a para poder hacer el enlace con el codigo HTML a aplicar el bootstrap -->
        <div style="visibility: hidden;">
            <a id="a_clicker" href="#clicker" data-toggle="modal">Prueba</a>
        </div>

        <!-- estructura basica del mensaje compuesta por un modal-content (modal-heaeder y modal-body) -->
        <div class="modal fade" id="clicker" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <font face="impact" size="18px" color="red">WARNING!</font>
                    </div>
                    <div class="modal-body">
                        <font face="impact" size="6px" color="black">We cannot register a duplicate user</font>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default gmw-submit-1" href="<?php echo $urlRegistration."?postback=1"?>">OK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Funcion Javascript que permite hacer click en el mensaje para que se muestre el bootstrap -->
<script language="JavaScript">
    function clickerAnchor(){
        var a = document.getElementById("a_clicker");
        a.click();
    }

</script>


<?php
do_action( 'avada_after_content' );
get_footer();
?>

<?php

if(isset($_POST["actionCreatePublicUserDistributor"])) {
    $_SESSION['RegistrationType'] = $_POST["RegistrationType"];
    $_SESSION['email'] = $_POST["email"];
    $_SESSION['nameDistributor'] = $_POST["nameDistributor"];
    $_SESSION['locationDistributor'] = $_POST["locationDistributor"];
    $_SESSION['taxIdDistributor'] = $_POST["taxIdDistributor"];
    $_SESSION['city'] = $_POST["city"];
    $_SESSION['selectState'] = $_POST["selectState"];
    $_SESSION['zip_postal'] = $_POST["zip_postal"];
    $_SESSION['selectCountry'] = $_POST["selectCountry"];
    $_SESSION['first_name'] = $_POST["first_name"];
    $_SESSION['last_name'] = $_POST["last_name"];
    $_SESSION['tittle_job'] = $_POST["tittle_job"];

    $resultVerify = verifyAllRequiredFields();
    if($resultVerify === ""){

        global $wpdb;

        $distributorName = $_POST['nameDistributor'];
        $locationDistributor = $_POST['locationDistributor'];
        $taxIdDistributor =$_POST['taxIdDistributor'];
        $city = $_POST['city'];
        $state = $_POST['selectState'];
        $zipcode = $_POST['zip_postal'];
        $country = $_POST['selectCountry'];
        $current_user = getCurrentUser();

        if($wpdb->check_connection()){
            $userLogin = get_user_by('login', $_POST["email"]);
            if(!$userLogin){
                $email =email_exists($_POST["email"]);
                if(!$email){
                    redirect();
                }else{
                    // Llamado a la funcion Javascript para poder aplicar el bootstrap
                    echo "<script>";
                    echo "clickerAnchor();";
                    echo "</script>";
                }
            }else{
                // Llamado a la funcion Javascript para poder aplicar el bootstrap
                echo "<script>";
                echo "clickerAnchor();";
                echo "</script>";
            }
        }else{
            $_SESSION['message-error'] = "<li>ERROR Data Base!</li>";
            redirectErrors();
        }
    }else{
        //$_SESSION['message-error'] = $resultVerify;
        redirectErrors();
    }
}

if(isset($_POST["cancelPublicUserDistributor"])) {
    session_destroy();
    $url = get_permalink(get_page_by_title('Homepage'));
    header("Location:".$url);
}

function redirect() {

    $url = get_permalink(get_page_by_title('Company Information'));
    header('Location:'.$url);
}

function redirectErrors() {

    $url = get_permalink(get_page_by_title('Register Distributor'));
    header('Location:'.$url);
}

function verifyAllRequiredFields(){
    $result="";
    if(!isset($_POST["nameDistributor"]) or $_POST["nameDistributor"] === ""){
        $result .= "<li>Company name is required!</li>";
    }

    if(!isset($_POST["locationDistributor"]) or $_POST["locationDistributor"] === ""){
        $result .= "<li>Address is required!</li>";
    }

    if(!isset($_POST["city"]) or $_POST["city"] === ""){
        $result .= "<li>City is required!</li>";
    }

    if(!isset($_POST["selectState"]) or $_POST["selectState"] === ""){
        $result .= "<li>State is required!</li>";
    }

    if(!isset($_POST["zip_postal"]) or $_POST["zip_postal"] === ""){
        $result .= "<li>Zip/Postal code is required!</li>";
    }

    if(!isset($_POST["selectCountry"]) or $_POST["selectCountry"] === ""){
        $result .= "<li>Country is required!</li>";
    }

    if(!isset($_POST["first_name"]) or $_POST["first_name"] === ""){
        $result .= "<li>First name is required!</li>";
    }

    if(!isset($_POST["last_name"]) or $_POST["last_name"] === ""){
        $result .= "<li>Last name is required!</li>";
    }

    if(!isset($_POST["email"]) or $_POST["email"] === ""){
        $result .= "<li>Email is required!</li>";
    }

    if(!isset($_POST["cbxTermsConditions"]) or $_POST["cbxTermsConditions"] !== "on"){
        $result .= "<li>The terms and conditions must be accepted!</li>";
    }

    $_SESSION['message-error'] = $result;
    return $result;
}