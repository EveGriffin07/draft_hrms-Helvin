<aside class="sidebar">

  {{-- DASHBOARD --}}
    <div class="sidebar-group {{ request()->is('admin/dashboard*') || request()->is('admin/assistant') ? 'open' : '' }}">
        <a href="#" class="sidebar-toggle">
            <div class="left">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </a>
        <ul class="submenu">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard Overview</a></li>
            <li><a href="{{ route('admin.announcements.index') }}">View Announcements</a></li>
            <li><a href="{{ route('admin.announcements.create') }}">Add Announcement</a></li>
            <li><a href="{{ route('admin.assistant') }}">AI Assistant</a></li>
        </ul>
    </div>


  {{-- RECRUITMENT --}}
    <div class="sidebar-group {{ request()->is('admin/recruitment*') ? 'open' : '' }}">
        <a href="#" class="sidebar-toggle">
            <div class="left">
                <i class="fa-solid fa-briefcase"></i>
                <span>Recruitment</span>
            </div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </a>
        <ul class="submenu">
            <li><a href="{{ route('admin.recruitment.index') }}">Overview</a></li>
            <li><a href="{{ route('admin.recruitment.create') }}">Add Job Posting</a></li>
            <li><a href="{{ url('/admin/recruitment/applicants') }}">View Applicants</a></li>
        </ul>
    </div>

  {{-- APPRAISAL --}}
    <div class="sidebar-group {{ request()->is('admin/appraisal*') ? 'open' : '' }}">
        <a href="#" class="sidebar-toggle">
            <div class="left">
                <i class="fa-solid fa-chart-line"></i>
                <span>Appraisal</span>
            </div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </a>
        <ul class="submenu">
            <li><a href="{{ route('admin.appraisal') }}">KPI Overview</a></li>
            <li><a href="{{ url('/admin/appraisal/add-kpi') }}">Add KPI Goals</a></li>
            <li><a href="{{ url('/admin/appraisal/reviews') }}">Performance Reviews</a></li>
            <li><a href="{{ url('/admin/appraisal/employee-kpi-list') }}">Employee KPI List</a></li>
        </ul>
    </div>

  {{-- TRAINING --}}
    <div class="sidebar-group {{ request()->is('admin/training*') ? 'open' : '' }}">
        <a href="#" class="sidebar-toggle">
            <div class="left">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Training</span>
            </div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </a>
        <ul class="submenu">
            <li><a href="{{ route('admin.training') }}">Training Overview</a></li>
            <li><a href="{{ url('/admin/training/add') }}">Add Training Program</a></li>
        </ul>
    </div>

   {{-- ONBOARDING --}}
    <div class="sidebar-group {{ request()->is('admin/onboarding*') ? 'open' : '' }}">
        <a href="#" class="sidebar-toggle">
            <div class="left">
                <i class="fa-solid fa-user-check"></i>
                <span>Onboarding</span>
            </div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </a>
        <ul class="submenu">
            <li><a href="{{ route('admin.onboarding') }}">Onboarding Overview</a></li>
            <li><a href="{{ url('/admin/onboarding/add') }}">Add New Onboarding</a></li>
        </ul>
    </div>

    {{-- REPORTS (NEW) --}}
    <div class="sidebar-group {{ request()->is('admin/reports') ? 'open' : '' }}">
        <a href="#" class="sidebar-toggle">
            <div class="left">
                <i class="fa-solid fa-file-contract"></i>
                <span>Reports</span>
            </div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </a>

        <ul class="submenu">
            <li><a href="{{ route('admin.reports.dashboard') }}">Central Report Dashboard</a></li>
        </ul>
    </div>

  {{-- Employee Management --}}
  <div class="sidebar-group">
    <a href="{{ route('admin.employee.list') }}" class="sidebar-toggle">
      <div class="left"><i class="fa-solid fa-users"></i><span>Employee Management</span></div>
      <i class="fa-solid fa-chevron-right arrow"></i>
    </a>
    <ul class="submenu">
      <li><a href="{{ url('/admin/employee/list') }}">Employee Overview</a></li>
      <li><a href="{{ url('/admin/employee/add') }}">Add Employee</a></li>
    </ul>
  </div>

  {{-- Attendance Management --}}
  <div class="sidebar-group">
    <a href="{{ route('admin.attendance.tracking') }}" class="sidebar-toggle">
      <div class="left">
        <i class="fa-solid fa-user-clock"></i>
        <span>Attendance Management</span>
      </div>
      <i class="fa-solid fa-chevron-right arrow"></i>
    </a>
    <ul class="submenu">
      <li><a href="{{ url('/admin/attendance/tracking') }}">Attendance Tracking</a></li>
      <li><a href="{{ url('/admin/attendance/penalty') }}">Penalty Removal & Tracking</a></li>
    </ul>
  </div>

  {{-- Payroll Management --}}
  <div class="sidebar-group">
    <a href="{{ route('admin.payroll.overtime') }}" class="sidebar-toggle">
      <div class="left">
        <i class="fa-solid fa-file-invoice-dollar"></i>
        <span>Payroll Management</span>
      </div>
      <i class="fa-solid fa-chevron-right arrow"></i>
    </a>
    <ul class="submenu">
      <li><a href="{{ route('admin.payroll.overtime') }}">Claim Overtime</a></li>
      <li><a href="{{ route('admin.payroll.salary') }}">Salary Calculation</a></li>
    </ul>
  </div>

  {{-- Leave Management --}}
  <div class="sidebar-group">
    <a href="{{ route('admin.leave.request') }}" class="sidebar-toggle">
      <div class="left">
        <i class="fa-solid fa-plane-departure"></i>
        <span>Leave Management</span>
      </div>
      <i class="fa-solid fa-chevron-right arrow"></i>
    </a>
    <ul class="submenu">
      <li><a href="{{ route('admin.leave.request') }}">Leave Request</a></li>
      <li><a href="{{ route('admin.leave.balance') }}">Leave Balance Tracking</a></li>
    </ul>
  </div>

  <div class="sidebar-divider"></div>
  <a class="sidebar-quick" href="{{ route('admin.dashboard') }}">
    <i class="fa-solid fa-house"></i>
    <span>My Home</span>
  </a>

  {{-- Logout --}}
  <div class="sidebar-main-item">
    <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <div class="left">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <span>Logout</span>
      </div>
    </a>
  </div>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
  </form>
