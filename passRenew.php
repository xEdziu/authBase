<?php
	session_start();
    require_once('config.php');

$_SESSION['xsrf'] = md5(rand(10-10000));
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
	    <title>Password change</title>
	    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
    <body>
        <div>
            <?php 
                if(isset($_GET['hash']) && !empty($_GET['hash'])){
 
                    $hash = $link->real_escape_string($_GET['hash']); 

                    $search = $link->query("SELECT hash FROM users WHERE hash='$hash'");
                    
                    if($search->num_rows > 0) {

                        echo '<form action="passRenewWork.php" method="post">
                        <input type="text" name="uname" placeholder="Type username" required>
                        <input type="password" name="pass1" placeholder="Type new password" required>
                        <input type="password" name="pass2" placeholder="Repeat new password" required>
			<input type="hidden" name="xsrf" value="'.$_SESSION['xsrf'].'">
                        <button type="submit" id="submit">Zmień hasło</button>
                        </form>';
                    }
                    else {
                        echo '<div>Adres URL jest niepoprawny</div>';
                    }
                }
            ?>
        </div>  

    </body>
    <script type="text/javascript">
        // type ajax here to passRenewWork.php file with POST method
    </script>
</html>
