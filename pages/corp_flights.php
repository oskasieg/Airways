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
			<?php
				$_SESSION['licznik'] = 0;
				$login = $_SESSION['login'];
				$a = array(); // tablica nazw przyciskow do usuwania
				$a2 = array(); // tablica nazw przyciskow do edycji
				$sql = "SELECT nazwa_lotu, koszt, data, czas_min, ilosc_km, start, cel, img, typ, godzina_odlotu,
					samoloty_numer_seryjny, piloci_id_pilota,
					samoloty_numer_seryjny, piloci_id_pilota FROM loty WHERE przewoziciele_nazwa_firmy = '$login'
					ORDER BY data DESC";
				foreach ($dbh->query($sql) as $row) {
					$nazwa = $row['nazwa_lotu'];
					$koszt = $row['koszt'];
					$data = $row['data'];
					$czas = $row['czas_min'];
					$km = $row['ilosc_km'];
					$start = $row['start'];
					$cel = $row['cel'];
					$img = $row['img'];
					$typ = $row['typ'];
					$nazwa_edit = "{$nazwa}a";
					$godzina_odlotu = $row['godzina_odlotu'];
					$pilot_id = $row['piloci_id_pilota'];
					$samolot_id = $row['samoloty_numer_seryjny'];
					$pilot_imie = ""; $pilot_nazwisko = "";
					$samolot_model = "";
					$sql = "SELECT producent, model FROM samoloty WHERE numer_seryjny='$samolot_id'";
					foreach($dbh->query($sql) as $row) {
						$samolot_producent = $row['producent'];
						$samolot_model = $row['model'];
					}
					
					$sql = "SELECT imie, nazwisko FROM piloci WHERE id_pilota=$pilot_id";
					foreach($dbh->query($sql) as $row) {
						$pilot_imie = $row['imie'];
						$pilot_nazwisko = $row['nazwisko'];
					}
					
					$sql2 = "select miasto, nazwa_lotniska from destynacje where id_lotniska = '$start'";
					$sql3 = "select miasto, nazwa_lotniska from destynacje where id_lotniska = '$cel'";
					$miasto_start = '';
					$miasto_cel = '';
					$lotnisko_start = '';
					$lotnisko_cel = '';
					foreach($dbh->query($sql2) as $row) {
						$miasto_start = $row['miasto'];
						$lotnisko_start = $row['nazwa_lotniska'];
					}
					foreach($dbh->query($sql3) as $row) {
						$miasto_cel = $row['miasto'];
						$lotnisko_cel = $row['nazwa_lotniska'];
					}
					
					echo "<table class='table_shows'>
							<tr>
								<th>
									<a class='a3'>Nazwa lotu:</a>
								</th>
								<td>
									<a class='a2'>$nazwa</a>
								</td>
								<td class='table_img' rowspan='9'>
									<img src='$img'/>
								</td>
								<td rowspan='9'>";
								$data_teraz = date("Y-m-d");
								if($data_teraz > $data) {
									echo "<center><a class='a5'>Już minął!</a></center>";
								}
								else {
							echo "	<form method='POST' action='/linie_lot/pages/corp_flights.php'>
										<input class='button_table2' type='submit'  value='E' name='$nazwa_edit'/><br><br>
										<input class='button_table3' type='submit' value='U' name='$nazwa'/>
									</form>
								</td>";
							}
					echo "	</tr>
							<tr>
								<th>
									<a class='a3'>Typ:</a>
								</th>
								<td>
									<a class='a2'>$typ</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Data: </a>
								</th>
								<td>
									<a class='a2'>$godzina_odlotu:00 $data r.</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Koszt: </a>
								</th>
								<td>
									<a class='a2'>$koszt zł</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Dystans: </a>
								</th>
								<td>
									<a class='a2'>$km km</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Czas lotu: </a>
								</th>
								<td>
									<a class='a2'>$czas min</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Start: </a>
								</th>
								<td>
									<a class='a2'>$start - $miasto_start, $lotnisko_start</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Cel: </a>
								</th>
								<td>
									<a class='a2'>$cel - $miasto_cel, $lotnisko_cel</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Pilot, Samolot: </a>
								</th>
								<td>
									<a class='a2'>$pilot_nazwisko, $samolot_producent $samolot_model</a>
								</td>
							</tr>
						</table>";
						
						array_push($a, $nazwa);
						array_push($a2, $nazwa_edit);
						
						//formularz, ktory umozliwia odswiezenie strony i wykonanie operacji np usuwania
						$_SESSION['licznik']++;
					}
			?>			
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a[$i]])){
						$nazwa_lotu = $a[$i];
						$sql = "SELECT login from uzytkownicy where login in (select uzytkownicy_login
								from uzytkownicy_has_loty where loty_nazwa_lotu in (select nazwa_lotu from loty
									where nazwa_lotu = '$nazwa_lotu'))";
						$spr = $dbh->prepare($sql);
						$spr->execute();
						$count = $spr->rowCount();
						
						$czy_zatwierdzony = false;
						$sql = "SELECT typ FROM loty WHERE nazwa_lotu = '$nazwa_lotu'";
						foreach($dbh->query($sql) as $row) {
							if(($row['typ'] == "VIP") || ($row['typ'] == "Standardowy") || ($row['typ'] == "Promocja"))
								$czy_zatwierdzony = true;
						}
						
						if($count == 0 && $czy_zatwierdzony == false) {
							$sql = "DELETE FROM loty where nazwa_lotu = '$nazwa_lotu'";
						
						$pilot = -1;
						$sql2 = "SELECT id_pilota FROM piloci WHERE id_pilota IN (SELECT piloci_id_pilota FROM loty WHERE nazwa_lotu = '$nazwa_lotu')";
						foreach($dbh->query($sql2) as $row) {
							$pilot = $row['id_pilota'];
						}
						$samolot = "";
						$sql2 = "SELECT numer_seryjny FROM samoloty WHERE numer_seryjny IN (SELECT samoloty_numer_seryjny FROM loty WHERE nazwa_lotu = '$nazwa_lotu')";
						foreach($dbh->query($sql2) as $row) {
							$samolot = $row['numer_seryjny'];
						}
						$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
						if (!$conn) {
							die("Connection failed: " . mysqli_connect_error());
						}
								
						$sql2 = "UPDATE piloci SET ilosc_lotow = ilosc_lotow - 1 WHERE id_pilota = $pilot";
						mysqli_query($conn, $sql2);
						$sql2 = "UPDATE samolot SET ilosc_lotow = ilosc_lotow - 1 WHERE numer_seryjny = '$samolot'";
						mysqli_query($conn, $sql2);
						
						$ilosc_miejsc = 0;
						$sql3 = "SELECT ilosc_miejsc FROM samoloty WHERE numer_seryjny = '$samolot'";
						foreach($dbh->query($sql3) as $row) {
							$ilosc_miejsc = $row['ilosc_miejsc'];
						}
						
						$koszt = 0;
						$sql3 = "SELECT koszt FROM loty WHERE nazwa_lotu = '$nazwa_lotu'";
						foreach($dbh->query($sql3) as $row) {
							$koszt = $row['koszt'];
						}
						
						$zarobek = $ilosc_miejsc * $koszt;
						$nazwa_firmy = $_SESSION['login'];
						$sql3 = "UPDATE przewoznicy SET budzet = budzet - $zarobek WHERE nazwa_firmy = '$nazwa_firmy' ";
						mysqli_query($conn, $sql3);
						
						mysqli_close($conn);
							
							$result = $dbh->exec($sql);
							if($result)
								header('refresh: 0');
								
							echo "<script>
									window.alert('Usunięto lot: $a[$i]!');
								</script>";
						}
						else {
							if($count != 0) {
								echo "<script>
										window.alert('Nie można usunąć! Ktoś już kupił lot: $a[$i]!');
									</script>";
							}
							else if($czy_zatwierdzony == true) {
								echo "<script>
										window.alert('Nie można usuwać zatwierdzonych lotów!');
									</script>";
							}
						}
					}
				}
			?>
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a2[$i]])){
						$nazwa_lotu = substr($a2[$i], 0, -1);
						//sprawdzamy czy nikt nie kupil juz tego lotu
						$sql = "SELECT login from uzytkownicy where login in (select uzytkownicy_login
								from uzytkownicy_has_loty where loty_nazwa_lotu in (select nazwa_lotu from loty
									where nazwa_lotu = '$nazwa_lotu'))";
						$spr = $dbh->prepare($sql);
						$spr->execute();
						$count = $spr->rowCount();

						$czy_zatwierdzony = true;
						$sql = "SELECT typ FROM loty WHERE nazwa_lotu = '$nazwa_lotu'";
						foreach($dbh->query($sql) as $row) {
							if(($row['typ'] == "VIP") || ($row['typ'] == "Standardowy") || ($row['typ'] == "Promocja"))
								$czy_zatwierdzony = true;
						} //WTF
						
						if($count == 0 && $czy_zatwierdzony == false) {
							$_SESSION['airway_edit'] = $a2[$i];
							header("refresh: 0; url='/linie_lot/pages/airway_edit.php");
						}
						else {
							if($count != 0) {
								echo "<script>
										window.alert('Nie można edytować tego lotu! Ktoś już go zamówił!');
									</script>";
							}
							else if($czy_zatwierdzony == true) {
								echo "<script>
										window.alert('Nie można edytować zatwierdzonych lotów!');
									</script>";
							}
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
			echo "<input class='button_add' type='button2' onclick= \"location.href='/linie_lot/pages/airway_add.php'\" value='Dodaj lot'/>";
	?>
</body>

<?php
	$dbh = null;
?>

</html>

