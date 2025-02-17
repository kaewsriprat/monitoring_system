<style>
    /* swal in front */
    .swal2-container {
        z-index: 10000;
    }
</style>

<div class=" container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        / ประกาศ
    </h5>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-primary py-2 h4">
                    ข่าวประกาศ
                </div>
                <!-- loader -->
                <div class="text-center mt-4" id="loader">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="text-end">
                    <button class="btn btn-label-primary mt-3 me-3  d-none" id="newAnnouceBtn" onclick="newAnnouce()"><i class="bx bx-plus"></i> เพิ่มประกาศ</button>
                </div>
                <div class="card-datatable text-nowrap">
                    <table class="datatables-ajax table d-none" id="projectsTable">
                        <thead>
                            <tr>
                                <th class="fw-bold">id</th>
                                <th class="fw-bold">หัวข้อ</th>
                                <th class="fw-bold">รายละเอียด</th>
                                <th class="fw-bold">เริ่มประกาศ</th>
                                <th class="fw-bold">ปักหมุด</th>
                                <th class="fw-bold">เปิด/ปิด</th>
                                <th class="fw-bold">จัดการ</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- New Annouce Modal -->
    <div class="modal fade" id="newAnnouceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-1">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="row">
                        <div class="col-12">
                            <h5 class="fw-bold" id="newAnnouceModalTitle"></h5>
                            <hr>
                            <form action="/annouce/new_annouce_post" method="POST">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">หัวข้อ</label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="หัวข้อ">
                                        </div>

                                        <div class="mb-3">
                                            <label for="detail" class="form-label">รายละเอียด</label>
                                            <textarea class="form-control" id="detail" name="detail" rows="3" placeholder="รายละเอียด"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">วันที่เริ่มประกาศ</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" placeholder="วันที่เริ่มประกาศ">
                                        </div>

                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">วันสิ้นสุด</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" placeholder="วันสิ้นสุด">
                                        </div>

                                        <div class="mb-3 text-center">
                                            <button class="btn btn-secondary me-2" type="reset" onclick="closeModal()">ยกเลิก</button>
                                            <button class="btn btn-primary ms-2" type="sumbit">บันทึก</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- New Annouce Modal -->

</div>

