<style>
    .scoreForm {
        width: 65px !important;
        height: 35px !important;
    }

    .scoreDiv {
        cursor: pointer;
        transition: 0.3s;
    }

    .scoreDiv:hover {
        background-color: rgba(255, 255, 255, 0.4) !important;
        transition: 0.3s;
    }
</style>
<div class="row">
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-header bg-label-primary py-2 h4">
                เป้าหมายตัวชี้วัดรวม
            </div>
            <div class="card-body px-0">
                <div class="row pt-4">
                    <div class="col-12">
                        <div class="table-responsive" id="majorIndTableWrapper">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th class="fw-bold fs-6">ตัวชี้วัด</th>
                                        <th class="fw-bold fs-6">โครงการ</th>
                                        <th class="text-center fw-bold fs-6">ค่าเป้าหมาย</th>
                                        <th class="text-center fw-bold fs-6">ไตรมาสที่ 1</th>
                                        <th class="text-center fw-bold fs-6">ไตรมาสที่ 2</th>
                                        <th class="text-center fw-bold fs-6">ไตรมาสที่ 3</th>
                                        <th class="text-center fw-bold fs-6">ไตรมาสที่ 4</th>
                                        <th class="text-center fw-bold fs-6">รวม</th>
                                        <th class="text-center fw-bold fs-6">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody id="majorTableBody"></tbody>
                            </table>
                        </div>
                        <div class="d-none d-flex justify-content-center mt-3" id="majorEmptyTable">
                            <p class="fs-4">ไม่พบข้อมูล</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header bg-label-warning py-2 h4">
                เป้าหมายตัวชี้วัดย่อย
            </div>
            <div class="card-body px-0">
                <div class="row pt-4">
                    <div class="col-12">
                        <div class="table-responsive" id="minorIndTableWrapper">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th class="fw-bold fs-6">ตัวชี้วัด</th>
                                        <th class="fw-bold fs-6">โครงการ</th>
                                        <th class="text-center fw-bold fs-6">ค่าเป้าหมาย</th>
                                        <th class="text-center fw-bold fs-6">ไตรมาสที่ 1</th>
                                        <th class="text-center fw-bold fs-6">ไตรมาสที่ 2</th>
                                        <th class="text-center fw-bold fs-6">ไตรมาสที่ 3</th>
                                        <th class="text-center fw-bold fs-6">ไตรมาสที่ 4</th>
                                        <th class="text-center fw-bold fs-6">รวม</th>
                                        <th class="text-center fw-bold fs-6">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody id="minorTableBody"></tbody>
                            </table>
                        </div>
                        <div class="d-none d-flex justify-content-center mt-3" id="minorEmptyTable">
                            <p class="fs-4">ไม่พบข้อมูล</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="requestUpdateTargetModal" tabindex="-1" aria-hidden="false">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body p-1">
                <div class="row">
                    <div class="col-12 mb-3">
                        <p class="mb-0 fs-4">ขอเปลี่ยนแปลงค่าเป้าหมาย</p>
                        <small class="text-muted fs-6">* การเปลี่ยนแปลงค่าเป้าหมายจะต้องได้รับการอนุมัติจากผู้บังคับบัญชา และสนย. ก่อน</small>
                    </div>
                    <div class="col-12 mb-3 text-center">
                        <p class="mb-0 fs-3">ค่าเป้าหมายเดิม: <span class="fw-bold" id="currentTarget"></span></p>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <input type="text" class="form-control fw-bold fs-3 text-center" id="newTargetInput" placeholder="ค่าเป้าหมายใหม่">
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-md-row justify-content-center mt-0 mt-md-4">
                        <div class="p-2 col-12 col-md-3">
                            <button class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">ยกเลิก</button>
                        </div>
                        <div class="p-2 col-12 col-md-3">
                            <button class="btn btn-primary w-100" id="requestUpdateTargetSubmit"><i class="bx bx-save pe-2"></i>ส่งคำขอ</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const division = <?= User::division(); ?>;
        let yearSelect = document.getElementById('yearsSelect');
        let year = yearSelect.value;

        try {
            const indicatorsData = await fetchIndicatorsData(year, division);
            await appendToTable(indicatorsData);

        } catch (error) {
            console.error(error);
        }
    });

    let tour = null;

    async function fetchIndicatorsData(year, division) {
        const url = `/indicators/getIndicatorsByDivision/${year}/${division}`;

        try {
            const response = await axios.get(url);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response.data;
        }
    }

    function appendToTable(data) {
        const majorTableBody = document.getElementById('majorTableBody');
        const minorTableBody = document.getElementById('minorTableBody');
        let majorHtml = '';
        let minorHtml = '';
        let classification = [];

        data.forEach(row => {
            if (!classification.includes(row.classification)) {
                classification.push(row.classification);
            }
        });

        // no indicators; hide indicators table
        if (!classification.includes('major')) {
            document.getElementById('majorIndTableWrapper').classList.add('d-none');
            document.getElementById('majorEmptyTable').classList.remove('d-none');
        } else {
            document.getElementById('majorIndTableWrapper').classList.remove('d-none');
            document.getElementById('majorEmptyTable').classList.add('d-none');
        }
        if (!classification.includes('minor')) {
            document.getElementById('minorIndTableWrapper').classList.add('d-none');
            document.getElementById('minorEmptyTable').classList.remove('d-none');
        } else {
            document.getElementById('minorIndTableWrapper').classList.remove('d-none');
            document.getElementById('minorEmptyTable').classList.add('d-none');
        }

        data.forEach(row => {

            let sumQuarter = parseInt(row.q1_score) + parseInt(row.q2_score) + parseInt(row.q3_score) + parseInt(row.q4_score);
            let progression = ((sumQuarter) / parseInt(row.target)) * 100;

            if (row.classification === 'major') {
                console.log(row);
                if (row.project_name === null) {
                    majorHtml += `
                    <tr>
                        <td>
                        ${row.ind_title}
                        <div class="progress mb-4" style="height: 5px;">
                            <div class="progress-bar" role="progressbar" id="major_progress_${row.rep_id}" style="width: ${progression}%;" aria-valuenow="${progression}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        </td>
                        <td class="fw-bold fs-5"></td>
                        <td class="text-center fw-bold fs-5" id="major_target_${row.rep_id}">${row.target ?? 0}</td>
                        <td colspan="5" class="text-center">
                            <span class="text-danger">ยังไม่ได้กำหนดโครงการ</span>
                        </td>
                        <td class="text-center" id="detailBtn">
                            <div class="d-inline-block">
                                <a href="javascript:;" class="btn btn-sm text-primary btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a href="javascript:;" class="dropdown-item" onclick="assignProject(${row.rep_id})"><i class="bx bx-edit text-info pe-2"></i>กำหนดโครงการ</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    `;
                } else {
                    majorHtml += `
                <tr>
                    <td>
                    ${row.ind_title}
                    <div class="progress mb-4" style="height: 5px;">
                        <div class="progress-bar" role="progressbar" id="major_progress_${row.rep_id}" style="width: ${progression}%;" aria-valuenow="${progression}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    </td>
                    <td class="fw-bold fs-5">${row.project_name}</td>
                    <td class="text-center fw-bold fs-5" id="major_target_${row.rep_id}">${row.target ?? 0}</td>
                    <td class="scoreDiv text-center fw-bold fs-5 bg-label-primary" id="major_1_${row.rep_id}" onclick="showInput(this,${row.q1_approve})">${row.q1_score}<i class="bx bx-check text-success ps-2 ${(row.q1_approve == 1)?'':'d-none'}"></i></td>
                    <td class="scoreDiv text-center fw-bold fs-5 bg-label-primary" id="major_2_${row.rep_id}" onclick="showInput(this,${row.q2_approve})">${row.q2_score}<i class="bx bx-check text-success ps-2 ${(row.q2_approve == 1)?'':'d-none'}"></i></td>
                    <td class="scoreDiv text-center fw-bold fs-5 bg-label-primary" id="major_3_${row.rep_id}" onclick="showInput(this,${row.q3_approve})">${row.q3_score}<i class="bx bx-check text-success ps-2 ${(row.q3_approve == 1)?'':'d-none'}"></i></td>
                    <td class="scoreDiv text-center fw-bold fs-5 bg-label-primary" id="major_4_${row.rep_id}" onclick="showInput(this,${row.q4_approve})">${row.q4_score}<i class="bx bx-check text-success ps-2 ${(row.q4_approve == 1)?'':'d-none'}"></i></td>
                    <td class="text-center fw-bold fs-5" id="major_sum_${row.rep_id}">${sumQuarter}</td>
                    <td class="text-center" id="detailBtn">
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm text-primary btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a href="javascript:;" class="dropdown-item" onclick="modalDetail(${row.rep_id})"><i class="bx bx-info-circle text-info pe-2"></i>รายละเอียด</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a href="javascript:;" class="dropdown-item" onclick="requestUpdateTarget(${row.rep_id}, ${row.target})"><i class="bx bx-edit text-danger pe-2"></i>ปรับปรุงค่าเป้าหมาย</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `;
                }

            } else {
                minorHtml += `
                <tr>
                    <td>
                    ${row.ind_title}
                    <div class="progress mb-4" style="height: 5px;">
                        <div class="progress-bar bg-warning" role="progressbar" id="minor_progress_${row.rep_id}" style="width: ${progression}%;" aria-valuenow="${progression}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    </td>
                    <td class="text-center fw-bold fs-5" id="minor_target_${row.rep_id}">${row.target ?? 0}</td>
                    <td class="scoreDiv text-center fw-bold fs-5 bg-label-warning" id="minor_1_${row.rep_id}" onclick="showInput(this,${row.q1_approve})">${row.q1_score}<i class="bx bx-check text-success ps-2 ${(row.q1_approve == 1)?'':'d-none'}"></i></td>
                    <td class="scoreDiv text-center fw-bold fs-5 bg-label-warning" id="minor_2_${row.rep_id}" onclick="showInput(this,${row.q2_approve})">${row.q2_score}<i class="bx bx-check text-success ps-2 ${(row.q2_approve == 1)?'':'d-none'}"></i></td>
                    <td class="scoreDiv text-center fw-bold fs-5 bg-label-warning" id="minor_3_${row.rep_id}" onclick="showInput(this,${row.q3_approve})">${row.q3_score}<i class="bx bx-check text-success ps-2 ${(row.q3_approve == 1)?'':'d-none'}"></i></td>
                    <td class="scoreDiv text-center fw-bold fs-5 bg-label-warning" id="minor_4_${row.rep_id}" onclick="showInput(this,${row.q4_approve})">${row.q4_score}<i class="bx bx-check text-success ps-2 ${(row.q4_approve == 1)?'':'d-none'}"></i></td>
                    <td class="text-center fw-bold fs-5" id="minor_sum_${row.rep_id}">${sumQuarter}</td>
                    <td class="text-center">
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm text-primary btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a href="javascript:;" class="dropdown-item" onclick="modalDetail(${row.rep_id})"><i class="bx bx-info-circle text-info pe-2"></i>รายละเอียด</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a href="javascript:;" class="dropdown-item" onclick="requestUpdateTarget(${row.rep_id}, ${row.target})"><i class="bx bx-edit text-danger pe-2"></i>ปรับปรุงค่าเป้าหมาย</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `;
            }

        });

        majorTableBody.innerHTML = majorHtml;
        minorTableBody.innerHTML = minorHtml;

    }

    function updateYear() {
        const yearSelect = document.getElementById('yearsSelect');
        const division = <?= User::division(); ?>;
        let year = yearSelect.value;

        fetchIndicatorsData(year, division)
            .then(data => {
                refreshTable(data);
                // if (data.length > 0) {
                //     refreshTable(data);
                // } else {
                //     refreshTable(data);
                // }
            })
            .catch(error => {
                console.error(error);
            });
    }

    function showInput(element, approve) {
        if (approve == 1) {
            return;
        }
        //prevent multiple click
        element.onclick = null;
        let input = document.createElement('input');
        input.classList.add('form-control', 'scoreForm', 'text-center', 'mx-auto', 'fw-bold', 'fs-5', 'fs-4');
        input.maxLength = 10;
        input.type = 'text';
        input.value = element.innerText;
        element.innerText = '';
        element.appendChild(input);
        input.focus();

        //check value on keyUp
        input.onkeyup = function(e) {
            if (!isInt(input.value)) {
                input.value = input.value.slice(0, -1);
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        }

        //if enter key is pressed
        input.onkeypress = function(e) {
            if (e.key === 'Enter') {
                input.blur();
            }
        }

        //if input is blurred
        input.onblur = function() {
            if (input.value === '') {
                input.value = 0;
            }
            element.innerText = input.value;
            updateScore(element.id, input.value);
            updateProgress(element);
            updateSum(element);
            element.onclick = function() {
                showInput(element);
            }

            // next element click
            // let nextElement = element.nextElementSibling;
            // if (nextElement) {
            //     // simulate click
            //     nextElement.click();
            //     nextElement.onclick = function() {
            //         showInput(nextElement);
            //     }
            // }
        }
    }

    function modalDetail(id) {
        reportDetailModal(id);
    }

    function isInt(value) {
        return !isNaN(value) && parseInt(Number(value)) == value && !isNaN(parseInt(value, 10));
    }

    async function updateScore(id, score) {
        const quarter = id.split('_')[1];
        const repId = id.split('_')[2];
        const url = `/indicators/updateIndicatorScore/`;
        let formData = new FormData();
        formData.append('repId', repId);
        formData.append('quarter', quarter);
        formData.append('score', score);

        await axios.post(url, formData).catch(error => {
            console.error(error);
        });
    }

    function statusBadge(status) {
        if (status == 1) {
            return `
                <button class="btn btn-sm btn-success btn-icon rounded-pill"><i class="bx bx-smile"></i></button><br>
                <p class="fs-6 text-success">รายงานสมบูรณ์</p>`;
        } else {
            return `<span class="badge bg-danger">ไม่สมบูรณ์</span>`;
        }
    }

    function updateProgress(element) {
        const classification = element.id.split('_')[0];
        const quarter = element.id.split('_')[1];
        const id = element.id.split('_')[2];

        let progressId = document.getElementById(`${classification}_progress_${id}`);
        let q1Score = document.getElementById(`${classification}_1_${id}`);
        let q2Score = document.getElementById(`${classification}_2_${id}`);
        let q3Score = document.getElementById(`${classification}_3_${id}`);
        let q4Score = document.getElementById(`${classification}_4_${id}`);
        let target = document.getElementById(`${classification}_target_${id}`).innerText;

        let progression = ((parseInt(q1Score.innerText) + parseInt(q2Score.innerText) + parseInt(q3Score.innerText) + parseInt(q4Score.innerText)) / parseInt(target)) * 100;
        progressId.style.width = `${progression}%`;
    }

    function updateSum(element) {
        const classification = element.id.split('_')[0];
        const id = element.id.split('_')[2];
        let q1Score = document.getElementById(`${classification}_1_${id}`);
        let q2Score = document.getElementById(`${classification}_2_${id}`);
        let q3Score = document.getElementById(`${classification}_3_${id}`);
        let q4Score = document.getElementById(`${classification}_4_${id}`);
        let sum = document.getElementById(`${classification}_sum_${id}`);

        sum.innerText = parseInt(q1Score.innerText) + parseInt(q2Score.innerText) + parseInt(q3Score.innerText) + parseInt(q4Score.innerText);
    }

    function refreshTable(data) {
        //clear table
        let majorTableBody = document.getElementById('majorTableBody');
        let minorTableBody = document.getElementById('minorTableBody');
        majorTableBody.innerHTML = '';
        minorTableBody.innerHTML = '';
        appendToTable(data);
    }

    function requestUpdateTarget(id, target) {
        $('#requestUpdateTargetModal').modal('show');
        document.getElementById('currentTarget').innerText = target;
        const requestUpdateTargetSubmit = document.getElementById('requestUpdateTargetSubmit');
        requestUpdateTargetSubmit.onclick = function() {
            let newTargetInput = document.getElementById('newTargetInput')
            let newTarget = newTargetInput.value;
            if (newTarget == '') {
                swal.fire({
                    icon: 'error',
                    title: 'ผิดพลาด',
                    text: 'กรุณากรอกค่าเป้าหมายใหม่',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    newTargetInput.focus();
                });
                return;
            }
            doRequestNewTarget(id, newTarget);
        }
    }

    async function doRequestNewTarget(id, newTarget) {
        const url = `/indicators/requestUpdateTarget/${id}/${newTarget}`;
        axios.get(url)
            .then(res => {
                swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: 'ส่งคำขอเปลี่ยนแปลงค่าเป้าหมายเรียบร้อย',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                })
            })
            .catch(error => {
                console.error(error);
            });
    }

    function assignProject(id) {
        createAssignProjectModal();
        $('#assignProjectModal').modal('show');
    }

    function createAssignProjectModal() {
        let modalHtml = `
        <div class="modal fade" id="assignProjectModal" tabindex="-1" aria-hidden="false">
            <div class="modal-dialog modal-lg modal-simple">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <p class="mb-0 fs-4">กำหนดโครงการ</p>
                                <small class="text-muted fs-6">* โปรดเลือกโครงการที่ต้องการกำหนด</small>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="projectSelect" class="form-label fs-5 fw-bold">เลือกโครงการ</label>
                                    <select class="form-select" id="projectSelect" name="projectSelect">
                                        <option value="1">โครงการ 1</option>
                                        <option value="2">โครงการ 2</option>
                                        <option value="3">โครงการ 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-md-row justify-content-center mt-0 mt-md-4">
                                <div class="p-2 col-12 col-md-3">
                                    <button class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">ยกเลิก</button>
                                </div>
                                <div class="p-2 col-12 col-md-3">
                                    <button class="btn btn-primary w-100" id="assignProjectSubmit"><i class="bx bx-save pe-2"></i>บันทึก</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;

        let modal = document.createElement('div');
        modal.innerHTML = modalHtml;
        document.body.appendChild(modal);
    }
</script>