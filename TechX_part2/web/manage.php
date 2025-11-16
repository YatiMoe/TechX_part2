<?php
session_start();
require_once("settings.php");

// Require login
if (!isset($_SESSION['manager_user']) || !isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit();
}

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failure</p>");
}

// Escape output
function h($x) {
    return htmlspecialchars($x);
}

// Handle actions: update status / delete by job_ref
$action_msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Update status
    if (isset($_POST["change_status"]) && isset($_POST["eoi_id"]) && isset($_POST["new_status"])) {

        $eoi_id = intval($_POST["eoi_id"]);
        $new_status = $_POST["new_status"];
        $allowed = ["New", "Current", "Final"];

        if (in_array($new_status, $allowed)) {
            $stmt = mysqli_prepare($conn,
                "UPDATE eoi SET status = ? WHERE EOInumber = ?"
            );
            mysqli_stmt_bind_param($stmt, "si", $new_status, $eoi_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $action_msg = "Status updated for EOI #$eoi_id.";
        }
    }

    // Delete all EOIs for a job reference
    if (isset($_POST["delete_all"]) && isset($_POST["jobref_del"])) {

        $jobref = trim($_POST["jobref_del"]);

        $stmt = mysqli_prepare($conn,
            "DELETE FROM eoi WHERE job_ref_number = ?"
        );
        mysqli_stmt_bind_param($stmt, "s", $jobref);
        mysqli_stmt_execute($stmt);

        $count = mysqli_stmt_affected_rows($stmt);
        $action_msg = "$count EOI(s) deleted for Job Reference: $jobref";

        mysqli_stmt_close($stmt);
    }
}

// Filtering + sorting
$filter_jobref = trim($_GET["jobref"] ?? "");
$search_fname = trim($_GET["fname"] ?? "");
$search_lname = trim($_GET["lname"] ?? "");

$sort_by = $_GET["sort_by"] ?? "EOInumber";
$order = strtoupper($_GET["order"] ?? "ASC");

$allowed_sort = ["EOInumber","job_ref_number","first_name","last_name","status","postcode"];
if (!in_array($sort_by, $allowed_sort)) $sort_by = "EOInumber";
if ($order !== "ASC" && $order !== "DESC") $order = "ASC";

$where = [];
$params = [];
$types = "";

// Build dynamic filters
if ($filter_jobref !== "") {
    $where[] = "job_ref_number = ?";
    $params[] = $filter_jobref;
    $types .= "s";
}
if ($search_fname !== "") {
    $where[] = "first_name LIKE ?";
    $params[] = "%$search_fname%";
    $types .= "s";
}
if ($search_lname !== "") {
    $where[] = "last_name LIKE ?";
    $params[] = "%$search_lname%";
    $types .= "s";
}

$where_sql = "";
if (count($where) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

$sql = "SELECT * FROM eoi $where_sql ORDER BY $sort_by $order";

$stmt = mysqli_prepare($conn, $sql);
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="en">
<?php include("includes/header.inc"); ?>
<body>
<?php include("includes/nav_index.inc"); ?>

<main class="container">
    <h1>Manage EOIs</h1>

    <p>Logged in as: <strong><?php echo h($_SESSION['manager_user']); ?></strong></p>
    <p><a href="logout.php">Logout</a></p>

    <?php if ($action_msg): ?>
        <p style="color:green;"><?php echo h($action_msg); ?></p>
    <?php endif; ?>

    <!-- Search / Sort -->
    <section>
        <h2>Search & Filter EOIs</h2>

        <form method="get">
            <label>Job Reference:
                <input type="text" name="jobref" value="<?php echo h($filter_jobref); ?>">
            </label>

            <label>First Name:
                <input type="text" name="fname" value="<?php echo h($search_fname); ?>">
            </label>

            <label>Last Name:
                <input type="text" name="lname" value="<?php echo h($search_lname); ?>">
            </label>

            <label>Sort by:
                <select name="sort_by">
                    <option value="EOInumber">EOI Number</option>
                    <option value="job_ref_number" <?php if ($sort_by == "job_ref_number") echo "selected"; ?>>Job Ref</option>
                    <option value="first_name" <?php if ($sort_by == "first_name") echo "selected"; ?>>First Name</option>
                    <option value="last_name" <?php if ($sort_by == "last_name") echo "selected"; ?>>Last Name</option>
                    <option value="status" <?php if ($sort_by == "status") echo "selected"; ?>>Status</option>
                </select>
            </label>

            <label>Order:
                <select name="order">
                    <option value="ASC" <?php if ($order == "ASC") echo "selected"; ?>>ASC</option>
                    <option value="DESC" <?php if ($order == "DESC") echo "selected"; ?>>DESC</option>
                </select>
            </label>

            <button type="submit">Search</button>
            <a href="manage.php">Reset</a>
        </form>
    </section>

    <!-- Delete -->
    <section>
        <h2>Delete All EOIs for a Job Reference</h2>
        <form method="post" onsubmit="return confirm('Delete ALL EOIs for this job reference?');">
            <input type="hidden" name="delete_all" value="1">
            <label>Job Reference:
                <input type="text" name="jobref_del" required>
            </label>
            <button type="submit">Delete</button>
        </form>
    </section>

    <!-- Results -->
    <section>
        <h2>EOI Records</h2>

        <table border="1" cellpadding="6">
            <tr>
                <th>EOI #</th>
                <th>Job Ref</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Skills</th>
                <th>Status</th>
                <th>Update</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo h($row["EOInumber"]); ?></td>
                    <td><?php echo h($row["job_ref_number"]); ?></td>
                    <td><?php echo h($row["first_name"]) . " " . h($row["last_name"]); ?></td>

                    <td>
                        <?php echo h($row["email"]); ?><br>
                        <?php echo h($row["phone"]); ?>
                    </td>

                    <td>
                        <?php
                            echo h($row["street_address"]) . ", " .
                                 h($row["suburb"]) . ", " .
                                 h($row["state"]) . " " .
                                 h($row["postcode"]);
                        ?>
                    </td>

                    <td>
                        <?php
                            $skills = [];
                            if ($row["skill1"]) $skills[] = "Networking";
                            if ($row["skill2"]) $skills[] = "Design";
                            if ($row["skill3"]) $skills[] = "Programming";
                            if ($row["skill4"]) $skills[] = "Database";
                            if ($row["skill5"]) $skills[] = "Other";
                            echo implode(", ", $skills);

                            if ($row["other_skills"]) {
                                echo "<br><em>" . h($row["other_skills"]) . "</em>";
                            }
                        ?>
                    </td>

                    <td><?php echo h($row["status"]); ?></td>

                    <td>
                        <form method="post">
                            <input type="hidden" name="eoi_id" value="<?php echo $row['EOInumber']; ?>">
                            <select name="new_status">
                                <option value="New" <?php if ($row["status"] == "New") echo "selected"; ?>>New</option>
                                <option value="Current" <?php if ($row["status"] == "Current") echo "selected"; ?>>Current</option>
                                <option value="Final" <?php if ($row["status"] == "Final") echo "selected"; ?>>Final</option>
                            </select>
                            <button type="submit" name="change_status">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>
    </section>

</main>

<?php include("includes/footer.inc"); ?>
</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
