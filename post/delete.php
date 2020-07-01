<?php
require('../dbConnect.php');
session_start();

if(isset($_SESSION['id'])){
	if(!empty($_REQUEST['id'])){
		$messages=$db->prepare('SELECT * FROM posts WHERE id=?');
		$messages->execute(array($_REQUEST['id']));
		$message=$messages->fetch();

		if($message['members_id']==$_SESSION['id']){//members_idとログイン情報のidが一致していれば削除
			$del=$db->prepare('DELETE FROM posts WHERE id=?');
			$del->execute(array($_REQUEST['id']));
		}		
	}
}else{
//未ログインの場合ログイン画面に移動
header('Location:../join/login.php');
exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
        <meta charset="UTF-8">
		<title>投稿削除</title>
		<link rel="stylesheet" href="./css/postDesign.css">
	<script type="text/javascript" src="../js/returnIndex.js"></script>
	<noscript><a href="../index.php">掲示板に戻る</noscript>
	</head>
	<body>
		<header>
			<h1>削除しました</h1>
		</header>
	</body>
</html>
