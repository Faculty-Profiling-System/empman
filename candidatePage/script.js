document.addEventListener("DOMContentLoaded", () => {
  const wrapper = document.getElementById("wrapper");
  const toggleBtn = document.getElementById("menu-toggle");

  // Sidebar starts visible (no toggled class yet)
  document.body.classList.add("loaded");

  // Toggle sidebar visibility on click
  toggleBtn.addEventListener("click", () => {
    wrapper.classList.toggle("toggled");
    toggleBtn.textContent = wrapper.classList.contains("toggled") ? "☰" : "✖";
  });
});

document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
  tab.addEventListener('shown.bs.tab', function (e) {
    document.querySelectorAll('.list-group-item').forEach(item => item.classList.remove('active'));
    e.target.classList.add('active');
  });
});


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
  });
});

// ---- JOB DATA ----
const jobs = [
  { id: 1, title: "Web Developer", company: "Acme Corp", location: "Makati City" },
  { id: 2, title: "Graphic Designer", company: "Acme Corp", location: "Makati City" },
  { id: 3, title: "Data Analyst", company: "Acme Corp", location: "Makati City" },
  { id: 4, title: "Software Engineer", company: "Acme Corp", location: "Makati City" },
  { id: 5, title: "UI/UX Designer", company: "Acme Corp", location: "Makati City" },
  { id: 6, title: "IT Support Specialist", company: "Acme Corp", location: "Makati City" },
  { id: 7, title: "Network Administrator", company: "Acme Corp", location: "Makati City" },
  { id: 8, title: "Database Manager", company: "Acme Corp", location: "Makati City" },
  { id: 9, title: "Project Manager", company: "Acme Corp", location: "Makati City" },
  { id: 10, title: "Content Writer", company: "Acme Corp", location: "Makati City" }
];

// ---- ELEMENTS ----
const jobList = document.getElementById("jobsList");
const applicationsTable = document.getElementById("applicationsTable");

// ---- RENDER JOBS ----
function renderJobs() {
  jobList.innerHTML = "";
  const applications = JSON.parse(localStorage.getItem("applications") || "[]");

  jobs.forEach(job => {
    const applied = applications.some(a => a.jobId === job.id);
    const col = document.createElement("div");
    col.className = "col-md-6 col-lg-4";
    const btnClass = applied ? 'btn-secondary' : 'btn-outline-primary';
    col.innerHTML = `
      <div class="job-card">
        <div class="card shadow-sm p-3 h-100">
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <h5 class="fw-bold text-primary mb-1">${job.title}</h5>
                <div class="company">${job.company}</div>
              </div>
              <div class="location small text-muted">${job.location}</div>
            </div>
            <div class="mt-auto job-actions">
              <button class="btn ${btnClass} btn-apply btn-sm w-100" data-id="${job.id}" ${applied ? 'disabled' : ''}>
                ${applied ? 'Applied' : 'Apply'}
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
}

// ---- CANCEL APPLICATION ----
function cancelApplication(jobId) {
  let applications = JSON.parse(localStorage.getItem("applications") || "[]");
  applications = applications.filter(a => a.jobId !== jobId);
  localStorage.setItem("applications", JSON.stringify(applications));
  renderApplications();
  renderJobs();
}

// ---- RENDER APPLICATIONS ----
function renderApplications() {
  const applications = JSON.parse(localStorage.getItem("applications") || "[]");
  applicationsTable.innerHTML = "";

  if (applications.length === 0) {
    applicationsTable.innerHTML = `<tr><td colspan="4" class="text-muted">No applications yet.</td></tr>`;
    return;
  }

  applications.forEach(app => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${app.title}</td>
      <td>${app.company}</td>
      <td><span class="badge badge-status ${app.status.toLowerCase()} text-white">${app.status}</span></td>
      <td>
        <button class="btn btn-danger btn-sm" onclick="cancelApplication(${app.jobId})">
          Cancel
        </button>
      </td>
    `;
    applicationsTable.appendChild(row);
  });
}

// ---- INITIALIZE ----
renderJobs();
renderApplications();
