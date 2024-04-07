<?php
$result = odbc_exec($odbcConn, "SELECT * FROM PS_WebSite.dbo.Tiered_Spender WHERE CURRENT_TIMESTAMP BETWEEN StartDate AND EndDate ORDER BY [ID]");
while ($Info = odbc_fetch_array($result)) {
    $IsActive = $SpenderID == $Info["ID"] ? "active" : "";
    echo <<<HTML
    <li class='leaf'>
        <a href='?p=$page&id=$Info[ID]' title='$Info[Name]' class='pad-30-l $IsActive'>$Info[Name]
            <span class='menu_icon' style='background-image:url($AssetUrl/images/tieredspender/icons/$Info[Image])'></span>
        </a>
    </li>
HTML;
}
