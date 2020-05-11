<?php
    session_start();
    include ('config.php');
    ob_start();

    $flag = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xsrf'])){
        
        if($_POST['xsrf'] !== $_SESSION['xsrf']){
            echo "xsrf error";
            die();
        }
        
        $logUser = $link->real_escape_string(htmlentities($_POST['logUser']));
        
        $logPass = $link->real_escape_string($_POST['logPass']);
        
        $tabLog = ["error_msg"=>"", "title"=>"success!", "icon"=>"success", "btn_text"=>"Yay!"];
        
        $check = $link->query("SELECT * FROM users WHERE username='$logUser'");
        $checkNum = $check->num_rows;

        if($checkNum != 1){

            $tabLog["error_msg"]= "Incorrect data.";
            $tabLog["title"]="Wait a minute...";
            $tabLog["icon"]="error";
            $tabLog["btn_text"]="OK";

        } else {

            $regPassHashed = $link->query("SELECT password, active FROM users WHERE username='$logUser'");
        
            if($regPassHashed->num_rows>0)
            {
                $pswdHash = $regPassHashed->fetch_assoc();
                if(password_verify($logPass, $pswdHash['password'])){

                    if ($pswdHash['active'] === '1'){

                        $tabLog["error_msg"]="Welcome ".$logUser."!";

                        $_SESSION['logged'] = 1;
                        $flag = true;

                        $check->close();
                        $link->close();

                        header("Location: http://yourdirection.com");

                    } else {

                        $tabLog["error_msg"]= "Account not verified, check your email.";
                        $tabLog["title"]="Wait a minute...";
                        $tabLog["icon"]="error";
                        $tabLog["btn_text"]="OK"; 

                    }

                } else {

                    $tabLog["error_msg"]= "Incorrect data.";
                    $tabLog["title"]="Wait a minute...";
                    $tabLog["icon"]="error";
                    $tabLog["btn_text"]="OK";

                }
            }
        }
        
        ob_end_clean();
        echo json_encode($tabLog);

    } else {
        echo "No data";
    }

    
?>
