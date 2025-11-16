<!DOCTYPE html>
<html lang="en">
<?php include("includes/header.inc"); ?>

<body>

<?php include("includes/nav_index.inc"); ?>

<?php
require_once("settings.php");
?>
<section>



      <!-- Get Ready for Your Next Outdoor Expedition -->
    <div class="header-content">
        <div class="leftBox">

			<h1>Your one-stop solution for innovative tech careers.</h1>
           <p>A leading technology solutions provider committed to driving innovation in IT services and digital design. At TechX, we combine technical expertise with creativity to deliver outstanding results for our clients across multiple industries.</p>
            <a class="primary" href="jobs.php">Find out more</a>
        </div>
        <div class="rightBox">
             <img src="../images/cover.png" alt="cover_page">
        </div>
    </div>

<div class="product_preview_parent">
    <h2>Services Avaliable</h2>
    <div class="product_item_parent res-row">
        <div class="product_preview col-lg-3 col-sm-6">
            <div class="product_img">
            <img src="../images/IT Support & Maintenance.jpg" width="250" height="250" alt="It_support"/> </div>
            <h3>IT Support & Maintenance</h3>
        </div>

         <div class="product_preview col-lg-3 col-sm-6">
            <div class="product_img">
            <img src="../images/UIUX Design & Web Development.jpg" width="250" height="250" alt="UI_UX"/> </div>
            <h3>UI/UX Design & Web Development</h3>
        </div>
         <div class="product_preview col-lg-3 col-sm-6">
            <div class="product_img">
            <img src="../images/Cloud Solutions & Cybersecurity.jpg" width="250" height="250" alt="Cloud_Data"/> </div>
            <h3>Cloud Solutions & Cybersecurity</h3>
        </div>
         <div class="product_preview col-lg-3 col-sm-6">
            <div class="product_img">
            <img src="../images/IT Consultancy & Training.jpg" width="250" height="250" alt="IT_consultancy"/> </div>
            <h3>IT Consultancy & Training</h3>
        </div>
    </div>
</div>

<!-- Section2 -->
<div class="container sec2">
    <div class="section2">
        <h3>Who We Are</h3>
        <h2>More than a tech company, we are a team of innovators.</h2>
        <div class="parent res-row">
            <div class="col-lg-6 feature-1-img">
                <img  class="picture" src="../images/Tech company.jpg" alt="Product">
            </div>
            <div class="content col-lg-6">
                <ul>
                    <li><h3>Our Vision</h3>
                        <p>We envision a world where every innovative idea has the right team to bring it to life, and every skilled professional has the oppotunity to make a lasting impact. We saw a need for a more human-centered approach to tech recruitment, and that's exactly what we deliver.</p></li>
                </ul>
                <ul>
                    <li><h3>Our Value</h3>
                        <p>We are committed to providing high-quality camping gear and resources that will make your outdoor experience safe, enjoyable, and memorable.</p></li>
                </ul>

                <ul>
                    <li><h3>Our Core Values </h3>
                        <p> <ul>
			     <li><strong>Innovation:</strong> We embrace new ideas and technologies.</li>
                 <li><strong>Integrity:</strong> We operate with honesty and transparency.</li>
				 <li><strong>Community:</strong> We build lasting relationships and support our network.</li>
			  </ul></p></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Component -->
<div class="container com">
    <div class="component res-row">
        <div class="component-content col-lg-6">
            <h4 class="white">Ready to Join with Us</h4>
            <p>Be part of TechX — where your skills create impact, your ideas spark innovation, and your career shapes tomorrow.</p>
            <div>
                <a class="button-component-content" href="apply.php">Apply Now</a>
            </div>

        </div>
        <div class="img_component col-lg-6">
            <img src="../images/apply now.png" width="250px" height="250px" alt="Apply Now">
        </div>
    </div>
</div>

<!-- Section3 -->



<!-- Section4 -->

<div class="container sec4">
    <div class="section4">
        <div class="section4-content">
            <h2>Why US?</h2>
            <p>Discover the Innovation That Drives Your Success</p>
        </div>
        <div class="section4-parent res-row">
            <div class="item col-lg-4">

                <p>TechX is more than just an IT service provider — we’re your trusted partner in growth. We believe technology should empower, not overwhelm, and our human-centered approach ensures solutions that truly fit your needs.</p>
            </div>
            <div class="item col-lg-4">

                <p>With our expertise in IT support, UI/UX design, cloud solutions, and digital consultancy, we help businesses and professionals stay ahead in a fast-changing world. At TechX, we combine innovation, integrity, and community to create meaningful impact and long-term success.</p>
            </div>
        </div>
    </div>
</div>

<!-- slideshow -->


<!-- ContactUs -->
<div class="container contact_us res-row">
    <div class="contact_us_content col-lg-6">
        <h2>Contact Us</h2>
        <h1>Say Hey!✌️</h1>
        <p>Got questions about our IT services or need support? The TechX team is here to help you with the right solutions, anytime you need us.</p>
    </div>
    <div class="form col-lg-6">
        <form action="#">
            <div class="form_input">
                <label for="name">Name:</label>
                <input id="name" class="text_design" type="text" placeholder="Enter Your Name" required>
            </div>

            <div class="form_input">
                <label for="email">Email:</label>
                <input id="email" class="text_design" type="email" placeholder="Email" required>
            </div>

            <div class="input">
                <label for="country">Country:</label>
                <select id="country" name="country" required>
                    <option selected disabled>Choose Your Country</option>
                    <option value="Myanmar">Myanmar 🇲🇲</option>
                    <option value="Thailand">Thailand 🇹🇭</option>
                    <option value="Singapore">Singapore 🇸🇬</option>
                    <option value="China">China 🇨🇳</option>
                    <option value="India">India 🇮🇳</option>
                    <option value="Malaysia">Malaysia 🇲🇾</option>
                    <option value="Australia">Australia 🇦🇺</option>
                </select>
            </div>

            <div class="input message-box">
                <label for="description">Description:</label>
                <textarea id="description" placeholder="Description" required></textarea>
            </div>

            <button class="primary">Send Here</button>
        </form>

    </div>
</div>

</section>

		<!-- Footer -->
<?php include("includes/footer.inc"); ?>


</body>
</html>
