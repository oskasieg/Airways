<?php
//w razie bledow flush
	ob_start();
	include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');	
?>

<html>
<head>
	<meta charset="utf-8">
	<title>Panel admin</title></title>
	<link rel="stylesheet" text="text/css" href="/linie_lot/styles/style2.css">
</head>

<body>
	<?php
		echo "<input class='button2' type='button' onclick= \"location.href='/linie_lot/index.php'\" value='<< Powrót'/>";
	?>
	<div id="menu">
			<img src="/linie_lot/img/plane.png"/>
			<?php
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/my_account.php'\" value='Informacje'/>";
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/my_airways.php'\" value='Moje loty'/>";				
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/edit_data.php'\" value='Zmien dane'/>";
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/add_cart.php'\" value='Karta'/>";
			?>

	</div>
	<div id="main">
		<div id="logo">
			<center><a>PANEL UŻYTKOWNIKA</a></center>
			<br>
			<?php
				$login = $_SESSION['login'];
				echo "<center><a style='color: red; margin-top: -2%; margin-left: -3%; position: absolute;'>$login</a></center>";
			?>
		</div>
		
		<div id="panel">
			
			<form action='/linie_lot/pages/add_opinion.php' method='post'>
				<center>
					<a style='position: absolute; margin-left: -10%; margin-top: 1%;'>
					<input type='radio' name='ocena' value='1'>1
					<input type='radio' name='ocena' value='2'>2
					<input type='radio' name='ocena' value='3'>3
					<input type='radio' name='ocena' value='4'>4
					<input type='radio' name='ocena' value='5'>5</a><br>
				</center>
				<textarea  name='opinia' class='opinia'></textarea>
				<input type='submit' name='dodaj' class='button3' value='Dodaj opinię' style='position: absolute; margin-top: 25%; margin-left: -50%; width: 15%;'>
				<input type='reset' value='Wyczyść' class='button3' style='position: absolute; margin-top: 25%; margin-left: -15%; width: 15%;'>
			</form>
			
			<?php
				if(isset($_POST['dodaj'])) {
		
					$login = $_SESSION['login'];
					$nazwa_lotu = substr($_SESSION['flight'], 0, -1);
					
					$opinia = $_POST['opinia'];
					
					if($opinia && isset($_POST['ocena'])) {
						$ocena = $_POST['ocena'];
						$sql = "INSERT INTO opinie (tresc, ocena, loty_nazwa_lotu, uzytkownicy_login)
							VALUES ('$opinia', '$ocena', '$nazwa_lotu', '$login')";
						$result = $dbh->exec($sql);
						if($result)
							echo "<script>
							window.alert('Dodano opinie do lotu!');
							</script>";
							header('refresh: 0; url="/linie_lot/pages/my_airways.php"');
					}
					else {
						echo "<script>
						window.alert('Nie wypełniono wszystkich pól!');
						</script>";
					}
				}
					
				
			?>
			
		</div>
	</div>
</body>

<?php
	$dbh = null;
?>

</html>

