<div class='user-block'>
    <?php
    $queryName = $conn->prepare('SELECT * FROM PS_GameData.dbo.Chars WHERE UserUID = ? AND Del = 0 ORDER BY Slot');
    $queryName->bindParam(1, $userDetails[0], PDO::PARAM_INT);
    $queryName->execute();

    while ($charDetails = $queryName->fetch(PDO::FETCH_ASSOC)) {
        // Set default values
        $grow5 = ($charDetails['Grow'] == 2) ? "Normal" : "Ultimate";
        $sex = ($charDetails['Sex'] == 0) ? "Male" : "Female";
        $family = getFamilyName($charDetails['Family']);
        $class = getClass($charDetails['Family'], $charDetails['Class']);
        $map = getMapName($charDetails['Map']);

        echo "<table id='control'>";
        echo "<tr><th>Character Details: </th></tr>";
        echo "<tr><td>CharID: </td><td>{$charDetails['CharID']}</td></tr>";
        echo "<tr><td>Char Name: </td><td>{$charDetails['CharName']}</td></tr>";
        echo "<tr><td>Status: </td><td>" . ($charDetails['Status'] == 1 ? "Deleted" : "Active") . "</td></tr>";
        echo "<tr><td>Family: </td><td>{$family}</td></tr>";
        echo "<tr><td>Class: </td><td>{$class}</td></tr>";
        echo "<tr><td>Level: </td><td>{$charDetails['Level']}</td></tr>";
        echo "<tr><td>Money: </td><td>{$charDetails['Money']}</td></tr>";
        echo "<tr><td>Map: </td><td>{$map}</td></tr>";
        echo "<tr><td>Kills: </td><td>{$charDetails['Kills']}</td></tr>";
        echo "<tr><td>Deaths: </td><td>{$charDetails['Deaths']}</td></tr>";
        echo "<tr><td>Old Char Name: </td><td>" . ($charDetails['OldName'] ?? "None") . "</td></tr>";
        echo "<tr><td>Login Status: </td><td>" . ($charDetails['LogStatus'] == 0 ? "<span style='color:#FF0000'>Offline</span>" : "<span style='color:#00FF00'>Online</span>") . "</td></tr>";
        echo "</table>";
    }

    // Display account details
    echo "<table id='control'>";
    echo "<tr><th>Account Details</th></tr>";
    echo "<tr><td>UserUID: </td><td>{$userDetails[0]}</td></tr>";
    echo "<tr><td>Account Name: </td><td>{$userDetails[1]}</td></tr>";
    if ($UserInfo["AdminLevel"] >= 255 && $userDetails[5] < 255) {
        echo "<tr><td>Password: </td><td>{$userDetails[2]}</td></tr>";
    }
    echo "<tr><td>Email: </td><td>{$userDetails[15]}</td></tr>";
    echo "<tr><td>Login Status: </td><td>" . ($userDetails[8] == 1 ? "<span style='color:#00FF00'>Online</span>" : "<span style='color:#FF0000'>Offline</span>") . "</td></tr>";
    echo "<tr><td>Banned ?!: </td><td>" . ($UserStatus == -5 ? "Yes" : "No") . "</td></tr>";
    echo "<tr><td>Shaiya Points: </td><td>{$userDetails[14]}</td></tr>";
    echo "<tr><td>Register Account Date: </td><td>{$userDetails[19]}</td></tr>";
    echo "<tr><td>Last Login Date: </td><td>{$userDetails[3]}</td></tr>";
    echo "<tr><td>IP Registration: </td><td>{$userDetails[11]}</td></tr>";
    echo "</table>";

    // Display last login details
    $queryUserLogin = $conn->prepare('SELECT * FROM PS_GameLog.dbo.UserLoginStatus WHERE UserUID = ?');
    $queryUserLogin->bindParam(1, $userDetails[0], PDO::PARAM_INT);
    $queryUserLogin->execute();
    $charUserLogin = $queryUserLogin->fetch(PDO::FETCH_ASSOC);

    echo "<table id='control'>";
    echo "<tr><th>Account Last Log</th></tr>";
    echo "<tr><td>Login: </td><td>{$charUserLogin['Login']}</td></tr>";
    echo "<tr><td>Logout: </td><td>{$charUserLogin['Logout']}</td></tr>";
    echo "<tr><td>Last Login IP: </td><td>{$charUserLogin['LastIP']}</td></tr>";
    echo "</table>";
    ?>
</div>

