<?php
session_start();
session_destroy();
$_SESSION['notification'] = ['type' => 'success', 'title' => 'خروج موفق', 'message' => 'شما با موفقیت از حساب خود خارج شدید'];
header('Location: index.php');
exit();
?>