<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Planify - Aviation</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Preloader -->
<div id="preloader">
  <div class="loader"></div>
</div>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="logo">Planify</div>
    <ul class="nav-links">
      <li><a href="#home">Home</a></li>
      <li><a href="#about">About Us</a></li>
      <li><a href="#courses">Courses</a></li>
      <li><a href="#contact">Contact</a></li>
      <li><a href="#blog">Blog</a></li>
      <li><a href="user_logout.php">Logout</a></li>
    </ul>
  </nav>

  <!-- Hero Section -->
  <section id="home" class="hero">
    <div class="slider">
      <img src="images/plane1.jpg" class="slide active" alt="Plane 1">
       <img src="images/plane2.jpg" class="slide" alt="Plane 2">
      <img src="images/plane3.jpg" class="slide" alt="Plane 3">
    </div>
    <div class="hero-text">
     <div class="hero-text">
  <h1>Soar High with Planify Aviation</h1>
  <p>India’s Leading Aviation Training Institute – Shaping the Future of Flight</p>
  <a href="#about" class="cta-btn">Explore More</a>
</div>

    </div>
  </section>
<!-- Intro Section -->
<section id="intro" class="intro">
  <div class="intro-container">
    <div class="intro-text">
      <h2>Welcome to Planify</h2>
      <p>
        Planify Aviation Institute is committed to training the next generation of aviation professionals. 
        With state-of-the-art simulators, experienced instructors, and modern facilities, we help our students achieve excellence in aviation.
      </p>
    </div>
    <div class="intro-image">
      <img src="images/intro-plane.jpg" alt="Aviation Training">
    </div>
  </div>
</section>

  <!-- About Us -->
  <section id="about" class="about">
  <h2>About Us</h2>
  <p>
    Planify Aviation Institute is a premier aviation training center dedicated to shaping the next generation of pilots, engineers, and aviation professionals. 
    Our institute offers world-class training programs, including pilot certification, aircraft maintenance, air traffic management, and aviation safety courses. 
    With state-of-the-art simulators, modern classrooms, and experienced instructors, we provide hands-on training to ensure our students excel in the competitive aviation industry.
    Our mission is to foster innovation, safety, and professionalism in aviation. We aim to create skilled professionals who are ready to meet global aviation standards and contribute to the growth of the aviation sector.
    At Planify, we also focus on career guidance, internships with airlines, and continuous support for our students to help them achieve their dreams of soaring high in their aviation careers.
</p>
</section>
<!--courses-->>
<section id="courses" class="courses">
  <h2>Our Training Programs</h2>
  <div class="courses-container">
    <div class="course-card">
      <img src="images/pilot-training.jpg" alt="Pilot Training">
      <h3>Pilot Training</h3>
      <p>Comprehensive flight and simulator training to prepare future airline pilots.</p>
    </div>
    <div class="course-card">
      <img src="images/aircraft-maintenance.jpg" alt="Aircraft Maintenance">
      <h3>Aircraft Maintenance</h3>
      <p>Hands-on aircraft maintenance and engineering certification programs.</p>
    </div>
    <div class="course-card">
      <img src="images/air-traffic.jpg" alt="Air Traffic Control">
      <h3>Air Traffic Management</h3>
      <p>Train to manage flight operations and ensure airspace safety and efficiency.</p>
    </div>
  </div>
</section>
  <?php
