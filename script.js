// Image slider
let slides = document.querySelectorAll('.slide');
let currentSlide = 0;

function showSlide(index) {
  slides.forEach((slide, i) => slide.classList.remove('active'));
  slides[index].classList.add('active');
}

function nextSlide() {
  currentSlide = (currentSlide + 1) % slides.length;
  showSlide(currentSlide);
}

setInterval(nextSlide, 10000); // change image every 3 seconds

// Contact form submit
const contactForm = document.getElementById('contactForm');
const contactMsg = document.getElementById('contactMsg');

contactForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = {
    name: contactForm.name.value,
    email: contactForm.email.value,
    message: contactForm.message.value
  };

    try {
    const res = await fetch('contact.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(formData)
    });

    if (res.ok) {
      contactMsg.textContent = 'Message sent successfully!';
      contactForm.reset();
    } else {
      contactMsg.textContent = 'Failed to send message.';
    }
  } catch (err) {
    contactMsg.textContent = 'Error connecting to the server.';
  }
});

// Fetch blog posts
async function loadBlogs() {
  try {
    const res = await fetch('http://localhost:5000/api/blog');
    const blogs = await res.json();
    const blogContainer = document.getElementById('blogPosts');
    blogContainer.innerHTML = blogs.map(blog => `
      <div class="blog-post">
        <h3>${blog.title}</h3>
        <p>${blog.content}</p>
      </div>
    `).join('');
  } catch (err) {
    console.error(err);
  }
}

loadBlogs();
//
const counters = document.querySelectorAll('.counter');
counters.forEach(counter => {
  const updateCount = () => {
    const target = +counter.getAttribute('data-target');
    const count = +counter.innerText;
    const increment = target / 100;
    if (count < target) {
      counter.innerText = Math.ceil(count + increment);
      setTimeout(updateCount, 40);
    } else {
      counter.innerText = target;
    }
  };
  updateCount();
});
// // Preloader
// window.addEventListener('load', () => {
//   const preloader = document.getElementById('preloader');
//   preloader.style.opacity = '0';
//   preloader.style.transition = 'opacity 0.5s ease';

//   setTimeout(() => {
//     preloader.style.display = 'none';
//   }, 500); // match transition duration
// });
