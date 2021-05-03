<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Db.php';

$db = Db::getInstance();

$arCategory = $db->getCategories();

$getCaterory = $_GET['category'] ?: '';

$offset = ($_GET['page'] > 1) ? ($_GET['page']- 1) * $db->getPrevLimit() : 0;
$arPreview = $db->getPreviewData($getCaterory, $offset);

$countPosts = $db->getCountPosts($getCaterory);
$prevLimit = $db->getPrevLimit();
if($countPosts > $prevLimit){
    $countPages = ceil($countPosts[0] / $prevLimit);
    $countPages = $countPages > 1 ? $countPages : 0;

}

if($_GET['article']){
    $postData = $db->getPost($_GET['article']);
}

