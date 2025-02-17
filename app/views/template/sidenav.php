<?php

$menuArray = [
    [
        'id' => 1,
        'Title' => 'หน้าแรก',
        'Link' => '/home',
        'Header' => 'หน้าแรก',
        'Icon' => 'bx bx-home-circle text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [], // 1 = admin, 2 = user, 3 = director, [] = all
        'Submenu' => [],
    ],
    [
        'id' => 2,
        'Title' => 'Dashboard',
        'Link' => '/home/dashboard',
        'Header' => '',
        'Icon' => 'bx bxs-dashboard text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [],
        'Submenu' => [],
    ],
    [
        'id' => 3,
        'Title' => 'โครงการ',
        'Link' => '/projects',
        'Header' => 'รายงาน',
        'Icon' => 'bx bxs-cog text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [1, 2],
        'Submenu' => [],
    ],
    [
        'id' => 4,
        'Title' => 'โครงการศธภ.',
        'Link' => '/projects/reo',
        'Header' => '',
        'Icon' => 'bx bxs-cog text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [4],
        'Submenu' => [],
    ],
    [
        'id' => 5,
        'Title' => 'ตัวชี้วัด',
        'Link' => '/indicators/reports',
        'Header' => '',
        'Icon' => 'bx bxs-tachometer text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [2],
        'Submenu' => [],
    ],
    [
        'id' => 6,
        'Title' => 'แบบสงป.',
        'Link' => '/budgetform/reports',
        'Header' => '',
        'Icon' => 'bx bxs-file text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [2],
        'Submenu' => [],
    ],
    [
        'id' => 7,
        'Title' => 'แบบฟอร์มสงป.',
        'Link' => '/budgetform/admin',
        'Header' => '',
        'Icon' => 'bx bxs-tachometer text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [1],
        'Submenu' => [],
    ],
    [
        'id' => 8,
        'Title' => 'ยืนยันคะแนน',
        'Link' => '/indicators/approve',
        'Header' => 'ตัวชี้วัด',
        'Icon' => 'bx bxs-flag-checkered text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [3],
        'Submenu' => [],
    ],
    [
        'id' => 9,
        'Title' => 'จัดการเป้าหมาย',
        'Link' => '/indicators/goals',
        'Header' => 'ตัวชี้วัด',
        'Icon' => 'bx bxs-bullseye text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [1],
        'Submenu' => [],
    ],
    [
        'id' => 10,
        'Title' => 'จัดการตัวชี้วัด',
        'Link' => '',
        'Header' => '',
        'Icon' => 'bx bxs-tachometer text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [1],
        'Submenu' => [
            [
                'id' => 11,
                'Title' => 'ตัวชี้วัดรวม',
                'Link' => '/indicators/majorIndicators',
                'Header' => '',
                'Icon' => ' text-primary',
                'Notify' => '',
                'Order' => 0,
                'Role' => [1],
                'Submenu' => [],
            ],
            [
                'id' => 12,
                'Title' => 'ตัวชี้วัดย่อย',
                'Link' => '/indicators/minorIndicators',
                'Header' => '',
                'Icon' => ' text-primary',
                'Notify' => '',
                'Order' => 0,
                'Role' => [1],
                'Submenu' => [],
            ],
        ],
    ],
    [
        'id' => 13,
        'Title' => 'ค่าเป้าหมาย',
        'Link' => '/indicators/targetApprove',
        'Header' => 'คำขอ',
        'Icon' => 'bx bxs-flag-checkered text-primary',
        'Notify' => '/indicators/getPendingRequestTargetsCount',
        'Order' => 0,
        'Role' => [1, 3],
        'Submenu' => [],
    ],
    [
        'id' => 14,
        'Title' => 'ยุทธศาสตร์',
        'Link' => '/strategies',
        'Header' => 'ข้อมูลระบบ',
        'Icon' => 'bx bxs-data text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [1],
        'Submenu' => [],
    //         [
    //             'id' => 15,
    //             'Title' => 'ประกาศ',
    //             'Link' => '/annouce',
    //             'Header' => '',
    //             'Icon' => ' text-primary',
    //             'Notify' => '',
    //             'Order' => 0,
    //             'Role' => [1],
    //             'Submenu' => [],
    //         ],
    ],
    [
        'id' => 18,
        'Title' => 'ประกาศ',
        'Link' => '/annouce',
        'Header' => 'ประกาศ',
        'Icon' => 'bx bx-news text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [1],
        'Submenu' => [],
    ],
    [
        'id' => 19,
        'Title' => 'จัดการผู้ใช้',
        'Link' => '/users',
        'Header' => 'ผู้ใช้งาน',
        'Icon' => 'bx bx-user-circle text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [1],
        'Submenu' => [],
    ],
    [
        'id' => 90,
        'Title' => 'เอกสารเผยแพร่',
        'Link' => '/publish',
        'Header' => 'เอกสารเผยแพร่',
        'Icon' => 'bx bxs-folder-open text-primary',
        'Notify' => '',
        'Order' => 0,
        'Role' => [],
        'Submenu' => [],
    ],
    [
        'id' => 99,
        'Title' => 'ออกจากระบบ',
        'Link' => '/auth/logout',
        'Header' => 'ออกจากระบบ',
        'Icon' => 'bx bx-log-out-circle text-danger',
        'Notify' => '',
        'Order' => 0,
        'Role' => [],
        'Submenu' => [],
    ],
];

