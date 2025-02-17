<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed " dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?= Site::title($title); ?></title>

    <?php include 'css_import.php'; ?>

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar layout-without-menu">
        <div class="layout-container">
            <!-- Layout container -->
            <div class="layout-page">

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

                        <div class="navbar-nav align-items-center">
                            <div class="nav-item navbar-search-wrapper mb-0">
                                <a class="nav-item nav-link search-toggler px-0" href="/">
                                    <img src="../../assets/img/moe_logo.png" alt="MOE" height="28">
                                    <span class="d-none d-md-inline-block pt-3 ms-1">
                                        <h6><?php echo APP_TITLE_TH; ?></h6>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <?php
                        if (!empty($_SESSION)) {
                            echo  '<ul class="navbar-nav flex-row align-items-center ms-auto">
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="bx bx-grid-alt bx-sm"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                <a class="dropdown-item" href="/users/profile">
                                <i class="bx bx-user me-2"></i>
                                <span class="align-middle">My Profile</span>
                                </a>
                                </li>
                                <li>
                                <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                <a class="dropdown-item" href="users/logout" target="_blank">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                                </a>
                                </li>
                                </ul>
                                </li>
                                </ul>';
                            }
                        ?>
                    </div>

                </nav>

                <div class="content-wrapper">

                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">