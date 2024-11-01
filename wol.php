<?php
/*
Plugin Name: wol - workoutlog
Plugin URI: http://wol.marcel-malitz.de
Description: Mit dem workoutblog (wol) kannst du deine Traingseinheiten loggen. F&auml;hrst du z.B. eine halbe Stunde Rennrad kannst du die km, die Zeit und die Art des Trainings notieren. Au&szlig;erdem kannst du weitere Sportarten hinzuf&uuml;gen und Statistiken in deine Templates einbinden. Unter dem Men&uuml;punkt Verwalten->workoutblog kann wol administriert werden. Jeder Benutzer kann nur die eigenen Einheiten verwalten.
Version: 0.2
Author: Marcel Malitz	
Author URI: http://www.marcel-malitz.de

Copyright 2006  Marcel Malitz  (email : wol@marcel-malitz.de)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Load all required files.
require('wol-setup.php');
require('wol-admin.php');
require('wol-view.php');

add_action('admin_menu', array('WolSetup','wol_addPages'));
add_action('admin_head', array('WolAdmin','wol_addAdminStylesheet'));
add_action('activate_' . basename(__FILE__),array('WolSetup','wol_createTables'));

add_filter('the_content', array('WolView','insertCurrentUserWeekStat'));
add_filter('the_content', array('WolView','insertCurrentYearStat'));

?>
