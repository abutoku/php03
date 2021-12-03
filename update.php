<?php

include('functions.php'); //関数を使うためfunctions.phpをinclude

// var_dump($_POST);
// exit();

// 入力項目のチェック
//todo，deadline，idのデータが揃っていることを確認し，データを受け取る
if (
  !isset($_POST['fish_name']) || $_POST['fish_name'] == '' ||
  !isset($_POST['depth']) || $_POST['depth'] == '' ||
  !isset($_POST['temp']) || $_POST['temp'] == '' ||
  !isset($_POST['input_date']) || $_POST['input_date'] == ''

) {
  exit('ParamError'); //エラーを返す
}

// データの受け取り
$fish_name = $_POST['fish_name'];
$depth = $_POST['depth'];
$temp = $_POST['temp'];
$input_date = $_POST['input_date'];
$id = $_POST['id'];


// var_dump($fish_name);
// var_dump($depth);
// var_dump($temp);
// var_dump($input_date);
// var_dump($id);
// exit();


// DB接続
$pdo = connect_to_db(); //データベース接続の関数、$pdoに受け取る


// SQL実行 UPDATE の SQL を実行
//取得したidと一致したものを更新（書き換え）
//必ず WHERE で id を指定すること！！！
$sql = 'UPDATE fish_table SET fish_name = :fish_name, depth = :depth,temp = :temp,input_date = :input_date,updated_at = now() WHERE id=:id';

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':fish_name', $fish_name, PDO::PARAM_STR);
$stmt->bindValue(':depth', $depth, PDO::PARAM_STR);
$stmt->bindValue(':temp', $temp, PDO::PARAM_STR);
$stmt->bindValue(':input_date', $input_date, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
//$stmt->bindValue(':tag', $tag, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

//var_dump($status);

//一覧画面へ戻る
header("Location:index.php");
exit();
