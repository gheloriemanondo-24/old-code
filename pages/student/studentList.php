<?php
$db = getDB();
$students = $db->query("
    SELECT s.*, c.collshortname, d.deptshortname, p.progshortname
    FROM students s
    JOIN colleges c ON s.studcollid=c.collid
    JOIN departments d ON s.studcolldeptid=d.deptid
    JOIN programs p ON s.studprogid=p.progid
    ORDER BY s.studid
")->fetchAll();
$msg = $_GET['msg'] ?? '';
?>
<div class="section-header"><h2>Student List</h2></div>
<?php if ($msg === 'created'): ?><div class="alert alert-success">✅ Student created successfully.</div>
<?php elseif ($msg === 'updated'): ?><div class="alert alert-success">✅ Student updated successfully.</div>
<?php elseif ($msg === 'deleted'): ?><div class="alert alert-info">🗑️ Student deleted.</div>
<?php elseif ($msg === 'error'): ?><div class="alert alert-danger">❌ An error occurred.</div>
<?php endif; ?>
<div style="margin-bottom:14px;">
    <a href="LogIn.php?section=student&page=studentCreate" class="btn btn-green">➕ Create Student Entry</a>
</div>
<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Stud ID</th><th>Last Name</th><th>First Name</th><th>Middle Name</th>
            <th>School</th><th>Dept</th><th>Program</th><th>Year</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($students)): ?>
    <tr><td colspan="9" style="text-align:center;color:#999;padding:24px;">No records found.</td></tr>
    <?php else: foreach ($students as $s): ?>
    <tr>
        <td><?= htmlspecialchars($s['studid']) ?></td>
        <td><?= htmlspecialchars($s['studlastname']) ?></td>
        <td><?= htmlspecialchars($s['studfirstname']) ?></td>
        <td><?= htmlspecialchars($s['studmidname'] ?? '') ?></td>
        <td><?= htmlspecialchars($s['collshortname']) ?></td>
        <td><?= htmlspecialchars($s['deptshortname']) ?></td>
        <td><?= htmlspecialchars($s['progshortname']) ?></td>
        <td><?= htmlspecialchars($s['studyear']) ?></td>
        <td>
            <a href="LogIn.php?section=student&page=studentUpdate&id=<?= $s['studid'] ?>" class="btn btn-green btn-sm">✏️ Update</a>
            <button onclick="confirmDelete('LogIn.php?section=student&page=studentDelete&id=<?= $s['studid'] ?>','<?= htmlspecialchars(addslashes($s['studfirstname'].' '.$s['studlastname'])) ?>')" class="btn btn-red btn-sm">🗑️ Delete</button>
        </td>
    </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
</div>
<p class="total-row">Total of: <?= count($students) ?> student<?= count($students) !== 1 ? 's' : '' ?> in the database</p>
