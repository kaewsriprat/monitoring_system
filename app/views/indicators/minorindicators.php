<?php 

$yearCount = 2;
$startYear = Budgetyear::getBudgetyearThai() - $yearCount;
$currentYear = Budgetyear::getBudgetyearThai();

?>
<div class=" container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        / จัดการตัวชี้วัด
    </h5>

    <div class="row">
        <div class="col-12">
            <div class="nav-align-top">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="/indicators/majorIndicators">เป้าหมายรวม</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link active btn-warning" role="tab" data-bs-toggle="tab" data-bs-target="#minorInd" aria-controls="minorInd" aria-selected="true">เป้าหมายย่อย</button>
                    </li>
                </ul>
                <div class="tab-content px-0">
                    <div class="tab-pane fade show active" id="minorInd" role="tabpanel">
                        <?php include 'components/minorInd.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'components/indicatorDetailModal.php'; ?>
</div>