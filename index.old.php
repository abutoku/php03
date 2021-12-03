<?php

include('functions.php'); //関数を使うためfunctions.phpをinclude

//------------処理の流れ----------------------------------------------------//

//表示ファイル（todo_read.php）へアクセス時，DB 接続する．
//データ参照用 SQL 作成 → 実行．
//取得したデータを HTML に埋め込んで画面を表示．

//------------処理の流れ----------------------------------------------------//

// DB接続
$pdo = connect_to_db(); //データベース接続の関数、$pdoに受け取る
// 「dbError:...」が表示されたらdb接続でエラーが発生していることがわかる．



// SQL作成&実行

//今回は「ユーザが入力したデータ」を使用しないのでバインド変数は不要．
$sql = 'SELECT * FROM fish_table ORDER BY input_date DESC';
$stmt = $pdo->prepare($sql);

//$statusには実行結果が入るが，この時点ではまだデータ自体の取得はできていない点に注意．
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$sql_2 = 'SELECT * FROM tag_table ORDER BY created_at DESC';
$stmt_2 = $pdo->prepare($sql_2);

//$statusには実行結果が入るが，この時点ではまだデータ自体の取得はできていない点に注意．
try {
  $status_2 = $stmt_2->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// SQL実行の処理
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetchAll()関数でデータ自体を取得する．
$tag = $stmt_2->fetchAll(PDO::FETCH_ASSOC); //fetchAll()関数でデータ自体を取得する．

//取得したデータを確認
// echo '<pre>';
// var_dump($tag);
// echo '</pre>';
// exit();

//DBのtideを照合
function tide_check($x)
{
  switch ($x) {
    case "oosio":
      return "大潮";
      break;
    case "nakasio":
      return "中潮";;
      break;
    case "kosio":
      return "小潮";
      break;
    case "nagasio":
      return "長潮";
      break;
    case "wakasio":
      return "若潮";
      break;
  }
}

//繰り返し処理を用いて，取得したデータから HTML タグを生成する．
$output = "";
foreach ($result as $record) {
  $tide = tide_check($record['tide']); //DBのtideを照合する関数を実行
  $output .= "
    <div class=\"output_contents\">
      <div id=\"output{$record['id']}\" class =\"test\">
        <div>{$record['fish_name']}  {$record['input_date']}</div>
        <div class=\"infomation\">
          <div>{$record['category']}</div>
          <div>水深{$record['depth']}ｍ</div>
          <div>水温{$record['temp']}℃</div>
          <div>潮:{$tide}</div>
          <a href=edit.php?id={$record["id"]}>edit</a>
          <a href=delete.php?id={$record["id"]}>delete</a>
        </div>
      </div>
    <div>
  ";
}

$tag_btn = "";
foreach ($tag as $record) {
  $tag_btn .= "
    <div id=\"btn_{$record['tag_id']}\">
      <a href=tag.php?value={$record["tag_name"]}><button value=\"{$record['tag_name']}\">{$record['tag_name']}</button></a>
    <div>
  ";
}



?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FISH DATA</title>

  <link rel="stylesheet" href="./css/reset.css">
  <link rel="stylesheet" href="./css/style.css">

  <!-- bootstrap css-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

  <!-- bootstrap toggle -->
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

</head>

<body>

  <header>
    <h1>FISH DATA</h1>
    <input type="checkbox" checked data-toggle="toggle" data-on="Log" data-off="Picture" data-onstyle="primary" data-offstyle="warning">
  </header>
  <div id="wrapper">

    <main>
      <section id="profile_section">
        <img src="./img/face.JPG" alt="アカウント画像" id="profile_img">
        <div id="profile_txt">
          <p>Takeshi</p>
          <p>長崎の猫です</p>
        </div>
      </section>

      <section id="addbtn_section">
        <input type="date">
        <a href="input.php"><button type="button" >add</button></a>
      </section>

      <section id="search_section">

        <form action="">
          <input type="text">
          <button type="button" >検索</button>
        </form>

      </section>

      <section id="list_section">
        <div id="output_area">
          <?= $output ?>
        </div>

      </section>

    </main>
  </div>
  <!--wrapperここまで-->

  <!-- jquery読み込み -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <!-- bootstrap js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

  <!-- bootstrap toggle -->
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

  <script>
    //selectタグの中に作成
    // $('#category').html(tagArray);
    // $('#category_search').html(tagArray);

    //名前と日付以外は隠しておく
    $('.infomation').hide();

    //名前がクリックされたら詳細を表示
    $('body').on('click', '.test', function() {
      if ($(this).children('.infomation').hasClass('show')) {
        $(this).children('.infomation').slideUp();
        $(this).children('.infomation').removeClass('show');
      } else {
        $(this).children('.infomation').slideDown();
        $(this).children('.infomation').addClass('show');
      }
    });

    $('#tag_contents').hide();

    $('#add').on('click', function() {
      if ($('#tag_contents').hasClass('show')) {
        $('#tag_contents').hide();
        $('#tag_contents').removeClass('show');
      } else {
        $('#tag_contents').show();
        $('#tag_contents').addClass('show');
      }
    });
  </script>
</body>

</html>