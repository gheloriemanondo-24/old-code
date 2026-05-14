<?php
// pages/department/router.php
$page = $_GET['page'] ?? 'chooseSchool';
switch ($page) {
    case 'chooseSchool':     include __DIR__ . '/chooseSchool.php';     break;
    case 'departmentList':   include __DIR__ . '/departmentList.php';   break;
    case 'departmentCreate': include __DIR__ . '/departmentCreate.php'; break;
    case 'departmentUpdate': include __DIR__ . '/departmentUpdate.php'; break;
    case 'departmentDelete': include __DIR__ . '/departmentDelete.php'; break;
    default: echo '<div class="alert alert-danger">Page not found.</div>';
}