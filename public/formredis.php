<?php
$redis = new Redis();
$redis->connect('redis',6379);

if (isset($_POST['body'])) {
  $inputText = $_POST['body'];
  $redis->set('maintext',$inputText);

}
$mainText = $redis->get('maintext');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>redis form</title>
</head>
<body>
  <p>ふぉーむにもじいれそうしんしてね</p>
  <form method="POST" action="">
    <textarea name="body" class="form-control" rows="3" placeholder="投稿内容"></textarea><br>
    <button type="submit">送信</button>
  </form>

  <p>内容</p>
  <p><?php echo htmlspecialchars($mainText); ?></p>

</body>
