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
    $stmt = $conn->prepare("SELECT G.*, GD.* FROM PS_GameData.dbo.Guilds G
        LEFT JOIN PS_GameData.dbo.GuildDetails GD ON GD.GuildID = G.GuildID
        WHERE G.Del = 0 ORDER BY G.[Rank]");
    $stmt->execute();
    while ($guild = $stmt->fetch(PDO::FETCH_ASSOC)) {
        include("Guild.php");
    }
    ?>
</table>
