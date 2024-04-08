
<?php include_once("$PageUrl/pages/$subpage/right.php") ?>
<ul class="right_menu">
	<?php 
	foreach ($subpages as $name => $info) {
		// Have access
		if ($info["AdminLevel"] <= $UserInfo["AdminLevel"])
			echo "<li><a href='/?p=gm-panel-n3w&sp=$name'>$info[Title]</a></li>";
	}
	?>
</ul>
