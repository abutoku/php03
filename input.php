<?php

include('functions.php'); //関数を使うためfunctions.phpをinclude

// var_dump($_GET);
// exit();

$date_id = $_GET['id'];

// var_dump($date_id);
// exit();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>データ登録</title>

  <link rel="stylesheet" href="./css/reset.css">
  <link rel="stylesheet" href="./css/style.css">

</head>

<body>

  <form action="create.php" method="POST">
    <fieldset>
      <legend>データ入力（登録画面）</legend>

      <a href="index.php">一覧画面</a>

      <div id="input_section">
        <!-- 登録部分 -->
        <form action="input.php" id="input_form" method="post">
          <!-- 魚の名前を入力 -->
          <div id="name_contents">
            <p>name</p>
            <input type="text" name="name" id="fish_name" required>
          </div>

          <!-- 水深を入力 -->
          <div id="depth_contents">
            <p>水深</p>
            <input type="number" name="depth" id="depth" min="0" max="40" value="15" required>
          </div>
          <!-- 水温を入力 -->
          <div id="temp_contents">
            <p>水温</p>
            <input type="number" name="temp" id="temp" min="-20" max="40" value="23" required>
          </div>

          <input type="hidden" name="id" value="<?= $date_id ?>">

          <!-- 登録ボタン -->
          <button id="send_btn" type="submit" onClick="return run();">登録</button>
        </form>
        <!-- 登録部分ここまで -->

    </fieldset>
  </form>


  <!-- jquery読み込み -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


</body>

</html>