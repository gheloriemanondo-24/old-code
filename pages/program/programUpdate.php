<?php
$db = getDB();
$id = $_GET['id'] ?? '';
$errors = [];
$schools = $db->query("SELECT * FROM colleges ORDER BY collfullname")->fetchAll();

$row = $db->prepare("SELECT * FROM programs WHERE progid=?");
$row->execute([$id]);
$prog = $row->fetch();
if (!$prog) { echo '<div class="alert alert-danger">Program not found.</div>'; return; }

$progfullname   = $prog['progfullname'];
$progshortname  = $prog['progshortname'];
$progcollid     = $prog['progcollid'];
$progcolldeptid = $prog['progcolldeptid'];

// Load depts for current or newly selected school
$selColl = $_POST['progcollid'] ?? $progcollid;
$d = $db->prepare("SELECT * FROM departments WHERE deptcollid=? ORDER BY deptfullname");
$d->execute([$selColl]);
$depts = $d->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formAction'] ?? '') === 'progUpdate') {
    $progfullname   = trim($_POST['progfullname'] ?? '');
    $progshortname  = trim($_POST['progshortname'] ?? '');
    $progcollid     = trim($_POST['progcollid'] ?? '');
    $progcolldeptid = trim($_POST['progcolldeptid'] ?? '');

    if ($progfullname === '')   $errors['progfullname']   = 'Program Full Name cannot be empty';
    if ($progcollid === '')     $errors['progcollid']     = 'Please select a school';
    if ($progcolldeptid === '') $errors['progcolldeptid'] = 'Please select a department';

    if (empty($errors)) {
        $db->prepare("UPDATE programs SET progfullname=?,progshortname=?,progcollid=?,progcolldeptid=? WHERE progid=?")
           ->execute([$progfullname, $progshortname, $progcollid, $progcolldeptid, $id]);
        header('Location: LogIn.php?section=program&page=programList&msg=updated');
        exit;
    }
}
?>
<div class="form-section">
    <h2>Program Update</h2>
    <form method="POST">
        <input type="hidden" name="formAction" value="progUpdate">
        <div class="form-row">
            <label>Program ID:</label>
            <input type="text" value="<?= htmlspecialchars($prog['progid']) ?>" disabled style="background:#f0f0f0;">
            <span></span>
        </div>
        <div class="form-row">
            <label>Program Full Name:</label>
            <input type="text" name="progfullname" value="<?= htmlspecialchars($progfullname) ?>">
            <span class="error-msg"><?= $errors['progfullname'] ?? '' ?></span>
        </div>
        <div class="form-row">
            <label>Program Short Name:</label>
            <input type="text" name="progshortname" value="<?= htmlspecialchars($progshortname) ?>">
            <span></span>
        </div>
        <div class="form-row">
            <label>School:</label>
            <select name="progcollid" onchange="this.form.submit()">
                <option value="">-- Select School --</option>
                <?php foreach ($schools as $s): ?>
                <option value="<?= $s['collid'] ?>" <?= $selColl == $s['collid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['collfullname']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <span class="error-msg"><?= $errors['progcollid'] ?? '' ?></span>
        </div>
        <div class="form-row">
            <label>Department:</label>
            <select name="progcolldeptid">
                <option value="">-- Select Department --</option>
                <?php foreach ($depts as $d): ?>
                <option value="<?= $d['deptid'] ?>" <?= $progcolldeptid == $d['deptid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['deptfullname']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <span class="error-msg"><?= $errors['progcolldeptid'] ?? '' ?></span>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-green">Update Program</button>
            <a href="LogIn.php?section=program&page=programList" class="btn btn-red">Exit</a>
        </div>
    </form>
</div>
