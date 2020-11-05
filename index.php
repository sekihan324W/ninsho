<?php 	
session_start();
// 	ファイル一つで入力と照合を行うパターン
	require_once('connect.php');
	// 1. POST値があるかどうか

	if(!empty($_POST['user_email']) && !empty($_POST['user_password'] ) && !empty($_POST['himitsu']) && $_SESSION['himitsu'] == $_POST['himitsu'] ){
		// ・あれば 2. DB接続 → メールで絞り込み
			$sql="select email, user_password from $table_name where email = ?";
			 $stmh = $dbh->prepare($sql);
			 $stmh->bindValue(1,$_POST['user_email'],PDO::PARAM_STR);
			 $stmh->execute(); 

				 if( $stmh->rowCount()>0 ){
					// ・メールがある	
					$result = $stmh->fetchAll();  // 2次元配列へ
							//パスワードがハッシュにマッチするか
		
					 		if(password_verify($_POST['user_password'], $result[0]['user_password'])){
									// ・する → ほんとうは限定サイトにリダイレクト
					 				echo "wellcome.php の表示";

					 		}else{
									// ・しない
								viewform("パスワードが違います");
					 		}
			 		}else{  // メールがない
						viewform("入力されたメールがありません");
			 		}
	}else{
			// postされていない・なければ(1) と同じ
			 	viewform();
  }

		// ・なければ(1) → 関数にする
	function viewform( $message="" ){
			if( !empty($message) )	echo "<h3>$message</h3>";
			$_SESSION['himitsu'] = token();
?>
<link rel="stylesheet" href="style.css">
 <h3>ログインしてください</h3>
<form method="post">
	<p>メールアドレス: <input type="text"name="user_email">
	<p>パスワード: <input type="password"name="user_password">
	<!-- . 秘密のトークンを設置する	 -->
	  <input type="hidden" name="himitsu" value="<?=$_SESSION['himitsu']?>">
	<p>  <input type="submit" value="ログイン">
</form>
<a href="new_member.php">新規会員登録</a>
<?php			
		}
?>
			


<?php 
	