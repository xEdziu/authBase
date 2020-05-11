<?php
require_once('config.php');
require(__DIR__.'\PHPMailer-5.2-stable\PHPMailerAutoload.php');
?>

<?php
    ob_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $regUser = $link->real_escape_string(htmlentities($_POST['regUser']));
        $regEmail = $link->real_escape_string($_POST['regEmail']);
        $regPass = $link->real_escape_string($_POST['regPass']);

        $tab_reg=["error_msg"=>"Hi ".$regUser."! Nice to meet you! Your account has been registred, now verify it by clicking link on your email!", "title"=>"success!", "icon"=>"success", "btn_text"=>"Cool!"];
    
        $result_u = $link->query("SELECT * FROM users WHERE username='$regUser'"); 
        $result_e = $link->query("SELECT * FROM users WHERE email='$regEmail'");
        $row_cnt_u = $result_u->num_rows;
        $row_cnt_e = $result_e->num_rows;
    
        if ($row_cnt_u > 0){ 
            $tab_reg["error_msg"]= "Username taken!";
            $tab_reg["title"]="Wait a minute...";
            $tab_reg["icon"]="error";
            $tab_reg["btn_text"]="Meh, OK";
        }  elseif  ($row_cnt_e > 0){
            $tab_reg["error_msg"]= "There's account linked to this email address!";
            $tab_reg["title"]="Wait a minute...";
            $tab_reg["icon"]="error";
            $tab_reg["btn_text"]="Meh, OK";
        } else {

            $hashedPassword = password_hash($regPass, PASSWORD_ARGON2I);
            $hashActivate = md5(rand(0,1000));

            if(password_verify($regPass, $hashedPassword)){

                $queryReg = $link->prepare("INSERT INTO users (username, email, password, hash) VALUES (?,?,?,?)");
                $queryReg->bind_param('ssss', $regUser, $regEmail, $hashedPassword, $hashActivate);

                $queryReg->execute();

                $queryReg->close();
                $link->close();

                // EMAIL
                $email_message = 
                'Hi '.$regUser.'! Thanks for registration on our page!
                Your account has been created, now you have to verify it. You can od that via clicking that link:
                http://yourpage.com/verify.php?hash='.$hashActivate.'' ;
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
                $mail->FromName = 'from who?'; 
                $mail->AddAddress($regEmail);
                $mail->WordWrap = 50;
                $mail->Priority = 1;
                $mail->Subject = 'Verification';
                $mail->Body='<!DOCTYPE html>
                <html>
                
                <head>
                    <meta charset="utf-8">
                    <title>VERIFICATION</title>
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
                            SIGN UP
                        </header>
                        <div class="textt">
                            '.$email_message.'
                        </div>
                        <div class="c">
                            Your company name.
                        </div>
                    </main>
                
                </body>
                
                </html>';
                $mail->isHTML(true);
                $mail->AltBody = $email_message;
                

                if(!$mail->send()) {
                    echo 'Message was not sent.';
                    echo 'Mailer error: ' . $mail->ErrorInfo;
                    
                    $tab_reg["error_msg"]= "Mail hasn't been send, contact administration, to activate your account manually.";
                    $tab_reg["title"]="System failure. ".$mail->ErrorInfo;
                    $tab_reg["icon"]="error";
                    $tab_reg["btn_text"]="OK"; 

                }
                
            } else{
                $tab_reg["error_msg"]= "Contact admin, problem with encryption.";
                $tab_reg["title"]="Critical error";
                $tab_reg["icon"]="error";
                $tab_reg["btn_text"]="OK"; 
            }
        }
        ob_end_clean();
        echo json_encode($tab_reg);

} else {
    echo "No data";
}
