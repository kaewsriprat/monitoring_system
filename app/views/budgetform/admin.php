<div class="container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        / แบบสงป.
    </h5>
    <!-- END BREADCRUMB -->

    <?php
    $yearCount = 2;
    $startYear = Budgetyear::getBudgetyearThai() - $yearCount;
    $currentYear = Budgetyear::getBudgetyearThai();
    ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-primary py-2 h4">
                    รายงานแบบสงป. ประจำปี <span id="formBudgetYearTitle"></span>
                </div>

                <div class="row g-3 px-4 py-3">
                    <div class="col-12 col-md-2">
                        <label for="reportYearSelect" class="form-label">ปีงบประมาณ</label>
                        <select class="form-select" id="reportYearSelect" onchange="updateData()">
                            <?php for ($i = $currentYear; $i >= $startYear; $i--) : ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="divisionSelect" class="form-label">หน่วยงาน</label>
                        <select class="form-select" id="divisionSelect" onchange="updateData()">
                            <option value="0">ทั้งหมด</option>
                        </select>
                    </div>
                </div>

                <!-- table components -->
                <div class="text-center my-4" id="tableSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <?php include 'components/adminTable.php'; ?>
            </div>
        </div>
    </div>

</div>

<script>
    let budgetformData = [];
    let selectedYear = document.getElementById('reportYearSelect').value;
    let selectedDivision = document.getElementById('divisionSelect').value;
    document.getElementById('formBudgetYearTitle').innerText = selectedYear;

    document.addEventListener('DOMContentLoaded', async function() {
        appendDivisionSelect();
        updateData();
    });

    // --- FUNCTION ---

    async function appendDivisionSelect() {
        const divisions = await getDivisions();
        const divisionSelect = document.getElementById('divisionSelect');
        divisions.forEach(division => {
            const option = document.createElement('option');
            option.value = division.division_id;
            option.text = division.division_name;
            divisionSelect.appendChild(option);
        });
    }

    // --- FUNCTION ---

    // --- API ---

    async function getDivisions() {
        const url = '/divisions/getDivisions';
        let result = await axios.get(url).then(res => res.data);
        return result;
    }

    async function getFilteredFormReports(year, division) {
        const url = `/budgetform/getFilteredFormReports/${year}/${division}`;
        let result = await axios.get(url).then(res => res.data);
        return result;
    }

    // --- API ---

    // --- LISTENER ---

    async function updateData() {
        selectedYear = document.getElementById('reportYearSelect').value;
        selectedDivision = document.getElementById('divisionSelect').value;
        document.getElementById('formBudgetYearTitle').innerText = selectedYear;

        budgetformData = await getFilteredFormReports(selectedYear, selectedDivision);
        showSpinner(false);
        showTable(true);
        initDatatable();
    }

    // --- LISTENER ---
</script>