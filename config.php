<?php
  $dbserver = "localhost";
  $dbuser ="id13505669_zaprogramowani_user";
  $dbpass ="U(6k<)d*SR}^5Ke-";
  $dbname ="id13505669_zaprogramowani";

  $link = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$serwer_smtp = "smtp.wp.pl";
$port_smtp = 465;

$smtplog = 'zaprogramowani.g4a@wp.pl';
$smtppass = 'zaprogramowani123';

?>