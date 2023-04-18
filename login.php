<?php
require('./functions.php');
if (auth()) {
    redirect("panel.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $errors = [];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $errors = validateLogin($email, $password);
    if (!count($errors)) {
        $users = getData("users");
        $login = login($users, $email, $password);
        if ($login) {
            $_SESSION['user'] = $login;
            redirect('panel.php');
        } else {
            $errors[] = "Invalid username or password";
        }
    }
}


?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Login to system</title>

    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/panel.css') ?>">
</head>

<body>
    <main>
        <form method='post'>
            <div class='login'>
                <h3>Login to system</h3>
                <?php if (isset($errors) and count($errors)) : ?>
                    <div class="errors">
                        <ul>
                            <?php foreach ($errors as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif ?>
                <div>
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?= isset($email) ? $email : "";  ?>">
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                </div>
                <div>
                    <input type="submit" value="Login">
                </div>
            </div>
        </form>
    </main>
</body>

</html>