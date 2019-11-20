<?php
//w razie bledow flush
	ob_start();
	include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');	
?>

<html>
<head>
	<meta charset="utf-8">
	<title>Panel firmy</title></title>
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
			<form method="POST" action="/linie_lot/pages/add_pilot.php">
				<table class="table_edit">
					<tr colspan="2">
						<th colspan="2">
							<?php
								if(isset($_SESSION['pilot_dodano'])) {
									echo "<a class='a4'>Zatrudniono nowego pilota!</a>";
									$_SESSION['pilot_dodano'] = null;
								}
								else if(isset($_SESSION['nie_wypelniono'])) {
									echo "<a class='a4'>Nie wypełniono wszystkich pól!</a>";
									$_SESSION['nie_wypelniono'] = null;
								}
							?>
						</th>
					</tr>
					<tr>
						<th><a class='a3'>Imię: </th><th><input type="text" name="imie" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Nazwisko: </th><th><input type="text" name="nazwisko" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Pensja: </th><th><input type="number" name="pensja" class="textarea"></th>
					</tr>
					<tr>
						<th colspan='4'>
							<input class="button3" type="submit" value="Zatrudnij" name="add_pilot">
							<input class="button3" type="reset" value="Zresetuj" name="reset">
						</th>
					</tr>
				</table>	
			</form>
			
			<?php
				if(isset($_POST['add_pilot'])) {	
					$imie = $_POST['imie'];
					$nazwisko = $_POST['nazwisko'];
					$pensja = $_POST['pensja'];
					$date = date("Y-m-d");
					$nazwa_firmy = $_SESSION['login'];				
					
					if($imie != null&&$nazwisko != null&&$pensja != null) {
							try {
								$sql = "INSERT INTO piloci (imie, nazwisko, data_zatrudnienia, pensja, przewoznicy_nazwa_firmy)
									VALUES ('$imie', '$nazwisko', '$date', $pensja, '$nazwa_firmy')";
								$dbh->exec($sql);
							}
							catch(PDOEXception $e){
								echo "Wystąpił problem (PDOException)";
							}
							$_SESSION['pilot_dodano'] = true;
							header('refresh: 0');
						
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

