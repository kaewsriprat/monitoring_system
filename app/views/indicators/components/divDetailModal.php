<div class="modal fade" id="divDetailModal" tabindex="-1" aria-hidden="false">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body p-1">
                <div class="row">
                    <div class="col-12" id="contentDiv">
                        <p class="h2 mb-4">รายละเอียดหน่วยงาน</p>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-12 fs-5"><span class="text-muted">หน่วยงานผู้รับผิดชอบ:</span><br><span class="fs-4" id="division_name"></span></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12 fs-5"><span class="text-muted">ชื่อตัวชี้วัด:</span> <span id="title"></span></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 fs-5"><span class="text-muted">เป้าหมาย:</span> <span id="goal_title"></span></div>
                            <div class="col-md-6 fs-5"><span class="text-muted">ประเภท:</span> <span id="classification"></span></div>
                        </div>


                        <!-- <div class="row mb-3">
                            <div class="col-md-4"><span class="text-muted">Division ID:</span> <span id="division_id"></span></div>
                            <div class="col-md-4"><span class="text-muted">ID:</span> <span id="id"></span></div>
                            <div class="col-md-4"><span class="text-muted">Indicator ID:</span> <span id="indicatorId"></span></div>
                        </div> -->

                        <div class="row mb-3">
                            <!-- <div class="col-md-3 fs-5"><span class="text-muted">หมายเลขโครงการ: </span> <span id="project_id"></span></div> -->
                            <div class="col-md-12 fs-5"><span class="text-muted">โครงการ: </span> <span id="project_name"></span></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fs-5"><span class="text-muted">ปีงบประมาณ:</span> <span id="year"></span></div>
                            <div class="col-md-4 fs-5"><span class="text-muted">ค่าเป้าหมาย:</span> <span id="target"></span></div>
                            <div class="col-md-4 fs-5"><span class="text-muted">หน่วยวัด:</span> <span id="target_detail"></span></div>
                        </div>

                        <h4 class="mb-3">คะแนนรายไตรมาส</h4>

                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="table-light">
                                    <tr>
                                        <th>ไตรมาส</th>
                                        <th class="text-center">ยืนยันคะแนน</th>
                                        <th class="text-center">วันที่รายงาน</th>
                                        <th class="text-center">คะแนน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>ไตรมาสที่ 1</td>
                                        <td class="text-center" id="q1_approve"></td>
                                        <td id="q1_report_date"></td>
                                        <td class="text-center fw-bold fs-5" id="q1_score"></td>
                                    </tr>
                                    <tr>
                                        <td>ไตรมาสที่ 2</td>
                                        <td class="text-center" id="q2_approve"></td>
                                        <td id="q2_report_date"></td>
                                        <td class="text-center fw-bold fs-5" id="q2_score"></td>
                                    </tr>
                                    <tr>
                                        <td>ไตรมาสที่ 3</td>
                                        <td class="text-center" id="q3_approve"></td>
                                        <td id="q3_report_date"></td>
                                        <td class="text-center fw-bold fs-5" id="q3_score"></td>
                                    </tr>
                                    <tr>
                                        <td>ไตรมาสที่ 4</td>
                                        <td class="text-center" id="q4_approve"></td>
                                        <td id="q4_report_date"></td>
                                        <td class="text-center fw-bold fs-5" id="q4_score"></td>
                                    </tr>
                                    <tr class="table-light">
                                        <td class="text-end fw-bold" colspan="3">รวม</td>
                                        <td class="text-center fw-bold fs-5" id="total_score"></td>
                                    </tr>
                                </tbody>
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

<script>
    async function divDetailModal(id) {
        $('#divDetailModal').modal('show');
        const data = await fetchReportById(id);
        appendCard(data);
    }

    async function fetchReportById(id) {
        if (!id) return;
        const url = `/indicators/getReportById/${id}`;
        return await axios.get(url)
            .then((res) => res.data)
            .catch((err) => console.error(err));
    }

    function appendCard(data) {
        var keys = Object.keys(data);
        var values = Object.values(data);
        let sum = 0;
        for (var i = 0; i < keys.length; i++) {
            if (document.getElementById(keys[i]) === null) {
                continue;
            }
            //classification
            if (keys[i].includes('classification')) {
                document.getElementById(keys[i]).innerHTML = classificationBadge(values[i]);
                continue;
            }
            //approve
            if (keys[i].includes('approve')) {
                document.getElementById(keys[i]).innerHTML = values[i] ? approveBadge(values[i]) : approveBadge(0);
                continue;
            }
            //date
            if (keys[i].includes('date')) {
                document.getElementById(keys[i]).textContent = thaiDate(values[i]);
                document.getElementById(keys[i]).classList.add('text-center');
                continue;
            }
            document.getElementById(keys[i]).textContent = values[i];
            if (keys[i].includes('score')) {
                sum += parseInt(values[i]);
            }
        }
        document.getElementById('total_score').textContent = `${sum}/${data.target}`;

    }

    function approveBadge(status) {
        if (status == 1) {
            return `<i class="bx bx-check fw-bold fs-3 text-success"></i>`;
        } else {
            return `<i class="bx bx-x fw-bold fs-3 text-danger"></i>`;
        }
    }

    function thaiDate(date) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Date(date).toLocaleDateString('th-TH', options);
    }

    function classificationBadge(classification) {
        if (classification == 'major') {
            return `<span class="badge bg-primary">ตัวชี้วัดรวม</span>`;
        } else {
            return `<span class="badge bg-warning">ตัวชี้วัดย่อย</span>`;
        }
    }
</script>