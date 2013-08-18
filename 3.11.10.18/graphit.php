<? 
/***************************************************************************
 *                               graphit.php
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
 	include 'includes/header.php';
 
	if ($_SESSION['user_level'] == SYSTEM_ADMIN) {
		$selectedGraph = keepsafe($_REQUEST['selectedGraph']);
		$graphType = keepsafe($_REQUEST['graphType']);
		$daterange = keepsafe($_REQUEST['daterange']);
		$start_date = keepsafe($_REQUEST['start_date']);
		$stop_date = keepsafe($_REQUEST['stop_date']);
		$currentTime = time();
		$startDate = "";
		$endDate = "";
		$graphSuffix = "";
		
		if ($daterange == "today") {
			$startDate = strtotime("today");
			$endDate = strtotime("+1 day");
			$graphSuffix = "Today";
		}
		elseif ($daterange == "thisWeek") {
			$startDate = strtotime(date("Y").'W'.date('W')."0");
			$endDate = strtotime(date("Y").'W'.date('W')."7");
			$graphSuffix = "This Week";
		}
		elseif ($daterange == "thisMonth") {
			$startDate = strtotime(makeMonth($currentTime) . " 1, " . makeYear($currentTime));
			$endDate = makeXMonthsFromCurrentMonthAsTimestamp(1);
			$graphSuffix = "This Month";
		}
		elseif ($daterange == "thisYear") {
			$startDate = strtotime("Jan 1, " . makeYear($currentTime));
			$endDate = strtotime("Jan 1, " . (makeYear($currentTime) + 1));
			$graphSuffix = "This Year";
		}
		elseif ($daterange == "allTime") {
			$graphSuffix = "All Time";
		}
		else {
			$startDate = strtotime($start_date);
			$endDate = strtotime($stop_date);
			$graphSuffix = "Between " . $start_date . " and " . $stop_date;
		}
	
		// Declare our basic variables that we will use
		$graphType = ($graphType == "") ? "column" : $graphType;
		$firstSeriesData = array();
		$secondSeriesData = array();
		$dataTitles = array();
		
		// Declare our graphclass object
		if (substr(phpversion(), 0, 1) == 5) { 
			$graph = new graphClass;
		}
		else { 
			$graph = &new graphClass;
		}
		
		//===================================================		
		// Fill our arrayData variable
		//===================================================		
		if ($selectedGraph == "totalTickets") {
			// Data Titles
			$dataTitles[0] = $LANG['TICKETS'];
		
			// First Series
			$firstSeriesData[0] = getTicketCount($startDate, $endDate);
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph("", "", 2, 1);
			$graph->retitleGraph($LANG['GRAPHS_TOTAL_TICKETS'] . " - " . $graphSuffix, "", "Amount", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		elseif ($selectedGraph == "ticketsByStatus") {
			// Data Titles
			$dataTitles[0] = $LANG['OPEN'];
			$dataTitles[1] = $LANG['CLOSED'];
			$dataTitles[2] = $LANG['ON_HOLD'];
		
			// First Series
			$firstSeriesData[0] = getTicketCount($startDate, $endDate, 0);
			$firstSeriesData[1] = getTicketCount($startDate, $endDate, 1);
			$firstSeriesData[2] = getTicketCount($startDate, $endDate, 2);
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph("", "", 2, 1);
			$graph->retitleGraph($LANG['GRAPHS_TICKETS_BY_STATUS'] . " - " . $graphSuffix, "", "Amount", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		elseif ($selectedGraph == "ticketsByProblemCategory") {
			$x = 0;
			
			$extraSQL = ($startDatetimestamp == "" || $stopDatetimestamp == "") ? "" : " WHERE t.datetimestamp >= '" . $startDatetimestamp . "' AND t.datetimestamp < '" . $stopDatetimestamp . "'";
			$sql = "SELECT cat.name, COUNT(t.id) AS totalTickets FROM `" . DBTABLEPREFIX . "tickets` t LEFT JOIN `" . DBTABLEPREFIX . "categories` cat ON cat.id = t.cat_id" . $extraSQL . " GROUP BY t.cat_id ORDER BY cat.name ASC";
			$result = mysql_query($sql);
			//echo $sql . "<br />";
			
			if ($result && mysql_num_rows($result) > 0) {
				while ($row = mysql_fetch_array($result)) {
					// Data Titles
					$dataTitles[$x] = $row['name'];
				
					// First Series
					$firstSeriesData[$x] = $row['totalTickets'];
					
					$x++;
				}		
				mysql_free_result($result);
			}
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph("", "", 2, 1);
			$graph->retitleGraph($LANG['GRAPHS_TICKETS_BY_PROBLEM_CATEGORY'] . " - " . $graphSuffix, "", "Amount", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		elseif ($selectedGraph == "testGraph") {			
			// Data Titles
			$dataTitles[0] = "Doughnuts";
			$dataTitles[1] = "Tires";
			$dataTitles[2] = "PCs";
			$dataTitles[3] = "Napkins";
			
			// First Series
			$firstSeriesData[0] = "12";
			$firstSeriesData[1] = "86";
			$firstSeriesData[2] = "32";
			$firstSeriesData[3] = "98";
			
			// Second Series
			$secondSeriesData[0] = "46";
			$secondSeriesData[1] = "2";
			$secondSeriesData[2] = "64";
			$secondSeriesData[3] = "90";
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph("$", "", 2, 1);
			$graph->retitleGraph("Test Graph", "Product", "Net Profit", $dataTitles, 2, "2008 Data", "2009 Data");
			$graph->addGraphData($firstSeriesData, $secondSeriesData);
		}
		elseif ($selectedGraph == "testGraph2") {			
			// Data Titles
			$dataTitles[0] = "Doughnuts";
			$dataTitles[1] = "Tires";
			$dataTitles[2] = "PCs";
			$dataTitles[3] = "Napkins";
			
			// First Series
			$firstSeriesTitle = "2008 Data";
			$firstSeriesData[0] = "12";
			$firstSeriesData[1] = "86";
			$firstSeriesData[2] = "32";
			$firstSeriesData[3] = "98";
			
			// Set all graph related items
			$graph->resizeGraph(400, 400);
			$graph->formatGraph("$", "", 2, 1);
			$graph->retitleGraph("Test Graph 2", "Product", "Net Profit", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		
		// We append the time to our chart holder id tag so that no two holders can exist with the same name which causes the chart to not load
		echo $graph->buildGraph("graphHolder" . time(), $graphType);
	}
?>