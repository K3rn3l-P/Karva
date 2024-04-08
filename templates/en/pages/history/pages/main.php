
<div style="text-align:right;">
	<a class="nice_button support-button" ><?= $currencyName, ": ", $Point, " ", $currencyCode ?></a>
	<a class="nice_button nice_active support-button" href="/?p=history">PURCHASE HISTORY</a>
	<a class="nice_button support-button" href="./?p=billing">BUY SP</a>
	
</div>
 <style>
.purchase_history_text{
    color: #c53b06;
    text-align: center;
    font-size: 24px;
    padding: 10px;
    margin: 50px 0;

}
</style>        
     

<br> 
        
<?
$checkdpCount = $conn->prepare('SELECT COUNT(*) FROM PS_WebSite.dbo.Payments WHERE uid = ?');
$checkdpCount->bindValue(1, $UserUID,PDO::PARAM_INT);
$checkdpCount->execute();
$countdp = $checkdpCount->fetch(PDO::FETCH_NUM);

if ($countdp[0] == 0) {
 echo '<div class="purchase_history_text">You haven\'t purchased any Shaiya Points!</div>';
} else {
    $checkdp = $conn->prepare('SELECT Row, Ref, uid, point, method, CAST([date] AS DATE) from PS_WebSite.dbo.Payments where uid=?');
    $checkdp->bindValue(1, $UserUID,PDO::PARAM_INT);
    $checkdp->execute();
echo '<table class="nice_table" cellspacing="0" cellpadding="0" style="width: 100%;">
<tr>
	<td width="10%" align="center">Transaction ID</td>
	<td width="10%" align="center">Date</td>
	<td width="10%" align="center">Payment method</td>
	<td width="10%" align="center">Shaiya Points</td>
	<td width="10%" align="center">Payment Status</td>
</tr>';
    while ($display = $checkdp->fetch(PDO::FETCH_NUM)) {
		echo "<tr>";
		echo "<td align='center'>$display[1]</td>";
		echo "<td align='center'>$display[5]</td>";
		echo "<td align='center'>$display[4]</td>";
		echo "<td align='center'>$display[3]</td>";	
		echo "<td align='center' style='color:green'>Successful</td>";
		echo "</tr>";
    }
echo '</table>';
}
?>   