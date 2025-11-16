<!DOCTYPE html>
<html lang="en">
<?php include("includes/header.inc"); ?>

<body>

<?php include("includes/nav_apply.inc"); ?>

<?php
require_once("settings.php");
?>

<main class="container">
  <h1>Job Application Form</h1>
  <form method="post" action="process_eoi.php" novalidate>
    
    <div class="apply_parent">
        <div class="apply_one">
            <!-- Job Selection -->
            <fieldset>
                <legend>Job Information</legend>
                <label  for="jobref">Job Reference:</label>
                <select class="class_design" id="jobref" name="jobref" required>
                    <option value="" disabled selected>Select a job</option>
                    <option value="IT001">IT001 – IT Support Technician</option>
                    <option value="UX002">UX002 – UI/UX Designer</option>
                </select>
            </fieldset>

            <!-- Personal Info -->
            <fieldset>
                <legend>Personal Information</legend>
                <label for="fname">First Name:</label>
                <input class="text_design" type="text" id="fname" name="fname" maxlength="20" required placeholder="John">

                <label for="lname">Last Name:</label>
                <input class="text_design"  type="text" id="lname" name="lname" maxlength="20" required placeholder="Doe">

                <label for="dob">Date of Birth:</label>
                <input class="text_design"  type="date" id="dob" name="dob" required>

                <label>Gender:</label><br>
               <div class="gender_parent">
                   <label><input type="radio" name="gender" value="male" required> Male</label>
                   <label><input type="radio" name="gender" value="female"> Female</label>
                   <label><input type="radio" name="gender" value="other"> Other</label>
               </div>
            </fieldset>
        </div>

        <div class="apply_two">
            <!-- Contact Info -->
            <fieldset>
                <legend>Contact Information</legend>
                <label for="address">Street Address:</label>
                <input class="text_design" type="text" id="address" name="address" maxlength="40" required placeholder="123 Main St">

                <label for="suburb">Suburb/Town:</label>
                <input class="text_design" type="text" id="suburb" name="suburb" maxlength="40" required>

                <label for="state">State:</label>
<select class="class_design" id="state" name="state" required>
    <option value="" disabled selected>Select State</option>
    <option value="VIC">VIC – Victoria</option>
    <option value="NSW">NSW – New South Wales</option>
    <option value="QLD">QLD – Queensland</option>
    <option value="NT">NT – Northern Territory</option>
    <option value="WA">WA – Western Australia</option>
    <option value="SA">SA – South Australia</option>
    <option value="TAS">TAS – Tasmania</option>
    <option value="ACT">ACT – Australian Capital Territory</option>
</select>

                <label for="postcode">Postcode:</label>
                <input class="text_design" type="text" id="postcode" name="postcode" pattern="\d{4}" required placeholder="e.g. 3000">

                <label for="email">Email:</label>
                <input class="text_design" type="email" id="email" name="email" required placeholder="example@email.com">

                <label for="phone">Phone:</label>
                <input class="text_design" type="tel" id="phone" name="phone" pattern="\d{8,12}" required placeholder="e.g. 0102345678">
            </fieldset>
        </div>

    </div>
      <div class="apply_three">
          <!-- Skills -->
          <fieldset>
              <legend>Skills</legend><br>
<label><input type="checkbox" name="skills[]" value="networking"> Networking</label>
<label><input type="checkbox" name="skills[]" value="design"> Design</label>
<label><input type="checkbox" name="skills[]" value="programming"> Programming</label>
<label><input type="checkbox" name="skills[]" value="database"> Database Management</label>

          </fieldset>



          <div class="other_skill">
              <label for="otherskills">Other Skills:</label>
              <textarea id="otherskills" name="otherskills" rows="4" placeholder="List any other relevant skills..."></textarea>
          </div>
          <button class="primary" type="submit"> Submit Application</button>
      </div>
  </form>
</main>


		<!-- Footer -->
<?php include("includes/footer.inc"); ?>

</body>
</html>
