<?php
// pages/department/departmentCreate.php
$db = getDB();
$errors = [];
$deptid = $deptfullname = $deptshortname = '';
$schools = $db->query("SELECT * FROM colleges ORDER BY collfullname")->fetchAll();

$collid = $_GET['collid'] ?? $_POST['collid'] ?? '';
$successMsg = '';

$schoolName = '';
if ($collid) {
    $s = $db->prepare("SELECT collfullname FROM colleges WHERE collid=?");
    $s->execute([$collid]);
    $school = $s->fetch();
    $schoolName = $school ? $school['collfullname'] : '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formAction'] ?? '') === 'deptCreate') {
    $deptid        = trim($_POST['deptid'] ?? '');
    $deptfullname  = trim($_POST['deptfullname'] ?? '');
    $deptshortname = trim($_POST['deptshortname'] ?? '');
    $deptcollid    = trim($_POST['deptcollid'] ?? '');

    if ($deptid === '')           $errors['deptid']       = 'Department ID cannot be empty';
    elseif (!is_numeric($deptid)) $errors['deptid']       = 'Department ID must be a number';
    elseif (strlen($deptid) > 5)  $errors['deptid']       = 'Department ID must not exceed 5 digits';
    if ($deptfullname === '')     $errors['deptfullname'] = 'Department Full Name cannot be empty';
    if ($deptcollid === '')       $errors['deptcollid']   = 'Please select a school';

    if (empty($errors)) {
        $chk = $db->prepare("SELECT deptid FROM departments WHERE deptid=?");
        $chk->execute([$deptid]);
        if ($chk->fetch()) {
            $errors['deptid'] = 'Department ID already exists. Please use a different ID.';
        } else {
            $db->prepare("INSERT INTO departments (deptid,deptfullname,deptshortname,deptcollid) VALUES(?,?,?,?)")
               ->execute([$deptid, $deptfullname, $deptshortname, $deptcollid]);
            $successMsg = 'Department entry created successfully';
            // Keep values in fields after save
        }
    }
}
?>
<div style="padding: 8px 32px; max-width: 860px;">
    <h2 style="font-size:16px; font-weight:700; margin-bottom:16px;">
        Department Create<?= $schoolName ? ' - ' . htmlspecialchars($collid) . ': ' . htmlspecialchars($schoolName) : '' ?>
    </h2>

    <?php if ($successMsg): ?>
        <p style="color:#155724; font-size:14px; margin-bottom:12px;"><?= htmlspecialchars($successMsg) ?></p>
    <?php endif; ?>

    <form method="POST" id="deptCreateForm">
        <input type="hidden" name="formAction" value="deptCreate">
        <input type="hidden" name="collid" value="<?= htmlspecialchars($collid) ?>">

        <!-- Department ID -->
        <div style="display:grid; grid-template-columns: 180px 360px 1fr; align-items:center; gap:10px; margin-bottom:12px;">
            <label style="font-size:14px;">Department ID:</label>
            <input type="number" id="deptid" name="deptid" value="<?= htmlspecialchars($deptid) ?>"
                style="padding:5px 10px; border:1px solid #dee2e6; border-radius:4px; font-size:14px; width:100%;">
            <span class="error-msg" style="color:red; font-size:13px;"><?= $errors['deptid'] ?? '' ?></span>
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
                style="padding:5px 10px; border:1px solid #dee2e6; border-radius:4px; font-size:14px; width:100%;">
            <span class="error-msg"></span>
        </div>

        <?php if (!$collid): ?>
        <div style="display:grid; grid-template-columns: 180px 360px 1fr; align-items:center; gap:10px; margin-bottom:12px;">
            <label style="font-size:14px;">School:</label>
            <select name="deptcollid" style="padding:5px 10px; border:1px solid #dee2e6; border-radius:4px; font-size:14px; width:100%;">
                <option value="">-- Select School --</option>
                <?php foreach ($schools as $s): ?>
                <option value="<?= $s['collid'] ?>"><?= htmlspecialchars($s['collfullname']) ?></option>
                <?php endforeach; ?>
            </select>
            <span class="error-msg" style="color:red; font-size:13px;"><?= $errors['deptcollid'] ?? '' ?></span>
        </div>
        <?php else: ?>
        <input type="hidden" name="deptcollid" value="<?= htmlspecialchars($collid) ?>">
        <?php endif; ?>

        <div style="display:flex; gap:8px; margin-top:16px;">
            <button type="submit" class="btn btn-gray" style="font-size:13px;">Save New Department Entry</button>
            <button type="button" class="btn btn-gray" style="font-size:13px;" onclick="resetForm()">Reset Form</button>
            <a href="LogIn.php?section=department&page=departmentList&collid=<?= htmlspecialchars($collid) ?>" class="btn btn-red" style="font-size:13px;">Exit</a>
        </div>
    </form>
</div>

<script>
function resetForm() {
    document.getElementById('deptid').value = '';
    document.getElementById('deptfullname').value = '';
    document.getElementById('deptshortname').value = '';
    document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
}
</script>