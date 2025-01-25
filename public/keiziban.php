<?php
// MySQLデータベースに接続
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

// POSTで送られてきたフォームパラメータ body がある場合
if (isset($_POST['body'])) {
    // TOUKOUテーブルにINSERTする
    $insert_sth = $dbh->prepare("INSERT INTO TOUKOU (text) VALUES (:body)");
    $insert_sth->execute([
        ':body' => $_POST['body'],
    ]);

    // 処理が終わったらリダイレクトする
    header("HTTP/1.1 302 Found");
    header("Location: ./keiziban.php");
    exit();
}

// ページ数をURLクエリパラメータから取得。無い場合は1ページ目とみなす
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 1ページあたりの行数を決める
$count_per_page = 10;

// ページ数に応じてスキップする行数を計算
$skip_count = $count_per_page * ($page - 1);

// TOUKOUテーブルの行数を SELECT COUNT で取得
$count_sth = $dbh->prepare('SELECT COUNT(*) FROM TOUKOU;');
$count_sth->execute();
$count_all = $count_sth->fetchColumn();
if ($skip_count >= $count_all) {
    // スキップする行数が全行数より多かったらエラーメッセージ表示し終了
    print('このページは存在しません!');
    return;
}

// TOUKOUテーブルからデータを取得
$select_sth = $dbh->prepare('
    SELECT id, text, created_at FROM TOUKOU 
    ORDER BY created_at DESC 
    LIMIT :count_per_page OFFSET :skip_count
');
$select_sth->bindParam(':count_per_page', $count_per_page, PDO::PARAM_INT);
$select_sth->bindParam(':skip_count', $skip_count, PDO::PARAM_INT);
$select_sth->execute();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <div class="container my-4">
        <div class="form-container mb-4 d-none d-md-block">
            <form method="POST" action="./keiziban.php">
                <div class="mb-3">
                    <textarea name="body" class="form-control" rows="3" placeholder="投稿内容"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">送信</button>
            </form>
        </div>

        <button type="button" class="btn btn-primary position-fixed bottom-0 end-0 m-4 d-none d-md-block" data-bs-toggle="modal" data-bs-target="#postModal">
            投稿する
        </button>

        <div class="mb-4">
            <?= $page ?>ページ目
            (全 <?= floor($count_all / $count_per_page) + 1 ?>ページ中)
        </div>

        <div class="d-flex justify-content-between mb-4">
            <div>
                <?php if($page > 1): // 前のページがあれば表示 ?>
                    <a href="?page=<?= $page - 1 ?>" class="btn btn-secondary">前のページ</a>
                <?php endif; ?>
            </div>
            <div>
                <?php if($count_all > $page * $count_per_page): // 次のページがあれば表示 ?>
                    <a href="?page=<?= $page + 1 ?>" class="btn btn-secondary">次のページ</a>
                <?php endif; ?>
            </div>
        </div>

        <?php
        foreach ($select_sth as $row): ?>
            <div class="border-bottom mb-3 pb-3">
                <div class="row mb-2">
                    <div class="col-2 fw-bold"><?= htmlspecialchars($row['id']) ?></div>
                    <div class="col-10 text-muted"><?= htmlspecialchars($row['created_at']) ?></div>
                </div>
                <div><?= nl2br(htmlspecialchars($row['text'])) ?></div>
            </div>
        <?php endforeach ?>
    </div>

    <!-- モーダル -->
    <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalLabel">投稿フォーム</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="./keiziban.php">
                        <div class="mb-3">
                            <textarea name="body" class="form-control" rows="3" placeholder="投稿内容"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">送信</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- スマホ用フローティングボタン -->
    <button type="button" class="btn btn-lg btn-primary position-fixed bottom-0 end-0 m-4 d-md-none" data-bs-toggle="modal" data-bs-target="#postModal">
        +
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>

