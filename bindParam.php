<?php

# 5.

try {

  // PDO接続
  $pdo = new PDO(
    'mysql:host=db;dbname=myapp;charset=utf8mb4',
    'dbuser',
    'dbpass',
    [
      // PDO エラー処理
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      // PDO FETCHモード
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      // PDO エミュレートモード（int型のstr型への自動変換防止）
      PDO::ATTR_EMULATE_PREPARES => false,
    ]
  );

  $pdo->query("DROP TABLE IF EXISTS posts");
  $pdo->query(
    "CREATE TABLE posts (
      id INT NOT NULL AUTO_INCREMENT,
      message VARCHAR(140),
      likes INT,
      PRIMARY KEY (id)
    )"
  );

  $pdo->query(
    "INSERT INTO posts (message, likes) VALUES
      ('Thanks', 12),
      ('thanks', 4),
      ('Arigato', 15)"
  );

  $message = 'Merci';
  $likes = 8;
  $stmt = $pdo->prepare(
    "INSERT INTO
      posts (message, likes)
    VALUES
      (:message, :likes)"
  );

  // 変数に格納されている値をプレースホルダーに紐付けるのではなく、
  // 変数そのものをプレースホルダーに紐付けるのが"bindParam"
  $stmt->bindParam(':message', $message, PDO::PARAM_STR);
  $stmt->bindParam(':likes', $likes, PDO::PARAM_INT);
  $stmt->execute();


  // execute()実行直前の$message, $likesを評価
  $message = 'Gracias';
  $likes = 5;
  $stmt->execute();

  $message = 'Danke';
  $likes = 11;
  $stmt->execute();

  $stmt = $pdo->query("SELECT * FROM posts");
  $posts = $stmt->fetchAll();
  foreach ($posts as $post) {
    printf(
      // %s: string, %d: digit
      '%s (%d)' . PHP_EOL,
      $post['message'],
      $post['likes'],
    );
  }
} catch (PDOException $e) {
  echo $e->getMessage() . PHP_EOL;
  exit;
}
