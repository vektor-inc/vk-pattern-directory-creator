# vk-pattern-directory-creator
VK Pattern Directory Creator

## 概要
**Block Pattern**というカスタム投稿タイプに追加されたブロックパターンをフロントエンドで紹介、コピペできるようにするためのプラグインです。

- テストサイト: https://pdc.vs4.nagoya/
- 完成イメージ: VKパターンライブラリ https://patterns.vektor-inc.co.jp/ の改良版。

## 仕様

### assets

| **`copy-button.js`** | ボタンをクリックして、コードスニペットを簡単にコピー。コピー回数を記録して人気のあるパターンを把握。 |
| --- | --- |
| **`iframe-responsive.js`** | ページ内のiframeをレスポンシブ対応にするスクリプトです。 |
| **`size-select.js`** | ユーザーが選択したサイズで、iframe内のコンテンツをプレビュー。 |

### modules

| `blocks.php` | カスタムブロックを登録し、WordPressブロックエディタ内で使用可能にする。 |
| --- | --- |
| `content-archive.php` | アーカイブページ用のコンテンツテンプレートを提供。 |
| `content-part.php` | 特定の部分テンプレート（パーツ）を構成し、再利用性を向上させる。 |
| `content-single.php` | シングル投稿ページのコンテンツをレンダリング。 |
| `enquque-scripts.php` | 必要なCSSやJavaScriptをWordPressに登録・読み込み。 |
| `iframe-sizes.php` | iframeのサイズに関する設定・レスポンシブ対応を管理。 |
| `iframe-view.php` | iframeの表示ロジックを定義し、ビューを出力するテンプレート。 |
| `register-post-type.php` | カスタム投稿タイプ「Block Patterns」の登録ロジックを実装し、独自投稿タイプを作成。 |
| `register-custom-taxonomies.php` | カスタム投稿タイプ「Block Patterns」のタクソノミー登録画面を実装し、独自カスタムタクソノミーを作成。 |

### blocks
| **VK Pattern Description** | vk-pattern-directory-creator/assets/src/js/copy-button.js でパターンをコピーする際に、パターンの説明を入れたいけどコピーされたくない時は「VK Pattern Description」を使うとコピーされない。 |
| --- | --- |
| **VK Pattern Display** | 固定ページか投稿か何かにブロックエディターで作ったページを参照してコピーできるブロック。 |
| **VK Pattern List** | アーカイブなどでパターン一覧を作る時に使用。現在は件数、表示順が変更可能。 |

### views
テーマがブロックテーマかクラシックテーマかを見分ける時に使用される。
