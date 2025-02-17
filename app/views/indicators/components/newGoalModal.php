<?php
$yearCount = 2;
$startYear = Budgetyear::getBudgetyearThai() - $yearCount;
$currentYear = Budgetyear::getBudgetyearThai();
?>
<style>
    .swal2-container {
        z-index: 99999;
    }
</style>
<div class="modal fade" id="newGoal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body p-1">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <div class="row">
                    <div class="col-12">
                        <h5 class="fw-bold" id="newGoalTitle">เป้าหมายใหม่</h5>
                        <hr>
                        <form action="/indicators/newGoal" id="newGoalForm" method="POST">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <small class="text-light fw-medium d-block">ประเภทเป้าหมาย</small>
                                    <div class="form-check form-check-inline mt-4">
                                        <input class="form-check-input" type="radio" name="classificationInput" id="classificationInput1" value="major" checked />
                                        <label class="form-check-label" for="classificationInput1">เป้าหมายรวม</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="classificationInput" id="classificationInput2" value="minor" />
                                        <label class="form-check-label" for="classificationInput2">เป้าหมายย่อย</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="yearsSelect" class="form-label">ปีงบประมาณ</label>
                                    <select class="form-select" id="yearsSelect" name="yearsSelect">
                                        <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                                            <option value="<?= $year ?>"><?= $year ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="goal" class="form-label">เป้าหมาย</label>
                                    <input type="text" class="form-control" id="goalInput" name="goalInput" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="row text-center">
                                        <div class="col-12 col-md-6">
                                            <button class="btn btn-secondary" type="reset" data-bs-dismiss="modal">ยกเลิก</button>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <button class="btn btn-primary" type="sumbit" onclick="formSubmit()">บันทึก</button>
                                        </div>
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

<script>
    function newGoal() {
        $('#newGoal').modal('show');
    }

    function formSubmit() {
        event.preventDefault();
        let form = document.getElementById('newGoalForm');
        let validator = new FormValidator(form);
        if (validator.validate()) {
            $('#newGoal').modal('hide');
            swal.fire({
                icon: 'success',
                title: 'บันทึกข้อมูลสำเร็จ',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                form.submit();
            });
        }
    }

    function resetForm() {
        let invalids = document.querySelectorAll('.is-invalid');
        invalids.forEach((el) => {
            el.classList.remove('is-invalid');
        })
        document.getElementById('newGoalForm').reset();
    }
</script>