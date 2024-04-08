<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center"> <a>GM-Panel</a> &rarr; <i><?= $subpages[$subpage]["Title"] ?></i></span>
    </div>
    <div class="page-body border_box self_clear">

	<!-- begin content -->
	<div class="node format">
		<?php include("modules/list.php") ?>
	</div>
	<!-- end content -->

    </div>
</div>

<script>
function Rename(GuildID) {
	var Name = prompt("Input new Guild Name");
	if (!Name) return;
	window.location.href = "<?= $TemplateUrl ?>actions/gm-panel-n3w/guild-management/rename.php?id=" + GuildID + "&name=" + Name;
}

function ChangeLeader(GuildID) {
	var Name = prompt("Input Nickname of new Guild Leader");
	if (!Name) return;
	window.location.href = "<?= $TemplateUrl ?>actions/gm-panel-n3w/guild-management/changeleader.php?id=" + GuildID + "&name=" + Name;
}
</script>