<?php
// pages/school/schoolUpdate.php
$db = getDB();
$id         = $_GET['id'] ?? '';
$returnPage = $_GET['p']  ?? 1;
$errors     = [];
$successMsg = '';

$row = $db->prepare("SELECT * FROM colleges WHERE collid = ?");
$row->execute([$id]);
$school = $row->fetch();

if (!$school) {
    echo '<div class="alert alert-danger">School not found.</div>';
    echo '<a href="LogIn.php?section=school&page=schoolList" class="btn btn-gray">Back to List</a>';
    return;
}

$collid        = $school['collid'];
$collfullname  = $school['collfullname'];
$collshortname = $school['collshortname'];

$origFullname  = $school['collfullname'];
$origShortname = $school['collshortname'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formAction'] ?? '') === 'schoolUpdate') {
    $returnPage    = $_POST['returnPage'] ?? 1;
    $collfullname  = trim($_POST['collfullname'] ?? '');
    $collshortname = trim($_POST['collshortname'] ?? '');

    if ($collfullname === '')  $errors['collfullname']  = 'School Full Name entry cannot be empty';
    if ($collshortname === '') $errors['collshortname'] = 'School Short Name entry cannot be empty';

    if (empty($errors)) {
        if ($collfullname === $origFullname && $collshortname === $origShortname) {
            $successMsg = 'Nothing to update. Original entry matches current entry.';
        } else {
            $stmt = $db->prepare("UPDATE colleges SET collfullname=?, collshortname=? WHERE collid=?");
            $stmt->execute([$collfullname, $collshortname, $collid]);
            $successMsg    = 'School entry updated successfully.';
            $origFullname  = $collfullname;
            $origShortname = $collshortname;
        }
    }
}
?>
<div class="form-section">
    <h2>School Update</h2>

    <?php if ($successMsg): ?>
        <div class="alert <?= str_contains($successMsg, 'Nothing') ? 'alert-info' : 'alert-success' ?>" id="alertMsg">
            <?= htmlspecialchars($successMsg) ?>
        </div>
    <?php endif; ?>

    <form method="POST" id="schoolUpdateForm">
        <input type="hidden" name="formAction" value="schoolUpdate">
        <input type="hidden" name="returnPage" value="<?= htmlspecialchars($returnPage) ?>">

        <div class="form-row">
            <label>School ID:</label>
            <input type="text" value="<?= htmlspecialchars($collid) ?>" disabled style="background:#f0f0f0;">
            <span class="error-msg"></span>
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
            <button type="submit" class="btn btn-green">Update School Entry</button>
            <button type="button" class="btn btn-gray" onclick="resetForm()">Reset Form</button>
            <a href="LogIn.php?section=school&page=schoolList&p=<?= htmlspecialchars($returnPage) ?>" class="btn btn-red">Exit</a>
        </div>
    </form>
</div>

<script>
const origFullname  = <?= json_encode($origFullname) ?>;
const origShortname = <?= json_encode($origShortname) ?>;

function resetForm() {
    document.getElementById('collfullname').value  = origFullname;
    document.getElementById('collshortname').value = origShortname;
    document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
    const alertMsg = document.getElementById('alertMsg');
    if (alertMsg) alertMsg.remove();
}
</script>