## customers（顧客）

|カラム名|データ型|制約|説明|
|:-----|:-----|:-----|:-----|
|id|BIGINT|PRIMARY KEY, AUTO_INCREMENT|顧客を1件ずつ区別|
|name|VARCHAR(20)|NULL不可|顧客名|
|email|VARCHAR(255)|NULL不可,UNIQUE|連絡先メールアドレス|
|phone|VARCHAR(20)|NULL可|電話番号|
|memo|TEXT|NULL可|備考|
|created_at|TIMESTAMP|-|登録した日時|
|updated_at|TIMESTAMP|-|最後に書き換えた日時|

## projects (案件)

|カラム名|データ型|制約|説明|
|:-----|:-----|:-----|:-----|
|id|BIGINT|PRIMARY KEY, AUTO_INCREMENT|案件を1件ずつ区別|
|name|VARCHAR(20)|NULL不可|案件名|
|customer_id|BIGINT|NULL不可,FK|紐づく顧客ID|
|user_id|BIGINT|NULL不可,FK|担当者のユーザーID|
|amount|INT|NULL不可|案件金額|
|status|VARCHAR(20)|NULL不可,ENUM|ステータス|
|description|TEXT|NULL可|案件概要|
|order_date|DATE|NULL可|受注予定日|
|created_at|TIMESTAMP|-|登録した日時|
|updated_at|TIMESTAMP|-|最後に書き換えた日時|

## users (担当者)
|カラム名|データ型|制約|説明|
|:-----|:-----|:-----|:-----|
|id|BIGINT|PRIMARY KEY, AUTO_INCREMENT|担当者を1人ずつ識別|
|name|VARCHAR(20)|NULL不可|担当者名|
|email|VARCHAR(255)|NULL不可,UNIQUE|担当者のメールアドレス|
|password|VARCHAR(255)|NULL不可|ログイン用パスワード（ハッシュ化して保存）|
|created_at|TIMESTAMP|-|登録した日時|
|updated_at|TIMESTAMP|-|最後に書き換えた日時|
