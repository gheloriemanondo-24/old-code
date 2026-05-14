<?php
$basePath = '';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Handle login / logout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'login') {
        $ok = login(trim($_POST['username'] ?? ''), $_POST['password'] ?? '');
        if (!$ok) $loginError = 'Invalid username or password.';
    } elseif ($action === 'logout') {
        logout();
    }
}

$user    = getCurrentUser();
$section = $_GET['section'] ?? '';
$page    = $_GET['page']    ?? '';

include 'includes/header.php';

if (!$user) {
    // ── GUEST HOME ──────────────────────────────────────────
    if (isset($loginError)): ?>
    <div class="main-content-full">
        <div class="login-error"><?= htmlspecialchars($loginError) ?></div>
    </div>
    <?php endif; ?>
    <div class="main-content-full">
        <div class="hero">
            <h1>Welcome to USJ-R School Management System</h1>
            <p>Manage your school's operations efficiently</p>
        </div>
        <h2 style="font-size:18px;font-weight:700;margin-bottom:16px;">Quick Access</h2>
        <div class="cards-grid">
            <div class="card">
                <span class="card-icon">🏫</span>
                <h3>Schools</h3>
                <p>Manage school information and details</p>
            </div>
            <div class="card">
                <span class="card-icon">📚</span>
                <h3>Departments</h3>
                <p>Organize departments within schools</p>
            </div>
            <div class="card">
                <span class="card-icon">🎓</span>
                <h3>Programs</h3>
                <p>Manage academic programs and courses</p>
            </div>
            <div class="card">
                <span class="card-icon">👥</span>
                <h3>Students</h3>
                <p>Manage student records and enrollment</p>
            </div>
        </div>
        <div class="getting-started">
            <h3>Getting Started</h3>
            <ol>
                <li>Log in with your credentials</li>
                <li>Navigate to any section using the sidebar menu</li>
                <li>View, create, update, or delete records as needed</li>
                <li>Contact administrator for access requests</li>
            </ol>
        </div>
    </div>
<?php } else {
    // ── AUTHENTICATED ────────────────────────────────────────
    echo '<div class="main-content">';

    if ($section === '') {
        // Dashboard
        include 'pages/home.php';
    } elseif ($section === 'school') {
        include 'pages/school/router.php';
    } elseif ($section === 'department') {
        include 'pages/department/router.php';
    } elseif ($section === 'program') {
        include 'pages/program/router.php';
    } elseif ($section === 'student') {
        include 'pages/student/router.php';
    } elseif ($section === 'user') {
        include 'pages/user/router.php';
    } else {
        echo '<div class="alert alert-danger">Section not found.</div>';
    }

    echo '</div>';
}

include 'includes/footer.php';
?>
