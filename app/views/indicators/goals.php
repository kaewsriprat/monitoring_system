<div class=" container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->
    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        / จัดการเป้าหมาย
    </h5>

    <div class="row">
        <div class="col-12">
            <?php include 'components/goalsTable.php'; ?>
        </div>
    </div>
</div>

<?php include 'components/newGoalModal.php'; ?>
<?php include 'components/editGoalModal.php'; ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initDatatable();
    })
</script>