# authBase
An example of authentication system for your website.

It is written in objective PHP which uses MySQLi instead of PDO.

authBase is using a PHPMailer-5.2-stable version, documentation here: [PHPMailer](https://github.com/PHPMailer/PHPMailer/tree/5.2-stable).

Some files contains a ready PHPMailer function which is prepared to send

Files in authBase are predicted to use AJAX or jQuery.

It can be easily used with [sweetAlert2](https://sweetalert2.github.io/) library for fancy alerts.

Feel free to use it :)

## Files

The authBase contains files:

* ```config.php```
* ```login.php```
* ```registration.php```
* ```verify.php```
* ```lostPassword.php```
* ```passRenew.php```
* ```passRenewWork.php```

## Relations between files

Most of the files are strictly connected to each other while others work independetly.

* ```config.php``` is used in every file.

* ```login.php``` is not related with any other file than ```config.php```.

* ```registration.php``` sends an email to user with link to ```verify.php```.

* ```lostPassword.php``` sends an email to user with link to ```passRenew.php``` and this file is strictly connected with ```passRenewWork.php```- AJAX recommended here.

## Setting up ```config.php```

This file contains:

```php
<?php
  $dbserver = "";
  $dbuser = "";
  $dbpass = "";
  $dbname = "";

  $link = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
  }

$serwer_smtp = "";
$port_smtp = 465;

$smtplog = '';
$smtppass = '';

?>
```
* To connect to your database you must fill 4 first lines with suitable data.

* ```$serwer_smtp``` - (SMTP Server/Host) and ```$port_smtp``` are used in PHPMailer. Fulfill with suitable data.
* ```$smtplog``` -(email) and ```$smtppass``` are used to connect to sender email.

## Database

My example table users look like this:

```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `active` int(1) NOT NULL DEFAULT 0,
  `usertype` varchar(255) NOT NULL DEFAULT 'normal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);
  
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
```


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## Contact

You can contact me via email: **adrian.goral@gmail.com**

## License
[MIT](https://choosealicense.com/licenses/mit/)


