<?php

class WolSetup {

	function wol_addPages() {
		add_management_page('wol - workoutlog', 'workoutlog', 2, __FILE__, array('WolAdmin','wol_adminPage'));
	}
	
	function wol_createTables() {
		global $table_prefix, $wpdb;
		$wpdb->query("
		CREATE TABLE IF NOT EXISTS `{$table_prefix}wol_category` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `name` varchar(30)  NOT NULL default '',
		  PRIMARY KEY  (`id`)
		);");
	
		$wpdb->query("
		CREATE TABLE IF NOT EXISTS `{$table_prefix}wol_data` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `userid` int(10) unsigned NOT NULL default '0',
		  `category` int(10) unsigned NOT NULL default '0',
		  `date` date NOT NULL default '0000-00-00',
		  `duration` time default NULL,
		  `distance` float unsigned default NULL,
		  `comment` varchar(255) NOT NULL default '',
		  PRIMARY KEY (`id`)
		);");
	}
	
}

?>