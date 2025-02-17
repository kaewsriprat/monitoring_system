<div class=" container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        / เปลี่ยนแปลงค่าเป้าหมาย
    </h5>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-primary py-2 h4">
                    คำขอเปลี่ยนแปลงค่าเป้าหมาย
                </div>
                <div class="card-body py-3" id="spinner">
                    <div class="d-flex justify-content-center py-3">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive d-none" id="requestTable">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-light">
                                <th class="fw-bold fs-6 text-center">#</th>
                                <th class="fw-bold fs-6 ">ชื่อตัวชี้วัด</th>
                                <th class="fw-bold fs-6 ">โครงการ/กิจกรรม</th>
                                <th class="fw-bold fs-6 text-center">ค่าเป้าหมายเดิม</th>
                                <th class="fw-bold fs-6 text-center">ค่าเป้าหมายใหม่</th>
                                <th class="fw-bold fs-6 text-center">ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody id="requestTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const division = '<?php echo User::division(); ?>';
    let requestLists = null;
    document.addEventListener('DOMContentLoaded', async () => {
        updateTable();
    });

    async function getPendingRequestTarget() {
        const res = await axios.get(`/indicators/getPendingRequestTargets/${division}`);
        return res.data;
    }

    function initTable() {
        const requestTableBody = document.getElementById('requestTableBody');
        requestTableBody.innerHTML = '';

        if(requestLists.length == 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td colspan="6" class="text-center">ไม่มีคำขอเปลี่ยนแปลงค่าเป้าหมาย</td>
            `;
            requestTableBody.appendChild(tr);
            showSpinner(false);
            showTable(true);
            return;
        }

        requestLists.forEach((request, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${request.title}</td>
                <td>${request.project_name}</td>
                <td class="fw-bold fs-5 text-center text-warning">${request.target}</td>
                <td class="fw-bold fs-5 text-center text-success">${request.new_target}</td>
                <td class="d-flex justify-content-evenly px-0">
                <button class="btn btn-icon rounded-pill text-danger me-2" onclick="updateRequestTargetStatus(${request.id}, 2)"><i class="bx bx-x fs-3"></i></button>
                <button class="btn btn-icon rounded-pill text-success ms-2" onclick="updateRequestTargetStatus(${request.id}, 1)"><i class="bx bx-check fs-3"></i></button>
                </td>
            `;
            requestTableBody.appendChild(tr);
        });
        showSpinner(false);
        showTable(true);
    }

    async function updateTable() {
        showSpinner(true);
        showTable(false);
        requestLists = await getPendingRequestTarget();
        initTable();
    }

    function showSpinner(status) {
        const spinner = document.getElementById('spinner');
        (status) ? spinner.classList.remove('d-none'): spinner.classList.add('d-none');
    }

    function showTable(status) {
        const requestTable = document.getElementById('requestTable');
        (status) ? requestTable.classList.remove('d-none'): requestTable.classList.add('d-none');
    }

    function updateRequestTargetStatus(id, status) {
        swal.fire({
            title: 'ยืนยันการดำเนินการ',
            text: 'คุณต้องการดำเนินการต่อใช่หรือไม่',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่',
        }).then((result) => {
            if (result.isConfirmed) {
                doUpdateRequest(id, status);
            }
        })
    }

    function doUpdateRequest(id, status) {
        axios.get(`/indicators/approveRequestTarget/${id}/${status}`)
            .then(res => {
                if (res.data.status == 'success') {
                    swal.fire({
                        icon: 'success',
                        title: 'อนุมัติสำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        updateTable();
                        getNotify();
                    });
                }
            })
            .catch(err => {
                console.error(err);
            });
    }
</script>