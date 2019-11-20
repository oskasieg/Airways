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
			<form method="POST" action="/linie_lot/pages/edit_psswd.php">
					<center>
						<table class='table_edit' style='margin-left: 0%; margin-top: 3%;'>
							<tr>
								<th colspan='2'>
								<?php
									if(isset($_SESSION['stare!=stare'])) {
										echo "
										<a class='a5'>Podano złe stare hasło!</a>";
										$_SESSION['stare!=stare'] = null;
									}
									else if(isset($_SESSION['nie_wypelniono'])) {
										echo "
										<a class='a5'>Nie wypełniono wszystkich pól!</a>";
										$_SESSION['nie_wypelniono'] = null;
									}
									else if(isset($_SESSION['nowe_hasla'])) {
										echo "
										<a class='a5'>Nowe hasła się nie zgadzaja!</a>";
										$_SESSION['nowe_hasla'] = null;
									}
									else if(isset($_SESSION['krotkie_haslo'])) {
										echo "
										<a class='a5'>Hasło za krótkie! (min. 8 znaków)!</a>";
										$_SESSION['krotkie_haslo'] = null;
									}
									else if(isset($_SESSION['nazwisko'])) {
										echo "
										<a class='a5'>Podano złe nazwisko!</a>";
										$_SESSION['nazwisko'] = null;
									}		
									else if(isset($_SESSION['haslo'])) {
										echo "
										<a class='a4'>Hasło zmienione!</a>";
										$_SESSION['haslo'] = null;
									}							
									?>
								</th>
							</tr>
							<tr>
								<th><a class="a3">Stare hasło: </a></th><th><input class="textarea" type="password" name="stare_haslo"></th>
							</tr>				
							<tr>
								<th><a class="a3">Nowe hasło:</a></th><th><input class="textarea" type="password" name="nowe_haslo"></th>
							</tr>
							<tr>
								<th><a class="a3">Powtórz hasło:</a></th><th><input class="textarea" type="password" name="nowe_haslo2"></th>
							</tr>
							<tr>
								<th><a class="a3">Nazwisko panieńskie matki:</a></th><th><input class="textarea" type="text" name="nazwisko"></th>
							</tr>
							<tr rowspan='2'>
							<th colspan='2'>
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
					$stare_haslo = $_POST['stare_haslo'];
					$stare_haslo_baza = '';
					$nowe_haslo = $_POST['nowe_haslo'];
					$nowe_haslo2 = $_POST['nowe_haslo2'];
					$nazwisko = $_POST['nazwisko'];
					$nazwisko_baza = '';
					$sql_haslo = "SELECT haslo FROM uzytkownicy WHERE login = '$login'";
					foreach($dbh->query($sql_haslo) as $row) {
						$stare_haslo_baza = $row['haslo'];
					} 
	
					$sql_nazwisko = "SELECT pytanie_pom FROM uzytkownicy WHERE login = '$login'";
					foreach($dbh->query($sql_nazwisko) as $row) {
						$nazwisko_baza = $row['pytanie_pom'];
					} 
					
					$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
					if (!$conn) {
						die("Connection failed: " . mysqli_connect_error());
					}
					
					if($stare_haslo!=null&&$nowe_haslo!=null&&$nowe_haslo2!=null&&$nazwisko!=null) {
						if(md5($stare_haslo) != $stare_haslo_baza){
							$_SESSION['stare!=stare'] = true;
							header('refresh: 0');
						}
						else if($nowe_haslo != $nowe_haslo2) {
							$_SESSION['nowe_hasla'] = true;
							header('refresh: 0');
						}
						else if(strlen($nowe_haslo) < 8) {
							$_SESSION['krotkie_haslo'] = true;
							header('refresh: 0');
						}
						else if($nazwisko_baza != $nazwisko){
							$_SESSION['nazwisko'] = true;
							header('refresh: 0');
						}
						else{
							$sql = "UPDATE uzytkownicy SET haslo = '".md5($nowe_haslo)."' WHERE login = '$login'";
							$result = mysqli_query($conn, $sql);
							if($result) {
								$_SESSION['haslo'] = true;
								header('refresh: 0');
							}
						}
						
					}
					else {
						$_SESSION['nie_wypelniono'] = true;
							header('refresh: 0');
					}
					
					 
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
