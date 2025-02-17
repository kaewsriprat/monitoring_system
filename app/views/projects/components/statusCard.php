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

<script>
    let yearValue = '<?= Budgetyear::getBudgetyearThai(); ?>';
    const divisionValue = '<?= User::division(); ?>';

    document.addEventListener('DOMContentLoaded', function(){
        updateProjectsStatus();

        const yearsSelect = document.getElementById('yearsSelect');
        yearsSelect.addEventListener('change', (e) => {
            yearValue = e.target.value;
            showStatusCardSpinner(true);
            showStatusCard(false);
            updateProjectsStatus();
        });

    });

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

    async function getProjectStatus() {
        const url = `/projects/getProjectStatus/${yearValue}/${divisionValue}`;
        let res = await axios.get(url);
        return res.data;
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
</script>