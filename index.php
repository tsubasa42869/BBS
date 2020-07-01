<?php
require('./dbconnect.php');
define('LAST_OPERATION',1800);//
define('REPLY_POST_NUMBER',0);//返信先のid

session_start();

function h($value){
		return htmlspecialchars($value,ENT_QUOTES);
}

if(isset($_SESSION['id']) && $_SESSION['time']+LAST_OPERATION > time()){//ログイン状態の確認
		$_SESSION['time']=time();//最後の操作時間を更新

		$members=$db->prepare('SELECT * FROM members WHERE id=?');
		$members->execute(array($_SESSION['id']));
		$member=$members->fetch();
}else{//１時間以内に操作がなかった場合はログイン画面に戻す
		header('Location:./post/sessionTimeOut.php');
		exit();
}


//$resultに掲示板の内容を格納
$result=mysqli_query(
		$link,
		'SELECT m.name, m.pictures, p.* FROM members m ,posts p WHERE m.id=p.members_id ORDER BY p.created DESC'
);

//$_POST['reply_post_id']が送られない
if(!empty($_POST['post'])){//投稿をDBに記録
		$message=$db->prepare('INSERT INTO posts SET members_id=?,messages=?,reply_post_id=0,created=NOW()');
		$message->execute(array(
		$member['id'],$_POST['comments']
		));
		header('Location:index.php');
		exit();
}

if(isset($_REQUEST['res'])){//返信された時の処理
		$res=$db->prepare('SELECT m.name, m.pictures, p.* FROM members m ,posts p WHERE m.id=p.members_id AND p.id=? ORDER BY p.created DESC');
		$res->execute(array($_REQUEST['res']));
		$table=$res->fetch();
		$replyMessage=$table['name'].'さんの投稿に返信@'.$table['messages'].'-> ';
}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
        <meta charset="UTF-8">
		<title>コメント入力</title>
		<link rel="stylesheet" href="./post/css/index.css">
	</head>
	<body>
		<header>
			<h1>掲示板</h1>
			<a id="logout_btn" href="./post/logout.php">ログアウト</a>
		</header>
		<section>
			<form action="" method="post">
				<textarea id="input_area" name="comments" cols="50" rows="10" placeholder="コメントをかく"
required><?php if(isset($replyMessage)):?><?php echo h($replyMessage);?><?php endif;?></textarea>
				<input type="hidden" name="reply_post_id" value="<?php echo h($_REQUEST['res']);?>"/>
					<div id="my_name"><?php if(!empty($member['name'])){echo h($member['name']);}?>さん</div>
				<input type="submit" name="post" value="投稿する">
			</form>
		</section>

		<main>
		<?php while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)):?>
			<div class="msg">
			<?php if(!empty($row['pictures'])):?>
				<img class="icon_position" src="../icon/<?php echo h($row['pictures']);?>" width="80" height="80" alt="No icons">
			<?php else:?>
				<img class="icon_position" src="../icon/default.jpg" width="80" height="80" alt="No icons">
			<?php endif;?>
				<div class="name"><?php echo h($row['name']);?>さんの投稿</div>
				<a class="day" href="./post/showAll.php?id=<?php echo h($row['id']);?>">投稿日時:<?php echo h($row['created']);?></a>
				<p class="message"><?php echo h($row['messages']);?></p>
			<div class="reply_delete_btn">
				[<a class="reply" href="index.php?res=<?php echo h($row['id']);?>">返信する</a>]
				<?php if($row['reply_post_id']>REPLY_POST_NUMBER):?>
					[<a  class="reply_post" href="./post/showAll.php?id=<?php echo h($row['reply_post_id']);?>">返信元の投稿</a>]
				<?php endif;?>
				<?php if($_SESSION['id']==$row['members_id']):?>
					[<a class="delete" href="./post/delete.php?id=<?php echo h($row['id']);?>">削除</a>]
				<?php endif;?>
			</div>
		<hr>
		<?php endwhile;?>
		</main>

		<footer>
			<p>&copy;tsubasa tanaka</p>
		</footer>
	</body>
</html>
