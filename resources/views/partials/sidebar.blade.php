<!-- Sidebar -->
<aside class="sidebar">
    <h2>Menu</h2>
    <ul class="menu-list">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.all_data') }}">Master Data</a></li>
        <li class="has-submenu">
            <div class="dropdown-title">Team Verification ▼</div>
            <ul class="submenu">
                <li><a href="{{ route('admin.tv_team_list') }}">Team List</a></li>
                <li><a href="{{ route('admin.tv_team_verification') }}">Team Verification Online</a></li>
                <li><a href="{{ route('admin.tv_team_awards') }}">Team Awards</a></li>
            </ul>
        </li>
    </ul>

    <h2>Camp</h2>
    <ul class="menu-list">
        <li><a href="{{ route('admin.camper_team') }}">Campers</a></li>
    </ul>

    <h2>Publications</h2>
    <ul class="menu-list">
        <li class="has-submenu">
            <div class="dropdown-title">Website ▼</div>
            <ul class="submenu">
                <li><a href="{{ route('admin.pub_schedule') }}">Schedules and Results</a></li>
                <li><a href="#">Statistic (Optional)</a></li>
        <li><a href="{{ route('admin.sponsor.sponsor') }}">Sponsor</a></li>
                </ul>
        </li>
        <li class="has-submenu">
            <div class="dropdown-title">Media ▼</div>
            <ul class="submenu">
                 <li><a href="{{ route('admin.news.index') }}">News</a></li>
                 <li><a href="{{ route('admin.videos.index') }}">Videos</a></li>
                 <li><a href="#">Gallery</a></li>
            </ul>
        </li>
    </ul>

      <!-- Term and Conditions -->
    <h2>Term and Conditions</h2>
    <ul class="menu-list">
      <li>
        <a href="{{ route('admin.term_conditions.index') }}">Manage S&K Dokumen</a>
      </li>
    </ul>
</aside>
