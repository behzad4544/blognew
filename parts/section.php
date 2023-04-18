<aside>
    <?php if ($postOrders != null) : ?>
    <div class="aside-box">
        <h2>Top Posts</h2>
        <ul>
            <?php foreach ($postOrders as $postOrder) : ?>
            <li style="border-bottom:1px solid black;"><a
                    href="single.php?id=<?= $postOrder['id'] ?>"><?= $postOrder['title'] ?>
                    <small>(<?= $postOrder['view'] ?>)</small></a></li>
            <?php endforeach ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="aside-box">
        <?php if ($postLasts != null) : ?>
        <h2>Last Posts</h2>
        <ul>
            <?php foreach ($postLasts as $postlast) : ?>
            <li style="border-bottom:1px solid black;"><a
                    href="single.php?id=<?= $postlast['id'] ?>"><?= $postlast['title'] ?><br><small>(<?= date("Y M d", strtotime($postlast['date'])) ?>)</small></a>
            </li>
            <?php endforeach ?>
            <?php endif ?>

        </ul>
    </div>
</aside>