<?php

    require_once('config.php');
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $uname = $link->real_escape_string($_POST['uname']);
        $pass1 = $link->real_escape_string($_POST['pass1']);
        $pass2 = $link->real_escape_string($_POST['pass2']);

        $tabRenew = ["error_msg"=>"Your password has been changed!", "title"=>"Ready!", "icon"=>"success", "btn_text"=>"Uff!"];

        if ($pass1 === $pass2){

            $hashedPassword = password_hash($pass1, PASSWORD_ARGON2I);
            if(password_verify($regPass, $hashedPassword)){
                $update = $link->query("UPDATE users SET password='$pass1' WHERE username='$uname'");
            } else {
                $tabRenew = ["error_msg"=>"Problem occured, contact admin to change password manually", "title"=>"Wait a minute...", "icon"=>"error", "btn_text"=>"Not good.."];
            }

        } else {
            $tabRenew = ["error_msg"=>"Passwords do not match.", "title"=>"Wait a minute...", "icon"=>"error", "btn_text"=>"Not good.."];
        }

        ob_end_clean();
        echo json_encode($tabRenew);
    } else {
        echo "No data";
    }

?>