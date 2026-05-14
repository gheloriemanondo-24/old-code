<?php
// includes/header.php
$currentSection = $_GET['section'] ?? '';
$currentPage    = $_GET['page']    ?? '';
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>USJR School Management System</title>
<link rel="stylesheet" href="<?= $basePath ?>css/style.css">
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
    <a class="topbar-brand" href="<?= $basePath ?>LogIn.php">USJ-R School Management System v1.01</a>
    <div class="topbar-right">
        <?php if ($user): ?>
            <span class="user-info">You are logged in as: <strong><?= htmlspecialchars($user['username']) ?></strong> | 👤</span>
            <form method="POST" action="<?= $basePath ?>LogIn.php" style="margin:0">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        <?php else: ?>
            <form method="POST" action="<?= $basePath ?>LogIn.php" class="login-form">
                <input type="hidden" name="action" value="login">
                <label>Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                <label>Password:</label>
                <input type="password" name="password">
                <button type="submit" class="btn-login">Login</button>
            </form>
        <?php endif; ?>
    </div>
</header>

<?php if ($user): ?>
<!-- SIDEBAR -->
<aside class="sidebar">
    <nav>
        <ul>
            <li><a href="<?= $basePath ?>LogIn.php" class="<?= !$currentSection ? 'active' : '' ?>">Home</a></li>
            <li><a href="<?= $basePath ?>LogIn.php?section=school&page=schoolList" class="<?= $currentSection==='school' ? 'active' : '' ?>">Schools</a></li>
            <li><a href="<?= $basePath ?>LogIn.php?section=department&page=chooseSchool" class="<?= $currentSection==='department' ? 'active' : '' ?>">Departments</a></li>
            <li><a href="<?= $basePath ?>LogIn.php?section=program&page=chooseSchoolAndDepartment" class="<?= $currentSection==='program' ? 'active' : '' ?>">Programs</a></li>
            <li><a href="<?= $basePath ?>LogIn.php?section=student&page=studentList" class="<?= $currentSection==='student' ? 'active' : '' ?>">Students</a></li>
            <?php if ($user['role'] === 'admin'): ?>
            <li><a href="<?= $basePath ?>LogIn.php?section=user&page=userList" class="<?= $currentSection==='user' ? 'active' : '' ?>">Users</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</aside>
<?php endif; ?>