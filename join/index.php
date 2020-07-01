<?php
require('../dbConnect.php');
define("NAME_DUPLICATE_CONFIRMATION",0);
define("PASSWORD_LENGTH",9);
define("EXTENTION_CHECK",-3);
session_start();//セッション開始

function h($value){//無害化関数の省略
		return htmlspecialchars($value,ENT_QUOTES);
}

if(!empty($_POST['signUp'])){//入力されている場合以下の処理を実行
	$sql='SELECT COUNT(*) AS cnt FROM members WHERE name=?';
	$duplicateUserNameCheck=$db->prepare($sql);
	$duplicateUserNameCheck->execute(array($_POST['userName']));
	$record=$duplicateUserNameCheck->fetch();
	if($record['cnt'] > NAME_DUPLICATE_CONFIRMATION){
			$error['userName']='duplicate';
	}
	
	if(strlen($_POST['password']) < PASSWORD_LENGTH){//パスワードが9文字未満の場合、error配列に'lenght'を格納
				$error['password']='length';
	}
	$fileName=basename($_FILES['icon']['name']);//ファイルシステムトラバーサル防止
	if(!empty($fileName)){//iconが指定されていた場合
		$image=date('YmdHis').$_FILES['icon']['name'];
		move_uploaded_file($_FILES['icon']['tmp_name'],'../icon/'.$image);
		$_SESSION['join']=$_POST;
		$_SESSION['join']['icon']=$image;
		$extention=substr($fileName,EXTENTION_CHECK);//末尾の3文字目の拡張子をチェック
		if($extention !='jpg' && $extention !='png'){//jpg png以外はエラーとする
			$error['icon']='extention';
		}
	}else{
		$_SESSION['join']=$_POST;
		$_SESSION['join']['icon']='';
	}

	if(empty($error)){//入力時のエラーがなければ、セッション配列に入力内容を格納し確認画面へ移行
		header('Location:signUpCheck.php');
		exit();
	}
}
if(empty($_REQUEST['action'])){
		$_REQUEST['action']='';
}else if($_REQUEST['action']=='rewrite'){//書き直し
		$_POST=$_SESSION['join'];//現在の$_POSTに書き直し前のセッション情報を格納
		$error['rewrite']=true;
}

?>

<!DOCTYPE html>
<html lang="ja">
	<head>
    	<meta charset="UTF-8">
		<title>会員登録画面</title>
		<link rel="stylesheet" href="./css/joinDesign.css">
		<link rel="stylesheet" href="./css/index.css">
	</head>
	<body>
		<header>
			<h1>新規登録</h1>
		</header>
		<form action="" method="post" enctype="multipart/form-data">
			<div class="inputArea">
			<dl>
				<div class="input_name">
					<dt>userName</dt>
						<dd><input type="text" name="userName" size="30" maxlength="255" value="<?php if(!empty($_POST['userName'])){ echo h($_POST['userName']);}?>" required>
						</dd> 
						<p class="error"><?php if(!empty($error['userName'])):?><?php if($error['userName']=='duplicate'):?>
						※指定したuserNameは既に登録されています<?php endif;?><?php endif;?>
						</p>
				</div>
				<div class="input_name">
					<dt>password</dt>
						<dd><input type="password" name="password" size="30" maxlength="20" value="<?php if(!empty($_POST['password'])){ echo h($_POST['password']);}?>" required>
						</dd> 
						<p class="error"><?php if(!empty($error['password'])):?><?php if($error['password']=='length'):?>
						※パスワードは8文字以上で入力してください<?php endif;?><?php endif;?>
						</p>
				</div>
				<div class="input_name">
					<dt>icon</dt>
						<dd><input type="file" name="icon" size="35"></dd>
						<p class="error"><?php if(!empty($error['icon'])):?><?php if($error['icon']=='extention'):?>
						写真の形式は「.jpg」か「.png」で指定してください<?php endif;?><?php endif;?>
						</p>
				</div>
			</dl>
			<div id="input_btn"><input type="submit" name="signUp" value="入力内容を確認する"></div>
			</div>
			</div>
		</form>
	</body>
</html>
