<?php

// ショートコード生成 true:有効 false:無効
$enable_sc = true;

//ファイル名
$file = __DIR__."/teigi.txt";

// ! これ以下は変更すべきではありません ! //

//存在確認
if(!file_exists($file)){
    $enable_sc = false;
}

/**
 * 定義読み出し関数
 * ショートコード生成が有効でかつ定義ファイル読み込みに成功した時に定義の配列を返す
 * 無効化あるいは定義ファイルの読み込みに失敗した場合falseを返す
 * @param bool $name
 * @return array|bool
 */
function LoadDifinition($name = false){
    global $file,$enable_sc;
    if($enable_sc && $r_defines = file_get_contents($file)){
        $defines = array();
        $r_define = explode("\n",$r_defines);
        foreach ($r_define as $line){
            $line = str_replace(array("\n","\r","\r\n"),"",$line);
            if(preg_match("/^#.*$/",$line)){
                //コメント行
                continue;
            }else if(preg_match("/^.+,.+$/",$line)){
                //セリフ行
                $define = explode(",",$line);
                if(count($define) != 2){
                    continue;
                }
                if($name){
                    $defines[$define[0]] = $define[0];
                }else{
                    $defines[$define[0]] = $define[1];
                }
            }
        }
        return $defines;
    }else{
        return false;
    }
}


/**
 * 有効化チェック関数
 * これいらないはずなんだよなぁ
 * @return bool
 */
function CheckEnable(){
    global $enable_sc;
    return $enable_sc ? true : false;
}