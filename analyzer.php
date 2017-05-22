<?php
/**
 * Created by PhpStorm.
 * User: miyano
 * Date: 17/05/22
 * Time: 20:45
 */

//処理時間
$starttime = microtime(true);

//読み込み確認
if(isset($_POST['body'])){
    return false;
}

//行ごとの配列化
$content = explode("\n",$_POST['body']);

//行数
$rowcount['total'] = count($content);

//総行数カウント初期化
$rowcount['words'] = 0;
$rowcount['narration'] = 0;
$rowcount['blank'] = 0;

//処理
foreach ($content as $row){
    //判定
    if(preg_match("/^.+「.*」$/",$row) == 1){
        $rowcount['serifu']++;
        $script = explode("「",$row);
        if(isset($actorcount[$script[0]])){
            $actorcount[$script[0]]++;
        }else{
            $actorcount[$script[0]] = 1;
        }
    }else if(preg_match("/^( |　).$/",$row) == 1){
        $rowcount['blank']++;
    }else{
        $rowcount['narration']++;
    }
}

//処理終了時間
$processtime = microtime(true) - $starttime;

//結果表示
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>結果表示</title>
</head>
<body>
<p>処理終了</p>
<table>
    <tr>
        <th>総行数</th><td><?php echo $rowcount['total']; ?></td>
    </tr>
    <tr>
        <th>台詞行数</th><td><?php echo $rowcount['serifu']; ?></td>
    </tr>
    <tr>
        <th>ナレ行数</th><td><?php echo $rowcount['blank']; ?></td>
    </tr>
</table>
<p>人物別カウント</p>
<table>
    <?php
    if(isset($actorcount)){
        foreach ($actorcount as $actor){
            ?>
            <tr>
                <th><?php echo key($actor); ?></th><td><?php echo $actor ?></td>
            </tr>
            <?php
        }
    }
    ?>
</table>
<p>
    処理時間 : <?php echo $processtime; ?>
</p>
</body>
</html>
