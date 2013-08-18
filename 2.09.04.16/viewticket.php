<?php 
/***************************************************************************
 *                               admin.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Fast Track Sites
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 * This program is licensed under the Fast Track Sites Program license 
 * located inside the license.txt file included with this program. This is a 
 * legally binding license, and is protected by all applicable laws, by 
 * editing this page you fall subject to these licensing terms.
 *
 ***************************************************************************/

	$content .= "
					<div id=\"updateMe\">" . returnTicketEntries($actual_id) . "</div>
					<br /><br />
					<div id=\"response\"></div>
					<form name=\"replyToTicketForm\" id=\"replyToTicketForm\" action=\"$menuvar[post]?action=newticket\" method=\"post\" onSubmit=\"return false;\">
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"2\">$T_REPLY</td>
							</tr>
							<tr>
								<td class=\"title2\" style=\"width: 200px;\"><strong>$T_NAME: </strong></td><td class=\"row1\">$_SESSION[username]</td>
							</tr>
							<tr>
							  <td class=\"title2\" colspan=\"2\"><div class=\"center\"><textarea cols=\"70\" rows=\"8\" name=\"message\" wrap=\"virtual\" class=\"required\"></textarea><br /></div></td>
							</tr>		
						</table>	
						<br />
						<div class=\"center\"><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"$T_SUBMIT_REPLY\" class=\"button\" /> <div id=\"submitSpinner\" style=\"display: none;\"><img src=\"themes/" . $tts_config['ftstts_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></div></div>	
					</form>
					<script type=\"text/javascript\">
						var valid = new Validation('replyToTicketForm', {immediate : true, useTitles:true, onFormValidate : ValidateForm});
				
						function ValidateForm(result, formRef) {
							if (result == true) {
								submitButton = fetchItem('submit');
								submitButton.disabled = true;
								sqr_show_hide('submitSpinner');
								new Ajax.Updater('updateMe', 'ajax.php?action=showIndicator', {asynchronous:true, evalScripts:true});
								new Ajax.Updater('response', 'ajax.php?action=postReplyToTicket&id=" . $actual_id . "', {onComplete:function(){ sqr_show_hide('submitSpinner'); submitButton.disabled = false; new Ajax.Updater('updateMe', 'ajax.php?action=printTicketEntries&id=" . $actual_id . "', {asynchronous:true, evalScripts:true}); },asynchronous:true, parameters:Form.serialize(document.replyToTicketForm), evalScripts:true});								
							}
							return false;
			 			}
					</script>";

	$page->setTemplateVar('PageContent', version_functions(yes) . $content);

?>