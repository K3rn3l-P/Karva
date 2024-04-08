function ShowItems(RewardID) {
	ItemsDiv = document.getElementById("redeem-" + RewardID);
	ItemsDiv.style.display = "block";
}
function HideItems(RewardID) {
	ItemsDiv = document.getElementById("redeem-" + RewardID);
	ItemsDiv.style.display = "none";
}