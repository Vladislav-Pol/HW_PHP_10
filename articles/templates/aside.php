<aside>
    <ul class="list-group">
        <? foreach ($arCategory as $category): ?>
            <li class="list-group-item"><a href="/articles/<?= $category['code'] ?>"><?= $category['name'] ?></a>
            </li>
        <? endforeach; ?>
    </ul>
</aside>