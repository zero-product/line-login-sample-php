<?php session_start(); ?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>line-login-sample-php</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <pre>
  <?= json_encode($_SESSION, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
  </pre>
  <h2>token</h2>
  <table class="parameter-table">
    <thead>
      <tr>
        <th>プロパティ</th>
        <th>タイプ</th>
        <th>説明</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>access_token</code></td>
        <td>String</td>
        <td>アクセストークン。有効期間は30日です。</td>
      </tr>
      <tr>
        <td><code>token_type</code></td>
        <td>String</td>
        <td><code>Bearer</code></td>
      </tr>
      <tr>
        <td><code>refresh_token</code></td>
        <td>String</td>
        <td>新しいアクセストークンを取得するためのトークン（リフレッシュトークン）。アクセストークンが発行されてから90日間有効です。</td>
      </tr>
      <tr>
        <td><code>expires_in</code></td>
        <td>Number</td>
        <td>アクセストークンの有効期限が切れるまでの秒数</td>
      </tr>
      <tr>
        <td><code>scope</code></td>
        <td>String</td>
        <td>
          アクセストークンに付与されている権限。スコープについて詳しくは、「<a href="https://developers.line.biz/ja/docs/line-login/integrate-line-login/#scopes" target="_blank" rel="noopener noreferrer">スコープ</a>」を参照してください。<br>
          注意：<code>email</code>スコープは権限が付与されていても<code>scope</code>プロパティの値としては返されません。
        </td>
      </tr>
      <tr>
        <td><code>id_token</code></td>
        <td>String</td>
        <td>
          ユーザー情報を含む<a href="https://datatracker.ietf.org/doc/html/rfc7519" target="_blank" rel="noopener noreferrer">JSONウェブトークン（JWT）</a>。<br>
          このプロパティは、スコープに<code>openid</code>を指定した場合にのみ返されます。
        </td>
      </tr>
    </tbody>
  </table>



  <h2>profile</h2>
  <table class="parameter-table">
    <thead>
      <tr>
        <th>プロパティ</th>
        <th>タイプ</th>
        <th>説明</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>iss</code></td>
        <td>String</td>
        <td><code>https://access.line.me</code>。IDトークンの生成URLです。</td>
      </tr>
      <tr>
        <td><code>sub</code></td>
        <td>String</td>
        <td>IDトークンの対象ユーザーID</td>
      </tr>
      <tr>
        <td><code>aud</code></td>
        <td>String</td>
        <td>チャネルID</td>
      </tr>
      <tr>
        <td><code>exp</code></td>
        <td>Number</td>
        <td>IDトークンの有効期限（UNIXタイム）</td>
      </tr>
      <tr>
        <td><code>iat</code></td>
        <td>Number</td>
        <td>IDトークンの生成時間（UNIXタイム）</td>
      </tr>
      <tr>
        <td><code>auth_time</code></td>
        <td>Number</td>
        <td>ユーザー認証時間（UNIXタイム）。認可リクエストに<code>max_age</code>の値を指定しなかった場合は含まれません。</td>
      </tr>
      <tr>
        <td><code>nonce</code></td>
        <td>String</td>
        <td>認可URLに指定した<code>nonce</code>の値。認可リクエストに<code>nonce</code>の値を指定しなかった場合は含まれません。</td>
      </tr>
      <tr>
        <td><code>amr</code></td>
        <td>Stringの配列</td>
        <td>
          ユーザーが使用した認証方法のリスト。特定の条件下ではペイロードに含まれません。<br>
          以下のいずれかの値が含まれます。それぞれの認証方法については「<a href="https://developers.line.biz/ja/docs/line-login/integrate-line-login/#authentication-process" target="_blank" rel="noopener noreferrer">ユーザーがユーザー認証を行う</a>」を参照してください。
          <ul>
            <li><code>pwd</code>：メールアドレスとパスワードによるログイン</li>
            <li><code>lineautologin</code>：LINEによる自動ログイン（LINE SDKを使用した場合も含む）</li>
            <li><code>lineqr</code>：QRコードによるログイン</li>
            <li><code>linesso</code>：シングルサインオンによるログイン</li>
          </ul>
        </td>
      </tr>
      <tr>
        <td><code>name</code></td>
        <td>String</td>
        <td>ユーザーの表示名。認可リクエストに<code>profile</code>スコープを指定しなかった場合は含まれません。</td>
      </tr>
      <tr>
        <td><code>picture</code></td>
        <td>String</td>
        <td>ユーザープロフィールの画像URL。認可リクエストに<code>profile</code>スコープを指定しなかった場合は含まれません。</td>
      </tr>
      <tr>
        <td><code>email</code></td>
        <td>String</td>
        <td>ユーザーのメールアドレス。認可リクエストに<code>email</code>スコープを指定しなかった場合は含まれません。</td>
      </tr>
    </tbody>
  </table>

</body>

</html>