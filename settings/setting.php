<?php
/**
 * SSAnalyzer設定ファイル
 */

// ショートコード生成 true:有効 false:無効
$enable_sc = false;

/*  ## これより下は編集すべきではありません。 ##  */

//ファイル名
$file = __DIR__."/teigi.txt";

//存在確認
if(!file_exists($file)){
    $enable_sc = false;
}