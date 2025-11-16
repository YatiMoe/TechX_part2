<?php
session_start();
require_once("settings.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? "");
    $password = $_POST['password'] ?? "";

    if ($username === "" || $password === "") {
        $errors[] = "Username and password are required.";
    } else {

        $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
        if (!$conn) die("Database connection failed");

        // FIXED: Using manager_id instead of id
        $stmt = mysqli_prepare($conn,
            "SELECT manager_id, password_hash, failed_attempts, locked_until 
             FROM manager WHERE username = ?"
        );
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $manager_id, $password_hash, $failed_attempts, $locked_until);

        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);

            // Check lockout
            $now = new DateTime("now");

            if ($locked_until && new DateTime($locked_until) > $now) {
                $errors[] = "Account is locked. Try again later.";
            } else {

                if (password_verify($password, $password_hash)) {

                    // Reset failed attempts
                    $reset = mysqli_prepare($conn,
                        "UPDATE manager SET failed_attempts = 0, locked_until = NULL WHERE manager_id = ?"
                    );
                    mysqli_stmt_bind_param($reset, "i", $manager_id);
                    mysqli_stmt_execute($reset);
                    mysqli_stmt_close($reset);

                    // Login success
                    $_SESSION['manager_user'] = $username;
                    $_SESSION['manager_id'] = $manager_id;
                    header("Location: manage.php");
                    exit();

                } else {
                    // Wrong password → increase attempts
                    $failed_attempts++;

                    if ($failed_attempts >= 3) {
                        $lockTime = new DateTime("now");
                        $lockTime->modify("+15 minutes");
                        $locked_until_val = $lockTime->format("Y-m-d H:i:s");
                    } else {
                        $locked_until_val = NULL;
                    }

                    $u = mysqli_prepare($conn,
                        "UPDATE manager SET failed_attempts=?, locked_until=?, last_failed_login=NOW()
                         WHERE manager_id=?"
                    );
                    mysqli_stmt_bind_param($u, "isi", $failed_attempts, $locked_until_val, $manager_id);
                    mysqli_stmt_execute($u);
                    mysqli_stmt_close($u);

                    $errors[] = "Invalid username or password.";
                }
            }
        } else {
            $errors[] = "Invalid username or password.";
        }

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
    <h1>Manager Login</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $e) echo "<li>$e</li>"; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <label>Username:
            <input type="text" name="username">
        </label><br><br>

        <label>Password:
            <input type="password" name="password">
        </label><br><br>

        <button type="submit">Login</button>
    </form>

    <p><a href="manager_register.php">Register new manager</a></p>
</main>

<?php include("includes/footer.inc"); ?>
</body>
</html>
