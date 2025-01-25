# 前期課題


## 起動手順
1. ローカルにクローンする
    ```bash
    git clone https://github.com/Siguretuki/Web-kadai.git
    ```
2. Dockerfileを使用して環境を構築する
※Dockerfileと同ディレクトリで
   ```bash
   docker compose up
   ```
3. MySQLに接続しテーブルを作成する
   ``` bash
   docker compose exec mysql mysql kyototech
   ```
4. SQLでテーブルを作成
   ``` sql
   CREATE TABLE TOUKOU (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `text` text NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `parent_id` INT DEFAULT NULL
    );
   ```

## ポイント
- hogehogeではないテーブルを実装した。具体的にはid,内容,投稿日時が保存されるようにした。
- レスアンカーが実装できた時用に親idも作った。
- bootstarpを使ってスマホでも見やすく、レスポンシブなデザインにした。
- デスクトップ版はページトップとサイドに投稿ボタンがあり、スマホ版はサイドのみとなっている。
- サイドからの投稿はモーダル表示されるように実装した。


docker ps -a
docker rm mysql
docker rm php

