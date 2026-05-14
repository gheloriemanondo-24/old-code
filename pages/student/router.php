<?php
$page = $_GET['page'] ?? 'studentList';
switch ($page) {
    case 'studentList':   include __DIR__ . '/studentList.php';   break;
    case 'studentCreate': include __DIR__ . '/studentCreate.php'; break;
    case 'studentUpdate': include __DIR__ . '/studentUpdate.php'; break;
    case 'studentDelete': include __DIR__ . '/studentDelete.php'; break;
    default: echo '<div class="alert alert-danger">Page not found.</div>';
}
