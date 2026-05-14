<?php
// pages/school/schoolDelete.php
$db = getDB();
$id = $_GET['id'] ?? '';

if ($id) {
    try {
        $stmt = $db->prepare("DELETE FROM colleges WHERE collid = ?");
        $stmt->execute([$id]);
        header('Location: LogIn.php?section=school&page=schoolList&msg=deleted');
    } catch (PDOException $e) {
        header('Location: LogIn.php?section=school&page=schoolList&msg=error');
    }
} else {
    header('Location: LogIn.php?section=school&page=schoolList');
}
exit;
