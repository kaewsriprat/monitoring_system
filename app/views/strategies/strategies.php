<div class="container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        / จัดการยุทธศาสตร์
    </h5>

    <?php
    $yearCount = 2;
    $startYear = Budgetyear::getBudgetyearThai() - $yearCount;
    $currentYear = Budgetyear::getBudgetyearThai();
    ?>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-primary py-2">
                    <span class="fw-bold fs-5">ยุทธศาสตร์</span>
                </div>
                <!-- loader -->
                <div class="text-center my-5" id="loader">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="row d-none pt-3" id="tableDiv">
                    <div class="col-12 text-center col-md-2 text-md-start px-4">
                        <label for="yearSelect" class="form-label">ปีงบประมาณ</label>
                        <select class="form-select" id="yearSelect" name="yearSelect" onchange="updateTable()">
                            <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                                <option value="<?= $year ?>"><?= $year ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-10 text-center text-md-end mt-3 pt-0 pt-md-3">
                        <button class="btn btn-label-primary me-0 me-md-3" onclick="newStrategy()">
                            <i class="bx bx-plus pe-2"></i>
                            เพิ่มยุทธศาสตร์
                        </button>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="card-datatable table-responsive text-nowrap">
                            <table class="datatables-basic table ">
                                <thead>
                                    <tr>
                                        <th class="fw-bold fs-6 text-center">#</th>
                                        <th class="fw-bold fs-6 ">ปี</th>
                                        <th class="fw-bold fs-6" style="min-width:50% !important;">ชื่อยุทธศาสตร์</th>
                                        <th class="fw-bold fs-6 text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal fade" id="newStrategyModal" tabindex="-1" aria-labelledby="newStrategyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-label-primary py-2">
                    <span class="fw-bold fs-5">เพิ่มยุทธศาสตร์</span>
                    <button type="button" class="btn-close bg-white mt-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newStrategyForm">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="year" class="form-label">ปีงบประมาณ</label>
                                <select class="form-select" id="year" name="year" required>
                                    <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                                        <option value="<?= $year ?>"><?= $year ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="strategyName" class="form-label">ชื่อยุทธศาสตร์</label>
                                <input type="text" class="form-control" id="strategyName" name="strategyName" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary" onclick="submitStrategy()">บันทึก</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="editModalContainer"></div>
</div>

