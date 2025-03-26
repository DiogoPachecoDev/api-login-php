<?php

	switch ($_SERVER['SERVER_NAME']) {
		case "localhost":
			define('DB_HOST', 'localhost');
			define('DB_NAME', 'DATABASE_NAME');
			define('DB_USER', 'USER_NAME');
			define('DB_PASS', 'PASSWORD');
			break;

		case 'doc360.com.br':
			define('DB_HOST', 'HOST_NAME');
			define('DB_NAME', 'DATABASE_NAME');
			define('DB_USER', 'USER_NAME');
			define('DB_PASS', 'PASSWORD');
			break;

		default:
			define('DB_HOST', 'HOST_NAME');
			define('DB_NAME', 'DATABASE_NAME');
			define('DB_USER', 'USER_NAME');
			define('DB_PASS', 'PASSWORD');
			break;
	}

	function getDatabaseConnection() {
		try {
			$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
			$pdo = new PDO($dsn, DB_USER, DB_PASS);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $pdo;
		} catch (PDOException $e) {
			die('database connection failed: ' . $e->getMessage());
		}
	}

?>