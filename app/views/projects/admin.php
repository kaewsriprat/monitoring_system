<style>
    .id-col {
        width: 1%;
    }
    .projectName-col {
        width: 30%;
    }
    .switch-col {
        width: 5%;
    }
</style>
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
    ?>

    <div class="card mb-6">
        <div class="card-widget-separator-wrapper">
            <div class="card-header bg-label-secondary py-2 h4">
                โครงการที่รายงานแล้ว
            </div>
            <div class="card-body card-widget-separator pt-3">
                <!-- SPINNER -->
                <div class="d-flex justify-content-center my-5" id="statusCardSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="row gy-4 gy-sm-1 d-none" id="statusCard">
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                            <div>
                                <p class="mb-1 fs-4 text-primary">ไตรมาสที่ 1</p>
                                <h4 class="mb-1"><span id="q1_status"></span>/<span class="fs-5 text-muted projectsTotal"></span></h4>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                            <div>
                                <p class="mb-1 fs-4 text-success">ไตรมาสที่ 2</p>
                                <h4 class="mb-1"><span id="q2_status"></span>/<span class="fs-5 text-muted projectsTotal"></span></h4>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                            <div>
                                <p class="mb-1 fs-4 text-warning">ไตรมาสที่ 3</p>
                                <h4 class="mb-1"><span id="q3_status"></span>/<span class="fs-5 text-muted projectsTotal"></span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1 fs-4 text-danger">ไตรมาสที่ 4</p>
                                <h4 class="mb-1"><span id="q4_status"></span>/<span class="fs-5 text-muted projectsTotal"></span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-primary py-2 h4">
                    โครงการทั้งหมดประจำปี <span id="yearTitle"></span>
                </div>
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-12 col-md-2">
                            <label for="yearsSelect" class="form-label">ปีงบประมาณ</label>
                            <select class="form-select" id="yearsSelect" name="yearsSelect" onchange="filter()">
                                <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                                    <option value="<?= $year ?>"><?= $year ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="divisionsSelect" class="form-label">หน่วยงาน</label>
                            <select class="form-select" id="divisionsSelect" name="divisionsSelect" onchange="filter()">
                                <option value="0" selected>ทั้งหมด</option>
                                <?php foreach ($data['divisions'] as $division) : ?>
                                    <option value="<?= $division['division_id'] ?>"><?= $division['division_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="strategiesSelect" class="form-label">ยุทธศาสตร์</label>
                            <select class="form-select" id="strategiesSelect" name="strategiesSelect" onchange="filter()">
                                <option value="0" selected>ทั้งหมด</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SPINNER -->
                <div class="d-flex justify-content-center my-5" id="tableSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="card-datatable table-responsive text-wrap d-none" id="projectTableWrapper">
                    <table class="datatables-ajax table " id="projectsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ชื่อโครงการ</th>
                                <th>หน่วยงาน</th>
                                <th>Q1</th>
                                <th>Q2</th>
                                <th>Q3</th>
                                <th>Q4</th>
                                <th>นอก/ในแผน</th>
                                <th>รายละเอียด</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

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
                            <span id="projectName">projectName</span>
                        </div>
                        <div class="col-12 fs-5 mb-2 col-md-6">
                            <span class="text-muted my-1">งบประมาณ: </span>
                            <span id="total_budget">Budget</span>
                        </div>
                        <div class="col-12 fs-5 mb-2 col-md-6">
                            <span class="text-muted my-1">ปีงบประมาณ: </span>
                            <span id="projectYear">ProjectYear</span>
                        </div>
                        <div class="col-12 fs-5 mb-2">
                            <span class="text-muted my-1">หน่วยงาน: </span>
                            <span id="division_name">division_name</span>
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

</div>

<script>
    const yearSelect = document.getElementById('yearsSelect');
    const divisionSelect = document.getElementById('divisionsSelect');
    const strategiesSelect = document.getElementById('strategiesSelect');

    let yearValue = yearSelect.value;
    let divisionValue = divisionSelect.value;
    let strategyValue = strategiesSelect.value;
    let projectData = [];
    let dataTable = null;

    document.addEventListener('DOMContentLoaded', async function() {
        setYearTitle();
        updateProjectsStatus();
        updateTable();
        appendStrategyOptions();
    });

    function initDatatable(data) {
        if (dataTable) {
            dataTable.destroy();
        }

        dataTable = $('#projectsTable').DataTable({
            data: data,
            columns: [{
                    data: "project_ID"
                },
                {
                    data: "project_name"
                },
                {
                    data: "division_abbr"
                },
                {
                    data: "q1_file_path",
                    render: function(data, type, row) {
                        return badgeReported(data);
                    }
                },
                {
                    data: "q2_file_path",
                    render: function(data, type, row) {
                        return badgeReported(data);
                    }
                },
                {
                    data: "q3_file_path",
                    render: function(data, type, row) {
                        return badgeReported(data);
                    }
                },
                {
                    data: "q4_file_path",
                    render: function(data, type, row) {
                        return badgeReported(data);
                    }
                },
                {
                    data: "planned",
                    render: function(data, type, row) {
                        return switchPlanned(data);
                    }
                },
                {
                    data: "project_ID",
                    render: function(data, type, row) {
                        return `<button  class="btn btn-icon btn-sm" onclick="projectDetailModal(${data})"><i class="bx bx-info-circle"></i></button>`;
                    }
                }
            ],
            columnDefs: [
                {
                    targets: [0, 2, 3, 4, 5, 6, 7, -1],
                    className: 'text-center'
                },
                {
                    targets: [0],
                    className: 'id-col'
                },
                {
                    targets: [1],
                    className: 'projectName-col'
                },
                {
                    targets: [7],
                    className: 'switch-col'
                }
            ],
        })

        dataTable.draw();

        showTableSpinner(false);
        showTable(true);
    }

    async function updateProjectsStatus() {
        const data = await getProjectStatus();
        document.getElementById('q1_status').innerText = data.quarter_1;
        document.getElementById('q2_status').innerText = data.quarter_2;
        document.getElementById('q3_status').innerText = data.quarter_3;
        document.getElementById('q4_status').innerText = data.quarter_4;
        document.querySelectorAll('.projectsTotal').forEach((ele) => {
            ele.innerText = data.total;
        });

        showStatusCardSpinner(false);
        showStatusCard(true);
    }

    /// API ///
    async function getProjects() {
        const url = `/projects/getProjectsTableData/${yearValue}/${divisionValue}/${strategyValue}`;
        let res = await axios.get(url).then((res) => {
            return res.data
        });
        return res;
    }

    async function getProjectDetail(id) {
        const url = '/projects/getProjectById/' + id;
        const res = await axios.get(url)
        return res.data;
    }

    async function doUpdateProjectPlannedStatus(id, status) {
        const url = '/projects/updateProjectPlannedStatus';
        let formData = new FormData();
        formData.append('id', id);
        formData.append('status', status);

        const res = await axios.post(url, formData);
    }

    async function getProjectStatus() {
        const url = `/projects/getProjectStatus/${yearValue}/${divisionValue}`;
        let res = await axios.get(url);
        return res.data;
    }

    async function getStrategies() {
        const yearSelect = document.getElementById('yearsSelect');
        let year = yearSelect.value;
        const url = `/strategies/getStrategies/${year}`;
        let res = await axios.get(url);
        return res.data;

    }
    /// API ///

    /// EVENT ///
    function filter() {
        yearValue = yearSelect.value;
        divisionValue = divisionSelect.value;
        strategyValue = document.getElementById('strategiesSelect').value;
        showTableSpinner(true);
        showTable(false);
        showStatusCardSpinner(true);
        showStatusCard(false);
        setYearTitle();
        updateTable();
        updateProjectsStatus();
        appendStrategyOptions();
    }

    async function updateTable() {
        const data = await getProjects();
        initDatatable(data);
    }

    function updateProjectPlannedStatus(ele) {
        const label = ele.nextElementSibling.nextElementSibling;
        const id = ele.closest('tr').getElementsByTagName('td')[0].innerText;
        const status = ele.checked;

        (status) ? label.innerText = 'ใน': label.innerText = 'นอก';

        doUpdateProjectPlannedStatus(id, status);
    }

    async function appendStrategyOptions() {
        const data = await getStrategies();
        const strategiesSelect = document.getElementById('strategiesSelect');
        strategiesSelect.innerHTML = '<option value="0" selected>ทั้งหมด</option>';
        data.forEach(strategy => {
            const option = document.createElement('option');
            option.value = strategy.id;
            option.innerText = strategy.strategy_name;
            if(strategy.id == strategyValue) {
                option.selected = true;
            }
            strategiesSelect.appendChild(option);
        });

    }   

    /// EVENT ///

    /// UTIL ///
    function badgeReported(status) {
        if (status === null || status === '') {
            return `<a href="javascript:void(0)" class=""><i class="bx bx-x text-secondary fs-4"></i></a>`;
        } else {
            return `<a href="/${status}" class=""><i class="bx bx-download fs-4 text-primary"></i></a>`;
        }
    }

    function switchPlanned(status) {
        const planText = (status == 1) ? 'ใน&nbsp;&nbsp;&nbsp;': 'นอก';
        let html = `
        <div class="d-flex justify-content-center">
            <label class="switch">
                <input type="checkbox" class="switch-input" ${(status == 1) ? 'checked': ''} onchange="updateProjectPlannedStatus(this)"/>
                    <span class="switch-toggle-slider">
                        <span class="switch-on">
                        <i class="bx bx-check"></i>
                        </span>
                        <span class="switch-off">
                        <i class="bx bx-x"></i>
                        </span>
                    </span>
                    <span class="switch-label">${planText}</span>
            </label>
        </div>
        `;

        return html;
    }

    function setYearTitle() {
        const yearTitle = document.getElementById('yearTitle');
        yearTitle.innerText = '';
        yearTitle.innerText = yearSelect.value;
    }

    function showTableSpinner(show) {
        const spinner = document.getElementById('tableSpinner');
        if (show) {
            if (spinner.classList.contains('d-none')) {
                spinner.classList.remove('d-none');
            }
        } else {
            spinner.classList.add('d-none');
        }
    }

    function showTable(show) {
        const tableWrapper = document.getElementById('projectTableWrapper');
        if (show) {
            if (tableWrapper.classList.contains('d-none')) {
                tableWrapper.classList.remove('d-none');
            }
        } else {
            tableWrapper.classList.add('d-none');
        }
    }

    function showStatusCardSpinner(show) {
        const spinner = document.getElementById('statusCardSpinner');
        if (show) {
            if (spinner.classList.contains('d-none')) {
                spinner.classList.remove('d-none');
            }
        } else {
            spinner.classList.add('d-none');
        }
    }

    function showStatusCard(show) {
        const card = document.getElementById('statusCard');
        if (show) {
            if (card.classList.contains('d-none')) {
                card.classList.remove('d-none');
            }
        } else {
            card.classList.add('d-none');
        }
    }

    function thaiDateFormate(date) {
        // 2024-11-12 09:24:35 => 12 พฤศจิกายน 2567
        const months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        const dateArr = date.split(' ')[0].split('-');
        const day = dateArr[2];
        const month = months[parseInt(dateArr[1]) - 1];
        const year = parseInt(dateArr[0]) + 543;
        return `${day} ${month} ${year}`;
    }
    /// UTIL ///

    /// MODAL ///
    async function projectDetailModal(id) {
        const projectData = await getProjectDetail(id);
        $('#projectDetailModal').modal('show');
        appendDataToModal(projectData);
    }

    function appendDataToModal(data) {
        const projectName = document.getElementById('projectName');
        const totalBudget = document.getElementById('total_budget');
        const projectYear = document.getElementById('projectYear');
        const divisionName = document.getElementById('division_name');
        const reportedTable = document.getElementById('reportedTable').getElementsByTagName('tbody')[0];

        projectName.innerText = data.project_name;
        totalBudget.innerText = (data.total_budget == '') ? 0 : parseInt(data.total_budget).toLocaleString();
        projectYear.innerText = data.project_year;
        divisionName.innerText = data.division_abbr;
        reportedTable.innerHTML = '';

        for (let i = 1; i <= 4; i++) {
            const quarter = `quarter_${i}`;
            const reported = `q${i}_file_path`;
            const reportedDate = `q${i}_file_update_date`;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>ไตรมาสที่ ${i}</td>
                <td class="text-center">${data[reported] ? '<i class="bx bx-check text-success"></i>' : '<i class="bx bx-x text-danger"></i>'}</td>
                <td>${data[reportedDate] ? data[reportedDate] : 'ยังไม่ได้รายงาน'}</td>
            `;
            reportedTable.appendChild(tr);
        }
    }
    /// MODAL ///
</script>