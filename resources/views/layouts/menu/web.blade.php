<li class="menu-header">Berita</li>
<li class="{{ request()->is('web/berita*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('web/berita') }}">
    <i class="fas fa-list-alt"></i>
    <span>Data Berita</span>
  </a>
</li>
<li class="{{ request()->is('web/arsip*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('web/arsip') }}">
    <i class="fas fa-archive"></i>
    <span>Data Arsip</span>
  </a>
</li>