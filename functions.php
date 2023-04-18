<?php
session_start();

define("BASE_URL", 'http://behzad.com/blog/');

function asset($file)
{
    return BASE_URL . 'assets/' . $file;
}
function assetPic($file)
{
    return BASE_URL . 'assets/images/' . $file;
}
function getData($fileData)
{
    $fileAddress = "database/" . $fileData . '.json';
    $file = fopen($fileAddress, 'r+');
    $database = fread($file, filesize($fileAddress));
    fclose($file);
    return json_decode($database, true);
}
function setData($fileData, $newData)
{
    $newData = json_encode($newData);
    $fileAddress = "database/" . $fileData . '.json';
    $file = fopen($fileAddress, 'w+');
    $database = fwrite($file, $newData);
    fclose($file);
    return true;
}
function dd($data)
{
    die("<pre>" . var_export($data) . "</pre>");
}
function summary($content, $cont = 150)
{
    return substr($content, 0, $cont) . "...";
}
function getPostOrderByView($posts)
{
    uasort($posts, function ($first, $second) {
        if ($first['view'] > $second['view']) {
            return -1;
        } else {
            return 1;
        }
    });
    $posts = array_values($posts);
    return count($posts) ? $posts : null;
}
function getLastId($posts)
{
    uasort($posts, function ($first, $second) {
        if ($first['id'] > $second['id']) {
            return -1;
        } else {
            return 1;
        }
    });
    $posts = array_values($posts);
    return count($posts) ? $posts[0]['id'] : null;
}
function lastPost($posts)
{
    uasort($posts, function ($first, $second) {
        if (strtotime($first['date']) > strtotime($second['date'])) {
            return -1;
        } else {
            return 1;
        }
    });
    $posts = array_values($posts);
    return count($posts) ? $posts : null;
}
function redirect($url)
{
    header("location: $url");
    exit();
}
function getPostsById($posts, $id)
{
    $post = array_filter($posts, function ($posts) use ($id) {
        if ($posts['id'] == $id) {
            return true;
        } else {
            return false;
        }
    });
    $post = array_values($post);
    return (count($post)) ? $post[0] : null;
}
function getPostsBySearch($posts, $search)
{
    $searchPost = array_filter($posts, function ($posts) use ($search) {
        if (strpos($posts['title'], $search) !== false or strpos($posts['content'], $search) !== false) {
            return true;
        } else {
            return false;
        }
    });
    $searchPost = array_values($searchPost);
    return (count($searchPost)) ? $searchPost : null;
}
function getCategori($posts)
{
    $cat = [];
    foreach ($posts as $post) {
        array_push($cat, $post['category']);
    }
    $cat = array_unique($cat);
    $cat = array_values($cat);
    return $cat;
}
function getPostsByCategory($posts, $category)
{
    $categoryPost = array_filter($posts, function ($posts) use ($category) {
        if ($posts['category'] == $category) {
            return true;
        } else {
            return false;
        }
    });
    $categoryPost = array_values($categoryPost);
    return (count($categoryPost)) ? $categoryPost : null;
}
function deletePost($posts, $id)
{
    $posts = array_filter($posts, function ($post) use ($id) {
        if ($post['id'] != $id) {
            return true;
        } else {
            deleteImage($post['image']);
            return false;
        }
    });
    $posts = array_values($posts);
    setData('posts', $posts);
    return true;
}
function createPost($posts, $title, $category, $content, $image)
{
    $lastId = getLastId($posts);
    $id = $lastId + 1;
    $imageName = uploadImage($image);
    $date = time();
    $newPost = [
        'id' => $id,
        'title' => $title,
        'content' => $content,
        'category' => $category,
        'view' => 0,
        'image' => $imageName,
        'date' => date("Y-m-d H:i:s", $date)
    ];
    $posts[] = $newPost;
    setData('posts', $posts);
    return true;
}
function validatePost($title, $category, $content, $image)
{
    $errors = array();
    $cat = ['sport', 'social', 'political'];
    if (empty($title)) {
        $errors[] = 'Title must not be empty';
    } elseif (!strlen($title) > 3) {
        $errors[] = 'Title must be at least 3 characters';
    }
    if (empty($category)) {
        $errors[] = 'Category Dosent selected';
    } elseif (in_array($cat, $category)) {
        $errors[] = 'Category isnt valid';
    }
    if (empty($content)) {
        $errors[] = 'Content must not be empty';
    } elseif (!strlen($content) > 5) {
        $errors[] = 'Content must be at least 5 characters';
    }
    if (!is_array($image)) {
        $errors[] = 'Selected image in Invalid';
    } elseif (empty($image['name'])) {
        $errors[] = 'Please Fill image Fields';
    } elseif ($image['size'] < 500000 && $image['size'] > 10000) {
        $errors[] = 'Image Size must be at least 5Mb';
    } elseif (in_array($image['type'], ['jpeg', 'png', 'gif'])) {
        $errors[] = 'Your file type not allowed';
    }
    return $errors;
}
function validateEditPost($title, $category, $content, $image)
{
    $errors = array();
    $cat = ['sport', 'social', 'political'];
    if (empty($title)) {
        $errors[] = 'Title must not be empty';
    } elseif (!(strlen($title) > 3)) {
        $errors[] = 'Title must be at least 3 characters';
    }
    if (empty($category)) {
        $errors[] = 'Category Dosent selected';
    } elseif (in_array($cat, $category)) {
        $errors[] = 'Category isnt valid';
    }
    if (empty($content)) {
        $errors[] = 'Content must not be empty';
    } elseif (!strlen($content) > 5) {
        $errors[] = 'Content must be at least 5 characters';
    }
    if (!empty($image['name'])) {
        if (!is_array($image)) {
            $errors[] = 'Selected image in Invalid';
        } elseif ($image['size'] > 500000 && $image['size'] < 100) {
            $errors[] = 'Image Size must be at least 5Mb';
        } elseif (in_array($image['type'], ['jpeg', 'png', 'gif'])) {
            $errors[] = 'Your file type not allowed';
        }
    }
    return $errors;
}
function editPost($posts, $id, $title, $content, $category, $image)
{
    $posts = array_map(function ($post) use ($id, $title, $content, $category, $image) {
        if ($post['id'] == $id) {
            $post['title'] = $title;
            $post['content'] = $content;
            $post['category'] = $category;
            if (!empty($image['name'])) {
                deleteImage($post['image']);
                $newImage = uploadImage($image);
                $post['image'] = $newImage;
            }
        }
        return $post;
    }, $posts);
    setData('posts', $posts);
    return true;
}
function login($users, $email, $password)
{
    $user = array_filter($users, function ($user) use ($email, $password) {
        if ($user['email'] == $email && $user['password'] == $password) {
            return true;
        } else {
            return false;
        }
    });
    $user = array_values($user);
    return (count($user)) ? $user[0] : null;
}
function validateLogin($email, $password)
{
    $errors = [];

    if (empty($email)) {
        $errors[] = 'Plaese fill email field.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Plase enter a valid email.';
    }

    if (empty($password)) {
        $errors[] = 'Please fill password field.';
    }

    return $errors;
}
function auth()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}
function logOut()
{
    unset($_SESSION['user']);
    redirect('login.php');
}
function getUserData()
{
    if (auth()) {
        return $_SESSION['user'];
    } else {
        return null;
    }
}
function getUserDataByArgoman($data)
{
    if (auth()) {
        return $_SESSION['user'][$data];
    } else {
        return null;
    }
}
function uploadImage($image)
{
    $dir = "assets/images/";
    $imageName = $image['name'];
    $imageTmp = $image['tmp_name'];
    $extention = pathinfo($imageName, PATHINFO_EXTENSION);
    $new_name = md5(time()) . "." . $extention;
    if (move_uploaded_file($imageTmp, $dir . $new_name)) {
        chmod($dir . $new_name, 0777);
        return "/images/" . $new_name;
    }
}
function deleteImage($image)
{
    if (file_exists("assets/" . $image)) {
        unlink('assets/' . $image);
        return true;
    } else {
        return false;
    }
}
