<?php
header("Content-type: text/html; charset=utf-8");
require_once('Utils.php');
$_aData = [];
$_sData = Utils::getDataFromFile(Utils::SAVE_FILE);
if (empty($_sData)) {
    $_sData = json_encode(Utils::getRemoteData());
    Utils::saveData(Utils::SAVE_FILE, $_sData);
}
$_aData = json_decode($_sData,TRUE);
?>
<!DOCTYPE html>
<html lang=”ja”>
<head>
<meta charset=”UTF-8″>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script language="javascript">
$(document).ready( function() {
$( "#fw" ).autocomplete({
        source: function( req, res ) {
            $.ajax({
                url: "http://localhost:8000/members.php?fw=" + encodeURIComponent(req.term),
                dataType: "json",
                success: function( data ) {
                    res(data);
                }
            });
        },
        autoFocus: true,
        delay: 300,
        minLength: 1
});
});
</script>
</head>
<body>
<p><input type="text" id="fw"></p>
<p>サジェストのテストです。</p>
<p>楽天ゴールデンイーグルスの投手名をローマ字でサジェスト候補が出ます。</p>
<p>漢字からローマ字への変換は、Yahoo APIの「ルビ振り」を使っています。http://developer.yahoo.co.jp/webapi/jlp/furigana/v1/furigana.html</p>
<table>
    <tr><th>名前</th><th>ローマ字</th></tr>
    <?php foreach($_aData as $_aRow) { ?>
    <tr><td><?php echo $_aRow['name'];?></td><td><?php echo $_aRow['roman'];?></td></tr>
    <?php } ?>
</body>
</html>