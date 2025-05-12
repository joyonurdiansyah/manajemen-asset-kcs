<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Request::is('home*') ? 'active' : 'collapsed' }}" href="{{ route('home') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <!-- Sidebar for IT Asset Management -->
        @canany(['view_sites', 'view_divisions', 'view_categories', 'view_subcategories'])
        <li class="nav-item">
            <a class="nav-link {{ Request::is('master-site', 'user-division', 'master-divisi', 'category*', 'sub-category*') || Request::routeIs('site.home', 'user.division.home', 'category.home', 'subCategory.index') ? '' : 'collapsed' }}"
            data-bs-target="#lokasi-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-geo-alt"></i><span>Referensi Data</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="lokasi-nav" class="nav-content collapse {{ Request::is('master-site', 'user-division', 'master-divisi', 'category*', 'sub-category*') || Request::routeIs('site.home', 'user.division.home', 'category.home', 'subCategory.index') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                @can('view_sites')
                <li>
                    <a href="{{ route('site.home') }}" class="{{ Request::routeIs('site.home') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Site</span>
                    </a>
                </li>
                @endcan
                
                @can('view_divisions')
                <li>
                    <a href="{{ route('user.division.home') }}" class="{{ Request::routeIs('user.division.home') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>User & Divisi</span>
                    </a>
                </li>
                @endcan
                
                @can('view_categories')
                <li>
                    <a href="{{ route('category.home') }}" class="{{ Request::routeIs('category.home') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Kategori Item</span>
                    </a>
                </li>
                @endcan
                
                @can('view_subcategories')
                <li>
                    <a href="{{ route('subCategory.index') }}" class="{{ Request::routeIs('subCategory.index') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Subkategori Item</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany
        
        @can('view_permissions')
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('role.permissions.index') ? 'active' : 'collapsed' }}" href="{{ route('role.permissions.index') }}">
                <i class="bi bi-people"></i><span>Role Permissions</span>
            </a>
        </li><!-- End Master User Nav -->
        @endcan

        @can('view_assets')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('asset-status*') ? 'active' : 'collapsed' }}"
                href="{{ route('assets.fetch') }}">
                <i class="bi bi-box-seam"></i>
                <span>Master Barang</span>
            </a>
        </li><!-- End Master Barang Nav -->
        @endcan

        <li class="nav-heading">Inspeksi Asset IT</li>

        @hasrole('super-admin|Developer|Approval|manager')
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('jadwal.index') ? 'active' : 'collapsed' }}" href="{{ route('jadwal.index') }}">
                <i class="bi bi-clipboard-data"></i>
                <span>Penjadwalan Audit</span>
            </a>
        </li><!-- End Audit Lokasi Nav -->
        @endhasrole

        @hasrole('super-admin|Developer')
        <li class="nav-item">
            <a class="nav-link collapsed" href="klasifikasi-site.html">
                <i class="bi bi-diagram-3"></i>
                <span>Pengecekkan barang</span>
            </a>
        </li><!-- End Klasifikasi Site Nav -->
        @endhasrole
    </ul>
</aside>