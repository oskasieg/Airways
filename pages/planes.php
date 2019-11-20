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
		if(isset($_SESSION['flight_add'])) {
			$_SESSION['flight_add'] = null;
			echo "<input class='button2' type='button' onclick= \"location.href='/linie_lot/pages/airway_add.php'\" value='<< Powrót'/>";
		}
		else if(isset($_SESSION['flight_edit'])) {
			$_SESSION['flight_edit'] = null;
			echo "<input class='button2' type='button' onclick= \"location.href='/linie_lot/pages/airway_edit.php'\" value='<< Powrót'/>";
		}
		else
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
			<?php
				$czy_zarejestrowana = 0;
				$sql = "SELECT nazwa_firmy FROM przewoznicy WHERE nazwa_firmy = '$login'";
				foreach($dbh->query($sql) as $row) {
					$czy_zarejestrowana++;
				}		
		
				//jezeli firma nie jest zarejestrowana:
				if($czy_zarejestrowana == 0) {
					echo "<table class='table_content'>
						<tr>
							<td>
								<a>Twoja firma nie została jeszcze zarejestrowana! Aby to zrobić kliknij w poniższy przycisk.</a>
							</td>
						</tr>
						<tr>
							<td>
								<center><input class='button3' type='button' onclick= \"location.href='/linie_lot/pages/register_corp.php'\" value='Zarejestruj'/></center>
							</td>
						</tr>
						</table>";
				}
				
				$_SESSION['licznik'] = 0;
				$login = $_SESSION['login'];
				$a = array(); // tablica nazw przyciskow do usuwania

				$sql = "SELECT numer_seryjny, producent, model, rok_produkcji, ilosc_miejsc, ilosc_lotow, zdjecie
					FROM samoloty WHERE pzewoznicy_nazwa_firmy = '$login'";
				foreach ($dbh->query($sql) as $row) {
					$numer_seryjny = $row['numer_seryjny'];
					$producent = $row['producent'];
					$model = $row['model'];
					$rok_produkcji = $row['rok_produkcji'];
					$ilosc_miejsc = $row['ilosc_miejsc'];
					$ilosc_lotow = $row['ilosc_lotow'];
					$img = $row['zdjecie'];
					$nazwa = $row['numer_seryjny'];

					
					echo "<table class='table_shows'>
							<tr>
								<th>
									<a class='a3'>Numer seryjny:</a>
								</th>
								<td>
									<a class='a2'>$numer_seryjny</a>
								</td>
								<td class='table_img' rowspan='6'>
									<img src='$img'/>
								</td>
								<td rowspan='8'>
									<form method='POST' action='/linie_lot/pages/planes.php'>
										<input class='button_table' type='submit' value='Usuń' name='$nazwa'/>
									</form>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Producent:</a>
								</th>
								<td>
									<a class='a2'>$producent</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Model: </a>
								</th>
								<td>
									<a class='a2'>$model</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Rok produkcji: </a>
								</th>
								<td>
									<a class='a2'>$rok_produkcji</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Ilość miejsc: </a>
								</th>
								<td>
									<a class='a2'>$ilosc_miejsc</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Ilość odbytych lotów: </a>
								</th>
								<td>
									<a class='a2'>$ilosc_lotow</a>
								</td>
						</table>";
						
						array_push($a, $nazwa);
						
						//formularz, ktory umozliwia odswiezenie strony i wykonanie operacji np usuwania
						$_SESSION['licznik']++;
					}
			?>		
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a[$i]])){
						$sql = "SELECT * FROM samoloty WHERE numer_seryjny = '$a[$i]' AND numer_seryjny IN (SELECT samoloty_numer_seryjny
												FROM loty)";
						$spr = $dbh->prepare($sql);
						$spr->execute();
						$count = $spr->rowCount();
						
						if($count == 0) {
							$sql = "DELETE FROM samoloty where numer_seryjny = '$a[$i]'";
							$result = $dbh->exec($sql);
							if($result)
								header('refresh: 0');
								
							echo "<script>
									window.alert('Usunięto samolot o numerze seryjnym: $a[$i]!');
								</script>";
						}
						else {
							echo "
							<script>
								window.alert('Nie można usunąć tego samolotu! Jest przypisany do jakiegoś lotu!');
							</script>";
						}
					}
				}
			?>
			
		</div>
	</div>
	<?php
		if(isset($_SESSION['czy_zarejestrowana'])) {
			if($_SESSION['czy_zarejestrowana'] != false) 
				echo "<input class='button_add' type='button2' onclick= \"location.href='/linie_lot/pages/add_plane.php'\" value='Dodaj samolot'/>";
		}
		else 
			echo "<input class='button_add' type='button2' onclick= \"location.href='/linie_lot/pages/add_plane.php'\" value='Dodaj samolot'/>";
	?>
</body>

<?php
	$dbh = null;
?>

</html>

