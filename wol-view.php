<?php

/* Provides you with functions to ouput statistics from your wol data. */
class WolView {
	
	/*
	*	Prints out a overall statistics of the current year.
	*/
	public static function currentYearStat() {
		$output = '';
		global $wpdb, $table_prefix;
		$wol_rows = $wpdb->get_results("SELECT
			SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS overallduration,
			ROUND(SUM(distance),1) AS overalldistance,
			ROUND(SUM(distance) / (SUM(TIME_TO_SEC(duration)) / 3600), 2) AS overallaverage,
			name
			FROM {$table_prefix}wol_data JOIN {$table_prefix}wol_category 
			ON {$table_prefix}wol_data.category = {$table_prefix}wol_category.id 
			WHERE YEAR(date) = YEAR(NOW()) 
			GROUP BY category 
			ORDER BY name");
		if ( $wol_rows != null ) {
			$output .= '<div id="wol_curyearstat">';
			$output .= wol_printStat("Jahresstatistik " . date('Y'), &$wol_rows);
			$output .= '</div>';
		}
		return $output;
	}
	
	/* 
	* A help function which prints out head in a h3 XHTML tag 
	* and loops through all rows of wol_rows.
	*/
	public static function printStat($head, $wol_rows) {
		$output = "<h3>$head</h3>";
		foreach( $wol_rows as $wol_row ) {
			$output .= "<h4>$wol_row->name</h4>
				<div>Trainingszeit: <span>$wol_row->overallduration h</span></div>
				<div>Trainingskilometer: <span>$wol_row->overalldistance km</span></div>
				<div>Durchschnittsgeschwindigkeit: <span>$wol_row->overallaverage km/h</span></div>";
		} 
		return $output;
	}
	
	/*
	* Prints out the accumulated stats of the current week from all users.
	*/
	public static function currentWeekStat() {
		$output = '';
		global $wpdb, $table_prefix;
		$wol_rows = $wpdb->get_results("SELECT
			SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS overallduration,
			ROUND(SUM(distance),1) AS overalldistance,
			ROUND(SUM(distance) / (SUM(TIME_TO_SEC(duration)) / 3600), 2) AS overallaverage,
			name
			FROM {$table_prefix}wol_data JOIN {$table_prefix}wol_category 
			ON {$table_prefix}wol_data.category = {$table_prefix}wol_category.id 
			WHERE WEEK(date,1) = WEEK(NOW(),1) 
			GROUP BY category 
			ORDER BY name");
		if ( $wol_rows != null ) {
			$output .= '<li id="wol_curweekstat">';
			$output .= wol_printStat("Aktuelle Wochenstatistik", &$wol_rows);
			$output .= '</li>';
		}
		return $output;
	}
	
	/* 
	* Prints out the stats of one you to the corresponding user.
	*	The parameter displayname must be the name of the users 
	* nickname in wordpress.
	*/
	public static function currentUserWeekStat($displayname) {
		global $wpdb, $table_prefix;
		$wol_rows = $wpdb->get_results("SELECT 
			SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS overallduration, 
			ROUND(SUM(distance),1) AS overalldistance, 
			ROUND(SUM(distance) / (SUM(TIME_TO_SEC(duration)) / 3600), 2) AS overallaverage, 
			name 
			FROM {$table_prefix}wol_data JOIN {$table_prefix}wol_category JOIN {$table_prefix}users 
			ON {$table_prefix}wol_data.category = {$table_prefix}wol_category.id AND {$table_prefix}wol_data.userid = {$table_prefix}users.ID 
			WHERE WEEK(date,1) = WEEK(NOW(),1) 
			AND display_name = '$displayname' 
			GROUP BY category 
			ORDER BY name");
		$output = '';
		if (isset($wol_rows)) {
			$output .= '<div id="wol_curuserweekstat">';
			$output .= wol_printStat("Aktuelle Wochenstatistik von $displayname", &$wol_rows);
			$output .= '</div>';
		}
		return $output;
	}
	
	public static function overallMonthlyOverview() {
		/* -- muss noch implementiert werden.
			Eine tabellarische Übersicht zu jedem Benutzer der vergangenen n Jahre. N kann als Parameter angegeben werden. Die Tabelle könnte wie folgt aussehen.
      		  | Januar	| Februar	| März	| ...
			2006	| 300 km	| 200 km	| 10 km | ...
			2007 |...
			...
		*/
	}
	
	public static function insertCurrentUserWeekStat($content = '') {
     $pattern = "/\[WOLCURUSERWEEKSTAT (\w*)\]/";
     return preg_replace_callback(	$pattern, 
     																create_function('$treffer','return WolView::currentUserWeekStat($treffer[1]);'), 
     																$content);
	}
	
	public static function insertCurrentYearStat($content = '') {
     $pattern = "/\[WOLCURYEARSTAT\]/";
     $output = preg_replace_callback(	$pattern, 
     																create_function('$treffer','return WolView::currentYearStat();'), 
     																$content);
		$handle = fopen("f:\\info.txt", "a+");
		fputs($handle, "Das kommt rein:\n");
		fputs($handle, $content);
		fputs($handle, "Das kommt raus:\n");
		fputs($handle, $output);
		fclose($handle);     																
		return $output;     																
	}
	
}

/* Deprecated function. Instead you should use WolView::currentYearStat(). */
function wol_currentYearStat() {
	echo WolView::currentYearStat();
}

/* Deprecated! Instead you should use WolView::printStat(&$head, &$wol_rows) */
function wol_printStat($head, $wol_rows) {
	echo WolView::printStat(&$head, &$wol_rows);
}

/* Deprecated! Instead you should use WolView::currentWeekStat() */
function wol_currentWeekStat() {
	echo WolView::currentWeekStat();
}

/* Deprecated! Instead you should use WolView::currentUserWeekStat(&$displayname) */
function wol_currentUserWeekStat($displayname) {
	echo WolView::currentUserWeekStat(&$displayname);
}

?>