# 【PHP】LINEログインを実装するサンプル


## 前提
開発環境端末にPHPおよびComposerをインストールしてください。


## "LINE Login"と"LINE Messaging API"を用意

1. [LINE Developers コンソール](https://developers.line.biz/console/)にログイン。  
アカウントがない場合は作成。

1. 任意のプロバイダを作成。  
(最大10個のプロバイダーを作成可能)

1. 2で作成したプロバイダーに`LINE Login`チャネルと`LINE Messaging API`チャネルをそれぞれ作成。


### LINE Login
1. 作成した`LINE Login`チャネルを開く。

1. 画面上部の「開発中🔵」をクリックし、チャネルを公開する。  
(忘れなければ最後でもおｋ)

1. 「チャネル基本設定」タブの「チャネルID」と「チャネルシークレット」をメモる。

1. 「チャネル基本設定」タブの「リンクされたボット」に先程作成した`LINE Messaging API`のアカウントを充てる。

1. 「チャネル基本設定」タブの「OpenID Connect」で「メールアドレス取得権限」を申請する。

1. 「LINEログイン設定」タブの「コールバックURL」に以下のURLを貼り付ける。  
`http://localhost:8000/callback/`


### LINE Messaging API

- 今回は設定なし


## ソースコードをいじる

1. `config_sample.php`を`config.php`としてコピペ。

1. `config.php`を開き、`LINE_CHANNEL_ID`と`LINE_CHANNEL_SECRET`の値を以下のように埋める。

> |プロパティ|値|
> |-|-|
> |`LINE_CHANNEL_ID`|上でメモった`LINE Login`チャネルのチャネルID|
> |`LINE_CHANNEL_SECRET`|上でメモった`LINE Login`チャネルのチャネルシークレット|

## ローカルサーバから検証

1. 以下のコマンドをそれぞれ実行
```
# composer.json のライブラリをインストール
composer install

# ローカルサーバ立ち上げ
php -S localhost:8000
```

2. ブラウザで[http://localhost:8000](http://localhost:8000)にアクセス