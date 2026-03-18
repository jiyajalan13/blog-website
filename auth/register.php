<?php
require_once "../config/db.php";
require_once "../includes/header.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; 

    // Check if the email is already in use
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "An account with this email already exists.";
    } else {
        // 1. Insert the new user
        $role = 'user';
        $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $name, $email, $password, $role);
        $insert_stmt->execute();

        // 2. Get the ID of the user we just created
        $new_user_id = $conn->insert_id;

        // 3. Log them in automatically by setting the session
        $_SESSION['user'] = [
            'id' => $new_user_id,
            'name' => $name,
            'email' => $email,
            'role' => $role
        ];

        // 4. Redirect to the homepage instead of login
        header("Location: ../index.php");
        exit();
    }
}
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Register</h2>

        <?php if (!empty($error)): ?>
            <p style="color: red; text-align: center; margin-bottom: 15px;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Create Account</button>
        </form>

        <div class="auth-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>