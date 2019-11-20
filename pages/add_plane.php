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
			<form method="POST" action="/linie_lot/pages/add_plane.php">
				<table class="table_edit">
					<tr colspan="2">
						<th colspan="2">
							<?php
								if(isset($_SESSION['samolot_istnieje'])){
									echo "<a class='a4'>Już istnieje samolot o takim numerze seryjnym!</a>";
									$_SESSION['samolot_istnieje'] = null;
								}
								else if(isset($_SESSION['samolot_dodano'])) {
									echo "<a class='a4'>Dodano nowy samolot do Twojego hangaru!</a>";
									$_SESSION['samolot_dodano'] = null;
								}
								else if(isset($_SESSION['nie_wypelniono'])) {
									echo "<a class='a4'>Nie wypełniono wszystkich pól!</a>";
									$_SESSION['nie_wypelniono'] = null;
								}
							?>
						</th>
					</tr>
					<tr>
						<th><a class='a3'>Numer seryjny: </th><th><input type="text" name="numer_seryjny" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Producent: </th><th><input type="text" name="producent" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Model: </th><th><input type="text" name="model" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Rok produkcji: </th><th><input type="text" name="rok_produkcji" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Ilość miejsc: </th><th><input type="text" name="ilosc_miejsc" class="textarea"></th>
					</tr>
					<tr>
						<th><a class='a3'>Zdjęcie (url): </th><th><input type="text" name="img" class="textarea"></th>
					</tr>
					<tr>
						<th colspan="2"><input type="submit" name="add" class="button3" value="Dodaj">
						<input class="button3" type="reset" value="Zresetuj" name="reset">
						</th>
					</tr>
				</table>	
			</form>
			
			<?php
				if(isset($_POST['add'])) {	
					$numer_seryjny = $_POST['numer_seryjny'];
					$producent = $_POST['producent'];
					$model = $_POST['model'];
					$rok_produkcji = $_POST['rok_produkcji'];
					$ilosc_miejsc = $_POST['ilosc_miejsc'];
					$img = $_POST['img'];
					$nazwa_firmy = $_SESSION['login'];
					$czy_istnieje = "SELECT numer_seryjny FROM SAMOLOTY WHERE numer_seryjny='$numer_seryjny'";
					
					$sql = "INSERT INTO samoloty (numer_seryjny, producent, model, rok_produkcji, ilosc_miejsc, ilosc_lotow, pzewoznicy_nazwa_firmy, zdjecie)
						VALUES ('$numer_seryjny', '$producent', '$model', $rok_produkcji, $ilosc_miejsc, 0, '$nazwa_firmy', '$img')";
				
					$spr = $dbh->prepare($czy_istnieje);
					$spr->execute();
					$tmp = $spr->rowCount(); 		
				
					
					if($numer_seryjny&&$producent&&$model&&$rok_produkcji&&$ilosc_miejsc&&$img) {
						if($tmp == 1) {
							$_SESSION['samolot_istnieje'] = true;
						}
						else{
							try {
								$dbh->exec($sql);
							}
							catch(PDOEXception $e){
								echo "Wystąpił problem (PDOException)";
							}
							$_SESSION['samolot_dodano'] = true;
							header('refresh: 0');
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

