<form action='/linie_lot/pages/buy_airways.php' method='POST'>
	<table class='table_filtr'>
	<tr>
		<td><a class='a_filtr'>Start:</a></td><td><input type='text' class='text_filtr' name='start'></td>
		<td><a class='a_filtr'>Cel:</a></td><td><input type='text' class='text_filtr' name='cel'></td>
		<td><a class='a_filtr'>Max. cena:</a></td><td><input type='text' class='text_filtr' name='max_cena'></td>
		<?php
		$data_teraz = date("Y-m-d");
		echo 
		"<td><a class='a_filtr'>Data od/do:</a></td><td><input name='data' type='date' value='$data_teraz' class='text_filtr'>
		<br><input name='data2' type='date' value='$data_teraz' class='text_filtr'>
		</td>";
		?>
		<td><a class='a_filtr'>Przewoźnik:</a></td><td><input type='text' name='przewoznik' class='text_filtr'></td>
	</tr>
	</table>
	<input value="Filtr" type='submit' name='szukaj' class='button_ok'>

</form>

<?php
	if(isset($_POST['szukaj'])) {
		$start = $_POST['start'];
		$cel = $_POST['cel'];
		$max_cena = $_POST['max_cena'];
		$data = $_POST['data'];
		$data2 = $_POST['data2'];
		$przewoznik = $_POST['przewoznik'];
		$data_teraz = date("Y-m-d");
		
		if($przewoznik == null)
			$przewoznik = "";
		if($data == $data_teraz)
			$data = "1997-04-22";
		if($data2 == $data_teraz)
			$data2 = "2025-04-22";
		if($start == null)
			$start = "";
		if($cel == null)
			$cel = "";
		if($max_cena = null)
			$max_cena = 99999;
			
		$_SESSION['start'] = $start;
		$_SESSION['cel'] = $cel;
		$_SESSION['data'] = $data;
		$_SESSION['data2'] = $data2;
		$_SESSION['przewoznik_filtr'] = $przewoznik;
		$_SESSION['max_cena'] = $max_cena;
		header("refresh: 0;");
}
	
?>
