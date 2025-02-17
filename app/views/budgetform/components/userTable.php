<style>
    .swal2-container {
        z-index: 99999;
    }

    .swal-label-danger {
        background-color: #ffe0db !important;
        color: #ff5722 !important;
    }

    .swal-label-danger:hover {
        background-color: #ff5722 !important;
        color: #fff !important;
    }
</style>


<div class="card-datatable table-responsive text-wrap d-none" id="tableWrapper">
    <table class="datatables-ajax table " id="budgetFormReportsTable">
        <thead>
            <tr>
                <th style="max-width:10%;">ปีงบประมาณ</th>
                <th>Q1</th>
                <th>Q2</th>
                <th>Q3</th>
                <th>Q4</th>
            </tr>
        </thead>
    </table>
</div>

<script>
    let dataTable;
    let budgetformData = [];
    let budgetYearList = [];

    function initDatatable() {
        if (dataTable) {
            dataTable.destroy();
        }

        dataTable = $('#budgetFormReportsTable').DataTable({
            data: budgetformData,
            columns: [{
                    data: 'budget_year'
                },
                {
                    data: 'q1_file_path',
                    render: function(data, type, row) {
                        const quarter = 1;
                        if (data) {
                            return buttonGroup(data, row.id, quarter);
                        }
                        return `<button class="btn btn-sm btn-icon text-muted" onclick="uploadReportForm(${row.id}, ${row.budget_year}, ${quarter})"><i class="bx bx-upload"></i></button>`;
                    }
                },
                {
                    data: 'q2_file_path',
                    render: function(data, type, row) {
                        const quarter = 2;
                        if (data) {
                            return buttonGroup(data, row.id, quarter);
                        }
                        return `<button class="btn btn-sm btn-icon text-muted" onclick="uploadReportForm(${row.id}, ${row.budget_year}, ${quarter})"><i class="bx bx-upload"></i></button>`;
                    }
                },
                {
                    data: 'q3_file_path',
                    render: function(data, type, row) {
                        const quarter = 3;
                        if (data) {
                            return buttonGroup(data, row.id, quarter);
                        }
                        return `<button class="btn btn-sm btn-icon text-muted" onclick="uploadReportForm(${row.id}, ${row.budget_year}, ${quarter})"><i class="bx bx-upload"></i></button>`;
                    }
                },
                {
                    data: 'q4_file_path',
                    render: function(data, type, row) {
                        const quarter = 4;
                        if (data) {
                            return buttonGroup(data, row.id, quarter);
                        }
                        return `<button class="btn btn-sm btn-icon text-muted" onclick="uploadReportForm(${row.id}, ${row.budget_year}, ${quarter})"><i class="bx bx-upload"></i></button>`;
                    }
                },
            ],
            columnDefs: [{
                className: 'text-center',
                targets: [0, 1, 2, 3, 4]
            }],
            order: [
                [0, 'desc']
            ]
        })
    }

    async function getBudgetformReportsByDivision() {
        const url = '/budgetform/getBudgetformReportsByDivision/<?= User::division() ?>';
        let result = await axios.get(url).then(res => res.data);
        return result;
    }

    function showTable(show) {
        if (show) {
            document.getElementById('tableWrapper').classList.remove('d-none');
        } else {
            document.getElementById('tableWrapper').classList.add('d-none');
        }
    }

    function buttonGroup(data, id, quarter) {
        return `<div class="btn-group">
                    <button type="button" class="btn btn-label-dark btn-sm btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/public/uploads/${data}" target="_BLANK"><i class="bx bx-download text-info pe-2"></i>ดาวน์โหลด</a></li>
                        <li><a class="dropdown-item" href="javascript:;" onclick="deleteForm(${id}, ${quarter})"><i class="bx bx-trash text-danger pe-2"></i>ลบไฟล์</a></li>
                    </ul>
                </div>`;
    }

    function deleteForm(id, quarter) {
        swal.fire({
            title: "คุณต้องการลบรายงานนี้ใช่หรือไม่?",
            html: `<p class="text-danger">หากลบไฟล์แล้วจะไม่สามารถกู้คืนได้อีก</p>`,
            icon: 'warning',
            showConfirmButton: true,
            showCancelButton: true,
            customClass: {
                confirmButton: "btn swal-label-danger me-4",
                cancelButton: "btn btn-primary ms-4"
            },
            confirmButtonText: 'ใช่, ลบรายงานนี้',
            cancelButtonText: 'ไม่, ยกเลิก',
        }).then((result) => {
            if (result.isConfirmed) {
                doDelete(id, quarter);
                swal.fire({
                    title: "ลบไฟล์สำเร็จ",
                    icon: "success",
                    showConfirmButton: true,
                    confirmButtonText: "ตกลง",
                }).then(async () => {
                    showSpinner(true);
                    showTable(false);
                    budgetformData = await getBudgetformReportsByDivision();
                    showSpinner(false);
                    showTable(true);
                    initDatatable();
                });

            }
        });
    }

    async function doDelete(id, quarter) {
        const url = `/budgetform/delete/${id}/${quarter}`;
        let response = await axios.delete(url);
        return response.data;
    }
</script>