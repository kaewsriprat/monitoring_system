<style>
    .pointer {
        cursor: pointer;
    }
</style>
<div class="modal fade" id="indicatorDetailModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="indicatorDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple" role="document">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body p-1">
                
                <div class="row">
                    <p class="h2 mb-4">รายละเอียดตัวชี้วัด</p>
                    <hr>
                    
                    <div class="col-12 fs-5 mb-2">
                        <span class="text-muted my-1">ชื่อตัวชี้วัด</span>
                        <span id="indicatorTitle">indicator title</span>
                    </div>
                    <div class="col-12 fs-5 mb-2 col-md-6">
                        <span class="text-muted my-1">เป้าหมาย: </span><span id="goalTitle">goal title</span>
                    </div>
                    <div class="col-12 fs-5 mb-2 col-md-6">
                        <span class="text-muted my-1">ปีงบประมาณ: </span><span id="goalYear">goal year</span>
                    </div>
                    <div class="col-12 fs-5 mb-2">
                        <span class="text-muted my-1">ค่าเป้าหมาย: </span><span id="indicatorTarget">indicator target</span>
                    </div>
                    <div class="col-12 fs-5 mb-2">
                        <span class="text-muted my-1">คำอธิบายค่าเป้าหมาย: </span><span id="targetDetail">target detail</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <p class="fs-5 mb-0">หน่วยงานที่รับผิดชอบ <span class="text-muted fs-6">* คลิกที่ตารางเพื่อดูรายละเอียดหน่วยงาน</span></p>
                        <div class="table-responsive">
                            <table class="table table-hover pointer" id="divisionsTable">
                                <thead class="table-light">
                                    <tr id="divRow">
                                        <th>หน่วยงาน</th>
                                        <th>โครงการ</th>
                                        <th class="text-center">ค่าเป้าหมาย</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer px-1">
                <button type="button" class="btn btn-secondary w-25" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<?php include "divDetailModal.php"; ?>

<script>
    async function showIndDetailModal(id) {
        clearModal()
        $('#indicatorDetailModal').modal('show');
        let indicatorData = await fetchIndicatorData(id)
        indicatorData = indicatorData[id]
        // console.log(indicatorData)
        const indicators = indicatorData.indicators[0] ?? []

        document.getElementById('indicatorTitle').innerHTML = indicators.indicator_title
        document.getElementById('goalTitle').innerHTML = indicatorData.goal_title
        document.getElementById('goalYear').innerHTML = indicatorData.goal_year
        document.getElementById('indicatorTarget').innerHTML = indicators.target
        document.getElementById('targetDetail').innerHTML = indicators.target_detail

        initTable(indicatorData)
    }

    async function fetchIndicatorData(id) {
        return await axios
            .get(`/indicators/getFullIndicatorsReportByIndicatorId/${id}`)
            .then((res) => {
                console.log(res.data)
                return res.data
            })
            .catch((err) => {
                return err
            })
    }

    function initTable(data) {
        data = data.indicators;
        const table = document.getElementById('divisionsTable').getElementsByTagName('tbody')[0]
        if(data[0].indicator_id == null) {
            const row = table.insertRow()
            const cell1 = row.insertCell(0)
            cell1.colSpan = 3
            cell1.style.textAlign = 'center'
            cell1.innerHTML = 'ยังไม่มีหน่วยงานรับผิดชอบ'
            return;
        }
        data.forEach((element) => {
            const row = table.insertRow()
            // onclick
            row.onclick = () => {
                divDetail(element)
            }
            const cell1 = row.insertCell(0)
            const cell2 = row.insertCell(1)
            const cell3 = row.insertCell(2)
            cell1.innerHTML = element.divisions.division_name
            cell2.innerHTML = element.divisions.project
            cell3.innerHTML = element.divisions.target
            cell3.style.textAlign = 'center'
        })

    }

    function clearModal() {
        document.getElementById('indicatorTitle').innerHTML = ''
        document.getElementById('goalTitle').innerHTML = ''
        document.getElementById('goalYear').innerHTML = ''
        document.getElementById('indicatorTarget').innerHTML = ''
        document.getElementById('targetDetail').innerHTML = ''
        clearTable();
    }

    function clearTable() {
        const table = document.getElementById('divisionsTable').getElementsByTagName('tbody')[0]
        table.innerHTML = ''
    }

    function clearDivDetailTable() {
        const table = document.getElementById('divDetailTable').getElementsByTagName('tbody')[0]
        table.innerHTML = ''
    }

    function divDetail(data) {
        divDetailModal(data.indicator_id)
    }
</script>