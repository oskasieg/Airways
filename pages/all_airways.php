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
			<?php
				$_SESSION['licznik'] = 0;
				$login = $_SESSION['login'];
				$a = array(); // tablica nazw przyciskow do usuwania
				$a2 = array(); // tablica nazw przyciskow do edycji
				$sql = "SELECT nazwa_lotu, koszt, data, czas_min, ilosc_km, start, cel, img, typ FROM loty
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
					$nazwa_accept = "{$nazwa}a";

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
								<td rowspan='8'>";
								$data_teraz = date("Y-m-d");
								if($data_teraz > $data) {
									$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
									if (!$conn) {
										die("Connection failed: " . mysqli_connect_error());
									}
									$typ_nowy = $typ.'_uplynal';
									$sql2 = "UPDATE loty SET typ = '$typ_nowy'";
									
									echo "<center><a class='a5'>Już upłynął!</a></center>";
								}
									
							echo "
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
						array_push($a2, $nazwa_accept);
						
						//formularz, ktory umozliwia odswiezenie strony i wykonanie operacji np usuwania
						$_SESSION['licznik']++;
					}
			?>		
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a[$i]])){
						/*//sprawdzamy czy nikt nie kupil juz tego lotu
						$sql = "SELECT login from uzytkownicy where login in (select uzytkownicy_login
						from uzytkownicy_has_loty where loty_nazwa_lotu in (select nazwa_lotu from loty
						where nazwa_lotu = '$a[$i]'))";
						$spr = $dbh->prepare($sql);
						$spr->execute();
						$count = $spr->rowCount();
						
						if($count == 0) { */
						
						//odrzuca lot
							$sql = "DELETE FROM loty where nazwa_lotu = '$a[$i]'";
							$result = $dbh->exec($sql);
							if($result)
								header('refresh: 0');
								
							echo "<script>
									window.alert('Odrzucono lot: $a[$i]!');
								</script>";
						/*}
						else {
							echo "<script>
									window.alert('Nie można usunąć tego lotu! Ktoś już go zamówił!');
								</script>";
						}*/
					}
				}
			?>
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a2[$i]])){
						$nazwa_lotu_accept = substr($a2[$i], 0, -1);
						$typ_lotu_accept = ""; 
						$sql = "SELECT typ FROM loty WHERE nazwa_lotu = '$nazwa_lotu_accept'";
						foreach($dbh->query($sql) as $row) {
							$typ_lotu_accept = $row['typ'];
						}
						
						$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
						if (!$conn) {
							die("Connection failed: " . mysqli_connect_error());
						}
						
						$typ_lotu_accept = substr($typ_lotu_accept, 0, -9);
						$sql = "UPDATE loty SET typ = '$typ_lotu_accept' WHERE nazwa_lotu = '$nazwa_lotu_accept'";
						mysqli_query($conn, $sql);
						mysqli_close($conn);
						echo 
						"<script>
							window.alert('Przyjęto lot: $nazwa_lotu_accept!');
						</script>";
						header("refresh: 0");
					}
				}
			?>
			
			<?php
				/*for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a2[$i]])){
						//sprawdzamy czy nikt nie kupil juz tego lotu
						$sql = "SELECT login from uzytkownicy where login in (select uzytkownicy_login
								from uzytkownicy_has_loty where loty_nazwa_lotu in (select nazwa_lotu from loty
									where nazwa_lotu = '$a[$i]'))";
						$spr = $dbh->prepare($sql);
						$spr->execute();
						$count = $spr->rowCount();
						
						if($count == 0) {
							$_SESSION['airway_edit'] = $a2[$i];
							header("refresh: 0; url='/linie_lot/pages/airway_edit.php");
						}
						else {
							echo "<script>
									window.alert('Nie można edytować tego lotu! Ktoś już go zamówił!');
								</script>";
						}
					}
				}*/
			?>
		</div>
	</div>
	<?php
		echo "<input class='button_add' type='button2' onclick= \"location.href='/linie_lot/pages/all_airways.php'\" value='Wszystkie loty'/>";
	?>
</body>

<?php
	$dbh = null;
?>

</html>

