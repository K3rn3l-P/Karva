<article id="post-<?= $item["Row"] ?>" class="post expandable collapsed">
	<div class="post-inner">
		<div class="post-left" style="background-image: url(<?= "$AssetUrl/images/articles/$item[Image]" ?>);"></div>

		<div class="post-right border_box">
			<div class="post_header">
				<h2 class="meta_info post_title overflow_ellipsis">
					<a><?= $item["Title"] ?></a>
				</h2>
				<span class="meta_info post_author overflow_ellipsis">
					 <a style="color:#00ae16"><?= $item["Category"] ?></a> 
					 <span class="post_date"> - <?= $newsDate ?></span>
				</span>
				<a href="javascript:void(0)" onClick="toggleView(this, 'post-<?= $item["Row"] ?>')"
				   class="nice_button meta_info post_readmore overflow_ellipsis vertical_center align_center">
					<span class="rm" >Read more</span>
					<span class="rl" style="display:none">Read less</span>
				</a>
			</div>
		</div>
	</div>

	<div class="post-bottom border_box" style="display:none">
		<div class="post_header" style="text-align: right; margin:10px;">
		<?php if ($IsStaff) : ?>
              <a class="nice_button news-button" href="/?p=news&e=<?= $item["Row"] ?>">EDIT</a>
              <a class="nice_button news-button" href="<?= $TemplateUrl ?>actions/news/delete-news.php?r=<?= $item["Row"] ?>">DELETE</a>
        <?php endif ?>
		</div>
		<div class="post_body">
			<div class="post_content self_clear">
				<div class="markup-2BOw-j">
					<span style="font-size: medium;"><?= $item["Text"] ?></span>
				</div>
			</div>
		</div>
	</div>
</article>