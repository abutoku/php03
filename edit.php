<?php

include('functions.php'); //関数を使うためfunctions.phpをinclude
//データの受け取りチェック
// var_dump($_GET);
// exit();

//id受け取り
$id = $_GET['id'];
// var_dump($id);
// exit();

// DB接続の関数を実行
$pdo = connect_to_db();
//できていればOK
//exit('ok');


// SQL実行
//idが一致しているものを取得
$sql = 'SELECT * FROM fish_table WHERE id=:id';
$stmt = $pdo->prepare($sql);
//バインド変数にする順番が大事
$stmt->bindValue(':id', $id, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

//$statusには実行結果が真偽値で返ってくる
// var_dump($status);
// exit();

//ファイル単体の場合fetch、複数の場合はfetchAll
$record = $stmt->fetch(PDO::FETCH_ASSOC);

//取得したデータを確認
// echo '<pre>';
// var_dump($record);
// echo '</pre>';
// exit();

//各入力フォームのvalue値に取得結果を反映


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DB連携型todoリスト（編集画面）</title>
</head>

<body>
  <form action="update.php" method="POST">
    <fieldset>
      <legend>データ修正（編集画面）</legend>

      <a href="index.php">一覧画面</a>

      <div id="input_section">
        <!-- 登録部分 -->
        <form action="input.php" id="input_form" method="post">
          <!-- 魚の名前を入力 -->
          <div id="name_contents">
            <p>name</p>
            <input type="text" name="fish_name" id="fish_name" value="<?= $record['fish_name'] ?>">
          </div>
          <!-- 水深を入力 -->
          <div id="depth_contents">
            <p>水深</p>
            <input type="number" name="depth" id="depth" min="0" max="40" value="<?= $record['depth'] ?>">
          </div>
          <!-- 水温を入力 -->
          <div id="temp_contents">
            <p>水温</p>
            <input type="number" name="temp" id="temp" min="-20" max="40" value="<?= $record['temp'] ?>">
          </div>
          <!-- 日付を入力 -->
          <div id="date_contents" required>
            <input type="date" name="input_date" value="<?= $record['input_date'] ?>"><br>
          </div>
          <input type="hidden" name="id" value="<?= $record['id'] ?>">
          <!-- 登録ボタン -->
          <button id="send_btn" type="submit" onClick="return run();">登録</button>

    </fieldset>
  </form>

  <!-- jquery読み込み -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <script>
    const categoryArray = [
      "ベラ科",
      "ハタ科",
      "スズメダイ科",
      "ハゼ科",
      "フグ科",
      "フサカサゴ科",
      "イサキ科",
      "チョウチョウオ科",
      "アジ科",
      "ヨウジウオ科",
      "テンジクダイ科",
      "カワハギ科",
    ];

    //タグ付のための配列
    const tagArray = [];

    //繰り返し処理ための配列
    categoryArray.forEach((x) => {
      tagArray.push(`<option value="${x}">${x}</option>`);
    });
    tagArray.unshift(`<option disabled selected value>科を選択</option>`);

    //selectタグの中に作成
    $('#category').html(tagArray);
    $('#category_search').html(tagArray);
  </script>

</body>

</html>