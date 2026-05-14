<?php
$db = getDB();
$id = $_GET['id'] ?? '';
$errors = [];
$schools = $db->query("SELECT * FROM colleges ORDER BY collfullname")->fetchAll();

$row = $db->prepare("SELECT * FROM students WHERE studid=?");
$row->execute([$id]); $stud = $row->fetch();
if (!$stud) { echo '<div class="alert alert-danger">Student not found.</div>'; return; }

$studfirstname  = $stud['studfirstname'];
$studlastname   = $stud['studlastname'];
$studmidname    = $stud['studmidname'];
$studcollid     = $stud['studcollid'];
$studcolldeptid = $stud['studcolldeptid'];
$studprogid     = $stud['studprogid'];
$studyear       = $stud['studyear'];

$selColl = $_POST['studcollid'] ?? $studcollid;
$selDept = $_POST['studcolldeptid'] ?? $studcolldeptid;

$depts = $progs = [];
if ($selColl) { $d=$db->prepare("SELECT * FROM departments WHERE deptcollid=? ORDER BY deptfullname"); $d->execute([$selColl]); $depts=$d->fetchAll(); }
if ($selDept) { $p=$db->prepare("SELECT * FROM programs WHERE progcolldeptid=? ORDER BY progfullname"); $p->execute([$selDept]); $progs=$p->fetchAll(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formAction'] ?? '') === 'studUpdate') {
    $studfirstname  = trim($_POST['studfirstname'] ?? '');
    $studlastname   = trim($_POST['studlastname'] ?? '');
    $studmidname    = trim($_POST['studmidname'] ?? '');
    $studcollid     = trim($_POST['studcollid'] ?? '');
    $studcolldeptid = trim($_POST['studcolldeptid'] ?? '');
    $studprogid     = trim($_POST['studprogid'] ?? '');
    $studyear       = trim($_POST['studyear'] ?? '');

    if ($studfirstname === '')  $errors['studfirstname'] = 'First name cannot be empty';
    if ($studlastname === '')   $errors['studlastname']  = 'Last name cannot be empty';
    if ($studcollid === '')     $errors['studcollid']    = 'Please select a school';
    if ($studcolldeptid === '') $errors['studcolldeptid']= 'Please select a department';
    if ($studprogid === '')     $errors['studprogid']    = 'Please select a program';
    if ($studyear === '')       $errors['studyear']      = 'Please select year';

    if (empty($errors)) {
        $db->prepare("UPDATE students SET studfirstname=?,studlastname=?,studmidname=?,studcollid=?,studcolldeptid=?,studprogid=?,studyear=? WHERE studid=?")
           ->execute([$studfirstname,$studlastname,$studmidname ?: null,$studcollid,$studcolldeptid,$studprogid,$studyear,$id]);
        header('Location: LogIn.php?section=student&page=studentList&msg=updated');
        exit;
    }
}
?>
<div class="form-section">
    <h2>Student Update</h2>
    <form method="POST">
        <input type="hidden" name="formAction" value="studUpdate">
        <div class="form-row">
            <label>Student ID:</label>
            <input type="text" value="<?= htmlspecialchars($stud['studid']) ?>" disabled style="background:#f0f0f0;">
            <span></span>
        </div>
        <div class="form-row">
            <label>Last Name:</label>
            <input type="text" name="studlastname" value="<?= htmlspecialchars($studlastname) ?>">
            <span class="error-msg"><?= $errors['studlastname'] ?? '' ?></span>
        </div>
        <div class="form-row">
            <label>First Name:</label>
            <input type="text" name="studfirstname" value="<?= htmlspecialchars($studfirstname) ?>">
            <span class="error-msg"><?= $errors['studfirstname'] ?? '' ?></span>
        </div>
        <div class="form-row">
            <label>Middle Name:</label>
            <input type="text" name="studmidname" value="<?= htmlspecialchars($studmidname ?? '') ?>">
            <span></span>
        </div>
        <div class="form-row">
            <label>School:</label>
            <select name="studcollid" onchange="this.form.submit()">
                <option value="">-- Select School --</option>
                <?php foreach ($schools as $s): ?>
                <option value="<?= $s['collid'] ?>" <?= $selColl == $s['collid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['collfullname']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <span class="error-msg"><?= $errors['studcollid'] ?? '' ?></span>
        </div>
        <div class="form-row">
            <label>Department:</label>
            <select name="studcolldeptid" onchange="this.form.submit()">
                <option value="">-- Select Department --</option>
                <?php foreach ($depts as $d): ?>
                <option value="<?= $d['deptid'] ?>" <?= $selDept == $d['deptid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['deptfullname']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <span class="error-msg"><?= $errors['studcolldeptid'] ?? '' ?></span>
        </div>
        <div class="form-row">
            <label>Program:</label>
            <select name="studprogid">
                <option value="">-- Select Program --</option>
                <?php foreach ($progs as $p): ?>
                <option value="<?= $p['progid'] ?>" <?= $studprogid == $p['progid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['progfullname']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <span class="error-msg"><?= $errors['studprogid'] ?? '' ?></span>
        </div>
        <div class="form-row">
            <label>Year Level:</label>
            <select name="studyear">
                <?php for ($y=1;$y<=5;$y++): ?>
                <option value="<?= $y ?>" <?= $studyear == $y ? 'selected' : '' ?>>Year <?= $y ?></option>
                <?php endfor; ?>
            </select>
            <span class="error-msg"><?= $errors['studyear'] ?? '' ?></span>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-green">Update Student</button>
            <a href="LogIn.php?section=student&page=studentList" class="btn btn-red">Exit</a>
        </div>
    </form>
</div>
