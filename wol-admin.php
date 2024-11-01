<?php
class WolAdmin {
	
	function wol_adminPage() {
		if ( isset( $_POST['wol_insert'] ) ) {
			global $user_ID, $wpdb, $table_prefix;
			$wpdb->query("INSERT INTO {$table_prefix}wol_data (userid, category, date, duration,
			distance, comment)
			VALUES ($user_ID, $_POST[wol_category], '$_POST[wol_date]', '$_POST[wol_duration]', 
			'$_POST[wol_distance]', '$_POST[wol_comment]')");
		}
		if ( isset( $_GET['wol_delwoid'] ) ) {
			global $user_ID, $wpdb, $table_prefix;
			$wpdb->query("DELETE FROM {$table_prefix}wol_data WHERE id=$_GET[wol_delwoid] AND userid=$user_ID");
		}
		if ( isset( $_POST['wol_delcat'] ) ) {
			global $wpdb, $user_level, $table_prefix;
			if ( $user_level >= 2 ) {
				$wpdb->query("DELETE FROM {$table_prefix}wol_category WHERE id=$_POST[wol_delcatid]");
			}
		}
		if ( isset( $_POST['wol_addcat'] ) ) {
			global $wpdb, $user_level, $table_prefix;
			if ( $user_level >= 2 ) {
				$wpdb->query("INSERT INTO {$table_prefix}wol_category (name) VALUES ('$_POST[wol_addcatname]')");
			}
		}
		if ( isset( $_GET['wol_overviewyear'] ) ) {
			global $user_ID;
			update_option('wol_overviewyear' . $user_ID, $_GET['wol_overviewyear']);
		}
		if ( isset( $_GET['wol_overviewmonth'] ) ) {
			global $user_ID;
			update_option('wol_overviewmonth' . $user_ID, $_GET['wol_overviewmonth']);
		}
	?>
		<div class="wrap">
			<form method="post" action="<?php echo $_SERVER[PHP_SELF] . '?page=wol\wol-setup.php';?>">
			<h2>wol - Training eintragen</h2>
			<div style="float:left;">
				<div>Datum (JJJJ:MM:TT):</div>
				<div><input type="text" name="wol_date" value="<?php echo date("Y-m-d");?>"/></div>
			</div>
			<div style="float:left;">
				<div>Dauer (HH:MM):</div>
				<div><input type="text" name="wol_duration" value="HH:MM"/></div>
			</div>
			<div style="float:left;">
				<div>Strecke:</div>
				<div><input type="text" name="wol_distance"/> km</div>
			</div>
			<div style="float:left;">
				<div>Sportart:</div>
				<div><select name="wol_category" size="1">
				<?php
				global $wpdb, $table_prefix;
				$wol_categories = $wpdb->get_results("SELECT * FROM {$table_prefix}wol_category ORDER BY name ASC");
				if (isset($wol_categories)) {
					foreach ( $wol_categories as $wol_category ) {
						echo "<option value='$wol_category->id'>$wol_category->name</option>";
					}
				}
				?></select></div>
			</div>
			<div style="float:left;">
				<div>Kommentar:</div>
				<div><input type="text" name="wol_comment"/></div>
			</div>
			<div class="submit">
			<input type="submit" name="wol_insert" value="Training eintragen"/></div>   
			</form>
			<div style="clear:both;"> </div>
		</div>
		<div class="wrap">
			<h2>wol - Sportarten verwalten</h2>
			<div>
			<form method="post" action="<?php echo $_SERVER[PHP_SELF] . '?page=wol\wol-setup.php';?>">
				<div>Sportart ausw&auml;hlen:</div>
				<div style="float:left;">
				<select size="1" name="wol_delcatid">
				<option>-</option>
				<?php
				global $wpdb, $table_prefix;
				$wol_categories = $wpdb->get_results("SELECT * FROM {$table_prefix}wol_category ORDER BY name ASC");
				if (isset($wol_categories)) {
					foreach ( $wol_categories as $wol_category ) {
						echo "<option value='$wol_category->id'>$wol_category->name</option>";
					}
				}
				?>
				</select>
				</div>
				<div class="submit">
				<input type="submit" name="wol_delcat" value="Sportart l&ouml;schen"/></div>
			<form>
			</div>
			<div>
			<form method="post" action="<?php echo $_SERVER[PHP_SELF] . '?page=wol\wol-setup.php' . basename(__FILE__);?>">
				<div style="float:left;">
					<div>Sportart eintragen:</div>
					<div><input type="text" name="wol_addcatname"/></div>
				</div>
				<div class="submit">
				<input type="submit" name="wol_addcat" value="Sportart hinzuf&uuml;gen"/></div>
			<form>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="wrap">
			<h2>wol - Monats&uuml;bersicht - einzeln</h2>
			<div style="clear:both;margin-bottom:10px;"><strong>Jahre:</strong> 
			<?php
				global $wpdb, $user_ID, $table_prefix;
				$wol_rows = $wpdb->get_results("
					SELECT YEAR(date) AS year 
					FROM {$table_prefix}wol_data WHERE userid = $user_ID 
					GROUP BY year");
				if ( $wol_rows != null ) {
					foreach ( $wol_rows as $wol_row ) {
						echo "<a href='$_SERVER[PHP_SELF]?page=wol\wol-setup.php" 
						. basename(__FILE__) . 
						"&wol_overviewyear=$wol_row->year' 
						title='Jahr auswählen.'>
						$wol_row->year</a> ";
					}
				}
			?>
			</div>
			<div style="clear:both;margin-bottom:10px"><strong>Monate:</strong> 
				<?php
				global $wpdb, $user_ID, $table_prefix;
				$wol_rows = $wpdb->get_results("
					SELECT MONTH(date) AS month 
					FROM {$table_prefix}wol_data WHERE userid = $user_ID 
					AND YEAR(date) = " . get_option('wol_overviewyear' . $user_ID) .
					" GROUP BY month");
				if ( $wol_rows != null ) {
					foreach ( $wol_rows as $wol_row ) {
						echo "<a href='$_SERVER[PHP_SELF]?page=wol\wol-setup.php" 
						. basename(__FILE__) . 
						"&wol_overviewmonth=$wol_row->month' 
						title='Monat auswählen.'>
						$wol_row->month</a> ";
					}
				}
				?>
			</div>
			<div style="clear:both"></div>
			<table>
			<tr>
				<th>Datum</th>
				<th>Dauer</th>
				<th>Strecke</th>
				<th>Schnitt</th>
				<th>Sportart</th>
				<th>Kommentar</th>
				<th></th>
			</tr>
			<?php
				global $wpdb, $user_ID, $table_prefix;
				add_option('wol_overviewmonth' . $user_ID, date('n'));
				add_option('wol_overviewyear' . $user_ID, date('Y'));
				$wol_rows = $wpdb->get_results("
				SELECT {$table_prefix}wol_data.id, name, date, duration, distance, 
				comment, 
				round((distance / (TIME_TO_SEC(duration) / 3600)),2) AS average
				FROM {$table_prefix}wol_data JOIN {$table_prefix}wol_category 
				ON {$table_prefix}wol_data.category = {$table_prefix}wol_category.id 
				WHERE userid = $user_ID 
				AND MONTH(date) = " . get_option('wol_overviewmonth' . $user_ID) . 
				" AND YEAR(date) = " . get_option('wol_overviewyear' . $user_ID) .
				" ORDER BY date, {$table_prefix}wol_data.id");
				if ( $wol_rows != null ) {
					foreach ( $wol_rows as $wol_row ) { 
						echo "
						<tr>
						<td>$wol_row->date</td>
						<td>$wol_row->duration h</td>
						<td>$wol_row->distance km</td>
						<td>$wol_row->average km/h</td>
						<td>$wol_row->name</td>
						<td>$wol_row->comment</td>
						<td><a href='$_SERVER[PHP_SELF]?page=wol\wol-setup.php" 
						. basename(__FILE__) . 
						"&wol_delwoid=$wol_row->id' 
						title='Eintrag l&ouml;schen'>
						l&ouml;schen</a></td>
						</tr>";
					}
				}
			?>
			</table>
					
			</div>
		</div>
		
		<div class="wrap">
			<h2>wol - Monats&uuml;bersicht - nach Kategorie</h2>
			<table>
				<tr>
				<th>Sportart</th>
				<th>Dauer</th>
				<th>Strecke</th>
				</tr>
				<?php
					$wol_rows = $wpdb->get_results("
					SELECT name, SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS duration_sum, ROUND(SUM(distance),1) AS distance_sum
					FROM {$table_prefix}wol_data JOIN {$table_prefix}wol_category 
					ON {$table_prefix}wol_data.category = {$table_prefix}wol_category.id 
					WHERE userid = $user_ID 
					AND MONTH(date) = " . get_option('wol_overviewmonth' . $user_ID) . 
					" AND YEAR(date) = " . get_option('wol_overviewyear' . $user_ID) .
					" GROUP BY category");
					if ( $wol_rows != null ) {
						foreach ( $wol_rows as $wol_row ) { 
							echo "
							<tr id=\"wol_sum\">
							<td>$wol_row->name</td>
							<td>$wol_row->duration_sum h</td>
							<td>$wol_row->distance_sum km</td>
							</tr>";
						}
					}
				?>
			</table>
		</div>	
		
		<div class="wrap">
			<h2>wol - Monats&uuml;bersicht - gesamt</h2>
			<table>
				<tr>
				<th>Monatsdauer</th>
				<th>Monatsstrecke</th>
				</tr>
				<?php
					$wol_rows = $wpdb->get_results("
					SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS duration_sum, ROUND(SUM(distance),1) AS distance_sum
					FROM {$table_prefix}wol_data 
					WHERE userid = $user_ID 
					AND MONTH(date) = " . get_option('wol_overviewmonth' . $user_ID) . 
					" AND YEAR(date) = " . get_option('wol_overviewyear' . $user_ID));
					if ( $wol_rows != null ) {
						foreach ( $wol_rows as $wol_row ) { 
							echo "
							<tr id=\"wol_sum\">
							<td>$wol_row->duration_sum h</td>
							<td>$wol_row->distance_sum km</td>
							</tr>";
						}
					}
				?>
			</table>
		</div>	
	
	<?php
	}
	
	function wol_addAdminStylesheet() {
		echo '<style type="text/css">
		td, th { text-align: left; }
		table { width: 100%; }
		</style>';
	}
	
}
?>