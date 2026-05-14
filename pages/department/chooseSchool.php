<?php
// pages/department/chooseSchool.php
$db = getDB();
$schools = $db->query("SELECT * FROM colleges ORDER BY collfullname")->fetchAll();
$selectedSchool = $_GET['collid'] ?? '';
?>
<div class="form-section">
    <h2>Select School</h2>

    <form method="GET" action="LogIn.php" id="chooserForm">
        <input type="hidden" name="section" value="department">
        <input type="hidden" name="page" value="chooseSchool">

        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
            <select name="collid" style="padding:8px 12px; border:1px solid #dee2e6; border-radius:4px; font-size:14px; width:360px;">
                <option value="">Select School</option>
                <?php foreach ($schools as $s): ?>
                <option value="<?= $s['collid'] ?>" <?= $selectedSchool == $s['collid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['collfullname']) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-green">Select School</button>
        </div>
    </form>

    <?php if ($selectedSchool): ?>
        <?php
        // Redirect to department list filtered by school
        header("Location: LogIn.php?section=department&page=departmentList&collid=$selectedSchool");
        exit;
        ?>
    <?php endif; ?>
</div>
