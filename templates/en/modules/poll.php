<?php
// Get active polls
$stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.Poll WHERE Del=0 AND (EndDate IS NULL OR CURRENT_TIMESTAMP < EndDate)");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
// There are no active polls
if (!$rows)
	return;

// Get random active poll
$rand = array_rand($rows);
$poll = $rows[$rand];
//var_dump($poll);

// Get poll variants
$stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.PollVariants WHERE PollID=$poll[ID]");
$stmt->execute();
$variants = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalVotes = array_sum(array_column($variants, "Votes"));

$votedVariant = 0;
if ($UserUID) {
	$stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.PollLog WHERE PollID=$poll[ID] AND (UserUID=? OR UserIP=?)");
	$stmt->bindValue(1, $UserUID, PDO::PARAM_INT);
	$stmt->bindValue(2, $UserIP);
	$stmt->execute();
	$playerVote = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($playerVote)
		$votedVariant = $playerVote["VariantID"];
}

?>
<style>
.content-poll .title,
.poll .title{border-width:0 0 1px;padding:10px;}
.poll .vote-result,
.poll .form-item{border-width:0 0 1px;padding:6px 10px;margin:0;clear:both;}

.poll label{padding:4px 0 4px 20px;display:inline;}
.poll .form-radio{margin:0 0 0 -17px;cursor:pointer; vertical-align: middle;}
.poll .form-submit{margin:12px auto 0 auto;display:block;min-width:50px;}
.poll .total{margin:15px 0 0;text-align:center;}

.poll .bar{width:70%;height:16px;width:70%;margin:8px 0 10px;border-style:solid;border-width:1px;overflow:hidden;}
.poll .percentage{height:16px;text-align:right;}
.poll .percentage{color:#fff;background:#852b0d;}
.poll .foreground{display:none;}
.poll .votes{width:30%;height:16px;float:right;display:inline;text-align:right;margin:-29px 0 0;}
.vote-result .text{color:#fff4d8;}
.sidebox .title{color:#FFF; font-size: 13px;}

</style>

<?php if ($votedVariant) : ?>
	<section id="sidebox_status" class="sidebox">
		<h4 class="sidebox_title border_box">
			<i>Latest Poll</i>
		</h4>
		<div class="sidebox_body border_box">
		
			<div class="poll">
				<div class="title"><?= $poll["Title"] ?></div>
				
				<?php foreach ($variants as $variant) : ?>
					<?php $percent = round($variant["Votes"] / $totalVotes * 100, 1) ?>
					<div class="vote-result">				
						<div class="text"><?= $variant["Title"] ?></div>						
						<div class="bar">
							<div style="width: <?= $percent ?>%;" class="foreground"></div>
							<div style="width: <?= $percent ?>%;" class="percentage">&nbsp;&nbsp;<?= $percent ?>% &nbsp;&nbsp;</div>
						</div>
						<div class="votes"><?= $variant["Votes"] ?> votes</div>
					</div>            
				<?php endforeach ?>
				
				<div class="total">Total votes: <?= $totalVotes ?></div>
			</div>
		</div>
	</section>
<?php else : ?>
	<section id="sidebox_status" class="sidebox">
		<h4 class="sidebox_title border_box">
			<i>Latest Poll</i>
		</h4>
		<div class="sidebox_body border_box">
		
			<div class="poll">
				<form action="<?= $TemplateUrl ?>actions/poll.php" method="post" id="poll_view_voting">
					<input type="hidden" name="poll" value="<?= $poll["ID"] ?>">
				
					<div class="title"><?= $poll["Title"] ?></div>
					
					<?php foreach ($variants as $variant) : ?>
					<div class="form-item" id="form-item-edit-choice">                    
						<label class="option">
							<input type="radio" name="variant" value="<?= $variant["ID"] ?>" class="form-radio" id="variant-<?= $variant["ID"] ?>" />
							<label for="variant-<?= $variant["ID"] ?>"><?= $variant["Title"] ?></label>
						</label>
					</div>
					<?php endforeach ?>
					
					<input type="submit" name="op" value="Vote" class="form-submit" />
				</form>
			</div>
		</div>
	</section>
<?php endif ?>