<script>
    $(document).ready(function() {
        showLoader();
        setTimeout(() => {
                hideLoader();
                initDataTable(<?php echo json_encode($data['annouces']); ?>)
            },
            500);
    })

    function hideLoader() {
        $('#loader').addClass('d-none');
        $('#projectsTable').removeClass('d-none');
        $('#newAnnouceBtn').removeClass('d-none');
    }

    function showLoader() {
        $('#loader').removeClass('d-none');
        $('#projectsTable').addClass('d-none');
        $('#newAnnouceBtn').addClass('d-none');
    }

    function initDataTable(data) {
        let roles = <?php echo json_encode($_SESSION['user']['roles']) ?>;
        let dataArr = [];
        data.forEach((item, index) => {
            data[index]['pin'] = `
            <div class="d-flex justify-content-center">
                <div class="form-check form-check-primary text-center">
                <input class="form-check-input" type="checkbox" onclick="pinAnnouce(${item.id}, ${item.pin})" ${(data[index]['pin']) == 1 ? "checked": ""} />
                </div>
            </div>
            `
            data[index]['start_date_th'] = thaiDateFormat(item.start_date);
            data[index]['activeSwitch'] = `
            <div class="d-flex justify-content-center">
            <label class="switch">
            <input type="checkbox" class="switch-input" onchange="annouceStateChange(${item.id}, ${item.status})"  ${(data[index]['status'] == 1 ? 'checked': '')} />
            <span class="switch-toggle-slider">
            <span class="switch-on"></span>
            <span class="switch-off"></span>
            </span>
            <span class="switch-label"></span>
            </label>
            </div>
            `
            data[index]['action'] = `
            <div class="d-flex justify-content-center">
            <button class="btn btn-label-primary btn-sm" onclick="editAnnouce('${item.id}', '${item.title}', '${item.detail}', '${item.start_date}', '${item.end_date}')"><i class="bx bx-edit"></i></button>
            <button class="btn btn-label-danger btn-sm ms-2" onclick="archiveAnnouce(${item.id})"><i class="bx bx-trash"></i></button>
            </div>
            `
            dataArr.push({
                id: item.id,
                title: item.title,
                detail: item.detail,
                start_date: item.start_date_th,
                pin: item.pin,
                activeSwitch: data[index]['activeSwitch'],
                action: data[index]['action'],
            });
            
        })

        //create columns from data
        var columns = [];
        for (var key in dataArr[0]) {
            columns.push({
                data: key
            });
        }

        let lastCol = columns.length - 1;

        $('#projectsTable').DataTable({
            responsive: true,
            columns: columns,
            data: dataArr,
            columnDefs: [{
                    targets: [0],
                    visible: false,
                    searchable: false
                },
                {
                    targets: [lastCol],
                    orderable: false,
                    searchable: false
                }
            ],
        });
    }

    function newAnnouce() {
        $('#newAnnouceModal').modal('show');
        $('#newAnnouceModalTitle').text('เพิ่มข่าวประกาศ');
    }

    function editAnnouce(id, title, detail,start_date, end_date) {
        $('#newAnnouceModal').modal('show');
        $('#newAnnouceModalTitle').text('แก้ไขข่าวประกาศ');

        $('#title').val(title);
        $('#detail').val(detail);
        $('#start_date').val(start_date);
        $('#end_date').val(end_date);

        let formData = new FormData();
        formData.append('id', id);
        formData.append('title', title);
        formData.append('detail', detail);
        formData.append('start_date', start_date);
        formData.append('end_date', end_date);
        axios
            .post('/annouce/updateAnnouce', formData)
            .then(res => {
                console.log(res.data);
            })
    }

    function archiveAnnouce(id) {
        //warning sweetalert2
        swal.fire({
            icon: 'warning',
            title: 'คุณต้องการลบข่าวประกาศนี้ใช่หรือไม่',
            text: 'ข้อมูลที่ถูกลบจะไม่สามารถกู้คืนได้',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            confirmButtonColor: '#ff3e1d',
            cancelButtonColor: '#696cff',

        }).then((result) => {
            if (result.isConfirmed) {
                //delete
                console.log(`delete ${id}`);
                let formData = new FormData();
                formData.append('id', id);
                axios
                .post('/annouce/archive_annouce', formData)
                .then((res) => {
                    console.log(res.data);
                    swal.fire({
                        icon: 'success',
                        title: 'ลบข่าวประกาศสำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })

            }
        })
    }

    function closeModal() {
        $('#newAnnouceModal').modal('hide');
    }

    function annouceStateChange(id, state) {
        let newState = !state;
        if (newState == true) newState = 1;
        if (newState == false) newState = 0;
        let formData = new FormData();
        formData.append('id', id);
        formData.append('status', newState);

        axios
            .post('/annouce/update_annouce_state', formData)
            .then(res => {})
    }

    function pinAnnouce(id, pin) {

        pin = !pin;
        (pin == true) ? pin = 1: pin = 0;
        let formData = new FormData();  
        formData.append('id', id);
        formData.append('pin', pin);

        axios
            .post('/annouce/update_annouce_pin', formData)
            .then(res => {})
    }

    function thaiDateFormat(date) {
        let thaiMonths = {
            "01": "ม.ค.",
            "02": "ก.พ.",
            "03": "มี.ค.",
            "04": "เม.ย.",
            "05": "พ.ค.",
            "06": "มิ.ย.",
            "07": "ก.ค.",
            "08": "ส.ค.",
            "09": "ก.ย.",
            "10": "ต.ค.",
            "11": "พ.ย.",
            "12": "ธ.ค."
        };

        date = date.split("-");
        let year = parseInt(date[0]) + 543;
        let month = thaiMonths[date[1]];
        let day = date[2];

        return `${day} ${month} ${year}`;
    }
</script>