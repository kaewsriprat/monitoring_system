<style>
    .swal-label-danger {
        background-color: #ffe0db !important;
        color: #ff5722 !important;
    }

    .swal-label-danger:hover {
        background-color: #ff5722 !important;
        color: #fff !important;
    }
</style>
<div class="card">
    <div class="card-header bg-label-success py-2">
        <span class="fw-bold fs-5">เป้าหมายตัวชี้วัด</span>
    </div>
    <!-- loader -->
    <div class="text-center my-5" id="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="row mb-5 mt-2 d-none" id="tableDiv">
        <div class="col-12 text-center col-md-8 text-md-start">
            <p class="fw-bold fs-3 px-3 text-success pt-3">เป้าหมายตัวชี้วัด</p>
        </div>
        <div class="col-12 text-center col-md-4 text-md-end">
            <div>
                <button class="btn btn-label-success mt-3 me-3 d-none" id="newGoalBtn" onclick="newGoal()"><i class="bx bx-plus pe-2"></i>เพิ่มเป้าหมาย</button>
            </div>
        </div>
 
        <div class="col-12">

            <div class="card-datatable table-responsive text-nowrap">
                <table class="datatables-basic table " id="goalsTable">
                    <thead>
                        <tr>
                            <th class="fw-bold">id</th>
                            <th class="fw-bold">ปีงบประมาณ</th>
                            <th class="fw-bold">เป้าหมาย</th>
                            <th class="fw-bold">ประเภท</th>
                            <th class="fw-bold">จัดการ</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    let datatable = null;

    function initDatatable() {
        fetchData();
    }

    async function fetchData() {
        let data = <?= json_encode($goals); ?>;

        await toggleLoader(true);
        await datatableRender(data);
        await toggleTable(true);
        await toggleButton(true);
        await toggleLoader(false);

    }

    function datatableRender(data) {
        datatable = $('#goalsTable').DataTable({
            data: data,
            columns: [{
                    data: 'id'
                },
                {
                    data: 'year'
                },
                {
                    data: 'title'
                },
                {
                    data: 'classification',
                    render: function(data, type, row) {
                        return classificationBadge(data);
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded fs-5"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-warning" onclick="editGoal(${data.id})" href="javascript:;"><i class="bx bx-edit pe-2"></i>แก้ไข</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item text-danger" onclick="deleteGoal(${data.id})" href="javascript:;"><i class="bx bx-trash pe-2"></i>ลบ</a></li>
                            </ul>
                        </div>
                        `
                    }
                }
            ],
            columnDefs: [{
                    className: 'text-center',
                    targets: [0, 1, 3, 4]
                },
                {
                    className: 'w-100',
                    targets: [2]
                },
                {
                    orderable: false,
                    targets: [-1]
                }
            ],
        })
    }

    function toggleLoader(status) {
        if (status) {
            document.getElementById('loader').classList.remove('d-none');
        } else {
            document.getElementById('loader').classList.add('d-none');
        }
    }

    function toggleTable(status) {
        if (status) {
            document.getElementById('tableDiv').classList.remove('d-none');
        } else {
            document.getElementById('tableDiv').classList.add('d-none');
        }
    }

    function toggleButton(status) {
        if (status) {
            document.getElementById('newGoalBtn').classList.remove('d-none');
        } else {
            document.getElementById('newGoalBtn').classList.add('d-none');
        }
    }

    function classificationBadge(classification) {
        switch (classification) {
            case 'major':
                return '<span class="badge bg-primary">เป้ารวม</span>';
            case 'minor':
                return '<span class="badge bg-warning">เป้าย่อย</span>';
            default:
                return '<span class="badge bg-danger">ไม่ระบุ</span>';
        }
    }

    function deleteGoal(id) {
        swal.fire({
            title: "คุณต้องการลบเป้าหมายนี้ใช่หรือไม่?",
            text: "หากลบเป้าหมายแล้วจะไม่สามารถกู้คืนได้อีก",
            icon: "warning",
            showConfirmButton: true,
            showCancelButton: true,
            customClass: {
                confirmButton: "btn swal-label-danger me-4",
                cancelButton: "btn btn-primary ms-4"
            },
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ไม่',
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                const url = `/indicators/deleteGoal/${id}`;
                axios
                    .delete(url)
                    .then((res) => {
                        console.log(res.data)
                        if (res.data.status === 'success') {
                            swal.fire({
                                title: "ลบเป้าหมายสำเร็จ",
                                icon: "success",
                                showConfirmButton: true,
                                confirmButtonText: "ตกลง",
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            swal.fire({
                                title: "ลบไฟล์ไม่สำเร็จ",
                                icon: "error",
                                showConfirmButton: true,
                                confirmButtonText: "ตกลง",
                                timer: 1500
                            });
                        }
                    })
            }
        });
    }
</script>