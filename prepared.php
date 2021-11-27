<?php

# 1.

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

  // $n = 10;
  $n = '10 OR 1=1';

  // SQL文にユーザー入力や変数入力を用いると、SQLインジェクションの危険！
  // $pdo->query("DELETE FROM posts WHERE likes < $n");

  // プリペアードステートメント(?：プレースホルダー)
  $stmt = $pdo->prepare("DELETE FROM posts WHERE likes < ?");
  // プレースホルダーにはexecute()の引数が"SQL文"ではなく、"文字列"として埋め込まれる。
  // "OR 1=1"は無視され、$n = 10として処理される。
  $stmt->execute([$n]);


  // $pdo->queryの場合、戻り値は結果セット
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
