<?php
//include_once("

//initialize variables
$rankPage		= 1;
$persite	= 25;
$cssicon	= 0;
$cssjob		= 0;
$where		= '';
$addlink	= '';
$maxlevel	= 70; //just for a html output
$scripturl	= $_SERVER['PHP_SELF'];
$pvp		= 4;

//the most sql drivers are buggy, use this as a little fix
function _odbc_num_rows($res){
	$rows = 0;
	while (odbc_fetch_array($res)){
		$rows++;
	}
	return $rows;
}

//function for showing next pages
function pages($seite, $maxseite, $url = "", $anzahl = 4, $get_name = "page"){ 
	if (preg_match("/\?/", $url))
		$anhang = "&amp;";
	else
		$anhang = "?";

	if (substr($url, -1, 1) == "&")
		$url = substr_replace($url, "", -1, 1);
	else if (substr($url, -1, 1) == "?") {
		$anhang	= "?";
		$url	= substr_replace($url, "" , -1, 1);
	}

	if ($anzahl%2 != 0)
		$anzahl++; //Wenn $anzahl ungeraden, dann $anzahl++ 

	$a			= $seite - ($anzahl/2);
	$b			= 0;
	$blaetter	= array();
	
	while ($b <= $anzahl){ 
		if ($a > 0 && $a <= $maxseite){ 
			$blaetter[] = $a; 
			$b++; 
		} 
		else if ($a > $maxseite && ($a-$anzahl-2)>=0){ 
			$blaetter = array(); 
			$a -= ($anzahl+2); 
			$b = 0; 
		} 
		else if ($a > $maxseite && ($a-$anzahl-2)<0) { 
			break; 
		} 

		$a++; 
	}
	
	$return = ""; 
	global $AssetUrl;
	if (!in_array(1, $blaetter) && count($blaetter) > 1){ 
		if (!in_array(2, $blaetter)) 
			$return .= "&nbsp;<div style=\"display: inline; position: relative; top: 5px;\"><a href=\"{$url}{$anhang}{$get_name}=1\"><img src=\"$AssetUrl/images/ranking/left.png\" alt=\"\"></a></div>"; 
		else 
			$return .= "&nbsp;<a href=\"{$url}{$anhang}{$get_name}=1\">1</a>&nbsp;"; 
	} 

	foreach ($blaetter as $blatt){ 
		if ($blatt == $seite) 
			$return .= "&nbsp;<b>$blatt</b>&nbsp;"; 
		else
			$return .= "&nbsp;<a href=\"{$url}{$anhang}{$get_name}=$blatt\">$blatt</a>&nbsp;"; 
	} 

	if (!in_array($maxseite, $blaetter) && count($blaetter) > 1) { 
		if (!in_array(($maxseite-1), $blaetter)) 
			$return .= "&nbsp;<div style=\"display: inline; position: relative; top: 5px;\"><a href=\"{$url}{$anhang}{$get_name}=$maxseite\"><img src=\"$AssetUrl/images/ranking/next.png\" alt=\"\"></a></div>&nbsp;"; 
		else
			$return .= "&nbsp;<a href=\"{$url}{$anhang}{$get_name}=$maxseite\">$maxseite</a>&nbsp;"; 
	} 

	if (empty($return)) 
		return  "&nbsp;<b>1</b>&nbsp;"; 
	else 
		return $return; 
} 



//check level area


//check current page
if (isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page'])) {
	$rankPage		= GetClear($_GET['page']);
	$addlink	= '&amp;page='.$rankPage;
}

//calculate begin and end
$begin	= ($rankPage - 1) * $persite;
$max	= $rankPage * $persite;

//output HTML
echo "<center>";



//output table header
echo '<table class="nice_table" border=0 cellspacing=5 cellpadding=1>
		<tr>
			<td width="5%" align="center">#</td>
			<td width="27%" align="center">Kill Time</td>
			<td width="18%" >Killer Name</td>	
			<td width="18%" align="center">Target Name</td>
			<td width="7%" align="center">Faction</td>
			<td width="7%" align="center">Job</td>
			<td width="15%" align="center">Map</td>
			
		</tr>';
	
	  
//sql query


