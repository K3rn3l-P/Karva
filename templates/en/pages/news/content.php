<?php include("modules/slide.php") ?>

<div class="powr-countdown-timer" id="629938b2_1641224691"></div><script src="https://www.powr.io/powr.js?platform=html"></script>

<div class="content_header border_box">
    <span class="latest_news vertical_center"> Latest news</span>
</div>

<?php if ($IsStaff): ?>
    <script src="<?= $AssetUrl ?>js/ckeditor/ckeditor.js"></script>
    <?php
    $i = 0;
    $title = '';
    $text = '';
    $category = "";
    $image = "";
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
    ?>
    <form id="addnews" action="<?= $TemplateUrl ?>actions/news/<?= $action ?>-news.php" method="post" style="margin: 20px; text-align: right;">
        <input type="text" placeholder="Title of news (Don't make title longer than 250 characters)"
               value="<?= $title ?>" required="" name="title" style="margin: 10px 0 0 0; width: 100%;">
        <input type="text" placeholder="Category"
               value="<?= $category ?>" required="" name="category" style="margin: 5px 0 0 0; width: 100%;">
        <input type="text" placeholder="Image"
               value="<?= $image ?>" required="" name="image" style="margin: 5px 0 5px 0; width: 100%;">
        <textarea cols="80" id="editor1" name="editor1" rows="10"><?= $text ?></textarea>
        <input type="hidden" value="<?= $i ?>" name="new">
        <input class="nice_button" type="submit" value="<?= $action ?>" style="margin: 10px 0; width: 120px;"></input>
    </form>
    <script>CKEDITOR.replace('editor1');</script>
<?php endif; ?>

<?php 
// Ottieni il numero totale di notizie
$queryCount = $conn->prepare('SELECT COUNT(1) AS Cnt FROM PS_WebSite.dbo.News' . $lang . ' WHERE Del = 0');
$queryCount->execute();
$maxCount = $queryCount->fetchColumn();

// Imposta il conteggio e l'offset per la paginazione
$count = 12; // Numero di notizie per pagina
$offset = isset($_GET["offset"]) && is_numeric($_GET["offset"]) ? $_GET["offset"] : 1;
$queryNews = $conn->prepare("SELECT TOP $count * FROM (SELECT *, ROW_NUMBER() OVER (ORDER BY Date DESC) AS RowID FROM PS_WebSite.dbo.News" . $lang . " WHERE Del = 0) t WHERE RowID >= ?");
$queryNews->bindValue(1, $offset, PDO::PARAM_INT);
$queryNews->execute();
$data = $queryNews->fetchAll();


foreach ($data as $item) {
    $newsDate = date_format(date_create($item["Date"]), 'd M Y');
    include("modules/news-item.php");
}
?>

<div class="news_pagi border_box self_clear">                                        
    <div class="news_pagi-right">
        <?php 
        // Paginazione
        if ($offset > 1) {
            $newer = $offset > $count ? $offset - $count : 1;
            echo "<a class='nice_button' href='/?p=news&offset=$newer'>← Newer posts</a>";
        }
        if ($offset + $count <= $maxCount) {
            $older = $offset + $count;
            echo "<a class='nice_button' href='/?p=news&offset=$older'>Older posts →</a>";
        }
        ?>
    </div>
</div>

<script>
$('.owl-carousel').owlCarousel({
    loop:true,
    margin: 3,
    items: 3,
    dots: false,
    loop: false,
})
</script>
