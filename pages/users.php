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
		if(isset($_SESSION['flight']))
			echo "<input class='button2' type='button' onclick= \"location.href='/linie_lot/pages/flight.php'\" value='<< Powrót'/>";
		else
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
				//$login = $_SESSION['login'];
				$a = array(); // tablica nazwa do odrzucania
				$a2 = array(); // tablica nazwa do akceptowania

				$sql = "SELECT * FROM uzytkownicy WHERE typ = 'oczekuje'";
					
				foreach ($dbh->query($sql) as $row) {
					$login = $row['login'];
					$login_accept = $login."a";
					$haslo = $row['haslo'];
					$pytanie_pom = $row['pytanie_pom'];
					$imie = $row['imie'];
					$nazwisko = $row['nazwisko'];
					$adres = $row['adres'];
					$nr_tel = $row['nr_tel'];
					$mail = $row['mail'];
					
					echo "<table class='table_shows'>
							<tr>
								<th>
									<a class='a3'>Login:</a>
								</th>
								<td>
									<a class='a2'>$login</a>
								</td>
								<td class='table_img' rowspan='7'>
									<center><img src='/linie_lot/img/user.jpg'/></center>
								</td>
								<td rowspan='7'>";
								$sql = "SELECT typ FROM uzytkownicy WHERE login = '$login'";
								$czy_oczekuje = false;
								foreach($dbh->query($sql) as $row) {
									if($row['typ'] == "oczekuje") $czy_oczekuje = true;
								}
								if($czy_oczekuje) {
									echo "<form method='POST' action='/linie_lot/pages/users.php'>
											<input class='button_table2' type='submit' value='Zatwierdź konto' name='$login_accept'/>
											<input class='button_table3' type='submit' value='Odrzuć konto' name='$login'/>
										</form>";
								}
					echo "		</td>
							</tr>
							</tr>
							<tr>
								<th>
									<a class='a3'>Pytanie pomocnicze: </a>
								</th>
								<td>
									<a class='a2'>$pytanie_pom</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Imię: </a>
								</th>
								<td>
									<a class='a2'>$imie</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Nazwisko: </a>
								</th>
								<td>
									<a class='a2'>$nazwisko</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Adres: </a>
								</th>
								<td>
									<a class='a2'>$adres</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Nr tel: </a>
								</th>
								<td>
									<a class='a2'>$nr_tel</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>E-mail: </a>
								</th>
								<td>
									<a class='a2'>$mail</a>
								</td>
							</tr>
						</table>";
						
						array_push($a, $login_accept);
						array_push($a2, $login);
						
						//formularz, ktory umozliwia odswiezenie strony i wykonanie operacji np usuwania
						$_SESSION['licznik']++;
				}
						
				$sql = "SELECT * FROM uzytkownicy WHERE typ != 'oczekuje'";
					
				foreach ($dbh->query($sql) as $row) {
					$login = $row['login'];
					$login_accept = $login."a";
					$haslo = $row['haslo'];
					$pytanie_pom = $row['pytanie_pom'];
					$imie = $row['imie'];
					$nazwisko = $row['nazwisko'];
					$adres = $row['adres'];
					$nr_tel = $row['nr_tel'];
					$mail = $row['mail'];
					
					echo "<table class='table_shows'>
							<tr>
								<th>
									<a class='a3'>Login:</a>
								</th>
								<td>
									<a class='a2'>$login</a>
								</td>
								<td class='table_img' rowspan='5'>
									<center><img src='/linie_lot/img/user.jpg'/></center>
								</td>
								<td rowspan='8'>";
								$sql = "SELECT typ FROM uzytkownicy WHERE login = '$login'";
								$czy_oczekuje = false;
								foreach($dbh->query($sql) as $row) {
									if($row['typ'] == "oczekuje") $czy_oczekuje = true;
								}
								if($czy_oczekuje) {
									echo "<form method='POST' action='/linie_lot/pages/users.php'>
											<input class='button_table2' type='submit' value='Zatwierdź konto' name='$login_accept'/>
											<input class='button_table3' type='submit' value='Odrzuć konto' name='$login'/>
										</form>";
								}
					echo "		</td>
							</tr>
							</tr>
							<tr>
								<th>
									<a class='a3'>Pytanie pomocnicze: </a>
								</th>
								<td>
									<a class='a2'>$pytanie_pom</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Imię: </a>
								</th>
								<td>
									<a class='a2'>$imie</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Nazwisko: </a>
								</th>
								<td>
									<a class='a2'>$nazwisko</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Adres: </a>
								</th>
								<td>
									<a class='a2'>$adres</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Nr tel: </a>
								</th>
								<td>
									<a class='a2'>$nr_tel</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>E-mail: </a>
								</th>
								<td>
									<a class='a2'>$mail</a>
								</td>
							</tr>
						</table>";
					}
			?>		
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a[$i]])){
						$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
						if (!$conn) {
							die("Connection failed: " . mysqli_connect_error());
						}
						$login = substr($a[$i], 0, -1);
						$sql = "UPDATE uzytkownicy SET typ = 'klient' WHERE login = '$login'";
						mysqli_query($conn, $sql);
						
						echo 
						"<script>
							window.alert('Zatwierdzono konto: $login!');
						</script>";
						header("refresh: 0");
						
						/*$sql = "SELECT * FROM piloci WHERE id_pilota = $a[$i] AND id_pilota IN (SELECT piloci_id_pilota
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
						}*/
					}
				}
			?>
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a2[$i]])){
							$sql = "DELETE FROM uzytkownicy WHERE login = '$a2[$i]'";
							$result = $dbh->exec($sql);
							if($result)
								header('refresh: 0');
								
							echo "<script>
									window.alert('Odrzucono konto: $a2[$i]!');
								</script>";
					}
				}
			?>
			
		</div>
	</div>
	<?php
		//echo "<input class='button_add' type='button2' onclick= \"location.href='/linie_lot/pages/add_pilot.php'\" value='Zatrudnij pilota'/>";
	?>
</body>

<?php
	$dbh = null;
?>

</html>

