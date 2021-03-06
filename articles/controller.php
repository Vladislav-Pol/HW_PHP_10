<?php
//подключаем классы
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Db_posts.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Db_PDO.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Db_PDO_posts.php';

//подготавливаем параметры для дальнейшего использования
$getCategory = $_GET['category'] ?: '';

//$db = Db_posts::getInstance(); //для работы с MySQL через объект mysqli
$db = Db_PDO_posts::getInstance(); //для работы с MySQL через объект PDO

$arCategory = $db->getCategories();

//проверяем на существование запрашиваемого раздела
if ($_GET['category']) {
    $flag = true;
    foreach ($arCategory as $category) {
        if (in_array($getCategory, $category)) {
            $flag = false;
            break;
        }
    }
    if ($flag) {
        header('Location:/404.php');
        die();
    }
}

$offset = ($pageN > 1) ? ($pageN - 1) * $db->getPrevLimit() : 0;
$arPreview = $db->getPreviewData($getCategory, $offset);

$countPosts = $db->getCountPosts($getCategory);
$prevLimit = $db->getPrevLimit();
$countPages = ceil($countPosts / $prevLimit ?: 1);
$countPages = ($countPages > 1) ? $countPages : 0;

$pageN = $_GET['page'] ?: 1;
$pageN = ($pageN > $countPages) ? $countPages : $pageN;

//проверяем на существование запрашиваемой статьи
if ($_GET['article']) {
    $postData = $db->getPost($_GET['article']);
    if (!$postData) {
        header('Location:/404.php');
        die();
    }
}