$sql = "SELECT    top $max  [o].[CharName], [o].[CharID] , [o].[row], [o].[KillDate], [o].[TargetCharName],  [m].[MapName] 
,[CC].[Level], [CC].[Job], [CC].[Family]
FROM [PS_GameLog].[dbo].[OwnKills] AS [o] 
INNER JOIN [PS_UserData].[dbo].[Users_Master] AS [u] ON [u].[UserUID] = [o].[UserUID] 
INNER JOIN [PS_GameDefs].[dbo].[MapNames] AS [m] ON [m].[MapID] = [o].[MapID] 
 INNER JOIN (Select [c].[Level], [c].[Job], [c].[CharID], [c].[Family] FROM  [PS_GameData].[dbo].[Chars] AS [c] ) CC 
 ON [o].[CharID] = [CC].[CharID] ORDER BY $order";
// $sql = "SELECT top $max [c].[Level], [c].[Job], [c].[Family], [o].[CharName], [o].[row], [o].[KillDate], [o].[TargetCharName], [c].[LoginStatus], [m].[MapName]  FROM  [PS_GameLog].[dbo].[OwnKills] AS [o]
//         INNER JOIN [PS_GameData].[dbo].[Chars] AS [c] ON  [o].[UserUID] = [c].[UserUID]
// 		INNER JOIN [PS_UserData].[dbo].[Users_Master] AS [u] ON [u].[UserUID] = [c].[UserUID]
// 		INNER JOIN [PS_GameDefs].[dbo].[MapNames] AS [m] ON [m].[MapID] = [o].[MapID]
		
// 		ORDER BY $order";
	
	
// Old query
//$sql = "SELECT top $max [c].* FROM [PS_GameData].[dbo].[Chars] AS [c] 
		//INNER JOIN [PS_UserData].[dbo].[Users_Master] AS [u] ON [u].[UserUID] = [c].[UserUID]
		//WHERE [c].[Del] = 0 AND [u].[Status] = 0 $where
		//ORDER BY [c].[K1] DESC, [c].[K2] ASC, [c].[CharName] ASC ";
$res = odbc_exec($odbcConn, $sql);



for ($i = 1; $char = odbc_fetch_array($res); $i++){
	if ($i >= $begin) {
		$cssjob = $char['Job'];
		
		// faction icon
		$FactionIcon = ($char['Family'] < 2) ? "faction-light" : "faction-dark";
		//light or dark
		//if ($char['Family'] < 2)
			//$faction = '<font color="#0e31ff">Light</font>';
		//else
			//$faction = '<font color="red">Dark</font>';
		
		
			
		//online status
		if (isset($char['LoginStatus'])){
			if ($char['LoginStatus'] == 0)
				$online = '<img src="../images/icons/red.png" title="Offline" >';
			else
				$online = '<img src="../images/icons/green.png" title="Online">';
		}
		else
			$online = '<font color="#014b9d">Unknown</font>';
		
	
			// if ($guild != false)
			// 	echo '<td><a class="orange" style="cursor:pointer;" onmouseover=\'Tip("'.$guild->getToolTipHtml().'")\' onmouseout="UnTip()">'.$char['CharName'].'</a></td>';
			// else '.$char['Level'].'
		
		//output
		echo '<tr>';
		echo '<td class="center">'.$char['row'].'</td>';
		echo '<td class="center">'.substr($char['KillDate'], 0, -4).'</td>';
		
		echo "<td class='orange'>".$char['CharName']."</td>";
		echo "<td class='center'>".$char['TargetCharName']."</td>";
		echo "<td><div class='faction_icon $FactionIcon'></div></td>";
		echo "<td class='center'><img src='$AssetUrl/images/ranking/$cssjob.png'></td>";	
		echo '<td class="center">'.$char['MapName'].'</td>';
	
		
		echo '</tr>'."\n";
	}
}

echo '</table><br>';

//show next pages
$csql = "SELECT Count([c].[CharID]) AS [Count] FROM [PS_GameData].[dbo].[Chars] AS [c]
		 INNER JOIN [PS_UserData].[dbo].[Users_Master] AS [u] ON [u].[UserUID] = [c].[UserUID]
		 WHERE [c].[Del] = 0 AND [u].[Status] = 0 $where";
$cres = odbc_exec($odbcConn, $csql);
$cfet = odbc_fetch_array($cres);


$ccount = $cfet['Count'];
$cpages = $ccount/$persite;

echo pages($rankPage,  ceil($cpages), $url="/?p=$page&type=$type&pvp=$pvp");