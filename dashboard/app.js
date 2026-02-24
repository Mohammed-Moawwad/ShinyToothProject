const sampleUrl = 'data/sample-data.json';

let currentRole = null;
let charts = {};

const selectors = {
  homePage: document.getElementById('homePage'),
  app: document.getElementById('app'),
  homeBtn: document.getElementById('homeBtn'),
  navLogo: document.querySelector('.nav-logo'),
  loginBtn: document.getElementById('loginBtn'),
  loginModal: document.getElementById('loginModal'),
  submitLogin: document.getElementById('submitLogin'),
  cancelLogin: document.getElementById('cancelLogin'),
  closeModal: document.querySelector('.close-modal'),
  username: document.getElementById('username'),
  password: document.getElementById('password'),
  loginError: document.getElementById('loginError'),
  adminView: document.getElementById('adminView'),
  dentistView: document.getElementById('dentistView'),
  patientView: document.getElementById('patientView'),
  searchInput: document.querySelector('.search-input'),
  searchBtn: document.querySelector('.search-btn')
};

// Mock Auth - Role-based users
function mockAuth(username, password) {
  const users = {
    admin: { password: 'admin', role: 'admin' },
    dentist: { password: 'pass', role: 'dentist' },
    patient: { password: 'pass', role: 'patient' }
  };
  const user = users[username];
  return user && user.password === password ? user : null;
}

function showLogin() { 
  selectors.loginModal.classList.remove('hidden');
  selectors.username.focus();
}

function hideLogin() { 
  selectors.loginModal.classList.add('hidden');
  selectors.username.value = '';
  selectors.password.value = '';
  selectors.loginError.textContent = '';
}

// Navigation functions
function showHome() {
  selectors.homePage.classList.remove('hidden');
  selectors.app.classList.add('hidden');
  currentRole = null;
  destroyAllCharts();
}

function showDashboard() {
  selectors.homePage.classList.add('hidden');
}

function destroyAllCharts() {
  Object.values(charts).forEach(chart => {
    if (chart) chart.destroy();
  });
  charts = {};
}

function hideAllViews() {
  selectors.adminView.classList.add('hidden');
  selectors.dentistView.classList.add('hidden');
  selectors.patientView.classList.add('hidden');
}

function showRoleView(role) {
  hideAllViews();
  if (role === 'admin') selectors.adminView.classList.remove('hidden');
  else if (role === 'dentist') selectors.dentistView.classList.remove('hidden');
  else if (role === 'patient') selectors.patientView.classList.remove('hidden');
}

async function fetchData() {
  const res = await fetch(sampleUrl);
  if (!res.ok) throw new Error('Failed to load sample data');
  return res.json();
}

// ADMIN DASHBOARD
function renderAdminView(data) {
  const admin = data.admin || {};
  document.getElementById('admin-patients').textContent = (admin.totalPatients || 0).toString();
  document.getElementById('admin-dentists').textContent = (admin.activeDentists || 0).toString();
  document.getElementById('admin-appts').textContent = (admin.apptToday || 0).toString();
  document.getElementById('admin-revenue').textContent = `$${(admin.dailyRevenue || 0).toLocaleString()}`;
  
  if (admin.appointmentsByDentist) {
    renderChart('adminChart', {
      labels: admin.appointmentsByDentist.map(d => d.dentist),
      datasets: [{
        label: 'Appointments',
        data: admin.appointmentsByDentist.map(d => d.count),
        backgroundColor: '#1e9b8b',
        borderColor: '#168574',
        borderWidth: 1
      }]
    }, 'bar');
  }
}

// DENTIST DASHBOARD
function renderDentistView(data) {
  const dentist = data.dentist || {};
  document.getElementById('dentist-patients').textContent = (dentist.myPatients || 0).toString();
  document.getElementById('dentist-appts').textContent = (dentist.apptToday || 0).toString();
  document.getElementById('dentist-completed').textContent = (dentist.completedToday || 0).toString();
  document.getElementById('dentist-pending').textContent = (dentist.pendingTreatments || 0).toString();
  
  if (dentist.weekSchedule) {
    renderChart('dentistChart', {
      labels: dentist.weekSchedule.map(d => d.day),
      datasets: [{
        label: 'Scheduled Appointments',
        data: dentist.weekSchedule.map(d => d.count),
        borderColor: '#1e9b8b',
        backgroundColor: 'rgba(30, 155, 139, 0.08)',
        borderWidth: 2,
        tension: 0.3
      }]
    }, 'line');
  }
}

