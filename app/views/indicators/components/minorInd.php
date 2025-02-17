<style>
    .colWidth {
        width: 30px !important;
        min-width: 30px !important;
        max-width: 30px !important;
    }
</style>

<?php
$yearCount = 2;
$startYear = Budgetyear::getBudgetyearThai() - $yearCount;
$currentYear = Budgetyear::getBudgetyearThai();
?>

<div class="row">
    <div class="col-12 text-center col-md-8 text-md-start">
        <p class="fw-bold fs-3 px-3 text-warning">เป้าหมายย่อย</p>
    </div>
    <div class="col-12 text-center col-md-4 text-md-end">
        <div>
            <a class="btn btn-label-warning me-0 me-md-3" href="create/minor">
                <i class="bx bx-plus pe-2"></i>
                เพิ่มตัวชี้วัดย่อย
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="px-4 d-flex justify-content-center justify-content-md-start">
            <div class="col-12 col-md-3">
                <label for="yearsSelect" class="form-label">ปีงบประมาณ</label>
                <select class="form-select" id="yearsSelect" name="yearsSelect">
                    <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                        <option value="<?= $year ?>"><?= $year ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="col-12">
        <!-- loader -->
        <div class="text-center my-5" id="loader">
            <div class="spinner-border text-warning" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="card-datatable table-responsive d-none" id="datatableDiv">
            <table class="datatables-basic table nowrap " id="indTable" width="100%">
                <thead class="bg-label-warning">
                    <tr>
                        <th class="fw-bold text-center">#</th>
                        <th class="fw-bold text-center">ตัวชี้วัด</th>
                        <th class="fw-bold text-center">เป้าหมาย</th>
                        <th class="fw-bold text-center">ร้อยละ</th>
                        <th class="fw-bold text-center">รายไตรมาส</th>
                        <th class="fw-bold text-center">จัดการ</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>

