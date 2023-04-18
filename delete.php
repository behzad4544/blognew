<?php
require('functions.php');
if (!auth()) {
    redirect('login.php');
}

if (!isset($_GET['delete'])) {
    redirect('panel.php');
}
$posts = getData('posts');
$id = $_GET['delete'];
if (!getPostsById($posts, $id)) {
    redirect('panel.php');
}
deletePost($posts, $id);
redirect('panel.php');
