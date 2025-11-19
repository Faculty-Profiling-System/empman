// Global state for filters
let currentFilters = {
  search: '',
  minSalary: 0,
  maxSalary: Infinity
};

// Theme management
function setTheme(theme) {
  document.documentElement.setAttribute('data-theme', theme);
  localStorage.setItem('theme', theme);
}

function toggleTheme() {
  const currentTheme = localStorage.getItem('theme') || 'light';
  const newTheme = currentTheme === 'light' ? 'dark' : 'light';
  setTheme(newTheme);
}

document.addEventListener("DOMContentLoaded", () => {
  const wrapper = document.getElementById("wrapper");
  const toggleBtn = document.getElementById("menu-toggle");
  const themeToggleBtn = document.getElementById("themeModeToggle");
  
  // Initialize theme
  const savedTheme = localStorage.getItem('theme') || 'light';
  setTheme(savedTheme);
  
  // Theme toggle handler
  themeToggleBtn?.addEventListener('click', toggleTheme);

  // Profile tab button - ensure dropdown closes when clicked
  const profileTabBtn = document.getElementById('profile-tab');
  if (profileTabBtn) {
    profileTabBtn.addEventListener('click', (e) => {
      // Close the dropdown menu after a short delay to allow tab to activate
      setTimeout(() => {
        const dropdownToggle = profileTabBtn.closest('.dropdown').querySelector('[data-bs-toggle="dropdown"]');
        if (dropdownToggle) {
          const dropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
          if (dropdown) {
            dropdown.hide();
          }
        }
      }, 100);
    });
  }

  // Notifications: show a small card when notification icon is pressed
  const notificationsBtn = document.getElementById('notificationsBtn');

  function createNotificationPanel() {
    const panel = document.createElement('div');
    panel.id = 'notificationPanel';
    panel.className = 'notification-panel card shadow-sm';
    panel.style.position = 'absolute';
    panel.style.minWidth = '260px';
    panel.style.width = '320px';
    panel.style.zIndex = '2200';
    panel.style.display = 'none';
    panel.innerHTML = `
      <div class="card-body p-2">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h6 class="mb-0">Notifications</h6>
          <button id="clearNotificationsBtn" class="btn btn-sm btn-link text-muted">Clear</button>
        </div>
        <div id="notificationList"></div>
      </div>
    `;
    document.body.appendChild(panel);
    return panel;
  }

  function updateNotificationPanelContent() {
    const list = document.getElementById('notificationList');
    if (!list) return;
    const arr = JSON.parse(localStorage.getItem('notifications') || '[]');
    list.innerHTML = '';
    if (!arr || arr.length === 0) {
      const el = document.createElement('div');
      el.className = 'text-muted small p-3 text-center';
      el.textContent = 'No notifications';
      list.appendChild(el);
      return;
    }
    arr.forEach(n => {
      const item = document.createElement('div');
      item.className = 'notification-item px-3 py-2 border-bottom';
      item.textContent = n.message || n;
      list.appendChild(item);
    });
  }

  function positionPanel(panel, btn) {
    const rect = btn.getBoundingClientRect();
    const panelWidth = panel.offsetWidth || 320;
    let left = rect.right - panelWidth;
    if (left < 8) left = rect.left;
    if (left + panelWidth > window.innerWidth - 8) left = window.innerWidth - panelWidth - 8;
    panel.style.top = (rect.bottom + window.scrollY + 8) + 'px';
    panel.style.left = (left + window.scrollX) + 'px';
  }

  let notificationPanelVisible = false;
  if (notificationsBtn) {
    notificationsBtn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      let panel = document.getElementById('notificationPanel') || createNotificationPanel();
      updateNotificationPanelContent();
      const clearBtn = document.getElementById('clearNotificationsBtn');
      if (clearBtn) {
        clearBtn.onclick = (ev) => {
          ev.stopPropagation();
          localStorage.setItem('notifications', JSON.stringify([]));
          updateNotificationPanelContent();
        };
      }
      if (!notificationPanelVisible) {
        // ensure panel is in DOM and measured before positioning
        panel.style.display = 'block';
        panel.style.visibility = 'hidden';
        // force reflow
        void panel.offsetWidth;
        panel.style.visibility = '';
        positionPanel(panel, notificationsBtn);
        notificationPanelVisible = true;
      } else {
        panel.style.display = 'none';
        notificationPanelVisible = false;
      }
    });

    // hide when clicking outside
    document.addEventListener('click', (ev) => {
      const panel = document.getElementById('notificationPanel');
      if (!panel) return;
      if (!panel.contains(ev.target) && ev.target !== notificationsBtn && !notificationsBtn.contains(ev.target)) {
        panel.style.display = 'none';
        notificationPanelVisible = false;
      }
    });
  }
  
  // Setup search and filter handlers
  const jobSearch = document.getElementById('jobSearch');
  const searchBtn = document.getElementById('searchBtn');
  const minSalary = document.getElementById('minSalary');
  const maxSalary = document.getElementById('maxSalary');
  const applySalaryFilter = document.getElementById('applySalaryFilter');

  // Search functionality
  jobSearch?.addEventListener('input', (e) => {
    currentFilters.search = e.target.value;
    const filteredJobs = filterJobs(
      currentFilters.search,
      currentFilters.minSalary,
      currentFilters.maxSalary
    );
    renderJobs(filteredJobs);
  });

  // Salary filter functionality
  applySalaryFilter?.addEventListener('click', () => {
    currentFilters.minSalary = Number(minSalary.value) || 0;
    currentFilters.maxSalary = Number(maxSalary.value) || Infinity;
    const filteredJobs = filterJobs(
      currentFilters.search,
      currentFilters.minSalary,
      currentFilters.maxSalary
    );
    renderJobs(filteredJobs);
  });

  // Reset salary filter
  const resetSalaryFilter = document.getElementById('resetSalaryFilter');
  resetSalaryFilter?.addEventListener('click', () => {
    currentFilters.minSalary = 0;
    currentFilters.maxSalary = Infinity;
    minSalary.value = '';
    maxSalary.value = '';
    renderJobs(jobs);
    
    // Close the dropdown
    const dropdownToggle = document.querySelector('[data-bs-toggle="dropdown"]');
    if (dropdownToggle) {
      const dropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
      if (dropdown) {
        dropdown.hide();
      }
    }
  });

  // Applications filter dropdown wiring (All / Pending Review / Under Consideration / Completed)
  (function wireApplicationsFilter() {
    const applicationsDropdown = document.querySelector('#applications .dropdown');
    if (!applicationsDropdown) return;
    const appsButton = applicationsDropdown.querySelector('button[data-bs-toggle="dropdown"]');
    const items = applicationsDropdown.querySelectorAll('.dropdown-item');
    items.forEach(item => {
      item.addEventListener('click', (ev) => {
        ev.preventDefault();
        // Update active state
        items.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        // Update visible label
        const text = item.textContent.trim();
        const labelSpan = appsButton && appsButton.querySelector('span');
        if (labelSpan) labelSpan.textContent = text;
        // Map to internal filter key
        const t = text.toLowerCase();
        if (t.includes('pending')) currentAppFilter = 'pending';
        else if (t.includes('under')) currentAppFilter = 'under';
        else if (t.includes('completed')) currentAppFilter = 'completed';
        else currentAppFilter = 'all';
        renderApplications();
      });
    });
  })();

  // Sidebar starts visible (no toggled class yet)
  document.body.classList.add("loaded");

  // Hide sidebar on first visit (persisted in localStorage)
  const hasVisited = localStorage.getItem('hasVisited');
  if (!hasVisited) {
    // first visit: hide sidebar by default
    wrapper.classList.add('toggled');
    localStorage.setItem('hasVisited', '1');
  }

  // Ensure toggle button label matches current state and wire click handler
  if (toggleBtn) {
    toggleBtn.textContent = wrapper.classList.contains("toggled") ? "☰" : "✖";
    toggleBtn.addEventListener("click", () => {
      wrapper.classList.toggle("toggled");
      toggleBtn.textContent = wrapper.classList.contains("toggled") ? "☰" : "✖";
    });
  }
  // Initialize tab system (activate tab click handlers)
  initializeTabs();
  
  // Update profile stats on initial load
  updateProfileStats();
});

