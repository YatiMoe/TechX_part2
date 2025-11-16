<?php
session_start();
require_once("settings.php");

// If logged in, redirect
if (isset($_SESSION['manager_user'])) {
    header("Location: manage.php");
    exit();
}

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? "");
    $password = $_POST['password'] ?? "";
    $password2 = $_POST['password2'] ?? "";

    if ($username === "") $errors[] = "Username is required.";
    if (strlen($username) < 3) $errors[] = "Username must be at least 3 characters.";
    if ($password === "") $errors[] = "Password is required.";
    if ($password !== $password2) $errors[] = "Passwords do not match.";

    // Strong password rule
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $errors[] = "Password must have at least 8 characters, one uppercase, one lowercase, one number.";
    }

    if (empty($errors)) {
        $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
        if (!$conn) die("Database connection failure.");

        // Check if username exists
        $stmt = mysqli_prepare($conn, "SELECT manager_id FROM manager WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = "Username already exists.";
        } else {
            // Insert new manager
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = mysqli_prepare($conn, "INSERT INTO manager (username, password_hash) VALUES (?, ?)");
            mysqli_stmt_bind_param($ins, "ss", $username, $hash);

            if (mysqli_stmt_execute($ins)) {
                $success = "Manager account created successfully! You can now login.";
            } else {
                $errors[] = "Error creating account: " . mysqli_error($conn);
            }
            mysqli_stmt_close($ins);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<?php include("includes/header.inc"); ?>
<body>
<?php include("includes/nav_index.inc"); ?>

<main class="container">
    <h1>Manager Registration</h1>

    <?php if ($success) : ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)) : ?>
        <ul style="color:red;">
            <?php foreach ($errors as $e) echo "<li>$e</li>"; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <label>Username: <input type="text" name="username" required></label><br><br>
        <label>Password: <input type="password" name="password" required></label><br><br>
        <label>Confirm Password: <input type="password" name="password2" required></label><br><br>
        <button type="submit">Register</button>
    </form>

    <p><a href="manager_login.php">Back to Login</a></p>
</main>

<?php include("includes/footer.inc"); ?>
</body>
</html>
