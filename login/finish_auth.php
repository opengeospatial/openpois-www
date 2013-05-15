<?php

require_once "common.php";
session_start();
run();

function run() {
    $consumer = getConsumer();

    // Complete the authentication process using the server's
    // response.
    $return_to = getReturnTo();
    $response = $consumer->complete($return_to);

    // Check the response status.
    if ($response->status == Auth_OpenID_CANCEL) {
        // This means the authentication was cancelled.
        $msg = 'Verification cancelled.';
    } else if ($response->status == Auth_OpenID_FAILURE) {
        // Authentication failed; display the error message.
        $msg = "OpenID authentication failed: " . $response->message;
    } else if ($response->status == Auth_OpenID_SUCCESS) {
       // This means the authentication succeeded; extract the
       // identity URL and Simple Registration data (if it was
       // returned).
       $openid = $response->getDisplayIdentifier();
			$_SESSION['uid'] = $openid;
			// setcookie("uid", $openid, 0, '/');

			if ( !empty($_SESSION['loginreferer']) ) {
				$lf = $_SESSION['loginreferer'];
				unset($_SESSION['loginreferer']);
				header('Location:' . $lf);
			} else {
				header('Location:/');
		}
    // 
    //     $esc_identity = escape($openid);
    // 
    //     $success = sprintf('You have successfully verified ' .
    //                        '<a href="%s">%s</a> as your identity.',
    //                        $esc_identity, $esc_identity);
    // 
    //     if ($response->endpoint->canonicalID) {
    //       $_SESSION['canonicalID'] = $response->endpoint->canonicalID;
    //         $escaped_canonicalID = escape($response->endpoint->canonicalID);
    //         $success .= '  (XRI CanonicalID: '.$escaped_canonicalID.') ';
    //     }
    // 
    //     $sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
    //     $sreg = $sreg_resp->contents();
    // 
    //     if (@$sreg['email']) {
    //       $_SESSION['openidemail'] = $sreg['email'];
    //         $success .= "  You also returned '".escape($sreg['email']).
    //             "' as your email.";
    //     }
    }
    // 
    // include 'openid_login.php';
}

function escape($thing) {
    return htmlentities($thing);
}

?>
