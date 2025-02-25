<?php

// セッションIDの取得(なければ新規で作成&設定)
$session_cookie_name = 'session_id';
$session_id = $_COOKIE[$session_cookie_name] ?? base64_encode(random_bytes(64));
if (!isset($_COOKIE[$session_cookie_name])) {
    setcookie($session_cookie_name, $session_id);
}

// 接続 (redisコンテナの6379番ポートに接続)
$redis = new Redis();
$redis->connect('redis', 6379);

// redisにセッション変数を保存しておくキーを決めておきます。
$redis_session_key = "session-" . $session_id; 

// 既にセッション変数(の配列)が何かしら格納されていればそれを，なければ空の配列を $session_values変数に保存。
$session_values = $redis->exists($redis_session_key)
    ? json_decode($redis->get($redis_session_key), true) 
    : []; 
$count = isset($session_values["access"]) ? intval($session_values["access"]) : 0;
$count++;

$before_accessed_at = $session_values["accessed_at"] ?? '';
$session_values["accessed_at"] = date('Y/m/d h:i:s');

// 値の保存
// 例として，username は mutoと保存
// $session_valuesにセットした上で，redisに保存。
$session_values["username"] = "muto";
$session_values["access"] = intval($count);
$redis->set($redis_session_key, json_encode($session_values));

// 値の取得
// $session_values変数は普通に連想配列なので，素直に値の取得ができますね。
$result = $session_values["username"];
echo("{$count}回目のアクセス");
if(!empty($before_accessed_at)){
  echo("前回の訪問日時{$before_accessed_at}");
}
