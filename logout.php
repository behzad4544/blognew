<?php
require('functions.php');
if (!auth()) {
    redirect('login.php');
}
logOut();
