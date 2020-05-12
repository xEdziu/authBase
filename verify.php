<?php
    require_once('config.php');
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
	    <title>Weryfikacja</title>
	    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
    </head>
    <body>
        <div>
            <?php 
                if(isset($_GET['hash']) && !empty($_GET['hash'])){
 
                    $hash = $link->real_escape_string($_GET['hash']); 

                    //$search = $link->query("SELECT hash, active FROM users WHERE hash='$hash' AND active='0'");
                    
                    //if($search->num_rows > 0) {
			if ($link->query("UPDATE users SET active='1' WHERE hash='$hash' AND active='0'"));

                        //$link->query("UPDATE users SET active='1' WHERE hash='$hash' AND active='0'");
                        echo '<div>Your account has been verified.<br>You can close this tab.<br></div>';
                    }
                    else {
                        echo '<div>URL address is incorrect or you have already verified your account.</div>';
                    }
                }
            ?>
        </div>  

    </body>
</html>
