<?php
// pages/program/chooseSchoolAndDepartment.php
$db = getDB();
$schools = $db->query("SELECT * FROM colleges ORDER BY collfullname")->fetchAll();

$selectedSchool = $_GET['collid'] ?? '';
$depts = [];
if ($selectedSchool) {
    $d = $db->prepare("SELECT * FROM departments WHERE deptcollid=? ORDER BY deptfullname");
    $d->execute([$selectedSchool]);
    $depts = $d->fetchAll();
}
?>
<div class="form-section">
    <h2>Select School and Department</h2>

    <form method="GET" action="LogIn.php" id="chooserForm">
        <input type="hidden" name="section" value="program">
        <input type="hidden" name="page" value="chooseSchoolAndDepartment">

        <!-- Row 1: School -->
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <select name="collid" onchange="this.form.submit()" style="padding:8px 12px; border:1px solid #dee2e6; border-radius:4px; font-size:14px; width:360px;">
                <option value="">Select School</option>
                <?php foreach ($schools as $s): ?>
                <option value="<?= $s['collid'] ?>" <?= $selectedSchool == $s['collid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['collfullname']) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <?php if ($selectedSchool): ?>
                <a href="LogIn.php?section=program&page=programList&collid=<?= $selectedSchool ?>" class="btn btn-green">Select School</a>
            <?php else: ?>
                <button type="button" class="btn btn-green" disabled style="opacity:0.6;cursor:not-allowed;">Select School</button>
            <?php endif; ?>
        </div>

        <!-- Row 2: Department (always visible) -->
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <select id="deptSel" style="padding:8px 12px; border:1px solid #dee2e6; border-radius:4px; font-size:14px; width:360px;" <?= empty($depts) ? 'disabled' : '' ?>>
                <option value="">Select Department</option>
                <?php foreach ($depts as $d): ?>
                <option value="<?= $d['deptid'] ?>"><?= htmlspecialchars($d['deptfullname']) ?></option>
                <?php endforeach; ?>
            </select>

            <?php if (!empty($depts)): ?>
                <button type="button" class="btn btn-green" onclick="selectDept()">Select Department</button>
            <?php else: ?>
                <button type="button" class="btn btn-gray" disabled style="opacity:0.5;cursor:not-allowed;">Select Department</button>
            <?php endif; ?>
        </div>

    </form>
</div>

<script>
function selectDept() {
    const collid = <?= json_encode($selectedSchool) ?>;
    const deptid = document.getElementById('deptSel')?.value;
    if (deptid) {
        window.location.href = 'LogIn.php?section=program&page=programList&collid=' + collid + '&deptid=' + deptid;
    }
}
</script>