<?php

function getCraftname($craftname) {
	if (!$craftname)
		return "";
	
	$str = (int)substr($craftname, 0, 2);
	$dex = (int)substr($craftname, 2, 2);
	$rec = (int)substr($craftname, 4, 2);
	$int = (int)substr($craftname, 6, 2);
	$wis = (int)substr($craftname, 8, 2);
	$luc = (int)substr($craftname, 10, 2);
	$hp = (int)substr($craftname, 12, 2) * 100;
	$mp = (int)substr($craftname, 14, 2) * 100;
	$sp = (int)substr($craftname, 16, 2) * 100;
	$enchant = (int)substr($craftname, 18, 2) % 50;
	return "<b class='pink'>Enchant:</b> <span class='orange'>+$enchant</span>
		<br />
		<b class='yellow'>Str:</b> <span class='orange'>+$str</span>
		<b class='yellow'>Dex:</b> <span class='orange'>+$dex</span>
		<b class='yellow'>Rec:</b> <span class='orange'>+$rec</span>
		<b class='yellow'>Int:</b> <span class='orange'>+$int</span>
		<b class='yellow'>Wis:</b> <span class='orange'>+$wis</span>
		<b class='yellow'>Luc:</b> <span class='orange'>+$luc</span>
		<br />
		<b class='indianred'>HP:</b> <span class='orange'>+$hp</span>
		<b class='darkorange'>SP:</b> <span class='orange'>+$sp</span>
		<b class='blue'>MP:</b> <span class='orange'>+$mp</span>";
}


function getGems($gem1, $gem2, $gem3, $gem4, $gem5, $gem6) {
	if (!$gem1 && !$gem2 && !$gem3 && !$gem4 && !$gem5 && !$gem6)
		return "<b class='lightgreen'>Gems:</b> no ";
	return "<b class='lightgreen'>Gem1:</b> $gem1 
			<b class='lightgreen'>Gem2:</b> $gem2
			<b class='lightgreen'>Gem3:</b> $gem3
			<b class='lightgreen'>Gem4:</b> $gem4
			<b class='lightgreen'>Gem5:</b> $gem5
			<b class='lightgreen'>Gem6:</b> $gem6 ";
}
