<table>
    <tr>
        <th>QuestID</th>
        <th>Count1</th>
        <th>Count2</th>
        <th>Count3</th>
        <th>Success</th>
        <th>Finish</th>
        <th>Action</th>
    </tr>
    <?php
    $query = $conn->prepare("SELECT * FROM PS_GameData.dbo.CharQuests WHERE CharID=? AND Del=0");
    $query->bindValue(1, $CharID, PDO::PARAM_INT);
    $query->execute();

    while ($Item = $query->fetch(PDO::FETCH_ASSOC)) {
        $rowId = $Item["RowID"];
        include("item.php");
    }
    ?>
</table>
