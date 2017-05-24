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
        if(isset($_POST['actor_'.$a])){
            $defined[$a] = htmlspecialchars($_POST['actor'.$a]);
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
                $script[1] = mb_substr($script[1],0,-1,"UTF-8");
                $code = BuildShortcode($script[0],$defined[$script[0]]);
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

var_dump($converted);