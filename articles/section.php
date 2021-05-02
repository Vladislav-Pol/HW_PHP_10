<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/articles/controller.php';
?>
<main>
    <aside>
        <? foreach ($arCategory as $category): ?>
            <a href="/articles/<?= $category['code'] ?>"><?= $category['name'] ?></a>
        <? endforeach; ?>
    </aside>
    <main>
        <? foreach ($arPreview as $preview): ?>
            <p><a href="/articles/<?=$preview['cat_code'] . '/' . $preview['post_code']?>"><?=$preview['title']?></a> <?=$preview['date']?></p>
        <? endforeach;?>
    </main>
</main>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';
?>

