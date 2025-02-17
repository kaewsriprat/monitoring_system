<style>
    /* swal to top */
    .swal2-container {
        z-index: 99999;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            <i class="bx bx-home pb-1"></i>
        </a>
        / ข้อมูลผู้ใช้งาน
    </h5>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header bg-label-primary py-2 h4">
                    บัญชีผู้ใช้งาน
                </div>
                <div class="card-body pt-3">
                    <div class="row">
                        <!-- edit form -->
                        <div class="col-12">
                            <form action="/users/update" method="post" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="prefix" class="form-label">คำนำหน้า</label>
                                        <input type="text" class="form-control" id="prefix" name="prefix" value="<?= $user['prefix'] ?>" <?php echo (User::isAdmin()) ? '' : 'readonly disabled'; ?>>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="firstname" class="form-label">ชื่อ</label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?= $user['firstname'] ?>" <?php echo (User::isAdmin()) ? '' : 'readonly disabled'; ?>>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="lastname" class="form-label">นามสกุล</label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?= $user['lastname'] ?>" <?php echo (User::isAdmin()) ? '' : 'readonly disabled'; ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">อีเมล์</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" <?php echo (User::isAdmin()) ? '' : 'readonly disabled'; ?>>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="division" class="form-label">สังกัด</label>
                                        <input type="text" class="form-control" id="division" name="division" value="<?= $user['division_abbr'] ?>" <?php echo (User::isAdmin()) ? '' : 'readonly disabled'; ?>>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card bg-label-danger">
                <div class="card-header py-2 h4 pt-3">
                    <span class="text-danger">
                        เปลี่ยนรหัสผ่าน
                    </span>
                </div>
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-12">
                            <!-- change password form -->
                            <form action="/users/updatePassword" method="post" enctype="multipart/form-data" id="updatePasswordForm">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6 mb-3">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label" for="new_password">รหัสผ่านใหม่</label>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="new_password" class="form-control" name="new_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                            <span class="input-group-text cursor-pointer" id="passToggle"><i class="bx bx-hide"></i></span>
                                        </div>
                                        <span class="pt-3 fw-bold fs-4">ความแข็งแรงของรหัสผ่าน: <span class="fs-3" id="passwordStrength"></span></span>
                                    </div>
                                    <div class="col-12 col-md-6 mb-4">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label" for="confirm_password">ยืนยันรหัสผ่าน</label>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                            <span class="input-group-text cursor-pointer" id="confirmPassToggle"><i class="bx bx-hide"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 text-start mb-3">
                                        <span class="text-secondary fs-7">* คำแนะนำในการตั้งรหัสผ่าน</span>
                                        <span class="text-secondary fs-7">รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร, </span>
                                        <span class="text-secondary fs-7">ตัวอักษรภาษาอังกฤษ เล็ก-ใหญ่ (a-A), </span>
                                        <span class="text-secondary fs-7">ตัวเลข (0-9), </span>
                                        <span class="text-secondary fs-7">และอักขระพิเศษ (!@#$%^&*) </span>
                                    </div>
                                    <div class="col-12 col-md-md-6 text-end">
                                        <button type="submit" class="btn btn-danger" id="chgPassBtn">เปลี่ยนรหัสผ่าน</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const chgPassBtn = document.querySelector('#chgPassBtn');
    togglePassword();
    toggleConfirmPassword();
    submitChangePassword();

    document.getElementById('new_password').addEventListener('keyup', () => passwordStrength(document.getElementById('new_password').value));

    function submitChangePassword() {
        chgPassBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const new_password = document.getElementById('new_password');
            const confirm_password = document.getElementById('confirm_password')
            const updatePasswordForm = document.getElementById('updatePasswordForm');

            const new_password_value = new_password.value;
            const confirm_password_value = confirm_password.value;
            swal.fire({
                icon: 'warning',
                title: 'แน่ใจหรือไม่?',
                text: 'คุณต้องการเปลี่ยนรหัสผ่านใช่หรือไม่',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ไม่ใช่',
            }).then((result) => {
                if (result.isConfirmed) {
                    if (new_password_value == '' || confirm_password_value == '') {
                        swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'กรุณากรอกรหัสผ่าน',
                        })
                    } else if (new_password_value.length < 8) {
                        swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
                        })
                    } else if (new_password_value != confirm_password_value) {
                        confirm_password.focus();
                        confirm_password.classList.add('is-invalid');
                        swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'รหัสผ่านไม่ตรงกัน',
                        })
                    } else {
                        swal.fire({
                            icon: 'success',
                            title: 'เปลี่ยนรหัสผ่านสำเร็จ',
                            text: 'รหัสผ่านของคุณถูกเปลี่ยนแล้ว',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                updatePasswordForm.submit();
                            }
                        })
                    }
                }

            })
        });
    }

    function togglePassword() {
        const passToggle = document.getElementById('passToggle');
        passToggle.addEventListener('click', (e) => {
            const new_password = document.getElementById('new_password');
            const type = new_password.getAttribute('type') === 'password' ? 'text' : 'password';
            new_password.setAttribute('type', type);
            passToggle.classList.toggle('hide');
        });
    }

    function toggleConfirmPassword() {
        const confirmPassToggle = document.getElementById('confirmPassToggle');
        confirmPassToggle.addEventListener('click', (e) => {
            const confirm_password = document.getElementById('confirm_password');
            const type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
            confirm_password.setAttribute('type', type);
            confirmPassToggle.classList.toggle('hide');
        });
    }

    function passwordStrength(val) {
        //password must combine of number, alphabet, and special character, 8 character
        const password = document.getElementById('new_password');
        const password_value = val
        const password_strength = document.getElementById('passwordStrength');
        const strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])");
        const mediumRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])");
        const enoughRegex = new RegExp("(?=.{8,})");
        if (password_value.length == 0) {
            password_strength.innerHTML = '';
        } else if (false == enoughRegex.test(password_value)) {
            password_strength.innerHTML = 'อย่างน้อย 8 ตัวอักษร';
            password_strength.classList.add('text-danger');
            password_strength.classList.remove('text-success');
            password_strength.classList.remove('text-warning');
            password.classList.remove('is-valid');
            password.classList.add('is-invalid');
        } else if (strongRegex.test(password_value)) {
            password_strength.innerHTML = 'แข็งแรง';
            password_strength.classList.add('text-success');
            password_strength.classList.remove('text-warning');
            password_strength.classList.remove('text-danger');
            password.classList.remove('is-invalid');
            password.classList.add('is-valid');
        } else if (mediumRegex.test(password_value)) {
            password_strength.innerHTML = 'ปานกลาง';
            password_strength.classList.add('text-warning');
            password_strength.classList.remove('text-success');
            password_strength.classList.remove('text-danger');
            password.classList.remove('is-invalid');
            password.classList.add('is-valid');
        } else {
            password_strength.innerHTML = 'ไม่ปลอดภัย';
            password_strength.classList.add('text-danger');
            password_strength.classList.remove('text-success');
            password_strength.classList.remove('text-warning');
            password.classList.remove('is-valid');
            password.classList.add('is-invalid');
        }

    }
</script>