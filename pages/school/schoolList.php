<?php
// pages/school/schoolList.php
$db = getDB();

$msg = $_GET['msg'] ?? '';

// Pagination
$perPage     = 6;
$totalCount  = $db->query("SELECT COUNT(*) FROM colleges")->fetchColumn();
$totalPages  = max(1, ceil($totalCount / $perPage));
$currentPage = max(1, min((int)($_GET['p'] ?? 1), $totalPages));
$offset      = ($currentPage - 1) * $perPage;

$schools = $db->prepare("SELECT * FROM colleges ORDER BY collid LIMIT ? OFFSET ?");
$schools->execute([$perPage, $offset]);
$schools = $schools->fetchAll();
?>
<div class="section-header">
    <h2>School List</h2>
</div>

<?php if ($msg === 'created'): ?>
    <div class="alert alert-success">✅ School entry created successfully.</div>
<?php elseif ($msg === 'updated'): ?>
    <div class="alert alert-success">✅ School entry updated successfully.</div>
<?php elseif ($msg === 'deleted'): ?>
    <div class="alert alert-info">🗑️ School entry deleted.</div>
<?php elseif ($msg === 'error'): ?>
    <div class="alert alert-danger">❌ An error occurred. Please try again.</div>
<?php endif; ?>

<div style="margin-bottom:14px;">
    <a href="LogIn.php?section=school&page=schoolCreate" class="btn btn-green">➕ Create School Entry</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>School ID</th>
                <th>School Full Name</th>
                <th>School Short Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($schools)): ?>
            <tr><td colspan="4" style="text-align:center;color:#999;padding:24px;">No records found.</td></tr>
            <?php else: ?>
            <?php foreach ($schools as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['collid']) ?></td>
                <td><?= htmlspecialchars($s['collfullname']) ?></td>
                <td><?= htmlspecialchars($s['collshortname']) ?></td>
                <td>
                    <a href="LogIn.php?section=school&page=schoolUpdate&id=<?= $s['collid'] ?>&p=<?= $currentPage ?>" class="btn btn-green btn-sm">✏️ Update</a>
                    <button onclick="confirmDelete('LogIn.php?section=school&page=schoolDelete&id=<?= $s['collid'] ?>','<?= htmlspecialchars(addslashes($s['collfullname'])) ?>')" class="btn btn-red btn-sm">🗑️ Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div style="display:flex; align-items:center; justify-content:space-between; margin-top:14px;">
    <p class="total-row">Total of: <?= $totalCount ?> school<?= $totalCount != 1 ? 's' : '' ?> in the database</p>
    <div style="display:flex; gap:8px;">
        <?php if ($currentPage > 1): ?>
            <a href="LogIn.php?section=school&page=schoolList&p=<?= $currentPage - 1 ?>" class="btn btn-gray btn-sm">← Previous</a>
        <?php else: ?>
            <button class="btn btn-gray btn-sm" disabled style="opacity:0.4; cursor:not-allowed;">← Previous</button>
        <?php endif; ?>

        <span style="padding:5px 12px; font-size:13px; color:#555;">Page <?= $currentPage ?> of <?= $totalPages ?></span>

        <?php if ($currentPage < $totalPages): ?>
            <a href="LogIn.php?section=school&page=schoolList&p=<?= $currentPage + 1 ?>" class="btn btn-green btn-sm">Next →</a>
        <?php else: ?>
            <button class="btn btn-green btn-sm" disabled style="opacity:0.4; cursor:not-allowed;">Next →</button>
        <?php endif; ?>
    </div>
</div>