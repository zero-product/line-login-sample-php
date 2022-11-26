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
  <?php
  session_start();
  require_once __DIR__ . '/config.php';

  $auth_url = 'https://access.line.me/oauth2/v2.1/authorize?';
  $query    = urldecode(
    http_build_query([
      'response_type' => 'code',
      'client_id'     => LINE_CHANNEL_ID, // LINE Login チャネルID
      'redirect_uri'  => CALLBACK_URL,    // LINE developersコンソールに設定したURL
      'bot_prompt'    => 'aggressive',    // 初回認証時に公式アカウントも同時にフォローする
      'state'         => rand(),
      'scope'         => implode('%20', ['profile', 'openid', 'email']),
    ])
  );
  ?>
  <a class="btn" href="<?= $auth_url . $query; ?>">LINEログイン</a>

  <div>
    <div class="document">
      <div>
        <h2>1. ログインフロー</h2>
        <p>ウェブアプリ向けのLINEログインの処理（ウェブログイン）は、OAuth 2.0の認可コード付与のフロー (opens new window)とOpenID Connect (opens new window)プロトコルに基づいています。</p>
        <img style="display:block;max-width:100%;" src="https://developers.line.biz/assets/img/web-login-flow.2af66354.svg" alt="">
      </div>

      <div>
        <h2>2. ユーザーに認証と認可を要求</h2>
        <p>LINEプラットフォームとユーザーの間で、認証と認可のプロセスを開始させます。ユーザーがLINEログインボタンをクリックしたときに、以下の例のように認可URLに必須のクエリパラメータを付けてユーザーをリダイレクトしてください。</p>
        <pre>https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1234567890&redirect_uri=https%3A%2F%2Fexample.com%2Fauth%3Fkey%3Dvalue&state=12345abcde&scope=profile%20openid&nonce=09876xyz</pre>

        <h3>認可URLに付与できるクエリパラメータ</h3>
        <table class="parameter-table">
          <thead>
            <tr>
              <th>パラメータ</th>
              <th>タイプ</th>
              <th>必須</th>
              <th>説明</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><code>response_type</code></td>
              <td>String</td>
              <td class="text-nowrap">必須</td>
              <td><code>code</code></td>
            </tr>
            <tr>
              <td><code>client_id</code></td>
              <td>String</td>
              <td class="text-nowrap">必須</td>
              <td>LINEログインチャネルのチャネルID。<a href="https://developers.line.biz/console/" class="">LINE Developersコンソール</a>で確認できます。</td>
            </tr>
            <tr>
              <td><code>redirect_uri</code></td>
              <td>String</td>
              <td class="text-nowrap">必須</td>
              <td><a href="https://developers.line.biz/console/" class="">LINE Developersコンソール</a>に登録したコールバックURLをURLエンコードした文字列。任意のクエリパラメータを付与できます。</td>
            </tr>
            <tr>
              <td><code>state</code></td>
              <td>String</td>
              <td class="text-nowrap">必須</td>
              <td>
                <a href="https://wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">クロスサイトリクエストフォージェリ</a>防止用の固有な英数字の文字列。<br>
                <strong>ログインセッションごとにウェブアプリでランダムに生成してください。</strong><br>
                なお、URLエンコードされた文字列は使用できません。
              </td>
            </tr>
            <tr>
              <td><code>scope</code></td>
              <td>String</td>
              <td class="text-nowrap">必須</td>
              <td>ユーザーに付与を依頼する権限。詳しくは、「<a href="#scopes">スコープ</a>」を参照してください。</td>
            </tr>
            <tr>
              <td><code>nonce</code></td>
              <td>String</td>
              <td></td>
              <td>
                <a href="https://en.wikipedia.org/wiki/Replay_attack" target="_blank" rel="noopener noreferrer">リプレイアタック</a>を防止するための文字列。<br>
                この値はレスポンスで返される<a href="https://developers.line.biz/ja/docs/line-login/verify-id-token/#id-tokens" class="">IDトークン</a>に含まれます。
              </td>
            </tr>
            <tr>
              <td><code>prompt</code></td>
              <td>String</td>
              <td></td>
              <td>
                <code>consent</code>。<br>
                ユーザーが要求された権限をすべて付与済みであっても、強制的に同意画面を表示します。
              </td>
            </tr>
            <tr>
              <td><code>max_age</code></td>
              <td>Number</td>
              <td></td>
              </td>
              <td>
                ユーザー認証後に許容される最大経過時間（秒）。<br>
                <a href="https://openid.net/specs/openid-connect-core-1_0.html" target="_blank" rel="noopener noreferrer">OpenID Connect Core 1.0</a>の「Authentication Request」のセクションで定義されている<code>max_age</code>パラメータに相当します。
              </td>
            </tr>
            <tr>
              <td><code>ui_locales</code></td>
              <td>String</td>
              <td></td>
              <td>
                LINEログインで表示される画面の表示言語および文字種。<a href="https://datatracker.ietf.org/doc/html/rfc5646" target="_blank" rel="noopener noreferrer">RFC 5646（BCP 47）</a>で定義されている言語タグを、優先順位が高い順に、スペース区切りのリストで設定します。<br>
                <a href="https://openid.net/specs/openid-connect-core-1_0.html" target="_blank" rel="noopener noreferrer">OpenID Connect Core 1.0</a>の「Authentication Request」のセクションで定義されている<code>ui_locales</code>パラメータに相当します。
              </td>
            </tr>
            <tr>
              <td><code>bot_prompt</code></td>
              <td>String</td>
              <td></td>
              <td>
                LINE公式アカウントを友だち追加するオプションをユーザーのログイン時に表示します。<code>normal</code>または<code>aggressive</code>を指定します。<br>
                詳しくは、「<a href="https://developers.line.biz/ja/docs/line-login/link-a-bot/" class="">LINEログインしたときにLINE公式アカウントを友だち追加する（ボットリンク）</a>」を参照してください。
              </td>
            </tr>
            <tr>
              <td><code>initial_amr_display</code></td>
              <td>String</td>
              <td></td>
              <td>
                <code>lineqr</code>を指定すると、<a href="https://developers.line.biz/ja/docs/line-login/integrate-line-login/#mail-or-qrcode-login" class="">メールアドレスログイン</a>の代わりに、<a href="/ja/docs/line-login/integrate-line-login/#mail-or-qrcode-login" class="">QRコードログイン</a>をデフォルト表示します。
              </td>
            </tr>
            <tr>
              <td><code>switch_amr</code></td>
              <td>Boolean</td>
              <td></td>
              <td>
                <code>false</code>を指定すると、ログインの方法を変更するための「メールアドレスでログイン」や「QRコードログイン」のボタンを非表示にします。デフォルト値は<code>true</code>です。
              </td>
            </tr>
            <tr>
              <td><code>disable_auto_login</code></td>
              <td>Boolean</td>
              <td></td>
              <td>
                <code>true</code>を指定すると、<a href="https://developers.line.biz/ja/docs/line-login/integrate-line-login/#line-auto-login" class="">自動ログイン</a>を無効にします。<br>
                デフォルト値は<code>false</code>です。<br>
                この値が<code>true</code>のとき、SSOが利用できる場合は<a href="https://developers.line.biz/ja/docs/line-login/integrate-line-login/#line-sso-login">シングルサインオン（SSO）によるログイン</a>が表示され、利用できない場合は<a href="https://developers.line.biz/ja/docs/line-login/integrate-line-login/#mail-or-qrcode-login">メールアドレスログイン</a>が表示されます。
              </td>
            </tr>
            <tr>
              <td><code>disable_ios_auto_login</code></td>
              <td>Boolean</td>
              <td></td>
              <td>
                <code>true</code>を指定すると、iOSにおいて<a href="https://developers.line.biz/ja/docs/line-login/integrate-line-login/#line-auto-login" class="">自動ログイン</a>を無効にします。<br>
                デフォルト値は<code>false</code>です。後発で追加された<code>disable_auto_login</code>パラメータの利用を推奨します。
              </td>
            </tr>
            <tr>
              <td><code>code_challenge</code></td>
              <td>String</td>
              <td></td>
              <td>
                LINEログインをPKCE対応するために必要なパラメータ。<br>
                一意の<code>code_verifier</code>をSHA256で暗号化したうえで、Base64URL形式にエンコードした値です。デフォルト値は<code>null</code>です（値を指定しない場合、リクエストはPKCE対応されません）。<br>
                PKCEの実装方法について詳しくは、「<a href="https://developers.line.biz/ja/docs/line-login/integrate-pkce/#how-to-integrate-pkce" class="">LINEログインにPKCEを実装する</a>」を参照してください。
              </td>
            </tr>
            <tr>
              <td><code>code_challenge_method</code></td>
              <td>String</td>
              <td></td>
              <td>
                <code>S256</code>（ハッシュ関数<code>SHA256</code>を表します。）<br>
                <code>code_verifier</code>から<code>code_challenge</code>を算出する際の暗号化方式を指定します。LINEログインでは、セキュリティ上の観点から<code>S256</code>のみをサポートしています。<br>
                PKCEの実装方法について詳しくは、「<a href="https://developers.line.biz/ja/docs/line-login/integrate-pkce/#how-to-integrate-pkce" class="">LINEログインにPKCEを実装する</a>」を参照してください。
              </td>
            </tr>
          </tbody>
        </table>


        <h3>スコープ</h3>
        <p><code>scope</code>パラメータに指定できるスコープは以下のとおりです。複数のスコープを指定するには、URLエンコードされた空白文字（%20）で区切って指定します。</p>
        <table class="parameter-table">
          <thead>
            <tr>
              <th>スコープ</th>
              <th>プロフィール情報</th>
              <th><small><a href="https://developers.line.biz/ja/docs/line-login/verify-id-token/#id-tokens" class="">IDトークン</a><br>（ユーザーIDを含む）</small></th>
              <th><small><a href="https://developers.line.biz/ja/docs/line-login/verify-id-token/#id-tokens" class="">IDトークン</a>内の<br>表示名</small></th>
              <th><small><a href="https://developers.line.biz/ja/docs/line-login/verify-id-token/#id-tokens" class="">IDトークン</a>内の<br>プロフィール画像のURL</small></th>
              <th><small><a href="https://developers.line.biz/ja/docs/line-login/verify-id-token/#id-tokens" class="">IDトークン</a>内の<br>メールアドレス</small></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><code>profile</code></td>
              <td>✓</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
            </tr>
            <tr>
              <td><code>profile%20openid</code></td>
              <td>✓</td>
              <td>✓</td>
              <td>✓</td>
              <td>✓</td>
              <td>-</td>
            </tr>
            <tr>
              <td><code>profile%20openid%20email</code></td>
              <td>✓</td>
              <td>✓</td>
              <td>✓</td>
              <td>✓</td>
              <td>✓（※）</td>
            </tr>
            <tr>
              <td><code>openid</code></td>
              <td>-</td>
              <td>✓</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
            </tr>
            <tr>
              <td><code>openid%20email</code></td>
              <td>-</td>
              <td>✓</td>
              <td>-</td>
              <td>-</td>
              <td>✓（※）</td>
            </tr>
          </tbody>
        </table>
        <p>※<code>email</code>を指定してユーザーにメールアドレスの取得権限を要求するには、あらかじめ<a href="https://developers.line.biz/ja/docs/line-login/integrate-line-login/#applying-for-email-permission">メールアドレス取得権限を申請</a>してください。</p>
      </div>
    </div>
  </div>
</body>

</html>