<style>
    .apexcharts-text {
        font-family: 'Athiti', sans-serif !important;
    }
</style>

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
        / ภาพรวมการรายงาน
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

    </div>
    <hr>
    <div class="row g-3">
        <div class="col-12">
            <p class="h2" id="dashboardTitle">ภาพรวมการรายงานประจำปี 2568</p>
        </div>
  
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="card" style="min-height: 300px !important;">
                        <div class="card-header py-2 h4">
                            สรุปภาพรวมโครงการ
                        </div>
                        <div class="card-body pb-3 px-3">
                            <div class="row g-3 pt-3">
                                <div class="col-12 col-md-6">
                                    <div class="card bg-label-primary py-2 px-3" style="min-height: 100px;">
                                        <p class="text-dark mb-0">โครงการทั้งหมด</p>
                                        <p class="text-end mb-0 fs-3 pt-3" ><span id="projectCountCard">0</span> โครงการ</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card bg-label-info py-2 px-3" style="min-height: 100px;">
                                        <p class="text-dark mb-0">งบประมาณรวม (ล้านบาท)</p>
                                        <p class="text-end mb-0 fs-3 pt-3">฿<span id="projectBudget">0</span></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card bg-label-secondary py-2 px-3" style="min-height: 100px;">
                                        <span class="text-dark mb-0">เป็นโครงการในแผน</span>
                                        <p class="text-end mb-0 fs-3 pt-3"><span id="projectPlannedCountCard">0</span> โครงการ</p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card bg-label-success py-2 px-3" style="min-height: 100px;">
                                        <span class="text-dark mb-0">รายงานครบทุกไตรมาสแล้ว</span>
                                        <p class="text-end mb-0 fs-3 pt-3" ><span id="projectfinishReported">0</span> โครงการ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card" style="min-height: 300px !important;">
                        <div class="card-header py-2 h4">
                            การรายงานรายไตรมาส
                        </div>
                        <div class="card-body pb-0 px-1">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <div class="d-flex justify-content-center" id="reportProgressionChart"></div>
                                </div>
                                <div class="col-12 col-md-7">
                                    <div class="d-flex justify-content-center" id="reportQuarterlyChart"></div>
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
    let selectedDivision = <?= json_encode(User::division()); ?>;
    let countProject = 0;

    document.addEventListener('DOMContentLoaded', async () => {
        udpateTitle();
        renderData();
    });

    // api
    class Api {
        constructor() {
            this.url = '/dashboardapi';
        }

        async getProjects(year, division) {
            const URL = `${this.url}/getProjects/${year}/${division}`;
            const RES = await this.get(URL);
            if (RES === undefined) {
                console.error('Failed to fetch projects');
                return [];
            }
            return RES;
        }

        async getReportedProjects(year, division = null) {
            const URL = `${this.url}/getReportedProjects/${year}/${division}`;
            const RES = await this.get(URL);
            if (RES === undefined) {
                console.error('Failed to fetch reported projects');
                return [];
            }
            return RES;
        }

        async get(url) {
            try {
                const RES = await axios.get(url);
                return RES.data;
            } catch (error) {
                console.error(error);
                throw error;
            }
        }
    }

    // listener
    function updateData() {
        selectedYear = document.getElementById('yearsSelect').value;
        udpateTitle();
        renderData();
    }

    // append
    async function renderData() {
        const API = new Api();
        countProject = await API.getProjects(selectedYear, selectedDivision);
        quarterReports = await API.getReportedProjects(selectedYear, selectedDivision);

        document.getElementById('projectCountCard').innerText = countProject.countProject || 0;
        document.getElementById('projectPlannedCountCard').innerText = countProject.countProjectPlanned || 0;
        document.getElementById('projectBudget').innerText = parseInt(countProject.budget || 0).toLocaleString();
        document.getElementById('projectfinishReported').innerText = countProject.finishReported || 0;
        
        if(quarterReports === false) {
            quarterReports = {
                q1_reported: 0,
                q1_pending: 0,
                q2_reported: 0,
                q2_pending: 0,
                q3_reported: 0,
                q3_pending: 0,
                q4_reported: 0,
                q4_pending: 0
            };
        }
        if(countProject === false) {
            countProject = {
                countProject: 0
            };
        }

        reportQuarterlyChart(quarterReports);
        reportProgressionChart(countProject.countProject);
    }

    function reportQuarterlyChart(data) {
        document.querySelector("#reportQuarterlyChart").innerHTML = '';
        if (data === undefined) {
            console.error('Failed to fetch reported projects');
            return;
        }

        let reported = [];
        let pending = [];

        reported.push(data.q1_reported);
        reported.push(data.q2_reported);
        reported.push(data.q3_reported);
        reported.push(data.q4_reported);
        pending.push(data.q1_pending);
        pending.push(data.q2_pending);
        pending.push(data.q3_pending);
        pending.push(data.q4_pending);

        var options = {
            series: [{
                name: 'รายงานแล้ว',
                data: reported
            }, {
                name: 'รอรายงาน',
                data: pending
            }],
            chart: {
                height: 200,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false,
                },
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            colors: ['#71dd37', '#ffab00'],
            xaxis: {
                categories: ['Q1', 'Q2', 'Q3', 'Q4'],
            },
            yaxis: {
                min: 0,
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val
                    }
                }
            },
            legend: {
                show: false,
            }
        };

        var chart = new ApexCharts(document.querySelector("#reportQuarterlyChart"), options);
        chart.render();
    }

    function reportProgressionChart(countProject) {
        document.querySelector("#reportProgressionChart").innerHTML = '';
        const QUARTER = 4;
        const PERCENTILE = countProject * QUARTER;

        var options = {
            chart: {
                height: 250,
                type: 'radialBar',
            },
            colors: ['#71dd37'],
            series: [PERCENTILE],
            labels: ['การรายงาน'],
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 15,
                        size: "70%"
                    },
                    dataLabels: {
          
                        name: {
                            offsetY: -10,
                            show: true,
                            color: "#111",
                            fontSize: "18px"
                        },
                        value: {
                            color: "#71dd37",
                            fontSize: "30px",
                            show: true
                        }
                    }
                }
            },


            stroke: {
                lineCap: "round",
            },
        }
        var chart = new ApexCharts(document.querySelector("#reportProgressionChart"), options);

        chart.render();

    }

    function udpateTitle() {
        const dashboardTitle = document.getElementById('dashboardTitle');
        dashboardTitle.innerText = `ภาพรวมการรายงานประจำปี ${selectedYear}`;
    }
</script>