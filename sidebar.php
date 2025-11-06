<?php
// sidebar.php - include this in every page AFTER session_start() and login-check
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
  :root {
    --sidebar-wide: 250px;
    --sidebar-narrow: 80px;
    --sidebar-bg: #0F172A;
    --sidebar-hover: #1E293B;
    --page-bg: #f0f4f8;
  }

  /* Reset / layout */
  body { font-family: 'Inter', sans-serif; background: var(--page-bg); margin:0; }
  /* fixed sidebar on the left */
  #sidebar {
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    width: var(--sidebar-wide);
    background: var(--sidebar-bg);
    color: #fff;
    transition: width .25s ease;
    overflow: auto;
    z-index: 1000;
  }
  /* collapsed state */
  #sidebar.collapsed { width: var(--sidebar-narrow); }

  /* page content should be pushed right by sidebar width */
  #page-content {
    margin-left: var(--sidebar-wide);
    padding: 20px;
    transition: margin-left .25s ease;
  }
  #page-content.collapsed { margin-left: var(--sidebar-narrow); }

  /* sidebar inner styles */
  #sidebar .brand { padding: 16px; display:flex; justify-content:space-between; align-items:center; }
  #sidebar .nav-link { color:#fff; padding:12px 18px; display:flex; align-items:center; gap:12px; }
  #sidebar .nav-link:hover { background: var(--sidebar-hover); color:#fff; text-decoration:none; }
  #sidebar .nav-link i { width:22px; text-align:center; }
  #sidebar .nav-link.active { background: var(--sidebar-hover); font-weight:700; }

  /* when collapsed hide link text but keep icons centered */
  #sidebar.collapsed .link-text { display: none; }
  #sidebar.collapsed .nav-link { justify-content: center; padding:12px 6px; }

  /* small screens: sidebar becomes top (not fixed) */
  @media (max-width: 768px) {
    #sidebar { position: relative; width: 100%; height: auto; }
    #sidebar.collapsed { width: 100%; } /* don't narrow on mobile */
    #page-content { margin-left: 0; }
    #page-content.collapsed { margin-left: 0; }
  }

  /* small helper notices */
  .notice-success { background:#ECFDF5; color:#065F46; padding:10px; border-radius:6px; margin-bottom:12px; }
  .notice-err { background:#FEF2F2; color:#991B1B; padding:10px; border-radius:6px; margin-bottom:12px; }
</style>

<nav id="sidebar" aria-label="Main sidebar">
  <div class="brand">
    <div><strong style="color:#A5B4FC">Planify</strong></div>
    <button id="sidebarToggle" class="btn btn-sm btn-light" aria-expanded="true" title="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link" href="user_dashboard.php"><i class="fas fa-home"></i><span class="link-text">Dashboard</span></a></li>
    <li class="nav-item"><a class="nav-link" href="joined_courses.php"><i class="fas fa-book-reader"></i><span class="link-text">Joined Courses</span></a></li>
    <li class="nav-item"><a class="nav-link" href="course_history.php"><i class="fas fa-history"></i><span class="link-text">Course History</span></a></li>
    <li class="nav-item"><a class="nav-link" href="available_courses.php"><i class="fas fa-chalkboard-teacher"></i><span class="link-text">Available Courses</span></a></li>
      <li class="nav-item"><a class="nav-link" href="finance.php"><i class="fas fa-wallet"></i><span class="link-text">Finance</span></a></li>
    <li class="nav-item"><a class="nav-link" href="contacts.php"><i class="fas fa-envelope"></i><span class="link-text">Contact</span></a></li>
    <li class="nav-item"><a class="nav-link" href="settings.php"><i class="fas fa-cog"></i><span class="link-text">Settings</span></a></li>
    <li class="nav-item"><a class="nav-link" href="user_logout.php"><i class="fas fa-sign-out-alt"></i><span class="link-text">Logout</span></a></li>
  </ul>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  (function(){
    const sidebar = document.getElementById('sidebar');
    const page = document.getElementById('page-content');
    const btn = document.getElementById('sidebarToggle');
    const STORAGE_KEY = 'planify_sidebar_collapsed';

    // helper to set aria-expanded and attr
    function setExpandedState(isCollapsed) {
      btn.setAttribute('aria-expanded', (!isCollapsed).toString());
    }

    // restore persisted state
    const collapsed = localStorage.getItem(STORAGE_KEY) === '1';
    if (collapsed) {
      sidebar.classList.add('collapsed');
      if (page) page.classList.add('collapsed');
      setExpandedState(true);
    } else {
      setExpandedState(false);
    }

    // toggle on click
    btn.addEventListener('click', () => {
      const isNowCollapsed = sidebar.classList.toggle('collapsed');
      if (page) page.classList.toggle('collapsed');
      localStorage.setItem(STORAGE_KEY, isNowCollapsed ? '1' : '0');
      setExpandedState(isNowCollapsed);
    });

    // highlight active link by filename
    (function markActive() {
      try {
        const current = window.location.pathname.split('/').pop() || 'user_dashboard.php';
        const links = document.querySelectorAll('#sidebar .nav-link');
        links.forEach(a => {
          const href = a.getAttribute('href');
          if (!href) return;
          if (href === current) a.classList.add('active');
        });
      } catch (e) { /* ignore */ }
    })();

    // ensure page content margin matches sidebar width on resize (for some edge cases)
    window.addEventListener('resize', () => {
      // nothing required because CSS uses variables, but keep for safety
      // (left intentionally minimal)
    });
  })();
</script>
