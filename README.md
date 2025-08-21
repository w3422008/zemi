#### サーバ起動コマンド
```
php artisan serve
```

---
# プロジェクト構成・ファイル説明

## フォルダ・ファイル一覧と役割

### app/
- Console/ : Laravelのコマンド（Artisan）を自作したい時に編集。バッチ処理や自動化タスクを追加する場合。
- Exceptions/ : エラー発生時の動作をカスタマイズしたい時に編集。独自の例外処理を追加する場合。
- Http/
	- Controllers/ : 画面やAPIの処理を書く場所。新しい画面や機能を追加する時はここにコントローラーを作成。
	- Middleware/ : リクエストの前後で共通処理（認証・権限チェックなど）を追加したい時に編集。
	- Kernel.php : ミドルウェアの登録・管理。
- Models/ : データベースのテーブルと連携するクラス。新しいデータ管理機能を追加する時はここにモデルを作成。
- Providers/ : アプリ全体の初期設定やサービス登録。拡張機能やサービスの追加時に編集。

### bootstrap/
- app.php : Laravelアプリの起動準備をするファイル。通常は編集不要。
- cache/ : 設定やサービスのキャッシュ保存場所。手動編集は不要。

### config/
- アプリ全体の設定ファイル群。
- app.php : アプリ名やタイムゾーンなど基本設定。
- database.php : データベース接続情報。
- mail.php : メール送信設定。
- view.php : テンプレート（Blade）の設定。
- その他、認証・キャッシュ・ログなどの設定ファイル。

### database/
- factories/ : テスト用のダミーデータ生成クラス。テストを書く時に編集。
- migrations/ : データベースのテーブル構造を定義するファイル。新しいテーブルやカラム追加時に作成。
- seeders/ : 初期データを一括登録するファイル。開発・テスト用のデータ投入時に編集。

### public/
- Webサーバから直接アクセスされる公開用ディレクトリ。
- index.php : 全リクエストの入口。通常は編集不要。
- css/ : 画面デザイン用のCSSファイルを配置。
- js/ : 画面の動きを制御するJavaScriptファイルを配置。
- 画像やfaviconなどもここに置く。

### resources/
- views/ : 画面レイアウトやパーツを記述するBladeテンプレート。新しい画面を作る時はここにファイル追加。
- lang/ : 多言語対応のテキスト管理。日本語・英語など切り替えたい時に編集。
- sass/, css/, js/ : フロントエンド資材。SassはCSSの拡張版。

### routes/
- web.php : Web画面用のURLと処理の紐付け。新しい画面追加時はここにルートを記述。
- api.php : API用のURLと処理の紐付け。外部連携やSPA開発時に編集。
- console.php : Artisanコマンド用のルート。バッチ処理追加時に編集。
- channels.php : イベント・リアルタイム通信の設定。

### storage/
- logs/ : アプリの動作ログが保存される。エラー調査時に参照。
- app/ : ユーザーアップロードファイルなどの保存場所。
- framework/ : セッション・キャッシュなどの一時保存領域。

### tests/
- Feature/ : 実際の画面やAPIの動作を確認するテスト。
- Unit/ : 個々の関数やクラスの動作を確認するテスト。
- TestCase.php : 全テストの共通処理を記述。

### vendor/
- Laravel本体や外部ライブラリが自動でインストールされる場所。手動編集は不要。


### その他
- artisan : Laravelのコマンドラインツール。サーバ起動や各種操作に使用。
- composer.json : PHPパッケージ管理ファイル。ライブラリ追加時に編集。
- package.json : Node.jsパッケージ管理ファイル。フロント資材追加時に編集。
- webpack.mix.js : CSSやJSのビルド設定。フロント開発時に編集。
- .env : データベースやメールなど環境ごとの設定。開発・本番で値を切り替える時に編集。

---


# 画面を追加する際に編集すべき箇所・ファイル（詳細・具体例）

## 1. ルーティングの追加
- **編集ファイル**: `routes/web.php`
- **役割**: URLとコントローラー（処理）を紐付ける
- **記述例**:
	```php
	Route::get('/sample', [SampleController::class, 'index'])->name('sample.index');
	Route::post('/sample', [SampleController::class, 'store'])->name('sample.store');
	```
- **ポイント**:
	- GETは画面表示、POSTはデータ送信など用途で使い分ける
	- `name()`でルート名を付けるとBladeやコントローラーから参照しやすい

## 2. コントローラーの作成・編集
- **編集ファイル**: `app/Http/Controllers/SampleController.php`（新規作成例）
- **役割**: 画面表示・データ処理のロジック
- **作成コマンド例**:
	```
	php artisan make:controller SampleController
	```
- **記述例**:
	```php
	namespace App\Http\Controllers;

	use Illuminate\Http\Request;

	class SampleController extends Controller
	{
		public function index()
		{
			// 画面表示用の処理
			return view('sample.index');
		}

		public function store(Request $request)
		{
			// データ保存処理
			// $request->input('name') などで値取得
			// モデルと連携する場合はここで呼び出す
			return redirect()->route('sample.index');
		}
	}
	```
- **ポイント**:
	- 画面表示は`return view('...')`でBladeテンプレートを呼び出す
	- データ処理は`Request`オブジェクトで受け取る

## 3. ビュー（画面レイアウト）の作成・編集
- **編集ファイル**: `resources/views/sample/index.blade.php`（新規作成例）
- **役割**: HTML・デザイン・画面表示
- **記述例**:
	```blade
	@extends('layouts.app')

	@section('content')
	<div class="container">
		<h1>サンプル画面</h1>
		<form action="{{ route('sample.store') }}" method="POST">
			@csrf
			<input type="text" name="name" placeholder="名前を入力">
			<button type="submit">送信</button>
		</form>
	</div>
	@endsection
	```
- **ポイント**:
	- `@extends('layouts.app')`で共通レイアウトを利用
	- `@section('content')`で親テンプレートの`@yield('content')`に内容を挿入
	- ルート名は`route('...')`で参照

## 4. モデルの作成・編集（必要に応じて）
- **編集ファイル**: `app/Models/Sample.php`（新規作成例）
- **役割**: データベースとの連携
- **作成コマンド例**:
	```
	php artisan make:model Sample
	```
- **記述例**:
	```php
	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class Sample extends Model
	{
		protected $fillable = ['name'];
	}
	```
- **ポイント**:
	- `$fillable`で登録可能なカラムを指定
	- コントローラーから`Sample::create([...])`などで利用

## 5. CSS・JSなどフロント資材の追加（必要に応じて）
- **編集ファイル**: `public/css/sample.css`, `public/js/sample.js` など
- **役割**: 画面のデザイン・動的処理
- **記述例**:
	```css
	/* public/css/sample.css */
	.container { margin-top: 40px; }
	```
	```js
	// public/js/sample.js
	document.addEventListener('DOMContentLoaded', function() {
		// JS処理
	});
	```
- **Bladeでの読み込み例**:
	```blade
	<link rel="stylesheet" href="{{ asset('css/sample.css') }}">
	<script src="{{ asset('js/sample.js') }}"></script>
	```

---

## 画面追加の流れ（まとめ）

1. ルート追加（routes/web.php）
2. コントローラー作成（app/Http/Controllers/）
3. ビュー作成（resources/views/）
4. 必要ならモデル作成（app/Models/）
5. CSS/JS追加（public/）

> 画面追加の際は、上記の流れに沿って各ファイルを編集・作成してください。  
> 詳細はLaravel公式ドキュメント（https://laravel.com/docs/）も参照すると便利です。