<a class="active"> HOME </a>
<ol>
	<li><a><?php 
				echo( "<form method='POST' action='/linie_lot/pages/buy_airways.php'>
					<input class='button_menu' name='wszystkie' type='submit' value='LOTY'/>
					</form>");
					
			?>
		</a>
		<ul>
			<li>
				<?php 
				echo( "<form method='POST' action='/linie_lot/pages/buy_airways.php'>
					<input class='button_menu2' name='standard' type='submit' value='Standard'/>
					</form>");
				?>
			</li>
			<li>
				<?php 
				echo( "<form method='POST' action='/linie_lot/pages/buy_airways.php'>
					<input class='button_menu2' name='okazje' type='submit' value='Okazje'/>
					</form>");
				?>
			</li>
			<li>
				<?php 
				echo( "<form method='POST' action='/linie_lot/pages/buy_airways.php'>
					<input class='button_menu2' name='VIP' type='submit' onclick= \"location.href='/linie_lot/pages/buy_airways.php'\" value='VIP'/>
					</form>");
				?>
			</li>
		</ul>
	</li><br>
	<li><a><?php echo( "<input class='button_menu' type='button' onclick= \"location.href='/linie_lot/pages/buy_airways.php'\" value='NASZE'/>" );?></a>
		<ul>
			<li><?php echo( "<input class='button_menu2' type='button' onclick= \"location.href='/linie_lot/pages/destinations.php'\" value='Lotniska'/>" );?></li>
			<li><?php echo( "<input class='button_menu2' type='button' onclick= \"location.href='/linie_lot/pages/hotels.php'\" value='Hotele'/>" );?></li>
		</ul>
	</li><br>
	<li><a><?php echo( "<input class='button_menu' type='button' onclick= \"location.href='/linie_lot/pages/opinions.php'\" value='OPINIE'/>" );?></a>
</ol>
