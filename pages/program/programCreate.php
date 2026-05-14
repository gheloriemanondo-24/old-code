<?php
$db = getDB();
$errors = [];
$progid = $progfullname = $progshortname = $progcollid = $progcolldeptid = '';
$schools = $db->query("SELECT * FROM colleges ORDER BY collfullname")->fetchAll();
$depts = [];
if (!empty($_POST['progcollid'])) {
    $d = $db->prepare("SELECT * FROM departments WHERE deptcollid=? ORDER BY deptfullname");
    $d->execute([$_POST['progcollid']]);
    $depts = $d->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formAction'] ?? '') === 'progCreate') {
    $progid        = trim($_POST['progid'] ?? '');
    $progfullname  = trim($_POST['progfullname'] ?? '');
    $progshortname = trim($_POST['progshortname'] ?? '');
    $progcollid    = trim($_POST['progcollid'] ?? '');
    $progcolldeptid= trim($_POST['progcolldeptid'] ?? '');

    if ($progid === '')        $errors['progid']        = 'Program ID cannot be empty';
    elseif (!is_numeric($progid)) $errors['progid']     = 'Program ID must be a number';
    if ($progfullname === '')  $errors['progfullname']  = 'Program Full Name cannot be empty';
    if ($progcollid === '')    $errors['progcollid']    = 'Please select a school';
    if ($progcolldeptid === '') $errors['progcolldeptid'] = 'Please select a department';

    if (empty($errors)) {
        $chk = $db->prepare("SELECT progid FROM programs WHERE progid=?");
        $chk->execute([$progid]);
        if ($chk->fetch()) { $errors['progid'] = 'Program ID already exists'; }
        else {
            $db->prepare("INSERT INTO programs (progid,progfullname,progshortname,progcollid,progcolldeptid) VALUES(?,?,?,?,?)")
               ->execute([$progid, $progfullname, $progshortname, $progcollid, $progcolldeptid]);
            header('Location: LogIn.php?section=program&page=programList&msg=created');
            exit;
        }
    }
}
?>
<div class="form-section">
    <h2>Program Create</h2>
    <form method="POST" id="progForm">
        <input type="hidden" name="formAction" value="progCreate">
        <div class="form-row">
            <label>Program ID:</label>
            <input type="number" name="progid" value="<?= htmlspecialchars($progid) ?>">
            <span class="error-msg"><?= $errors['progid'] ?? '' ?></span>
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
            <select name="progcollid" onchange="this.form.submit()" id="schoolSel">
                <option value="">-- Select School --</option>
                <?php foreach ($schools as $s): ?>
                <option value="<?= $s['collid'] ?>" <?= $progcollid == $s['collid'] ? 'selected' : '' ?>>
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
            <button type="submit" name="formAction" value="progCreate" class="btn btn-gray">Save New Program Entry</button>
            <a href="LogIn.php?section=program&page=programList" class="btn btn-red">Exit</a>
        </div>
    </form>
</div>
