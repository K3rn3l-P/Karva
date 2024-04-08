$query = $conn->prepare("SELECT TypeID, ItemName, ReqDex FROM PS_GameDefs.dbo.Items WHERE Type=:type");
$query->bindValue(':type', 30, PDO::PARAM_INT);
$query->execute();
$Gems = array();
while ($gem = $query->fetch(PDO::FETCH_ASSOC)) {
    $typeId = $gem["TypeID"];
    $Gems[$typeId] = array("name" => $gem["ItemName"], "sort" => $gem["ReqDex"]);
}

function getCraftname($craftname) {
    if (!$craftname) {
        return "";
    }

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

function getGems($row, $Gems) {
    if ($row["Type"] == 24 || $row["Type"] == 39) {
        return "";
    }

    $outString = "";
    for ($i = 1; $i <= 6; $i++) {
        $typeId = $row["Gem$i"];
        if ($typeId) { // lapis exist
            if (array_key_exists($typeId, $Gems)) {
                $name = $Gems[$typeId]["name"];
                $sort = $Gems[$typeId]["sort"];
                $class = getSort($sort);
                $label = "<b class='$class'>$name</b>";
            } else { // lapis not founded
                $label = "Unknown";
            }
        } else { // empty slot
            $label = "Empty";
        }

        $outString .= "<span>Gem$i:</span> $label <br />";
    }
    return $outString;
}

function getSort($sort) {
    switch ($sort) {
        case 1:
            return "blue";
        case 2:
            return "dark-blue";
        case 3:
            return "lightgreen";
        case 4:
            return "yellow";
        case 5:
            return "orange";
        case 6:
            return "lightred";
        case 7:
            return "pink";
        case 8:
            return "violet";
        default:
            return "";
    }
}
