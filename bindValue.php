<?php

# 4.

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

  // executeでデータを追加した場合、すべて文字列として追加。
  // 任意の型を指定した際は、bindValue()を使う。
  $stmt->bindValue(':message', $message, PDO::PARAM_STR);
  $stmt->bindValue(':likes', $likes, PDO::PARAM_INT);
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
