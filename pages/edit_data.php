<?php
include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');
?>

<html>
<head>
<meta charset="utf-8">
<title>Moje konto</title>
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
				if($_SESSION['login'] != 'admin')
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/my_airways.php'\" value='Moje loty'/>";				
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/edit_data.php'\" value='Zmien dane'/>";
				if($_SESSION['login'] != 'admin')
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
			<form method="POST" action="/linie_lot/pages/edit_data.php">
					<center>
						<table class='table_edit' style='margin-left: 0%; margin-top: 3%;'>
							<tr>
								<th><a class="a3">Imię:</a></th><th><input class="textarea" type="text" name="imie"></th>
							</tr>				
							<tr>
								<th><a class="a3">Nazwisko:</a></th><th><input class="textarea" type="text" name="nazwisko"></th>
							</tr>				<tr>
								<th><a class="a3">Email:</a></th><th><input class="textarea" type="text" name="mail" value="___@___"></th>
							</tr>				<tr>
								<th><a class="a3">Adres:</a></th><th><input class="textarea" type="text" name="adres"></th>
							</tr>				<tr>
								<th><a class="a3">Nr tel.:</a></th><th><input class="textarea" type="text" name="nr_tel" value="000-000-000"></th>
							</tr>
							<tr rowspan='2'>
							<th colspan='2'>
							<?php
								echo "<input class='button3' type='button' value='Zmień hasło' onclick= \"location.href='/linie_lot/pages/edit_psswd.php'\" name='password'>";
							?>
							<input class="button3" type="submit" value="Zatwierdź zmiany" name="zatwierdz_zmiany">
							<input class="button3" type="reset" value="Zresetuj" name="reset">
							</th>
							</tr>
						</table>
					</center>
			</form>
			
			<?php
				if(isset($_POST['zatwierdz_zmiany'])){
					$login = $_SESSION['login'];
					
					$mail = ($_POST['mail']);
					$imie = ($_POST['imie']);
					$nazwisko = ($_POST['nazwisko']);
					$nr_tel = ($_POST['nr_tel']);
					$adres = ($_POST['adres']);
					$sql = " ";
					
					$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
					if (!$conn) {
						die("Connection failed: " . mysqli_connect_error());
					}
					if($mail != null && $mail != "___@___"){
						 $sql = "UPDATE uzytkownicy SET mail= '$mail' WHERE login = '$login'";	
						 mysqli_query($conn, $sql);	
					}
					if($imie != null){
						 $sql = "UPDATE uzytkownicy SET imie = '$imie' WHERE login = '$login'";	
						 mysqli_query($conn, $sql);	
					}
					if($nazwisko != null){
						 $sql = "UPDATE uzytkownicy SET nazwisko = '$nazwisko' WHERE login = '$login'";	
						 mysqli_query($conn, $sql);	
					}
					if($nr_tel != null && $nr_tel != "000-000-000"){
						 $sql = "UPDATE uzytkownicy SET nr_tel = '$nr_tel' WHERE login = '$login'";	
						 mysqli_query($conn, $sql);	
					}
					if($adres != null){
						 $sql = "UPDATE uzytkownicy SET adres = '$adres' WHERE login = '$login'";
						 mysqli_query($conn, $sql);	
					}
					
					echo "<center><a class='a4'>Zmiany zatwierdzone!</a></center>";
					 
					mysqli_close($conn);
				}
			?>
		</div>
	</div>
</body>
</html>
<?php
	$dbh = null;
?>
