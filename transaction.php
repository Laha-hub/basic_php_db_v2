<?php

# 8.

class Post
{
  // パラメータ記載なくても可
  // public $id;
  // public $message;
  // public $likes;

  public function show()
  {
    echo "$this->message ($this->likes)" . PHP_EOL;
  }
}

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

  $pdo->beginTransaction();
  $stmt = $pdo->query("UPDATE posts SET likes = likes + 1 WHERE id = 1");
  $stmt = $pdo->query("UPDATE posts SET likes = likes - 1 WHERE id = 2");
  $pdo->commit();

} catch (PDOException $e) {
  $pdo->rollBack();
  echo $e->getMessage() . PHP_EOL;
  // exit;

} finally {

  $stmt = $pdo->query("SELECT * FROM posts");
  $posts = $stmt->fetchAll(PDO::FETCH_CLASS, 'Post');
  foreach ($posts as $post) {
    $post->show();
  }

}
