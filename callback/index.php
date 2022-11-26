<?php
session_start();
$_SESSION = [];

require dirname(__DIR__).'/vendor/autoload.php';
require_once dirname(__DIR__).'/config.php';
$client = new GuzzleHttp\Client();

// アクセストークン取得
$token_resp = $client->post("https://api.line.me/oauth2/v2.1/token", [
  'verify' => false,
  'form_params' => [
    "code"          => $_GET["code"],
    "grant_type"    => "authorization_code",  // 固定
    "redirect_uri"  => CALLBACK_URL,        //LINE developersコンソールに設定したURL
    "client_id"     => LINE_CHANNEL_ID,     // チャネルID
    "client_secret" => LINE_CHANNEL_SECRET, // チャネルシークレット
  ]
])->getBody()->getContents();
$token_json = json_decode($token_resp);
$_SESSION['token'] = $token_json;


// プロフィール取得
$profile_resp = $client->post("https://api.line.me/oauth2/v2.1/verify", [
  'verify' => false,
  'form_params' => [
    'id_token'  => $token_json->id_token, // 取得したIDトークン
    'client_id' => LINE_CHANNEL_ID        // チャネルID
  ]
])->getBody()->getContents();
$profile_json = json_decode($profile_resp);
$_SESSION['profile'] = $profile_json;


header("Location: http://localhost:8000/response/");
exit;