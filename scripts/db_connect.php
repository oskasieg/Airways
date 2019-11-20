<?php
session_start();
	try {
		$dbh = new PDO('mysql:host=127.0.0.1;dbname=linie_lotnicze', 'root', '');
		} 
		catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
?>
