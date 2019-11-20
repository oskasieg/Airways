<?php
	include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');
?>

<html>
<head>
<meta charset="utf-8">
<title>Panel admin</title>
<link rel="stylesheet" text="text/css" href="/linie_lot/styles/style2.css">
</head>

<body>
	<?php		
		$nazwa_edit = substr($_SESSION['airway_edit'], 0, -1);

		echo "<input class='button2' type='button' onclick= \"location.href='/linie_lot/index.php'\" value='<< Powrót'/>";
		echo "<center><a style='font-size: 5vh; margin-top: 2%; color: red; font-weight: bold'> Edytor lotu: $nazwa_edit</a></center>";
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
			<center><a>PANEL ADMINISTRATORA</a></center>
		</div>
		
		<div id="panel">
			<form method="POST" action="/linie_lot/pages/airway_edit.php">
					<center>
						<table class="table_edit">
							<tr>
								<th colspan='4'><?php if(isset($_SESSION['edit_lot'])) echo "<a class='a4'>Zatwierdzono zmiany!</a></th>"; $_SESSION['edit_lot'] = null;?></th>
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
								<th><a class="a3">Start:</a></th><th><input class="textarea" type="text" name="start"></th>
							</tr>	
							<tr>
								<th><a class="a3">Cel:</a></th><th><input class="textarea" type="text" name="cel"></th>
							</tr>
							<tr>
								<th><a class="a3">Obrazek (url):</a></th><th><input class="textarea" type="text" name="img"></th>						
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
									<input class="button3" type="submit" value="Zatwierdź" name="zatwierdz">
									<input class="button3" type="reset" value="Zresetuj" name="reset">
								</th>
							</tr>
						</table>
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
				if(isset($_POST['zatwierdz'])){
					if(isset($_POST['typ']))
						$typ = $_POST['typ'];
					$data = ($_POST['data']);
					$koszt = ($_POST['koszt']);
					$czas = ($_POST['czas']);
					$dystans = ($_POST['km']);
					$start = ($_POST['start']);
					$cel = ($_POST['cel']);
					$obrazek = ($_POST['img']);
					$licznik_zmian = 0;
					$pilot = $_POST['pilot'];
					$samolot = $_POST['samolot'];
					
					$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
					if (!$conn) {
						die("Connection failed: " . mysqli_connect_error());
					}
					
					if($data != null){
						 $sql = "UPDATE loty SET data = '$data' WHERE nazwa_lotu = '$nazwa_edit'";
						 mysqli_query($conn, $sql);	
						 $licznik_zmian++;
					}
					
					if($koszt != null){
						 $sql = "UPDATE loty SET koszt = $koszt WHERE nazwa_lotu = '$nazwa_edit'";
						 mysqli_query($conn, $sql);	
						 $licznik_zmian++;
					}	
					if($czas != null){
						 $sql = "UPDATE loty SET czas_min = $czas WHERE nazwa_lotu = '$nazwa_edit'";
						 mysqli_query($conn, $sql);	
						 $licznik_zmian++;
					}
					
					if($dystans != null){
						 $sql = "UPDATE loty SET ilosc_km = $dystans WHERE nazwa_lotu = '$nazwa_edit'";
						 mysqli_query($conn, $sql);	
						 $licznik_zmian++;
					}	
					if($start != null){
						 $sql = "UPDATE loty SET start = '$start' WHERE nazwa_lotu = '$nazwa_edit'";
						 mysqli_query($conn, $sql);	
						 $licznik_zmian++;
					}
					
					if($cel != null){
						 $sql = "UPDATE loty SET cel = '$cel' WHERE nazwa_lotu = '$nazwa_edit'";
						 mysqli_query($conn, $sql);	
						$licznik_zmian++;
					}
					if($obrazek != null){
						 $sql = "UPDATE loty SET img = '$obrazek' WHERE nazwa_lotu = '$nazwa_edit'";
						 mysqli_query($conn, $sql);	
						 $licznik_zmian++;
					}
					
					if($pilot != null){
						 $sql = "UPDATE loty SET piloci_id_pilota = $pilot WHERE nazwa_lotu = '$nazwa_edit'";
						 mysqli_query($conn, $sql);	
						 $licznik_zmian++;
					}	
				
					if($samolot != null){
						 $sql = "UPDATE loty SET samoloty_numer_seryjny = '$samolot' WHERE nazwa_lotu = '$nazwa_edit'";
						 mysqli_query($conn, $sql);	
						 $licznik_zmian++;
					}			
					
					if($licznik_zmian>=2){
						$_SESSION['edit_lot'] = true;
						header('refresh: 0');
					}
					mysqli_close($conn);
				}
			?>
			
		<?php
		if(isset($_POST['samoloty'])) {
			$_SESSION['flight_edit'] = true;
			header('refresh: 0; url="/linie_lot/pages/planes.php"');
		}
		?>
		
		<?php
		if(isset($_POST['piloci'])) {
			$_SESSION['flight_edit'] = true;
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
