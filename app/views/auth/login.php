<div class="row justify-content-center mt-5">
    <div class="col-12 col-md-6">
        <div class="card w-100">
            <div class="card-body">

                <?php
                if (!$dept) {
                    echo '<p class="text-center fs-4">' . APP_TITLE_TH . '</p>';
                } else {
                    echo '<p class="text-center fs-4">' . APP_TITLE_TH_BICT . '</p>';
                }
                ?>

                <div class="mt-3 text-center">
                    <p class="h2">เข้าสู่ระบบ</p>
                </div>

                <div class="row mt-4">
                    <form class="form" action="/auth/login" method="POST">
                        <div class="col-12 mb-3">
                            <label for="emailInput" class="form-label">Username</label>
                            <input type="text" class="form-control" id="emailInput" name="emailInput" placeholder="Email" autofocus>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="passwordInput">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="passwordInput" class="form-control" name="passwordInput" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide" id="showPassIcon"></i></span>
                            </div>
                        </div>

                        <!-- if error -->
                        <?php if (isset($data['error'])) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo "Email หรือรหัสผ่าน ผิดพลาด"; ?>
                            </div>
                        <?php endif; ?>

                        <div class="pt-4 mb-3 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-lg" name="signin">เข้าสู่ระบบ</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>


<script>
     localStorage.setItem('activeMenuId', 1);
    // Show/Hide password
    const showPassIcon = document.getElementById('showPassIcon');
    const passwordInput = document.getElementById('passwordInput');
    showPassIcon.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showPassIcon.classList.remove('bx-hide');
            showPassIcon.classList.add('bx-show');
        } else {
            passwordInput.type = 'password';
            showPassIcon.classList.remove('bx-show');
            showPassIcon.classList.add('bx-hide');
        }
    });
</script>