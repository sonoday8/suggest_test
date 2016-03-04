サジェストのテスト

実行方法

PHPのビルトインWEBサーバーを起動し、ブラウザにて、test.phpへアクセスしてください。

$ php -S 127.0.0.1:8000

http://localhost:8000/test.php

事前に楽天ゴールデンイーグルスの投手データを Googleドライブ上に保存してあり、初回アクセス時にデータ取得と同時に、

Yahoo テキスト解析APIの「ルビ振り」を用いて、投手の漢字名から、ローマ字へ変換し、データをファイルに保存します。

画面上の入力窓に、ローマ字を１文字入力すると、投手名の候補がautocompleteにて一覧表示されます。

ブラウザ画面の下に、読み込んだデータの一覧を表示してありますので、ローマ字を打つ時の参考にしてください。

「ルビ振り」なので、カタカナは変換できていないです。

またローマ字前方一致を、トライ木にて探索してみました。

＊データがおかしい場合は、data.txtを削除してみてください。

参考リンク：

YahooAPIテキスト解析「ルビ振り」：http://developer.yahoo.co.jp/webapi/jlp/furigana/v1/furigana.html
トライ木：https://ja.wikipedia.org/wiki/%E3%83%88%E3%83%A9%E3%82%A4%E6%9C%A8
トライ木の参考ソース：http://d.hatena.ne.jp/tsugehara/20121211/1355234140
＊ほとんど参考に↑コピーしてしましました。。
