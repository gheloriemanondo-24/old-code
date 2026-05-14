<?php
$db = getDB();
$id = $_GET['id'] ?? '';
if ($id) {
    try { $db->prepare("DELETE FROM programs WHERE progid=?")->execute([$id]);
          header('Location: LogIn.php?section=program&page=programList&msg=deleted');
    } catch (PDOException $e) { header('Location: LogIn.php?section=program&page=programList&msg=error'); }
} else { header('Location: LogIn.php?section=program&page=programList'); }
exit;
