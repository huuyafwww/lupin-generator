# ルパン三世タイトルジェネレーター

技術的なことは、[Qitta](https://qiita.com/huuya/items/a4a8022e19bfe87322db)に書いてます！

## 環境

### 動作確認済み環境

- macOS Catalina : ``ver 10.15.2``
- PHP : ``7.3.1``
- Web Server : ``Apache``

### 必要な外部ライブラリ

- [FFmpeg](https://www.ffmpeg.org/)

### その他特記事項

- PHPにGDモジュールがインストールされていること

## 実行方法

FFmpegのコマンドパスが通った上記の環境下で、

```
lupin-generator/index.php
```

の

```
$string = "あけおめ！";
```

を編集して実行します。

## 出力先

```
lupin-generator/output/output.mp4
```

※既に出力結果が存在する場合は、上書き保存されます

### 設定関連

基本的に画像やフレームの作成に用いるパラメータは、

```
lupin-generator/Define.php
```

に記述しています。

以下は各定数の概要です。

|定数  |概要  |
|---|---|
|L_FFMPEG_COMMAND_PATH  |FFmpegのコマンドパス  |
|L_FONT_FILE  |出力する文字のフォント(TrueType)ファイルパス。デフォルトではGoogleのWebフォントを利用  |
|L_IMG_WIDTH  |出力結果の横幅  |
|L_IMG_HEIGHT  |出力結果の縦幅  |
|L_BACKGROUND_COLOR_R  |出力結果背景の``R``(0 〜 255)  |
|L_BACKGROUND_COLOR_G  |出力結果背景の``G``(0 〜 255)  |
|L_BACKGROUND_COLOR_B  |出力結果背景の``B``(0 〜 255)  |
|L_FONT_COLOR_R  |出力結果文字の``R``(0 〜 255)  |
|L_FONT_COLOR_G  |出力結果文字の``G``(0 〜 255)  |
|L_FONT_COLOR_B  |出力結果文字の``B``(0 〜 255)  |
|L_FONT_SIZE  |1文字専用フレームのフォントサイズ  |
|L_IMG_ANGLE  |文字の角度  |
|L_IMG_X  |左上を基点としたX軸方向の開始位置  |
|L_IMG_Y  |左上を基点としたY軸方向の開始位置  |
|L_TEMP_IMAGE_PATH  |フレームの作成に必要な画像ファイルの保存パス  |
|L_TEMP_FRAME_PATH  |動画の作成に必要なフレームファイルの保存パス  |
|L_OUTPUT_PATH  |出力結果（動画）の保存パス  |
|L_TYPE_SOUND  |タイピングサウンドの参照パス  |
|L_TITLE_SOUND  |タイトルサウンドの参照パス  |
|L_FRAME_LIST_FILE  |FFmpegを用いてフレームを連結する際に参照パスリストを記述するファイルのパス  |