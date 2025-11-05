# アプリケーション名
fleama（模擬案件初級フリマアプリ）

## 概要
本アプリケーションは、商品の登録・検索・詳細表示・更新・購入機能を持つシステムです。  
商品ごとに「季節」を複数紐づける中間テーブルを用いて、多対多（Many-to-Many）リレーションを実装しています。  
また、画像アップロードやバリデーション、シンボリックリンクを用いた画像保存も実装しました。


## 環境構築

### Docker ビルド
```bash
git clone https:
cd Fresh
docker compose up -d --build
```

＊MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせてdocker-compose.ymlファイルを編集してください。

### Laravel環境構築
```bash
.env.exampleファイルから.envを作成し、以下の環境変数を変更してください。
DB_HOST=mysql
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass


docker compose exec php bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
　※ すでにリンクが存在する場合は
　「The [/var/www/public/storage] link already exists.」と表示されますが問題ありません。

exit
```

## 使用技術(実行環境)
php 8.1  
Laravel  8.83.29  
MySQL 8.0.26  
nginx 1.21.1  

## ER図
```mermaid
erDiagram
    PRODUCTS {
        BIGINT id PK
        VARCHAR name
        INT price
        VARCHAR description
        VARCHAR image
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    SEASONS {
        BIGINT id PK
        VARCHAR name
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    PRODUCTS_SEASONS {
        BIGINT id PK
        BIGINT product_id FK
        BIGINT season_id FK
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    PRODUCTS ||--o{ PRODUCTS_SEASONS : has
    SEASONS ||--o{ PRODUCTS_SEASONS : has
```

## テーブル仕様書
### products テーブル

| カラム名 | 型 | NULL | デフォルト | 備考 |
|-----------|----|------|------------|------|
| id | BIGINT | NOT NULL | AUTO_INCREMENT | 主キー |
| name | VARCHAR(255) | NOT NULL |  | 商品名 |
| price | INT | NOT NULL |  | 値段（0〜10000円） |
| description | VARCHAR(120) | NOT NULL |  | 商品説明（120文字以内） |
| image | VARCHAR(255) | NOT NULL |  | 商品画像パス（例：img/kiwi.png） |
| created_at | TIMESTAMP | NULL | CURRENT_TIMESTAMP | 登録日時 |
| updated_at | TIMESTAMP | NULL | CURRENT_TIMESTAMP | 更新日時 |

---

### seasons テーブル

| カラム名 | 型 | NULL | デフォルト | 備考 |
|-----------|----|------|------------|------|
| id | BIGINT | NOT NULL | AUTO_INCREMENT | 主キー |
| name | VARCHAR(50) | NOT NULL |  | 季節名（春・夏・秋・冬） |
| created_at | TIMESTAMP | NULL | CURRENT_TIMESTAMP | 登録日時 |
| updated_at | TIMESTAMP | NULL | CURRENT_TIMESTAMP | 更新日時 |

---

### products_seasons テーブル（中間テーブル）

| カラム名 | 型 | NULL | デフォルト | 備考 |
|-----------|----|------|------------|------|
| id | BIGINT | NOT NULL | AUTO_INCREMENT | 主キー |
| product_id | BIGINT | NOT NULL |  | 外部キー（products.id） |
| season_id | BIGINT | NOT NULL |  | 外部キー（seasons.id） |
| created_at | TIMESTAMP | NULL | CURRENT_TIMESTAMP | 登録日時 |
| updated_at | TIMESTAMP | NULL | CURRENT_TIMESTAMP | 更新日時 |


## URL
開発環境：http://localhost/products  
phpMyAdmin：http://localhost:8080/