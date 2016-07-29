<?php
/*
Template Name: User Activated
*/
get_header();

?>
    <h1 class="entry-title">User Activated</h1>
    <br>
    <p>Congratulations, your user has been activated.</p>
    <p>We will send you an email with your password!</p>
    <a href="/wp-login.php">Login</a>
    <br>
<?php
if( is_page() && get_the_ID() == get_page_by_title('User Activated')->ID) {
    $user_id = filter_input( INPUT_GET, 'user', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
    if( $user_id ) {
        // get user meta activation hash field
        $code = get_user_meta( $user_id, 'has_to_be_activated', true );
        $codeSend =filter_input( INPUT_GET, 'key' );
        if( $codeSend == $code ) {
            delete_user_meta( $user_id, 'has_to_be_activated' );
            $user = get_user_by('ID', $user_id);
            $password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
            wp_set_password( $password, $user_id );
            $message = "<html>
                            <head>
                                <font FACE=\"impact\" SIZE=6 COLOR=\"red\">O</font><font FACE=\"impact\" SIZE=6 COLOR=\"black\">PENTRADE</font>
                                <br/>
                                <h1>Open Trade Credentials</h1>
                            </head>
                            <body>
                                Welcome to Open Trade, Your user is : ".$user->user_email." and your password is: ".$password."
                            </body>
                        </html>";

            add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

            wp_mail( $user->user_email, 'OpenTrade User Information', $message );
        }
    }
}

get_footer();