<?php
//include_once("

//initialize variables
$rankPage		= 1;
$persite	= 25;
$cssicon	= 0;
$cssjob		= 0;
$where		= '';
$addlink	= '';
$maxlevel	= 60; //just for a html output
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
if (isset($_GET['pvp']) && !empty($_GET['pvp']) && is_numeric($_GET["pvp"])){
	$pvp = GetClear($_GET['pvp']);
	
	if ($pvp == 1)
		$where = 'AND [c].[Level] BETWEEN 1 AND 15';
	else if ($pvp == 2)
		$where = 'AND [c].[Level] BETWEEN 16 AND 30';	
	else if ($pvp == 3)
		$where = 'AND [c].[Level] > 31';
}

//check current page
if (isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page'])) {
	$rankPage		= GetClear($_GET['page']);
	$addlink	= '&amp;page='.$rankPage;
}

//calculate begin and end
$begin	= ($rankPage - 1) * $persite;
$max	= $rankPage * $persite;



//output table header
echo '<table class="nice_table" border=0 cellspacing=5 cellpadding=1>
		<tr>
			<td width="5%" align="center">Top</td>
			<td width="7%" align="center">Faction</td>
			<td width="20%" >Name</td>
			<td width="5%" align="center">Job</td>
			<td width="5%" align="center">Lv</td>
			<td width="7%" align="center">Status</td>
			<td width="400px" align="center">Total Kills</td>
			<td width="110px" align="center">Daily kills</td>
			<td width="110px" align="center">Weekly Kills</td>
			<td width="110px" align="center">Own Kills</td>
			<td width="110px" align="center">Death</td>
			<td width="7%" align="center">Rank</td>
		</tr>';
	  
