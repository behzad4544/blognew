<?php
require('functions.php');
if (!auth()) {
    redirect('login.php');
}
$posts = getData("posts");


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
                <li><a href="create.php">Create post</a></li>
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
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>View</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post) : ?>
                        <tr>
                            <td><?= $post['id'] ?></td>
                            <td><?= $post['title'] ?></td>
                            <td><?= $post['category'] ?></td>
                            <td><?= $post['view'] ?></td>
                            <td><?= date("Y M d", strtotime($post['date'])) ?></td>
                            <td>
                                <a href="edit.php?edit=<?= $post['id'] ?>">Edit</a>
                                <a href="delete.php?delete=<?= $post['id'] ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>