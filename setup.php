<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.
 
 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/

// Original Author of file: BALPE Dévi
// Purpose of file:
// ----------------------------------------------------------------------

include_once ("plugin_pdf.includes.php");

function plugin_init_pdf() {
	global $PLUGIN_HOOKS;
	
	$PLUGIN_HOOKS['init_session']['pdf'] = 'plugin_pdf_initSession';
	$PLUGIN_HOOKS['change_profile']['pdf'] = 'plugin_pdf_changeprofile';
	
	if (isset($_SESSION["glpi_plugin_pdf_profile"]) && $_SESSION["glpi_plugin_pdf_profile"]["use"])
	{
		$PLUGIN_HOOKS['use_massive_action']['pdf']=1;
		$PLUGIN_HOOKS['headings']['pdf'] = 'plugin_get_headings_pdf';
		$PLUGIN_HOOKS['headings_action']['pdf'] = 'plugin_headings_actions_pdf';
		$PLUGIN_HOOKS['pre_item_delete']['pdf'] = 'plugin_pre_item_delete_pdf';
	}
	if (haveRight("config","w") || haveRight("profile","r")) {
		$PLUGIN_HOOKS['config_page']['pdf'] = 'front/plugin_pdf.profiles.php';
	}
	
}

	
function plugin_version_pdf() {
	global $LANG;

	return array( 
		'name'    => $LANG['pdf']["title"][1],
		'version' => '0.6',
		'author' => 'Dévi Balpe',
		'homepage'=> 'http://www.glpi-project.org/spip.php?article229');
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_pdf_check_prerequisites(){
	if (GLPI_VERSION >= 0.72){
		return true;
	} else {
		echo "GLPI version not compatible need 0.72";
	}
}

// Config process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_pdf_check_config(){
	return TableExists("glpi_plugin_pdf_profiles");
}
// Hook done on delete item case


?>