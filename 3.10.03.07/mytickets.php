<? 
/***************************************************************************
 *                               mytickets.php
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

if ($_SESSION['userid']) {
	// Pull the download table for this user
	$page_content .= "
						<div id=\"tabs\">
							<ul>
								<li><a href=\"#currentTickets\"><span>" . $LANG['TABS_CURRENT_TICKETS'] . "</span></a></li>
								<li><a href=\"#createANewTicket\"><span>" . $LANG['TABS_CREATE_A_NEW_TICKET'] . "</span></a></li>
							</ul>
							<div id=\"currentTickets\">
								<div class=\"roundedBox center\">
									<a href=\"" . $menuvar['MYTICKETS'] . "\">" . $LANG['FORMTITLES_ALL_TICKETS'] . "</a> &nbsp;|&nbsp; 
									<a href=\"" . $menuvar['MYTICKETS'] . "&status=0\">" . $LANG['FORMTITLES_OPEN_TICKETS'] . "</a> &nbsp;|&nbsp; 
									<a href=\"" . $menuvar['MYTICKETS'] . "&status=1\">" . $LANG['FORMTITLES_CLOSED_TICKETS'] . "</a> &nbsp;|&nbsp; 
									<a href=\"" . $menuvar['MYTICKETS'] . "&status=2\">" . $LANG['FORMTITLES_ON_HOLD_TICKETS'] . "</a>
								</div>
								<br />
								<div id=\"updateMeTickets\">
									" . printTicketsTable(array('user_id' => $_SESSION['userid'], 'status' => $_GET['status'])) . "
								</div>
							</div>
							<div id=\"createANewTicket\">
								" . printNewTicketForm() . "
							</div>
						</div>";
	
	// Handle our JQuery needs
	$JQueryReadyScripts = returnTicketsTableJQuery() . returnNewTicketFormJQuery(1) . "$(\"#tabs\").tabs();";

	$page->setTemplateVar("PageContent", $page_content);
	$page->setTemplateVar("JQueryReadyScript", $JQueryReadyScripts);
}
else {
	$page->setTemplateVar('PageContent', $LANG['ERROR_NOT_AUTHORIZED']);
}
?>