<?php
require_once("settings.php");

// Connect to database
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failure.</p>");
}

// Fetch jobs
$query = "SELECT * FROM jobs";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<?php include("includes/header.inc"); ?>

<body>
<?php include("includes/nav_jobs.inc"); ?>

<main class="container">
    <h1>Current Job Openings</h1>

    <div class="job_container">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <section class="job-card">

                <!-- Job Title -->
                <h3>
                    <i class="fa-solid fa-briefcase"></i>
                    <?php echo htmlspecialchars($row['job_ref']) . " – " . htmlspecialchars($row['job_title']); ?>
                </h3>

                <!-- Job Image -->
                <?php if (!empty($row['image'])) : ?>
                    <img src="../images/<?php echo htmlspecialchars($row['image']); ?>"
                         width="250"
                         height="350"
                         alt="<?php echo htmlspecialchars($row['job_title']); ?>">
                <?php endif; ?>

                <!-- Salary -->
                <p><strong>Salary Range:</strong> <?php echo htmlspecialchars($row['salary_range']); ?></p>

                <!-- Responsibilities -->
                <h4><i class="fa-solid fa-list-check"></i> Key Responsibilities</h4>
                <ul>
                    <?php
                    $resp_items = explode(";", $row['responsibilities']);
                    foreach ($resp_items as $item) {
                        echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                    }
                    ?>
                </ul>

                <!-- Essential Skills -->
                <h4><i class="fa-solid fa-star"></i> Essential Skills</h4>
                <ol>
                    <?php
                    $ess_items = explode(";", $row['essential_skills']);
                    foreach ($ess_items as $item) {
                        echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                    }
                    ?>
                </ol>

                <!-- Preferable Skills -->
                <?php if (!empty($row['preferable_skills'])) : ?>
                    <h4><i class="fa-solid fa-wand-magic-sparkles"></i> Preferable Skills</h4>
                    <ul>
                        <?php
                        $pref_items = explode(";", $row['preferable_skills']);
                        foreach ($pref_items as $item) {
                            echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                        }
                        ?>
                    </ul>
                <?php endif; ?>

                <!-- Apply Button -->
                <a class="primary" href="apply.php">Apply Now</a>
            </section>
        <?php endwhile; ?>
    </div>
</main>

<?php include("includes/footer.inc"); ?>
</body>
</html>

<?php mysqli_close($conn); ?>
