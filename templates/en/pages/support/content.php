<?php
if (!$UserUID) {
	include("pages/not-logged.php");
	return;
}

if (isset($_GET["tickets"]))
	include(is_numeric($_GET["tickets"]) && $_GET["tickets"] > 0 ? "pages/ticketview.php" : "pages/tickets.php");
elseif (isset($_GET["panel"]) && $IsStaff)
	include(is_numeric($_GET["panel"]) && $_GET["panel"] > 0 ? "pages/panel-ticketview.php" : "pages/panel.php");
else
	include("pages/contact.php");
