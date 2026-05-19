<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    @role('admin|super admin')
                        <li class="{{ currentSelectedURL('admin.dashboard') }}">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="fa-solid fa-gauge"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endrole
                    <li class="{{ currentSelectedURL('admin.region.dashboard') }}">
                        <a href="{{ route('admin.region.dashboard') }}">
                            <i class="fa-solid fa-map"></i>
                            <span>Region Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ currentSelectedURL('admin.region.dashboard.trending.form') }}">
                        <a href="{{ route('admin.region.dashboard.trending.form') }}">
                            <i class="fa-solid fa-fire"></i>
                            <span>Trending Items</span>
                        </a>
                    </li>
                    <li class="{{ currentSelectedURL('head-tag.index') }}">
                        <a href="{{ route('head-tag.index') }}">
                            <i class="fa-solid fa-code"></i>
                            <span>Head Tags</span>
                        </a>
                    </li>
                    <li class="treeview">
                        @canany([
                            'config',
                            'create config',
                            'edit config',
                            'delete config',
                            'logo edit',
                            'favicon
                            edit',
                            ])
                            <a href="#">
                                <i class="fa-solid fa-gear"></i>
                                <span>Website Setting</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                        @endcan
                        <ul class="treeview-menu">
                            @can('favicon edit')
                                <li class="{{ currentSelectedURL('admin.config.favicon') }}">
                                    <a href="{{ route('admin.config.favicon') }}">
                                        <i class="fa-regular fa-circle-dot"></i>
                                        Favicon</a>
                                </li>
                            @endcan
                            @can('logo edit')
                                <li class="{{ currentSelectedURL('admin.config.logo') }}">
                                    <a href="{{ route('admin.config.logo') }}">
                                        <i class="fa-regular fa-circle-dot"></i>
                                        Logo</a>
                                </li>
                            @endcan

                            @can('edit config')
                                <li class="{{ currentSelectedURL('admin.config.settings') }}">
                                    <a href="{{ route('admin.config.settings') }}">
                                        <i class="fa-regular fa-circle-dot"></i>
                                        Config</a>
                                </li>
                            @endcan

                        </ul>
                    </li>

                    @foreach ($laravelAdminMenus->menus as $section)
                        @if ($section->items)
                            <li class="header">{{ $section->section }}</li>
                            @foreach ($section->items as $menu)
                                <li class="treeview">
                                    <a href="#">
                                        {!! $menu->icon !!}

                                        <span>{{ $menu->title }}</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-right pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu">

                                        <li
                                            class="{{ Request::is('admin' . $menu->url . '/create') ? 'active' : '' }}">
                                            <a href="{{ url('/admin' . $menu->url . '/create') }}"><i
                                                    class="icon-Commit"><span class="path1"></span><span
                                                        class="path2"></span></i>Add {{ $menu->title }}</a>
                                        </li>
                                        <li class="{{ Request::is('admin' . $menu->url) ? 'active' : '' }}">
                                            <a href="{{ url('/admin' . $menu->url) }}"><i class="icon-Commit"><span
                                                        class="path1"></span><span
                                                        class="path2"></span></i>{{ $menu->title }} List</a>
                                        </li>
                                    </ul>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                    @role('super admin')
                        <li class="header">Roles</li>
                        <li class="treeview">
                            <a href="#">
                                <i class="icon-Library"><span class="path1"></span><span class="path2"></span></i>
                                <span>Roles</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @can('create role')
                                    <li class="{{ currentSelectedURL('roles.create') }}">
                                        <a href="{{ route('roles.create') }}"><i class="icon-Commit"><span
                                                    class="path1"></span><span class="path2"></span></i>Add Roles</a>
                                    </li>
                                @endcan
                                @can('role')
                                    <li class="{{ currentSelectedURL('roles.index') }}">
                                        <a href="{{ route('roles.index') }}"><i class="icon-Commit"><span
                                                    class="path1"></span><span class="path2"></span></i>Roles List</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                        <li class="header">Permissions</li>
                        <li class="treeview">
                            <a href="#">
                                <i class="icon-Library"><span class="path1"></span><span class="path2"></span></i>
                                <span>Permissions</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @can('create permission')
                                    <li class="{{ currentSelectedURL('permissions.create') }}">
                                        <a href="{{ route('permissions.create') }}"><i class="icon-Commit"><span
                                                    class="path1"></span><span class="path2"></span></i>Add Permission</a>
                                    </li>
                                @endcan
                                @can('permission')
                                    <li class="{{ currentSelectedURL('permissions.index') }}">
                                        <a href="{{ route('permissions.index') }}"><i class="icon-Commit"><span
                                                    class="path1"></span><span class="path2"></span></i>Permission List</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endrole

                    <li class="header">Ecommerce</li>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa-solid fa-folder"></i>
                            <span>Categories</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ currentSelectedURL('admin.category.create') }}">
                                <a href="{{ route('admin.category.create') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Add Category</a>
                            </li>
                            <li class="{{ currentSelectedURL('admin.category.index') }}">
                                <a href="{{ route('admin.category.index') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Categories List</a>
                            </li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa-solid fa-image"></i>
                            <span>Banners</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ currentSelectedURL('admin.banner.create') }}">
                                <a href="{{ route('admin.banner.create') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Add Banner</a>
                            </li>
                            <li class="{{ currentSelectedURL('admin.banner.index') }}">
                                <a href="{{ route('admin.banner.index') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Banners List</a>
                            </li>
                        </ul>
                    </li>

                    
                                
                    <li class="treeview">
                        <a href="#">
                            <i class="fa-solid fa-store"></i>
                                
                                <span>Stores</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">

                            <li class="{{ currentSelectedURL('admin.stores.create') }}">
                                <a href="{{ route('admin.stores.create') }}">
                                 <i class="fa-regular fa-circle-dot"></i> Add Store
                                </a>
                            </li>
                            
                            <li class="{{ currentSelectedURL('admin.stores.index') }}">
                                <a href="{{ route('admin.stores.index') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Stores List
                                </a>
                            </li>
                        
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa-solid fa-percent"></i>
                            <span>Offers</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ currentSelectedURL('admin.offer.create') }}">
                                <a href="{{ route('admin.offer.create') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Add Offer</a>
                            </li>
                            <li class="{{ currentSelectedURL('admin.offer.index') }}">
                                <a href="{{ route('admin.offer.index') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Offers List</a>
                            </li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa-solid fa-file"></i>
                            <span>Pages</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ currentSelectedURL('admin.page.create') }}">
                                <a href="{{ route('admin.page.create') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Add Page</a>
                            </li>
                            <li class="{{ currentSelectedURL('admin.page.index') }}">
                                <a href="{{ route('admin.page.index') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Pages List</a>
                            </li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa-solid fa-blog"></i>
                            <span>Blogs</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ currentSelectedURL('admin.blog.create') }}">
                                <a href="{{ route('admin.blog.create') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Add Blog</a>
                            </li>
                            <li class="{{ currentSelectedURL('admin.blog.index') }}">
                                <a href="{{ route('admin.blog.index') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Blogs List</a>
                            </li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa-solid fa-share-nodes"></i>
                            <span>Social Apps</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ currentSelectedURL('admin.social-app.create') }}">
                                <a href="{{ route('admin.social-app.create') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Add Social App</a>
                            </li>
                            <li class="{{ currentSelectedURL('admin.social-app.index') }}">
                                <a href="{{ route('admin.social-app.index') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Social Apps List</a>
                            </li>
                        </ul>
                    </li>

                    <li class="treeview">
                        @canany(['user', 'create user', 'edit user'])
                            <a href="#">
                                <i class="fa-solid fa-user"></i>
                                <span>User</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                        @endcan
                        <ul class="treeview-menu">
                            @can('create user')
                                <li class="{{ currentSelectedURL('customer.create') }}">
                                    <a href="{{ route('customer.create') }}">
                                        <i class="fa-regular fa-circle-dot"></i>
                                        Add User</a>
                                </li>
                            @endcan
                            @can('user')
                                <li class="{{ currentSelectedURL('customer.index') }}">
                                    <a href="{{ route('customer.index') }}">
                                        <i class="fa-regular fa-circle-dot"></i>
                                        User List</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                    
                    @role('admin|super admin')
                    <li class="treeview">
                        <a href="#">
                            <i class="fa-solid fa-map"></i>
                            <span>Regions</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ currentSelectedURL('admin.region.create') }}">
                                <a href="{{ route('admin.region.create') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Add Region</a>
                            </li>
                            <li class="{{ currentSelectedURL('admin.region.index') }}">
                                <a href="{{ route('admin.region.index') }}">
                                    <i class="fa-regular fa-circle-dot"></i> Regions List</a>
                            </li>
                        </ul>
                    </li>
                    @endrole
                </ul>
            </div>
        </div>
    </section>
</aside>
