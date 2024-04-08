<table class="table-center">
    <tr>
        <th>Top</th>
        <th>Faction</th>
        <th>Name</th>
        <th>Leader</th>
        <th>Point</th>
        <th></th>
        <th></th>
    </tr>
    <?php
    $query = "SELECT * FROM PS_GameData.dbo.Guilds G
            LEFT JOIN PS_GameData.dbo.GuildDetails GD ON GD.GuildID=G.GuildID
            WHERE Del=:del ORDER BY [Rank]";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':del', 0, PDO::PARAM_INT);
    $stmt->execute();
    while ($Guild = $stmt->fetch(PDO::FETCH_ASSOC))  {
        include("Guild.php");
    }
    ?>
</table>
