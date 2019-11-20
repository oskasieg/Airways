
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
		</div>
		
		<div id="panel">
			<?php
				$login = $_SESSION['login'];
				$sql = "SELECT imie, nazwisko, mail, nr_tel, data_rejestracji, adres FROM uzytkownicy WHERE login = '$login'";
				foreach ($dbh->query($sql) as $row) {
					$imie = $row['imie'];
					$nazwisko = $row['nazwisko'];
					$mail = $row['mail'];
					$nr_tel = $row['nr_tel'];
					$data_rejestracji = $row['data_rejestracji'];
					$adres = $row['adres'];
					
					echo "<table>
							<tr>
								<th>
									<a class='a3'>Login: </a>
								</th>
								<th>
									<a class='a2'>$login</a>
								</th>
							</tr>
							<tr>
								<th>
									<a class='a3'>Imię i nazwisko:</a>
								</th>
								<th>
									<a class='a2'>$imie $nazwisko</a>
								</th>
							</tr>
							<tr>
								<th>
									<a class='a3'>Mail: </a>
								</th>
								<th>
									<a class='a2'>$mail</a>
								</th>
							</tr>
							<tr>
								<th>
									<a class='a3'>Numer telefonu: </a>
								</th>
								<th>
									<a class='a2'>$nr_tel</a>
								</th>
							</tr>
							<tr>
								<th>
									<a class='a3'>Adres: </a>
								</th>
								<th>
									<a class='a2'>$adres</a>
								</th>
							</tr>
							<tr>
								<th>
									<a class='a3'>Data rejestracji: </a>
								</th>
								<th>
									<a class='a2'>$data_rejestracji</a>
								</th>
							</tr>
						</table>";
				}
			?>
		</div>
	</div>
</body>
</html>
<?php
	$dbh = null;
?>