// available_courses.php
include 'db.php';
$courses = $conn->query("SELECT * FROM courses ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Available Courses - Planify</title>

<style>
  /* Page container */
  :root{
    --blue-1: #3f8edc;   /* header (soft) */
    --blue-2: #0a4f9a;   /* heading text */
    --bg: #f6f9ff;       /* page background */
    --table-border: #d6e1ee;
    --row-alt: #f8fbff;
    --text: #222;
    --muted: #6b7280;
  }

  body{
    margin:0;
    font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background:var(--bg);
    color:var(--text);
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
  }

  .section-wrap{
    padding:44px 16px;
  }

  .container{
    max-width:1100px;    /* keep table & heading aligned */
    margin:0 auto;
  }

  h2.section-title{
    text-align:center;
    color:var(--blue-2);
    font-size:2rem;
    font-weight:700;
    margin:0 0 22px 0;
    line-height:1.1;
  }

  /* Table wrapper to allow horizontal scroll on small screens */
  .table-outer{
    width:100%;
    overflow-x:auto;
  }

  table.courses-table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border:1px solid var(--table-border);
    box-shadow:0 6px 18px rgba(10,30,60,0.06);
    border-radius:10px;
    overflow:hidden;
  }

  /* Header row */
  thead th{
    background:var(--blue-1);
    color:#fff;
    font-weight:600;
    padding:14px 20px;
    text-align:left;           /* keep header aligned with body */
    vertical-align:middle;
  }

  /* give rounded corners to header cells so the top row looks rounded */
  thead th:first-child{ border-top-left-radius:10px; }
  thead th:last-child{ border-top-right-radius:10px; }

  tbody td{
    padding:16px 20px;
    vertical-align:middle;
    color:var(--text);
    border-bottom:1px solid #eef4fb;
  }

  /* alternate row background */
  tbody tr:nth-child(even){ background:var(--row-alt); }

  /* column specific alignment */
  td.col-index{ width:6%; text-align:center; color:var(--muted); }
  th.col-index{ width:6%; text-align:center; }

  td.col-name{ width:54%; font-weight:600; color:#111; }
  td.col-duration{ width:20%; text-align:left; color:#333; }
  td.col-fee{ width:20%; text-align:right; color:var(--blue-2); font-weight:600; }

  /* small screens adjustments */
  @media (max-width:720px){
    thead th, tbody td { padding:12px 14px; }
    td.col-name { font-size:0.95rem; }
    td.col-fee { text-align:right; font-size:0.95rem; }
    h2.section-title { font-size:1.5rem; margin-bottom:16px; }
  }
</style>
</head>
<body>
  <section class="section-wrap">
    <div class="container">
      <h2 class="section-title">Available Courses</h2>

      <div class="table-outer">
        <?php if ($courses && $courses->num_rows > 0): ?>
          <table class="courses-table" role="table" aria-label="Available Courses">
            <thead>
              <tr>
                <th class="col-index">#</th>
                <th class="col-name">Name of the Course</th>
                <th class="col-duration">Duration</th>
                <th class="col-fee">Average Fees (INR)</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; while($row = $courses->fetch_assoc()): ?>
              <tr>
                <td class="col-index"><?php echo $i++; ?></td>
                <td class="col-name"><?php echo htmlspecialchars($row['course_name']); ?></td>
                <td class="col-duration"><?php echo htmlspecialchars($row['course_duration']); ?></td>
                <td class="col-fee">₹<?php echo htmlspecialchars(number_format((float)$row['course_fee'], 2)); ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div style="padding:28px 18px; background:#fff; border:1px solid var(--table-border); border-radius:8px; box-shadow:0 4px 12px rgba(10,30,60,0.04);">
            <p style="margin:0; color:var(--muted); text-align:center; font-size:1rem;">No courses available right now.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</body>
</html>

<section id="stats" class="stats">
  <div class="stat-item">
    <h3 class="counter" data-target="500">0</h3>
    <p>Students Trained</p>
  </div>
  <div class="stat-item">
    <h3 class="counter" data-target="15">0</h3>
    <p>Years of Excellence</p>
  </div>
  <div class="stat-item">
    <h3 class="counter" data-target="20">0</h3>
    <p>Certified Instructors</p>
  </div>
</section>
<!--apply-->>
<a href="#contact" class="float-btn">Apply Now</a>

 <!-- Testimonials -->
  <section id="testimonials" class="testimonials">
    <h2>Testimonials</h2>
    <div class="testimonials-container">
      <div class="testimonial">
        <p>"Planify Aviation Institute gave me the skills and confidence to pursue my dream of becoming a pilot. The instructors are extremely knowledgeable and supportive."</p>
        <h4>- Aarav S., Pilot Trainee</h4>
      </div>
      <div class="testimonial">
        <p>"The practical training and simulator sessions at Planify prepared me perfectly for my first job at a commercial airline. I highly recommend this institute!"</p>
        <h4>- Neha R., Aircraft Engineer</h4>
      </div>
      <div class="testimonial">
        <p>"Planify consistently produces skilled professionals who are ready to take on real-world aviation challenges. Their graduates are top-notch."</p>
        <h4>- Captain Vikram L., Airline Operations Manager</h4>
      </div>
    </div>
  </section>


  <!-- Contact -->
  <section id="contact" class="contact">
  <h2>Contact Us</h2>
  <div class="contact-container">
    <form action="contact.php" method="post" id="contactForm">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" placeholder="Your Message" required></textarea>
      <button type="submit">Send Message</button>
    </form>
  <p id="contactMsg" style="margin-top:10px; color:lightgreen;"></p>
  </section>

  <!-- Blog -->
 <!-- Blog Section -->
<section id="blog" class="blog">
  <h2>Our Blog</h2>
  <div class="blog-container">

    <!-- Blog Post 1 -->
    <div class="blog-post">
      <img src="images/blog1.jpg" alt="Aviation Safety">
      <div class="blog-content">
        <h3>Top 10 Aviation Safety Tips</h3>
        <p class="blog-snippet">
          Discover essential safety tips that every pilot and passenger should know...
        </p>
        <button class="read-more-btn">Read More</button>
        <p class="full-text" style="display:none;">
          Aviation safety is paramount in the industry. Pilots and crew undergo rigorous training, and passengers must follow guidelines to ensure safety during flights. Here are the top 10 tips to stay safe...
        </p>
        <span class="blog-date">Oct 10, 2025</span>
      </div>
    </div>

    <!-- Blog Post 2 -->
    <div class="blog-post">
      <img src="images/blog2.jpg" alt="Pilot Training">
      <div class="blog-content">
        <h3>Pilot Training: What to Expect</h3>
        <p class="blog-snippet">
          Learn about the process, simulators, and real-world training pilots undergo...
        </p>
        <button class="read-more-btn">Read More</button>
        <p class="full-text" style="display:none;">
          Pilot training involves ground school, simulator sessions, and actual flight training. Students learn about aerodynamics, navigation, safety procedures, and emergency protocols. Here’s a complete guide for aspiring pilots...
        </p>
        <span class="blog-date">Oct 8, 2025</span>
      </div>
    </div>

    <!-- Blog Post 3 -->
    <div class="blog-post">
      <img src="images/blog3.jpg" alt="Aircraft Technology">
      <div class="blog-content">
        <h3>Latest Aircraft Technology Updates</h3>
        <p class="blog-snippet">
          Explore the newest advancements in aircraft technology and aviation systems...
        </p>
        <button class="read-more-btn">Read More</button>
        <p class="full-text" style="display:none;">
          Aircraft technology is evolving rapidly with innovations in fuel efficiency, avionics, and safety systems. Modern aircraft now feature advanced navigation systems, automated controls, and environmentally friendly engines...
        </p>
        <span class="blog-date">Oct 5, 2025</span>
      </div>
    </div>

  </div>
</section>
<script>
  const buttons = document.querySelectorAll('.read-more-btn');
  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const fullText = btn.nextElementSibling;
      if (fullText.style.display === "none") {
        fullText.style.display = "block";
        btn.textContent = "Show Less";
      } else {
        fullText.style.display = "none";
        btn.textContent = "Read More";
      }
    });
  });
</script>


  <!-- Footer -->
  <footer class="footer">
  <div class="footer-content">
    <div class="contact-info">
      <p><strong>Address:</strong> 123 Aviation Lane, City, Country</p>
      <p><strong>Phone:</strong> +91 98765 43210</p>
    </div>
    <div class="social-icons">
      <a href="https://facebook.com" target="_blank"><img src="images/facebook.jpg" alt="Facebook"></a>
      <a href="https://instagram.com" target="_blank"><img src="images/instagram.png" alt="Instagram"></a>
      <a href="https://twitter.com" target="_blank"><img src="images/twitter.jpg" alt="Twitter"></a>
    </div>
  </div>
  <p class="footer-bottom">&copy; 2025 Planify. All rights reserved.</p>
</footer>

  <script src="script.js"></script>
</body>
</html>
