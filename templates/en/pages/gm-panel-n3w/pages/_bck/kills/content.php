<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> 
            <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i>
        </span>
    </div>
    
    <div class="page-body border_box self_clear">
        <!-- begin content -->
        <style>
            #control {
                border: 1px solid #000;
                float: left;
                margin-left: 10px;
                font-size: 10px;
                margin-top: 10px;
            }

            #control th, #control td {
                border: 1px solid #000;
                padding: 5px;
                font-weight: 600;
            }
        </style>

        <table id='control'>
            <tr>
                <th>Killer</th>
                <th>Death</th>
                <th>MapName</th>
                <th>Pos X</th>
                <th>Pos Z</th>
                <th>ActionTime</th>
            </tr>
            <?php
            $query = $conn->prepare('SELECT TOP 1000 CharName, KillerCharName, MapID, PosX, PosZ, ActionTime FROM PS_GameLog.dbo.Death_log ORDER BY ActionTime DESC');
            $query->execute();

            while ($kills = $query->fetch(PDO::FETCH_ASSOC)) {
                // Map
                $mapId = $kills['MapID'];
                $mapName = getMapName($mapId);
                $killDate = date("Y-m-d H:i:s", strtotime($kills['ActionTime']));

                echo '<tr><td>' . $kills['KillerCharName'] . '</td><td>' . $kills['CharName'] . '</td><td>' . $mapName . '</td><td>' . $kills['PosX'] . '</td><td>' . $kills['PosZ'] . '</td><td>' . $killDate . '</td></tr>';
            }
            ?>
        </table>
        <!-- end content -->	
    </div>
</div>
