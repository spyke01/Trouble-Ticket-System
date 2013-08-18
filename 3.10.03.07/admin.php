<? 
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

if ($_SESSION['user_level'] == SYSTEM_ADMIN || $_SESSION['user_level'] == TICKET_ADMIN) {
	
	// Declare our basic variables that we will use	
	$graphType = "column";
	$firstSeriesData = array();
	$secondSeriesData = array();
	$dataTitles = array();
	
	// Declare our graphclass object
	if (substr(phpversion(), 0, 1) == 5) { 
		$graph = new graphClass;
		$graph2 = new graphClass;
	}
	else { 
		$graph = &new graphClass;
		$graph2 = &new graphClass;
	}
	
	//=========================================================
	// Current Tickets by Status Graph
	//=========================================================	
	// Data Titles
	$dataTitles[0] = $LANG['OPEN'];
	$dataTitles[2] = $LANG['ON_HOLD'];

	// First Series
	$firstSeriesData[0] = getTicketCount($startDate, $endDate, 0);
	$firstSeriesData[2] = getTicketCount($startDate, $endDate, 2);
	
	// Set all graph related items
	$graph->resizeGraph(900, 400);
	$graph->formatGraph("", "", 2, 1);
	$graph->retitleGraph($LANG['GRAPHS_TICKETS_BY_STATUS'], "", "Amount", $dataTitles, 1, "", "");
	$graph->addGraphData($firstSeriesData, "");
	
	//=========================================================
	// Current Tickets by Tech Graph
	//=========================================================	
	// Reset our arrays and reuse them
	$firstSeriesData = array();
	$secondSeriesData = array();
	$dataTitles = array();
	
	$x = 0;
	
	$sql = "SELECT u.username, COUNT(t.id) AS totalTickets FROM `" . DBTABLEPREFIX . "tickets` t LEFT JOIN `" . DBTABLEPREFIX . "users` u ON u.id = t.user_id WHERE t.status != '1' GROUP BY t.cat_id ORDER BY u.username ASC";
	$result = mysql_query($sql);
	//echo $sql . "<br />";
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			// Data Titles
			$dataTitles[$x] = $row['username'];
		
			// First Series
			$firstSeriesData[$x] = $row['totalTickets'];
			
			$x++;
		}		
		mysql_free_result($result);
	}
	
	// Set all graph related items
	$graph2->resizeGraph(900, 400);
	$graph2->formatGraph("", "", 2, 1);
	$graph2->retitleGraph($LANG['GRAPHS_TICKETS_BY_TECHNICIAN'], "", "Amount", $dataTitles, 1, "", "");
	$graph2->addGraphData($firstSeriesData, "");
	
	// We append the time to our chart holder id tag so that no two holders can exist with the same name which causes the chart to not load
	$page_content .= "
					<div id=\"tabs\">
						<ul>
							<li><a href=\"#ticketOverview\"><span>" . $LANG['TABS_TICKET_OVERVIEW'] . "</span></a></li>
						</ul>
						<div id=\"ticketOverview\">
							" . $graph->buildGraph("graphHolder" . time(), $graphType) . "
							" . $graph2->buildGraph("graphHolder2" . time(), $graphType) . "
						</div>
					</div>";
	
	// Handle our JQuery needs
	$JQueryReadyScripts = "$(\"#tabs\").tabs();";
	
	$page->setTemplateVar("PageContent", version_functions("yes") . $page_content);
	$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
}
else {
	$page->setTemplateVar('PageContent', $LANG['ERROR_NOT_AUTHORIZED']);
}
?>