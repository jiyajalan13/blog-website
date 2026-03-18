<?php
require_once "../config/db.php";
require_once "../includes/header.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password']; 

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Plain text comparison as requested
    if ($user && $user['password'] === $password) {
        $_SESSION['user'] = $user;
        
        // Using standard PHP header redirect
        header("Location: ../index.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <p style="color: red; text-align: center; margin-bottom: 15px;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="auth-link">
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>