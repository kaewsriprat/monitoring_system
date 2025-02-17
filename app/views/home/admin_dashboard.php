<?php
$years = BudgetYear::getBudgetYearList();
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        / ภาพรวมโครงการ
    </h5>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <label for="yearsSelect" class="form-label fw-bold fs-5">รายงานปีงบประมาณ</label>
            <select class="form-select" id="yearsSelect" name="yearsSelect" onchange="updateData()">
                <?php foreach ($years as $year) : ?>
                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php endforeach; ?>
            </select>
        </div>



        <div class="col-12 col-md-4">
            <label for="divSelect" class="form-label fw-bold fs-5">หน่งวยงาน</label>
            <select class="form-select" id="divSelect" name="divSelect" onchange="updateData()">
                <option value="0" selected>ทุกหน่วยงาน</option>
                <?php foreach ($divisions as $division) : ?>
                    <option value="<?php echo $division['division_id']; ?>"><?php echo $division['division_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>


    </div>

    <div class="row g-3">
        <div class="col-12 col-md-5">

            <div class="row g-3">

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header py-2 h4">
                            จำนวนโครงการทั้งหมด
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                <p class="text-dark p-0"><span class="fw-bold fs-1" id="projectCountCard">0</span><span> โครงการ</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header py-2 h4">
                            โครงการที่เป็นตัวชี้วัด
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                <p class="text-dark p-0"><span class="fw-bold fs-1" id="projectIndCountCard">0</span><span> โครงการ</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header py-2 h4">
                            การรายงาน
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-end">

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    let selectedYear = document.getElementById('yearsSelect').value;
    let selectedDivision = document.getElementById('divSelect').value;
    let countProject = 0;

    document.addEventListener('DOMContentLoaded', async () => {
        renderData();
    });

    // api

    class Api {
        constructor() {
            this.url = '/dashboardapi';
        }

        async getProjects(year, division) {
            const url = `${this.url}/getProjects/${year}/${division}`;
            const response = await this.axiosGet(url);
            if (response === undefined) {
                console.error('Failed to fetch projects');
                return [];
            }
            return response;
        }

        async getReportedProjects(year, division = null) {
            const url = `${this.url}/getReportedProjects/${year}/${division}`;
            const response = await this.axiosGet(url);
            if (response === undefined) {
                console.error('Failed to fetch reported projects');
                return [];
            }
            return response;
        }

        async axiosGet(url) {
            try {
                const response = await axios.get(url);
                return response.data;
            } catch (error) {
                console.error(error);
                throw error;
            }
            const response = await axios.get(url);
            return response.data;
        }
    }

    // charts
    class Apexchart {
        constructor() {
            this.chart = null;
        }

        chartRender(ele) {

        }
    }

    // listener
    function updateData() {
        selectedYear = document.getElementById('yearsSelect').value;
        selectedDivision = document.getElementById('divSelect').value;
        console.log(selectedDivision)
        renderData();
        console.log(quarterReports);

    }

    // append
    async function renderData() {
        const api = new Api();
        countProject = await api.getProjects(selectedYear, selectedDivision);
        quarterReports = await api.getReportedProjects(selectedYear, selectedDivision);

        // document.getElementById('projectCountCard').innerText = countProject.countProject;
        // document.getElementById('projectIndCountCard').innerText = countProject.countProjectInd;

        console.log(countProject);
        console.log(quarterReports);
    }
</script>