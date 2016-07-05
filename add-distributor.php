<?php
/*
Template Name: Register Distributor
*/

get_header();

get_registered_nav_menus();

?>
    <div class="" style="width:100%;">
        <h1 class="entry-title">Register your Company</h1>
        <p>Please register your company and user information below</p>
        <?php
        if (isset($_GET['message-error'])) {
            ?>
            <div id="message" class="woocommerce-error">
                <?php echo $_GET['message-error'] ?>
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
            <p style="background-color: #2b2b2b; font-size:15px; font-weight: bold;"><label style="color: white;">Step 1: Tells us which registration type describes you better</label></p>
            <table align="center">
                <tr>
                    <td>
                        <input type="radio" name="RegistrationType" value="End User">End User
                    </td>
                    <td>
                        <input type="radio" name="RegistrationType" value="Distributor">Distributor
                    </td>
                    <td>
                        <input type="radio" name="RegistrationType" value="Broker">Broker
                    </td>
                </tr>
            </table>

            <p style="background-color: #2b2b2b; font-size:15px; font-weight: bold;"><label style="color: white;">Step 2: Tell us your address</label></p>
            <table align="center" style="width: 50%;">
                <tr>
                    <th style="width: 20%;">*Company Name:</th>
                    <td><input type="text" name="nameDistributor" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_GET['nameDistributor'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">*Address</th>
                    <td><textarea style="resize: none;" cols="58" class="gmw-address gmw-full-address gmw-address-1" rows="3" name="locationDistributor"><?php echo $_GET['locationDistributor'];?></textarea></td>
                </tr>
                <tr>
                    <th scope="row" style="width: 20%;"><label for="locationDistributor">Tax Id: </label></th>
                    <td><input type="text" name="taxIdDistributor" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_GET['taxIdDistributor'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">*City:</th>
                    <td><input type="text" name="city" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_GET['city'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">*State:</th>
                    <td>
                        <select name="selectState" class="gmw-distance-select gmw-distance-select-1" style="width: 100%;">
                            <option value="">Select a state</option>
                            <option value="AL">AL - Alabama</option>
                            <option value="AK">AK - Alaska</option>
                            <option value="AZ">AZ - Arizona</option>
                            <option value="AR">AR - Arkansas</option>
                            <option value="CA">CA - California</option>
                            <option value="CO">CO - Colorado</option>
                            <option value="CT">CT - Connecticut</option>
                            <option value="DE">DE - Delaware </option>
                            <option value="FL">FL - Florida</option>
                            <option value="GA">GA - Georgia</option>
                            <option value="HI">HI - Hawaii</option>
                            <option value="ID">ID - Idaho</option>
                            <option value="IL">IL - Illinois</option>
                            <option value="IN">IN - Indiana</option>
                            <option value="IA">IA - Iowa</option>
                            <option value="KS">KS - Kansas</option>
                            <option value="KY">KY - Kentucky</option>
                            <option value="LA">LA - Luisiana</option>
                            <option value="ME">ME - Maine</option>
                            <option value="MD">MD - Maryland</option>
                            <option value="MA">MA - Massachusetts</option>
                            <option value="MI">MI - Michigan</option>
                            <option value="MN">MN - Minesota</option>
                            <option value="MS">MS - Misisipi</option>
                            <option value="MO">MO - Misuri</option>
                            <option value="MT">MT - Montana</option>
                            <option value="NE">NE - Nebraska</option>
                            <option value="NV">NV - Nevada</option>
                            <option value="NH">NH - New Hampshire</option>
                            <option value="NJ">NJ - New Jersey</option>
                            <option value="NM">NM - New México</option>
                            <option value="NY">NY - New York</option>
                            <option value="NC">NC - North Carolina</option>
                            <option value="ND">ND - North Dakota</option>
                            <option value="OH">OH - Ohio</option>
                            <option value="OK">OK - Oklahoma</option>
                            <option value="OR">OR - Oregon</option>
                            <option value="PA">PA - Pennsylvania</option>
                            <option value="RI">RI - Rhode Island</option>
                            <option value="SC">SC - South Carolina</option>
                            <option value="SD">SD - South Dakota</option>
                            <option value="TN">TN - Tennessee</option>
                            <option value="TX">TX - Texas</option>
                            <option value="UT">UT - Utah</option>
                            <option value="VT">VT - Vermont</option>
                            <option value="VA">VA - Virginia</option>
                            <option value="WA">WA - Washington</option>
                            <option value="WV">WV - West Virginia</option>
                            <option value="WI">WI - Wisconsin</option>
                            <option value="WY">WY - Wyoming</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th style="width: 20%;">*ZIP/Postal Code:</th>
                    <td><input type="text" name="zip_postal" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_GET['zip_postal'];?>"></td>
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

            <p style="background-color: #2b2b2b; font-size:15px; font-weight: bold;"><label style="color: white;">Step 3: Tell us who you are</label></p>
            <table align="center" style="width: 50%;">
                <tr>
                    <th style="width: 20%;">*Your First Name:</th>
                    <td><input type="text" name="first_name" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_GET['first_name'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">*Your Last Name:</th>
                    <td><input type="text" name="last_name" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_GET['last_name'];?>"></td>
                </tr>
                <tr>
                    <th scope="row" style="width: 20%;"><label for="email">Email <span class="description">(required)</span></label></th>
                    <td><input name="email" id="email" type="email" class="input-text" value="<?php echo $_GET['email'];?>"></td>
                </tr>
                <tr>
                    <th style="width: 20%;">Your Job Tittle:</th>
                    <td><input type="text" name="tittle_job" class="gmw-address gmw-full-address gmw-address-1" value="<?php echo $_GET['tittle_job'];?>" class="gmw-address gmw-full-address gmw-address-1  "></td>
                </tr>

            </table>
            <br/>
            <div style="width: 600px; height: 150px; margin-left: 25%; background-color: #f8f8f8; border-width: 2px; border-color: #1e73be; border-style: solid;">
                <table align="center">
                    <tr>
                        <td colspan="2" align="center"><h1 style="font-size: 20px;">*TERMS OF REGISTRATION</h1></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="1%" valign="top"><input type="checkbox" id="cbxTermsConditions" onclick="checkboxValidation()"></td>
                        <td valign="top">
                            I ackknowledge that I have read and agree to the Grainger <b>Terms of Sale</b> and <b>Termo of Access</b>, and understand
                                that information I submit will be used as described on this page and in the Grainger <b>Privacy Policy.</b>
                        </td>
                    </tr>
                </table>
            </div>
            <br/>
            <table id="tbRegister" align="center" style="visibility: hidden;">
                <tr>
                    <td align="center"><input id="doAction" class="gmw-submit gmw-submit-1" value="Register" type="submit" name="actionCreatePublicUserDistributor"></td>
                </tr>
            </table>
        </form>
    </div>
    <script language="JavaScript">
        function checkboxValidation() {
            var check = document.getElementById("cbxTermsConditions");
            if(check.checked){
                document.getElementById("tbRegister").style.visibility = "visible";
            }else{
                document.getElementById("tbRegister").style.visibility = "hidden";
            }
        }
    </script>
<?php
do_action( 'avada_after_content' );
get_footer();
?>

<?php

if(isset($_POST["actionCreatePublicUserDistributor"])) {
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
                    $_GET['message-error'] = "<li>Email already exists!</li>";
                    redirectErrors();
                }
            }else{
                $_GET['message-error'] = "<li>Email already exists!</li>";
                redirectErrors();
            }
        }else{
            $_GET['message-error'] = "<li>ERROR Data Base!</li>";
            redirectErrors();
        }
    }else{
        $_GET['message-error'] = $resultVerify;
        redirectErrors();
    }
}

function showMessage($message){
    $_GET['message-error'] = $message;
    header("Refresh:0");
}

function redirect() {

    $url = get_permalink(get_page_by_title('Company Information'));
    header('Location:'.$url.'?RegistrationType='.$_POST["RegistrationType"].'&nameDistributor='.$_POST["nameDistributor"].'&locationDistributor='.$_POST["locationDistributor"].
        '&taxIdDistributor='.$_POST["taxIdDistributor"].'&city='.$_POST["city"].'&selectState='.$_POST["selectState"].'&zip_postal='.$_POST["zip_postal"].
        '&selectCountry='.$_POST["selectCountry"].'&first_name='.$_POST["first_name"].'&last_name='.$_POST["last_name"].'&email='.$_POST["email"].'&tittle_job='.$_POST["tittle_job"]);

}

function redirectErrors() {

    $url = get_permalink(get_page_by_title('Register Distributor'));
    header('Location:'.$url.'?RegistrationType='.$_POST["RegistrationType"].'&nameDistributor='.$_POST["nameDistributor"].'&locationDistributor='.$_POST["locationDistributor"].
        '&taxIdDistributor='.$_POST["taxIdDistributor"].'&city='.$_POST["city"].'&selectState='.$_POST["selectState"].'&zip_postal='.$_POST["zip_postal"].
        '&selectCountry='.$_POST["selectCountry"].'&first_name='.$_POST["first_name"].'&last_name='.$_POST["last_name"].'&email='.$_POST["email"].'&tittle_job='.$_POST["tittle_job"].'&message-error='.$_GET['message-error']);

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

    return $result;
}