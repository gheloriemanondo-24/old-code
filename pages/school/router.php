<?php
// pages/school/router.php
$page = $_GET['page'] ?? 'schoolList';

switch ($page) {
    case 'schoolList':   include __DIR__ . '/schoolList.php';   break;
    case 'schoolCreate': include __DIR__ . '/schoolCreate.php'; break;
    case 'schoolUpdate': include __DIR__ . '/schoolUpdate.php'; break;
    case 'schoolDelete': include __DIR__ . '/schoolDelete.php'; break;
    default: echo '<div class="alert alert-danger">Page not found.</div>';
}
