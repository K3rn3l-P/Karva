<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

		<!-- begin content -->
		<style>
			/* Tables */
			table {
				border-collapse: collapse;
				padding: 0;
				margin: 0 auto;
				border: solid 1px #e87308;
			}

			td {
				border: solid 1px #653508;
			}

			td .form-item {
				margin: 5px 0;
			}

			table th {
				font-size: 12px;
				font-weight: bold;
				background-color: #e87308;;
				text-align: left;
				padding: 7px 10px;
				border: solid 1px #e87308;
				font-family: Tahoma, Geneva;

				background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#2E2E2E), to(#1D1D1D));
				filter: Progid:DXImageTransform.Microsoft.gradient(startColorstr=#2E2E2E, endColorstr=#1D1D1D)
			}

			border {
				color: red;
			}
		</style>

	
More commands will be posted later! i sleep now :)
	
		
		
<table id='control' style='width:600px;'>
<tr>	
<th>Command</th>
<th>Functionality</th>
<th>Permission</th>			
</tr>
		
		
<tr>
<td>/char on</td>
<td>character becomes invisible</td>
<td>GM/GMA</td>	
</tr>

<tr>
<td>/char off</td>
<td>character becomes visible</td>
<td>GM/GMA</td>	
</tr>		



<tr>
<td>/attack on</td>
<td>character cannot be attacked</td>
<td>GM/GMA</td>	
</tr>	

<tr>
<td>/attack off</td>
<td>character can be attacked</td>
<td>GM/GMA</td>	
</tr>

<tr>
<td>/amove PlayerName</td>
<td>you teleport to the player</td>
<td>GM/GMA</td>	
</tr>

<tr>
<td>/asummon PlayerName</td>
<td>player is teleported to you</td>
<td>GM/GMA</td>	
</tr>

<tr>
<td>/bsummon PlayerName [X Coords] [Z Coords] [MapID]</td>
<td>this moves a player to the coords you enter on the map</td>
<td>GM/GMA</td>	
</tr>




<tr>
<td>/cmove MapID</td>
<td>you teleport to the map. <br>Click <a href="/?p=gm-panel-n3w&sp=info4" >here</a> for MapID list</td>
<td>GM/GMA</td>	
</tr>

<tr>
<td>/bmove [X Coords] [Z Coords] [MapID]</td>
<td>this moves you to the coords map you enter</td>
<td>GM/GMA</td>	
</tr>



<tr>
<td>/getitem itemID</td>
<td>you receive item</td>
<td>ADM</td>	
</tr>


<tr>
<td>/mmake MobID</td>
<td>create monster near you. <br>Click <a href="/?p=gm-panel-n3w&sp=info2" >here</a> for MobID list</td>
<td>GM</td>	
</tr>


<tr>
<td>/mera t</td>
<td>delete selected monster</td>
<td>GM</td>	
</tr>


<tr>
<td>/nmake npcID</td>
<td>create NPC near you. Click <a href="/?p=gm-panel-n3w&sp=info3" >here</a> for NPC list</td>
<td>GM</td>	
</tr>


<tr>
<td>/nera t</td>
<td>delete selected NPC</td>
<td>GM</td>	
</tr>



<tr>
<td>/notice "text</td>
<td>send a global message </td>
<td>GM/GMA</td>	
</tr>



<tr>
<td>/notice "text</td>
<td>send a global message </td>
<td>GM/GMA</td>	
</tr>



<tr>
<td>/znotice "text</td>
<td>send a message on current map only </td>
<td>GM/GMA</td>	
</tr>

<tr>
<td>/bnotice "text</td>
<td>??? </td>
<td>GM/GMA</td>	
</tr>


<tr>
<td>/wnotice PlayerName "text</td>
<td>send a message like a whisper but in notice form </td>
<td>GM/GMA</td>	
</tr>



<tr>
<td>/gmnotice "text</td>
<td>send a message to GM/GMA only </td>
<td>GM/GMA</td>	
</tr>


<tr>
<td>/cnotice "text</td>
<td>send a message to Current Faction only </td>
<td>GM/GMA</td>	
</tr>




<tr>
<td>/watch PlayerName</td>
<td>locate a player</td>
<td>GM/GMA</td>	
</tr>


<tr>
<td>/warning PlayerName "text</td>
<td>send a warning message to player </td>
<td>GM/GMA</td>	
</tr>


<tr>
<td>/kick PlayerName</td>
<td>kick out of game the player</td>
<td>GM/GMA</td>	
</tr>





<tr>
<td>/stopon PlayerName</td>
<td>player can't move anymore</td>
<td>GM/GMA</td>	
</tr>

<tr>
<td>/stopoff PlayerName</td>
<td>player can move</td>
<td>GM/GMA</td>	
</tr>


<tr>
<td>/silence on PlayerName</td>
<td>player can't talk anymore</td>
<td>GM/GMA</td>	
</tr>

<tr>
<td>/silence off PlayerName</td>
<td>player can talk</td>
<td>GM/GMA</td>	
</tr>




<tr>
<td>/xcall 1 or 2</td>
<td>Light is 1, Fury is 2. This will summon all of one faction to your currently location.</td>
<td>GM/GMA</td>	
</tr>


<tr>
<td>/auctionsearch PlayerName</td>
<td>auction Item search of a player</td>
<td>GM/GMA</td>	
</tr>





<tr>
<td>/auctionrecall PlayerName</td>
<td>get market Items of a player</td>
<td>ADM</td>	
</tr>




<tr>
<td>/eclear PlayerName</td>
<td>delete all equipped Items of a player</td>
<td>ADM</td>	
</tr>



<tr>
<td>/iclear PlayerName</td>
<td>delete all inventory Items of a player</td>
<td>ADM</td>	
</tr>



<tr>
<td>/event off PlayerName</td>
<td>remove all buffs from the player</td>
<td>GM</td>	
</tr>




<tr>
<td>/event on PlayerName</td>
<td>auto heal the player</td>
<td>GM</td>	
</tr>

<tr>
<td>/cure PlayerName</td>
<td>heal the player </td>
<td>GM</td>	
</tr>


<tr>
<td>/quiry PlayerName</td>
<td>check player info </td>
<td>GM</td>	
</tr>











</table>	
	
	
	

	
	
	
	
	
	
	
	
	
		<!-- end content -->	

    </div>
</div>