<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'usjr');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('<div style="padding:20px;color:red;font-family:sans-serif;">
                <h3>Database Connection Error</h3>
                <p>' . htmlspecialchars($e->getMessage()) . '</p>
                <p>Please check your database configuration in <code>includes/db.php</code></p>
            </div>');
        }
    }
    return $pdo;
}
?>
