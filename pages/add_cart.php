<!--1) klucz glowny w karty klientow musi byc auto increment
	2) jeżeli dodajemy karte to wtedy jej numer = karty_klientow_numer (naprawic UPDATE!)
-->

<?php
include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');
?>

<html>
<head>
<meta charset="utf-8">
<title>Moje konto</title>
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
				$login = $_SESSION['login'];
			
				$sql = "SELECT karty_klientow_numer FROM uzytkownicy WHERE login = '$login'";
				
				foreach($dbh->query($sql) as $row){
					$karta = $row['karty_klientow_numer'];
				}
				
				if($karta == null){
					echo 
					"<center>
					<a class='a5'><br><br>Nie dodano jeszcze karty klienta. Aby to zrobić kliknij w poniższy przycisk.</a>
					<form action='/linie_lot/pages/add_cart.php' method='post'>
					<br><br><input type='submit' value='Dodaj kartę' name='add_cart' class='button3'>
					</form>
					</center>";
					
					if(isset($_POST['add_cart'])) {
						$waznosc = date("Y-m-d", strtotime('+1 year'));					
						$data = date("Y-m-d");
						$sql = "INSERT INTO karty_klientow (stan_konta, data_waznosci, ilosc_transakcji, data_transakcji) VALUES
						(0, '".$waznosc."', 0, '".$data."')";
						
						try {
							$result = $dbh->exec($sql);
						}
						catch(PDOException $e){
							echo "blad";
						}
						
						if($result)
							echo "<center><a class='a4'>Karta została dodana!</a></center>";
						
						$sql = "SELECT numer FROM karty_klientow WHERE data_waznosci = '$waznosc'";
						foreach($dbh->query($sql) as $row){
							$numer = $row['numer'];
						}
						
						$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
						if (!$conn) {
							die("Connection failed: " . mysqli_connect_error());
						}
						
						$sql = "UPDATE uzytkownicy SET karty_klientow_numer = $numer WHERE login = '$login'";
						mysqli_query($conn, $sql);
						
						mysqli_close($conn);
						
						header('refresh: 2; url="/linie_lot/pages/add_cart.php"');
					}
					
				}
				else{
					echo "<center><a class='a4'>Karta została już dodana</a></center>";
					$sql = "SELECT numer, stan_konta, data_waznosci, ilosc_transakcji, data_transakcji FROM karty_klientow WHERE numer 
					IN(SELECT karty_klientow_numer from uzytkownicy where login = '$login')";
										
					foreach($dbh->query($sql) as $row){
						$numer = $row['numer'];
						$stan = $row['stan_konta'];
						$data = $row['data_waznosci'];
						$ilosc = $row['ilosc_transakcji'];
						$data_transakcji = $row['data_transakcji'];
						echo "<table>
							<tr>
								<th>
									<a class='a3'>Numer karty: </a>
								</th>
								<th>
									<a class='a2'>$numer</a>
								</th>
							</tr>
							<tr>
								<th>
									<a class='a3'>Stan konta:</a>
								</th>
								<th>
									<a class='a2'>$stan</a>
								</th>
							</tr>
							<tr>
								<th>
									<a class='a3'>Data ważności: </a>
								</th>
								<th>
									<a class='a2'>$data</a>
								</th>
							</tr>
							<tr>
								<th>
									<a class='a3'>Ilość transakcji: </a>
								</th>
								<th>
									<a class='a2'>$ilosc</a>
								</th>
							</tr>
							<tr>
								<th>
									<a class='a3'>Data ostatniej transakcji: </a>
								</th>
								<th>
									<a class='a2'>$data_transakcji</a>
								</th>
							</tr>
						</table>";
						
					}
					echo "
					<form action='/linie_lot/pages/add_cart.php' method='post'>
					<input type='submit' name='add50' class='button3' value='Doładuj za 50' style='float: right; margin-top: -13%; margin-right: 20%; width: 15%;'>
					<input type='submit' name='add100' class='button3' value='Doładuj za 100' style='float: right; margin-top: -8%; margin-right: 20%; width: 15%;'>
					<input type='submit' name='add200' class='button3' value='Doładuj za 200' style='float: right; margin-top: -3%; margin-right: 20%; width: 15%;'>
					</form>";
				}
			?>
			
			<?php
				$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
				if (!$conn) {
					die("Connection failed: " . mysqli_connect_error());
				}
				
				if(isset($_POST['add50'])){
					$login = $_SESSION['login'];
					$sql = "UPDATE karty_klientow SET stan_konta = stan_konta + 50 WHERE numer IN (SELECT karty_klientow_numer
																FROM uzytkownicy WHERE login = '$login');";
					$result = mysqli_query($conn, $sql);
					
					if($result) {
						echo "<script>
							window.alert('Doładowano konto za 50zł Miłego podróżowania!');
						</script>";
						header('refresh: 0');
					}
				}
				
				if(isset($_POST['add100'])){
					$login = $_SESSION['login'];
					$sql = "UPDATE karty_klientow SET stan_konta = stan_konta + 100 WHERE numer IN (SELECT karty_klientow_numer
																FROM uzytkownicy WHERE login = '$login');";
					$result = mysqli_query($conn, $sql);
					
					if($result) {
						echo "<script>
							window.alert('Doładowano konto za 100zł Miłego podróżowania!');
						</script>";
						header('refresh: 0');
					}
				}

				if(isset($_POST['add200'])){
					$login = $_SESSION['login'];
					$sql = "UPDATE karty_klientow SET stan_konta = stan_konta + 200 WHERE numer IN (SELECT karty_klientow_numer
																FROM uzytkownicy WHERE login = '$login');";
					$result = mysqli_query($conn, $sql);
					
					if($result) {
						echo "<script>
							window.alert('Doładowano konto za 200zł Miłego podróżowania!');
						</script>";
						header('refresh: 0');
					}
				}
				mysqli_close($conn);
			?>
		</div>
	</div>
</body>
</html>
<?php
	$dbh = null;
?>
