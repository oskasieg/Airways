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
				$login = $_SESSION['login'];
				$a = array(); // tablica nazw przyciskow do usuwania
				$sql = "SELECT nazwa_firmy, kraj, data_zalozenia, logo, budzet FROM przewoznicy";
				foreach ($dbh->query($sql) as $row) {
					$nazwa_firmy = $row['nazwa_firmy'];
					$kraj = $row['kraj'];
					$data_zalozenia = $row['data_zalozenia'];
					$budzet = $row['budzet'];
					$logo = $row['logo'];
					
					echo "<table class='table_shows'>
							<tr>
								<th>
									<a class='a3'>Nazwa firmy:</a>
								</th>
								<td>
									<a class='a2'>$nazwa_firmy</a>
								</td>
								<td class='table_img' rowspan='4'>
									<img src='$logo'/>
								</td>
								<td rowspan='4'>
									<form method='POST' action='/linie_lot/pages/carriers.php'>
										<input class='button_table' type='submit' value='Zerwij współpracę' name='$nazwa_firmy'/>
									</form>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Kraj:</a>
								</th>
								<td>
									<a class='a2'>$kraj</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Data założenia: </a>
								</th>
								<td>
									<a class='a2'>$data_zalozenia r.</a>
								</td>
							</tr>
							<tr>
								<th>
									<a class='a3'>Budżet: </a>
								</th>
								<td>
									<a class='a2'>$budzet zł</a>
								</td>
							</tr>
						</table>";
						
						array_push($a, $nazwa_firmy);
						
						//formularz, ktory umozliwia odswiezenie strony i wykonanie operacji np usuwania
						$_SESSION['licznik']++;
					}
			?>		
			
			<?php
				for($i = 0; $i < $_SESSION['licznik']; $i++){
					if(isset($_POST[$a[$i]])){
						$conn = mysqli_connect('localhost', 'root', '', 'linie_lotnicze');
						if (!$conn) {
							die("Connection failed: " . mysqli_connect_error());
						}
						
						mysqli_close($conn);
						
						$sql = "select * from uzytkownicy where przewoznicy_nazwa_firmy in (select nazwa_firmy
                                                            from przewoznicy 
                                                            where nazwa_firmy in (select przewoziciele_nazwa_firmy 
                                                                                  from loty))";
                        $sql = "SELECT nazwa_firmy from przewoznicy where nazwa_firmy in (select przewoziciele_nazwa_firmy
                                                          from loty) and nazwa_firmy = 'lot';";
                        $spr = $dbh->prepare($sql);
                        $spr->execute();
                        $count = $spr->rowCount();
						if($count == 0) {
							$sql = "DELETE FROM uzytkownicy WHERE login = '$a[$i]'";
							$result = $dbh->exec($sql);
							$sql = "DELETE FROM samoloty WHERE pzewoznicy_nazwa_firmy = '$a[$i]'";
							$result = $dbh->exec($sql);
							$sql = "DELETE FROM piloci WHERE przewoznicy_nazwa_firmy = '$a[$i]'";
							$result = $dbh->exec($sql);
							$sql = "DELETE FROM przewoznicy WHERE nazwa_firmy = '$a[$i]'";
							$result = $dbh->exec($sql);
							if($result)
								header('refresh: 0');
							echo "<script>
								window.alert('Zerwano współpracę z : $a[$i]!');
							</script>";
						}
						else {
							echo "<script>
								window.alert('Przewoźnik $a[$i] przejął już jakiś lot! Nie można rozwiązać kontraktu!');
							</script>";
						}
					}
				}
			?>
		</div>
	</div>
	<?php
		echo "<input class='button_add' type='button2' onclick= \"location.href='/linie_lot/pages/carrier_add.php'\" value='Dodaj przewoźnika'/>";
	?>
</body>

<?php
	$dbh = null;
?>

</html>

