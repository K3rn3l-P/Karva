function showRewards(id) {
	$(".current").removeClass("current");
	$("#reward-block-" + id).addClass("current");
	$("#reward-link-" + id).addClass("current");
}