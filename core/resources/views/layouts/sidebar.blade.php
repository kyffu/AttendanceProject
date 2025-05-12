<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/favicon/logo.png') }}" alt="Logo Gemati" width="50">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2 text-uppercase">SATELYTE</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        @role(['karywan','mandor','tukang','spv','superadmin'])
        <li class="menu-item {{ request()->is('attendances*') ? 'active' : '' }}">
            <a href="" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-notepad"></i>
                <div data-i18n="Authentications">Kehadiran</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('attendances/attendance*') ? 'active' : '' }}">
                    <a href="{{ route('attendance.index') }}" class="menu-link">
                        <div data-i18n="Basic">Presensi</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('attendances/absent*') ? 'active' : '' }}">
                    <a href="{{ route('attendance.absent.index') }}" class="menu-link">
                        <div data-i18n="Basic">Tidak Hadir</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('attendances/overtime*') ? 'active' : '' }}">
                    <a href="{{ route('attendance.overtime.index') }}" class="menu-link">
                        <div data-i18n="Basic">Lembur</div>
                    </a>
                </li>
                @role(['mandor', 'spv','superadmin'])
                <li class="menu-item {{ request()->is('attendances/reimburse*') ? 'active' : '' }}">
                    <a href="{{ route('attendance.reimburse.index') }}" class="menu-link">
                        <div data-i18n="Basic">Reimburse</div>
                    </a>
                </li>
                @endrole
                @role(['mandor', 'spv', 'superadmin'])
                <li class="menu-item {{ request()->is('attendances/validate*') ? 'active' : '' }}">
                    <a href="{{ route('attendance.validate.index') }}" class="menu-link">
                        <div data-i18n="Basic">Validasi Presensi</div>
                    </a>
                </li>
                @endrole
            </ul>
        </li>
        @endrole
        @role(['superadmin', 'pm'])
        <li class="menu-item {{ request()->is('project*') ? 'active' : '' }}">
            <a href="{{ route('project.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-hard-hat"></i>
                <div data-i18n="Mandor">Proyek Mandor</div>
            </a>
        </li>
        @endrole
        @role(['superadmin', 'spv', 'mandor'])
        <li class="menu-item {{ request()->is('reports*') ? 'active' : '' }}">
            <a href="" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxs-report"></i>
                <div>Laporan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('reports/attendance*') ? 'active' : '' }}" style="">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div class="text-truncate" data-i18n="Products">Laporan Presensi</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item  {{ request()->is('reports/attendance/date*') ? 'active' : '' }}">
                            <a href="{{ route('report.attendance.bydate.index') }}" class="menu-link">
                                <div class="text-truncate" data-i18n="Product List">Per Bulan</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('reports/attendance/staff*') ? 'active' : '' }}">
                            <a href="{{ route('report.attendance.bystaff.index') }}" class="menu-link">
                                <div class="text-truncate" data-i18n="Add Product">Per Pegawai</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item {{ request()->is('reports/payroll*') ? 'active' : '' }}">
                    <a href="{{ route('report.payroll.index') }}" class="menu-link">
                        <div data-i18n="Basic">Laporan Gaji</div>
                    </a>
                </li>
            </ul>
        </li>
        @endrole

        @role(['superadmin','admin'])
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Pengaturan</span></li>
        <li class="menu-item {{ request()->is('user*') ? 'active' : '' }}">
            <a href="{{ route('user.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                <div data-i18n="Mandor">Pengaturan Pengguna</div>
            </a>
        </li>
        
        
        <li class="menu-item {{ request()->is('role*') ? 'active' : '' }}">
            <a href="{{ route('role.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                <div data-i18n="Mandor">Pengaturan Role</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('settings*') ? 'active' : '' }}">
            <a href="" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div>Pengaturan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('settings/position*') ? 'active' : '' }}">
                    <a href="{{ route('settings.position.index') }}" class="menu-link">
                        <div data-i18n="Basic">Jabatan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('settings/salary*') ? 'active' : '' }}">
                    <a href="{{ route('settings.salary.index') }}" class="menu-link">
                        <div data-i18n="Basic">Gaji</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('settings/allowance*') ? 'active' : '' }}">
                    <a href="{{ route('settings.allowance.index') }}" class="menu-link">
                        <div data-i18n="Basic">Tunjangan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('settings/shift*') ? 'active' : '' }}">
                    <a href="{{ route('shift.index') }}" class="menu-link">
                        <div data-i18n="Basic">Shift</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('settings/absent*') ? 'active' : '' }}">
                    <a href="{{ route('settings.absent.index') }}" class="menu-link">
                        <div data-i18n="Basic">Master Absen</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('settings/general*') ? 'active' : '' }}">
                    <a href="{{ route('settings.general.index') }}" class="menu-link">
                        <div data-i18n="Basic">Umum</div>
                    </a>
                </li>
            </ul>
        </li>
        @endrole
    </ul>
</aside>