function menuRender($menuArray)
{
    $html = '';
    $role = User::roles();
    sort($role);
    foreach ($menuArray as $menu) {

        //check role if $menu['Role'] match with $_SESSION['role'] or empty show menu
        if (array_intersect($role, $menu['Role']) || $menu['Role'] == []) {
            //check submenu if empty show single menu
            if (empty($menu['Submenu'])) {
                $html .= (($menu['Header']) ? '<li class="menu-header small text-uppercase">
            <span class="menu-header-text">' . $menu['Header'] . '</span></li>' : '') . '            
            <li class="menu-item" onclick="setActive(' . $menu['id'] . ')" id="' . $menu['id'] . '">
                <a href="' . $menu['Link'] . '" class="menu-link">
                    ' .
                    (($menu['Icon']) ? '<i class="menu-icon tf-icons ' . $menu['Icon'] . '"></i>' : '')
                    . '
                    <div data-i18n="' . $menu['Title'] . '">' . $menu['Title'] . '</div>

                    ' . (($menu['Notify']) ? '<span class="badge badge-center rounded-pill bg-label-danger ms-1 notify" data-notify-api="' . $menu['Notify'] . '"></span>' : '') . '

                </a>
            </li>
            ';
            } else {
                $html .= (($menu['Header']) ? '<li class="menu-header small text-uppercase">
            <span class="menu-header-text">' . $menu['Header'] . '</span></li>' : '') . '
            <li class="menu-item parentMenu">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ' . $menu['Icon'] . '"></i>
                    <div data-i18n="' . $menu['Title'] . '">' . $menu['Title'] . '</div>
                </a>
                <ul class="menu-sub">
                    ' . menuRender($menu['Submenu']) . '
                </ul>
            </li>
            ';
            }
        }
    }
    return $html;
}

?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- LOGO -->
    <div class="app-brand demo ">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo">
                <img src="/assets/img/moe_logo.png" alt="MOE" height="35">
            </span>
            <span class="app-brand-text menu-text fs-5 fw-bold ms-5 mb-1">
                <?php
                // if($_SESSION['user']['roles'][0] == 128) {
                //     echo APP_TITLE_TH_BICT; 
                // } else {
                //     echo APP_TITLE_TH;
                // }
                echo APP_TITLE_EN_SHORT;
                ?>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <!-- LOGO -->

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <!-- SHOW USER MENU PROFILE -->
        <?php
        if (count($_SESSION) > 0) {
            echo '
            <li class="menu-item" id="userTitle">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <div data-i18n="' . User::email() . '" class="text-uppercase">' . User::email() . '</div>
            </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="userProfile">
                        <a href="/users/profile/'.User::id().'" class="menu-link" onclick="setActive(100)">
                            <i class="menu-icon tf-icons bx bxs-user-account text-primary"></i>
                            <div>ตั้งค่าบัญชี</div>
                        </a>
                    </li>
                </ul>
            </li>
                ';
        }
        ?>
        <!-- SHOW USER MENU PROFILE -->

        <!-- <li class=" menu-header small text-uppercase mt-0">
            <span class="menu-header-text">เมนู</span>
        </li> -->
        <?php echo menuRender($menuArray); ?>
    </ul>
</aside>

<!-- HAMBERGER MENU -->
<nav class="layout-navbar container-fluid navbar navbar-expand-xl" id="layout-navbar" style="background-color:RGBA(255,255,255,0) !important; z-index:0;">
    <div class=" layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none mt-2 ms-2">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>
</nav>
<!-- HAMBERGER MENU -->

<script>
    checkActive();

    function setActive(id) {
        localStorage.setItem('activeMenuId', id);
    }

    function checkActive() {
        if (localStorage.getItem('activeMenuId') == null) {
            localStorage.setItem('activeMenuId', 1);
            window.location.href = '/home';
        }

        if(localStorage.getItem('activeMenuId') == 100) {
            document.getElementById('userTitle').classList.add('active', 'open');
            document.getElementById('userProfile').classList.add('active');
        }
        const activeMenuId = localStorage.getItem('activeMenuId') ?? 1;
        const activeMenu = document.getElementById(activeMenuId);
        const activeMenuParent = activeMenu.parentElement.parentElement;

        if (activeMenuParent.classList.contains('parentMenu')) {
            activeMenuParent.classList.add('active', 'open');
            activeMenu.classList.add('active');
        } else {
            activeMenu.classList.add('active');
        }
    }

    function getNotify() {
        const notify = document.getElementsByClassName('notify');
        for (let i = 0; i < notify.length; i++) {
            let notifyItem = notify[i];
            let notifyLink = notifyItem.getAttribute('data-notify-api');
            fetch(notifyLink)
                .then(response => response.json())
                .then(data => {
                    if (data > 0) {
                        notifyItem.innerHTML = data;
                    } else {
                        notifyItem.innerHTML = '';
                    }
                });
        }
    }
    getNotify();
</script>