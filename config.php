<?php
  $dbserver = "";
  $dbuser ="";
  $dbpass ="";
  $dbname ="";

  $link = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$serwer_smtp = "";
$port_smtp = 465;

$smtplog = '';
$smtppass = '';

?>