// Initialize Bootstrap tabs
function initializeTabs() {
  // Create Bootstrap tab instances for each tab element
  const tabElements = document.querySelectorAll('[data-bs-toggle="tab"]');
  tabElements.forEach(tabElement => {
    const tab = new bootstrap.Tab(tabElement);
    
    // Add click handler for each tab
    tabElement.addEventListener('click', (e) => {
      e.preventDefault();
      
      // Remove active class from all list group items in sidebar
      document.querySelectorAll('.list-group-item').forEach(item => {
        item.classList.remove('active');
      });
      
      // Show the tab after removing active classes
      tab.show();
      
      // Add active class to the corresponding sidebar item if it exists
      const sidebarItem = document.querySelector(`[data-bs-target="${tabElement.getAttribute('data-bs-target')}"]`);
      if (sidebarItem) {
        sidebarItem.classList.add('active');
      }
      
      // Update profile stats when profile tab is shown
      if (tabElement.getAttribute('data-bs-target') === '#profile') {
        updateProfileStats();
      }
    });
  });
  
  // Set initial active tab - prevent hash navigation
  const jobsTab = document.querySelector('[data-bs-target="#jobs"]');
  if (jobsTab) {
    // Manually activate the tab without triggering navigation
    const jobsTabPane = document.querySelector('#jobs');
    const jobsSidebarItem = document.querySelector('[data-bs-target="#jobs"]');
    
    // Remove active from all tabs and panes
    document.querySelectorAll('.tab-pane').forEach(pane => {
      pane.classList.remove('show', 'active');
    });
    document.querySelectorAll('.list-group-item').forEach(item => {
      item.classList.remove('active');
    });
    
    // Activate jobs tab manually
    if (jobsTabPane) {
      jobsTabPane.classList.add('show', 'active');
    }
    if (jobsSidebarItem) {
      jobsSidebarItem.classList.add('active');
    }
  }
}

