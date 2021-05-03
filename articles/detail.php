<?php
$title = 'Статьи';
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/articles/controller.php';
?>
<main>
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-12">
            <? require_once $_SERVER['DOCUMENT_ROOT'] . '/articles/templates/aside.php'; ?>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12">
            <h2><?=$postData['title']?></h2>
            <p><?=$postData['content']?></p>
            <p><?=$postData['name']?></p>
            <p><?=$postData['date']?></p>

        </div>
    </div>
</main>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php';
?>

