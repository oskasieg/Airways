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
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/airways.php'\" value='Loty'/>";
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/users.php'\" value='Użytkownicy'/>";
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/carriers.php'\" value='Przewoźnicy'/>";
			?>

	</div>
	<div id="main">
		<div id="logo">
			<center><a>PANEL ADMINISTRATORA</a></center>
			<?php
				$login = $_SESSION['login'];
				echo "<center><a style='color: red; margin-top: -1%; margin-left: -5%; position: absolute;'>$login</a></center>";
			?>
		</div>
		
		<div id="panel">
			<form method="POST" action="/linie_lot/pages/carrier_add.php">
				<table class="table_edit">
					<tr colspan="2">
						<th colspan="2">
							<?php
								if(isset($_SESSION['login_zajety'])){
									echo "<a class='a4'>Podany login jest zajęty!</a>";
									$_SESSION['login_zajety'] = null;
								}
								else if(isset($_SESSION['hasla_rozne'])){
									echo "<a class='a4'>Hasła muszą być takie same!</a>";
									$_SESSION['hasla_rozne'] = null;
								}
								else if(isset($_SESSION['dodano_user'])) {
									echo "<a class='a4'>Dodano konto przewoziciela!<br>Teraz musi je on skonfigurować w swoim panelu!</a>";
									$_SESSION['dodano_user'] = null;
								}
								else if(isset($_SESSION['nie_wypelniono'])) {
									echo "<a class='a4'>Nie wypełniono wszystkich pól!</a>";
									$_SESSION['nie_wypelniono'] = null;
								}
							?>
						</th>
					</tr>
					<tr>
						<th><a class='a3'>Login: </th><th><input type="text" name="login" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Hasło: </th><th><input type="password" name="haslo1" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Powtórz hasło: </th><th><input type="password" name="haslo2" class="textarea"></th>
					</tr>
					<tr>
						<th colspan="2"><input type="submit" name="add" class="button3" value="Dodaj"></th>
					</tr>
				</table>	
			</form>
			
			<?php
				if(isset($_POST['add'])) {
					if($_POST['login'] != null && $_POST['login'] != null && $_POST['login'] != null) {
						$login = $_POST['login'];
						$haslo1 = $_POST['haslo1'];
						$haslo2 = $_POST['haslo2'];
						$date = date("Y-m-d");
						$czy_login = "SELECT login FROM uzytkownicy WHERE login='$login'";
						$sql = "INSERT INTO uzytkownicy (login, haslo, typ, data_rejestracji) VALUES ('$login', '".md5($haslo1)."', 'przewoznik', '$date')";
					
						$spr = $dbh->prepare($czy_login);
						$spr->execute();
						$ilosc_login = $spr->rowCount(); 
					}
					
					if(isset($login) && isset($haslo1) && isset($haslo2)) {
						if($ilosc_login == 1 && $login != null) {
							$_SESSION['login_zajety'] = true;
						}
						else if($haslo1 != $haslo2) {
							$_SESSION['hasla_rozne'] = true;
						}
						else{
							try {
								$result = $dbh->exec($sql);
							}
							catch(PDOEXception $e){
								echo "Wystąpił problem (PDOException)";
							}
							$_SESSION['dodano_user'] = true;
						}
						header('refresh: 0');
					}
					else {
						$_SESSION['nie_wypelniono'] = true;
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

