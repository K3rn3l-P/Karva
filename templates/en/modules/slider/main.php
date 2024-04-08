<?php
$slides = array(
	array(
		"image" => "2.jpg",
		"desc" => "<span><b>SHAIYA Duff</b> IS A FREE TO DOWNLOAD AND FREE TO PLAY ONLINE 3D MMORPG.</span> <a href='/?p=download'>- DOWNLOAD GAME -</a>"
	),
	array(
		"image" => "3.jpg",
		"desc" => "<span><b>VOTE FOR US</b> EVERY 12 HOURS AND RECEIVE 20 FREE SP!</span> <a href='/?p=vote'>- VOTE NOW LINK -</a>"
	),
);

// Get random order of slides in any page loading. Delete it if you won't it
shuffle($slides);
?>
<!-- Slider.Start -->
<div id="slider_container" class="slider_container anti_blur" style="display:block!important">
    <div id="slider">
		<?php foreach ($slides as $slide): ?>
            <div class="slide">
                <img src="<?php echo htmlspecialchars($slide['image']); ?>" alt="Slide Image">
                <div class="slide_description"><?php echo $slide['desc']; ?></div>
            </div>
        <?php endforeach; ?>    
    </div>
</div>
<!-- Slider.End -->
