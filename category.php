<?php
if (!isset($_GET['category'])) {
    redirect("./index.php");
}
require "./functions.php";
$setting = getData("setting");
$posts = getData("posts");
$postOrders = getPostOrderByView($posts);
$postLasts = lastPost($posts);
$category = $_GET['category'];
$cats = getPostsByCategory($posts, $category);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?= $setting['description'] ?>">
    <meta name="keyword" content="<?= $setting['keyword'] ?>">
    <meta name="author" content="<?= $setting['author'] ?>">

    <title><?= $setting['title'] ?></title>
    <link rel="stylesheet" href="<?= asset("css/style.css") ?>">
</head>

<body>
    <main>

        <?php require("./parts/header.php") ?>
        <?php require("./parts/navbar.php") ?>
        <section id="content">
            <?php require("./parts/section.php") ?>
            <div id="articles">
                <?php if ($cats != null) : ?>
                    <?php foreach ($cats as $post) : ?>
                        <article>
                            <div class="caption">
                                <h3><?= $post['title'] ?></h3>
                                <ul>
                                    <li>Date: <span><?= date('Y M d', strtotime($post['date'])) ?></span></li>
                                    <li>Views: <span> <?= $post['view'] ?></span></li>
                                </ul>
                                <p>
                                    <?= summary($post['content']) ?>
                                </p>
                                <a href="single.php?id=<?= $post['id'] ?>">More...</a>
                            </div>
                            <div class="image">
                                <img src="<?= asset($post['image']) ?>" alt="<?= $post['title'] ?>">
                            </div>
                            <div class="clearfix"></div>
                        </article>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
            <div class="clearfix"></div>
        </section>
        <?php require("./parts/footer.php") ?>
    </main>
</body>

</html>