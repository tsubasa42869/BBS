<?php
require('../dbConnect.php');
session_start();

function h($value){
		return htmlspecialchars($value,ENT_QUOTES);
}

if(empty($_REQUEST['id'])){
		header('Location:../index.php');
		exit();
}

//投稿を取得する
$posts=$db->prepare('SELECT m.name, m.pictures, p.* FROM members m,posts p WHERE m.id=p.members_id AND p.id=? ORDER BY p.created DESC');
$posts->execute(array($_REQUEST['id']));
?>

<!DOCTYPE html>
<html lang="en">
	<head>
        <meta charset="UTF-8">
		<title>メッセージ表示</title>
		<link rel="stylesheet" href="./css/showAll.css">
	</head>
	<body>
		<header>
			<h1>全文表示</h1>
		</header>
		<main>
			<?php if($post=$posts->fetch()):?>
				<?php if(!empty($post['pictures'])):?>
					<img class="icon_position" src="../icon/<?php echo h($post['pictures']);?>" width="100" height="100" alt="No icons">
				<?php else:?>
					<img class="icon_position" src="../icon/default.jpg?>" width="100" height="100" alt="No icons">
				<?php endif;?>

				<div id="day"><p>投稿日時:<?php echo ($post['created']);?></p></div>
				<div id="name">投稿者:<?php echo h($post['name']);?></p></div>
				<div class="message"><p><?php echo h($post['messages']);?></div>
			<?php else:?>
				<div class="message"><p>コメントが見つかりませんでした。その投稿は削除された可能性があります</p></div>
			<?php endif;?>
			<a id="returnbbs" href="../index.php">&rang; 一覧に戻る</a>
		</main>
	</body>
</html>
