<style>
    aside {
        z-index: 100 !important;
    }

    nav {
        z-index: 99 !important;
    }

    .swal2-popup {
        z-index: 99999;
    }

    .filter-option-inner-inner {
        color: #697a8d !important;
    }

    .dropdown-menu {
        z-index: 99999;
    }

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

$classification = $indicatorDetail['classification'];

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
        / ปรับปรุงตัวชี้วัด<?= $classification == 'major' ? 'รวม' : 'ย่อย'; ?>
    </h5>
    <div class="row">
        <div class="col-12">
            <form id="indicatorForm">
                <input type="hidden" name="indicatorId" value="<?= $indicatorDetail['indicator_id'] ?>">
                <div class="card">
                    <div class="card-header py-2 h4 <?= $classification == 'major' ? 'bg-label-primary' : 'bg-label-warning'; ?>">
                        สร้างตัวชี้วัด<?= $classification == 'major' ? 'รวม' : 'ย่อย'; ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="yearsSelect" class="form-label">ปีงบประมาณ</label>
                                    <select class="form-select" id="yearsSelect" name="yearsSelect" disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-8 mb-3">
                                <div class="form-group">
                                    <label for="goalsIndSelect" class="form-label">เป้าหมาย</label>
                                    <select class="form-select" id="goalsIndSelect" name="goalsIndSelect"></select>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
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
                        </div>

                        <div class="row my-3">

                            <div class="col-12" id="existedDivisionInputDiv"></div>
                            <div class="col-12" id="divisionInputDiv"></div>
                            <div class="col-12">
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-success fw-bold" onclick="addDivisionInput()" id="addDivisionBtn">
                                        <i class="bx bx-plus pe-2"></i>เพิ่มหน่วยงานรับผิดชอบ
                                    </button>
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
    const indicatorDetail = <?= json_encode($indicatorDetail) ?>;

    const year = indicatorDetail.year;
    const goals = indicatorDetail.goals;
    const classification = indicatorDetail.classification;
    const indicatorName = indicatorDetail.title;
    const indicatorTarget = indicatorDetail.target;
    const indicatorTargetText = indicatorDetail.target_detail;

    let updateReports = [];
    let deleteReports = [];

    let divisionDivCount = 0;
    let divArr = [];
    const divLimit = 10;

    document.addEventListener('DOMContentLoaded', async () => {
        // set year select
        const yearSelect = document.getElementById('yearsSelect');
        // create option
        let option = document.createElement('option');
        option.value == year
        option.innerText = year;
        option.selected = true;

        yearSelect.appendChild(option);

        await getGoalByYear(year);

        // set division input
        if (indicatorDetail.reports[0].rep_id != null) {
            for (let i = 0; i < indicatorDetail.reports.length; i++) {
                updateReports.push(indicatorDetail.reports[i].rep_id);
                addExistedDivisionInput(indicatorDetail.reports[i]);
            }
        }

        // set goals select
        const goalsSelect = document.getElementById('goalsIndSelect');
        goalsSelect.value = indicatorDetail.goal_id;

        const indicatorNameInput = document.getElementById('indicatorName');
        indicatorNameInput.value = indicatorName;

        const indicatorTargetInput = document.getElementById('IndTarget');
        indicatorTargetInput.value = indicatorTarget;

        const indicatorTargetTextInput = document.getElementById('IndTargetText');
        indicatorTargetTextInput.value = indicatorTargetText;

        let divisionInputDiv = document.getElementById('divisionInputDiv');
        let observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.addedNodes.length > 0) {
                    let addedElement = mutation.addedNodes[0];
                    let divisionSelect = addedElement.querySelector('.indDivisionSelect');
                    let divCounter = divisionSelect.id.split('_')[1];
                    divisionSelect.addEventListener('change', () => {
                        let divisionId = divisionSelect.value;
                        getProjectsByDivision(divCounter, divisionId);
                    });
                    axios
                        .get(`/divisions/getDivisions`)
                        .then((res) => {
                            let divisionsList = res.data;
                            divisionSelect.innerHTML = '';
                            let disabledOption = document.createElement('option');
                            disabledOption.value = '';
                            disabledOption.innerText = 'เลือกหน่วยงาน';
                            disabledOption.disabled = true;
                            disabledOption.selected = true;
                            divisionSelect.appendChild(disabledOption);
                            divisionsList.forEach(division => {
                                let option = document.createElement('option');
                                option.value = division.division_id;
                                option.innerText = division.division_name;
                                option.setAttribute('data-tokens', division.division_name);
                                divisionSelect.appendChild(option);
                            });
                            selectPicker(`indDivisionSelect_${divCounter}`);
                        })
                }
            });
        });
        observer.observe(divisionInputDiv, {
            childList: true
        });


        sumTarget();
    });

    async function getGoalByYear(year) {
        let data = await axios
            .get(`/indicators/getGoalsByYear/${year}/${classification}`)
            .then((res) => {
                return res.data
            })
        let goalsSelect = document.getElementById('goalsIndSelect');
        goalsSelect.innerHTML = '';
        if (data.length > 0) {
            data.forEach(goal => {
                let option = document.createElement('option');
                option.value = goal.id;
                option.innerText = goal.title;
                goalsSelect.appendChild(option);
            });

        } else {
            let option = document.createElement('option');
            option.value = '';
            option.innerText = 'ไม่พบเป้าหมาย';
            goalsSelect.appendChild(option);
        }

    }

    function addExistedDivisionInput(reports) {
        event.preventDefault();
        if (divArr.length >= divLimit) {
            document.getElementById('addDivisionBtn').disabled = true;
            return;
        }
        divisionDivCount++;

        if (divArr.includes(divisionDivCount)) {
            divisionDivCount++;
        }

        divArr.push(divisionDivCount);

        let existedDivisionInputDiv = document.getElementById('existedDivisionInputDiv');
        let divisionInput = document.createElement('div');
        divisionInput.className = 'col-12 mb-3';
        divisionInput.innerHTML = `
        <div class="card mt-3 bg-label-secondary">
            <div class="card-body">
                <input type="hidden" name="edit_${reports.rep_id}" value="${reports.rep_id}">
                <h5 class="card-title fw-bold">หน่วยงานรับผิดชอบตัวชี้วัด</h5>
                <div class="row">
                    <div class="col-11">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <input type="text" class="form-control text-dark fs-5" value="${reports.division_name}" readonly disabled>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <select class="form-select" id="indProjectSelect_${divisionDivCount}" name="edit_${reports.rep_id}_indProjectSelect"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="form-floating">
                                <input type="text" class="form-control sumTarget" id="indDivisionTarget_${divisionDivCount}" name="edit_${reports.rep_id}_indDivisionTarget" placeholder="ค่าเป้าหมาย" onkeyup="sumTarget()" required number>
                                <label for="indDivisionTarget_${divisionDivCount}">ค่าเป้าหมาย</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                <input type="text" class="form-control" id="indDivisionTargetText_${divisionDivCount}" name="edit_${reports.rep_id}_indDivisionTargetText" placeholder="คำอธิบายค่าเป้าหมาย">
                                <label for="indDivisionTargetText_${divisionDivCount}">คำอธิบายค่าเป้าหมาย</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-end my-auto">
                        <button class="btn btn-icon rounded-pill btn-sm btn-outline-danger mt-2" onclick="addRemoveDiv(${reports.rep_id}, this)"><i class="bx bx-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
        `;
        divisionInputDiv.appendChild(divisionInput);
        getExistedProjectsByDivision(divisionDivCount, reports.rep_division_id);
        document.getElementById(`indProjectSelect_${divisionDivCount}`).value = reports.rep_project_id;
        document.getElementById(`indDivisionTarget_${divisionDivCount}`).value = reports.rep_target;
        document.getElementById(`indDivisionTargetText_${divisionDivCount}`).value = reports.rep_target_detail;

    }

    function addDivisionInput() {
        event.preventDefault();
        if (divArr.length >= divLimit) {
            document.getElementById('addDivisionBtn').disabled = true;
            return;
        }

        divisionDivCount++;

        if (divArr.includes(divisionDivCount)) {
            divisionDivCount++;
        }

        divArr.push(divisionDivCount);

        let divisionInputDiv = document.getElementById('divisionInputDiv');
        let divisionInput = document.createElement('div');
        divisionInput.className = 'col-12 mb-3';
        divisionInput.innerHTML = `
        <div class="card mt-3 bg-label-secondary">
            <div class="card-body">
                <h5 class="card-title fw-bold">หน่วยงานรับผิดชอบตัวชี้วัด</h5>
                <div class="row">
                    <div class="col-11">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <select class="indDivisionSelect selectpicker w-100" data-style="btn-default" data-live-search="true" id="indDivisionSelect_${divisionDivCount}" name="new_indDivisionSelect_${divisionDivCount}" required></select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <select class="form-select" id="indProjectSelect_${divisionDivCount}" name="new_indProjectSelect_${divisionDivCount}"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="form-floating">
                                <input type="text" class="form-control sumTarget" id="indDivisionTarget_${divisionDivCount}" name="new_indDivisionTarget_${divisionDivCount}" placeholder="ค่าเป้าหมาย" onkeyup="sumTarget()" required number>
                                <label for="indDivisionTarget_${divisionDivCount}">ค่าเป้าหมาย</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                <input type="text" class="form-control" id="indDivisionTargetText_${divisionDivCount}" name="new_indDivisionTargetText_${divisionDivCount}" placeholder="คำอธิบายค่าเป้าหมาย">
                                <label for="indDivisionTargetText_${divisionDivCount}">คำอธิบายค่าเป้าหมาย</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-end my-auto">
                        <button class="btn btn-icon rounded-pill btn-sm btn-outline-danger mt-2" onclick="removeIndDivisionInput(this, ${divisionDivCount})"><i class="bx bx-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
        `;

        divisionInputDiv.appendChild(divisionInput);

    }

    function removeIndDivisionInput(e, id) {
        e.closest('.col-12').remove();
        divArr = divArr.filter(item => item !== id);
        document.getElementById('addDivisionBtn').disabled = false;
        sumTarget()
    }

    function selectPicker(elementId) {
        $(`#${elementId}`).selectpicker();
    }

    function getProjectsByDivision(divCounter, divisionId) {
        let year = document.getElementById('yearsSelect').value;
        axios
            .get(`/projects/getProjectsByDivision/${year}/${divisionId}`)
            .then((res) => {
                let projectSelect = document.getElementById(`indProjectSelect_${divCounter}`);
                projectSelect.innerHTML = '';
                if (res.data.length > 0) {
                    let disableOption = document.createElement('option');
                    disableOption.value = '';
                    disableOption.innerText = 'เลือกโครงการ';
                    disableOption.disabled = true;
                    disableOption.selected = true;
                    projectSelect.appendChild(disableOption);
                    res.data.forEach(project => {
                        let option = document.createElement('option');
                        option.value = project.project_ID;
                        option.innerText = project.project_name;
                        projectSelect.appendChild(option);
                    });
                } else {
                    let option = document.createElement('option');
                    option.value = '';
                    option.innerText = 'ไม่พบโครงการ';
                    projectSelect.appendChild(option);
                }
            })
    }

    function getExistedProjectsByDivision(divCounter, divisionId) {
        let year = document.getElementById('yearsSelect').value;

        axios
            .get(`/projects/getProjectsByDivision/${year}/${divisionId}`)
            .then((res) => {
                let projectSelect = document.getElementById(`indProjectSelect_${divCounter}`);
                if (res.data.length > 0) {

                    res.data.forEach(project => {
                        let option = document.createElement('option');
                        option.value = project.project_ID;
                        option.innerText = project.project_name;
                        projectSelect.appendChild(option);
                    });
                } else {
                    let option = document.createElement('option');
                    option.value = '';
                    option.innerText = 'ไม่พบโครงการ';
                    projectSelect.appendChild(option);
                }
            })
    }

    function clearDivisionInput() {
        let divisionInputDiv = document.getElementById('divisionInputDiv');
        divisionInputDiv.innerHTML = '';
        divisionDivCount = 0;
    }

    function addRemoveDiv(repId, e) {
        event.preventDefault()
        swal.fire({
            icon: 'warning',
            title: 'คุณต้องการลบหน่วยงานนี้หรือไม่?',
            html: '<p class="mb-0">การลบไม่สามารถย้อนกลับได้</p><p class="mb-0">หน่วยงานที่รับผิดชอบ และคะแนนทั้งหมด จะถูกลบด้วย</p><p class="mb-0">ต้องการจะลบหน่วยงานนี้หรือไม่ ?</p>',
            showConfirmButton: true,
            showCancelButton: true,
            customClass: {
                confirmButton: "btn swal-label-danger me-4",
                cancelButton: 'btn swal-label-primary btn-primary ms-4'
            },
            confirmButtonText: 'ใช่, ลบตัวชี้วัดนี้',
            cancelButtonText: 'ไม่, ยกเลิกการลบ',
        }).then((result) => {
            if (result.isConfirmed) {
                swal.fire({
                    icon: 'info',
                    title: 'ตัวชี้วัดจะถูกลบหลังจากกดบันทึกหน้านี้',
                    showConfirmButton: true,
                    customClass: {
                        confirmButton: "btn btn-label-primary",
                    },
                    confirmButtonText: 'รับทราบ',
                }).then(() => {
                    removeIndDivisionInput(e, repId);
                    deleteReports.push(repId);
                })
            }
        })


    }

    function formSubmit() {
        event.preventDefault();

        let form = document.getElementById('indicatorForm');

        //validator
        const validator = new FormValidator(form);
        if (!validator.validate()) {
            return;
        }

        let formData = new FormData(form);
        formData.append('updateReports', JSON.stringify(updateReports));
        formData.append('deleteReports', JSON.stringify(deleteReports));

        const url = '/indicators/editIndicator';
        axios
            .post(url, formData)
            .then((res) => {

                if (res.data.status === 'success') {
                    swal.fire({
                        icon: 'success',
                        title: 'ปรับปรุงตัวชี้วัดสำเร็จ',
                        text: 'กำลังกลับหน้าจัดการตัวชี้วัด',
                        showConfirmButton: false,
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.href = `/indicators/${classification}Indicators`;
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

    function pageClose(classification) {
        swal.fire({
            title: 'คุณต้องการปิดหน้านี้หรือไม่?',
            text: 'การปรับปรุงจะไม่ถูกบันทึก',
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
            if(isNaN(parseInt(ele.value)) || ele.value === '') {
                ele.value = 0;
            }
            sum += parseInt(ele.value);
        })

        indTarget.value = sum;
    }
</script>