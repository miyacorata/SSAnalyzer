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
if(!isset($_POST['body'])){
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
<h2>解析結果</h2>
<p>全体の行数</p>
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
<p>人物別カウント</p>
<table>
    <?php
    if(isset($actorcount)){
        foreach ($actorcount as $actor){
            ?>
            <tr>
                <th><?php echo $actor['actor'] ?></th><td><?php echo $actor['times'] ?></td>
            </tr>
            <?php
        }
    }
    ?>
</table>
<p>
    処理時間 : <?php echo $processtime; ?>
</p>
<h2>入力内容</h2>
<div style="height: 500px;overflow-y: scroll">
    <pre>
<?php echo $_POST['body']; ?>
    </pre>
</div>
</body>
</html>
