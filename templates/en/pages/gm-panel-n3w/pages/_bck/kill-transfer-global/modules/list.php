<table class="table-center">
    <tr>
        <th>CharID</th>
        <th>CharName</th>
        <th>Job</th>
        <th>Kills</th>
        <th>Deaths</th>
        <th></th>
    </tr>
    <?php foreach ($Chars as $Char): ?>
        <?php include("char.php"); ?>
    <?php endforeach; ?>
</table>
