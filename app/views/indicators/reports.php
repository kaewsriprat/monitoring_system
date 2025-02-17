<?php

$yearCount = 2;
$startYear = Budgetyear::getBudgetyearThai() - $yearCount;
$currentYear = Budgetyear::getBudgetyearThai();

?>

<style>
    .shepherd-footer {
        margin-top: 20px;
    }

    .next-tour {
        background-color: #007bff !important;
        color: white !important;
    }

    .back-tour {
        background-color: #6c757d !important;
        color: white !important;
    }

    .close-tour {
        background-color: #f44336 !important;
        color: white !important;
    }

    /* swal on top */
    .swal2-container {
        z-index: 999999 !important;
    }
</style>

<div class=" container-xxl flex-grow-1 container-p-y">
    <!-- BREADCRUMB -->

    <h5 class="fw-bold pb-2 pt-4">
        <!-- home icon -->
        <a href="/home" class="text-muted fw-light">
            หน้าแรก
        </a>
        / รายงานตัวชี้วัด

    </h5>

    <div class="row mb-3">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="yearsSelect" class="form-label fs-5 fw-bold">เลือกปีงบประมาณ</label>
                <select class="form-select" id="yearsSelect" name="yearsSelect" onchange="updateYear()">
                    <?php for ($year = $currentYear; $year >= $startYear; $year--) : ?>
                        <option value="<?= $year ?>"><?= $year ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="d-flex justify-content-end justify-content-md-start">
                <button type="button" class="btn btn-label-primary btn-sm mt-3" style="min-width: 110px; max-width: 110px;" id="expandingBtn" onclick="expandAll()"><i class="bx bx-chevron-down pe-2"></i>ขยายทั้งหมด</button>
            </div>
        </div>

        <div class="col-12 col-md-8 d-flex justify-content-end align-items-end mt-3 mt-md-0 d-none">
            <div id="tutorialBtn">
                <span class="text-muted fs-6 me-2">แนะนำการใช้งาน</span>
                <button class="btn btn-icon btn-sm btn-label-info rounded-pill" id="tourBtn" title="แนะนำการใช้งาน"><i class="bx bx-question-mark text-info fs-4"></i></button>
            </div>
        </div>
    </div>

    <?php include "components/reportTable.php"; ?>
    <?php // include "components/reportDetailModal.php"; 
    ?>