// ---- PROFILE SAVE & LOAD ----
document.addEventListener("DOMContentLoaded", () => {
  const profileForm = document.getElementById("profileForm");

  // Load saved profile data
  const savedProfile = JSON.parse(localStorage.getItem("userProfile"));
  if (savedProfile) {
    document.getElementById("fullName").value = savedProfile.fullName || "";
    document.getElementById("email").value = savedProfile.email || "";
    document.getElementById("phone").value = savedProfile.phone || "";
    document.getElementById("portfolio").value = savedProfile.portfolio || "";
    document.getElementById("bio").value = savedProfile.bio || "";
  }

  // Save profile data
  profileForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const profileData = {
      fullName: document.getElementById("fullName").value.trim(),
      email: document.getElementById("email").value.trim(),
      phone: document.getElementById("phone").value.trim(),
      portfolio: document.getElementById("portfolio").value.trim(),
      bio: document.getElementById("bio").value.trim()
    };

    localStorage.setItem("userProfile", JSON.stringify(profileData));
    alert("✅ Profile saved successfully!");
    updateProfileStats(); // Add this line
  });
});

// ---- JOB DATA ----
const jobs = [
  { 
    id: 1, 
    title: "Web Developer", 
    company: "Acme Corp", 
    location: "Makati City",
    salaryMin: 50000,
    salaryMax: 80000,
    type: "Full-time",
    experience: "2-4 years"
  },
  { 
    id: 2, 
    title: "Graphic Designer", 
    company: "Acme Corp", 
    location: "Makati City",
    salaryMin: 35000,
    salaryMax: 55000,
    type: "Full-time",
    experience: "1-3 years"
  },
  { 
    id: 3, 
    title: "Data Analyst", 
    company: "Acme Corp", 
    location: "Makati City",
    salaryMin: 45000,
    salaryMax: 70000,
    type: "Full-time",
    experience: "2-4 years"
  },
  { 
    id: 4, 
    title: "Software Engineer", 
    company: "Acme Corp", 
    location: "Makati City",
    salaryMin: 60000,
    salaryMax: 120000,
    type: "Full-time",
    experience: "3-5 years"
  },
  { 
    id: 5, 
    title: "UI/UX Designer", 
    company: "Acme Corp", 
    location: "Makati City",
    salaryMin: 40000,
    salaryMax: 75000,
    type: "Full-time",
    experience: "2-4 years"
  },
  { 
    id: 6, 
    title: "IT Support Specialist", 
    company: "Acme Corp", 
    location: "Makati City",
    salaryMin: 25000,
    salaryMax: 45000,
    type: "Full-time",
    experience: "1-3 years"
  },
  { 
    id: 7, 
    title: "Network Administrator", 
    company: "Acme Corp", 
    location: "Makati City",
    salaryMin: 45000,
    salaryMax: 80000,
    type: "Full-time",
    experience: "3-5 years"
  },
  { 
    id: 8, 
    title: "Database Manager", 
    company: "Acme Corp", 
    location: "Makati City",
    salaryMin: 70000,
    salaryMax: 130000,
    type: "Full-time",
    experience: "5+ years"
  },
  { 
    id: 9, 
    title: "Project Manager", 
    company: "Acme Corp", 
    location: "Makati City",
    salaryMin: 80000,
    salaryMax: 150000,
    type: "Full-time",
    experience: "5+ years"
  },
  { 
    id: 10, 
    title: "Content Writer", 
    company: "Acme Corp", 
    location: "Makati City",
    salaryMin: 30000,
    salaryMax: 50000,
    type: "Full-time",
    experience: "1-3 years"
  }
];

