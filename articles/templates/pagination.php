<nav aria-label="Page navigation example">
    <ul class="pagination">
<!--        <li class="page-item">-->
<!--            <a class="page-link" href="#" aria-label="Previous">-->
<!--                <span aria-hidden="true">&laquo;</span>-->
<!--            </a>-->
<!--        </li>-->
        <? for($i = 1; $i <= $countPages; $i++):?>
        <li class="page-item <?= $i == $pageN ? " active" : ""?>"><a class="page-link" href="/articles/<?= $getCategory ?>?page=<?= $i ?>"><?= $i ?></a></li>
       <?endfor;?>
<!--        <li class="page-item">-->
<!--            <a class="page-link" href="#" aria-label="Next">-->
<!--                <span aria-hidden="true">&raquo;</span>-->
<!--            </a>-->
<!--        </li>-->
    </ul>
</nav>