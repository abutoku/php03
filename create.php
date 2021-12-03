<?php

include('functions.php');//関数を使うためfunctions.phpをinclude

//-----------------データ受け取り側では以下の処理を実装する----------------------------//

//必須項目の入力チェック
//データの受け取り
////DB 接続
//SQL 作成&実行
//SQL 実行後の処理

//-------------------------------------------------------------------------------------//

//POSTデータ確認
// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';
// exit();




if (
  !isset($_POST['name']) || $_POST['name'] == '' ||
  !isset($_POST['depth']) || $_POST['depth'] == ''||
  !isset($_POST['temp']) || $_POST['temp'] == ''
) {
  exit('ParamError'); //エラーを返す
}

// データの受け取り
$fish_name = $_POST['name'];
$depth = $_POST['depth'];
$temp = $_POST['temp'];
$id = $_POST['id'];


// var_dump($name);
// var_dump($depth);
// var_dump($temp);

// exit();

// -------------------------DB接続の必要な項目----------------------------------------//

// mysql：DB の種類（他にPostgreSQL，Oracle Database，などが存在する）
// dbname：DB の名前（今回はここをdec_todoに設定する！）
// port：接続ポート
// host：DB のホスト名
// username：DB 接続時のユーザ名
// password：DB 接続時のパスワード

// ----------------------------------------------------------------------------------//

// DB接続
$pdo = connect_to_db();//データベース接続の関数、$pdoに受け取る
  // 「dbError:...」が表示されたらdb接続でエラーが発生していることがわかる．

//------------SQL（今回は INSERT 文）を実行する場合も手順--------------//

//1.SQL 文の記述．
//2.バインド変数の設定．
//3.SQL 実行．
//4.（SQL 実行に失敗した場合はエラーメッセージを出力する）

//---------------------------------------------------------------------//


$sql = 'INSERT INTO fish_table2 (id,fish_name,depth,temp,created_at,updated_at,date_link_id) VALUES (NULL,:fish_name,:depth,:temp,now(), now(),:date_link_id)';

$stmt = $pdo->prepare($sql);

// バインド変数を設定
$stmt->bindValue(':fish_name', $fish_name, PDO::PARAM_STR);
$stmt->bindValue(':depth', $depth, PDO::PARAM_STR);
$stmt->bindValue(':temp', $temp, PDO::PARAM_STR);
$stmt->bindValue(':date_link_id', $id, PDO::PARAM_STR);


// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
//ユーザが入力した値を SQL 文内で使用する場合には必ずバインド変数を使用すること．


// SQL実行の処理
header('Location:index.php'); //SQL が正常に実行された場合は，データ入力画面に移動
exit();