// ---- ELEMENTS ----
const jobList = document.getElementById("jobsList");
const applicationsTable = document.getElementById("applicationsTable");
// current applications filter: 'all' | 'pending' | 'under' | 'completed'
let currentAppFilter = 'all';

// ---- RENDER JOBS ----
// Format salary to PHP currency
function formatSalary(amount) {
  // Return formatted number without currency symbol
  const value = Number(amount) || 0;
  return value.toLocaleString('en-PH', { maximumFractionDigits: 0 });
}

// Filter jobs by search term and salary range
function filterJobs(searchTerm = '', minSalary = 0, maxSalary = Infinity) {
  return jobs.filter(job => {
    const matchesSearch = job.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         job.company.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         job.location.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesSalary = job.salaryMax >= minSalary && job.salaryMin <= maxSalary;
    return matchesSearch && matchesSalary;
  });
}

// Render jobs with filtering
function renderJobs(filteredJobs = jobs) {
  jobList.innerHTML = "";
  const applications = JSON.parse(localStorage.getItem("applications") || "[]");

  if (filteredJobs.length === 0) {
    jobList.innerHTML = `
      <div class="col-12">
        <div class="card shadow-sm p-4 text-center">
          <div class="empty-illustration mb-3">
            <svg viewBox="0 0 64 64" width="64" height="64" fill="none">
              <rect x="8" y="18" width="48" height="28" rx="3" stroke="#cfe2ff" stroke-width="2" fill="#e9f2ff"/>
              <circle cx="20" cy="30" r="3" fill="#9fc5ff"/>
              <rect x="28" y="28" width="18" height="2" rx="1" fill="#9fc5ff"/>
              <rect x="28" y="32" width="12" height="2" rx="1" fill="#9fc5ff"/>
            </svg>
          </div>
          <h6 class="mb-1">No jobs found</h6>
          <p class="text-muted mb-0">Try adjusting your search criteria</p>
        </div>
      </div>
    `;
    return;
  }

  filteredJobs.forEach(job => {
    const applied = applications.some(a => a.jobId === job.id);
    const col = document.createElement("div");
    col.className = "col-md-6 col-lg-4";
    const btnClass = applied ? 'btn-secondary' : 'btn-outline-primary';
    col.innerHTML = `
      <div class="job-card">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <h5 class="fw-bold text-primary mb-1">${job.title}</h5>
                <div class="company mb-2">${job.company}</div>
                <div class="d-flex align-items-center text-muted small gap-2">
                  <span>
                    <svg class="bi me-1" width="14" height="14" fill="currentColor">
                      <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                      <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    </svg>
                    ${job.location}
                  </span>
                  <span class="text-body-secondary">•</span>
                  <span>${job.type}</span>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <div class="d-flex align-items-center gap-2 mb-2">
                <!-- Peso SVG symbol (16x16) -->
                <svg class="bi text-primary me-1" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                  <text x="8" y="11" text-anchor="middle" font-size="12" font-family="Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial">₱</text>
                </svg>
                <span class="fw-semibold">
                  ${formatSalary(job.salaryMin)} - ${formatSalary(job.salaryMax)}
                </span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <svg class="bi text-primary" width="16" height="16" fill="currentColor">
                  <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z"/>
                  <path d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z"/>
                </svg>
                <span class="text-muted small">${job.experience} experience</span>
              </div>
            </div>
            <div class="mt-auto job-actions">
              <button class="btn ${btnClass} btn-apply w-100" data-id="${job.id}" ${applied ? 'disabled' : ''}>
                ${applied ? 'Applied' : 'Apply Now'}
              </button>
            </div>
          </div>
        </div>
      </div>
    `;
    jobList.appendChild(col);
  });

  document.querySelectorAll('[data-id]').forEach(btn => {
    btn.addEventListener("click", (e) => {
      const id = parseInt(e.target.getAttribute("data-id"));
      applyForJob(id);
    });
  });
}