<script>
    let indTable = null;
    let selectYearValue = document.getElementById('yearsSelect').value;

    document.addEventListener('DOMContentLoaded', async () => {
        await initIndTable();
        toggleLoader(0);
        toggleIndTable(1);

        document.getElementById('yearsSelect').addEventListener('change', async () => {
            selectYearValue = document.getElementById('yearsSelect').value;
            await toggleIndTable(0)
            await toggleLoader(1)
            await initIndTable();
            await toggleLoader(0)
            await toggleIndTable(1)

        })
    });

    async function initIndTable() {
        if (indTable !== null) {
            indTable.destroy();
        }
        let year = selectYearValue;
        let data = await fetchIndicators(year, 2);

        data = dataManipulate(data);

        indTable = await $('#indTable').DataTable({
            responsive: true,
            data: data,
            columns: [{
                    data: 'indicator_id'
                },
                {
                    data: 'indicator_title'
                },
                {
                    data: 'goal_title'
                },
                {
                    data: 'percentile',
                    render: function(data) {
                        isNaN(data) ? data = 0 : data;
                        data = Math.round(data);
                        return `<span class="fs-5">${data}%</span>`
                    }
                },
                {
                    data: 'allScore',
                    render: function(data, meta, row) {
                        sparklineId = `sparkline-${row.indicator_id}`;
                        setObserver(data, row, sparklineId); // set observer to re-render sparkline when table change
                        sparklineChart(sparklineId, data);
                        return `<div class="d-flex justify-content-center"><div id="${sparklineId}"></div></div>`;
                    }
                },
                {
                    data: 'indicator_id',
                    render: function(data) {
                        return buttonGroup(data);
                    }
                }
            ],
            columnDefs: [{
                    targets: [0, 3, -1],
                    className: 'text-center'
                },
                {
                    targets: 4,
                    className: 'colWidth m-0'
                }
            ],
        });
    }

    function dataManipulate(data) {
        data.forEach((item, index) => {
            data[index]['q1_score'] = parseFloat(item['q1_score']);
            data[index]['q2_score'] = parseFloat(item['q2_score']);
            data[index]['q3_score'] = parseFloat(item['q3_score']);
            data[index]['q4_score'] = parseFloat(item['q4_score']);
            data[index]['percentile'] = parseFloat(item['percentile']);
            data[index]['allScore'] = [parseFloat(item['q1_score']), parseFloat(item['q2_score']), parseFloat(item['q3_score']), parseFloat(item['q4_score'])];
        })
        return data
    }

    async function fetchIndicators(year, classification) {
        let url = `/indicators/getSummaryIndicatorReport/${year}/${classification}`;
        return await axios.get(url)
            .then((res) => {
                return res.data;
            })
            .catch((err) => {
                console.error(err);
            });
    }

    function scoreBadge(score) {
        (score === null) ? score = '0': score;
        score = Math.round(score);
        if (score >= 80) {
            return `<button class="btn btn-icon btn-sm btn-success rounded-pill"><i class="bx bx-happy-alt"></i></button>`;
        } else if (score >= 60) {
            return `<button class="btn btn-icon btn-sm btn-warning rounded-pill"><i class="bx bx-meh-alt"></i></button>`;
        } else {
            return `<button class="btn btn-icon btn-sm btn-danger rounded-pill"><i class="bx bx-tired"></i></button>`;
        }
    }

    function buttonGroup(data) {
        return `<div class="btn-group">
                    <button type="button" class="btn btn-sm btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded fs-5"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item" onclick="showIndDetailModal(${data})"><i class="bx bx-info-circle pe-2 text-info"></i>รายละเอียด</button></li>
                        <li><a class="dropdown-item" onclick="" href="/indicators/edit/${data}"><i class="bx bx-edit pe-2 text-warning"></i>แก้ไข</a></li>
                        <div class="dropdown-divider"></div>
                        <li><a class="dropdown-item" onclick="deleteIndicator(${data})" href="javascript:;"><i class="bx bx-trash pe-2 text-danger"></i>ลบ</a></li>
                    </ul>
                </div>`;
    }

    function toggleIndTable(display) {
        (display === 1) ?
        $('#datatableDiv').removeClass('d-none'):
            $('#datatableDiv').addClass('d-none');
    }

    function toggleLoader(display) {
        (display === 1) ?
        $('#loader').removeClass('d-none'):
            $('#loader').addClass('d-none');
    }

    function deleteIndicator(id) {
        swal.fire({
            icon: 'warning',
            title: 'ลบตัวชี้วัด ?',
            html: '<p class="mb-0">การลบไม่สามารถย้อนกลับได้</p><p class="mb-0">หน่วยงานที่รับผิดชอบ และคะแนนทั้งหมด จะถูกลบด้วย </p><p class="mb-0">ต้องการจะลบตัวชี้วัดนี้หรือไม่ ?</p>',
            showConfirmButton: true,
            showCancelButton: true,
            customClass: {
                confirmButton: "btn swal-label-danger me-4",
                cancelButton: 'btn swal-label-primary btn-primary ms-4'
            },
            confirmButtonText: 'ใช่, ลบตัวชี้วัดนี้',
            cancelButtonText: 'ไม่ใช่, ยกเลิกการลบ',
        }).then((result) => {
            if (result.isConfirmed) {
                swal.fire({
                    title: 'กำลังลบข้อมูล...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="bx bx-undo me-2"></i>ยกเลิกการลบ',
                    customClass: {
                        confirmButton: 'btn btn-label-secondary',
                    },
                    timer: 3000,
                    timerProgressBar: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        swal.fire({
                            title: 'ยกเลิกการลบข้อมูล!',
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        return;
                    } else {
                        if (doDelete(id)) {
                            swal.fire({
                                title: 'ลบข้อมูลสำเร็จ!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            indTable.row($(`#indTable tr:contains(${id})`)).remove().draw();
                        } else {
                            swal.fire('เกิดข้อผิดพลาด!', '', 'error');
                        }
                    }
                })

            }
        })
    }

    async function doDelete(id) {
        const url = '/indicators/delete/' + id;
        await axios.delete(url).then(res => {
            res.data
        })
    }

    function sparklineChart(ele, dataSet) {
        ele = document.getElementById(ele);

        dataSet = dataSet.map((item) => {
            if (isNaN(item)) {
                return 0;
            }
            return item;
        });

        const min = Math.min(...dataSet);
        const max = Math.max(...dataSet);

        if (ele === null) return;
        if (ele.innerHTML !== '') return;
        const options = {
            series: [{
                data: dataSet
            }],
            chart: {
                type: 'line',
                width: 100,
                height: 25,
                sparkline: {
                    enabled: true
                }
            },
            markers: {
                size: 0,
                colors: '#fff',
                strokeColors: '#00b19d',
                strokeWidth: 2,
                hover: {
                    size: 3,
                }
            },
            stroke: {
                width: 2,
                curve: 'smooth',
                colors: ['#ffab00'] // Sets the stroke color for the line.
            },
            yaxis: {
                min: min - 5,
                max: max + 5,
            },
            tooltip: {
                fixed: {
                    enabled: false
                },
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function(seriesName) {
                            return ''
                        }
                    }
                },
                marker: {
                    show: false
                }
            }
        };

        const chart = new ApexCharts(ele, options);
        chart.render();
    }

    function setObserver(data, row, ele) {
        // observe tbody
        const observe = document.querySelector('#indTable tbody');
        // if tbody changed then re-render sparkline
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    sparklineChart(ele, data);
                }
            });
        });
        observer.observe(observe, {
            childList: true
        });
    }
</script>