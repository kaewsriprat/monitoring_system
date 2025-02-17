<style>
    /* swal in front */
    .swal2-container {
        z-index: 10000;
    }
</style>

<?php

?>

<div class=" container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        / ไตรมาส
    </h5>
    <hr>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-warning py-2 h4">
                    ไตรมาส
                </div>
                <!-- loader -->
                <div class="text-center mt-4" id="loader">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="text-end">
                    <button class="btn btn-label-warning mt-3 me-3  d-none" id="newQuarterBtn" onclick="newQuarter()"><i class="bx bx-plus"></i> ไตรมาสใหม่</button>
                </div>
                <div class="card-datatable text-nowrap">
                    <table class="datatables-ajax table d-none" id="quarterTable">
                        <thead>
                            <tr>
                                <th class="fw-bold">id</th>
                                <th class="fw-bold">ปีงบประมาณ</th>
                                <th class="fw-bold">ไตรมาส</th>
                                <th class="fw-bold">เริ่ม</th>
                                <th class="fw-bold">สิ้นสุด</th>
                                <th class="fw-bold">สถานะ</th>
                                <th class="fw-bold">แก้ไข</th>
                                <th class="fw-bold">ลบ</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- New Quarter Modal -->
    <div class="modal fade" id="newQuarter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-1">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="row">
                        <div class="col-12">
                            <h5 class="fw-bold" id="newQuarterTitle"></h5>
                            <hr>
                            <form action="/quarter/quater_post" id="newQuaterForm" method="POST">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="quarterSelect" class="form-label fs-5 fw-bold text-primary">ไตรมาสที่</label>
                                            <select class="form-select" name="quarterSelect" id="quarterSelect" required>
                                                <option value="" selected disabled>กรุณาเลือก</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="BudgetyearSelect" class="form-label fs-5 fw-bold">ปีงบประมาณ</label>
                                            <!-- form select -->
                                            <?php
                                            $year = date("Y") + 543;
                                            ?>
                                            <select class="form-select" name="BudgetyearSelect" id="BudgetyearSelect" required>
                                                <option value="" selected disabled>กรุณาเลือก</option>
                                                <option value="<?php echo $year - 1 ?>"><?php echo $year - 1 ?></option>
                                                <option value="<?php echo $year ?>"><?php echo $year ?></option>
                                                <option value="<?php echo $year + 1 ?>"><?php echo $year + 1 ?></option>
                                            </select>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="start_date" class="form-label fs-5 fw-bold text-success">วันเริ่ม</label>
                                            <input type="text" class="form-control" placeholder="วันที่เริ่ม" id="start_date" name="start_date" data-provide="datepicker" data-date-language="th-th" required>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="end_date" class="form-label fs-5 fw-bold text-danger">วันสิ้นสุด</label>
                                            <input type="text" class="form-control" placeholder="วันสิ้นสุด" id="end_date" name="end_date" data-provide="datepicker" data-date-language="th-th" required>
                                        </div>

                                        <!-- <div class="col-12 mb-3">
                                            <label class="switch">
                                                <input type="checkbox" class="switch-input" onchange="" />
                                                <span class="switch-toggle-slider">
                                                    <span class="switch-on"></span>
                                                    <span class="switch-off bg-danger"></span>
                                                </span>
                                                <span class="switch-label"></span>
                                            </label>
                                        </div> -->

                                        <div class="mb-3 text-center" id="submitDiv">
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
    <!-- New Quarter Modal -->

</div>

<script>
    $(document).ready(function() {
        showLoader();
        initDatePicker();
        setTimeout(() => {
                hideLoader();
                initDataTable(<?php echo json_encode($quarters); ?>)
            },
            500);
    })

    function initDatePicker() {
        $.fn.datepicker.defaults.autoclose = true;
        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'dd-mm-yyyy'
        });
    }

    function hideLoader() {
        $('#loader').addClass('d-none');
        $('#quarterTable').removeClass('d-none');
        $('#newQuarterBtn').removeClass('d-none');
    }

    function showLoader() {
        $('#loader').removeClass('d-none');
        $('#quarterTable').addClass('d-none');
        $('#newQuarterBtn').addClass('d-none');
    }

    function initDataTable(data) {
        let roles = <?php echo json_encode($_SESSION['user']['roles']) ?>;
        data.forEach((item, index) => {
            data[index]['start_date_th'] = thaiDateFormat(item.start_date);
            data[index]['end_date_th'] = thaiDateFormat(item.end_date);

            data[index]['status'] = `
            <div class="d-flex justify-content-center">
                ${quarterStatusBadge(item.end_date)}
            </div>
            `
            data[index]['action'] = `
            <div class="d-flex justify-content-center">
                <button class="btn btn-label-primary btn-sm" onclick="editQuarter(${item.budget_year},${item.quarter},'${convertDateToThFormat(item.start_date)}','${convertDateToThFormat(item.end_date)}')"><i class="bx bx-edit"></i></button>
            </div>
            `

            data[index]['delete'] = `
            <div class="d-flex justify-content-center">
                <button class="btn btn-label-danger btn-sm" onclick="deleteQuarter(${item.id})"><i class="bx bx-trash"></i></button>
            </div>
            `

            delete data[index]['start_date'];
            delete data[index]['end_date'];
            delete data[index]['active'];

        })

        //create columns from data
        var columns = [];
        for (var key in data[0]) {
            columns.push({
                data: key
            });
        }

        $('#quarterTable').DataTable({
            responsive: true,
            columns: columns,
            data: data,
            columnDefs: [
            {
                targets: [0],
                visible: false
            },    
            {
                targets: [0, 1, 2, 3, 4, 5],
                className: 'text-center'
            }, {
                targets: [4, 5],
                orderable: false
            }
        ]

        });
    }

    function newQuarter() {
        //reset form 
        document.getElementById("newQuaterForm").reset();
        $('#newQuarter').modal('show');
        $('#newQuarterTitle').text('ไตรมาสใหม่');
    }

    function editQuarter(year, quarter, start_date, end_date) {
        $('#newQuarter').modal('show');
        $('#newQuarterTitle').text('แก้ไขไตรมาส');
        $('#quarterSelect').val(quarter);
        $('#BudgetyearSelect').val(year);
        $('#start_date').val(start_date);
        $('#end_date').val(end_date);
    }

    function closeModal() {
        $('#newQuarter').modal('hide');
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

    function convertDateToThFormat(date) {
        //convert from yyyy-mm-dd to dd/mm/yyyy
        date = date.split("-");
        let year = parseInt(date[0]) + 543;
        let month = date[1];
        let day = date[2];

        return `${day}/${month}/${year}`;
    }

    function quarterStatusBadge(quarterEndDate) {
        //check if current date less then quarter end date
        let today = new Date();
        let end_date = new Date(quarterEndDate);
        if (today <= end_date) {
            return `<span class="badge bg-success">เปิดรับรายงาน</span>`;
        } else {
            return `<span class="badge bg-danger">สิ้นสุด</span>`;
        }
    }

    function deleteQuarter(id) {
        const url = "/quarter/delete/" + id;
        Swal.fire({
            title: 'ลบไตรมาส',
            text: "คุณต้องการลบไตรมาสนี้ใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response == 0) {
                            Swal.fire(
                                'ลบไตรมาส',
                                'ลบไตรมาสเรียบร้อย',
                                'success'
                            ).then(() => {
                                location.reload();
                            })
                        } else {
                            Swal.fire(
                                'ลบไตรมาส',
                                'เกิดข้อผิดพลาดในการลบไตรมาส',
                                'error'
                            )
                        }
                    }
                });
            }
        })
    }
</script>