<script>
    const tableDiv = document.getElementById('tableDiv');
    const loader = document.getElementById('loader')
    let yearSelect = document.getElementById('yearSelect');
    let selectYear = yearSelect.value;

    document.addEventListener('DOMContentLoaded', async () => {
        await updateTable();
    });

    async function updateTable() {
        selectYear = yearSelect.value;
        showLoader(true);
        showTable(false);
        const strategies = await getStrategies(selectYear);
        if (strategies) {
            showLoader(false);
            showTable(true);
            appendTable(strategies);
        }
    }

    function appendTable(data) {
        let tableHtml = '';
        data.forEach((strategy, index) => {
            tableHtml += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${strategy.budget_year}</td>
                    <td>${strategy.strategy_name}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded fs-5"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" onclick="editStrategy(${strategy.id})" href="javascript:;"><i class="bx bx-edit pe-2 text-warning"></i>แก้ไข</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item" onclick="deleteStrategy(${strategy.id})" href="javascript:;"><i class="bx bx-trash pe-2 text-danger"></i>ลบ</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `;
        });

        document.querySelector('.datatables-basic tbody').innerHTML = tableHtml;
    }

    function showLoader(status) {
        if (status) {
            loader.classList.remove('d-none');
        } else {
            loader.classList.add('d-none');
        }
    }

    function showTable(status) {
        if (status) {
            tableDiv.classList.remove('d-none');
        } else {
            tableDiv.classList.add('d-none');
        }
    }

    async function getStrategies(year) {
        const url = `/strategies/getStrategies/${year}`;
        const res = await axios.get(url);
        return res.data;
    }

    // NEW STRATEGY
    function newStrategy() {
        $('#newStrategyModal').modal('show');
    }

    async function submitStrategy() {
        event.preventDefault();
        const result = await doAddStrategy();
        $('#newStrategyModal').modal('hide');
        if (result.status === 'success') {
            await updateTable();
            // success swal with timer
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: result.message,
                timer: 1500,
                showConfirmButton: false,
            }).then(() => {
                updateTable();
            })
        } else {
            Swal.fire({
                icon: 'error',
                title: 'ล้มเหลว',
                text: result.message,
            }).then(() => {
                updateTable();
            })
        }
    }

    async function doAddStrategy() {
        const url = '/strategies/newStrategy';
        let formdata = new FormData();
        formdata.append('yearSelect', document.getElementById('yearSelect').value);
        formdata.append('strategyName', document.getElementById('strategyName').value);

        const res = await axios.post(url, formdata);
        return res.data;
    }

    // EDIT STRATEGY
    async function editStrategy(id) {
        const strategy = await getStrategyById(id);
        document.getElementById('editModalContainer').innerHTML = editModal();
        appendYearEdit();
        if (strategy) {
            $('#editStrategyModal').modal('show');
            document.getElementById('year_edit').value = strategy.budget_year;
            document.getElementById('strategyName_edit').value = strategy.strategy_name;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'ล้มเหลว',
                text: 'ไม่พบข้อมูลยุทธศาสตร์',
            }).then(() => {
                updateTable();
            })
        }

        document.getElementById('submitEditStrategy').addEventListener('click', () => {
            submitEditStrategy(id);
        });
    }

    async function submitEditStrategy(id) {
        event.preventDefault();
        $('#editStrategyModal').modal('hide');
        const result = await doEditStrategy(id);
        if (result.status === 'success') {
            // destroy modal
            document.getElementById('editModalContainer').innerHTML = '';
            await updateTable();
            // success swal with timer
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: result.message,
                timer: 1500,
                showConfirmButton: false,
            }).then(() => {
                updateTable();
            })
        } else {
            Swal.fire({
                icon: 'error',
                title: 'ล้มเหลว',
                text: result.message,
            }).then(() => {
                updateTable();
            })
        }
    }

    async function doEditStrategy(id) {
        const url = `/strategies/updateStrategy/${id}`;
        let formdata = new FormData();
        formdata.append('yearSelect', document.getElementById('year_edit').value);
        formdata.append('strategyName', document.getElementById('strategyName_edit').value);
        const res = await axios.post(url, formdata);
        return res.data;
    }

    async function getStrategyById(id) {
        const url = `/strategies/getStrategyById/${id}`;
        const res = await axios.get(url);
        return res.data;
    }

    function editModal() {
        return `
    <div class="modal fade" id="editStrategyModal" tabindex="-1" aria-labelledby="editStrategyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-label-primary py-2">
                    <span class="fw-bold fs-5">เพิ่มยุทธศาสตร์</span>
                    <button type="button" class="btn-close bg-white mt-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStrategyForm">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="year" class="form-label">ปีงบประมาณ</label>
                                <select class="form-select" id="year_edit" name="year_edit" required></select>
                            </div>
                            <div class="col-12">
                                <label for="strategyName_edit" class="form-label">ชื่อยุทธศาสตร์</label>
                                <input type="text" class="form-control" id="strategyName_edit" name="strategyName_edit" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary" id="submitEditStrategy">บันทึก</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>`;
    }

    function appendYearEdit() {
        let yearSelect = document.getElementById('year_edit');
        let selectYear = yearSelect.value;
        for (let year = <?= $currentYear ?>; year >= <?= $startYear ?>; year--) {
            let option = document.createElement('option');
            option.value = year;
            option.text = year;
            yearSelect.appendChild(option);
        }
    }

    // DELETE STRATEGY
    async function deleteStrategy(id) {
        console.log('delete', id);
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบยุทธศาสตร์นี้หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบ!',
            cancelButtonText: 'ยกเลิก'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const result = await doDeleteStrategy(id);
                if (result.status === 'success') {
                    await updateTable();
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: result.message,
                        timer: 1500,
                        showConfirmButton: false,
                    }).then(() => {
                        updateTable();
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ล้มเหลว',
                        text: result.message,
                    }).then(() => {
                        updateTable();
                    })
                }
            }
        })

    }

    async function doDeleteStrategy(id) {
        const url = `/strategies/deleteStrategy/${id}`;
        const res = await axios.delete(url);
        return res.data;
    }
</script>