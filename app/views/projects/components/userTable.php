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

<div class="card-datatable table-responsive text-wrap" id="projectTableWrapper">
    <table class="datatables-ajax table " id="projectsTable">
        <thead>
            <tr>
                <th style="max-width:5%;">#</th>
                <th>ชื่อโครงการ</th>
                <th style="max-width:10%;">ปีงบประมาณ</th>
                <th>Q1</th>
                <th>Q2</th>
                <th>Q3</th>
                <th>Q4</th>
                <th style="max-width:10%;">นอก/ในแผน</th>
                <th>รายละเอียด</th>
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

        const url = `/projects/userDatatableProjectsAPI/`;

        dataTable = $('#projectsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                type: 'POST',
                data: function(d) {
                    d.yearsSelect = document.getElementById('yearsSelect').value;
                }
            },
            columns: [{
                    data: "project_ID"
                },
                {
                    data: "project_name"
                },
                {
                    data: "project_year"
                },
                {
                    data: "q1_file_path",
                    render: function(data, type, row) {
                        return badge(data, row, 1);
                    }
                },
                {
                    data: "q2_file_path",
                    render: function(data, type, row) {
                        return badge(data, row, 2);
                    }
                },
                {
                    data: "q3_file_path",
                    render: function(data, type, row) {
                        return badge(data, row, 3);
                    }
                },
                {
                    data: "q4_file_path",
                    render: function(data, type, row) {
                        return badge(data, row, 4);
                    }
                },
                {
                    data: "planned",
                    render: function(data, type, row) {
                        return plannedProject(data);
                    }
                },
                {
                    data: "project_ID",
                    render: function(data, type, row) {
                        return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="projectDetailModal(${data})"><i class="bx bx-info-circle text-info pe-2"></i>รายละเอียด</a></li>
                                <li><a class="dropdown-item" href="/projects/update/${data}"><i class="bx bx-pencil text-warning pe-2"></i>ปรับปรุง</a></li>
                                <li><button class="dropdown-item ${row.planned == 1 ? 'disabled' : ''}" onclick="deleteProject(${data}, '${row.project_name}')"><i class="bx bx-trash ${row.planned == 1 ? 'text-secondary' : 'text-danger'} pe-2"></i>ลบโครงการ</ิ></li>
                            </ul>
                        </div>
                        `;
                    }
                }
            ],
            columnDefs: [{
                    className: 'text-center',
                    targets: [0, 2, 3, 4, 5, 6, 7, -1]
                },
                {
                    className: 'fs-7',
                    targets: [0]
                },
                {
                    orderable: false,
                    targets: [2, 3, 4, 5, 6, -1]
                }
            ],
        })
    }

    function badge(filePath, row, quarter) {
        if (filePath === null) {
            let data = {
                project_ID: row.project_ID,
                project_name: row.project_name,
                project_year: row.project_year
            }

            return `<button class="btn btn-icon" data-file='${JSON.stringify(data)}' onclick="uploadModal(this, ${quarter})"><i class="bx bx-upload text-muted fs-5"></i></button>`;
        } else {
            row.project_name = (row.project_name === '') ? 'ไม่ระบุ' : row.project_name;
            return `
            <div class="btn-group">
                <button type="button" class="btn btn-label-dark btn-sm btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/${filePath}"><i class="bx bx-download text-info pe-2"></i>ดาวน์โหลด</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="deleteFile(${row.project_ID},'${row.project_name}',${quarter})"><i class="bx bx-trash text-danger pe-2"></i>ลบไฟล์</a></li>
                </ul>
            </div>
            `;
        }
    }

    function plannedProject(status) {
        status = parseInt(status);
        let badgeColor = '';
        let badgeText = '';
        switch (status) {
            case 0:
                badgeColor = 'bg-label-secondary';
                badgeText = 'นอกแผน';
                break;
            case 1:
                badgeColor = 'bg-label-success';
                badgeText = 'ในแผน';
                break;
            default:
                badgeColor = 'bg-label-secondary';
                badgeText = 'นอกแผน';
                break;
        }

        return `<span class="badge ${badgeColor}">${badgeText}</span>`;
    }

    function deleteFile(projectID, projectName, quarter) {
        swal.fire({
            title: "คุณต้องการลบรายงานนี้ใช่หรือไม่?",
            html: `<p class="fw-6">${projectName} ในไตรมาสที่ ${quarter}</p>
            <span class="text-danger">หากลบไฟล์แล้วจะไม่สามารถกู้คืนได้อีก</span>`,
            icon: "warning",
            showConfirmButton: true,
            showCancelButton: true,
            customClass: {
                confirmButton: "btn swal-label-danger me-4",
                cancelButton: "btn btn-primary ms-4"
            },
            confirmButtonText: 'ใช่, ลบรายงานนี้',
            cancelButtonText: 'ไม่, ยกเลิก',
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                const url = '/projects/deleteFileFromProject/';
                const payload = projectID + '/' + quarter;
                axios
                    .delete(url + payload)
                    .then((res) => {
                        if (res.data === true) {
                            swal.fire({
                                title: "ลบไฟล์สำเร็จ",
                                icon: "success",
                                showConfirmButton: true,
                                confirmButtonText: "ตกลง",
                                timer: 1500
                            }).then(() => {
                                tableReload();
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

    async function projectDetailModal(id) {
        $('#projectDetailModal').modal('show');
        const projectDetail = await getProjectDetail(id);
        document.getElementById('projectTitle').innerText = projectDetail.project_name;
        document.getElementById('total_budget').innerText = projectDetail.total_budget;
        document.getElementById('projectBudgetYear').innerText = projectDetail.project_year;
        document.getElementById('division_name').innerText = projectDetail.division_name;
        document.getElementById('strategy_name').innerText = projectDetail.strategy_name;

        document.getElementById('reportedTable').getElementsByTagName('tbody')[0].innerHTML = '';
        let html = '';
        const quarters = 4;
        for (let i = 1; i <= quarters; i++) {
            let reported = projectDetail['q' + i + '_file_path'] === null ? '<i class="bx bx-x text-danger fs-4"></i>' : '<i class="bx bx-check text-success fs-4"></i>';
            let date = projectDetail['q' + i + '_file_update_date'] === null ? 'ยังไม่รายงาน' : thaiDateFormate(projectDetail['q' + i + '_file_update_date']);
            html += `
            <tr>
                <td>ไตรมาสที่ ${i}</td>
                <td class="text-center">${reported}</td>
                <td>${date}</td>
            </tr>
            `;
        }

        document.getElementById('reportedTable').getElementsByTagName('tbody')[0].innerHTML = html;
    }

    async function getProjectDetail(id) {
        const url = '/projects/getProjectById/' + id;
        const res = await axios.get(url)
        return res.data;
    }

    async function deleteProject(id, projectName) {
        swal.fire({
            title: "คุณต้องการลบโครงการนี้ใช่หรือไม่?",
            html: `<p class="fw-6">โครงการ: ${projectName}</p>
            <span class="text-danger">หากลบโครงการแล้วจะไม่สามารถกู้คืนได้อีก</span>`,
            icon: "warning",
            showConfirmButton: true,
            showCancelButton: true,
            customClass: {
                confirmButton: "btn swal-label-danger me-4",
                cancelButton: "btn btn-primary ms-4"
            },
            confirmButtonText: 'ใช่, ลบโครงการนี้',
            cancelButtonText: 'ไม่, ยกเลิก',
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                const url = '/projects/deleteProject/';
                axios
                    .delete(url + id)
                    .then((res) => {
                        if (res.data === true) {
                            swal.fire({
                                title: "ลบโครงการสำเร็จ",
                                icon: "success",
                                showConfirmButton: true,
                                confirmButtonText: "ตกลง",
                                timer: 1500
                            }).then(() => {
                                tableReload();
                            });
                        } else {
                            swal.fire({
                                title: "ลบโครงการไม่สำเร็จ",
                                icon: "error",
                                showConfirmButton: true,
                                confirmButtonText: "ตกลง",
                                timer: 1500
                            });
                        }
                    })
            }
        })
        
    }

    function tableYearFilter() {
        event.preventDefault();
        tableReload();
    }

    function tableReload() {
        dataTable.ajax.reload();
    }

    function thaiDateFormate(date) {
        // 2024-11-12 09:24:35 => 12 พฤศจิกายน 2567
        const months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        const dateArr = date.split(' ')[0].split('-');
        const day = dateArr[2];
        const month = months[parseInt(dateArr[1]) - 1];
        const year = parseInt(dateArr[0]) + 543;
        return `${day} ${month} ${year}`;
    }
</script>