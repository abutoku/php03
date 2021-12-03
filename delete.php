<?php

include('functions.php'); //関数を使うためfunctions.phpをinclude
// データ受け取り
// var_dump($_GET);
// exit();

//$idに代入
// $id = $_GET['id'];
// var_dump($id);
// exit();

// DB接続
$pdo = connect_to_db();

// SQL実行
$sql = 'DELETE FROM fish_table WHERE id=:id';
$stmt = $pdo->prepare($sql);
//バインド変数にする順番が大事
$stmt->bindValue(':id', $id, PDO::PARAM_STR);



try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}





//一覧画面へ戻る
header("Location:index.old.php");
exit();
