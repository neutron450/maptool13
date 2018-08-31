<?php

include_once('sitevars.inc.php');

class DbTools {

	private $dbhost = DB_HOST;
	private $dbuser = DB_USER;
	private $dbpass = DB_PASS;
	private $dbname = DB_NAME;

	private $dbh;
	private $stmt;
	private $error;

	public function __construct() {

		$dsn = 'mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname;
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		try {
			$this->dbh = new PDO($dsn, $this->dbuser, $this->dbpass, $options);
		} catch(PDOException $e){
			$this->error = $e->getMessage();
		}

	}

	public function createToken() {

		try {
			//$token = bin2hex(random_bytes(15));
			@$token = bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
			$sql = "INSERT INTO tokens (token) VALUES ('$token')";
			$this->dbh->exec($sql);
			//echo 'setting token : '.$_SESSION['token'] = $token;
			$_SESSION['token'] = $token;
		} catch(PDOException $e) {
			echo $sql . "<br>" . $e->getMessage();
		}

	}

	public function checkToken($token) {

		try {

			$sql = "SELECT * FROM tokens WHERE token = '$token'";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute();
			$rows = $stmt->fetch();

			// 	echo '<pre>';
			// 	print_r($rows);
			// 	echo '</pre>';

			if ($rows['token']) {
				return true;
			}
			return false;

		} catch(PDOException $e) {
			echo $sql . "<br>" . $e->getMessage();
		}

	}

}