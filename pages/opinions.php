<?php
include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');
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
		<?php
		$sql = "SELECT * FROM loty";
		foreach($dbh->query($sql) as $row) {
			$data = $row['data'];
			$godzina_odlotu = $row['godzina_odlotu'];
			$czas_min = $row['czas_min'];
			$data = $data.' '.$godzina_odlotu;
			echo $data;
		}
		?>
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
	<div class='opinions'>
		<?php
			$_SESSION['opinion'] = true;
			$sql = "SELECT * FROM opinie";
			foreach($dbh->query($sql) as $row) {
				$nazwa_lotu = $row['loty_nazwa_lotu'];
				$ocena = $row['ocena'];
				$tresc = $row['tresc'];
				$id = $row['id'];
				$sql = "SELECT imie, nazwisko FROM uzytkownicy WHERE login IN (SELECT uzytkownicy_login 
									FROM opinie WHERE id = $id)";
				$imie = '';
				$nazwisko = '';
				foreach($dbh->query($sql) as $row) {
					$imie = $row['imie'];
					$nazwisko = $row['nazwisko'];
				}
				
				echo "
				<table class='table_opinions'>
				<tr><th rowspan='2' >
				$nazwa_lotu</th>
				<td>Ocena: $ocena/5</td>
				<td rowspan='2' class='tresc'>$tresc</td>
				</tr>
				<tr>
				<td>$imie $nazwisko</td>
				</tr>
				</table>";
			}
		?>
	</div>
</div>

<div id="footer">
<a href="/linie_lot/pages/help.php" style="font-weight: bold; font-size: 2vh; color: red; margin-top: 1%; margin-right: 1%; float: right;">POMOC</a>
</div>

</body>
</html>
