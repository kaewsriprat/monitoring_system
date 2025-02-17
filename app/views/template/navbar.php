<!-- Layout container -->
<div class="layout-page">

    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">

        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>


        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <div class="navbar-nav align-items-center">
                <div class="nav-item navbar-search-wrapper">
                    <a class="nav-item nav-link search-toggler" href="/">
                        <img src="../../assets/img/moe_logo.png" alt="MOE" height="35">
                       
                        <?php
                            $userId = $_SESSION['user']['id'];
                            
                            if($userId == 128){
                                echo '<span class="d-none d-md-inline-block ps-3 fs-5">'. APP_TITLE_TH_BICT .'</span>';
                            }else{
                                echo '<span class="d-none d-md-inline-block ps-3 fs-5">'. APP_TITLE_TH .' </span>';
                            }

                        ?>
                        
                    </a>
                </div>
            </div>
        </div>

    </nav>

    <div class="content-wrapper">