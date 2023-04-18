<?php
require "functions.php";
if (!auth()) {
    redirect("login.php");
}
if (!isset($_GET['edit'])) {
    redirect("panel.php");
}
$id = $_GET['edit'];
$posts = getData("posts");
$post = getPostsById($posts, $id);
if (is_null($post)) {
    redirect("panel.php");
}
$user = getUserData();
$categories = getCategori($posts);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['category']) && isset($_POST['content']) && isset($_FILES['image'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    $image = $_FILES['image'];
    $errors = validateEditPost($title, $category, $content, $image);
    if (!count($errors)) {
        $posts = getData("posts");
        editPost($posts, $id, $title, $content, $category, $image);
        redirect("panel.php");
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
                    <input type="text" name="title" id="title" value="<?= $post['title'] ?>">
                </div>
                <div>
                    <label for="category">Category</label>
                    <select name="category" id="category">
                        <option value="">Please Selecet One Categori</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category ?>" <?php if ($post['category'] == $category) {
                                                                    echo "selected";
                                                                } ?>><?= $category ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div>
                    <label for="content">Content</label>
                    <textarea name="content" id="content" cols="30" rows="10"><?= $post['content'] ?></textarea>
                </div>
                <div>
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image">
                    <img src="<?= asset($post['image']) ?>">
                </div>
                <div>
                    <input type="submit" value="Edit">
                </div>
            </form>
        </section>
    </main>
</body>

</html>