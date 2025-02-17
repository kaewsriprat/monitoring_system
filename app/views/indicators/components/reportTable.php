<style>
    .text_truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ind-accordian-button:hover {
        background-color: #e9ecef;
    }
</style>

<div class="row g-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="text-dark">รายงานผลการดำเนินงาน</hjson>
        </div>
    </div>
    <div class="col-12">
        <div id="indicatorsCardWrapper"></div>
    </div>
</div>

<script>
    const division = <?= User::division(); ?>;
    let yearSelect = document.getElementById('yearsSelect');
    let year = yearSelect.value;
    let indicatorsData = [];
    let projectsList = [];
    const indicatorsCardWrapper = document.getElementById('indicatorsCardWrapper');

    document.addEventListener('DOMContentLoaded', async function() {
        indicatorsData = await fetchIndicatorsData(year, division);
        projectsList = await fetchProjectsData(year, division);

        indicatorsCardRender(indicatorsData);
    });

    function expandAll() {
        toggleExpandingBtn()
        let accordions = document.querySelectorAll('.accordion-collapse');
        accordions.forEach(acc => {
            let id = acc.id;
            let accordion = new bootstrap.Collapse(document.getElementById(id), {
                toggle: false
            });
            accordion.show();
        });
    }

    function collapseAll() {
        toggleExpandingBtn()
        let accordions = document.querySelectorAll('.accordion-collapse');
        accordions.forEach(acc => {
            let id = acc.id;
            let accordion = new bootstrap.Collapse(document.getElementById(id), {
                toggle: false
            });
            accordion.hide();
        });
    }

    function toggleExpandingBtn() {
        let expandingBtn = document.getElementById('expandingBtn');
        if (expandingBtn.textContent.includes('ขยาย')) {
            expandingBtn.onclick = collapseAll;
            expandingBtn.innerHTML = `<i class="bx bx-chevron-up pe-2"></i>ยุบทั้งหมด`;
        } else {
            expandingBtn.onclick = expandAll;
            expandingBtn.innerHTML = `<i class="bx bx-chevron-down pe-2"></i>ขยายทั้งหมด`;
        }
    }

    function indicatorsCardRender(data) {
        let html = '';
        data = transformData(data);
        data.forEach(indicator => {
            html += indicatorsCard(indicator);
        });
        indicatorsCardWrapper.innerHTML = html;
    }

    function indicatorsCard(indData) {
        // console.log(indData);
        let projects = [];
        let projectsCount = 0;
        indData.reports.forEach(report => {
            // if rep_id is null, remove the project from the list
            if (report.rep_id === null) {
                return;
            }

            projects.push(projectsCard(report, indData.classification));
            projectsCount++;
        });
        return `<div class="accordion mb-3" id="indicatorCard_${indData.ind_id}">
                    <div class="accordion-item card">
                        <div class="accordion-header text-body d-flex justify-content-between" id="indicatorCard">
                            <button type="button" class="accordion-button ind-accordian-button collapsed" data-bs-toggle="collapse" data-bs-target="#indicatorCard-${indData.ind_id}" aria-controls="indicatorCard-${indData.ind_id}">
                                <span class="fw-bold fs-4 text-truncate" title="${indData.ind_title}">${indData.ind_title}</span>
                                ${classificationBadge(indData.classification)} ${(projectsCount > 0) ? `<span class="badge bg-label-secondary mx-1">${projectsCount} โครงการ</span>` : `<span class="badge bg-label-danger ms-3">ไม่มีโครงการ</span>`}
                            </button>
                        </div>
                       
                        <div id="indicatorCard-${indData.ind_id}" class="accordion-collapse collapse" data-bs-parent="#indicatorCard_${indData.ind_id}">                   
                            <div class="accordion-body">
                                ${(projects.length > 0) ? projects.join('') : ''}
                                <hr>
                                ${addProject(indData.ind_id)}
                            </div>
                        </div>
                    </div>
                </div>`;
    }

    function projectsCard(projData, classification) {
        return `<div id="projectCard${projData.rep_id}" class="accordion accordion-without-arrow my-3">
                    <div class="accordion-item card">
                        <h2 class="accordion-header text-body d-flex justify-content-between" id="projectCardOne">
                            <button type="button" class="accordion-button btn-label-${(classification === 'major') ? 'primary' : 'warning'} collapsed" data-bs-toggle="collapse" data-bs-target="#projectCard-${projData.rep_id}" aria-controls="projectCard-${projData.rep_id}">
                                <span class="fw-bold text-truncate">${projData.project_name}</span>
                            </button>
                        </h2>
                        <div id="projectCard-${projData.rep_id}" class="accordion-collapse collapse" data-bs-parent="#projectCard${projData.rep_id}">
                            <div class="accordion-body pt-3">
                                <div class="row g-3">
                                    <div class="col-3">
                                        <p class="fw-bold mb-0">ค่าเป้าหมาย</p>
                                        <span class="fw-bold fs-5" id="target-${projData.rep_id}">${projData.target}</span>
                                    </div>
                                    <div class="col-12">
                                        <p class="fw-bold mb-0">คำอธิบายค่าเป้าหมาย</p>
                                        <span class="text_truncate" id="targetDetail-${projData.rep_id}">
                                            ${(projData.target_detail == '') ? '--- ไม่มีคำอธิบาย ---' : projData.target_detail}
                                        </span>
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <p class="fw-bold mb-0">คะแนนรายไตรมาส</p>
                                        <div class="row">
                                            <div class="col-6 col-md-3">
                                                <label for="quarter1-${projData.rep_id}" class="form-label">Q1</label>
                                                <p class="fw-bold fs-5" id="quarter1-${projData.rep_id}">${projData.q1_score}</p>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <label for="quarter2-${projData.rep_id}" class="form-label">Q2</label>
                                                <p class="fw-bold fs-5" id="quarter2-${projData.rep_id}">${projData.q2_score}</p>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <label for="quarter3-${projData.rep_id}" class="form-label">Q3</label>
                                                <p class="fw-bold fs-5" id="quarter3-${projData.rep_id}">${projData.q3_score}</p>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <label for="quarter4-${projData.rep_id}" class="form-label">Q4</label>
                                                <p class="fw-bold fs-5" id="quarter4-${projData.rep_id}">${projData.q4_score}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end align-items-center">
                                            <button type="button" class="btn btn-label-success d-none" onclick="saveButton(this, ${projData.rep_id})"><i class="bx bx-save pe-2"></i>บันทึก</button>
                                            <button type="button" class="btn btn-label-secondary mx-2" onclick="editMode(this, ${projData.rep_id})"><i class="bx bx-pencil pe-2"></i>แก้ไข</button>
                                            <button type="button" class="btn btn-label-danger" onclick="deleteButton(this, ${projData.rep_id})"><i class="bx bx-trash pe-2"></i>ลบ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    `;
    }

    function addProject(indId) {
        let html = `
                <div class="p-3 border rounded bg-label-secondary">               
                    <div class="row g-3">
                        <div class="col-12 text-start">
                            <p class="text-dark fw-bold fs-6 mb-0">เพิ่มโครงการ</p>
                        </div>
                        <div class="col-12 mt-0">
                            <label class="form-label fw-bold text-dark" for="projectName">ชื่อโครงการ</label>
                            <select class="form-select" name="projectName">
                                ${
                                    projectsList.map(project => {
                                        return `<option class="text-truncate" value="${project.project_ID}">${project.project_name}</option>`;
                                    }).join('')
                                }
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold" for="projectTargetScore">ค่าเป้าหมาย</label>
                            <input type="number" class="form-control" id="projectTargetScore" name="projectTargetScore" value="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold" for="projectTargetScoreDetail">คำอธิบายค่าเป้าหมาย</label>
                            <input type="text" class="form-control" id="projectTargetScoreDetail" name="projectTargetScoreDetail">
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-primary w-100" onclick="assignProject(${indId}, this)">เพิ่มโครงการ</button>
                        </div>
                    </div>
                </div>
        `;

        return html;
    }

    function assignProject(indId, ele) {
        let projectSelect = ele.closest('.p-3').querySelector('select');
        let projectId = parseInt(projectSelect.options[projectSelect.selectedIndex].value);
        let projectTargetScore = parseInt(ele.closest('.p-3').querySelector('input[name="projectTargetScore"]').value);
        let projectTargetScoreDetail = ele.closest('.p-3').querySelector('input[name="projectTargetScoreDetail"]').value;
        let formData = new FormData();
        formData.append('indId', indId);
        formData.append('projectId', projectId);
        formData.append('division', division);
        formData.append('target', projectTargetScore);
        formData.append('targetDetail', projectTargetScoreDetail);

        // confirm swal
        swal.fire({
            title: 'ยืนยันการเพิ่มโครงการ',
            text: 'คุณต้องการเพิ่มโครงการนี้ใช่หรือไม่?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่ใช่'
        }).then(async (result) => {
            if (result.isConfirmed) {
                let response = await axios.post(`/indicators/assignProjectToReport`, formData);
                if (response) {
                    swal.fire({
                        icon: 'success',
                        title: 'เพิ่มโครงการสำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    })
                }
            }
        });


    }

    async function fetchIndicatorsData(year, division) {
        const url = `/indicators/getIndicatorsByDivision2/${year}/${division}`;
        try {
            const response = await axios.get(url);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response.data;
        }
    }

    async function fetchProjectsData(year, division) {
        const url = `/projects/getProjectsByDivision/${year}/${division}`;
        try {
            const response = await axios.get(url);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response.data;
        }
    }

    function unassignProjects(data) {
        return data.filter(item => item.project_name === null).length;
    }

    async function updateYear() {
        let year = yearSelect.value;
        indicatorsData = await fetchIndicatorsData(year, division);
        projectsList = await fetchProjectsData(year, division);
        indicatorsCardRender(indicatorsData);
    }

    function classificationBadge(classification) {
        if (classification === "major") {
            return `<span class="badge bg-label-primary ms-2 mx-1">เป้าหมายหลัก</span>`;
        } else {
            return `<span class="badge bg-label-warning ms-2 mx-1">เป้าหมายรอง</span>`;
        }
    }

    function transformData(data) {
        let result = [];
        let indMap = new Map();
        data.forEach(item => {
            let {
                ind_id,
                ind_title,
                goal_id,
                goal_title,
                classification,
                rep_id,
                project_name,
                division_name,
                target,
                target_detail,
                q1_score,
                q1_approve,
                q2_score,
                q2_approve,
                q3_score,
                q3_approve,
                q4_score,
                q4_approve,
                total,
                percentile
            } = item;

            if (!indMap.has(ind_id)) {
                indMap.set(ind_id, {
                    ind_id,
                    ind_title,
                    goal_id,
                    goal_title,
                    classification,
                    reports: []
                });
            }

            indMap.get(ind_id).reports.push({
                rep_id,
                project_name: project_name || "",
                division_name,
                target,
                target_detail,
                q1_score,
                q1_approve,
                q2_score,
                q2_approve,
                q3_score,
                q3_approve,
                q4_score,
                q4_approve,
                total,
                percentile: percentile || ""
            });
        });

        return Array.from(indMap.values());
    }

    function editMode(ele, id) {
        ele.previousElementSibling.classList.remove('d-none');
        cancelMode(ele, id);
        let projectCard = document.getElementById(`projectCard-${id}`);
        let target = projectCard.querySelector(`#target-${id}`);
        let targetDetail = projectCard.querySelector(`#targetDetail-${id}`);
        let quarter1 = projectCard.querySelector(`#quarter1-${id}`);
        let quarter2 = projectCard.querySelector(`#quarter2-${id}`);
        let quarter3 = projectCard.querySelector(`#quarter3-${id}`);
        let quarter4 = projectCard.querySelector(`#quarter4-${id}`);

        let targetValue = target.textContent.trim();
        let targetDetailValue = targetDetail.textContent.trim();
        let quarter1Value = quarter1.textContent.trim();
        let quarter2Value = quarter2.textContent.trim();
        let quarter3Value = quarter3.textContent.trim();
        let quarter4Value = quarter4.textContent.trim();

        target.innerHTML = `<input type="number" class="form-control" value="${targetValue}" id="target-${id}">`;
        targetDetail.innerHTML = `<input type="text" class="form-control" value="${targetDetailValue}" id="${id}">`;
        quarter1.innerHTML = `<input type="number" class="form-control" value="${quarter1Value}" id="quarter1-${id}">`;
        quarter2.innerHTML = `<input type="number" class="form-control" value="${quarter2Value}" id="quarter2-${id}">`;
        quarter3.innerHTML = `<input type="number" class="form-control" value="${quarter3Value}" id="quarter3-${id}">`;
        quarter4.innerHTML = `<input type="number" class="form-control" value="${quarter4Value}" id="quarter4-${id}">`;
    }

    function cancelMode(ele, id) {
        ele.innerHTML = `<i class="bx bx-x pe-2"></i>ยกเลิก`;
        ele.setAttribute('onclick', `cancelEdit(this, ${id})`);
    }

    function cancelEdit(ele, id) {
        ele.previousElementSibling.classList.add('d-none');
        ele.innerHTML = `<i class="bx bx-pencil pe-2"></i>แก้ไข`;
        ele.setAttribute('onclick', `editMode(this, ${id})`);
        let projectCard = document.getElementById(`projectCard-${id}`);
        let target = projectCard.querySelector(`#target-${id}`);
        let targetDetail = projectCard.querySelector(`#targetDetail-${id}`);
        let quarter1 = projectCard.querySelector(`#quarter1-${id}`);
        let quarter2 = projectCard.querySelector(`#quarter2-${id}`);
        let quarter3 = projectCard.querySelector(`#quarter3-${id}`);
        let quarter4 = projectCard.querySelector(`#quarter4-${id}`);

        let targetValue = target.querySelector('input').value;
        let targetDetailValue = targetDetail.querySelector('input').value;
        let quarter1Value = quarter1.querySelector('input').value;
        let quarter2Value = quarter2.querySelector('input').value;
        let quarter3Value = quarter3.querySelector('input').value;
        let quarter4Value = quarter4.querySelector('input').value;

        target.innerHTML = targetValue;
        targetDetail.innerHTML = targetDetailValue;
        quarter1.innerHTML = quarter1Value;
        quarter2.innerHTML = quarter2Value;
        quarter3.innerHTML = quarter3Value;
        quarter4.innerHTML = quarter4Value;
    }

    function saveButton(ele, id) {
        let projectCard = document.getElementById(`projectCard-${id}`);
        let target = projectCard.querySelector(`#target-${id}`);
        let targetDetail = projectCard.querySelector(`#targetDetail-${id}`);
        let quarter1 = projectCard.querySelector(`#quarter1-${id}`);
        let quarter2 = projectCard.querySelector(`#quarter2-${id}`);
        let quarter3 = projectCard.querySelector(`#quarter3-${id}`);
        let quarter4 = projectCard.querySelector(`#quarter4-${id}`);

        let targetValue = target.querySelector('input').value;
        let targetDetailValue = targetDetail.querySelector('input').value;
        let quarter1Value = quarter1.querySelector('input').value;
        let quarter2Value = quarter2.querySelector('input').value;
        let quarter3Value = quarter3.querySelector('input').value;
        let quarter4Value = quarter4.querySelector('input').value;

        let formData = new FormData();
        formData.append('repId', id);
        formData.append('target', targetValue);
        formData.append('targetDetail', targetDetailValue);
        formData.append('q1', quarter1Value);
        formData.append('q2', quarter2Value);
        formData.append('q3', quarter3Value);
        formData.append('q4', quarter4Value);

        swal.fire({
            title: 'ยืนยันการบันทึก',
            text: 'คุณต้องการบันทึกข้อมูลนี้ใช่หรือไม่?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่ใช่'
        }).then(async (result) => {
            if (result.isConfirmed) {
                let res = await axios.post(`/indicators/updateReport`, formData);
                if (res) {
                    swal.fire({
                        icon: 'success',
                        title: 'บันทึกข้อมูลสำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    })
                }
            }
        });
    }

    function deleteButton(ele, repId) {
        swal.fire({
            title: 'ยืนยันการลบ',
            text: `คุณต้องการลบข้อมูลหมายเลข ${repId} ใช่หรือไม่?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่ใช่'
        }).then(async (result) => {
            if (result.isConfirmed) {
                let res = await axios.delete(`/indicators/deleteUserReport/${repId}`)
                if (res) {
                    swal.fire({
                        icon: 'success',
                        title: 'ลบข้อมูลสำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    })
                }
            }
        })
    }
</script>