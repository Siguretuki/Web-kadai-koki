# 後期課題


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

## 実装できたこと
無限スクロール


docker ps -a
docker rm mysql
docker rm php

