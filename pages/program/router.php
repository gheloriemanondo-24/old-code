<?php
// pages/program/router.php
$page = $_GET['page'] ?? 'chooseSchoolAndDepartment';

switch ($page) {
    case 'chooseSchoolAndDepartment': include __DIR__ . '/chooseSchoolAndDepartment.php'; break;
    case 'programList':   include __DIR__ . '/programList.php';   break;
    case 'programCreate': include __DIR__ . '/programCreate.php'; break;
    case 'programUpdate': include __DIR__ . '/programUpdate.php'; break;
    case 'programDelete': include __DIR__ . '/programDelete.php'; break;
    default: echo '<div class="alert alert-danger">Page not found.</div>';
}