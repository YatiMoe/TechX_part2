<?php
// Prevent direct access without POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit();
}

// Include database settings
require_once("settings.php");

// ============================
// SANITIZATION FUNCTION
// ============================
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

// ============================
// AUSTRALIAN POSTCODE VALIDATION
// ============================
function validate_postcode($state, $postcode) {
    $first_digit = substr($postcode, 0, 1);

    switch ($state) {
        case "VIC": return ($first_digit == "3" || $first_digit == "8");
        case "NSW": return ($first_digit == "1" || $first_digit == "2");
        case "QLD": return ($first_digit == "4" || $first_digit == "9");
        case "SA":  return ($first_digit == "5");
        case "WA":  return ($first_digit == "6");
        case "TAS": return ($first_digit == "7");
        case "ACT": return ($first_digit == "0");
        default: return false;
    }
}

// ============================
// RETRIEVE + SANITIZE INPUTS
// ============================
$jobref     = clean_input($_POST["jobref"] ?? "");
$fname      = clean_input($_POST["fname"] ?? "");
$lname      = clean_input($_POST["lname"] ?? "");
$dob        = clean_input($_POST["dob"] ?? "");
$gender     = clean_input($_POST["gender"] ?? "");
$address    = clean_input($_POST["address"] ?? "");
$suburb     = clean_input($_POST["suburb"] ?? "");
$state      = clean_input($_POST["state"] ?? "");
$postcode   = clean_input($_POST["postcode"] ?? "");
$email      = clean_input($_POST["email"] ?? "");
$phone      = clean_input($_POST["phone"] ?? "");
$skills = isset($_POST["skills"]) ? (array)$_POST["skills"] : [];
$otherskills = clean_input($_POST["otherskills"] ?? "");

// ============================
// SERVER-SIDE VALIDATION
// ============================

// Required fields
if (!$jobref || !$fname || !$lname || !$address || !$suburb || !$state || !$postcode || !$email || !$phone) {
    die("<p>Error: All required fields must be filled in.</p>");
}

// First & Last name validation
if (!preg_match("/^[A-Za-z]{1,20}$/", $fname)) {
    die("<p>Error: First name must be alphabetic and up to 20 characters.</p>");
}
if (!preg_match("/^[A-Za-z]{1,20}$/", $lname)) {
    die("<p>Error: Last name must be alphabetic and up to 20 characters.</p>");
}

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("<p>Error: Invalid email format.</p>");
}

// Phone validation (8-12 digits)
if (!preg_match("/^[0-9 ]{8,12}$/", $phone)) {
    die("<p>Error: Phone must be 8–12 digits.</p>");
}

// Postcode: only digits AND match state rules
if (!preg_match("/^[0-9]{4}$/", $postcode)) {
    die("<p>Error: Postcode must be exactly 4 digits.</p>");
}
if (!validate_postcode($state, $postcode)) {
    die("<p>Error: Postcode does not match selected Australian state.</p>");
}

// Skills → convert to tinyint
$skill1 = in_array("networking", $skills) ? 1 : 0;
$skill2 = in_array("design", $skills) ? 1 : 0;
$skill3 = in_array("programming", $skills) ? 1 : 0;
$skill4 = in_array("database", $skills) ? 1 : 0;
$skill5 = 0; // unused

// ============================
// CONNECT TO DATABASE
// ============================
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("<p>Database connection failure</p>");
}

// ============================
// CREATE TABLE IF NOT EXISTS
// ============================

$table_sql = "
CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    job_ref_number VARCHAR(10) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    dob DATE NULL,
    gender ENUM('Male','Female','Other') NULL,
    street_address VARCHAR(40) NOT NULL,
    suburb VARCHAR(40) NOT NULL,
    state ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
    postcode CHAR(4) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(12) NOT NULL,
    skill1 TINYINT(1),
    skill2 TINYINT(1),
    skill3 TINYINT(1),
    skill4 TINYINT(1),
    skill5 TINYINT(1),
    other_skills TEXT,
    status ENUM('New','Current','Final') DEFAULT 'New'
);";

mysqli_query($conn, $table_sql);

// ============================
// INSERT INTO TABLE
// ============================

$insert_sql = "
INSERT INTO eoi 
(job_ref_number, first_name, last_name, dob, gender, street_address, suburb, state, postcode, email, phone, skill1, skill2, skill3, skill4, skill5, other_skills)
VALUES 
('$jobref', '$fname', '$lname', '$dob', '$gender', '$address', '$suburb', '$state', '$postcode', '$email', '$phone', 
$skill1, $skill2, $skill3, $skill4, $skill5, '$otherskills');
";

if (mysqli_query($conn, $insert_sql)) {
    $eoi_id = mysqli_insert_id($conn);

    echo "<h2>Application Submitted Successfully</h2>";
    echo "<p>Thank you, <strong>$fname</strong>. Your application has been received.</p>";
    echo "<p>Your unique application number is: <strong>$eoi_id</strong></p>";
    echo "<a href='index.php'>Return to Home</a>";
} else {
    echo "<p>Error inserting record: " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
?>
