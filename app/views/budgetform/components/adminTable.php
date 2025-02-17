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
                <th>หน่วยงาน</th>
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
                    data: 'division_name'
                },
                {
                    data: 'q1_file_path',
                    render: function(data, type, row) {
                        return (data != null) 
                        ? `<a href="/public/uploads/${data}" target="_BLANK"><i class="bx bx-download text-primary"></i></a>` 
                        : `<a href="javascript:;"><i class="bx bx-x text-muted"></i></a>`;
                    }
                },
                {
                    data: 'q2_file_path',
                    render: function(data, type, row) {
                        return (data != null) 
                        ? `<a href="/public/uploads/${data}" target="_BLANK"><i class="bx bx-download text-primary"></i></a>` 
                        : `<a href="javascript:;"><i class="bx bx-x text-muted"></i></a>`;
                    }
                },
                {
                    data: 'q3_file_path',
                    render: function(data, type, row) {
                        return (data != null) 
                        ? `<a href="/public/uploads/${data}" target="_BLANK"><i class="bx bx-download text-primary"></i></a>` 
                        : `<a href="javascript:;"><i class="bx bx-x text-muted"></i></a>`;
                    }
                },
                {
                    data: 'q4_file_path',
                    render: function(data, type, row) {
                        return (data != null) 
                        ? `<a href="/public/uploads/${data}" target="_BLANK"><i class="bx bx-download text-primary"></i></a>` 
                        : `<a href="javascript:;"><i class="bx bx-x text-muted"></i></a>`;
                    }
                },
            ],
            columnDefs: [{
                className: 'text-center',
                targets: [0, 2, 3, 4, 5]
            }],
            order: [
                [0, 'desc']
            ]
        })
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


    // --- UTIL ---
    function showSpinner(show) {
        if (show) {
            document.getElementById('tableSpinner').classList.remove('d-none');
        } else {
            document.getElementById('tableSpinner').classList.add('d-none');
        }
    }

    function showTable(show) {
        if (show) {
            document.getElementById('tableWrapper').classList.remove('d-none');
        } else {
            document.getElementById('tableWrapper').classList.add('d-none');
        }
    }

    // --- UTIL ---
</script>