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
	<?php
	include('/opt/lampp/htdocs/linie_lot/scripts/filter.php');
	?>
	<div class="content">
		<?php
		$a = array();
		
		$start = "";
		$cel = "";
		$przewoznik = "";
		$data = "1997-04-22";
		$data2 = "2025-04-22";
		$max_cena = 99999;
		
		if(isset($_SESSION['start'])) $start = $_SESSION['start'];
		if(isset($_SESSION['cel'])) $cel = $_SESSION['cel'];
		if(isset($_SESSION['data'])) $data = $_SESSION['data'];
		if(isset($_SESSION['data2'])) $data2 = $_SESSION['data2'];
		if(isset($_SESSION['przewoznik_filtr'])) $przewoznik = $_SESSION['przewoznik_filtr'];
		if(isset($_SESSION['max_cena'])) $max_cena = $_SESSION['max_cena'];
		
		if(isset($_POST['wszystkie'])) 
			$_SESSION['wszystkie'] = true;
		else if(isset($_POST['standard'])) {
			$_SESSION['standard'] = true;
			$_SESSION['wszystkie'] = null;
			$_SESSION['VIP'] = null;
			$_SESSION['okazje'] = null;
		}
		else if(isset($_POST['okazje'])) {
			$_SESSION['standard'] = null;
			$_SESSION['wszystkie'] = null;
			$_SESSION['VIP'] = null;
			$_SESSION['okazje'] = true;
		}
		else if(isset($_POST['VIP'])) {
			$_SESSION['standard'] = null;
			$_SESSION['wszystkie'] = null;
			$_SESSION['VIP'] = true;
			$_SESSION['okazje'] = null;
		}
		
		if(isset($_SESSION['zalogowany'])){		
		if(isset($_SESSION['wszystkie'])) {
			if(isset($_SESSION['typ'])) {
				if($_SESSION['typ'] == 'przewoznik') {
					$data_teraz = date("Y-m-d");
					$a = array();
					$_SESSION['licznik'] = 0;
					$sql = "SELECT * FROM loty WHERE
						data BETWEEN '$data' AND '$data2'
						AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena
						AND typ NOT LIKE '%oczekuje' AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
															     miasto LIKE '$cel%')
										   ORDER BY data DESC";
					foreach($dbh->query($sql) as $row) {
						$nazwa_lotu = $row['nazwa_lotu'];
						$img = $row['img'];
						$data = $row['data'];
						
						if($data_teraz <= $data) {
							echo "<table class='table_shows'>
							<tr>
							<th class='naglowek'><a>$nazwa_lotu</a></th>
							</tr>
							<tr>
							<th class='odsylacz'>
							<form method='POST' action='/linie_lot/pages/buy_airways.php'>
							<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
							</form>
							</th>
							</tr>
							</table>";
							array_push($a, $nazwa_lotu);
							$_SESSION['licznik']++;
						}
					}
				}
				else if($_SESSION['typ'] == 'admin') {
					$data_teraz = date("Y-m-d");
					$sql = "SELECT * FROM loty WHERE
						data BETWEEN '$data' AND '$data2'
						AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena
						AND start IN (SELECT id_lotniska FROM destynacje WHERE
									  miasto LIKE '$start%')
						AND cel IN (SELECT id_lotniska FROM destynacje WHERE
									miasto LIKE '$cel%')
								ORDER by data";
					foreach($dbh->query($sql) as $row) {
						$nazwa_lotu = $row['nazwa_lotu'];
						$img = $row['img'];
						$data = $row['data'];
						
						if($data_teraz < $data) {
							echo "<table class='table_shows'>
							<tr>
							<th class='naglowek'><a>$nazwa_lotu</a></th>
							</tr>
							<tr>
							<th class='odsylacz'>
							<form method='POST' action='/linie_lot/pages/buy_airways.php'>
							<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
							</form>
							</th>
							</tr>
							</table>";
							array_push($a, $nazwa_lotu);
							$_SESSION['licznik']++;
						}
					}
				}
				else if($_SESSION['typ'] == 'klient') {
					$data_teraz = date("Y-m-d");
					$login = $_SESSION['login'];
                    $sql = "select * from loty where nazwa_lotu not in (select loty_nazwa_lotu 
                                                     from uzytkownicy_has_loty where uzytkownicy_login IN (select login from uzytkownicy
                                                                                                           where login = '$login'))
                   AND przewoziciele_nazwa_firmy is NOT NULL
                   AND data BETWEEN '$data' AND '$data2'
                   AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena
				   AND typ NOT LIKE '%oczekuje'  
				   AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
															     miasto LIKE '$cel%')
				   ORDER BY data DESC";                                                                                                                                                                          
                        
						foreach($dbh->query($sql) as $row) {
							$nazwa_lotu = $row['nazwa_lotu'];
							$img = $row['img'];
							$data = $row['data'];
							
							if($data_teraz <= $data) {
								echo "<table class='table_shows'>
								<tr>
								<th class='naglowek'><a>$nazwa_lotu</a></th>
								</tr>
								<tr>
								<th class='odsylacz'>
								<form method='POST' action='/linie_lot/pages/buy_airways.php'>
								<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
								</form>
								</th>
								</tr>
								</table>";
								array_push($a, $nazwa_lotu);
								$_SESSION['licznik']++;
							}
						}
				}
			}
		}
		else if(isset($_SESSION['standard'])) {
			if(isset($_SESSION['typ'])) {
				if($_SESSION['typ'] == 'przewoznik') {
										$data_teraz = date("Y-m-d");
					$a = array();
					$_SESSION['licznik'] = 0;
					$sql = "SELECT * FROM loty WHERE przewoziciele_nazwa_firmy IS NOT NULL AND typ = 'Standardowy'
					AND data BETWEEN '$data' AND '$data2'
                   AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena 
                   				   AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
															     miasto LIKE '$cel%')
                   ORDER BY data DESC";
					foreach($dbh->query($sql) as $row) {
						$nazwa_lotu = $row['nazwa_lotu'];
						$img = $row['img'];
						$data = $row['data'];
						
						if($data_teraz <= $data) {
							echo "<table class='table_shows'>
							<tr>
							<th class='naglowek'><a>$nazwa_lotu</a></th>
							</tr>
							<tr>
							<th class='odsylacz'>
							<form method='POST' action='/linie_lot/pages/buy_airways.php'>
							<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
							</form>
							</th>
							</tr>
							</table>";
							array_push($a, $nazwa_lotu);
							$_SESSION['licznik']++;
						}
					}
				}
				else if($_SESSION['typ'] == 'admin') {
					$data_teraz = date("Y-m-d");
					$sql = "SELECT * FROM loty WHERE typ = 'Standardowy'
                   AND data BETWEEN '$data' AND '$data2'
                   AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena 
                   				   AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
												     miasto LIKE '$cel%')
                   ORDER BY data DESC";
					foreach($dbh->query($sql) as $row) {
						$nazwa_lotu = $row['nazwa_lotu'];
						$img = $row['img'];
						$data = $row['data'];
						
						if($data_teraz <= $data) {
							echo "<table class='table_shows'>
							<tr>
							<th class='naglowek'><a>$nazwa_lotu</a></th>
							</tr>
							<tr>
							<th class='odsylacz'>
							<form method='POST' action='/linie_lot/pages/buy_airways.php'>
							<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
							</form>
							</th>
							</tr>
							</table>";
							array_push($a, $nazwa_lotu);
							$_SESSION['licznik']++;
						}
					}
				}
				else if($_SESSION['typ'] == 'klient') {
					$data_teraz = date("Y-m-d");
					$login = $_SESSION['login'];
                    $sql = "select * from loty where nazwa_lotu not in (select loty_nazwa_lotu 
                                                     from uzytkownicy_has_loty where uzytkownicy_login IN (select login from uzytkownicy
                                                                                                           where login = '$login'))
                                                                                           AND przewoziciele_nazwa_firmy is NOT NULL
                                                                                           AND typ = 'Standardowy'
                   AND data BETWEEN '$data' AND '$data2'
                   AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena 
                   				   AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
															     miasto LIKE '$cel%')
                   ORDER BY data DESC";                                                                                                                                                                        
                        
						foreach($dbh->query($sql) as $row) {
							$nazwa_lotu = $row['nazwa_lotu'];
							$img = $row['img'];
							$data = $row['data'];
							
							if($data_teraz <= $data) {
								echo "<table class='table_shows'>
								<tr>
								<th class='naglowek'><a>$nazwa_lotu</a></th>
								</tr>
								<tr>
								<th class='odsylacz'>
								<form method='POST' action='/linie_lot/pages/buy_airways.php'>
								<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
								</form>
								</th>
								</tr>
								</table>";
								array_push($a, $nazwa_lotu);
								$_SESSION['licznik']++;
							}
						}
				}
			}
		}
		else if(isset($_SESSION['okazje'])) {
			if(isset($_SESSION['typ'])) {
				if($_SESSION['typ'] == 'przewoznik') {
					$data_teraz = date("Y-m-d");
					$a = array();
					$_SESSION['licznik'] = 0;
					$sql = "SELECT * FROM loty WHERE przewoziciele_nazwa_firmy IS NOT NULL AND typ = 'Promocja'
                   AND data BETWEEN '$data' AND '$data2'
                   AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena 
                   				   AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
															     miasto LIKE '$cel%')
                   ORDER BY data DESC";
					foreach($dbh->query($sql) as $row) {
						$nazwa_lotu = $row['nazwa_lotu'];
						$img = $row['img'];
						$data = $row['data'];
						
						if($data_teraz <= $data) {
							echo "<table class='table_shows'>
							<tr>
							<th class='naglowek'><a>$nazwa_lotu</a></th>
							</tr>
							<tr>
							<th class='odsylacz'>
							<form method='POST' action='/linie_lot/pages/buy_airways.php'>
							<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
							</form>
							</th>
							</tr>
							</table>";
							array_push($a, $nazwa_lotu);
							$_SESSION['licznik']++;
						}
					}
				}
				else if($_SESSION['typ'] == 'admin') {
					$data_teraz = date("Y-m-d");
					$sql = "SELECT * FROM loty WHERE typ = 'Promocja'
                  AND data BETWEEN '$data' AND '$data2'
                   AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena 
                   				   AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
															     miasto LIKE '$cel%')
                   ORDER BY data DESC";
					foreach($dbh->query($sql) as $row) {
						$nazwa_lotu = $row['nazwa_lotu'];
						$img = $row['img'];
						$data = $row['data'];
						
						if($data_teraz <= $data) {
							echo "<table class='table_shows'>
							<tr>
							<th class='naglowek'><a>$nazwa_lotu</a></th>
							</tr>
							<tr>
							<th class='odsylacz'>
							<form method='POST' action='/linie_lot/pages/buy_airways.php'>
							<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
							</form>
							</th>
							</tr>
							</table>";
							array_push($a, $nazwa_lotu);
							$_SESSION['licznik']++;
						}
					}
				}
				else if($_SESSION['typ'] == 'klient') {
					$data_teraz = date("Y-m-d");
					$login = $_SESSION['login'];
                    $sql = "select * from loty where nazwa_lotu not in (select loty_nazwa_lotu 
                                                     from uzytkownicy_has_loty where uzytkownicy_login IN (select login from uzytkownicy
                                                                                                           where login = '$login'))
                                                                                           AND przewoziciele_nazwa_firmy is NOT NULL
                                                                                           AND typ = 'Promocja'
                    AND data BETWEEN '$data' AND '$data2'
                   AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena 
                   				   AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
															     miasto LIKE '$cel%')
                   ORDER BY data DESC";                                                                                                                                                                                                                                                                     
                        
						foreach($dbh->query($sql) as $row) {
							$nazwa_lotu = $row['nazwa_lotu'];
							$img = $row['img'];
							$data = $row['data'];
							
							if($data_teraz <= $data) {
								echo "<table class='table_shows'>
								<tr>
								<th class='naglowek'><a>$nazwa_lotu</a></th>
								</tr>
								<tr>
								<th class='odsylacz'>
								<form method='POST' action='/linie_lot/pages/buy_airways.php'>
								<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
								</form>
								</th>
								</tr>
								</table>";
								array_push($a, $nazwa_lotu);
								$_SESSION['licznik']++;
							}
						}
				}
			}
		}
		else if(isset($_SESSION['VIP'])) {
			if(isset($_SESSION['typ'])) {
				if($_SESSION['typ'] == 'przewoznik') {
										$data_teraz = date("Y-m-d");
					$a = array();
					$_SESSION['licznik'] = 0;
					$sql = "SELECT * FROM loty WHERE przewoziciele_nazwa_firmy IS NOT NULL AND typ = 'VIP'
                    AND data BETWEEN '$data' AND '$data2'
                   AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena 
                   				   AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
															     miasto LIKE '$cel%')
                   ORDER BY data DESC";
					foreach($dbh->query($sql) as $row) {
						$nazwa_lotu = $row['nazwa_lotu'];
						$img = $row['img'];
						$data = $row['data'];
						
						if($data_teraz <= $data) {
							echo "<table class='table_shows'>
							<tr>
							<th class='naglowek'><a>$nazwa_lotu</a></th>
							</tr>
							<tr>
							<th class='odsylacz'>
							<form method='POST' action='/linie_lot/pages/buy_airways.php'>
							<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
							</form>
							</th>
							</tr>
							</table>";
							array_push($a, $nazwa_lotu);
							$_SESSION['licznik']++;
						}
					}
				}
				else if($_SESSION['typ'] == 'admin') {
					$data_teraz = date("Y-m-d");
					$sql = "SELECT * FROM loty WHERE typ = 'VIP'
                    AND data BETWEEN '$data' AND '$data2'
                   AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena 
                   				   AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
															     miasto LIKE '$cel%')
                   ORDER BY data DESC";
					foreach($dbh->query($sql) as $row) {
						$nazwa_lotu = $row['nazwa_lotu'];
						$img = $row['img'];
						$data = $row['data'];
						
						if($data_teraz <= $data) {
							echo "<table class='table_shows'>
							<tr>
							<th class='naglowek'><a>$nazwa_lotu</a></th>
							</tr>
							<tr>
							<th class='odsylacz'>
							<form method='POST' action='/linie_lot/pages/buy_airways.php'>
							<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
							</form>
							</th>
							</tr>
							</table>";
							array_push($a, $nazwa_lotu);
							$_SESSION['licznik']++;
						}
					}
				}
				else if($_SESSION['typ'] == 'klient') {
                    					$data_teraz = date("Y-m-d");
					$login = $_SESSION['login'];
                    $sql = "select * from loty where nazwa_lotu not in (select loty_nazwa_lotu 
                                                     from uzytkownicy_has_loty where uzytkownicy_login IN (select login from uzytkownicy
                                                                                                           where login = '$login'))
                                                                                           AND przewoziciele_nazwa_firmy is NOT NULL
                                                                                           AND typ = 'VIP'
                    AND data BETWEEN '$data' AND '$data2'
                   AND przewoziciele_nazwa_firmy LIKE '$przewoznik%' AND koszt < $max_cena 
                   				   AND start IN (SELECT id_lotniska FROM destynacje WHERE
																   miasto LIKE '$start%')
												     AND cel IN (SELECT id_lotniska FROM destynacje WHERE
															     miasto LIKE '$cel%')
                   ORDER BY data DESC";                                                                                                                                                                          
                        
						foreach($dbh->query($sql) as $row) {
							$nazwa_lotu = $row['nazwa_lotu'];
							$img = $row['img'];
							$data = $row['data'];
							
							if($data_teraz <= $data) {
								echo "<table class='table_shows'>
								<tr>
								<th class='naglowek'><a>$nazwa_lotu</a></th>
								</tr>
								<tr>
								<th class='odsylacz'>
								<form method='POST' action='/linie_lot/pages/buy_airways.php'>
								<input type='image' src='$img' name='$nazwa_lotu' alt='Submit' class='table_img'/>
								</form>
								</th>
								</tr>
								</table>";
								array_push($a, $nazwa_lotu);
								$_SESSION['licznik']++;
							}
						}
				}
			}
		}
		}
		else {
			echo 
			"<center>
			<a style='font-size: 5vh; color: navy; text-shadow: 3px 3px 3px gray'>Aby przeglądać dostępne loty</a>
			<a style='font-size: 5vh; font-weight: bold; color: blue; text-shadow: 3px 3px 3px gray'>Zaloguj się</a>	
			<a style='font-size: 5vh; font-weight: bold; color: navy; text-shadow: 3px 3px 3px gray'>lub</a>	
			<a style='font-size: 5vh; font-weight: bold; color: blue; text-shadow: 3px 3px 3px gray'>Załóż nowe konto</a>	
			</center>";
		}
		
		
		?>
		
		<?php
			if(isset($_SESSION['zalogowany'])) {
				for($i=0; $i < $_SESSION['licznik']; $i++) {
					if($a[$i] != null) {
						if(isset($_POST[$a[$i].'_y']) && isset($_POST[$a[$i].'_x'])) {
							$_SESSION['flight'] = $a[$i];
							header('refresh: 0; url="/linie_lot/pages/flight.php"');
						}
					}
				}
			}
		?>
	</div>
	</div>
<div id="footer">
<a target='_blank' href="/linie_lot/help.txt" style="font-weight: bold; font-size: 2vh; color: red; margin-top: 1%; margin-left: 1%; float: left;">POMOC</a>
</div>

</body>
</html>
