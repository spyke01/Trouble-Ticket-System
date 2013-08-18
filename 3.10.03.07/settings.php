<? 
/***************************************************************************
 *                               settings.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Fast Track Sites
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

/***************************************************************************
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of the <organization> nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 ***************************************************************************/

if ($_SESSION['user_level'] == SYSTEM_ADMIN) {
	// Handle updating system variables in the database
	if (isset($_POST['submit'])) {		
		foreach($_POST as $name => $value) {
			if ($name != "submit"){			
				if ($name == "ftstts_active") {
					if ($value == "") { $value = 0; }
					else { $value = 1; }	
				}
				$sql = "UPDATE `" . DBTABLEPREFIX . "config` SET value = '" . keeptasafe($value) . "' WHERE name = '" . keeptasafe($name) . "'";
				$result = mysql_query($sql);
			}
		}		
		
		// Handle checkboxes, unchecked boxes are not posted so we check for this and mark them in the DB as such
		if (!isset($_POST['ftstts_active'])) {
			$sql = "UPDATE `" . DBTABLEPREFIX . "config` SET value = '0' WHERE name = 'ftstts_active'";
			$result = mysql_query($sql);
		}
		
		unset($_POST['submit']);
	}
	
	// Pull the curent variables since we can't trust oir tts_config to carry the latest
	$current_config = array();
	
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "config`";
	$result = mysql_query($sql);
	
	// This is used to let us get the actual items and not just name and value
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$name = $row['name'];
			$value = $row['value'];
			$current_config[$name] = $value;
		}
		mysql_free_result($result);
	}
		
	// Give our template the values
	$page_content .= "
				<form action=\"" . $menuvar['SETTINGS'] . "\" method=\"post\" class=\"inputForm\">
					<div id=\"tabs\">
						<ul>
							<li><a href=\"#systemSettings\"><span>" . $LANG['TABS_SYSTEM_SETTINGS'] . "</span></a></li>
						</ul>
						<div id=\"systemSettings\">
							<fieldset>
								<legend>" . $LANG['FORMTITLES_SYSTEM_SETTINGS'] . "</legend>
								<div><label for=\"ftstts_active\">" . $LANG['FORMITEMS_ACTIVE'] . " </label> <input name=\"ftstts_active\" type=\"checkbox\" value=\"1\"". testChecked($current_config['ftstts_active'], ACTIVE) . " /></div>
								<div><label for=\"ftstts_inactive_msg\">" . $LANG['FORMITEMS_INACTIVE_MSG'] . " </label> <textarea name=\"ftstts_inactive_msg\" cols=\"45\" rows=\"10\">" . $current_config['ftstts_inactive_msg'] . "</textarea></div>
								<div><label for=\"ftstts_cookie_name\">" . $LANG['FORMITEMS_COOKIE_NAME'] . " </label> <input type=\"text\" name=\"ftstts_cookie_name\" id=\"ftstts_cookie_name\" size=\"60\" value=\"" . $current_config['ftstts_cookie_name'] . "\" /></div>
								<div><label for=\"ftstts_site_name\">" . $LANG['FORMITEMS_SITE_NAME'] . " </label> <input type=\"text\" name=\"ftstts_site_name\" id=\"ftstts_site_name\" size=\"60\" value=\"" . $current_config['ftstts_site_name'] . "\" /></div>
								<div><label for=\"ftstts_admin_email\">" . $LANG['FORMITEMS_SYSTEM_EMAIL'] . " </label> <input type=\"text\" name=\"ftstts_admin_email\" id=\"ftstts_admin_email\" size=\"60\" value=\"" . $current_config['ftstts_admin_email'] . "\" /></div>
								<div><label for=\"ftstts_time_zone\">" . $LANG['FORMITEMS_SYSTEM_TIME_ZONE'] . " </label> " . createDropdown("timezone", "ftstts_time_zone", $current_config['ftstts_time_zone'], "") . "</div>
								<div><label for=\"ftstts_language\">" . $LANG['FORMITEMS_SYSTEM_LANGUAGE'] . " </label> " . createDropdown("languages", "ftstts_language", $current_config['ftstts_language'], "") . "</div>
							</fieldset>
						</div>
					</div>
					<div class=\"clear center\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"" . $LANG['BUTTONS_UPDATE_SETTINGS'] . "\" /></div>
				</form>";
				
	$JQueryReadyScripts .= "$(\"#tabs\").tabs();";

	$page->setTemplateVar("PageContent", $page_content);
	$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
}
else {
	$page->setTemplateVar('PageContent', $LANG['ERROR_NOT_AUTHORIZED']);
}
?>