<?php
$categories = getCategori($posts);
?>

<nav>
    <ul>
        <li><a href="http://behzad.com/blog">Home</a></li>
        <?php foreach ($categories as $categori) : ?>
        <li><a href="category.php?category=<?= $categori ?>"><?= $categori ?></a></li>
        <?php endforeach; ?>
        <?php if (isset($_SESSION['user'])) : ?>
        <li><a href="panel.php">Panel</a></li>
        <?php else : ?>
        <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
    <form action="search.php" method="GET">
        <input type="text" name="search" placeholder="Search your word" value="<?= (isset($search)) ? $search : "" ?>">
        <input type="submit" value="Search">
    </form>
</nav>