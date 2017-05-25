<?php
/**
 * PHPだよー
 */
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>SSAnalyzer</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/mplus1p.css">
    <link rel="stylesheet" href="./css/style.css">
    <style type="text/css">
        textarea {
            height: 350px;
            width: 100%;
            resize: vertical;
        }
        a{
            color: whitesmoke;
        }
    </style>
</head>
<body>
<?php
if(isset($_SESSION['error'])){
    ?>
    <div id="message" class="error">
        <i><?php echo htmlspecialchars($_SESSION['error'],ENT_QUOTES); ?></i><br>
        <?php if(isset($_SESSION['message']))echo htmlspecialchars($_SESSION['message'],ENT_QUOTES); ?>
    </div>
    <?php
    unset($_SESSION['error']);
    unset($_SESSION['message']);
}
?>
<div id="header">
    <div class="sitename">SSAnalyzer<span style="font-size: 15px"> - Short Story Analyzer</span></div>
    <div class="headmenu">
        <ul>
            <li><a href="index.php">TOP</a></li>
        </ul>
    </div>
</div>
<div id="content">
    <h1>SSAnalyzer</h1>
    <div id="contentright">
        <div class="msgbox">
            <div class="msgboxtop">これは何？</div>
            <div class="msgboxbody">
                <p>
                    SS(ShortStory)を解析し人物の発言回数などを表示します。
                </p>
                <p>
                    また、WordPressプラグイン
                    <a href="https://ja.wardpress.org/plugins/speech-bubble/" target="_blank">Speech bubble</a>
                    向けのショートコード生成が可能です。<br>
                    <span style="color: darkgray;font-size: 14px;font-style: italic">ただし、管理者側で設定ファイルを用意する必要があります。</span>
                </p>
            </div>
            <div class="msgboxfoot"></div>
        </div>
        <div class="msgbox">
            <div class="msgboxtop">注意事項</div>
            <div class="msgboxbody">
                <p>
                    解析できるSSはそれぞれの行が以下のようなタイプのものです。
                </p>
                <blockquote>
                    人物名「台詞」<br>
                    ナレーション等<br>
                    <span style="color: darkgray;font-size: 14px;font-style: italic">(空行)</span>
                </blockquote>
            </div>
            <div class="msgboxfoot"></div>
        </div>
    </div>
    <div id="contentleft">
        <div class="msgbox">
            <form method="post" name="ss" action="analyzer.php">
                <div class="msgboxtop">解析</div>
                <div class="msgboxbody">
                    <h2>SS本文</h2>
                    <textarea name="body" title="本文" placeholder="SS本文をここへペースト"></textarea>
                </div>
                <div class="msgboxfoot">
                    <a href="javascript:void(0)" onclick="document.ss.submit();" class="button">解析実行</a>
                </div>
            </form>
        </div>

    </div>
</div>
</body>
</html>