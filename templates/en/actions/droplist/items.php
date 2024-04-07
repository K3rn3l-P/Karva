<option value="0">Select an item</option>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

$category = isset($_POST['c']) ? $_POST['c'] : 0;
$map = isset($_POST['m']) ? $_POST['m'] : 0;
$itemID = isset($_POST['i']) ? $_POST['i'] : 0;

$gradeCondition = ' AND i.Grade not in 
(0, 30, 39, 40, 99, 335, 336, 442,  815, 999, 1115, 1116, 1117, 1118, 1119, 1120, 1121, 1122, 1123, 1171, 1172, 1181, 1182, 1192, 1198, 1200, 1201, 1202, 1203, 
1400, 1401, 1402, 1403, 1404, 1405, 1407, 1581, 1758, 1759, 1781, 1810, 1811, 1812, 1813, 1814, 1815, 2000, 2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 
2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040, 2041, 2042, 2043, 2044, 
2045, 2046, 2047, 2048, 2049, 2050, 2051, 2052, 2053, 2054, 2055, 2056, 2057, 2058, 2059, 2060, 2061, 2062, 2063, 2064, 2065, 2067, 2068, 2069, 2070, 2071, 2072, 2073, 2074, 2075, 2076, 
2077, 2078, 2079, 2080, 2081, 2082, 2083, 2084, 2085, 2086, 2087, 2088, 2089, 2090, 2091, 2092, 2093, 2094, 2095, 2096, 2097, 2098, 2099, 2100, 2101, 2138, 2139, 2140, 2141, 2142, 2143, 
2144, 2145, 2146, 2147, 2148, 2149, 2150, 2151, 2152, 2153, 2154, 2155, 2156, 2157, 2158, 2159, 2160, 2161, 2162, 2163, 2164, 2165, 2167, 2168, 2169, 2170, 2171, 2172, 2173, 2174, 2175, 
2176, 2177, 2178, 2179, 2180, 2181, 2182, 2183, 2184, 2185)';

$mapCondition = $map != 100 ? " AND mn.MapID=$map" : "";

switch ($category) {
    case 0:
        $typeCondition = '';
        break;
    case 1:
        $typeCondition = ' AND i.Type IN(16, 17, 18, 19, 20, 21, 31, 32, 33, 34, 35, 36)';
        break;
    case 2:
        $typeCondition = ' AND i.Type IN(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 69, 84)';
        break;
    case 3:
        $typeCondition = ' AND i.Type IN(30)';
        break;
    case 4:
        $typeCondition = ' AND i.Type IN(22, 23, 40)';
        break;
    case 5:
        $typeCondition = ' AND i.Type IN(24, 39)';
        break;
    case 6:
        $typeCondition = ' AND i.Type IN(42)';
        break;
    case 7:
        $typeCondition = ' AND i.Type IN(25, 38, 41, 43, 44, 100)';
        break;
    case 8:
        $typeCondition = ' AND i.Type IN(95)';
        break;
}

$search = $conn->prepare("SELECT i.ItemID, i.ItemName
			FROM PS_GameDefs.dbo.MobItems mi 
			INNER JOIN PS_GameDefs.dbo.Mobs m ON m.MobID = mi.MobID 
			INNER JOIN PS_GameDefs.dbo.MapNames mn ON m.MapID = mn.MapID
            INNER JOIN PS_GameDefs.dbo.Items i ON mi.Grade = i.Grade
			WHERE mi.MobID != 0 $gradeCondition $typeCondition $mapCondition GROUP BY i.ItemID, i.ItemName ORDER BY i.ItemName ASC");
$search->execute();
while($item = $search->fetch(PDO::FETCH_NUM)) {
    $select = $itemID == $item[0] ? 'selected' : '';
    echo "<option value='$item[0]' $select> $item[1] </option>";
}
