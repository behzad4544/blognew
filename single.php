<?php
include 'functions.php';
if (!isset($_GET['id'])) {
    redirect("./index.php");
}
$id = $_GET['id'];
$setting = getData("setting");
$posts = getData("posts");
$postOrders = getPostOrderByView($posts);
$postLasts = lastPost($posts);
$post = getPostsById($posts, $id);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?= $setting['description'] ?>">
    <meta name="keyword" content="<?= $setting['keyword'] ?>">
    <meta name="author" content="<?= $setting['author'] ?>">

    <title><?= $setting['title'] ?></title>
    <link rel="stylesheet" href='<?= asset("css/style.css") ?>'>
</head>

<body>
    <main>

        <?php require("./parts/header.php") ?>
        <?php require("./parts/navbar.php") ?>
        <section id="content">
            <?php require("./parts/section.php") ?>
            <?php if ($post != null) : ?>
                <div id="articles">
                    <article>
                        <div class="caption">
                            <h3><?= $post['title'] ?></h3>
                            <ul>
                                <li>Date: <span><?= date('Y M d', strtotime($post['date'])) ?></span></li>
                                <li>Views: <span> <?= $post['view'] ?></span></li>
                            </ul>
                            <p>
                                <?= $post['content'] ?>
                            </p>
                        </div>
                        <div class="image">
                            <img src="<?= asset($post['image']) ?>" alt="<?= $post['title'] ?>">
                        </div>
                        <div class="clearfix"></div>
                    </article>
                <?php else : ?>


                    <?php redirect("./index.php") ?>

                <?php endif ?>


                </div>
                <div class="clearfix"></div>
        </section>
        <?php require("./parts/footer.php") ?>
    </main>
</body>

</html>