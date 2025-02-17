<?php
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
        / ยืนยันตัวชี้วัด
    </h5>

    <div class="row g-3">
        <div class="col-12">
            <div class="d-flex justify-content-center justify-content-md-start">
                <div class="col-12 col-md-3">
                    <label for="yearsSelect" class="form-label">ปีงบประมาณ</label>
                    <select class="form-select" id="yearsSelect" name="yearsSelect" onchange="updateYear()">
                        <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                            <option value="<?= $year ?>"><?= $year ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-12">
            <p class="h2 fw-bold mb-0">คะแนนตัวชี้วัดประจำปีงบประมาณ <span id="indTitle"></span></p>
            <small class="fs-5 text-danger">* กดที่ชื่อตัวชี้วัดเพื่อขยาย</small>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-primary py-2 h4">
                    ตัวชี้วัดรวม
                </div>
                <div class="spinnerWrapper d-flex justify-content-center my-4">
                    <div class="spinner-border text-primary" role="status" id="spinner">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="card-body d-none indCard" id="majorIndicatorCards">
                    <div id="majorIndicatorCard"></div>
                    <div class="d-none d-flex justify-content-center mt-3">
                        <p class="fs-4">ไม่พบข้อมูล</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-warning py-2 h4">
                    ตัวชี้วัดย่อย
                </div>
                <div class="spinnerWrapper d-flex justify-content-center my-4">
                    <div class="spinner-border text-warning" role="status" id="spinner">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="card-body d-none indCard" id="minorIndicatorCards">
                    <div id="minorIndicatorCard"></div>
                    <div class="d-none d-flex justify-content-center mt-3">
                        <p class="fs-4">ไม่พบข้อมูล</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const yearsSelect = document.getElementById('yearsSelect');
    let currentYear = yearsSelect.value;
    const division = <?= $division ?>;
    let majorIndicators = [];
    let minorIndicators = [];
    let indicatorsData;

    document.addEventListener('DOMContentLoaded', async function() {
        indicatorsData = await API.getIndicatorsByDivision(currentYear, division);
        updateTitle();
        await classifyIndicators();
        await displayIndicators();
    });

    class API {
        static async getIndicatorsByDivision(currentYear, division) {
            const url = `/indicators/getIndicatorsByDivision/${currentYear}/${division}`;
            return await this.get(url);
        }
        static async get(url) {
            const response = await axios.get(url);
            return response.data;
        }

        static async post(url, formData) {
            const response = await axios.post(url, formData);
            return response.data;
        }
    }

    function updateTitle() {
        const indTitle = document.getElementById('indTitle');
        indTitle.textContent = currentYear;
    }

    async function updateYear() {
        clearCard();
        currentYear = yearsSelect.value;
        updateTitle();
        showSpinner(true);
        showCard(false);
        indicatorsData = await API.getIndicatorsByDivision(currentYear, division);
        classifyIndicators();
        displayIndicators();
    }

    function classifyIndicators() {
        indicatorsData.forEach(indicator => {
            if (indicator.classification === 'major') {
                majorIndicators.push(indicator);
            } else if (indicator.classification === 'minor') {
                minorIndicators.push(indicator);
            } else {
                console.error('Invalid classification');
            }
        });
    }


    function displayIndicators() {

        showSpinner(false);
        showCard(true);

        if (majorIndicators.length > 0) {
            showTable('majorIndicatorCards', true);
            displayMajorCard();
        } else {
            showTable('majorIndicatorCards', false);
        }

        if (minorIndicators.length > 0) {
            showTable('minorIndicatorCards', true);
            displayMinorCard();
        } else {
            showTable('minorIndicatorCards', false);
        }

    }

    function showSpinner(show) {
        const spinnerWrapper = document.getElementsByClassName('spinnerWrapper');
        for (let spinner of spinnerWrapper) {
            if (show) {
                spinner.classList.remove('d-none');
            } else {
                spinner.classList.add('d-none');
            }
        }
    }

    function showCard(show) {
        const indCard = document.getElementsByClassName('indCard');
        for (let card of indCard) {
            if (show) {
                card.classList.remove('d-none');
            } else {
                card.classList.add('d-none');
            }
        }
    }

    function showTable(cardName, show) {
        const ele = document.getElementById(cardName);
        const table = ele.children[0];
        const noData = ele.children[1];

        if (show) {
            table.classList.remove('d-none');
            noData.classList.add('d-none');
        } else {
            table.classList.add('d-none');
            noData.classList.remove('d-none');
        }
    }

    function displayMajorCard() {
        const majorIndicatorCard = document.getElementById('majorIndicatorCard');
        let html = indicatorCard(majorIndicators);
        majorIndicatorCard.innerHTML = html;
    }

    function displayMinorCard() {
        const minorIndicatorCard = document.getElementById('minorIndicatorCard');
        let html = indicatorCard(minorIndicators);
        minorIndicatorCard.innerHTML = html;
    }

    function indicatorCard(data) {

        let html = `<div class="accordion" id="indicatorAccordion_${data[0].classification}">`;

        data.forEach((item, index) => {
            html += `
            <div class="card accordion-item">
                <h2 class="accordion-header" id="header_${index}">
                    <button type="button" class="bg-label-secondary accordion-button" data-bs-toggle="collapse" data-bs-target="#accordion_${item.classification}_${index}" aria-expanded="true" aria-controls="accordion_${item.classification}_${index}" role="tabpanel">
                        <p class="fw-bold fs-4 mb-0 text-dark">${item.ind_title}</p>
                    </button>
                </h2>
                <div id="accordion_${item.classification}_${index}" class="accordion-collapse collapse " data-bs-parent="#indicatorAccordion_${data[0].classification}">
                    <div class="accordion-body pt-3">
                        <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <p class="fw-bold mb-0">โครงการ</p>
                            <span class="fw-bold text-dark">${item.project_name}</span>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="fw-bold mb-0">ชื่อเป้าหมาย</p>
                            <span class="fw-bold text-dark">${item.goal_title}</span>
                        </div>
                            <div class="col-12 col-md-6">
                                <p class="fw-bold mb-0">ค่าเป้าหมาย</p>
                                <span class="fw-bold text-dark">${item.target}</span>
                            </div>
                            <div class="col-12 col-md-6">
                                <p class="fw-bold mb-0">คะแนนรวม</p>
                                <span class="fw-bold text-dark">${item.total}</span>
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-6 col-md-3 border p-3">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-bold mb-0">Q1</p> 
                                    <p class="badge ${(item.q1_approve==0) ? 'bg-label-secondary':'bg-success'} mb-0" id="${item.rep_id}_1">${(item.q1_approve==0) ? 'รออนุมัติ':'อนุมัติแล้ว'}</p>
                                </div>
                                <p class="fw-bold text-dark fs-4 text-center mb-2">${item.q1_score}</p>
                                <div class="d-flex justify-content-evenly">
                                    <button class="btn btn-outline-secondary btn-sm w-25" onclick="approveScore(this, '${item.rep_id}', 1,0)" ${(item.q1_approve==0)?' disable':''}>
                                        <i class="bx bx-x"></i>
                                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                    <button class="btn btn-success btn-sm w-25" onclick="approveScore(this,'${item.rep_id}',1,1)" ${(item.q1_approve==1)?' disabled':''}>
                                        <i class="bx bx-check"></i>
                                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                             <div class="col-6 col-md-3 border p-3">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-bold mb-0">Q2</p> 
                                    <p class="badge ${(item.q2_approve==0) ? 'bg-label-secondary':'bg-success'} mb-0" id="${item.rep_id}_2">${(item.q2_approve==0) ? 'รออนุมัติ':'อนุมัติแล้ว'}</p>
                                </div>
                                <p class="fw-bold text-dark fs-4 text-center mb-2">${item.q2_score}</p>
                                <div class="d-flex justify-content-evenly">
                                    <button class="btn btn-outline-secondary btn-sm w-25" onclick="approveScore(this, '${item.rep_id}', 2,0)" ${(item.q2_approve==0)?' disabled':' '}>
                                        <i class="bx bx-x"></i>
                                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                    <button class="btn btn-success btn-sm w-25" onclick="approveScore(this,'${item.rep_id}',2,1)" ${(item.q2_approve==1)?' disabled':''}>
                                        <i class="bx bx-check"></i>
                                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                             <div class="col-6 col-md-3 border p-3">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-bold mb-0">Q3</p> 
                                    <p class="badge ${(item.q3_approve==0) ? 'bg-label-secondary':'bg-success'} mb-0" id="${item.rep_id}_3">${(item.q3_approve==0) ? 'รออนุมัติ':'อนุมัติแล้ว'}</p>
                                </div>
                                <p class="fw-bold text-dark fs-4 text-center mb-2">${item.q3_score}</p>
                                <div class="d-flex justify-content-evenly">
                                    <button class="btn btn-outline-secondary btn-sm w-25" onclick="approveScore(this, '${item.rep_id}', 3,0)" ${(item.q3_approve==0)?' disabled':''}>
                                        <i class="bx bx-x"></i>
                                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                    <button class="btn btn-success btn-sm w-25" onclick="approveScore(this,'${item.rep_id}',3,1)" ${(item.q3_approve==1)?' disabled':''}>
                                        <i class="bx bx-check"></i>
                                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                             <div class="col-6 col-md-3 border p-3">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-bold mb-0">Q4</p> 
                                    <p class="badge ${(item.q4_approve==0) ? 'bg-label-secondary':'bg-success'} mb-0" id="${item.rep_id}_4">${(item.q4_approve==0) ? 'รออนุมัติ':'อนุมัติแล้ว'}</p>
                                </div>
                                <p class="fw-bold text-dark fs-4 text-center mb-2">${item.q4_score}</p>
                                <div class="d-flex justify-content-evenly">
                                    <button class="btn btn-outline-secondary btn-sm w-25" onclick="approveScore(this, '${item.rep_id}', 4,0)" ${(item.q4_approve==0)?' disabled':''}>
                                        <i class="bx bx-x"></i>
                                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                    <button class="btn btn-success btn-sm w-25" onclick="approveScore(this,'${item.rep_id}',4,1)" ${(item.q4_approve==1)?' disabled':''}>
                                        <i class="bx bx-check"></i>
                                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        `
        })
        html += '</div>';

        return html;
    }

    async function approveScore(ele, repId, quarter, status) {
        showButtonSpinner(ele, true);
        const url = `/indicators/doApprove/${repId}/${quarter}/${status}`;
        let formData = new FormData();

        formData.append('rep_id', repId);
        formData.append('quarter', quarter);
        formData.append('status', status);

        const response = await API.post(url, formData);
        // if network is slow, the spinner will be shown for at least 1 second
        await new Promise(resolve => setTimeout(resolve, 1000));
        if(response.status === 'success'){
            showButtonSpinner(ele, false);
            updateBadge(ele.parentElement.parentElement.children[0].children[1], status);
            updateButtonStatus(ele, status);
        }
    }

    function showButtonSpinner(ele, show) {
        const spinner = ele.children[1];
        const icon = ele.children[0];
        if (show) {
            spinner.classList.remove('d-none');
            icon.classList.add('d-none');
        } else {
            spinner.classList.add('d-none');
            icon.classList.remove('d-none');
        }

    }

    function updateBadge(ele, status) {
        if (status == 1) {
            ele.classList.remove('bg-label-secondary');
            ele.classList.add('bg-success');
            ele.textContent = 'อนุมัติแล้ว';
        } else {
            ele.classList.remove('bg-success');
            ele.classList.add('bg-label-secondary');
            ele.textContent = 'รออนุมัติ';
        }
    }

    function updateButtonStatus(ele, status) {
        if (status === 1) {
            ele.previousElementSibling.disabled = false;
            ele.disabled = true;
        } else {
            ele.nextElementSibling.disabled = false;
            ele.disabled = true;
        }
    }

    function clearCard() {
        majorIndicators = [];
        minorIndicators = [];
        document.getElementById('majorIndicatorCard').innerHTML = '';
        document.getElementById('minorIndicatorCard').innerHTML = '';
    }
</script>