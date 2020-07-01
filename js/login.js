//２秒後に自動的にログイン画面へ戻る
function autoLink()
{
location.href="../join/login.php";
}
setTimeout("autoLink()",2000);
