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
				if(isset($_SESSION['przewoznik'])) {
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/my_account.php'\" value='Panel firmy'/>";
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/planes.php'\" value='Samoloty'/>";
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/pilots.php'\" value='Piloci'/>";
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/corp_flights.php'\" value='Nasze loty'/>";
				}
				else {
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/my_account.php'\" value='Informacje'/>";
					if($_SESSION['login'] != 'admin')
						echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/my_airways.php'\" value='Moje loty'/>";				
					echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/edit_data.php'\" value='Zmien dane'/>";
					if($_SESSION['login'] != 'admin')
						echo "<input class='button' type='button2' onclick= \"location.href='/linie_lot/pages/add_cart.php'\" value='Karta'/>";
				}
			?>

	</div>
	<div id="main">
		<div id="logo">
			<?php
				if(isset($_SESSION['przewoznik'])) {
					echo 
					"<center><a>PANEL PRZEWOŹNIKA</a></center><br>";
					$login = $_SESSION['login'];
					echo 
					"<center><a style='color: red; margin-top: -3%; margin-left: -5%; position: absolute;'>$login</a></center>";
				}
				else {
					echo "<center><a>PANEL UŻYTKOWNIKA</a></center><br>";
					$login = $_SESSION['login'];
					echo "
					<center><a style='color: red; margin-top: -2%; margin-left: -3%; position: absolute;'>$login</a></center>";
				}
			?>
		</div>
		
		<div id="panel">
			<?php
				$login = $_SESSION['login'];
				$sql = "SELECT imie, nazwisko, mail, nr_tel, data_rejestracji, adres FROM uzytkownicy WHERE login = '$login'";
				foreach ($dbh->query($sql) as $row) {
					//w przypadku zalogowania jako przewoznik:
					$imie = $row['imie'];
					$nazwisko = $row['nazwisko'];
					$mail = $row['mail'];
					$nr_tel = $row['nr_tel'];
					$data_rejestracji = $row['data_rejestracji'];
					$adres = $row['adres'];
					
					if(isset($_SESSION['przewoznik'])) {
						$login = $_SESSION['login'];
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
								$_SESSION['czy_zarejestrowana'] = false;
						}//jezeli firma zarejestrowana to wypisuje jej dane
						else {
							$nazwa_firmy = $_SESSION['login'];
							$sql = "SELECT * FROM przewoznicy WHERE nazwa_firmy = '$nazwa_firmy'";
							foreach($dbh->query($sql) as $row) {
								$kraj = $row['kraj'];
								$data_zalozenia = $row['data_zalozenia'];
								$budzet = $row['budzet'];
								$logo = $row['logo'];
						echo "<table class='table_content' style='float: left; margin-top: 2%; margin-left: 0%;'>
										<tr colspan='2'>";
										if(isset($_SESSION['zarejestrowano_corp'])){
											echo "<a class='a4'>Zarejestrowano firmę! Teraz możesz przejmować loty!</a>";	
											unset($_SESSION['zarejestrowano_corp']);
											}									
										echo "</tr>
										<tr>
											<th><a class='a3'>Nazwa firmy: </a></th><th><a class='a2'>$nazwa_firmy</a></th>
											<th rowspan='4' class='table_img'><img src='$logo'></th>
										</tr>
										<tr>
											<th><a class='a3'>Kraj: </a></th><th><a class='a2'>$kraj</a></th>
										</tr>
										<tr>
											<th><a class='a3'>Data założenia: </a></th><th><a class='a2'>$data_zalozenia r.</a></th>
										</tr>										<tr>
											<th><a class='a3'>Budżet: </a></th><th><a class='a2'>$budzet zł</a></th>";
										$sql = "SELECT nr_tel, mail FROM uzytkownicy WHERE login = '$nazwa_firmy'";
										foreach($dbh->query($sql) as $row) {
											$nr_tel = $row['nr_tel'];
											$mail = $row['mail'];
											echo "<tr>
												<th><a class='a3'>Telefon kontaktowy: </a></th><th><a class='a2'>$nr_tel</a></th>
												</tr>
												<tr>
												<th><a class='a3'>Adres e-mail: </a></th><th><a class='a2'>$mail</a></th>";
										}
									echo "</table></center>";
								
								echo "<input class='button_edit' type='button' onclick= \"location.href='/linie_lot/pages/edit_corp.php'\" value='Edytuj dane'/>";
							}
						}
				}
				else {//jezeli zalogowany zwykly uzytkownik lub admin, to wypisuje jego dane
					$login = $_SESSION['login'];
					$sql = "SELECT * FROM uzytkownicy WHERE login = '$login'";
					foreach($dbh->query($sql) as $row) {
						$login = $row['login'];
						$imie = $row['imie'];
						$nazwisko = $row['nazwisko'];
						$adres = $row['adres'];
						$nr_telefonu = $row['nr_tel'];
						$data_rejestracji = $row['data_rejestracji'];
						$prestiz = $row['prestiz'];
						
						echo "<table class='table_content' style='float: left; margin-top: 2%; margin-left: -8%;'>

							<th><a class='a3'>Login:</a></th><th><a class='a2'>$login</a></th>
							</tr>
							<tr>
							<th><a class='a3'>Imię i nazwisko:</a></th><th><a class='a2'>$imie $nazwisko</a></th>
							</tr>
							<tr>
							<th><a class='a3'>Adres:</a></th><th><a class='a2'>$adres</a></th>
							</tr>							<tr>
							<th><a class='a3'>E-mail:</a></th><th><a class='a2'>$mail</a></th>
							</tr>							<tr>
							<th><a class='a3'>Numer telefonu:</a></th><th><a class='a2'>$nr_telefonu</a></th>
							</tr>
							<tr>
							<th><a class='a3'>Data rejestracji:</a></th><th><a class='a2'>$data_rejestracji r.</a></th>
							</tr>							<tr>
							<th><a class='a3'>Prestiż:</a></th><th><a class='a2'>$prestiz</a></th>
							</tr>
							</table>";
					}
				}
			}
			?>
		</div>
	</div>
</body>
</html>
<?php
	$dbh = null;
?>
