<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link">
        <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ Auth::guard()->user()->nursery->name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::guard()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link {{Route::currentRouteName() == 'dashboard' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            الرئيسية
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('seedling-services.index')}}" class="nav-link {{Route::currentRouteName() == 'seedling-services.index' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-seedling" ></i>
                        <p>خدمة تشتيل المزارعين</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('seedling-purchase-requests.index')}}" class="nav-link {{Route::currentRouteName() == 'seedling-purchase-requests.index' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-seedling" style="color: #8cba92;"></i>
                        <p>مبيعات اشتال خاصة مشتل</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('nursery-seeds-sales.index')}}" class="nav-link {{Route::currentRouteName() == 'nursery-seeds-sales.index' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-allergies"></i>
                        <p>مبيعات بذور المشتل</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('warehouse-entities.index')}}" class="nav-link {{Route::currentRouteName() == 'warehouse-entities.index' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p> مخزن المشتل</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-marker"></i>
                        <p>سياسة الخصوصية</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>شروط الاستخدام</p>
                    </a>
                </li>

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" id="logout">
                        @csrf
                    </form>
                    <a href="javascript:{}" onclick="$('#logout').submit();" class="nav-link">
                        <i class="nav-icon fa fa-sign-out-alt"></i>
                        <p>
                            تسجيل الخروج
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