// PATIENT DASHBOARD
function renderPatientView(data) {
  const patient = data.patient || {};
  document.getElementById('patient-next').textContent = patient.nextAppt || '—';
  document.getElementById('patient-dentist').textContent = patient.dentistName || '—';
  document.getElementById('patient-visits').textContent = (patient.totalVisits || 0).toString();
  document.getElementById('patient-pending').textContent = patient.pendingWork || '—';
  
  if (patient.visitHistory) {
    renderChart('patientChart', {
      labels: patient.visitHistory.map(v => v.date),
      datasets: [{
        label: 'Visit Cost ($)',
        data: patient.visitHistory.map(v => v.cost),
        borderColor: '#1e9b8b',
        backgroundColor: 'rgba(30, 155, 139, 0.08)',
        borderWidth: 2,
        tension: 0.3
      }]
    }, 'line');
  }
}

function renderChart(canvasId, config, type = 'line') {
  const canvas = document.getElementById(canvasId);
  if (!canvas) return;
  
  if (charts[canvasId]) charts[canvasId].destroy();
  
  charts[canvasId] = new Chart(canvas.getContext('2d'), {
    type,
    data: config,
    options: {
      responsive: true,
      plugins: { legend: { display: true, position: 'bottom' } },
      scales: type === 'bar' ? {
        y: { beginAtZero: true }
      } : {
        y: { min: 0 }
      }
    }
  });
}

async function loadDashboard(role) {
  try {
    const data = await fetchData();
    currentRole = role;
    
    // Show appropriate view
    showDashboard();
    selectors.app.classList.remove('hidden');
    showRoleView(role);
    
    // Render role-specific content
    if (role === 'admin') renderAdminView(data);
    else if (role === 'dentist') renderDentistView(data);
    else if (role === 'patient') renderPatientView(data);
  } catch (err) {
    console.error(err);
    alert('Unable to load dashboard data. See console.');
  }
}

// Event Listeners
selectors.homeBtn.addEventListener('click', showHome);
selectors.navLogo.addEventListener('click', showHome);
selectors.loginBtn.addEventListener('click', showLogin);
selectors.cancelLogin.addEventListener('click', hideLogin);
selectors.closeModal.addEventListener('click', hideLogin);

// Close modal when clicking outside
selectors.loginModal.addEventListener('click', (e) => {
  if (e.target === selectors.loginModal) hideLogin();
});

// Role selector buttons
document.querySelectorAll('.role-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const role = btn.dataset.role;
    loadDashboard(role);
  });
});

// Login form submission
selectors.submitLogin.addEventListener('click', async () => {
  const u = selectors.username.value.trim();
  const p = selectors.password.value;
  
  if (!u || !p) {
    selectors.loginError.textContent = 'Enter username and password';
    return;
  }
  
  const userObj = mockAuth(u, p);
  if (!userObj) {
    selectors.loginError.textContent = 'Invalid credentials';
    return;
  }
  
  selectors.loginError.textContent = '';
  hideLogin();
  await loadDashboard(userObj.role);
});

// Search functionality
function handleSearch(query) {
  const services = {
    'appointment': '#services',
    'booking': '#services',
    'checkup': '#services',
    'cleaning': '#services',
    'restoration': '#services',
    'cosmetic': '#services',
    'whitening': '#services',
    'emergency': '#services',
    'preventive': '#services',
    'team': '#team',
    'doctor': '#team',
    'dentist': '#team',
    'contact': '#contact',
    'location': '#contact',
    'phone': '#contact',
    'hours': '#contact'
  };

  const lowerQuery = query.toLowerCase().trim();
  
  if (!lowerQuery) {
    alert('Please enter a search term');
    return;
  }

  for (const [keyword, section] of Object.entries(services)) {
    if (lowerQuery.includes(keyword)) {
      const target = document.querySelector(section);
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        return;
      }
    }
  }

  alert(`No results found for "${query}". Try searching for: appointment, team, contact, etc.`);
}

// Search event listeners
if (selectors.searchBtn) {
  selectors.searchBtn.addEventListener('click', () => {
    handleSearch(selectors.searchInput.value);
  });
}

if (selectors.searchInput) {
  selectors.searchInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      handleSearch(selectors.searchInput.value);
    }
  });
}
