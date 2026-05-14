<?php
// pages/department/departmentList.php
$db = getDB();

$msg    = $_GET['msg']    ?? '';
$collid = $_GET['collid'] ?? '';

// Get school name for title
$schoolName = '';
if ($collid) {
    $s = $db->prepare("SELECT collfullname FROM colleges WHERE collid=?");
    $s->execute([$collid]);
    $school = $s->fetch();
    $schoolName = $school ? $school['collfullname'] : '';
}

// Get departments filtered by school
if ($collid) {
    $stmt = $db->prepare("
        SELECT d.*, c.collshortname 
        FROM departments d 
        JOIN colleges c ON d.deptcollid = c.collid 
        WHERE d.deptcollid = ?
        ORDER BY d.deptid
    ");
    $stmt->execute([$collid]);
} else {
    $stmt = $db->query("
        SELECT d.*, c.collshortname 
        FROM departments d 
        JOIN colleges c ON d.deptcollid = c.collid 
        ORDER BY d.deptid
    ");
}
$depts = $stmt->fetchAll();
?>

<div class="section-header">
    <h2>Department List<?= $schoolName ? ' - ' . htmlspecialchars($schoolName) : '' ?></h2>
</div>

<?php if ($msg === 'created'): ?><div class="alert alert-success">✅ Department created successfully.</div>
<?php elseif ($msg === 'updated'): ?><div class="alert alert-success">✅ Department updated successfully.</div>
<?php elseif ($msg === 'deleted'): ?><div class="alert alert-info">🗑️ Department deleted.</div>
<?php elseif ($msg === 'error'): ?><div class="alert alert-danger">❌ Error. Cannot delete — linked records exist.</div>
<?php endif; ?>

<div style="margin-bottom:14px; display:flex; gap:10px;">
    <a href="LogIn.php?section=department&page=departmentCreate&collid=<?= $collid ?>" class="btn btn-green">➕ Create Department Entry</a>
    <a href="LogIn.php?section=department&page=chooseSchool" class="btn btn-red">⬅ Back</a>
</div>

<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Department ID</th>
            <th>Department Full Name</th>
            <th>Department Short Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($depts)): ?>
        <tr><td colspan="4" style="text-align:center;color:#999;padding:24px;">No records found.</td></tr>
    <?php else: foreach ($depts as $d): ?>
    <tr>
        <td><?= htmlspecialchars($d['deptid']) ?></td>
        <td><?= htmlspecialchars($d['deptfullname']) ?></td>
        <td><?= htmlspecialchars($d['deptshortname']) ?></td>
        <td>
            <a href="LogIn.php?section=department&page=departmentUpdate&id=<?= $d['deptid'] ?>&collid=<?= $collid ?>" class="btn btn-green btn-sm">✏️ Update</a>
            <button onclick="confirmDelete('LogIn.php?section=department&page=departmentDelete&id=<?= $d['deptid'] ?>&collid=<?= $collid ?>','<?= htmlspecialchars(addslashes($d['deptfullname'])) ?>')" class="btn btn-red btn-sm">🗑️ Delete</button>
        </td>
    </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
</div>
<p class="total-row">Total of: <?= count($depts) ?> department<?= count($depts) !== 1 ? 's' : '' ?> in the database</p>