</aside>

<script>
(function () {
  // 1. Prevent double-binding
  if (window.__HRMS_SIDEBAR_INIT__) return;
  window.__HRMS_SIDEBAR_INIT__ = true;

  const STORAGE_KEY = 'hrms_sidebar_open_group';
  const groups = document.querySelectorAll(".sidebar-group");

  // 2. On Page Load: Restore the last opened group from localStorage
  const savedIndex = localStorage.getItem(STORAGE_KEY);
  if (savedIndex !== null && groups[savedIndex]) {
    groups[savedIndex].classList.add("open");
  }

  // 3. Handle Clicks (Event Delegation)
  document.addEventListener("click", function (e) {

    // If another script already handled this click (e.g., page-specific sidebar logic), skip to avoid double toggling.
    if (e.defaultPrevented) return;

    const toggle = e.target.closest(".sidebar-toggle");
    if (!toggle) return;

    e.preventDefault();

    const group = toggle.closest(".sidebar-group");
    if (!group) return;

    const isOpen = group.classList.contains("open");
    let activeIndex = -1;

    // Close all other groups (Accordion effect)
    groups.forEach((g, index) => {
      g.classList.remove("open");
      // Keep track of which index we just opened
      if (g === group && !isOpen) {
        activeIndex = index;
      }
    });

    // Toggle the clicked group and update storage
    if (!isOpen) {
      group.classList.add("open");
      localStorage.setItem(STORAGE_KEY, activeIndex);
    } else {
      group.classList.remove("open");
      localStorage.removeItem(STORAGE_KEY);
    }
  });
})();

</script>

