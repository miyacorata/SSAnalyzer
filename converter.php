<?php
/**
 * Created by PhpStorm.
 * User: miyano
 * Date: 17/05/23
 * Time: 23:49
 */
session_start();

//入力確認
if(!isset($_SESSION['check']) && !isset($_POST['body']) && !isset($_POST['check']) && !isset($_POST['actor'])){
    $_SESSION['error'] = "エラーが発生しました";
    $_SESSION['message'] = "入力に不足があります";
    header("Location: ./index.php");
    exit();
}else if($_POST['check'] !== $_SESSION['check']){
    $_SESSION['error'] = "エラーが発生しました";
    $_SESSION['message'] = "データに何らかの誤りがあります";
    header("Location: ./index.php");
    exit();
}else{
    $content = explode("\n",htmlspecialchars($_POST['body']));
    $actors = explode(",",htmlspecialchars($_POST['actor']));
}

require_once __DIR__."/settings/loaddifinition.php";

//定義読み込み
$defines = LoadDifinition();
if(!$defines){
    $_SESSION['error'] = "ショートコード生成は利用できません";
    $_SESSION['message'] = "定義を読み込むことができませんでした";
    header("Location: ./index.php");
}else{
    foreach ($actors as $a){
        if(!empty($_POST['actor_'.$a])){
            $defined[$a] = htmlspecialchars($_POST['actor_'.$a]);
        }
    }
}

//ショートコード生成
function BuildShortcode($name,$icon,$subtype = "L1",$type = "std"){
    $code = "[speech_bubble ";
    $code .= "type=\"".$type."\" ";
    $code .= "subtype=\"".$subtype."\" ";
    $code .= "icon=\"".$icon."\" ";
    $code .= "name=\"".$name."\"]";
    return $code;
}

//解析&変換
$actorcount = array();
$converted = array();
if(isset($content)){
    foreach ($content as $row){
        //判定
        $row = str_replace(array("\r\n", "\r", "\n"),"",$row);
        if(preg_match("/^.+「.*」/m",$row) === 1){
            $script = explode("「",$row);
            if(isset($defined[$script[0]])){
//                $script[1] = mb_substr($script[1],0,-1,"UTF-8");
                $script[1] = str_replace("」","",$script[1]);
                $code = BuildShortcode($script[0],$defines[$defined[$script[0]]]);
                $code .= $script[1];
                $code .= "[/speech_bubble]";
                $converted[] = $code;
            }else{
                $converted[] = $row;
            }
        }else{
            $converted[] = $row;
        }
    }
}

//テキスト生成
$text = "";
foreach ($converted as $line){
    $text .= $line."\n";
}

?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>変換結果</title>
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
<div id="header">
    <div class="sitename">SSAnalyzer<span style="font-size: 15px"> - Short Story Analyzer</span></div>
    <div class="headmenu">
        <ul>
            <li><a href="index.php">TOP</a></li>
        </ul>
    </div>
</div>
<div id="content">
    <h1>変換結果</h1>
    <div class="msgbox">
        <div class="msgboxtop">出力</div>
        <div class="msgboxbody">
            <textarea title="出力結果"><?php echo $text; ?></textarea>
            <p>
                コピーしてお使いください。
            </p>
        </div>
        <div class="msgboxfoot"></div>
    </div>
    <div class="msgbox">
        <div class="msgboxtop">Thank you</div>
        <div class="msgboxbody">
            <h3>フィードバックのお願い</h3>
            <p>
                ご利用ありがとうございます。
            </p>
            <p>
                このツールはまだつくりたてです。<br>
                もしバグを発見した、またご意見ご質問等がある場合は、お手数ですが開発者の
                <a href="https://twitter.com/miyacorata" target="_blank">Twitter</a> で質問頂くか、
                <a href="https://github.com/miyacorata/SSAnalyzer" target="_blank">SSAnalyzerのリポジトリ</a>
                に詳しい状況とともにIssueを書いてください。よろしくお願いします。
            </p>
        </div>
        <div class="msgboxfoot">
            <a href="http://twitter.com/miyacorata" target="_blank" class="button">開発者Twitter</a>
            <a href="https://github.com/miyacorata/SSAnalyzer" target="_blank" class="button">GitHubリポジトリ</a>
        </div>
    </div>
</div>
</html>
