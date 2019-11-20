<?php
include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');
$_SESSION['start'] = "";
$_SESSION['cel'] = "";;
$_SESSION['data'] = "1997-04-22";
$_SESSION['data2'] = "2025-04-22";
$_SESSION['przewoznik_filtr'] = "";
$_SESSION['max_cena'] = 99999;
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
	<div id="gallery">
		<div class="g1">
			
		<?php
		$sql = "SELECT * FROM loty";
		foreach($dbh->query($sql) as $row) {
			$data = $row['data'];
			$godzina_odlotu = $row['godzina_odlotu'];
			$czas_min = $row['czas_min'];
			$data = $data.' '.$godzina_odlotu.':00';
			if($data < date("Y-m-d H:i")) {
				//usuwanie lotu 3 dni po
			}
		}
		?>
		
		<?php
			$sql = "SELECT * FROM opinie";
			$spr = $dbh->prepare($sql);
			$spr->execute();
			$count = $spr->rowCount();
			$min = 1;
			$max = $count;
			$rand = rand($min, $max);
			$sql = "SELECT * FROM opinie WHERE id = $rand";
			foreach($dbh->query($sql) as $row) {
				$nazwa_lotu = $row['loty_nazwa_lotu'];
				$ocena = $row['ocena'];
				$tresc = $row['tresc'];
				$sql = "SELECT imie, nazwisko FROM uzytkownicy WHERE login IN (SELECT uzytkownicy_login 
									FROM opinie WHERE id = $rand)";
				$imie = '';
				$nazwisko = '';
				foreach($dbh->query($sql) as $row) {
					$imie = $row['imie'];
					$nazwisko = $row['nazwisko'];
				}
				
				echo "
				<a style='float: left;'>Losowa opinia:</a>
				<table class='table_opinion'>
				<tr><th colspan='2'><a class='a4'>Nazwa lotu:</a><a style='color: blue; font-size: 3vh;'>$nazwa_lotu</a></th>
				<tr><th colspan='2'><a class='a4'>Ocena:</a> $ocena/5</th></tr><br>
				<tr><th colspan='2'><a class='a4'>Autor:</a> $imie $nazwisko</a></th>
				<tr><th colspan='2'><a class='a4'>Treść:</a></th></tr>
				<tr><td colspan='2'>$tresc</td></tr>

				</table>";
			}
		?>

		</div>
		<div class="g2">
			<img class="clients" src="/linie_lot/img/happy_clients.jpg"/> 
		</div>
	</div>
</div>

<div id="footer">
<a target='_blank' href="/linie_lot/help.txt" style="font-weight: bold; font-size: 2vh; color: red; margin-top: 1%; margin-right: 1%; float: right;">POMOC</a>
</div>

</body>
</html>
