<?php
include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');
ob_start();
?>


<html>
<head>
<meta charset="utf-8">
<title>Linie lotnicze</title>
<link rel="stylesheet" text="text/css" href="/linie_lot/styles/style.css">

</head>

<body style="margin: 0;">
<div id="header">
	<div class="logo">
		<a href="/linie_lot/index.php"><img src="/linie_lot/img/plane.png" ></a>
	</div>
	<div class="weryfikacja">
		<?php
			if(isset($_SESSION['zalogowany'])){
				if($_SESSION['login'] == "admin"){
					echo( "<a><b>Login:</b> ".$_SESSION['login']."</a>");
					echo( "<input class='button2' type='button2' onclick= \"location.href='/linie_lot/pages/admin_account.php'\" value='Admin Panel'/>" );
					echo( "<input class='button2' type='button2' onclick= \"location.href='/linie_lot/pages/my_account.php'\" value='Moje Konto'/>" );
					echo( "<input class='button2' type='button2' onclick= \"location.href='/linie_lot/pages/logged_out.php'\" value='Wyloguj'/>" );
				}
				else {
					echo( "<input class='button2' type='button2' onclick= \"location.href='/linie_lot/pages/my_account.php'\" value='Moje Konto'/>" );
					echo( "<input class='button2' type='button2' onclick= \"location.href='/linie_lot/pages/logged_out.php'\" value='Wyloguj'/>" );
					if(isset($_SESSION['przewoznik']))
						echo( "<a><b>Przewoźnik: </b><br>".$_SESSION['login']."</a>");
					else
						echo( "<a><b>Login:</b>".$_SESSION['login']."</a>");
				}
			}
			else
				include('/opt/lampp/htdocs/linie_lot/scripts/login.html');
		 ?>
	</div>
</div>

<div id="aside">
	<div class="menu">
		<?php
			include("/opt/lampp/htdocs/linie_lot/scripts/menu.php");
		?>
	</div>
</div>

