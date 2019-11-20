<!-- strona po wcisnieciu zarejestruj -->
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
			include("/opt/lampp/htdocs/linie_lot/scripts/login.html");
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

<div class="form_rej">
<?php
	include("/opt/lampp/htdocs/linie_lot/scripts/rejestration.php");
?>
</div>

</div>

<div id="footer">

</div>

</body>
</html>
