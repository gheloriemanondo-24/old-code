<?php // pages/home.php
$db = getDB();
$schoolCount = $db->query("SELECT COUNT(*) FROM colleges")->fetchColumn();
$deptCount   = $db->query("SELECT COUNT(*) FROM departments")->fetchColumn();
$progCount   = $db->query("SELECT COUNT(*) FROM programs")->fetchColumn();
$studCount   = $db->query("SELECT COUNT(*) FROM students")->fetchColumn();
?>
<div class="hero">
    <h1>Welcome to USJ-R School Management System</h1>
    <p>Hello, <strong><?= htmlspecialchars($user['username']) ?>!</strong> 👋</p>
    <p>Manage your school's operations efficiently</p>
</div>

<div style="margin-bottom:16px;">
    <h2 
        style="font-size:18px; 
        font-weight:700; 
        padding-bottom:8px; 
        border-bottom:3px solid var(--green);"
    >Quick Access
    </h2>
</div>

<div class="cards-grid" style="grid-template-columns: repeat(5, 1fr);">
    <div class="card">
        <span class="card-icon">🏫</span>
        <h3>Schools</h3>
        <p>Manage school information and details</p>
        <a href="LogIn.php?section=school&page=schoolList" class="btn btn-green btn-sm">View Schools</a>
    </div>

    <div class="card">
        <span class="card-icon">📚</span>
        <h3>Departments</h3>
        <p>Organize departments within schools</p>
        <a href="LogIn.php?section=department&page=departmentList" class="btn btn-green btn-sm">View Departments</a>
    </div>

    <div class="card">
        <span class="card-icon">🎓</span>
        <h3>Programs</h3>
        <p>Manage academic programs and courses</p>
        <a href="LogIn.php?section=program&page=programList" class="btn btn-green btn-sm">View Programs</a>
    </div>

    <div class="card">
        <span class="card-icon">👥</span>
        <h3>Students</h3>
        <p>Manage student records and enrollment</p>
        <a href="LogIn.php?section=student&page=studentList" class="btn btn-green btn-sm">View Students</a>
    </div>

 
 
</div>
