<?php
require('../dbConnect.php');
session_start();

function h($value){
		return htmlspecialchars($value,ENT_QUOTES);
}

if(!isset($_SESSION['join'])){//登録内容が不足していれば登録画面へ飛ばす
		header('Location:index.php');
		exit();
}
if(!empty($_POST)){//登録処理
	$sql='INSERT INTO members SET name=?,password=?,pictures=?,created=NOW()';
	$statement=$db->prepare($sql);
	echo $ret = $statement->execute(array(
			$_SESSION['join']['userName'],
			password_hash($_SESSION['join']['password'],PASSWORD_DEFAULT),
			$_SESSION['join']['icon']
	));
	unset($_SESSION['join']);

	header('Location:signedUp.php');
	exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
        <meta charset="UTF-8">
        <title>登録確認画面</title>
		<link rel="stylesheet" href="./css/joinDesign.css">
		<link rel="stylesheet" href="./css/signUpCheck.css">
	</head>
	<body>
		<header>
			<h1>登録内容</h1>
		</header>
		<div class="inputArea">
		<form action="" method="post">
			<input type="hidden" name="action" value="submit">
				<div class="input_display">
				<dl>
					<dt>userName</dt>
						<dd><?php echo h($_SESSION['join']['userName']);?></dd> 
					<dt>password</dt>
						<dd>【表示されません】</dd> 
					<dt>icon</dt> 
						<dd>
							<img src="../icon/<?php echo h($_SESSION['join']['icon']);?>" width="100" height="100" alt="">
						</dd> 
				</dl> 
				</div>
				<div class="button">
					<a class="fix_btn" href="index.php?action=rewrite">登録内容を修正する</a>
				<div class="register_btn">
					<input type="submit" value="登録する">
				</div> 
				</div> 
		</form>
		</div>
	</body>
</html>
