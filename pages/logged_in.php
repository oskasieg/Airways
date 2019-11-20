<!-- strona po wcisnieciu przycisku zaloguj -->

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
		<a href="/linie_lot/index.php"><img src="/linie_lot/img/plane.png" ></a></div>
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

	<?php 
		if (isset($_POST['loguj']))
		{
			$login = ($_POST['login']);
			$haslo = md5(($_POST['haslo']));
			$typ = "";
			
			$sql = "SELECT login FROM uzytkownicy WHERE login = '".$login."' AND haslo = '".$haslo."'";
			$rows = $dbh->prepare($sql);
			$rows->execute();
			$rows2 = $rows->rowCount();
					
			// sprawdzamy czy login i hasło są dobre
			if($rows2 == 1)
			{
				$sql = "SELECT typ FROM uzytkownicy WHERE login = '".$login."' AND haslo = '".$haslo."'";
				foreach($dbh->query($sql) as $row) {
					$typ = $row['typ'];
				}
				
				
				if($typ == "oczekuje") {
					
					echo "<center><b>Twoje konto nie zostało jeszcze zweryfikowane!</b></center><br>";
				}
				else {
					$_SESSION['zalogowany'] = true;
					$_SESSION['login'] = $login;
					$_SESSION['haslo'] = $haslo;
	
					$sql = "SELECT typ FROM uzytkownicy WHERE typ = 'przewoznik' AND login='$login'";
					$sql2 = $dbh->query($sql);
					$czy_przewoznik = $sql2->rowCount();
					if($czy_przewoznik == 1){
						$_SESSION['przewoznik'] = true;
					}
					header('refresh: 0.001');
				}
			}
		}
	?>
		
	<div id="img_slider">
		
	</div>
		
	<?php
	$_SESSION['typ'] = null;
		if(isset($_SESSION['zalogowany'])) {
			$login = $_SESSION['login'];
			$wer = "SELECT typ FROM uzytkownicy WHERE login = '$login'";
			foreach($dbh->query($wer) as $row) {
				$_SESSION['typ'] = $row['typ'];
			}
			echo "<center><b>Zalogowano, witaj '".$_SESSION['login']."'!</b></center><br>";
		} 
		else {

				echo "<center><b>Podano złe hasło!</b></center><br>";
		}
			echo "<center><input class='button' type='button' onclick= \"location.href='/linie_lot/index.php'\" value='Strona główna'/></center>";
	?>
</div>

<div id="footer">
<a href="/linie_lot/help.txt" target='_blank' style="font-weight: bold; font-size: 2vh; color: red; margin-top: 1%; margin-right: 1%; float: right;">POMOC</a>
</div>

</body>
</html>
