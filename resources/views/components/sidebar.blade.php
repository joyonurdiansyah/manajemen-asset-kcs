    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link {{ Request::is('home*') ? 'active' : 'collapsed' }}" href="{{ route('home') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <!-- Sidebar for IT Asset Management -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#lokasi-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-geo-alt"></i><span>Master Lokasi</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="lokasi-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="master-site.html">
                            <i class="bi bi-circle"></i><span>Site</span>
                        </a>
                    </li>
                    <li>
                        <a href="master-divisi.html">
                            <i class="bi bi-circle"></i><span>Divisi</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Master Lokasi Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="master-user.html">
                    <i class="bi bi-people"></i><span>Master User</span>
                </a>
            </li><!-- End Master User Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('asset-status*') ? 'active' : 'collapsed' }}"
                    href="{{ route('assets.fetch') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Master Barang</span>
                </a>
            </li><!-- End Master Barang Nav -->

            <li class="nav-heading">Manajemen Vendor</li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="audit-lokasi.html">
                    <i class="bi bi-clipboard-data"></i>
                    <span>Audit Lokasi</span>
                </a>
            </li><!-- End Audit Lokasi Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="klasifikasi-site.html">
                    <i class="bi bi-diagram-3"></i>
                    <span>Kirim ke Gudang Kering</span>
                </a>
            </li><!-- End Klasifikasi Site Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="approval-asset.html">
                    <i class="bi bi-patch-check"></i>
                    <span>Approval Asset</span>
                </a>
            </li><!-- End Approval Asset Nav -->

        </ul>

    </aside>
