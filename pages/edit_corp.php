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
			<center><a>PANEL PRZEWOŹNIKA</a></center>
			<?php
				$login = $_SESSION['login'];
					echo "<center><a style='color: red; margin-top: -1%; margin-left: -5%; position: absolute;'>$login</a></center>";
			?>
		</div>
		
		<div id="panel">
			<form method="POST" action="/linie_lot/pages/edit_corp.php">
				<table class="table_edit">
					<tr colspan="2">
						<th colspan="2">
							<?php
								if(isset($_SESSION['edytowano_corp'])) {
									echo "<a class='a4'>Zatwierdzono zmiany!</a>";
									$_SESSION['edytowano_corp'] = null;
								}
								else if(isset($_SESSION['nie_wypelniono'])) {
									echo "<a class='a5'>Nie wypełniono żadnego pola!</a>";
									$_SESSION['nie_wypelniono'] = null;
								}
							?>
						</th>
					</tr>
					<tr>
						<th><a class='a3'>Nazwa firmy: </th><th><?php $nazwa = $_SESSION['login'];echo "<a'>$nazwa</a>"; ?></th>
					</tr>
					<tr>
						<th><a class='a3'>Data założenia:  </th>
						<th><?php
							$nazwa_firmy = $_SESSION['login'];
							$sql = "SELECT data_zalozenia FROM przewoznicy WHERE nazwa_firmy = '$nazwa_firmy'";
							foreach($dbh->query($sql) as $row) {
								$data_zalozenia = $row['data_zalozenia'];
								echo "<a class='a2'>$data_zalozenia</a>";
							}
							?></th>
					</tr>
					<tr>
						<th><a class='a3'>Kraj:  </th><th><input type="text" name="kraj" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Logo (url): </th><th><input value="/linie_lot/img" type="text" name="img" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Telefon kontaktowy:  </th><th><input value="000-000-000" type="text" name="nr_tel" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Adres e-mail:  </th><th><input type="text" name="mail" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Budżet: </th><th><input type="number" name="budzet" class="textarea"></th>
					</tr>
					<tr>
						<th colspan="2"><input type="submit" name="edit" class="button3" value="Zatwierdź zmiany"></th>
					</tr>
				</table>	
			</form>
			
			<?php
				if(isset($_POST['edit'])) {
					$nazwa_firmy = $_SESSION['login']; //firma nazywa sie tak jak konto przewoznika
					$kraj = $_POST['kraj'];
					$budzet = $_POST['budzet'];
					$img = $_POST['img'];
					$mail = $_POST['mail'];
					$nr_tel = $_POST['nr_tel'];
					
					$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
					if (!$conn) {
						die("Connection failed: " . mysqli_connect_error());
					}
					
					if($kraj != '') {
						$sql = "UPDATE przewoznicy SET kraj = '$kraj' WHERE nazwa_firmy = '$nazwa_firmy'";
						$query = mysqli_query($conn, $sql);
						if(!$query) {
							echo "Błąd kraj";
						}
						else {
							$_SESSION['edytowano_corp'] = true;
						}
					}
					if($budzet != '') {
						$sql = "UPDATE przewoznicy SET budzet = $budzet WHERE nazwa_firmy = '$nazwa_firmy'";
						$query = mysqli_query($conn, $sql);
						if(!$query) {
							echo "Błąd budzet";
						}
						else {
							$_SESSION['edytowano_corp'] = true;
						}
					}
					if($img != '') {
						$sql = "UPDATE przewoznicy SET logo = '$img' WHERE nazwa_firmy = '$nazwa_firmy'";
						$query = mysqli_query($conn, $sql);
						if(!$query) {
							echo "Błąd logo";
						}
						else {
							$_SESSION['edytowano_corp'] = true;
						}
					}
					if($mail) {
						$sql = "UPDATE uzytkownicy SET mail = '$mail' WHERE login = '$nazwa_firmy'";
						$query = mysqli_query($conn, $sql);
						if(!$query) {
							echo "Błąd logo";
						}
						else {
							$_SESSION['edytowano_corp'] = true;
						}
					}
					if($nr_tel) {
						$sql = "UPDATE uzytkownicy SET nr_tel = '$nr_tel' WHERE login = '$nazwa_firmy'";
						$query = mysqli_query($conn, $sql);
						if(!$query) {
							echo "Błąd logo";
						}
						else {
							$_SESSION['edytowano_corp'] = true;
						}
					}
					if(!($mail&&$nr_tel&&$img&&$budzet&&$kraj)){
						$_SESSION['nie_wypelniono'] = true;
					}
					header('refresh: 0');
					mysqli_close($conn);
				}
			?>
			
		</div>
	</div>
</body>

<?php
	$dbh = null;
?>

</html>

