<li class="menu-header">Berita</li>
<li class="{{ request()->is('web/berita*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('web/berita') }}">
    <i class="fas fa-list-alt"></i>
    <span>Data Berita</span>
  </a>
</li>