<?php
$db = getDB();
$id = $_GET['id'] ?? '';
$collid = $_GET['collid'] ?? $_POST['collid'] ?? '';
$errors = [];

$row = $db->prepare("SELECT * FROM departments WHERE deptid=?");
$row->execute([$id]);
$dept = $row->fetch();

if (!$dept) {
    echo '<div class="alert alert-danger">Department not found.</div>';
    echo '<a href="LogIn.php?section=department&page=departmentList" class="btn btn-gray">Back</a>';
    return;
}

$deptfullname  = $dept['deptfullname'];
$deptshortname = $dept['deptshortname'];
$deptcollid    = $dept['deptcollid'];
$successMsg    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formAction'] ?? '') === 'deptUpdate') {
    $newFullname  = trim($_POST['deptfullname'] ?? '');
    $newShortname = trim($_POST['deptshortname'] ?? '');

    if ($newFullname === '') $errors['deptfullname'] = 'Department Full Name cannot be empty';

    if (empty($errors)) {
        if ($newFullname === $dept['deptfullname'] && $newShortname === $dept['deptshortname']) {
            $successMsg = 'Nothing to update. Original entry matches current entry.';
        } else {
            $db->prepare("UPDATE departments SET deptfullname=?,deptshortname=? WHERE deptid=?")
               ->execute([$newFullname, $newShortname, $id]);
            $successMsg = 'Department entry updated successfully';
        }
        $deptfullname  = $newFullname;
        $deptshortname = $newShortname;
    }
}
?>
<div style="padding: 8px 32px; max-width: 860px;">
    <h2 style="font-size:16px; font-weight:700; margin-bottom:16px;">Department Update</h2>

    <?php if ($successMsg): ?>
        <p style="color:#155724; font-size:14px; margin-bottom:12px;"><?= htmlspecialchars($successMsg) ?></p>
    <?php endif; ?>

    <form method="POST" id="deptUpdateForm">
        <input type="hidden" name="formAction" value="deptUpdate">
        <input type="hidden" name="collid" value="<?= htmlspecialchars($collid) ?>">

        <!-- Department ID (read-only) -->
        <div style="display:grid; grid-template-columns: 180px 360px 1fr; align-items:center; gap:10px; margin-bottom:12px;">
            <label style="font-size:14px;">Department ID:</label>
            <input type="text" value="<?= htmlspecialchars($dept['deptid']) ?>" disabled
                style="padding:5px 10px; border:1px solid #dee2e6; border-radius:4px; font-size:14px; width:100%; background:#f0f0f0;">
            <span></span>
        </div>

        <!-- Department Full Name -->
        <div style="display:grid; grid-template-columns: 180px 360px 1fr; align-items:center; gap:10px; margin-bottom:12px;">
            <label style="font-size:14px;">Department Full Name:</label>
            <input type="text" id="deptfullname" name="deptfullname" value="<?= htmlspecialchars($deptfullname) ?>"
                style="padding:5px 10px; border:1px solid #dee2e6; border-radius:4px; font-size:14px; width:100%;">
            <span class="error-msg" style="color:red; font-size:13px;"><?= $errors['deptfullname'] ?? '' ?></span>
        </div>

        <!-- Department Short Name -->
        <div style="display:grid; grid-template-columns: 180px 360px 1fr; align-items:center; gap:10px; margin-bottom:12px;">
            <label style="font-size:14px;">Department Short Name:</label>
            <input type="text" id="deptshortname" name="deptshortname" value="<?= htmlspecialchars($deptshortname) ?>"
                placeholder="None specified"
                style="padding:5px 10px; border:1px solid #dee2e6; border-radius:4px; font-size:14px; width:100%;">
            <span></span>
        </div>

        <div style="display:flex; gap:8px; margin-top:16px;">
            <button type="submit" class="btn btn-gray" style="font-size:13px;">Update Department Entry</button>
            <button type="button" class="btn btn-gray" style="font-size:13px;" onclick="resetForm()">Reset Form</button>
            <a href="LogIn.php?section=department&page=departmentList&collid=<?= htmlspecialchars($collid) ?>" class="btn btn-red" style="font-size:13px;">Exit</a>
        </div>
    </form>
</div>

<script>
const origFullname  = <?= json_encode($dept['deptfullname']) ?>;
const origShortname = <?= json_encode($dept['deptshortname']) ?>;

function resetForm() {
    document.getElementById('deptfullname').value  = origFullname;
    document.getElementById('deptshortname').value = origShortname;
    document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
}
</script>