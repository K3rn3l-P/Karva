<div class="page">
    <div class="content_header border_box">
        <span class="latest_news vertical_center">Download Game</span>
    </div>
    <div class="page-body border_box self_clear">
        <div style="margin: 50px auto; width: 300px; text-align: center;">
            <br>
            Select the server:
            <select id="lang" onchange="updateLink()">
                <option selected value="en">Shaiya Duff</option>
            </select>
            <br>
            <br>
            Select the platform from where you want download:
            <select id="method" onchange="updateLink()">
                <option selected value="mega">Mega</option>
                <option value="drive">Google Drive</option>
                <option value="mediafire">Media Fire</option>
            </select>
            <br>
            <br>
            <span style="color:green">Game is available for download!</span>
            <a id="link" class="nice_button" style="font-size: 13px;" title="Download Shaiya Duff" href="">DOWNLOAD SHAIYA Duff</a>
            <br><br>
        </div>
        <br>
        <p><b>NOTE:</b></p>
        <p><span style="color:#99ccff">-</span> Download <a href="https://www.win-rar.com/download.html?&L=0" target="_blank">WinRar Tool</a> or <a href="https://www.7-zip.org/" target="_blank">7-Zip Tool</a> to extract the Game.</p>
    </div>
</div>
<script>
    function updateLink() {
        var lang = $("#lang").val();
        var method = $("#method").val();
        var js_data = <?= json_encode($DownloadLinks) ?>;
        var link = js_data[lang][method];
        $("#link").attr("href", link);
    }
    updateLink();
</script>
