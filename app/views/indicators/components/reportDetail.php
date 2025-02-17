<?php
$yearCount = 2;
$startYear = Budgetyear::getBudgetyearThai() - $yearCount;
$currentYear = Budgetyear::getBudgetyearThai();
?>

<div class="modal fade" id="reportDetail" tabindex="-1" aria-hidden="false">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body p-1">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
            </div>
        </div>
    </div>
</div>

<script>
    let goalId = null;

    function reportDetail(id) {
        goalId = id;
        $('#reportDetail').modal('show');
        
    }

</script>