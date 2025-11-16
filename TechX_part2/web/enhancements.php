<?php
require_once("settings.php");
?>
<!DOCTYPE html>
<html lang="en">
<?php include("includes/header.inc"); ?>
<body>
<?php include("includes/nav.inc"); ?>

<main class="container">
    <h1>Enhancements</h1>
    <p>This page documents all additional enhancements implemented beyond the required specifications of COS10026 Project Part 2. Each enhancement includes an explanation and relevant code examples.</p>

    <section>
        <h2>1. Dynamic Job Listings from Database</h2>
        <p>
            Instead of hardcoding job descriptions in <code>jobs.php</code>, all job data is now loaded dynamically 
            from a MySQL database table named <strong>jobs</strong>. This means new jobs can be added without editing HTML.
        </p>

        <h3>Example Code:</h3>
<pre><code>
// Fetch all jobs dynamically
$query = "SELECT * FROM jobs";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo "&lt;h3&gt;" . $row['job_ref'] . " - " . $row['job_title'] . "&lt;/h3&gt;";
}
</code></pre>
    </section>

    <section>
        <h2>2. Dynamic Job Dropdown in Application Form</h2>
        <p>
            The Job Reference field in <code>apply.php</code> now automatically loads job titles from the database instead of being hardcoded. 
            This ensures the application form always matches the jobs page.
        </p>

<h3>Example Code:</h3>
<pre><code>
$job_sql = "SELECT job_ref, job_title FROM jobs";
$job_result = mysqli_query($conn, $job_sql);

while ($job = mysqli_fetch_assoc($job_result)) {
    echo "&lt;option value='{$job['job_ref']}'&gt;{$job['job_ref']} – {$job['job_title']}&lt;/option&gt;";
}
</code></pre>
    </section>

    <section>
        <h2>3. Enhanced Security: Manager Login Lockout System</h2>
        <p>
            After 3 failed login attempts inside <code>manager_login.php</code>, the manager account becomes 
            locked for 15 minutes. This prevents brute-force password attacks.
        </p>

<h3>Key Code Snippet:</h3>
<pre><code>
// Lock user for 15 minutes after 3 failed attempts
if ($failed_attempts >= 3) {
    $lockTime = new DateTime("now");
    $lockTime->modify("+15 minutes");
    $locked_until_val = $lockTime->format('Y-m-d H:i:s');
}
</code></pre>
    </section>

    <section>
        <h2>4. Status Update Feature in manage.php</h2>
        <p>
            HR managers can update an applicant’s status (New → Current → Final) directly from 
            <code>manage.php</code> without opening another page.
        </p>

<h3>Example Code:</h3>
<pre><code>
&lt;form method="post"&gt;
    &lt;input type="hidden" name="eoi_id" value="&lt;?php echo $row['EOInumber']; ?&gt;"&gt;
    &lt;select name="new_status"&gt;
        &lt;option&gt;New&lt;/option&gt;
        &lt;option&gt;Current&lt;/option&gt;
        &lt;option&gt;Final&lt;/option&gt;
    &lt;/select&gt;
    &lt;button type="submit" name="change_status" value="1"&gt;Update&lt;/button&gt;
&lt;/form&gt;
</code></pre>
    </section>

    <section>
        <h2>5. Server-Side Australian Postcode Validation</h2>
        <p>
            Postcodes are validated to match the correct Australian state ranges. This goes beyond simple 
            pattern checking and ensures accurate input.
        </p>

<h3>Example Function:</h3>
<pre><code>
function validate_postcode($state, $postcode) {
    $first_digit = $postcode[0];

    switch ($state) {
        case "VIC": return ($first_digit == '3' || $first_digit == '8');
        case "NSW": return ($first_digit == '1' || $first_digit == '2');
        case "QLD": return ($first_digit == '4' || $first_digit == '9');
        case "SA":  return ($first_digit == '5');
        case "WA":  return ($first_digit == '6');
        case "TAS": return ($first_digit == '7');
        case "ACT": return ($first_digit == '0');
    }
    return false;
}
</code></pre>
    </section>

    <section>
        <h2>6. Automatic EOI Table Creation</h2>
        <p>
            <code>process_eoi.php</code> automatically creates the EOI table if it does not already exist. 
            This makes the site more portable between servers.
        </p>

<h3>Example Code:</h3>
<pre><code>
$table_sql = "
CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    job_ref_number VARCHAR(10),
    first_name VARCHAR(20),
    ...
);";
mysqli_query($conn, $table_sql);
</code></pre>
    </section>

    <section>
        <h2>7. Prepared Statements for Security</h2>
        <p>
            Manager login and updates use prepared statements to prevent SQL Injection.
        </p>

<h3>Example Code:</h3>
<pre><code>
$stmt = mysqli_prepare($conn, "SELECT id, password_hash FROM manager WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
</code></pre>
    </section>

    <section>
        <h2>8. Sorting and Filtering of EOIs in Panel</h2>
        <p>
            HR managers can sort applications by EOI number, job reference, status, and more using 
            dropdown controls, improving usability.
        </p>

<h3>Example Code:</h3>
<pre><code>
$sql = "SELECT * FROM eoi $where_sql ORDER BY $sort_by $order";
</code></pre>
    </section>

</main>

<?php include("includes/footer.inc"); ?>
</body>
</html>
