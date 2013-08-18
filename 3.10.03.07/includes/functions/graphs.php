<?php 
/***************************************************************************
 *                               graphs.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton - Fast Track Sites
 *   email                : sales@fasttacksites.com
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
	
	//=========================================================
	// Gets the total number of tickets based on a date range
	//=========================================================
	function getTicketCount($startDatetimestamp, $stopDatetimestamp, $ticketStatus = "") {
		$extraSQL = ($startDatetimestamp == "" || $stopDatetimestamp == "") ? "" : " AND datetimestamp >= '" . $startDatetimestamp . "' AND datetimestamp < '" . $stopDatetimestamp . "'";
		$extraSQL .= (!is_numeric($ticketStatus)) ? "" : " AND status = '" . $ticketStatus . "'";
		$sql = "SELECT COUNT(id) AS totalTickets FROM `" . DBTABLEPREFIX . "tickets` WHERE 1" . $extraSQL;
		$result = mysql_query($sql);
		//echo $sql . "<br />";
						
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				return $row['totalTickets'];
			}		
			mysql_free_result($result);
		}
		else {
			return "0";
		}
	}
	
	//=================================================
	// Create a form to run a custom graph
	//
	// Used so that we can display it in many places
	//=================================================
	function printNewGraphForm() {
		global $menuvar, $tts_config, $LANG;

		$content .= "
				<form name=\"newGraphForm\" id=\"newGraphForm\" action=\"" . $menuvar['GRAPHS'] . "\" method=\"post\" class=\"inputForm\" onsubmit=\"return false;\">
					<fieldset>
						<legend>" . $LANG['FORMTITLES_GENERATE_A_CUSTOM_GRAPH'] . "</legend>
						<h3>1. " . $LANG['FORMITEMS_CHOOSE_GRAPH'] . "</h3>
						<div><label for=\"selectedGraph\">" . $LANG['FORMITEMS_GRAPH'] . " <span>- Required</span></label> " . createDropdown("graphs", "selectedGraph", "invoicedVsPaid", "", "required") . "</div>
						
						<h3>2. " . $LANG['FORMITEMS_CHOOSE_DATE_RANGE'] . "</h3>
						<div><label for=\"daterange\">" . $LANG['FORMITEMS_DATE_RANGE'] . " <span>- Required</span></label> " . createDropdown("daterange", "daterange", "allTime", "", "required") . "</div>
						<div><label for=\"start_date\">" . $LANG['FORMITEMS_START_DATE'] . " </label> <input type=\"text\" name=\"start_date\" id=\"start_date\" size=\"60\" /></div>
						<div><label for=\"stop_date\">" . $LANG['FORMITEMS_STOP_DATE'] . " </label> <input type=\"text\" name=\"stop_date\" id=\"stop_date\" size=\"60\" /></div>
						
						<h3>3. " . $LANG['FORMITEMS_CHOOSE_GRAPH_TYPE'] . "</h3>
						<div><label for=\"client_id\">" . $LANG['FORMITEMS_GRAPH_TYPE'] . " <span>- Required</span></label> " . createDropdown("graphtypes", "graphType", "column", "", "required") . "</div>
						<div class=\"center\"><input type=\"submit\" class=\"button\" value=\"" . $LANG['BUTTONS_CREATE_GRAPH'] . "\" /></div>
					</fieldset>
				</form>
				<div id=\"newGraphResponse\">
				</div>";
			
		return $content;
	}
	
	//=================================================
	// Returns the JQuery functions used to run the 
	// new graph form
	//=================================================
	function returnNewGraphFormJQuery($reprintGraph = 0) {			
		$extraJQuery = ($reprintGraph == 0) ? "
  						// Update the proper div with the returned data
						$('#newGraphResponse').html('" . progressSpinnerHTML() . "');
						$('#newGraphResponse').html(data);
						$('#newGraphResponse').effect('highlight',{},500);" 
						: "
						// Clear the current graph and show the new one
						$('#newGraphResponse').html('" . progressSpinnerHTML() . "');
						$('#newGraphResponse').html(data);";
						
		$JQueryReadyScripts = "
			$('#start_date').datepicker({
				showButtonPanel: true
			});
			$('#stop_date').datepicker({
				showButtonPanel: true
			});
			var v = jQuery(\"#newGraphForm\").validate({
				errorElement: \"div\",
				errorClass: \"validation-advice\",
				submitHandler: function(form) {			
					jQuery.get('graphit.php', $('#newGraphForm').serialize(), function(data) {
  						" . $extraJQuery . "
					});
				}
			});";
		
		return $JQueryReadyScripts;
	}

?>