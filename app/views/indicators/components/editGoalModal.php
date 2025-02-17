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
<div class="modal fade" id="editGoal" tabindex="-1" aria-hidden="false">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body p-1">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="row">
                    <div class="col-12">
                        <h5 class="fw-bold" id="editGoalTitle">แก้ไขเป้าหมาย</h5>
                        <hr>
                        <form id="editGoalForm" method="PUT">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <small class="text-light fw-medium d-block">ประเภทเป้าหมาย</small>
                                    <div class="form-check form-check-inline mt-4">
                                        <input class="form-check-input" type="radio" name="editClassificationInput" id="editClassificationInput1" value="major" />
                                        <label class="form-check-label" for="editClassificationInput1">เป้าหมายรวม</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="editClassificationInput" id="editClassificationInput2" value="minor" />
                                        <label class="form-check-label" for="editClassificationInput2">เป้าหมายย่อย</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="editYearSelect" class="form-label">ปีงบประมาณ</label>
                                    <select class="form-select" id="editYearSelect" name="editYearSelect">
                                        <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                                            <option value="<?= $year ?>"><?= $year ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="goal" class="form-label">เป้าหมาย</label>
                                    <input type="text" class="form-control" id="editGoalInput" name="editGoalInput" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="row text-center">
                                        <div class="col-12 col-md-6">
                                            <button class="btn btn-secondary" type="reset" data-bs-dismiss="modal">ยกเลิก</button>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <button class="btn btn-primary" onclick="editFormSubmit()">บันทึก</button>
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
    let goalId = null;

    function editGoal(id) {
        goalId = id;
        $('#editGoal').modal('show');
        let url = '/indicators/getGoalById/' + id;
        axios
            .put(url)
            .then((res) => {
                let data = res.data;
                document.getElementById('editGoalInput').value = data.title;
                document.getElementById('editYearSelect').value = data.year;
                document.getElementById('editClassificationInput1').checked = data.classification == 'major';
                document.getElementById('editClassificationInput2').checked = data.classification == 'minor';
            })
    }

    function editFormSubmit() {
        event.preventDefault();
        let form = document.getElementById('editGoalForm');
        let validator = new FormValidator(form);
        if (validator.validate()) {
            let url = '/indicators/updateGoal/' + goalId;
            let formData = new FormData(form);
            
            axios
                .post(url, formData)
                .then((res) => {
                    if (res.data.status == 'error') {
                        swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: res.data.message,
                        });
                        return;
                    }
                    $('#editGoal').modal('hide');
                    swal.fire({
                        icon: 'success',
                        title: 'บันทึกข้อมูลสำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                })
        }
    }
</script>