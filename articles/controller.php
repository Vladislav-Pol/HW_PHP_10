<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Db.php';

$db = Db::getInstance();

$arCategory = $db->getCategories();

$getCaterory = $_GET['category'] ?: '';
$arPreview = $db->getPreviewData($getCaterory);

if($_GET['article']){
    $postData = $db->getPost($_GET['article']);
}

