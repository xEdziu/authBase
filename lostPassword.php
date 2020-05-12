<?php 
    require_once('config.php');
    require(__DIR__.'\PHPMailer-5.2-stable\PHPMailerAutoload.php');
    ob_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' $$ isset($_POST['xsrf'])) {
        
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $tabLost["error_msg"] = "Email format is invalid. Is it correct?";
            $tabLost["title"] = "Wait a minute...";
            $tabLost["icon"] = "error";
            $tabLost["btn_text"] = "Oh..";
            
            echo json_encode($tab_Lost);
            die();
          }
        
        if($_POST['xsrf'] !== $_SESSION['xsrf']){
            echo "xsrf error";
            unset($_SESSION['xsrf']);
            die();
        }
        unset($_SESSION['xsrf']);
        $email = $link->real_escape_string($_POST['email']);

        $tabLost = ["error_msg"=>"Message with link has been sent to your email!", "title"=>"Done!", "icon"=>"success", "btn_text"=>"Uff!"];

        $check = $link->query("SELECT email FROM users WHERE email='$email'");
        $checkNum = $check->num_rows;

        if ($checkNum != 1){

            $tabLost["error_msg"] = "There is no registred email like that. Is it correct?";
            $tabLost["title"] = "Wait a minute...";
            $tabLost["icon"] = "error";
            $tabLost["btn_text"] = "Oh..";

        } else {

            $hash = md5(rand(0,1000));

            $check = $link->query("UPDATE users SET hash='$hash' WHERE email='$email'");

            $email_message = 
                'Hi! We heard that you lost your password. To change it, click this link".
                http://yourpage.com/passRenew?hash='.$hash.'<br>
                You did not ask for password change? Contact admin right now.' ;
                $email_message = wordwrap($email_message, 70, "\r\n");

                $mail = new PHPMailer();
                $mail->CharSet = "UTF-8";
                $mail->IsSMTP();
                $mail->Host = $serwer_smtp; 
                $mail->Port = $port_smtp; 
                $mail->SMTPAuth = true; 
                $mail->Username = $smtplog;
                $mail->Password = $smtppass;
                $mail->From = 'from who?'; 
                $mail->SMTPSecure = 'ssl';
                $mail->FromName = 'your name'; 
                $mail->AddAddress($email);
                $mail->WordWrap = 50;
                $mail->Priority = 1;
                $mail->Subject = 'Password renew';
                $mail->Body='<!DOCTYPE html>
            <html>
                
            <head>
                <meta charset="utf-8">
                <title>LOST PASSWORD</title>
                <style type="text/css">
                    body {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        height: 100vh;
                        overflow: hidden;
                    }
                        
                    main {
                        height: 100vh;
                        width: 100%;
                    }
                        
                    header {
                        background-color: rgb(84, 150, 255);
                        height: 10vh;
                        text-align: center;
                        font-size: 7vh;
                        font-family: "Arial";
                        line-height: 10vh;
                        color: white;
                        margin-top: 10vh;
                        margin-right: 20%;
                        margin-left: 20%;
                        margin-bottom: 10vh;
                    }
                        
                    .textt {
                        font-size: 20px;
                        font-size: 3vh;
                        text-align: center;
                        font-family: "verdana";
                        margin-right: 20%;
                        margin-left: 20%;
                        margin-bottom: 10vh;
                    }
                        
                    .c {
                        font-size: 3vh;
                        margin-right: 1vh;
                        color: black;
                        position: absolute;
                        right: 50vh;
                        font-family: "verdana";
                    }
            </head>
                
            <body>
               <main>
                       <header>
                           PASSWORD RENEW
                       </header>
                       <div class="textt">
                           '.$email_message.'
                    </div>
                    <div class="c">
                        Your company name
                    </div>
                </main>
                
            </body>
                
            </html>';
            $mail->isHTML(true);
            $mail->AltBody = $email_message;
                

            if(!$mail->send()) {
                echo 'Message was not sent.';
                echo 'Mailer error: ' . $mail->ErrorInfo;
                   
                $tab_Lost["error_msg"]= "Mail hasn't been send, contact administrator to change password manually.";
                $tab_Lost["title"]="Błąd systemu. ".$mail->ErrorInfo;
                $tab_Lost["icon"]="error";
                $tab_Lost["btn_text"]="OK"; 
            }
        }
        
        ob_end_clean();
        echo json_encode($tab_Lost);
    } else {
        echo "No data.";
    }


?>