<div id="main">
	<div class="content">
		<?php
			//wyliczanie ilosci miejsc
			$nazwa_lotu = $_SESSION['flight'];
			$sql = "SELECT ilosc_miejsc FROM samoloty WHERE numer_seryjny IN(SELECT samoloty_numer_seryjny
								FROM loty WHERE nazwa_lotu = '$nazwa_lotu')";
			$ilosc_miejsc_ogolnie = 0;
			$ilosc_kupionych_biletow = 0;			
			foreach($dbh->query($sql) as $row) {
				$ilosc_miejsc_ogolnie = $row['ilosc_miejsc'];
			}
			
			$sql = "SELECT * from uzytkownicy where login in (select uzytkownicy_login from uzytkownicy_has_loty
                                        where loty_nazwa_lotu in (select nazwa_lotu from loty where nazwa_lotu = '$nazwa_lotu'))";
                  
                  $spr = $dbh->prepare($sql);
                  $spr->execute();
                  $ilosc_kupionych_biletow = $spr->rowCount();
                  $_SESSION['ilosc_miejsc'] = $ilosc_miejsc_ogolnie - $ilosc_kupionych_biletow;
			
		?>
		
		<?php
		$nazwa_lotu = $_SESSION['flight'];

			if($_SESSION['typ'] == 'przewoznik') {
				$sql = "SELECT * FROM loty WHERE przewoziciele_nazwa_firmy IS NOT NULL AND nazwa_lotu = '$nazwa_lotu'";
				foreach($dbh->query($sql) as $row) {
					$koszt = $row['koszt'];
					$img = $row['img'];
					$czas = $row['czas_min'];
					$km = $row['ilosc_km'];
					$start = $row['start'];
					$cel = $row['cel'];
					$data = $row['data'];
					$typ_lotu = $row['typ'];
					
					$sql2 = "select miasto from destynacje where id_lotniska = '$start'";
					$sql3 = "select miasto from destynacje where id_lotniska = '$cel'";
					$miasto_start = '';
					$miasto_cel = '';
					foreach($dbh->query($sql2) as $row) {
						$miasto_start = $row['miasto'];
					}
					foreach($dbh->query($sql3) as $row) {
						$miasto_cel = $row['miasto'];
					}
					
					echo "<center><table class='table_shows' style='margin: 3%;'>
						<tr>
							<th colspan='3' class='naglowek'>$nazwa_lotu</th>
						</tr>
						<tr>
						<th class='wiersz'>Typ lotu</th><td class='wiersz'>$typ_lotu</td>
						</tr>
						<tr>
						<th class='wiersz'>Przychód:</th><td class='wiersz'>$koszt zł</td>
						<td rowspan='7'><img class='table_img' src='$img'/></td>
						</tr>
						<tr>
						<th class='wiersz'>Data</th><td class='wiersz'>$data</td>
						</tr>
						<tr>
						<th class='wiersz'>Czas (min)</th><td class='wiersz'>$czas</td>
						</tr>
						<tr>
						<th class='wiersz'>Ilość KM</th><td class='wiersz'>$km</td>
						</tr>
						<tr>
						<th class='wiersz'>Start</th><td class='wiersz'>$miasto_start</td>
						</tr>
						<tr>
						<th class='wiersz'>Cel</th><td class='wiersz'>$miasto_cel</td>
						</tr>
					
					</table></center>";
				}
				
				$login = $_SESSION['login'];
				$pilot; $samolot;
				$samolot_producent; $samolot_model; $pilot_imie; $pilot_nazwisko;
				/////////////////pilot
				$sql = "SELECT piloci_id_pilota, samoloty_numer_seryjny FROM loty WHERE nazwa_lotu = '$nazwa_lotu'";
				foreach($dbh->query($sql) as $row) {
					$pilot = $row['piloci_id_pilota'];
					$samolot = $row['samoloty_numer_seryjny'];
				}
				$sql = "SELECT imie, nazwisko FROM piloci WHERE id_pilota = $pilot";
				foreach($dbh->query($sql) as $row) {
					$pilot_imie = $row['imie'];
					$pilot_nazwisko = $row['nazwisko'];
				}
				/////////////////////samolot
				$samolot_producent; $samolot_model; $pilot_imie; $pilot_nazwisko;
				$sql = "SELECT producent, model FROM samoloty WHERE numer_seryjny = '$samolot'";
				foreach($dbh->query($sql) as $row) {
					$samolot_model = $row['model'];
					$samolot_producent = $row['producent'];
				}
				
				$przewoziciel;
				$sql = "SELECT przewoziciele_nazwa_firmy FROM loty WHERE nazwa_lotu = '$nazwa_lotu'";
				foreach($dbh->query($sql) as $row) {
					$przewoziciel = $row['przewoziciele_nazwa_firmy'];
				}
				
				if($pilot_imie && $pilot_nazwisko && $samolot_model && $samolot_producent && ($przewoziciel == $login)) {
					echo "
					<table class='table_get'>
					<tr>
					<form action='/linie_lot/pages/flight.php' method='POST'>
					<th colspan='3'class='tresc'>Ten lot należy już do Ciebie!</th>
					</tr>
					<tr>
					<td class='wiersz' style='background-color: dodgerblue'>Nazwa firmy</td>
					<td class='wiersz' style='background-color: dodgerblue'>Samolot
					<td class='wiersz' style='background-color: dodgerblue'>Pilot
					</tr>
					<tr>
					<th class='wiersz'>$login</td>
					<td class='wiersz'>$pilot_imie $pilot_nazwisko</td>
					<td class='wiersz'>$samolot_producent $samolot_model</td>
					</tr>
					<tr>
					<td colspan='3'>
					</td>
					</tr>
					</table>";
				}
				else {
					echo "
					<table class='table_get'>
					<tr>
					<form action='/linie_lot/pages/flight.php' method='POST'>
					<th colspan='3'class='tresc'>Ten lot należy do konkurencji!</th>
					</tr>
					<tr>
					<td class='wiersz' style='background-color: dodgerblue'>Nazwa firmy</td>
					<td class='wiersz' style='background-color: dodgerblue'>Samolot
					<td class='wiersz' style='background-color: dodgerblue'>Pilot
					</tr>
					<tr>
					<th class='wiersz'>$przewoziciel</td>
					<td class='wiersz'>$pilot_imie $pilot_nazwisko</td>
					<td class='wiersz'>$samolot_producent $samolot_model</td>
					</tr>
					<tr>
					<td colspan='3'>
					</td>
					</tr>
					</table>";
				}
			}
			else if($_SESSION['typ'] == 'klient') {
				$sql = "SELECT * FROM loty WHERE przewoziciele_nazwa_firmy IS NOT NULL AND nazwa_lotu = '$nazwa_lotu'";
				foreach($dbh->query($sql) as $row) {
					$koszt = $row['koszt'];
					$img = $row['img'];
					$czas = $row['czas_min'];
					$km = $row['ilosc_km'];
					$start = $row['start'];
					$cel = $row['cel'];
					$data = $row['data'];
					$typ_lotu = $row['typ'];
					$godzina = $row['godzina_odlotu'];
					
					$sql2 = "select miasto from destynacje where id_lotniska = '$start'";
					$sql3 = "select miasto from destynacje where id_lotniska = '$cel'";
					$miasto_start = '';
					$miasto_cel = '';
					foreach($dbh->query($sql2) as $row) {
						$miasto_start = $row['miasto'];
					}
					foreach($dbh->query($sql3) as $row) {
						$miasto_cel = $row['miasto'];
					}
					
					echo "<center><table class='table_shows' style='margin: 3%;'>
						<tr>
							<th colspan='3' class='naglowek'>$nazwa_lotu</th>
						</tr>
						<tr>
						<th class='wiersz'>Typ lotu</th><td class='wiersz'>$typ_lotu</td>
						</tr>
						<tr>
						<th class='wiersz'>Cena:</th><td class='wiersz'>$koszt zł</td>
						<td rowspan='8'><img class='table_img' src='$img'/></td>
						</tr>
						<tr>
						<th class='wiersz'>Data</th><td class='wiersz'>$data</td>
						</tr>
						<tr>
						<th class='wiersz'>Odlot o: </th><td class='wiersz'>$godzina:00</td>
						</tr>
						<tr>
						<th class='wiersz'>Czas (min)</th><td class='wiersz'>$czas min</td>
						</tr>
						<tr>
						<th class='wiersz'>Ilość KM</th><td class='wiersz'>$km km</td>
						</tr>
						<tr>
						<th class='wiersz'>Start</th><td class='wiersz'>$start $miasto_start</td>
						</tr>
						<tr>
						<th class='wiersz'>Cel</th><td class='wiersz'>$cel $miasto_cel</td>
						</tr>
					
					</table></center>";
				}
					
					$ilosc_miejsc = $_SESSION['ilosc_miejsc'];
					
					if($ilosc_miejsc > 0) {
						echo "<table class='table_get'>
						<tr>
						<th colspan='2' class='naglowek'>Pozostało miejsc: 
						<a style='color: red; font-size: 3vh'> $ilosc_miejsc </a></th>
						</tr>
						<tr>
						<form action='/linie_lot/pages/flight.php' method='POST'>
						<th class='wiersz'>
						<input type='submit' class='button4' name='kup_bilet' value='Kup bilet'>
						</th>
						<th class='wiersz'>
						<input type='submit' class='button4' name='ulubiony' value='Ulubione'>
						</th>
						</form>
						</tr>
						</table>";
					}
					else {
						echo "
						<table class='table_get'>
						<tr><th colspan='2' class='naglowek' style='color: red;'>Bilety wyprzedane!</th></tr>
						</table>";
					}
			
					?>
					
					<?php
			
		}
		else if($_SESSION['typ'] == "admin") {
				$sql = "SELECT * FROM loty WHERE przewoziciele_nazwa_firmy IS NOT NULL AND nazwa_lotu = '$nazwa_lotu'";
				foreach($dbh->query($sql) as $row) {
					$koszt = $row['koszt'];
					$img = $row['img'];
					$czas = $row['czas_min'];
					$km = $row['ilosc_km'];
					$start = $row['start'];
					$cel = $row['cel'];
					$data = $row['data'];
					$typ_lotu = $row['typ'];
					$godzina = $row['godzina_odlotu'];
					
					$sql2 = "select miasto from destynacje where id_lotniska = '$start'";
					$sql3 = "select miasto from destynacje where id_lotniska = '$cel'";
					$miasto_start = '';
					$miasto_cel = '';
					foreach($dbh->query($sql2) as $row) {
						$miasto_start = $row['miasto'];
					}
					foreach($dbh->query($sql3) as $row) {
						$miasto_cel = $row['miasto'];
					}
					
					echo "<center><table class='table_shows' style='margin: 3%;'>
						<tr>
							<th colspan='3' class='naglowek'>$nazwa_lotu</th>
						</tr>
						<tr>
						<th class='wiersz'>Typ lotu</th><td class='wiersz'>$typ_lotu</td>
						</tr>
						<tr>
						<th class='wiersz'>Cena:</th><td class='wiersz'>$koszt zł</td>
						<td rowspan='8'><img class='table_img' src='$img'/></td>
						</tr>
						<tr>
						<th class='wiersz'>Data</th><td class='wiersz'>$data</td>
						</tr>
						<tr>
						<th class='wiersz'>Odlot o: </th><td class='wiersz'>$godzina:00</td>
						</tr>
						<tr>
						<th class='wiersz'>Czas (min)</th><td class='wiersz'>$czas min</td>
						</tr>
						<tr>
						<th class='wiersz'>Ilość KM</th><td class='wiersz'>$km km</td>
						</tr>
						<tr>
						<th class='wiersz'>Start</th><td class='wiersz'>$start $miasto_start</td>
						</tr>
						<tr>
						<th class='wiersz'>Cel</th><td class='wiersz'>$cel $miasto_cel</td>
						</tr>
					
					</table></center>";
					
					$login = $_SESSION['login'];
					$pilot; $samolot;
					$samolot_producent; $samolot_model; $pilot_imie; $pilot_nazwisko;
					/////////////////pilot
					$sql = "SELECT piloci_id_pilota, samoloty_numer_seryjny FROM loty WHERE nazwa_lotu = '$nazwa_lotu'";
					foreach($dbh->query($sql) as $row) {
						$pilot = $row['piloci_id_pilota'];
						$samolot = $row['samoloty_numer_seryjny'];
					}
					$sql = "SELECT imie, nazwisko FROM piloci WHERE id_pilota = $pilot";
					foreach($dbh->query($sql) as $row) {
						$pilot_imie = $row['imie'];
						$pilot_nazwisko = $row['nazwisko'];
					}
					/////////////////////samolot
					$samolot_producent; $samolot_model; $pilot_imie; $pilot_nazwisko;
					$sql = "SELECT producent, model FROM samoloty WHERE numer_seryjny = '$samolot'";
					foreach($dbh->query($sql) as $row) {
						$samolot_model = $row['model'];
						$samolot_producent = $row['producent'];
					}
					
					$nazwa_firmy;
					$sql = "SELECT nazwa_firmy FROM przewoznicy WHERE nazwa_firmy IN(SELECT przewoziciele_nazwa_firmy FROM
									loty WHERE nazwa_lotu = '$nazwa_lotu')";
					foreach($dbh->query($sql) as $row) {
						$nazwa_firmy = $row['nazwa_firmy'];
					}
					
					echo "
					<table class='table_get'>
					<tr>
					<form action='/linie_lot/pages/flight.php' method='POST'>
					<th colspan='3'class='tresc'>Ten lot należy do przewoźnika!</th>
					</tr>
					<tr>
					<td class='wiersz' style='background-color: dodgerblue'>Nazwa firmy</td>
					<td class='wiersz' style='background-color: dodgerblue'>Samolot
					<td class='wiersz' style='background-color: dodgerblue'>Pilot
					</tr>
					<tr>
					<th class='wiersz'>$nazwa_firmy</td>
					<td class='wiersz'>$pilot_imie $pilot_nazwisko</td>
					<td class='wiersz'>$samolot_producent $samolot_model</td>
					</tr>
					<tr>
					<td colspan='3'>
					</td>
					</tr>
					</table>";
					
					echo( "<input class='button2' type='button2' style='position:absolute; margin-left: 44%; margin-top: 20%;' onclick= \"location.href='/linie_lot/pages/all_airways.php'\" value='Wszystkie loty'/>" );
				}
		}
		?>
		
		<?php
		if(isset($_POST['samoloty'])) {
			header('refresh: 0; url="/linie_lot/pages/planes.php"');
		}
		?>
		
		<?php
		if(isset($_POST['piloci'])) {
			header('refresh: 0; url="/linie_lot/pages/pilots.php"');
		}
		?>
		
		<?php
		if(isset($_POST['zatwierdz_przewoznik'])) {
			if(isset($_POST['samolot']))
				$samolot = $_POST['samolot'];
				
			if(isset($_POST['pilot']))
				$pilot = $_POST['pilot'];
			
			$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}
			
			
			$samolot_przewoznik = false;
			$pilot_przewoznik = false;
			if($samolot&&$pilot) {
				$sql = "SELECT nazwa_firmy FROM przewoznicy WHERE nazwa_firmy IN(SELECT pzewoznicy_nazwa_firmy FROM samoloty
									WHERE numer_seryjny = '$samolot')";
				$spr = $dbh->prepare($sql);
				$spr->execute();
				$count = $spr->rowCount();
				if($count > 0)
					$samolot_przewoznik = true;
					
				$sql = "SELECT nazwa_firmy FROM przewoznicy WHERE nazwa_firmy IN(SELECT przewoznicy_nazwa_firmy FROM piloci
									WHERE id_pilota = $pilot)";
				$spr = $dbh->prepare($sql);
				$spr->execute();
				$count = $spr->rowCount();
				if($count == 1)
					$pilot_przewoznik = true;
			}
			else {
				echo "
				<script>
				window.alert('Nie wypełniono wszystkich potrzebnych danych!');
				</script>";
			}
			
			if($samolot_przewoznik == true && $pilot_przewoznik == true) {
				$numer_seryjny = $_POST['samolot'];
				$id_pilota = $_POST['pilot'];
				$sql = "UPDATE loty SET przewoziciele_nazwa_firmy = '$nazwa_firmy' WHERE nazwa_lotu = '$nazwa_lotu'";
				mysqli_query($conn, $sql);
				$sql = "UPDATE loty SET samoloty_numer_seryjny = $numer_seryjny WHERE nazwa_lotu = '$nazwa_lotu'";
				mysqli_query($conn, $sql);
				$sql = "UPDATE loty SET piloci_id_pilota = $id_pilota WHERE nazwa_lotu = '$nazwa_lotu'";
				mysqli_query($conn, $sql);
				$sql = "UPDATE piloci SET ilosc_lotow = ilosc_lotow + 1 WHERE id_pilota = $id_pilota";
				mysqli_query($conn, $sql);
				$sql = "UPDATE samoloty SET ilosc_lotow = ilosc_lotow + 1 WHERE numer_seryjny = $numer_seryjny";
				mysqli_query($conn, $sql);
				
				$koszt = 0;
				$sql = "SELECT koszt FROM loty WHERE nazwa_lotu = '$nazwa_lotu'";
				foreach($dbh->query($sql) as $row) {
					$koszt = $row['koszt'];
				}
				$ilosc_miejsc = 0;
				$sql = "SELECT ilosc_miejsc FROM samoloty WHERE numer_seryjny = '$samolot'";
				foreach($dbh->query($sql) as $row) {
					$ilosc_miejsc = $row['ilosc_miejsc'];
				}
								
				$sql = "UPDATE przewoznicy SET budzet = budzet + $koszt * $ilosc_miejsc WHERE nazwa_firmy = '$nazwa_firmy'";
				mysqli_query($conn, $sql);
				echo "<center><a class='a3'>Lot został przejęty!<br>Zostaniesz przeniesiony do panelu swoich lotów..</a></center>";
				$_SESSION['flight'] = null;
				header('refresh: 2; url="/linie_lot/pages/corp_flights.php"');
			}
			else if($samolot_przewoznik == false && $pilot_przewoznik == false) {
				echo "<center><a class='a2'>Błędne ID pilota oraz numer seryjny samolotu!</a></center>";

			}
			else if($pilot_przewoznik == false) {
				echo "<center><a class='a2'>Błędne ID pilota!</a></center>";

			}
			else if($samolot_przewoznik == false) {
				echo "<center><a class='a2'>Błędny numer seryjny samolotu!</a></center>";

			}
			
			mysqli_close($conn);
		}
		?>
		
		<?php
			if(isset($_POST['kup_bilet'])) {
				$login = $_SESSION['login'];
				$nazwa_lotu = $_SESSION['flight'];
				
				$sql = "SELECT numer FROM karty_klientow WHERE numer IN (SELECT karty_klientow_numer
																		FROM uzytkownicy where login = '$login')";
				$spr = $dbh->prepare($sql);
				$spr->execute();
				$count = $spr->rowCount();														
				
				if($count == 1) {//CZYLI UZYTKOWNIK MA JUZ KARTE 
					$stan_konta = 0;
					$koszt = 0;
					$sql_stan_konta = "SELECT stan_konta FROM karty_klientow WHERE numer IN (SELECT karty_klientow_numer
																						FROM uzytkownicy
																						WHERE login = '$login')";
					foreach($dbh->query($sql_stan_konta) as $row) {
						$stan_konta = $row['stan_konta'];
					}
					
					$sql_koszt = "SELECT koszt FROM loty WHERE nazwa_lotu = '$nazwa_lotu'";
					foreach($dbh->query($sql_koszt) as $row) {
						$koszt = $row['koszt'];
					}
						
						
					if($koszt <= $stan_konta) {		
						$sql = "INSERT INTO uzytkownicy_has_loty (loty_nazwa_lotu, uzytkownicy_login)
									VALUES('$nazwa_lotu', '$login')";	
						$result = $dbh->exec($sql);
					
						if($result) {
							echo "<center><br><a class='a3'>Zakupiłeś lot! Został on dodany i możesz go edytować w Twoim panelu!</a></center>";
							
							$sql_transakcja = "UPDATE karty_klientow SET stan_konta = $stan_konta - $koszt
									WHERE numer IN (SELECT karty_klientow_numer FROM uzytkownicy WHERE login = '$login')";
							
							$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
							mysqli_query($conn, $sql_transakcja);
							
							$sql_prestiz = "UPDATE uzytkownicy SET prestiz = prestiz + $koszt / 10 WHERE login = '$login'";
							mysqli_query($conn, $sql_prestiz);
							
							
							$data = date("Y-m-d");
							$sql_data_transakcji = "UPDATE karty_klientow SET data_transakcji = '$data'
							WHERE numer IN (select karty_klientow_numer from uzytkownicy where login = '$login')";
							mysqli_query($conn, $sql_data_transakcji);
							
							$sql_ilosc_transakcji = "UPDATE karty_klientow SET ilosc_transakcji = ilosc_transakcji + 1
							WHERE numer IN (select karty_klientow_numer from uzytkownicy where login = '$login')";
							mysqli_query($conn, $sql_ilosc_transakcji);
							
							mysqli_close($conn);
							
							header('refresh: 2; url="/linie_lot/pages/my_airways.php"');
						}
					}
					else {
						echo "<center><br><a class='a2'>Masz za mało pieniędzy na swoim koncie!<br>
							Doładuj konto, aby cieszyć się podróżami!</a></center>";
					}
				
				}
				else {
					echo "<center><br><br><a class='a2'>Wystąpił problem z zakupem lotu!<br>
							Nie znaleziono karty klienta.<br>
							Zostajesz przekierowany do swojego panelu..</a></center>";
					header('refresh: 2; url="/linie_lot/pages/my_account.php"');
				}
			}		
			
		?>
		

	</div>
</div>

<div id="footer">
<a href="/linie_lot/pages/help.php" style="font-weight: bold; font-size: 2vh; color: red; margin-top: 1%; margin-right: 1%; float: right;">POMOC</a>
</div>

</body>
</html>
