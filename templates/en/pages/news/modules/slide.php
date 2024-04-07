<?php 
$lightboxItems = [
	
	
	[
		"href" => "https://www.youtube.com/embed/Of5ZJ38SSKU?autoplay=1&amp;autohide=1&amp;border=0&amp;egm=0&amp;showinfo=0&amp;showsearch=0&amp;rel=0",
		"preview" => "https://i.ytimg.com/vi/Of5ZJ38SSKU/mqdefault.jpg",
		"desc" => "Shaiya Duff! 2024",
	],
	
	[
		"href" => "https://www.youtube.com/embed/CY6190cF0S4?autoplay=1&amp;autohide=1&amp;border=0&amp;egm=0&amp;showinfo=0&amp;showsearch=0&amp;rel=0",
		"preview" => "https://i.ytimg.com/vi/CY6190cF0S4/mqdefault.jpg",
		"desc" => "Shaiya Duff! - Some moments from the past weeks",
	],
	
	
	
	
	[
		"href" => "https://www.youtube.com/embed/4xMIOYRzTQ0?autoplay=1&amp;autohide=1&amp;border=0&amp;egm=0&amp;showinfo=0&amp;showsearch=0&amp;rel=0",
		"preview" => "https://i.ytimg.com/vi/4xMIOYRzTQ0/mqdefault.jpg",
		"desc" => "Shaiya Duff! - Player focused, Results driven (4K ULTRA HD)",
	],
	
	[
		"href" => "https://www.youtube.com/embed/C63zGf1zBGY?autoplay=1&amp;autohide=1&amp;border=0&amp;egm=0&amp;showinfo=0&amp;showsearch=0&amp;rel=0",
		"preview" => "https://i.ytimg.com/vi/C63zGf1zBGY/mqdefault.jpg",
		"desc" => "Shaiya Duff! - Trailer",
	],
	
	
];
?>


<!-- Carousel.Start -->
<div class="video-carousel" style="height: 10.2rem;" >
	<div class="owl-carousel owl-theme" style="position: absolute; bottom: 0;">
	
		<?php foreach ($lightboxItems as $item) : ?>
		<div class="item">
			<a href="<?= $item['href'] ?>" class="lightview" data-toggle="lightbox" data-lightview-group="mixed">
				<div class="image-thumb-preview" style="background-image:url('<?= $item['preview'] ?>'); background-position: center; background-repeat: no-repeat; background-size:98%; height: 100%; ">
					<div style=" position: absolute; width: 100%;  display: inline-block; bottom: 4px; padding: 0 7px; background-color: rgba(0,0,0,0.8);font-size: 11px;overflow: hidden;height: 25px;line-height: 25px;"><?= $item['desc'] ?></div>
				</div>
				<div class="play-button-small"></div>
			</a>
		</div>
		<?php endforeach ?>
	
	</div>
	
</div>

<style>
.item a .play-button-small {
    position: absolute;
    z-index: 999;
    width: 65px;
    height: 65px;
    top: 45px;
    left: 125px;
    margin: -32px 0 0 -32px;
    background-position: -230px -81px;
    opacity: .5;
    background-color: rgba(0, 0, 0, .7);
    border-radius: 50px;
    box-shadow: inset 0 0 0 4px rgba(0, 0, 0, 1);
    transition: all 300ms;
    -webkit-transition: all 300ms;
    -moz-transition: all 300ms;
    -o-transition: all 300ms;
}

.item a:hover .play-button-small {
  opacity: 1;
  background-color: rgba(0, 0, 0, .3);  /*box-shadow: 0 0 0 3px rgba(40, 50, 120, .7), inset 0 0 10px 2px rgba(0, 0, 0, 1);*/
}

.new_video_thumb a:hover div.image-thumb-preview {
  box-shadow: 0 0 10px rgba(40, 50, 80, .95), inset 0 0 10px rgba(85, 100, 125, .85);
}

.play-button-small {
    background-image: url(/images/media-controls.png);
    background-repeat: no-repeat;
}
</style>
<!-- Carousel.End -->

