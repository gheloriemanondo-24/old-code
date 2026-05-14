<?php
$db = getDB();
$id = $_GET['id'] ?? '';
if ($id) {
    try {
        $db->prepare("DELETE FROM departments WHERE deptid=?")->execute([$id]);
        header('Location: LogIn.php?section=department&page=departmentList&msg=deleted');
    } catch (PDOException $e) {
        header('Location: LogIn.php?section=department&page=departmentList&msg=error');
    }
} else { header('Location: LogIn.php?section=department&page=departmentList'); }
exit;
