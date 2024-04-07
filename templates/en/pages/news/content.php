<?php 
include("modules/slide.php");

echo '<div class="powr-countdown-timer" id="629938b2_1641224691"></div>
<script src="https://www.powr.io/powr.js?platform=html"></script>

<div class="content_header border_box">
    <span class="latest_news vertical_center"> Latest news</span>
</div>';

if ($IsStaff) {
    echo '<script src="' . $AssetUrl . 'js/ckeditor/ckeditor.js"></script>';
    
    $i = 0;
    $title = '';
    $text = '';
    $category = '';
    $image = '';
    $action = 'create';

    if (isset($_GET['e'])) {
        $i = $_GET['e'];
        $qNE = $conn->prepare("SELECT * FROM PS_WebSite.dbo.News$lang WHERE Row = ?");
        $qNE->bindParam(1, $i, PDO::PARAM_INT);
        $qNE->execute();
        $rEN = $qNE->fetch(PDO::FETCH_NUM);
        $title = $rEN[1];
        $text = $rEN[2];
        $category = $rEN[5];
        $image = $rEN[4];
        $action = 'edit';
    }

    echo '<form id="addnews" action="' . $TemplateUrl . 'actions/news/' . $action . '-news.php" method="post" style="margin: 20px; text-align: right;">
        <input type="text" placeholder="Title of news (Don\'t make title longer than 250 characters)"
               value="' . $title . '" required="" name="title" style="margin: 10px 0 0 0; width: 100%;">
        <input type="text" placeholder="Category"
               value="' . $category . '" required="" name="category" style="margin: 5px 0 0 0; width: 100%;">
        <input type="text" placeholder="Image"
               value="' . $image . '" required="" name="image" style="margin: 5px 0 5px 0; width: 100%;">
        <textarea cols="80" id="editor1" name="editor1" rows="10">' . $text . '</textarea>
        <input type="hidden" value="' . $i . '" name="new">
        <input class="nice_button" type="submit" value="' . $action . '" style="margin: 10px 0; width: 120px;"></input>
    </form>
    <script>CKEDITOR.replace(\'editor1\');</script>';
}

$odbcResult = odbc_exec($odbcConn, "SELECT COUNT(1) AS Cnt FROM PS_WebSite.dbo.News$lang WHERE Del=0");
$maxCount = odbc_result($odbcResult, "Cnt");

// Get news
$count = 12; // Count of news
$offset = isset($_GET["offset"]) && is_numeric($_GET["offset"]) ? $_GET["offset"] : 1;
$query = "SELECT TOP $count * FROM 
        (SELECT *, ROW_NUMBER() OVER (ORDER BY Date DESC) AS RowID FROM PS_WebSite.dbo.News$lang WHERE Del=0) t
        WHERE RowID >= $offset";

$command = $conn->prepare($query);
$command->execute();
$data = $command->fetchAll();

foreach ($data as $item) {
    $newsDate = date_format(date_create($item["Date"]), 'd M Y');
    include("modules/news-item.php");
}

echo '<div class="news_pagi border_box self_clear">                                        
    <div class="news_pagi-right">';
        
// Pagination
if ($offset > 1) {
    $newer = $offset > $count ? $offset - $count : 1;
    echo "<a class='nice_button' href='/?p=news&offset=$newer'>← Newer posts</a>";
}
if ($offset + $count <= $maxCount) {
    $older = $offset + $count;
    echo "<a class='nice_button' href='/?p=news&offset=$older'>Older posts →</a>";
}

echo '</div>
</div>

<script>
$(".owl-carousel").owlCarousel({
    loop:true,
    margin: 3,
    items: 3,
    dots: false,
    loop: false,
});
</script>';
?>
