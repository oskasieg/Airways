<?php
include('/opt/lampp/htdocs/linie_lot/scripts/db_connect.php');
ob_start();
?>

<form method="POST" action="/linie_lot/pages/register.php">
	<center>
		<table class='table_register'>
		<tr>
		<th style='width: 30%;'>
			<?php
				if(isset($_SESSION['konto'])) {
					echo "<b>Utworzono konto! Poczekaj na weryfikację przez admina!</b>";
					$_SESSION['konto'] = null;
				}
				else if(isset($_SESSION['krotkie_haslo'])) {
					echo "<b>Hasło jest zbyt krótkie! (min. 8 znaków)</b>";
					$_SESSION['krotkie_haslo'] = null;
				}
				else if(isset($_SESSION['nie_wypelniono'])) {
					echo "<b>Nie wypełniono wszystkich danych!</b>";
					$_SESSION['nie_wypelniono'] = null;
				}
				else if(isset($_SESSION['haslo!=haslo'])) {
					echo "<b>Hasła muszą być takie same!</b>";
					$_SESSION['haslo!=haslo'] = null;
				}
				else if(isset($_SESSION['login_zajety'])) {
					echo "<b>Podany login jest zajęty!</b>";
					$_SESSION['login_zajety'] = null;
				}
			
			?>
		</th>
		</tr>
		<tr><th><a class='a4'>Login:</a></th></tr>
		<tr><td ><input class='textarea2' type='text' name='login'></td></tr>
		<tr><th><a class='a4'>Hasło:</a></th></tr>
		<tr><td ><input class='textarea2' type='password' name='haslo1'></td></tr>
		<tr><th><a class='a4'>Powtórz hasło:</a></th></tr>
		<tr><td ><input class='textarea2' type='password' name='haslo2'></td></tr>
		<tr><th><a class='a4'>Imię:</a></th></tr>
		<tr><td ><input class='textarea2' type='text' name='imie'></td></tr>
		<tr><th><a class='a4'>Nazwisko:</a></th></tr>
		<tr><td ><input class='textarea2' type='text' name='nazwisko'></td></tr>
		<tr><th><a class='a4'>E-mail:</a></th></tr>
		<tr><td ><input class='textarea2' type='email' name='mail'></td></tr>
		<tr><th><a class='a4'>Adres:</a></th></tr>
		<tr><td ><input class='textarea2' type='text' name='adres'></td></tr>
		<tr><th><a class='a4'>Numer telefonu:</a></th></tr>
		<tr><td ><input type="tel" class="textarea2" name="nr_tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}"></td></tr>
		<tr><th><a class='a4'>Nazwisko rodowe matki:</a></th></tr>
		<tr><td><input class='textarea2' type='text' name='pyt_pom'></td></tr>
		<tr>
		<th><input class="button" type="submit" value="Utwórz konto" name="rejestruj"></th>
		</tr>
		
		</table>
	
	</center>
</form>

<?php
 
if (isset($_POST['rejestruj']))
{
	$login =$_POST['login'];
	$haslo1 = ($_POST['haslo1']);
	$haslo2 = ($_POST['haslo2']);
	$mail = ($_POST['mail']);
	$imie = ($_POST['imie']);
	$nazwisko = ($_POST['nazwisko']);
	$nr_tel = ($_POST['nr_tel']);
	$adres = ($_POST['adres']);
	$pyt_pom = ($_POST['pyt_pom']);
 
	$sql = "SELECT login FROM uzytkownicy WHERE login = '".$login."'";
	$tmp = $dbh->prepare($sql);
	$tmp->execute();
	$rows = $tmp->rowCount();
	$t = time();
	$date = date("Y-m-d",$t);
 
 
   // sprawdzamy czy login nie jest już w bazie
	if($login!=null&&$haslo1!=null&&$haslo2!=null&&$mail!=null&&$imie!=null&&$nazwisko!=null&&$nr_tel!=null&&$adres!=null&&$pyt_pom!=null) {
		if ($rows == 0)
		{
			if ($haslo1 == $haslo2) // sprawdzamy czy hasła takie same
			{		
					$insert_user = "INSERT INTO `uzytkownicy` (`login`, `haslo`, `imie`, `nazwisko`, `mail`, `nr_tel`, `adres`, `data_rejestracji`, `pytanie_pom`, `typ`)
					VALUES ('".$login."','".md5($haslo1)."','".$imie."','".$nazwisko."'
					,'".$mail."','".$nr_tel."', '".$adres."', '".$date."', '".$pyt_pom."', 'oczekuje')";
					
					try{
						$result = $dbh->exec($insert_user);
					}
					catch(PDOException $e){
						echo $e->getCode() . " - " . $e->getMessage();
					}
		
		
				$_SESSION['konto'] = true;
				header('refresh: 0');
			}
			else if (strlen($haslo1) < 8){
				$_SESSION['krotkie_haslo'] = true;
				header('refresh: 0');
			}
			else {
				$_SESSION['haslo!=haslo'] = true;
				header('refresh: 0');
			}
		}
		else {
			$_SESSION['login_zajety'] = true;
				header('refresh: 0');
		}
}
else {
	$_SESSION['nie_wypelniono'] = true;
	header('refresh: 0');
}
}

$dbh = null;
?>
