<?php
$db = getDB();
$id = $_GET['id'] ?? '';
if ($id) {
    try { $db->prepare("DELETE FROM students WHERE studid=?")->execute([$id]);
          header('Location: LogIn.php?section=student&page=studentList&msg=deleted');
    } catch (PDOException $e) { header('Location: LogIn.php?section=student&page=studentList&msg=error'); }
} else { header('Location: LogIn.php?section=student&page=studentList'); }
exit;
