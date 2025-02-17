<style>
    /* swal on top layer */
    .swal2-container {
        z-index: 10000;
    }
</style>
<div class="container-xxl flex-grow g-3-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        <a href="project" class="text-muted fw-light">
            / โครงการ
        </a>
        / เพิ่มโครงการ
    </h5>
    <!-- END BREADCRUMB -->

    <?php
    $yearCount = 1;
    $startYear = Budgetyear::getBudgetyearThai() - $yearCount;
    $currentYear = Budgetyear::getBudgetyearThai();
    ?>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-primary py-2 h4">
                    เพิ่มโครงการ
                </div>
                <form id="newProjectForm" action="" method="POST">
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-3">
                                <div class="form-group input-group-lg">
                                    <label for="yearsSelect" class="form-label">ปีงบประมาณ</label>
                                    <select class="form-select" id="yearsSelect" name="yearsSelect" onchange="appendStrategies()">
                                        <option value="" disabled selected>เลือกปีงบประมาณ</option>
                                        <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                                            <option value="<?= $year ?>" <?= $year == $currentYear ? 'selected' : '' ?>><?= $year ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <div class="form-group input-group-lg">
                                    <label for="projetName" class="form-label">ชื่อโครงการ</label>
                                    <input type="text" class="form-control" id="projetName" name="projetName" placeholder="ชื่อโครงการ" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <div class="form-group input-group-lg">
                                    <label for="divisionSelect" class="form-label">หน่วยงาน</label>
                                    <input type="text" class="form-control" id="divisionSelect" name="divisionSelect" placeholder="หน่วยงาน" value="<?= $division['division_name']; ?>" readonly disabled>
                                    <input type="hidden" id="divisionId" name="divisionId" value="<?= $division['division_id']; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group input-group-lg">
                                    <label for="budget" class="form-label">งบประมาณ</label>
                                    <input type="text" class="form-control" id="budget" name="budget" placeholder="หากยังไม่ได้รับจัดจรร กรุณาใส่ 0" number>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group input-group-lg">
                                    <label for="strategiesSelect" class="form-label">ยุทธศาสตร์</label>
                                    <select class="form-select" id="strategiesSelect" name="strategiesSelect"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-2">
                                <button type="button" class="btn btn-outline-secondary w-100 mb-3 mb-md-0" onclick="cancelCreate()">ยกเลิก</button>
                            </div>
                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-primary w-100" onclick="formSubmit()">บันทึก</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        appendStrategies();
    });

    function formSubmit() {
        event.preventDefault();

        let form = document.getElementById('newProjectForm');

        let validator = new FormValidator(form);
        let isVaild = validator.validate();

        if (!isVaild) {
            return;
        }

        swal.fire({
            title: 'ยืนยันการบันทึกข้อมูล',
            text: 'คุณต้องการบันทึกข้อมูลใช่หรือไม่?',
            icon: 'question',
            showCancelButton: true,
            customClass: {
                confirmButton: "btn btn-primary ms-4",
                cancelButton: "btn swal-label-danger me-4"
            },
            confirmButtonText: 'ใช่, บันทึกข้อมูล',
            cancelButtonText: 'ไม่, กลับไปแก้ไข',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })

    }

    function cancelCreate() {
        swal.fire({
            title: 'ยกเลิกการเพิ่มโครงการ',
            text: 'ข้อมูลที่กรอกจะไม่ถูกบันทึก คุณต้องการยกเลิกใช่หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                confirmButton: "btn swal-label-danger me-4",
                cancelButton: "btn btn-primary ms-4"
            },
            confirmButtonText: 'ใช่, กลับหน้าหลัก',
            cancelButtonText: 'ไม่, กรอกข้อมูลต่อ',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/projects';
            }
        })
    }

    async function appendStrategies() {
        let strategies = await getStrategies();
        let strategiesSelect = document.getElementById('strategiesSelect');
        strategiesSelect.innerHTML = '';
        let option = document.createElement('option');
        option.value = '';
        option.text = 'เลือกยุทธศาสตร์';
        strategiesSelect.appendChild(option);
        strategies.forEach(strategy => {
            let option = document.createElement('option');
            option.value = strategy.id;
            option.text = strategy.strategy_name;
            strategiesSelect.appendChild(option);
        });

    }

    async function getStrategies() {
        let year = document.getElementById('yearsSelect').value;
        const url = `/strategies/getStrategies/${year}`;
        const res = await axios.get(url);
        return res.data;
    }
</script>