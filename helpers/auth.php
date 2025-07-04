<?php
// Start by clearing any existing session data
session_start();
session_unset();
session_destroy();

// Restart session after clearing
session_start();

// DB connection
$host = 'localhost';
$dbname = 'pharmacy_db'; // change as needed
$user = 'root';       // change as needed
$pass = '';           // change as needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        // Prepare query
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent fixation
            session_regenerate_id(true);

            // Store user info securely in session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];

            // Optional: Redirect by role
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../home.php");
                    break;
                case 'doctor':
                    header("Location: ../home.php");
                    break;
                case 'pharmacist':
                    header("Location: ../home.php");
                    break;
                default:
                    // Unknown role – force logout
                    session_unset();
                    session_destroy();
                    header("Location: ../index.php?error=invalid_role");
            }

            exit();
        } else {
            echo "<script>alert('Invalid username or password.'); window.location.href='../index.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Please fill in both fields.'); window.location.href='../index.php';</script>";
        exit();
    }
}
?>
