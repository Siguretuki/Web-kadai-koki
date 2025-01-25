<?php
// Redisサーバへの接続
$redis = new Redis();
$redis->connect('redis', 6379);  // Redisのホストとポート

// フォームが送信された場合
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    // フォームから送信されたメッセージを取得
    $message = trim($_POST['message']);
    
    // 空でないメッセージならRedisに保存
    if ($message !== '') {
        // 現在の時刻とともにメッセージを保存
        $postData = [
            'message' => $message
        ];
        
        // 投稿メッセージをJSONにシリアル化してRedisに追加
        $redis->lPush('messages', json_encode($postData));  // 'messages'というリストにメッセージを保存
    }
}

// Redisから保存されているメッセージを取得（最新の10件を取得）
$messages_json = $redis->lRange('messages', 0, 9);  // 最大10件のメッセージを取得
$messages = array_map('json_decode', $messages_json);  // JSONをデコードして配列に変換

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>簡易掲示板</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 100px;
        }
        ul {
            list-style: none;
            padding-left: 0;
        }
        li {
            background-color: #f4f4f4;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <h1>簡易掲示板</h1>

    <!-- 投稿フォーム -->
    <form method="POST" action="">
        <label for="message">メッセージを投稿:</label><br>
        <textarea name="message" id="message" rows="4" required></textarea><br>
        <button type="submit">投稿する</button>
    </form>

    <h2>投稿一覧</h2>
    <ul>
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $msg): ?>
                <li>
                    <?php echo htmlspecialchars($msg->message); ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>まだ投稿がありません。</p>
        <?php endif; ?>
    </ul>

</body>
</html>

