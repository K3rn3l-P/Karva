<article id="post-<?= $item["Row"] ?>" class="mainbox post">
    <div class="mainbox-inner post-inner">
        <div class="mainbox-header post-header border_box">
            <h2 class="post_info post_title overflow_ellipsis"><a><?= $item["Title"] ?></a></h2>
            <span class="post_info post_date"><?= $newsDate ?></span>
        </div>
        <div class="mainbox-body post-body">
			<div class="post_header" style="text-align: right; margin:10px;">
			<?php if ($IsStaff) : ?>
				  <a class="nice_button news-button" href="/?p=news&e=<?= $item["Row"] ?>">EDIT</a>
				  <a class="nice_button news-button" href="<?= $TemplateUrl ?>actions/news/delete-news.php?r=<?= $item["Row"] ?>">DELETE</a>
			<?php endif ?>
			</div>
			<div class="mainbox-body post-body">
				<div class="post_content self_clear">
					<?= $item["Text"] ?>
				</div>
			</div>
        </div>
    </div>
</article>