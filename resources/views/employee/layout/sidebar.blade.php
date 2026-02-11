<aside class="sidebar">

  {{-- 1. DASHBOARD --}}
  <div class="sidebar-group {{ request()->routeIs('employee.dashboard') ? 'open' : '' }}">
    <a href="{{ route('employee.dashboard') }}" class="sidebar-toggle sidebar-quick-link">
      <div class="left">
        <i class="fa-solid fa-chart-pie"></i>
        <span>My Dashboard</span>
      </div>
    </a>
  </div>

  {{-- 2. MY ONBOARDING --}}
  <div class="sidebar-group {{ request()->routeIs('employee.onboarding.index') ? 'open' : '' }}">
    <a href="{{ route('employee.onboarding.index') }}" class="sidebar-toggle sidebar-quick-link">
      <div class="left">
        <i class="fa-solid fa-list-check"></i>
        <span>My Onboarding</span>
      </div>
    </a>
  </div>

  {{-- 3. MY TRAINING --}}
  <div class="sidebar-group {{ request()->is('employee/training*') ? 'open' : '' }}">
    <a href="#" class="sidebar-toggle">
      <div class="left">
        <i class="fa-solid fa-graduation-cap"></i>
        <span>My Training</span>
      </div>
      <i class="fa-solid fa-chevron-right arrow"></i>
    </a>
    <ul class="submenu">
      <li><a href="{{ route('employee.training.index') }}">Training Overview</a></li>
    </ul>
  </div>

  {{-- 4. MY ATTENDANCE --}}
  <div class="sidebar-group {{ (request()->is('employee/attendance*') || request()->is('employee/face*')) ? 'open' : '' }}">
    <a href="#" class="sidebar-toggle">
      <div class="left">
        <i class="fa-solid fa-user-clock"></i>
        <span>My Attendance</span>
      </div>
      <i class="fa-solid fa-chevron-right arrow"></i>
    </a>
    <ul class="submenu">
      <li><a href="{{ url('/employee/attendance/log') }}">Daily Log</a></li>
      <li><a href="{{ url('/employee/attendance/overtime') }}">Overtime Records</a></li>
      <li><a href="{{ route('employee.face.verify.form') }}">Verify My Face</a></li>
    </ul>
  </div>

  {{-- 5. MY LEAVE --}}
  <div class="sidebar-group {{ request()->is('employee/leave*') ? 'open' : '' }}">
    <a href="#" class="sidebar-toggle">
      <div class="left">
        <i class="fa-solid fa-plane-departure"></i>
        <span>My Leave</span>
      </div>
      <i class="fa-solid fa-chevron-right arrow"></i>
    </a>
    <ul class="submenu">
      <li><a href="{{ url('/employee/leave/apply') }}">Apply for Leave</a></li>
      <li><a href="{{ url('/employee/leave/balance') }}">Leave Balance</a></li>
      <li><a href="{{ url('/employee/leave/history') }}">My Requests</a></li>
    </ul>
  </div>

  {{-- 6. MY PAYROLL --}}
  <div class="sidebar-group {{ request()->is('employee/payroll*') ? 'open' : '' }}">
    <a href="#" class="sidebar-toggle">
      <div class="left">
        <i class="fa-solid fa-file-invoice-dollar"></i>
        <span>My Payroll</span>
      </div>
      <i class="fa-solid fa-chevron-right arrow"></i>
    </a>
    <ul class="submenu">
      <li><a href="{{ url('/employee/payroll/payslips') }}">My Payslips</a></li>
      <li><a href="{{ url('/employee/payroll/tax') }}">Tax Documents</a></li>
    </ul>
  </div>

  {{-- ✅ 7. MY PERFORMANCE (UPDATED) --}}
  {{-- Checks if current route is 'employee.kpis' to keep menu open --}}
  <div class="sidebar-group {{ request()->is('employee/appraisal*') ? 'open' : '' }}">
    <a href="#" class="sidebar-toggle">
      <div class="left">
        <i class="fa-solid fa-chart-line"></i>
        <span>My Performance</span>
      </div>
      <i class="fa-solid fa-chevron-right arrow"></i>
    </a>
    <ul class="submenu">
      <li><a href="{{ route('employee.kpis') }}">My KPIs</a></li>
      {{-- Link to the new page --}}
      <li><a href="{{ route('employee.kpis.self-eval') }}">Self Evaluation</a></li>
    </ul>
  </div>

  <div class="sidebar-divider"></div>

  {{-- LOGOUT --}}
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
