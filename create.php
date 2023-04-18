<?php
require "functions.php";
if (!auth()) {
    redirect('login.php');
}
$user = getUserData();
$posts = getData("posts");
$categories = getCategori($posts);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['category']) && isset($_POST['content']) && isset($_FILES['image'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    $image = $_FILES['image'];
    $errors = validatePost($title, $category, $content, $image);
    if (!count($errors)) {
        createPost($posts, $title, $category, $content, $image);
        redirect('panel.php');
    }
}

?>
<html>

<head>
    <title>Panel</title>

    <link rel="stylesheet" href="<?= asset('css/style.css') ?>" />
    <link rel="stylesheet" href="<?= asset('css/panel.css') ?>" />
</head>

<body>
    <main>
        <nav>
            <ul>
                <li><a href="panel.php">Panel</a></li>
                <li><a href="index.php">Website</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
            <ul>
                <li>Welcome
                    <?= ucfirst(getUserDataByArgoman("first_name")) . " " . ucfirst(getUserDataByArgoman("last_name"))  ?>
                </li>
            </ul>
        </nav>
        <section class="content">
            <?php if (isset($errors) && count($errors)) : ?>

                <div class="errors">
                    <?php foreach ($errors as $error) : ?>
                        <ul>
                            <?= $error ?>;
                        </ul>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div>
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" value="<?= isset($title) ? $title : '' ?>">
                </div>
                <div>
                    <label for="category">Category</label>
                    <select name="category" id="category">
                        <option value="">Please Selecet One Categori</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category ?>" <?php
                                                                if (isset($category) && $_POST['category'] == $category) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?= $category ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div>
                    <label for="content">Content</label>
                    <textarea name="content" id="content" cols="30" rows="10"><?= isset($content) ? $content : '' ?></textarea>
                </div>
                <div>
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image">
                </div>
                <div>
                    <input type="submit" value="Create Post">
                </div>
            </form>
        </section>
    </main>
</body>

</html>