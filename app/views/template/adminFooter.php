<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-center py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            © <script>
                document.write(new Date().getFullYear())
            </script>
            , made with ❤️ by
            <a href="https://www.bict.moe.go.th" target="_blank">
                <img src="/assets/img/bict_logo.png" alt="BICT" height="25">
            </a>
            ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร
            สำนักงานปลัดกระทรวงศึกษาธิการ
        </div>

    </div>
</footer>
<!-- / Footer -->

</div>

</div>

</div>

</div>

<?php
include 'js_import.php';
?>
<script>
    const layoutWrapper = document.querySelector('.layout-wrapper');
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'aria-hidden') {
                // remove aria-hidden attribute
                document.querySelector('.layout-wrapper').removeAttribute('aria-hidden');
            }
        });
    });

    observer.observe(layoutWrapper, {
        attributes: true,
    });
</script>
</body>

</html>