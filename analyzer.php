<?php
/**
 * Created by PhpStorm.
 * User: miyano
 * Date: 17/05/22
 * Time: 20:45
 */
ini_set("error_reporting",1);

session_start();

//処理時間
$starttime = microtime(true);

//読み込み確認
if(isset($_POST['body']) && !empty($_POST['body'])){
    $body = htmlspecialchars($_POST['body']);
    //バイト数・文字列
    $byte = strlen($body);
    $char = mb_strlen($body);
}else {
    $_SESSION['error'] = "入力がありません";
    $_SESSION['message'] = "入力を確かめて再実行してください";
    header("Location: ./index.php");
    exit();
}

//行ごとの配列化
$content = explode("\n",$body);

//行数
$rowcount['total'] = count($content);

//総行数カウント初期化
$rowcount['words'] = 0;
$rowcount['narration'] = 0;
$rowcount['blank'] = 0;

//処理
foreach ($content as $row){
    //判定
    $row = str_replace(array("\r\n", "\r", "\n"),"",$row);
    if(preg_match("/^.+「.*」/m",$row) === 1){
        $rowcount['words']++;
        $script = explode("「",$row);
        if(isset($actorcount[$script[0]])){
            $actorcount[$script[0]]['times']++;
        }else{
            $actorcount[$script[0]] = array(
                "actor" => $script[0],
                "times" => 1
            );
        }
    }else if(preg_match("/^( |　).$/m",$row) == 1 || empty($row)){
        $rowcount['blank']++;
    }else{
        $rowcount['narration']++;
    }
}

//処理終了時間
$processtime = round(microtime(true) - $starttime,4);

//結果表示
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>結果表示</title>
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
if(isset($error)){
    ?>
    <div id="message" class="error">
        <i><?php echo htmlspecialchars($error); ?></i><br>
        <?php if(isset($message))echo htmlspecialchars($message); ?>
    </div>
<?php
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
    <h1>解析結果</h1>
    <div class="msgbox">
        <div class="msgboxtop">統計</div>
        <div class="msgboxbody">
            <div>
                <div style="float: left;width: 400px">
                    <h3 style="margin-top: 0;">全体の行数</h3>
                    <table>
                        <tr>
                            <th>総行数</th><td><?php echo $rowcount['total']; ?></td>
                        </tr>
                        <tr>
                            <th>台詞行数</th><td><?php echo $rowcount['words']; ?></td>
                        </tr>
                        <tr>
                            <th>ナレ行数</th><td><?php echo $rowcount['narration']; ?></td>
                        </tr>
                        <tr>
                            <th>空行</th><td><?php echo $rowcount['blank']; ?></td>
                        </tr>
                    </table>
                    <h3>文書情報</h3>
                    <table>
                        <tr>
                            <th>バイト数</th><td><?php echo $byte."bytes"; ?></td>
                        </tr>
                        <tr>
                            <th>文字数カウント</th><td><?php echo $char; ?></td>
                        </tr>
                    </table>
                </div>
                <div style="margin-left: 410px;width: auto;min-height: 250px">
                    <h3>人物別カウント</h3>
                    <table>
                        <?php
                        if(isset($actorcount)){
                            foreach ($actorcount as $actor){
                                ?>
                                <tr>
                                    <th><?php echo htmlspecialchars($actor['actor']); ?></th><td><?php echo $actor['times'] ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </div>
                <p>
                    処理時間 : <?php echo $processtime."sec"; ?>
                </p>
            </div>
        </div>
        <div class="msgboxfoot"></div>
    </div>
    <?php
    require_once __DIR__."/settings/loaddifinition.php";
    if(isset($actorcount) && CheckEnable()){
        $list_n = LoadDifinition(true);
        ?>
        <form method="post" action="converter.php" name="convert">
            <div class="msgbox">
                <div class="msgboxtop">ショートコード生成</div>
                <div class="msgboxbody">
                    <p>
                        ショートコード生成が利用可能です<br>
                        それぞれの登場人物に使うアイコンを選択してください。
                    </p>
                    <h3>アイコン選択</h3>
                    <div style="max-height: 300px;overflow-y: scroll">
                        <table>
                        <?php
                        unset($actor);
                        if(isset($actorcount)){
                            foreach ($actorcount as $actor){
                            ?>
                                <tr>
                                    <th><?php echo htmlspecialchars($actor['actor']); ?></th>
                                    <td>
                                        <select name="actor_<?php echo htmlspecialchars($actor['actor']); ?>" title="選択" class="select">
                                            <option value="">生成しない</option>
                                            <?php
                                            if(isset($list_n) && is_array($list_n)){
                                                foreach ($list_n as $n){
                                                    $n = htmlspecialchars($n);
                                                    ?>
                                                    <option value="<?php echo $n; ?>"><?php echo $n; ?></option>
                                                    <?php
                                                }
                                            } ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                        </table>
                    </div>
                    <h3>SS本文<span style="font-size: 14px;font-style: italic;color: lightgray;"> 変更できません</span></h3>
                    <textarea name="body" title="SS本文" readonly><?php if(isset($body))echo $body; ?></textarea>
                </div>
                <div class="msgboxfoot">
                    <a href="javascript:void(0)" onclick="document.convert.submit();" class="button">変換実行</a>
                </div>
            </div>
        </form>
        <?php
    }else{
        ?>
        <div class="msgbox">
            <div class="msgboxtop">ショートコード生成</div>
            <div class="msgboxbody">
                <p>
                    ショートコード生成は現在利用できません。
                </p>
            </div>
            <div class="msgboxfoot"></div>
        </div>
        <?php
    }
    ?>
</div>
</body>
</html>
