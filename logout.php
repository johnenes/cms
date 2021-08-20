<?php
// require_once'./config/init.php';
require_once './admin/config/init.php';
unset($_SESSION['email']);
unset($_SESSION['username']);
unset($_SESSION['id']);
unset($_SESSION['user_role']);
unset($_SESSION['firstname']);
unset($_SESSION['lastname']);

if (isset($_COOKIE['email'])) { // check if Cookies exist then destroy it 
    unset($_COOKIE['email']);
    setcookie('email', '', time() - 864000);
}
redirect('index.php');
