<?php
// pages/school/schoolCreate.php
$db = getDB();
$errors = [];
$collid = $collfullname = $collshortname = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formAction'] ?? '') === 'schoolCreate') {
    $collid        = trim($_POST['collid'] ?? '');
    $collfullname  = trim($_POST['collfullname'] ?? '');
    $collshortname = trim($_POST['collshortname'] ?? '');

    if ($collid === '')           $errors['collid']        = 'School ID entry cannot be empty';
    elseif (!is_numeric($collid)) $errors['collid']        = 'School ID must be a number';
    if ($collfullname === '')     $errors['collfullname']  = 'School Full Name entry cannot be empty';
    if ($collshortname === '')    $errors['collshortname'] = 'School Short Name entry cannot be empty';

    if (empty($errors)) {
        // Check duplicate ID
        $chk = $db->prepare("SELECT collid FROM colleges WHERE collid = ?");
        $chk->execute([$collid]);
        if ($chk->fetch()) {
            $errors['collid'] = 'School ID already exists';
        } else {
            $stmt = $db->prepare("INSERT INTO colleges (collid, collfullname, collshortname) VALUES (?,?,?)");
            $stmt->execute([$collid, $collfullname, $collshortname]);
            // Keep form values displayed after success (matching image 2)
            $successMsg = 'School entry created successfully';
        }
    }
}
?>
<div class="form-section">
    <h2>School Create</h2>

    <?php if ($successMsg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>

    <form method="POST" id="schoolCreateForm">
        <input type="hidden" name="formAction" value="schoolCreate">

        <div class="form-row">
            <label for="collid">School ID:</label>
            <input type="number" id="collid" name="collid" value="<?= htmlspecialchars($collid) ?>">
            <span class="error-msg"><?= $errors['collid'] ?? '' ?></span>
        </div>

        <div class="form-row">
            <label for="collfullname">School Full Name:</label>
            <input type="text" id="collfullname" name="collfullname" value="<?= htmlspecialchars($collfullname) ?>">
            <span class="error-msg"><?= $errors['collfullname'] ?? '' ?></span>
        </div>

        <div class="form-row">
            <label for="collshortname">School Short Name:</label>
            <input type="text" id="collshortname" name="collshortname" value="<?= htmlspecialchars($collshortname) ?>">
            <span class="error-msg"><?= $errors['collshortname'] ?? '' ?></span>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-green">Save New School Entry</button>
            <button type="button" class="btn btn-gray" onclick="resetForm()">Reset Form</button>
            <a href="LogIn.php?section=school&page=schoolList" class="btn btn-red">Exit</a>
        </div>
    </form>
</div>

<script>
function resetForm() {
    document.getElementById('collid').value = '';
    document.getElementById('collfullname').value = '';
    document.getElementById('collshortname').value = '';
    document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
}
</script>