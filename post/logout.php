<?php
define('SET_PAST_TIME',3600);
session_start();

$_SESSION=array();//ssessionを全て解除
if(ini_get("session.use_cookies")){
		$params=session_get_cookie_params();
		setcookie(session_name(),'',time()-42000,
				$params['path'],$params['domain'],$params['secure'],$params['httponly']
		);
}
session_destroy();//sessionを削除

//Cookie情報も削除
setcookie('userName','',time()-SET_PAST_TIME);//有効期限を過去に設定し削除
setcookie('password','',time()-SET_PAST_TIME);//有効期限を過去に設定し削除
header('Location:logoutCheck.php');
exit();
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
        <meta charset="UTF-8">
        <title>ログアウト</title>
	</head>
	<body>
        
	</body>
</html>
