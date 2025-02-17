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
                    รายงานแบบสงป.
                </div>
                <div class="card-body pt-4" id="cardBody">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-label-primary" onclick="addReportYear()">
                            <i class="bx bx-plus"></i>
                            เพิ่มปีรายงาน
                        </button>
                    </div>
                </div>
                <!-- table components -->
                <div class="text-center my-4" id="tableSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <?php include 'components/userTable.php'; ?>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div id="modalWrapper"></div>

    <div class="modal fade" id="reportYearModal" tabindex="-1" aria-labelledby="reportYearModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportYearModalLabel">เพิ่มปีรายงาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <label for="reportYear" class="form-label">ปีงบประมาณ</label>
                        <select class="form-select" name="reportYear" id="reportyearSelect">
                            <?php for ($i = $currentYear; $i >= $startYear; $i--) : ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" onclick="submitReportYear()">บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const modalWrapper = document.querySelector('#modalWrapper');
    modalWrapper.innerHTML = appendUploadReportFormModal();

    document.addEventListener('DOMContentLoaded', async function() {
        budgetformData = await getBudgetformReportsByDivision();
        showSpinner(false);
        showTable(true);
        initDatatable();
        uploadFormModalObserver();

    });

    function uploadFormModalObserver() {
        // observe when uploadFormModal is close
        const uploadFormModal = document.getElementById('uploadFormModal');
        uploadFormModal.addEventListener('hidden.bs.modal', function() {
            const modalBody = document.querySelector('#uploadFormModal .modal-body');
            modalBody.innerHTML = '';
            // destroy modal
            uploadFormModal.remove();
        });

    }

    function showSpinner(show) {
        if (show) {
            document.getElementById('tableSpinner').classList.remove('d-none');
        } else {
            document.getElementById('tableSpinner').classList.add('d-none');
        }
    }

    function addReportYear() {
        $('#reportYearModal').modal('show');
    }

    async function submitReportYear() {
        event.preventDefault();
        const reportYear = document.getElementById('reportyearSelect').value;
        let result = await doSubmit(reportYear);
        if (!result) {
            swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ปีงบประมาณนี้มีอยู่แล้ว'
            })
            return;
        }
        showSpinner(true);
        showTable(false);
        budgetformData = await getBudgetformReportsByDivision();
        showSpinner(false);
        showTable(true);
        initDatatable();

    }

    async function doSubmit(reportYear) {
        const url = "/budgetform/submitReportYear";
        let formData = new FormData();
        formData.append('reportYear', reportYear);
        const response = await axios.post(url, formData);
        return response.data;
    }


    function uploadReportForm(id, budgetYear, quarter) {
        const modalWrapper = document.querySelector('#modalWrapper');
        modalWrapper.innerHTML = appendUploadReportFormModal();
        $('#uploadFormModal').modal('show');
        const submitReportFormBtn = document.getElementById('submitReportFormBtn');
        submitReportFormBtn.addEventListener('click', async function() {
            event.preventDefault();
            if( document.getElementById('reportFile').files[0] === undefined) {
                swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณาเลือกไฟล์รายงาน'
                })
                return;
            }
            let response = await submitReportForm(id, budgetYear, quarter);
            if(response.status == false ) {
                swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: response.message
                })
                return;
            }
            swal.fire({
                icon: 'success',
                title: 'บันทึกสำเร็จ',
                text: 'บันทึกไฟล์รายงานสำเร็จ'
            }).then(async () => {
                $('#uploadFormModal').modal('hide');
                showSpinner(true);
                showTable(false);
                budgetformData = await getBudgetformReportsByDivision();
                showSpinner(false);
                showTable(true);
                initDatatable();
            });
        });
    }

    async function submitReportForm(id, budgetYear, quarter) {
        const url = '/budgetform/submitReportForm';
        let formData = new FormData();
        formData.append('id', id);
        formData.append('budgetYear', budgetYear);
        formData.append('quarter', quarter);
        formData.append('reportFile', document.getElementById('reportFile').files[0]);

        let response = await axios.post(url, formData);
        return response.data
    }

    function appendUploadReportFormModal() {
        return `
            <div class="modal fade" id="uploadFormModal" tabindex="-1" aria-labelledby="uploadFormModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadFormModalLabel">อัพโหลดรายงานสงป.</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="post">
                            <div class="modal-body">
                                <label for="reportFile" class="form-label">ไฟล์รายงาน</label>
                                <input type="file" class="form-control" name="reportFile" id="reportFile">
                            </div>
                            <div class="modal-footer">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" id="submitReportFormBtn">บันทึก</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            `;
    }
</script>