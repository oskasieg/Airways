<?php
include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');
ob_start();
$_SESSION['licznik'] = 0;
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
	<div class='content'>
			<center>
			<a style='font-size: 5vh; color: navy; text-shadow: 3px 3px 3px gray'>Projekt nie został uzupełniony w </a>
			<a style='font-size: 5vh; font-weight: bold; color: blue; text-shadow: 3px 3px 3px gray'>obsługę hoteli</a>	
			<a style='font-size: 5vh; font-weight: bold; color: navy; text-shadow: 3px 3px 3px gray'>nastąpi to w</a>	
			<a style='font-size: 5vh; font-weight: bold; color: blue; text-shadow: 3px 3px 3px gray'>przyszłości</a>	
			</center>
	</div>
	</div>
<div id="footer">
<a href="/linie_lot/pages/help.php" style="font-weight: bold; font-size: 2vh; color: red; margin-top: 1%; margin-left: 1%; float: left;">POMOC</a>
</div>

</body>
</html>
