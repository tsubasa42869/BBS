<?php
require('../dbConnect.php');
define('LOGIN_RETENTION_PERIOD',60*60*24);//ログイン保持期間
session_start();

function h($value){
		return htmlspecialchars($value,ENT_QUOTES);
}

if(!empty($_COOKIE['userName'])){
		$_POST['userName']=$_COOKIE['userName'];
		$_POST['password']=$_COOKIE['password'];
		$_POST['save']='on';
}

$error=false;
if(!empty($_POST['login'])){
	$login=$db->prepare('SELECT * FROM members WHERE name=?');
	$login->execute(array($_POST['userName']));
	$result=$login->fetch();
	if($result==false){//登録されていないuserNameが入力された場合
			$error=true;
	}else if(password_verify($_POST['password'],$result['password'])){//パスワードのチェック
			$_SESSION['id']=$result['id'];
			$_SESSION['time']=time();
			if($_POST['save']=='on'){//自動ログインを有効にする
				setcookie('userName',$_POST['userName'],time()+LOGIN_RETENTION_PERIOD);
				setcookie('password',$_POST['password'],time()+LOGIN_RETENTION_PERIOD);
			}
				header('Location:../index.php');
				exit();
	}else{//パスワード不一致の場合
			$error=true;
	}
}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
        <meta charset="UTF-8">
        <title>ログイン画面</title>
		<link rel="stylesheet" href="./css/joinDesign.css">
		<link rel="stylesheet" href="./css/login.css">
	</head>
	<body>
		<header>
			<h1>ログイン</h1>
		</header>	
		<div class="inputArea">
		<form action="" method="post">
			<dl>
			<div class="input_name">
				<dt>userName</dt>
					<dd>
						<input type="text" name="userName" size="35" maxlength="255" value="<?php if(!empty($_POST['userName'])){echo h($_POST['userName']);}?>" required>
					</dd>
			</div>
			<div class="input_name">
				<dt>パスワード</dt>
					<dd>
						<input type="password" name="password" size="35" maxlength="255" value="<?php if(!empty($_POST['password'])){echo h($_POST['password']);}?>" required>
							<?php if(!empty($_POST['login'])):?>
								<?php if($result==false || $error==true):?>
							<p>userNameとパスワードが一致しません</p>
								<?php endif;?>
							<?php endif;?>
					</dd>
				</div>
			</dl>
			<div id="checkbox">
				<input id="save" type="checkbox" name="save" value="on">
					<label for="save">ログイン情報を保存する</label>
			</div>
			<div id="input_btn">
				<input type="submit" name="login" value="ログイン">
				<a id="new_register" href="./index.php">新規登録</a>
			</div>
		</form>
		</div>
	</body>
</html>