</div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@13.0.0/dist/css/shepherd.css" />
<script type="module">
    import Shepherd from "https://cdn.jsdelivr.net/npm/shepherd.js@13.0.0/dist/esm/shepherd.mjs"

    function initTour() {
        const tour = new Shepherd.Tour({
            useModalOverlay: true,
            defaultStepOptions: {
                classes: 'shadow-md bg-purple-dark',
                scrollTo: true
            }
        });

        // Define steps
        // year select
        tour.addStep({
            id: 'step1',
            text: `<div class="mt-3"><p class="fw-bold fs-5 mb-0">เลือกปีงบประมาณที่ต้องการดูรายงาน</p><span class="text-muted">ปีงบประมาณที่หน่วยงานได้รับมอบหมายตัวชี้วัด</span></div>`,
            attachTo: {
                element: '#yearsSelect',
                on: 'bottom'
            },
            buttons: [{
                action: tour.next,
                classes: 'next-tour',
                text: 'ถัดไป'
            }]
        });

        // major indicators
        tour.addStep({
            id: 'step2',
            text: `<div class="mt-3"><p class="fw-bold fs-5 mb-0">ตัวชี้วัดรวม</p><span class="text-muted">ตัวชี้วัดที่หน่วยงานได้รับมอบหมาย</span></div>`,
            attachTo: {
                element: '.card-header.bg-label-primary',
                on: 'bottom'
            },
            buttons: [{
                action: tour.back,
                classes: 'back-tour',
                text: 'ย้อนกลับ'
            }, {
                action: tour.next,
                classes: 'next-tour',
                text: 'ถัดไป'
            }]
        });

        // minor indicators
        tour.addStep({
            id: 'step3',
            text: `<div class="mt-3"><p class="fw-bold fs-5 mb-0">ตัวชี้วัดย่อย</p><span class="text-muted">ตัวชี้วัดที่หน่วยงานได้รับมอบหมาย</span></div>`,
            attachTo: {
                element: '.card-header.bg-label-warning',
                on: 'bottom'
            },
            buttons: [{
                action: tour.back,
                classes: 'back-tour',
                text: 'ย้อนกลับ'
            }, {
                action: tour.next,
                classes: 'next-tour',
                text: 'ถัดไป'
            }]
        });

        // major indicators table
        tour.addStep({
            id: 'step4',
            text: `<div class="mt-3"><p class="fw-bold fs-5 mb-0">ตารางแสดงตัวชี้วัดรวม</p><span class="text-muted">ตัวชี้วัดที่หน่วยงานได้รับมอบหมาย</span></div>`,
            attachTo: {
                element: '.table.table-borderless',
                on: 'bottom'
            },
            buttons: [{
                action: tour.back,
                classes: 'back-tour',
                text: 'ย้อนกลับ'
            }, {
                action: tour.next,
                classes: 'next-tour',
                text: 'ถัดไป'
            }]
        });

        //input major
        tour.addStep({
            id: 'step5',
            text: `<div class="mt-3">
                    <p class="fw-bold fs-5 mb-0">คลิกที่คะแนนเพื่อแก้ไข</p>
                    <div class="text-center mb-2">
                        <img src="/assets/img/tutorial-report-score-1.png" class="img-fluid" alt="edit" style="width: 50%">
                    </div>
                    <p class="text-muted">เมื่อกรอกคะแนนแล้วกด Enter หรือกดที่อื่นบนหน้าจอระบบจะทำการบันทึกให้อัตโนมัติ</p>
                </div>`,
            attachTo: {
                element: '.scoreDiv',
                on: 'bottom'
            },
            buttons: [{
                action: tour.back,
                classes: 'back-tour',
                text: 'ย้อนกลับ'
            }, {
                action: tour.next,
                classes: 'next-tour',
                text: 'ถัดไป'
            }]
        });

        tour.addStep({
            id: 'step6',
            text: `<div class="mt-3">
                    <p class="fw-bold fs-5 mb-0">หากคะแนนได้รับการยืนยันจากผู้บริหารหน่วยงานแล้วจะมีเครื่องหมาย <i class="bx bx-check text-success fs-4"></i></p>
                    <div class="text-center mb-2">
                        <img src="/assets/img/tutorial-report-score-2.png" class="img-fluid" alt="edit" style="width: 50%">
                    </div>
                    <p class="text-muted">เมื่อคะแนนได้รับการยืนยันแล้วจะไม่สามารถแก้ไขได้</p>
                </div>`,
            attachTo: {
                element: '.scoreDiv',
                on: 'bottom'
            },
            buttons: [{
                action: tour.back,
                classes: 'back-tour',
                text: 'ย้อนกลับ'
            }, {
                action: tour.next,
                classes: 'next-tour',
                text: 'ถัดไป'
            }]
        });

        tour.addStep({
            id: 'step7',
            text: `<div class="mt-3">
                    <p class="fw-bold fs-5 mb-0">กดปุ่ม <i class="bx bx-info-circle fs-3 text-info"></i> เพื่อดูรายละเอียดตัวชี้วัด</p>
                    <p class="text-muted"></p>
                </div>`,
            attachTo: {
                element: '#detailBtn',
                on: 'bottom'
            },
            buttons: [{
                action: tour.back,
                classes: 'back-tour',
                text: 'ย้อนกลับ'
            }, {
                action: tour.next,
                classes: 'next-tour',
                text: 'ถัดไป'
            }]
        });

        tour.addStep({
            id: 'step8',
            text: `<div class="mt-3">
                    <p class="fw-bold fs-5 mb-0">หากต้องการคำแนะนำการใช้งาน</p>
                    <p class="text-muted">กดที่นี่เพื่อดูคำแนะนำการใช้งาน</p>
                    </div>`,
            attachTo: {
                element: '#tutorialBtn',
                on: 'bottom'
            },
            buttons: [{
                    action: tour.back,
                    classes: 'back-tour',
                    text: 'ก่อนหน้า'
                },
                {
                    action: function() {
                        localStorage.setItem('tour', 'false');
                        tour.complete();
                    },
                    classes: 'close-tour',
                    text: 'ปิด'

                },
            ]
        });

        tour.start();
    }
    // Start the tour
    if (localStorage.getItem('tour') === 'true' || localStorage.getItem('tour') === null) {
        initTour();
    }

    document.getElementById('tourBtn').addEventListener('click', () => {
        initTour();
    });
</script>