<?php
// Simple user management page (uses hardcoded users from auth.php)
if ($user['role'] !== 'admin') {
    echo '<div class="alert alert-danger">Access denied. Admin only.</div>';
    return;
}
$users_list = [
    ['username' => 'admin', 'role' => 'admin'],
    ['username' => 'staff', 'role' => 'staff'],
];
?>
<div class="section-header"><h2>User Management</h2></div>
<div class="alert alert-info">ℹ️ Users are managed in <code>includes/auth.php</code>. Default credentials: admin/admin and staff/staff.</div>
<div class="table-wrap">
<table>
    <thead><tr><th>#</th><th>Username</th><th>Role</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach ($users_list as $i => $u): ?>
    <tr>
        <td><?= $i+1 ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><span style="background:<?= $u['role']==='admin'?'#fd7e14':'#28a745' ?>;color:#fff;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;"><?= ucfirst($u['role']) ?></span></td>
        <td><span style="color:#28a745;font-weight:600;">● Active</span></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<p class="total-row">Total of: <?= count($users_list) ?> users in the system</p>
