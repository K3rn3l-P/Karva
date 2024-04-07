<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

$enableSandbox = false;

$paypalUrl = $enableSandbox ? "https://www.sandbox.paypal.com/cgi-bin/webscr"  : "https://www.paypal.com/cgi-bin/webscr";

$queryString = http_build_query($_POST);


header("Location : ".$paypalUrl."?".$queryString);

?>