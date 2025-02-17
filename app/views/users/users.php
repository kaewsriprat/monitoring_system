<div class="container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            <i class="bx bx-home pb-1"></i>
        </a>
        / ผู้ใช้งาน
    </h5>
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-label-primary py-2">
                    <span class="fw-bold fs-5">บัญชีผู้ใช้งาน</span>
                </div>
                <!-- spinner -->
                <div class="text-center my-3" id="spinnerWrapper">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="row d-none" id="tableWrapper">
                    <div class="col-12 text-center text-md-end">
                        <button class="btn btn-label-primary mt-3 me-3" onclick="newUser()"><i class="bx bx-plus pe-2"></i>เพิ่มผู้ใช้งาน</button>
                    </div>
                    <div class="col-12">
                        <div class="card-datatable table-responsive text-nowrap">
                            <table class="datatables-ajax table " id="usersTable">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>ชื่อ</th>
                                        <th>Email</th>
                                        <th>สังกัด</th>
                                        <th>บทบาท</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-label-primary py-2">
                    <span class="fw-bold fs-5" id="newUserModalLabel">เพิ่มผู้ใช้งาน</span>
                    <button type="button" class="btn-close bg-white mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="newUserForm">
                <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <!-- text input prefix firstname lastname -->
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label for="prefix" class="form-label">คำนำหน้า</label>
                                        <input type="text" class="form-control" id="prefix" name="prefix" placeholder="คำนำหน้า">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="firstname" class="form-label">ชื่อ</label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="ชื่อ">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="lastname" class="form-label">นามสกุล</label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="นามสกุล">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    dataTable = null;

    document.addEventListener('DOMContentLoaded', async function() {
        const data = await getUsersByDivision();
        initDataTable(data);
        showSpinner(false);
        showTable(true);
    });

    function initDataTable(data) {
        datatableDestroy();
        data.forEach((item, index) => {
            (item.prefix === null) ? item.prefix = '': item.prefix;
            item.fullname = `${item.prefix}${item.firstname} ${item.lastname}`;
        });

        dataTable = $('#usersTable').DataTable({
            data: data,
            columns: [{
                    data: 'id'
                },
                {
                    data: 'fullname'
                },
                {
                    data: 'email'
                },
                {
                    data: 'division_abbr'
                },
                {
                    data: 'roles',
                    render: (data, type, row) => rolesBadge(data)
                },
                {
                    data: 'active',
                    render: (data, type, row) => activeBadge(data)
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded fs-5"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-info" href="/users/profile/${data}"><i class="bx bx-edit pe-2"></i>ปรับปรุง</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item text-danger" href="javascript:;"><i class="bx bx-x pe-2"></i>ปิดการใช้งาน</a></li>
                            </ul>
                        </div>
                        `
                    }
                }
            ],
            columnDefs: [{
                targets: [0],
                visible: false
            }]
        });
    }

    function datatableDestroy() {
        if (dataTable) {
            dataTable.destroy();
        }
    }

    async function getUsersByDivision() {
        const url = '/users/getUsersByDivision/';
        let response = await axios.get(url);
        return response.data;
    }

    function showSpinner(status) {
        if (status) {
            document.querySelector('#spinnerWrapper').classList.remove('d-none');
        } else {
            document.querySelector('#spinnerWrapper').classList.add('d-none');
        }
    }

    function showTable(status) {
        if (status) {
            document.querySelector('#tableWrapper').classList.remove('d-none');
        } else {
            document.querySelector('#tableWrapper').classList.add('d-none');
        }
    }

    function activeBadge(status) {
        if (status == 1) {
            return `<span class="badge bg-success">Active</span>`;
        } else {
            return `<span class="badge bg-danger">Inactive</span>`;
        }
    }

    function rolesBadge(roles) {
        const roleNames = ['1', '2', '3']
        const badgeHtml = [
            '<span class="badge bg-primary">Admin</span>',
            '<span class="badge bg-secondary">User</span>',
            '<span class="badge bg-info">Director</span>'
        ]
        return roles.split(',').map(role => badgeHtml[role - 1]).join(' ');
    }

    function newUser() {
        $('#newUserModal').modal('show');
    }
</script>