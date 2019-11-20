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
				$_SESSION['licznik'] = 0;
				$nazwa_firmy = $_SESSION['login'];
				$a = array(); // tablica nazw przyciskow do usuwania

				$sql = "SELECT id_pilota, imie, nazwisko, data_zatrudnienia, pensja, ilosc_lotow FROM piloci
					WHERE przewoznicy_nazwa_firmy = '$nazwa_firmy'";
					
				$czy_zarejestrowana = 0;
				$sql2 = "SELECT nazwa_firmy FROM przewoznicy WHERE nazwa_firmy = '$login'";
				foreach($dbh->query($sql2) as $row) {
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
					
				foreach ($dbh->query($sql) as $row) {
					$id_pilota = $row['id_pilota'];
					$imie = $row['imie'];
					$nazwisko = $row['nazwisko'];
					$data_zatrudnienia = $row['data_zatrudnienia'];
					$pensja = $row['pensja'];
					$ilosc_lotow = $row['ilosc_lotow'];
					
					echo "<table class='table_shows'>
							<tr>
								<th>
									<a class='a3'>ID pilota:</a>
								</th>
								<td>
									<a class='a2'>$id_pilota</a>
								</td>
								<td class='table_img' rowspan='5'>
									<center><img src='/linie_lot/img/pilot.jpg'/></center>
								</td>
								<td rowspan='8'>
									<form method='POST' action='/linie_lot/pages/pilots.php'>
										<input class='button_table' type='submit' value='Zwolnij' name='$id_pilota'/>
									</form>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Imię i nazwisko:</a>
								</th>
								<td>
									<a class='a2'>$imie $nazwisko</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Data zatrudnienia: </a>
								</th>
								<td>
									<a class='a2'>$data_zatrudnienia r.</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Pensja: </a>
								</th>
								<td>
									<a class='a2'>$pensja zł</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Ilość lotów: </a>
								</th>
								<td>
									<a class='a2'>$ilosc_lotow</a>
								</td>
							</tr>
						</table>";
						
						array_push($a, $id_pilota);
						
						//formularz, ktory umozliwia odswiezenie strony i wykonanie operacji np usuwania
						$_SESSION['licznik']++;
					}
			?>		
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a[$i]])){
						$sql = "SELECT * FROM piloci WHERE id_pilota = $a[$i] AND id_pilota IN (SELECT piloci_id_pilota
												FROM loty)";
						$spr = $dbh->prepare($sql);
						$spr->execute();
						$count = $spr->rowCount();
						
						if($count == 0) {
							$sql = "DELETE FROM piloci where id_pilota= '$a[$i]'";
							$result = $dbh->exec($sql);
							if($result)
								header('refresh: 0');
								
							echo "<script>
									window.alert('Usunięto pilota o ID: $a[$i]!');
								</script>";
						}
						else {
							echo "
							<script>
								window.alert('Nie można usunąć tego pilota! Jest przypisany do jakiegoś lotu!');
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
			echo "<input class='button_add' type='button2' onclick= \"location.href='/linie_lot/pages/add_pilot.php'\" value='Zatrudnij pilota'/>";
	?>
</body>

<?php
	$dbh = null;
?>

</html>

