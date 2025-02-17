<style>
    .custom-width {
        width: 100%;
    }

    @media (min-width: 768px) {

        /* md breakpoint */
        .custom-width {
            width: 30%;
        }
    }
</style>

<?php
//get last url segment
$_REQUEST['url'] = $_SERVER['REQUEST_URI'];
$classification = explode('/', $_REQUEST['url'])[3];

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
        /
        <a href="/indicators/<?= $classification . 'Indicators' ?>" class="text-muted fw-light">
            จัดการตัวชี้วัด
        </a>
        / สร้างตัวชี้วัด<?= $classification == 'major' ? 'รวม' : 'ย่อย'; ?>
    </h5>
    <div class="row">
        <div class="col-12">
            <form id="indicatorForm">
                <div class="card">
                    <div class="card-header py-2 h4 <?= $classification == 'major' ? 'bg-label-primary' : 'bg-label-warning'; ?>">
                        สร้างตัวชี้วัด<?= $classification == 'major' ? 'รวม' : 'ย่อย'; ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="yearsSelect_new" class="form-label">ปีงบประมาณ</label>
                                    <select class="form-select" id="yearsSelect_new" name="yearsSelect_new" onchange="yearSelect()" required>
                                        <option value="" disabled selected>เลือกปีงบประมาณ</option>
                                        <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                                            <option value="<?= $year ?>"><?= $year ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-8 mb-3">
                                <div class="form-group">
                                    <label for="goalsIndSelect" class="form-label">เป้าหมาย</label>
                                    <select class="form-select" id="goalsIndSelect" name="goalsIndSelect" disabled>
                                        <option value="" disabled selected>เลือกปีงบประมาณก่อน</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <!-- indicator name -->
                                <div class="form-group">
                                    <label for="indicatorName" class="form-label">ชื่อตัวชี้วัด</label>
                                    <input type="text" class="form-control" id="indicatorName" name="indicatorName" placeholder="ตัวชี้วัด" required>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="form-group">
                                    <label for="IndTarget" class="form-label">คะแนนเป้าหมาย</label>
                                    <button type="button" class="btn btn-sm btn-icon rounded-pill" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<span>ค่าเป้าหมายจะถูกรวมมากจากค่าเป้าหมายของหน่วยงาน</span>">
                                        <i class="bx bx-info-circle"></i>
                                    </button>
                                    <input type="text" class="form-control" id="IndTarget" name="IndTarget" placeholder="คะแนนเป้าหมาย" readonly required number>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="form-group">
                                    <label for="IndTargetText" class="form-label">รายละเอียดคะแนนตัวชี้วัด</label>
                                    <input type="text" class="form-control" id="IndTargetText" name="IndTargetText" placeholder="คำอธิบายคะแนนตัวชี้วัด">
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="indDivisionSelect" class="form-label">เลือกหน่วยงานที่รับผิดชอบ</label>
                                <div class="select2-primary">
                                    <select id="indDivisionSelect" name="indDivisionSelect" class="select2 form-select" multiple></select>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center mt-4">
                            <div class="col-12 col-md-6 mb-3 mb-md-0 text-md-end">
                                <button type="button" class="btn btn-outline-secondary mx-2 custom-width" onclick="pageClose(`<?= $classification ?>`)">ปิด</button>
                            </div>
                            <div class="col-12 col-md-6 text-md-start">
                                <button type="button" class="btn btn-primary mx-2 custom-width" onclick="formSubmit()" id="indFormSubmitBtn"><i class="bx bx-save pe-2"></i>บันทึก</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    const classification = <?= json_encode($classification); ?>;
    let divisionDivCount = 0;
    let divArr = [];
    const divLimit = 10;

    document.addEventListener('DOMContentLoaded', async () => {
        let divisionInputDiv = document.getElementById('divisionInputDiv');
        sumTarget();

        initDivisionSelect();

    });

    function formSubmit() {
        event.preventDefault();

        let form = document.getElementById('indicatorForm');

        //validator
        const validator = new FormValidator(form);
        if (!validator.validate()) {
            return;
        }

        let formData = new FormData(form);

        let divisionSelect = document.getElementById('indDivisionSelect');
        let divisionArr = Array.from(divisionSelect.selectedOptions).map(option => option.value);
        formData.append('divisionArr', divisionArr);

        //validator
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const url = '/indicators/newIndicator';
        axios
            .post(url, formData)
            .then((res) => {
                console.log(res.data);
                if (res.data.status === 'success') {
                    swal.fire({
                        icon: 'success',
                        title: 'เพิ่มตัวชี้วัดสำเร็จ',
                        text: 'กำลังกลับหน้าจัดการตัวชี้วัด',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    }).then(() => {
                        let url = `/indicators/${classification}Indicators`;
                        window.location.href = url;
                    })
                } else {
                    swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: res.data.message,
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    })
                }
            })
    }

    function modalTitle(classification) {
        let modalHeader = document.getElementById('modalHeader');
        let modalLabel = document.getElementById('indModalLabel');
        if (classification === 'major') {
            modalHeader.classList.remove('bg-label-warning');
            modalHeader.classList.add('bg-label-primary');
            modalLabel.innerText = 'เพิ่มตัวชี้วัดรวม';
            modalLabel.classList.add('text-primary');
        } else {
            modalHeader.classList.remove('bg-label-primary');
            modalHeader.classList.add('bg-label-warning');
            modalLabel.innerText = 'เพิ่มตัวชี้วัดย่อย';
            modalLabel.classList.add('text-warning');
        }
    }

    function yearSelect() {
        document.getElementById('goalsIndSelect').disabled = false;
        let year = document.getElementById('yearsSelect_new').value;
        getGoalByYear(year);
    }

    function getGoalByYear(year) {
        axios
            .get(`/indicators/getGoalsByYear/${year}/${classification}`)
            .then((res) => {
                let goalsSelect = document.getElementById('goalsIndSelect');
                goalsSelect.innerHTML = '';
                if (res.data.length > 0) {
                    res.data.forEach(goal => {
                        let option = document.createElement('option');
                        option.value = goal.id;
                        option.innerText = goal.title;
                        goalsSelect.appendChild(option);
                        document.getElementById('indFormSubmitBtn').disabled = false;
                    });
                } else {
                    let option = document.createElement('option');
                    option.value = '';
                    option.innerText = 'ไม่พบเป้าหมาย';
                    goalsSelect.appendChild(option);
                    document.getElementById('indFormSubmitBtn').disabled = true;
                }
            })
    }

    function selectPicker(elementId) {
        $(`#${elementId}`).selectpicker();
    }

    function pageClose(classification) {
        swal.fire({
            title: 'คุณต้องการปิดหน้านี้หรือไม่?',
            text: 'ข้อมูลที่กรอกจะไม่ถูกบันทึก',
            icon: 'warning',
            showConfirmButton: true,
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn swal-label-danger me-4',
                cancelButton: 'btn swal-label-primary btn-primary ms-4'
            },
            confirmButtonText: 'ใช่, ปิดหน้านี้',
            cancelButtonText: 'ไม่, ยกเลิก',
        }).then((result) => {
            if (result.isConfirmed) {
                let url = `/indicators/${classification}Indicators`;
                window.location.href = url;
            }
        })

    }

    function sumTarget() {
        let sumTargetDiv = document.getElementsByClassName('sumTarget');
        let indTarget = document.getElementById('IndTarget');

        let sum = 0;

        indTarget.value = '';
        indTarget.value = sum;

        sumTargetDiv.forEach(ele => {
            sum += parseInt(ele.value);
        })

        indTarget.value = sum;
    }

    async function initDivisionSelect() {
        let divisionSelect = document.getElementById('indDivisionSelect');
        let divisionArr = [];
        let divisions = await axios.get('/divisions/getDivisions');
        divisions.data.forEach(division => {
            let option = document.createElement('option');
            option.value = division.division_id;
            option.innerText = division.division_name;
            divisionSelect.appendChild(option);
        });
        $(".select2").select2({
            placeholder: "เลือกหน่วยงาน",
            tokenSeparators: [',', ' ']
        });
    }
</script>