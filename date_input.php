<?php

include('functions.php'); //関数を使うためfunctions.phpをinclude

// データ受け取り
// var_dump($_POST);
// exit();

if (
  !isset($_POST['date']) || $_POST['date'] == '' 
) {
  exit('ParamError'); //エラーを返す
}

$input_date = $_POST['date'];

// DB接続
$pdo = connect_to_db(); //データベース接続の関数、$pdoに受け取る

$sql = 'INSERT INTO date_table (date_id,date,created_at,updated_at) VALUES (NULL,:date,now(), now())';

$stmt = $pdo->prepare($sql);

// バインド変数を設定
$stmt->bindValue(':date', $input_date, PDO::PARAM_STR);

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





?>