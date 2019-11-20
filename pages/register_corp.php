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
				if($_SESSION['przewoznik']) {
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/my_account.php'\" value='Panel firmy'/>";
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/planes.php'\" value='Samoloty'/>";
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/pilots.php'\" value='Piloci'/>";
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/corp_flights.php'\" value='Nasze loty'/>";
				}
				else {
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/my_account.php'\" value='Informacje'/>";				
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/edit_data.php'\" value='Zmien dane'/>";
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/add_cart.php'\" value='Dodaj karte'/>";
				}
			?>

	</div>
	<div id="main">
		<div id="logo">
			<center><a>PANEL ADMINISTRATORA</a></center>
		</div>
		
		<div id="panel">
			<form method="POST" action="/linie_lot/pages/register_corp.php">
				<table class="table_edit">
					<tr colspan="2">
						<th colspan="2">
							<?php
								if(isset($_SESSION['nie_wypelniono'])) {
									echo "<a class='a4'>Nie wypełniono wszystkich pól!</a>";
									$_SESSION['nie_wypelniono'] = null;
								}
							?>
						</th>
					</tr>
					<tr>
						<th><a class='a3'>Nazwa firmy: </th><th><?php echo $_SESSION['login']; ?></th>
					</tr>
					<tr>
						<th><a class='a3'>Kraj:  </th><th><input type="text" name="kraj" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Mail: </th><th><input type="text" name="mail" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Telefon kontaktowy: </th><th><input type="tel" class="textarea" name="nr_tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}" value="000-000-000"></th>
					</tr>
					<tr>
						<th><a class='a3'>Logo:  </th><th><input type="text" name="img" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Budżet: </th><th><input type="number" name="budzet" class="textarea"></th>
					</tr>
					<tr>
						<th colspan="2"><input type="submit" name="register" class="button3" value="Zarejestruj"></th>
					</tr>
				</table>	
			</form>
			
			<?php
				if(isset($_POST['register'])) {
					$nazwa_firmy = $_SESSION['login']; //firma nazywa sie tak jak konto przewoznika
					$kraj = $_POST['kraj'];
					$data_zalozenia = date("Y-m-d");
					$budzet = $_POST['budzet'];
					$img = $_POST['img'];
					
					
					$nr_tel = $_POST['nr_tel'];
					$mail = $_POST['mail'];

					$sql = "INSERT INTO przewoznicy (nazwa_firmy, kraj, data_zalozenia, budzet, logo)
						VALUES ('$nazwa_firmy', '$kraj', '$data_zalozenia', $budzet, '$img')";	
					
					if($kraj&&$mail&&$nr_tel&&$img&&$budzet) {
						$result = $dbh->exec($sql);
						echo $result;
						
						if($result == 1) {
							$_SESSION['zarejestrowano_corp'] = true;
							$_SESSION['czy_zarejestrowana'] = true;
							$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
							if (!$conn) {
								die("Connection failed: " . mysqli_connect_error());
							}
							$sql = "UPDATE uzytkownicy SET przewoznicy_nazwa_firmy = '$nazwa_firmy' WHERE login = '$nazwa_firmy'";
							mysqli_query($conn, $sql);
							$sql = "UPDATE uzytkownicy SET mail = '$mail' WHERE login = '$nazwa_firmy'";
							mysqli_query($conn, $sql);
							$sql = "UPDATE uzytkownicy SET nr_tel = '$nr_tel' WHERE login = '$nazwa_firmy'";
							mysqli_query($conn, $sql);
							mysqli_close($conn);
							header('refresh: 0; url="/linie_lot/pages/my_account.php"');
						}
					}
					else {
						$_SESSION['nie_wypelniono'] = true;
						header('refresh: 0');
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

