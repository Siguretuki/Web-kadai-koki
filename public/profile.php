<?php
session_start();
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

// ログイン中のユーザー情報を取得
$user_id = $_SESSION['login_user_id'];
$select_sth = $dbh->prepare("SELECT icon_filename FROM users WHERE id = :id");
$select_sth->execute([':id' => $user_id]);
$user = $select_sth->fetch(PDO::FETCH_ASSOC);

// アイコン画像のパスを設定
if ($user && !empty($user['icon_filename'])) {
    $icon_url = '/images/' . $user['icon_filename'];
} else {
    $icon_url = '/images/default.png';  // デフォルトのアイコン画像
}

echo '<h1>プロフィール</h1>';
echo '<img src="' . $icon_url . '" alt="ユーザーアイコン" width="100" height="100">';
echo '<p>名前: ' . htmlspecialchars($user['name']) . '</p>';
?>

