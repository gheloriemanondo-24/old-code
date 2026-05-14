<?php
// pages/program/programList.php
$db = getDB();

$msg    = $_GET['msg']    ?? '';
$collid = $_GET['collid'] ?? '';
$deptid = $_GET['deptid'] ?? '';

// Build query based on filters
if ($collid && $deptid) {
    $stmt = $db->prepare("
        SELECT p.*, c.collshortname, d.deptfullname
        FROM programs p
        JOIN colleges c ON p.progcollid = c.collid
        JOIN departments d ON p.progcolldeptid = d.deptid
        WHERE p.progcollid = ? AND p.progcolldeptid = ?
        ORDER BY p.progid
    ");
    $stmt->execute([$collid, $deptid]);
} elseif ($collid) {
    $stmt = $db->prepare("
        SELECT p.*, c.collshortname, d.deptfullname
        FROM programs p
        JOIN colleges c ON p.progcollid = c.collid
        JOIN departments d ON p.progcolldeptid = d.deptid
        WHERE p.progcollid = ?
        ORDER BY p.progid
    ");
    $stmt->execute([$collid]);
} else {
    $stmt = $db->query("
        SELECT p.*, c.collshortname, d.deptfullname
        FROM programs p
        JOIN colleges c ON p.progcollid = c.collid
        JOIN departments d ON p.progcolldeptid = d.deptid
        ORDER BY p.progid
    ");
}
$progs = $stmt->fetchAll();
$totalCount = count($progs);
?>
<div class="section-header"><h2>Program List</h2></div>

<?php if ($msg === 'created'): ?><div class="alert alert-success">✅ Program created successfully.</div>
<?php elseif ($msg === 'updated'): ?><div class="alert alert-success">✅ Program updated successfully.</div>
<?php elseif ($msg === 'deleted'): ?><div class="alert alert-info">🗑️ Program deleted.</div>
<?php elseif ($msg === 'error'): ?><div class="alert alert-danger">❌ Error. Cannot delete — linked records exist.</div>
<?php endif; ?>

<div style="margin-bottom:14px; display:flex; gap:10px;">
    <a href="LogIn.php?section=program&page=programCreate" class="btn btn-green">➕ Create Program Entry</a>
    <a href="LogIn.php?section=program&page=chooseSchoolAndDepartment" class="btn btn-gray">🔍 Filter by School/Department</a>
</div>

<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Prog ID</th>
            <th>Program Full Name</th>
            <th>Short</th>
            <th>School</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($progs)): ?>
        <tr><td colspan="6" style="text-align:center;color:#999;padding:24px;">No records found.</td></tr>
    <?php else: foreach ($progs as $p): ?>
    <tr>
        <td><?= htmlspecialchars($p['progid']) ?></td>
        <td><?= htmlspecialchars($p['progfullname']) ?></td>
        <td><?= htmlspecialchars($p['progshortname']) ?></td>
        <td><?= htmlspecialchars($p['collshortname']) ?></td>
        <td style="font-size:12px;"><?= htmlspecialchars($p['deptfullname']) ?></td>
        <td>
            <a href="LogIn.php?section=program&page=programUpdate&id=<?= $p['progid'] ?>" class="btn btn-green btn-sm">✏️ Update</a>
            <button onclick="confirmDelete('LogIn.php?section=program&page=programDelete&id=<?= $p['progid'] ?>','<?= htmlspecialchars(addslashes($p['progfullname'])) ?>')" class="btn btn-red btn-sm">🗑️ Delete</button>
        </td>
    </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
</div>
<p class="total-row">Total of: <?= $totalCount ?> program<?= $totalCount !== 1 ? 's' : '' ?> in the database</p>