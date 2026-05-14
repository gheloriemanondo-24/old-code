<?php
$page = $_GET['page'] ?? 'userList';
switch ($page) {
    case 'userList': include __DIR__ . '/userList.php'; break;
    default: echo '<div class="alert alert-danger">Page not found.</div>';
}
