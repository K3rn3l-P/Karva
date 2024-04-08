<?php
// Verifica se l'utente è stato trovato
$stmt = $conn->prepare($query);
$stmt->bindParam(1, $val, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_NUM);
if ($result[0] == NULL) {
    echo "User not found";
    return;
}

// Calcola la data di inizio e fine del ban
$date = date("Y-m-d G:i");

$ban_days_map = [
    1 => ["days" => 1, "years" => 0, "text" => "1 Day"],
    2 => ["days" => 2, "years" => 0, "text" => "2 Days"],
    3 => ["days" => 3, "years" => 0, "text" => "3 Days"],
    4 => ["days" => 7, "years" => 0, "text" => "7 Days"],
    5 => ["days" => 15, "years" => 0, "text" => "15 Days"],
    6 => ["days" => 30, "years" => 0, "text" => "30 Days"],
    7 => ["days" => 0, "years" => 5, "text" => "Permanent"],
];

$ban_day = $ban_days_map[$days_ban]["days"];
$ban_year = $ban_days_map[$days_ban]["years"];
$ban_text = $ban_days_map[$days_ban]["text"];

$year = date("Y");
$month = date("m");
$day = date("d");
$hour = date("G");
$min = date("i");

$bann_begin = "$year-$month-$day $hour:$min";

$test_day = $day + $ban_day;
$test_year = $year + $ban_year;
$last_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);

if ($test_day > $last_day) {
    $bann_day = $test_day - $last_day;
    $bann_month = $month + 1;
} else {
    $bann_day = $test_day;
    $bann_month = $month;
}
if ($bann_month == 13) {
    $bann_year = $test_year + 1;
    $bann_month = 1;
} else {
    $bann_year = $test_year;
}

$bann_end = "$bann_year-$bann_month-$bann_day $hour:$min";

// Esegue le query per inserire il ban e aggiornare lo status dell'utente
$querybannDate = $conn->prepare("INSERT INTO PS_UserData.dbo.Users_Bann (UserUID, DaysBann, BanDate, Sbandate, Reason, GM_ID) VALUES (?, ?, ?, ?, ?, ?)");
$querybannDate->execute([$result[0], $ban_text, $date, $bann_end, $reason, $UserID]);

$queryBan = $conn->prepare("UPDATE PS_UserData.dbo.Users_Master SET Status = '-5', Leave = 0 WHERE UserUID= ?");
$queryBan->execute([$result[0]]);

// Registra l'azione nell'AdminLog
$log_query = $conn->prepare("INSERT INTO [PS_WebSite].[dbo].[AdminLog] ([UserUID],[UserID],[Action],[Text],[IP]) VALUES (?, ?, ?, ?, ?)");
$log_query->execute([$UserUID, $UserID, 'Block user', "UID: $result[0]; USERNAME: $result[1]", $UserIP]);

echo "$result[1] banned";
?>
