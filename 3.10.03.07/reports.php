<? 
/***************************************************************************
 *                               reports.php
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
	//==================================================
	// Handle editing, adding, and deleting of users
	//==================================================	
	if ($actual_action == "viewreport" && isset($actual_report)) {
		// Add breadcrumb
		$page->addBreadCrumb($LANG['REPORTS_VIEW_REPORT'], "");
		$reportData = $reportJQuery = "";
		
		$selectedReport = keepsafe($_REQUEST['report']);
		$daterange = keepsafe($_REQUEST['daterange']);
		$start_date = keepsafe($_REQUEST['start_date']);
		$stop_date = keepsafe($_REQUEST['stop_date']);
		$currentTime = time();
		$startDate = "";
		$endDate = "";
		$reportSuffix = "";
		
		if ($daterange == "today") {
			$startDate = strtotime("today");
			$endDate = strtotime("+1 day");
			$reportSuffix = " - Today";
		}
		elseif ($daterange == "thisWeek") {
			$startDate = strtotime(date("Y").'W'.date('W')."0");
			$endDate = strtotime(date("Y").'W'.date('W')."7");
			$reportSuffix = " - This Week";
		}
		elseif ($daterange == "thisMonth") {
			$startDate = strtotime(makeMonth($currentTime) . " 1, " . makeYear($currentTime));
			$endDate = makeXMonthsFromCurrentMonthAsTimestamp(1);
			$reportSuffix = " - This Month";
		}
		elseif ($daterange == "thisYear") {
			$startDate = strtotime("Jan 1, " . makeYear($currentTime));
			$endDate = strtotime("Jan 1, " . (makeYear($currentTime) + 1));
			$reportSuffix = " - This Year";
		}
		elseif ($daterange == "allTime") {
			$reportSuffix = " - All Time";
		}
		elseif ($daterange != "") {
			$startDate = strtotime($start_date);
			$endDate = strtotime($stop_date);
			$reportSuffix = "Between " . $start_date . " and " . $stop_date;
		}
		
		// Depending on the report we request lets build the page
		switch ($actual_report) {
			case 'ticketEntries':
				$reportData = printTicketEntriesReport($startDate, $endDate, $reportSuffix);
				$reportJQuery = returnTicketEntriesReportJQuery();
				break;
			case 'tickets':
				$reportData = printTicketsReport($startDate, $endDate, $reportSuffix);
				$reportJQuery = returnTicketsReportJQuery();
				break;
			case 'userDetails':
				$reportData = printUserDetailsReport($startDate, $endDate, $reportSuffix);
				$reportJQuery = returnUserDetailsReportJQuery();
				break;
			default:
				$reportData = "You did not specify a proper report, please try again.";
				break;
		}
		
		// Take and send the actual data to the page
		$otherVersionLink = ($actual_style == "printerFriendly") ? "<a href=\"" . $menuvar['VIEWREPORT'] . "&report=" . $actual_report . "\">Normal Version</a>" : "<a href=\"" . $menuvar['VIEWREPORT'] . "&report=" . $actual_report . "&style=printerFriendly\">Printer Friendly Version</a>";
		
		$page_content .= "
			<div class=\"roundedBox\">
				<span class=\"versionLinkContainer\"> " . $otherVersionLink . "</span>
				" . $reportData . "
			</div>";
		
		// Handle our JQuery needs
		$JQueryReadyScripts = $reportJQuery;
	}
	else {		
		//==================================================
		// Print out our reports table
		//==================================================
		
		$page_content .= "
						<div id=\"tabs\">
							<ul>
								<li><a href=\"#builtinReports\"><span>" . $LANG['TABS_BUILTIN_REPORTS'] . "</span></a></li>
								<li><a href=\"#runACustomReport\"><span>" . $LANG['TABS_SHOW_A_CUSTOM_REPORT'] . "</span></a></li>
							</ul>
							<div id=\"builtinReports\">
								<ul>
									<li><a href=\"" . $menuvar['VIEWREPORT'] . "&report=tickets\">" . $LANG['REPORTS_TICKETS'] . "</a></li>
									<li><a href=\"" . $menuvar['VIEWREPORT'] . "&report=ticketEntries\">" . $LANG['REPORTS_TICKET_ENTRIES'] . "</a></li>
									<li><a href=\"" . $menuvar['VIEWREPORT'] . "&report=userDetails\">" . $LANG['REPORTS_USER_DETAILS'] . "</a></li>
								</ul>
							</div>
							<div id=\"runACustomReport\">
								" . printNewReportForm() . "
							</div>
						</div>";
				
		// Handle our JQuery needs
		$JQueryReadyScripts = returnNewReportFormJQuery(1) . "$(\"#tabs\").tabs();";
	}
	
	$page->setTemplateVar("PageContent", $page_content);
	$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
}
else {
	$page->setTemplateVar("PageContent", $LANG['ERROR_NOT_AUTHORIZED']);
}
?>