//sql query
$sql = "SELECT TOP $max ISNULL(DK,0) AS DK, ISNULL(WK,0) AS WK, ISNULL(MK,0) AS MK, [c].* FROM [PS_GameData].[dbo].[Chars] AS [c] 
	LEFT JOIN (SELECT COUNT(1) AS [DK],CharID FROM PS_GameLog.dbo.Kills WHERE DT>CAST(CURRENT_TIMESTAMP AS DATE) AND IsKiller=0 GROUP BY CharID) DK ON DK.CharID=C.CharID
	LEFT JOIN (SELECT COUNT(1) AS [WK],CharID FROM PS_GameLog.dbo.Kills WHERE DT>DATEADD(WEEK,-1,CURRENT_TIMESTAMP) AND IsKiller=0 GROUP BY CharID) WK ON WK.CharID=C.CharID
	LEFT JOIN (SELECT COUNT(1) AS [MK],CharID FROM PS_GameLog.dbo.Kills WHERE DT>DATEADD(MONTH,-1,CURRENT_TIMESTAMP) AND IsKiller=0 GROUP BY CharID) MK ON MK.CharID=C.CharID
		INNER JOIN [PS_UserData].[dbo].[Users_Master] AS [u] ON [u].[UserUID] = [c].[UserUID]
		WHERE [c].[Del] = 0 AND [u].[Status] = 0 $where
		ORDER BY $order";
		
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
		
		
		
			if ($char['K1'] >= 5000000)
			$cssicon = 67;
		else if ($char['K1'] >= 4800000)
			$cssicon = 66;
		else if ($char['K1'] >= 4600000)
			$cssicon = 65;
		else if ($char['K1'] >= 4400000)
			$cssicon = 64;
		else if ($char['K1'] >= 4200000)
			$cssicon = 63;
		else if ($char['K1'] >= 4000000)
			$cssicon = 62;
		else if ($char['K1'] >= 3800000)
			$cssicon = 61;
		else if ($char['K1'] >= 3600000)
			$cssicon = 60;
		else if ($char['K1'] >= 3400000)
			$cssicon = 59;
		else if ($char['K1'] >= 3200000)
			$cssicon = 58;
		else if ($char['K1'] >= 3000000)
			$cssicon = 57;
		else if ($char['K1'] >= 2900000)
			$cssicon = 56;
		else if ($char['K1'] >= 2800000)
			$cssicon = 55;
		else if ($char['K1'] >= 2700000)
			$cssicon = 54;
		else if ($char['K1'] >= 2600000)
			$cssicon = 53;
		else if ($char['K1'] >= 2500000)
			$cssicon = 52;
		else if ($char['K1'] >= 2400000)
			$cssicon = 51;
		else if ($char['K1'] >= 2300000)
			$cssicon = 50;	
		else if ($char['K1'] >= 2200000)
			$cssicon = 49;	
		else if ($char['K1'] >= 2100000)
			$cssicon = 48;	
		else if ($char['K1'] >= 2000000)
			$cssicon = 47;	
		else if ($char['K1'] >= 1900000)
			$cssicon = 46;	
		else if ($char['K1'] >= 1800000)
			$cssicon = 45;	
		else if ($char['K1'] >= 1700000)
			$cssicon = 44;	
		else if ($char['K1'] >= 1600000)
			$cssicon = 43;	
		else if ($char['K1'] >= 1500000)
			$cssicon = 42;	
		else if ($char['K1'] >= 1400000)
			$cssicon = 41;	
		else if ($char['K1'] >= 1300000)
			$cssicon = 40;	
		else if ($char['K1'] >= 1200000)
			$cssicon = 39;	
		else if ($char['K1'] >= 1100000)
			$cssicon = 38;	
		else if ($char['K1'] >= 1000000)
			$cssicon = 37;	
		else if ($char['K1'] >= 900000)
			$cssicon = 36;
		else if ($char['K1'] >= 850000)
			$cssicon = 35;
		else if ($char['K1'] >= 800000)
			$cssicon = 34;
		else if ($char['K1'] >= 750000)
			$cssicon = 33;
		else if ($char['K1'] >= 700000)
			$cssicon = 32;
		else if ($char['K1'] >= 650000)
			$cssicon = 31;
		else if ($char['K1'] >= 600000)
			$cssicon = 30;
		else if ($char['K1'] >= 550000)
			$cssicon = 29;
		else if ($char['K1'] >= 500000)
			$cssicon = 28;
		else if ($char['K1'] >= 450000)
			$cssicon = 27;
		else if ($char['K1'] >= 400000)
			$cssicon = 26;
		else if ($char['K1'] >= 350000)
			$cssicon = 25;
		else if ($char['K1'] >= 300000)
			$cssicon = 24;
		else if ($char['K1'] >= 250000)
			$cssicon = 23;
		else if ($char['K1'] >= 200000)
			$cssicon = 16;
		else if ($char['K1'] >= 150000)
			$cssicon = 15;
		else if ($char['K1'] >= 130000)
			$cssicon = 14;
		else if ($char['K1'] >= 110000)
			$cssicon = 13;
		else if ($char['K1'] >= 90000)
			$cssicon = 12;
		else if ($char['K1'] >= 70000)
			$cssicon = 11;
		else if ($char['K1'] >= 50000)
			$cssicon = 10;
		else if ($char['K1'] >= 40000)
			$cssicon = 9;
		else if ($char['K1'] >= 30000)
			$cssicon = 8;
		else if ($char['K1'] >= 20000)
			$cssicon = 7;
		else if ($char['K1'] >= 10000)
			$cssicon = 6;
		else if ($char['K1'] >= 5000)
			$cssicon = 5;
		else if ($char['K1'] >= 1000)
			$cssicon = 4;
		else if ($char['K1'] >= 300)
			$cssicon = 3;
		else if ($char['K1'] >= 50)
			$cssicon = 2;
		else if ($char['K1'] >= 1)
			$cssicon = 1;
		else
			$cssicon = 0;
		
		//output
		echo '<tr>';
		echo '<td class="center">'.$i.'</td>';
		echo "<td><div class='faction_icon $FactionIcon'></div></td>";
		if ($guild != false)
			echo '<td><a class="orange" style="cursor:pointer;" onmouseover=\'Tip("'.$guild->getToolTipHtml().'")\' onmouseout="UnTip()">'.$char['CharName'].'</a></td>';
		else
			echo "<td class='orange'>$char[CharName]</td>";
		echo "<td class='center'><img src='$AssetUrl/images/ranking/$cssjob.png'></td>";
		echo '<td class="center">'.$char['Level'].'</td>';
		echo '<td class="center">'.$online.'</td>';
		echo '<td class="center">' . number_format($char['K1'], 0, '.', ' ') . '</td>';
	
		
		// vicsind		
		echo "<td class='center'>$char[DK]</td>";
		echo "<td class='center'>$char[WK]</td>";
		echo '<td class="center">'.$char['OwnKill'].'</td>';
		echo '<td class="center">'.$char['K2'].'</td>';
		echo '<td><div class="rank-icon i'.$cssicon.'"></div></td>';
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