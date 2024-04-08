<?php

function parseCraftname($info) {
    if (!$info)
        return "";

    // Trova la posizione dello spazio
    $pos = strpos($info, ' ');
    if ($pos === false)
        return "";

    // Estrai le gemme dall'informazione
    $gems = substr($info, 0, $pos);

    // Estrai il craftname
    $craftname = substr($info, $pos + 1);

    // Se la lunghezza del craftname non è 20, restituisci solo le gemme
    if (strlen($craftname) != 20)
        return "<span class='lightgreen'>Gems:</span> $gems";

    // Se il craftname è lungo 20, estrai i valori e formatta la stringa
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

    // Formatta la stringa con i valori estratti
    return "<b class='pink'>Enchant:</b> <span class='orange'>+$enchant</span>
        <b class='lightgreen'>Gems:</b> $gems 
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

function parseOrange($info) {
    if (!$info)
        return "";

    // Trova la posizione dello spazio
    $pos = strpos($info, ' ');
    if ($pos === false)
        return "";

    // Estrai le gemme dall'informazione
    $gems = substr($info, 0, $pos);

    // Estrai il craftname
    $craftname = substr($info, $pos + 1);

    // Se la lunghezza del craftname non è 20, restituisci "No orange"
    if (strlen($craftname) != 20)
        return "No orange";

    // Se il craftname è lungo 20, estrai i valori e formatta la stringa
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

    // Formatta la stringa con i valori estratti
    return "<b class='red'>Str:</b> <span class='orange'>+$str</span>
        <b class='yellow'>Dex:</b> <span class='orange'>+$dex</span>
        <b class='violet'>Rec:</b> <span class='orange'>+$rec</span>
        <b class='dark-blue'>Int:</b> <span class='orange'>+$int</span>
        <b class='green'>Wis:</b> <span class='orange'>+$wis</span>
        <b class='blue'>Luc:</b> <span class='orange'>+$luc</span>
        <br />
        <b class='indianred'>HP:</b> <span class='orange'>+$hp</span>
        <b class='lightyellow'>SP:</b> <span class='orange'>+$sp</span>
        <b class='blue'>MP:</b> <span class='orange'>+$mp</span>";
}

?>
