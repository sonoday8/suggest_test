<?php
header('Content-type: application/json');

$_aMembers = [];
require_once('Utils.php');
$_sData = Utils::getDataFromFile(Utils::SAVE_FILE);
if (empty($_sData)) {
    $_sData = json_encode(Utils::getRemoteData());
    Utils::saveData(Utils::SAVE_FILE, $_sData);
}

require_once('Trie.php');
$trie = new Trie();
$_aOrgData = json_decode($_sData, TRUE);
foreach($_aOrgData as $_aRow){
    if (!empty($_aRow['roman'])) {
        $trie->add($_aRow['roman']);
        $_aMembers[$_aRow['roman']] = $_aRow['name'];
    }
}

$fw = $_GET['fw'];
$ret = $trie->search($fw);
$res = array();
foreach($ret as $k) {
if(array_key_exists($k,$_aMembers)){
$res[] = $_aMembers[$k];
}
}
echo json_encode($res);