// ---- APPLY FUNCTION ----
function applyForJob(jobId) {
  const applications = JSON.parse(localStorage.getItem("applications") || "[]");
  const job = jobs.find(j => j.id === jobId);

  if (applications.some(a => a.jobId === jobId)) return;

  applications.push({
    jobId: job.id,
    title: job.title,
    company: job.company,
    status: "Applied"
  });

  localStorage.setItem("applications", JSON.stringify(applications));
  renderApplications();
  renderJobs();
  updateProfileStats(); // Add this line
}

// ---- CANCEL APPLICATION ----
function cancelApplication(jobId) {
  let applications = JSON.parse(localStorage.getItem("applications") || "[]");
  applications = applications.filter(a => a.jobId !== jobId);
  localStorage.setItem("applications", JSON.stringify(applications));
  renderApplications();
  renderJobs();
  updateProfileStats(); // Add this line
}

// ---- RENDER APPLICATIONS ----
function renderApplications() {
  const applications = JSON.parse(localStorage.getItem("applications") || "[]");
  applicationsTable.innerHTML = "";

  // Apply filter
  const filtered = applications.filter(app => {
    if (currentAppFilter === 'all') return true;
    const s = (app.status || '').toLowerCase();
    if (currentAppFilter === 'pending') {
      return s.includes('appl') || s.includes('pend'); // matches 'applied' or 'pending'
    }
    if (currentAppFilter === 'under') {
      return s.includes('under') || s.includes('consider');
    }
    if (currentAppFilter === 'completed') {
      return s.includes('complete') || s.includes('hired') || s.includes('rejected') || s.includes('closed');
    }
    return true;
  });

  if (filtered.length === 0) {
    applicationsTable.innerHTML = `<tr><td colspan="4" class="text-muted">No applications match "${escapeHtml(filterLabelForKey(currentAppFilter))}".</td></tr>`;
    return;
  }

  filtered.forEach(app => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${escapeHtml(app.title)}</td>
      <td>${escapeHtml(app.company)}</td>
      <td><span class="badge badge-status ${escapeHtml((app.status||'').toLowerCase())} text-white">${escapeHtml(app.status)}</span></td>
      <td>
        <button class="btn btn-danger btn-sm" onclick="cancelApplication(${app.jobId})">
          Cancel
        </button>
      </td>
    `;
    applicationsTable.appendChild(row);
  });
}

// helper to map filter key to label
function filterLabelForKey(key) {
  switch(key) {
    case 'pending': return 'Pending Review';
    case 'under': return 'Under Consideration';
    case 'completed': return 'Completed';
    default: return 'All Applications';
  }
}

// simple html escaper for small content
function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

// ---- UPDATE PROFILE STATS ----
function updateProfileStats() {
  const applications = JSON.parse(localStorage.getItem("applications") || "[]");
  const applicationsCount = applications.length;
  
  // Update applications count
  const applicationsCountEl = document.getElementById('profileApplicationsCount');
  if (applicationsCountEl) {
    applicationsCountEl.textContent = applicationsCount;
  }
  
  // Update saved jobs count (currently no saved jobs feature, so set to 0)
  const savedJobsCountEl = document.getElementById('profileSavedJobsCount');
  if (savedJobsCountEl) {
    savedJobsCountEl.textContent = 0; // Update this if you add saved jobs functionality
  }
  
  // Update profile name and email from saved profile
  const savedProfile = JSON.parse(localStorage.getItem("userProfile") || "null");
  if (savedProfile) {
    const profileNameEl = document.getElementById('profileName');
    const profileEmailEl = document.getElementById('profileEmail');
    if (profileNameEl && savedProfile.fullName) {
      profileNameEl.textContent = savedProfile.fullName;
    }
    if (profileEmailEl && savedProfile.email) {
      profileEmailEl.textContent = savedProfile.email;
    }
  }
}

// ---- INITIALIZE ----
renderJobs();
renderApplications();