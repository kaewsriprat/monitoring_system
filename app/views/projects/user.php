<div class="container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        / โครงการ
    </h5>
    <!-- END BREADCRUMB -->
    <?php
    $yearCount = 2;
    $startYear = Budgetyear::getBudgetyearThai() - $yearCount;
    $currentYear = Budgetyear::getBudgetyearThai();

    // print_r($projects);
    // print_r($projectStatus);
    // print_r($divisions);
    // exit;
    ?>

    <?php include 'components/statusCard.php'; ?>

    <hr>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-primary py-2 h4">
                    โครงการทั้งหมด
                </div>
                <div class="card-body">
                        <div class="d-flex justify-content-between flex-md-row flex-column">
                            <div class="col-12 col-md-4">
                                <label for="yearsSelect" class="form-label">ปีงบประมาณ</label>
                                <select class="form-select" id="yearsSelect" name="yearsSelect" onchange="tableYearFilter()">
                                    <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                                        <option value="<?= $year ?>"><?= $year ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mt-1 col-12 col-md-4 text-center text-md-end">
                                <a class="btn btn-label-primary mt-4" href="create"><i class="bx bx-plus pe-2"></i>เพิ่มโครงการ</a>
                            </div>
                        </div>
                </div>
                <?php include 'components/userTable.php'; ?>
            </div>
        </div>
    </div>

    <?php include 'components/uploadModal.php'; ?>

    <!-- Project Detail Modal -->
    <div class="modal fade" id="projectDetailModal" tabindex="-1" aria-labelledby="projectDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="projectDetailModalLabel">รายละเอียดโครงการ</h5>
                </div>
                <hr>
                <div class="modal-body pt-1">
                    <div class="row">
                        <div class="col-12 fs-5 mb-2">
                            <span class="text-muted my-1">ชื่อโครงการ</span>
                            <span id="projectTitle"></span>
                        </div>
                        <div class="col-12 fs-5 mb-2 col-md-6">
                            <span class="text-muted my-1">งบประมาณ: </span>
                            <span id="total_budget"></span>
                        </div>
                        <div class="col-12 fs-5 mb-2 col-md-6">
                            <span class="text-muted my-1">ปีงบประมาณ: </span>
                            <span id="projectBudgetYear"></span>
                        </div>
                        <div class="col-12 fs-5 mb-2">
                            <span class="text-muted my-1">หน่วยงาน: </span>
                            <span id="division_name"></span>
                        </div>
                        <div class="col-12 fs-5 mb-2">
                            <span class="text-muted my-1">ยุทธศาสตร์: </span>
                            <span id="strategy_name"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <p class="fs-5 mb-0">สถานะการรายงาน</p>
                            <div class="table-responsive">
                                <table class="table table-hover pointer" id="reportedTable">
                                    <thead class="table-light">
                                        <tr id="divRow">
                                            <th>ไตรมาส</th>
                                            <th class="text-center">การรายงาน</th>
                                            <th>วันที่รายงาน</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-3">
                    <button type="button" class="btn btn-secondary w-px-100" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initDatatable();
    });
</script>