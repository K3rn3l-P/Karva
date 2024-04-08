<?php
// Get active polls
$stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.Poll WHERE Del = 0 AND (EndDate IS NULL OR CURRENT_TIMESTAMP < EndDate)");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if there are active polls
if (!$rows) {
    return;
}

// Get random active poll
$rand = array_rand($rows);
$poll = $rows[$rand];

// Get poll variants
$stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.PollVariants WHERE PollID = ?");
$stmt->execute([$poll['ID']]);
$variants = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalVotes = array_sum(array_column($variants, "Votes"));

// Check if the user has voted
$votedVariant = 0;
if ($UserUID) {
    $stmt = $conn->prepare("SELECT * FROM PS_WebSite.dbo.PollLog WHERE PollID = ? AND (UserUID = ? OR UserIP = ?)");
    $stmt->execute([$poll['ID'], $UserUID, $UserIP]);
    $playerVote = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($playerVote) {
        $votedVariant = $playerVote["VariantID"];
    }
}
?>

<style>
/* Aggiungi stili CSS qui */
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
