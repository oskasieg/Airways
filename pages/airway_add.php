<?php
	ob_start();
	include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');
?>

<html>
<head>
<meta charset="utf-8">
<title>Panel admin</title>
<link rel="stylesheet" text="text/css" href="/linie_lot/styles/style2.css">
</head>

<body style="overflow-y: hidden">
	<?php		
		echo "<input class='button2' type='button' onclick= \"location.href='/linie_lot/index.php'\" value='<< Powrót'/>";
		echo "<center><a style='font-size: 5vh; margin-top: 2%; color: red; font-weight: bold;'> Dodawanie nowego lotu</a></center>";
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
		</div>
		
		<div id="panel">
			
			<form method="POST" action="/linie_lot/pages/airway_add.php">
					<center>
						<table class="table_edit" style="margin-top: 1%; margin-left: 2%;">
							<tr>
								<th colspan='4'>
									<?php 
										if(isset($_SESSION['add_lot'])) {
											echo "<a class='a4'>Dodano nowy lot!</a></th>"; 
											$_SESSION['add_lot'] = null;
										}
										else if(isset($_SESSION['start_cel_same'])) {
											echo "<a class='a5'>Start i cel muszą się różnić i istnieć w bazie! Nie dodano lotu!</a>";
											$_SESSION['start_cel_same'] = null;
										}
										else if(isset($_SESSION['lot_istnieje'])) {
											echo "<a class='a5'>Lot o podanej nazwie już istnieje!</a>";
											$_SESSION['lot_istnieje'] = null;
										}
										else if(isset($_SESSION['nie_wypelniono'])) {
											echo "<a class='a5'>Nie wypełniono wszystkich pól</a>";
											$_SESSION['nie_wypelniono'] = null;
										}
										else if(isset($_SESSION['zla_data'])) {
											echo "<a class='a5'>Ten dzień już minął..</a>";
											$_SESSION['zla_data'] = null;
										}	
										else if(isset($_SESSION['miasta_same'])) {
											echo "<a class='a5'>Miasta muszą się różnić!</a>";
											$_SESSION['miasta_same'] = null;
										}									
									?>
								</th>
							</tr>
							<tr>
								<th><center><a class="a3">Nazwa: </a></center></th><th><input class="textarea" type="text" name="nazwa"></th>		
							</tr>
								<th><a class="a3">Start:</a></th><th><input name="start" class="textarea" list="destynacje"></th>
							</tr>	
							<tr>
								<th><a class="a3">Cel:</a></th><th><input name="cel" class="textarea" list="destynacje"></th>
							</tr>
							<tr>							
								<th><a class="a3">Typ:</a></th>
								<th>
									<a class="a2"><input type="radio" name="typ" value="VIP">VIP</a>
									<a class="a2"><input type="radio" name="typ" value="Standardowy">Standard</a>
									<a class="a2"><input type="radio" name="typ" value="Promocja">Promocja</a>
								</th>	
							</tr>
							<tr>							
								<th><a class="a3">Data:</a></th><th><input class="textarea" type="date" name="data" value="2019-05-01"></th>		
							</tr>
							<tr>
								<th><a class="a3">Godzina odlotu:</a></th><th><input class="textarea" type="number" name="godzina_odlotu" min="0" max="23"></th>		
							</tr>
							<tr>
								<th><a class="a3">Dystans (km):</a></th><th><input class="textarea" type="number" name="km"></th>
							</tr>
							<tr>
								<th><a class="a3">Koszt:</a></th><th><input class="textarea" type="number" name="koszt"></th>
							</tr>				
							<tr>
								<th><a class="a3">Czas (min):</a></th><th><input class="textarea" type="number" name="czas"></th>
							</tr>				
							<tr>
							<tr>
								<th><a class="a3">Obrazek (url):</a></th><th><input class="textarea" type="text" name="img" value="/linie_lot/img/..."></th>						
							</tr>
							<tr>
								<th><a class="a3">Pilot:</a></th>
								<th class='wiersz'><input class="textarea" list='piloci' name='pilot' value='[id pilota]'><input type='Submit' class='button4' name='piloci' value='>>'></th>
							</tr>
							<tr>
								<th><center><a class="a3">Samolot:</a></center></th>
								<th><input class="textarea" list='samoloty' name='samolot' value='[numer seryjny]'><input type='Submit' class='button4' name='samoloty' value='>>'></th>
							</tr>
							<tr>
								<th colspan='4'>
									<input class="button3" type="submit" value="Dodaj lot" name="dodaj_lot">
									<input class="button3" type="reset" value="Zresetuj" name="reset">
								</th>
							</tr>
						</table>
					</center>
			</form>
			
				<?php	
					$nazwa_firmy = $_SESSION['login'];
					$sql = "SELECT numer_seryjny FROM samoloty where pzewoznicy_nazwa_firmy = '$nazwa_firmy'";
					$a = array();
					$licznik = 0;
					
					foreach($dbh->query($sql) as $row) {
						array_push($a, $row['numer_seryjny']);
						$licznik++;
					}
					
					echo "<datalist id='samoloty'>";
						for($i=0; $i < $licznik; $i++) {
							echo "<option value='$a[$i]'>";
						}
					echo "</datalist>";
					
					
					
					$sql = "SELECT id_pilota FROM piloci where przewoznicy_nazwa_firmy = '$nazwa_firmy'";
					$a2 = array();
					$licznik = 0;
					
					foreach($dbh->query($sql) as $row) {
						array_push($a2, $row['id_pilota']);
						$licznik++;
					}
					
					echo "<datalist id='piloci'>";
						for($i=0; $i < $licznik; $i++) {
							echo "<option value='$a2[$i]'>";
						}
					echo "</datalist>";
				?>
			
			<?php
				$sql = "SELECT id_lotniska FROM destynacje";
				$a = array();
				$licznik = 0;
				
				foreach($dbh->query($sql) as $row) {
					array_push($a, $row['id_lotniska']);
					$licznik++;
				}
				
				echo "<datalist id='destynacje'>";
					for($i=0; $i < $licznik; $i++) {
						echo "<option value='$a[$i]'>";
					}
				echo "</datalist>";
			?>
			
			<?php
				if(isset($_POST['dodaj_lot'])){
					if(isset($_POST['typ']))
						$typ = $_POST['typ'];
					$typ = $typ."_oczekuje";
					$nazwa = ($_POST['nazwa']);
					$data = ($_POST['data']);
					$koszt = ($_POST['koszt']);
					$czas = ($_POST['czas']);
					$dystans = ($_POST['km']);
					$start = ($_POST['start']);
					$cel = ($_POST['cel']);
					$img = ($_POST['img']);
					$pilot = $_POST['pilot'];
					$samolot = $_POST['samolot'];
					$godzina_odlotu = $_POST['godzina_odlotu'];
					$nazwa_firmy = $_SESSION['login'];
					$data_teraz = date("Y-m-d");
					$result = false;
					
					$sql_nazwa = "SELECT * FROM loty WHERE nazwa_lotu = '$nazwa'";
					$spr = $dbh->prepare($sql_nazwa);
					$spr->execute();
					$count = $spr->rowCount();
					
					if($data!=null&&isset($typ)&&$koszt!=null&&$czas!=null&&$dystans!=null&&$start!=null&&$cel!=null&&$img!=null){
						$sql = "INSERT INTO `loty` (`nazwa_lotu`, `typ`, `data`, `koszt`, `czas_min`, `ilosc_km`, `start`, 
						`cel`, `img`, `godzina_odlotu`, `przewoziciele_nazwa_firmy`, `samoloty_numer_seryjny`, `piloci_id_pilota`)
								VALUES('".$nazwa."', '".$typ."', '".$data."', $koszt, $czas, $dystans, '".$start."'
								, '".$cel."', '".$img."', $godzina_odlotu, '".$nazwa_firmy."', '".$samolot."', $pilot)";
						
						$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
						if (!$conn) {
							die("Connection failed: " . mysqli_connect_error());
						}
								
						$sql2 = "UPDATE piloci SET ilosc_lotow = ilosc_lotow + 1 WHERE id_pilota = $pilot";
						mysqli_query($conn, $sql2);
						$sql3 = "UPDATE samoloty SET ilosc_lotow = ilosc_lotow + 1 WHERE numer_seryjny = '$samolot'";
						mysqli_query($conn, $sql3);
						
						$ilosc_miejsc = 0;
						$sql3 = "SELECT ilosc_miejsc FROM samoloty WHERE numer_seryjny = '$samolot'";
						foreach($dbh->query($sql3) as $row) {
							$ilosc_miejsc = $row['ilosc_miejsc'];
						}
						$zarobek = $ilosc_miejsc * $koszt;
						$nazwa_firmy = $_SESSION['login'];
						$sql3 = "UPDATE przewoznicy SET budzet = budzet + $zarobek WHERE nazwa_firmy = '$nazwa_firmy' ";
						mysqli_query($conn, $sql3);
						
						mysqli_close($conn);
						
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
												
						if($count > 0) {
							$_SESSION['lot_istnieje'] = true;
							header('refresh: 0');
						}
						else if($start == $cel){
							$_SESSION['start_cel_same'] = true;
							header('refresh: 0');
						}
						else if($data_teraz > $data) {
							$_SESSION['zla_data'] = true;
							header('refresh: 0');
						}
						else if($miasto_cel==$miasto_start){
							$_SESSION['miasta_same'] = true;
							header('refresh: 0');
						}
						else if($start != $cel) {
							$result = $dbh->exec($sql);
							if($result) {
								$_SESSION['add_lot'] = true;
								header('refresh: 0');
							}
						}
					}
					else {
						$_SESSION['nie_wypelniono'] = true;
						header('refresh: 0');
					}
				}
			?>
			
		<?php
		if(isset($_POST['samoloty'])) {
			$_SESSION['flight_add'] = true;
			header('refresh: 0; url="/linie_lot/pages/planes.php"');
		}
		?>
		
		<?php
		if(isset($_POST['piloci'])) {
			$_SESSION['flight_add'] = true;
			header('refresh: 0; url="/linie_lot/pages/pilots.php"');
		}
		?>
		</div>
	</div>
</body>
</html>
<?php
	$dbh = null;
?>
