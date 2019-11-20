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
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/my_account.php'\" value='Informacje'/>";
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/my_airways.php'\" value='Moje loty'/>";				
				echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/edit_data.php'\" value='Zmien dane'/>";
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
			<?php
				$_SESSION['licznik'] = 0;
				$login = $_SESSION['login'];
				$a = array(); // tablica nazw przyciskow do usuwania
				$a2 = array(); // tablica nazw przyciskow do edycji
				$sql = "SELECT * FROM loty WHERE nazwa_lotu IN (SELECT loty_nazwa_lotu FROM uzytkownicy_has_loty
											WHERE uzytkownicy_login IN(SELECT login FROM uzytkownicy WHERE login = '$login'))";
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
					$przewoznik = $row['przewoziciele_nazwa_firmy'];
					$pilot = $row['samoloty_numer_seryjny'];
					$samoloty = $row['piloci_id_pilota'];
					$nazwa_opinia = $nazwa.'a';
					
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
					
					echo "<table class='table_shows'>
							<tr>
								<th>
									<a class='a3'>Nazwa lotu:</a>
								</th>
								<td>
									<a class='a2'>$nazwa</a>
								</td>
								<td class='table_img' rowspan='8'>
									<img src='$img'/>
								</td>
								<td rowspan='5' colspan='2'>
									<form method='POST' action='/linie_lot/pages/my_airways.php'>";
								$data_anuluj = strtotime($data);
								$data_anuluj = date("Y-m-d", strtotime("-7 days", $data_anuluj));
								$data_teraz = date("Y-m-d");
								if($data_anuluj >= $data_teraz) {
									echo "	<input class='button_table' type='submit' value='Anuluj' name='$nazwa'/>";
								}
								else if($data > $data_teraz) {
									echo "<center><a class='a5' style='font-size: 2.5vh;'>Nie można <br>już anulować lotu!</a></center>";
								}
								else {
									
									echo "<input type='submit' class='button_table' value='Opinia' name='$nazwa_opinia'>";
								}								
					echo" 			</form>
								</td>
							</tr>
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
									<a class='a2'>$data r.</a>
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
								<th>
									<a class='a3' style='text-align: left;'>Przewoźnik: </a><td>$przewoznik</td>
								</th>
							</tr>
							<tr>
								<th>
									<a class='a3'>Start: </a>
								</th>
								<td>
									<a class='a2'>$start $miasto_start</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Cel: </a>
								</th>
								<td>
									<a class='a2'>$cel $miasto_cel</a>
								</td>
							</tr>
						</table>";
						
						array_push($a, $nazwa);
						array_push($a2, $nazwa_opinia);
						
						//formularz, ktory umozliwia odswiezenie strony i wykonanie operacji np usuwania
						$_SESSION['licznik']++;
					}
			?>		
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a[$i]])){
					$login = $_SESSION['login'];
					$sql = "DELETE FROM uzytkownicy_has_loty where loty_nazwa_lotu = '$a[$i]' and uzytkownicy_login = '$login'";
					$result = $dbh->exec($sql);
					
					$koszt = 0;
					//koszt lotu 
					$sql = "SELECT koszt FROM loty WHERE nazwa_lotu = '$a[$i]'";
					foreach($dbh->query($sql) as $row) {
						$koszt = $row['koszt'];
					}
					
					$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
					if (!$conn) {
						die("Connection failed: " . mysqli_connect_error());
					}
					
					$sql = "UPDATE karty_klientow SET stan_konta = stan_konta + $koszt
					WHERE numer IN(SELECT karty_klientow_numer
					FROM uzytkownicy WHERE login = '$login')";
					mysqli_query($sql);
					
					$sql = "UPDATE karty_klientow SET ilosc_transakcji = ilosc_transakcji - 1
					WHERE numer in (SELECT karty_klientow_numer from uzytkownicy
					where login = '$login')";
					mysqli_query($sql);
					
					$sql_prestiz = "UPDATE uzytkownicy SET prestiz = prestiz - $koszt / 10 WHERE login = '$login'";
					mysqli_query($conn, $sql_prestiz);
					
					mysqli_close($conn);
					
					
						header('refresh: 0');
						
					echo "<script>
							window.alert('Anulowano lot: $a[$i]!');
						</script>";
					}
				}
			?>
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a2[$i]])){

						$_SESSION['flight'] = $a2[$i];
						header("refresh: 0; url='/linie_lot/pages/add_opinion.php");

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

