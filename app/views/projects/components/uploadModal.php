<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                <div class="mb-4">
                    <p class="h4 text-center mb-4">นำเข้าไฟล์รายงานผลการดำเนินงาน</p>
                    <p class="h4 text-center">โครงการ <span class="fw-bold" id="projectName"></span></p>
                    <p class="h4 text-center">ปีงบประมาณ <span class="fw-bold" id="projectYear"></span></p>
                    <p class="h4 text-center">ไตรมาสที่ <span class="fw-bold" id="quarter"></span></p>
                    <p class="text-danger text-center py-2">*** อัพโหลดไฟล์ .doc หรือ .docx ขนาดไม่เกิน 10Mb เท่านั้น ***</p>
                </div>
                <div class="card-body m-0" style="min-height:250px !important;">
                    <div class="" id="uploadDiv">
                        <form action="" class="dropzone needsclick text-center" id="fileUploadDZ">
                            <div class="dz-message needsclick my-0">
                                <i class="bx bx-cloud-upload mb-4 text-muted" style="font-size: 100px;"></i><br>
                                <span class="text-muted">ลากไฟล์มาที่นี่ / คลิกเพื่อเลือกไฟล์</span>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file" accept=".doc,.docx" />
                            </div>
                        </form>
                        <!-- upload button -->
                        <div class="row text-center g-3 mt-5">
                            <div class="d-none d-md-block col-md-3"></div>
                            <div class="col-12 col-md-3 text-center text-md-end">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="closeModal()">ยกเลิก</button>
                            </div>
                            <div class="col-12 col-md-3 text-center text-md-start">
                                <button type="submit" class="btn btn-primary w-100" id="DZSubmitBtn"><i class="bx bx-save pe-2"></i> บันทึก</button>
                            </div>
                            <div class="d-none d-md-block col-md-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let DZ = null;
    let projectID = null;
    let projectYear = null;
    let quarter = null;



    document.getElementById('DZSubmitBtn').addEventListener('click', async () => {
        event.preventDefault();
        let formData = new FormData();
        formData.append('fileInput', DZ.files[0]);
        formData.append('year', projectYear)
        formData.append('projectID', projectID);
        formData.append('quarter', quarter);

        await doUpload(formData);

    })

    function uploadModal(ele, thisQuarter) {
        $('#uploadModal').modal('show');
        destroyDZ();
        initDropZone();

        let data = JSON.parse(ele.getAttribute('data-file'));
        projectID = data.project_ID;
        projectName = data.project_name;
        projectYear = data.project_year;
        quarter = thisQuarter;

        document.getElementById("projectName").innerText = projectName;
        document.getElementById("projectYear").innerText = projectYear;
        document.getElementById("quarter").innerText = thisQuarter;
    }

    function destroyDZ() {
        if (DZ !== null) {
            DZ.destroy();
        }
    }

    function initDropZone() {
        Dropzone.autoDiscover = false;

        document.getElementById("DZSubmitBtn").disabled = true;
        var DZTemplate = `<div class="dz-preview dz-file-preview">
            <div class="dz-details">
            <div class="dz-thumbnail">
                <img data-dz-thumbnail>
                <span class="fw-normal" class="dz-nopreview">No preview</span>
                <div class="dz-success-mark"></div>
                <div class="dz-error-mark"></div>
                <div class="dz-error-message"><span class="fw-normal" data-dz-errormessage></span></div>
                <div class="progress">
                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                </div>
            </div>
            <div class="dz-filename" data-dz-name></div>
            <div class="dz-size" data-dz-size></div>
            </div>
            </div>`;

        DZ = new Dropzone("#fileUploadDZ", {
            url: '/projects/upload',
            previewTemplate: DZTemplate,
            parallelUploads: 1,
            maxFilesize: 10000,
            addRemoveLinks: !0,
            maxFiles: 1,
            acceptedFiles: '.doc,.docx',
            dictDefaultMessage: "คลิกที่นี่เพื่อเลือกไฟล์",
            dictRemoveFile: "ลบไฟล์",
            dictCancelUpload: "ยกเลิกอัพโหลด",
            dictCancelUploadConfirmation: "คุณต้องการยกเลิกอัพโหลดไฟล์นี้ใช่หรือไม่?",
            dictMaxFilesExceeded: "คุณสามารถอัพโหลดได้เพียง 1 ไฟล์เท่านั้น",
            dictFileTooBig: "ไฟล์มีขนาดใหญ่เกินไป ขนาดไฟล์สูงสุดที่อนุญาตคือ 10 MB",
            dictInvalidFileType: "ไฟล์ที่อัพโหลดต้องเป็นไฟล์ .doc หรือ docx เท่านั้น",
            dictResponseError: "เกิดข้อผิดพลาดขึ้นในการอัพโหลดไฟล์",
            //dropzone height 

            init: function() {
                this.on("addedfile", function(file) {
                    document.getElementById("DZSubmitBtn").disabled = false;
                });
                this.on("removedfile", function(file) {
                    document.getElementById("DZSubmitBtn").disabled = true;
                });
                this.on("error", function(file, errorMessage) {
                    document.getElementById("DZSubmitBtn").disabled = true;
                });
            },
        })

    }

    function toggleUploadDiv(status) {
        if (status) {
            document.getElementById('uploadDiv').style.display = 'block';
        } else {
            document.getElementById('uploadDiv').style.display = 'none';
        }
    }

    async function doUpload(formData) {
        axios
            .post('/projects/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((res) => {
                let realFileName = res.data.realFileName;
                let fileUpdateStatus = res.data.fileUploadStatus;
                let pathUpdateStatus = res.data.pathUpdateStatus;

                if (fileUpdateStatus && pathUpdateStatus) {
                    swal.fire({
                        title: "อัพโหลดไฟล์สำเร็จ",
                        text: `ไฟล์ ${realFileName} ถูกอัพโหลดเรียบร้อยแล้ว`,
                        icon: "success",
                        timer: 3000,
                        timerProgressBar: true,
                        confirmButtonText: "ตกลง",
                    }).then(() => {
                        tableReload();
                        $('#uploadModal').modal('hide');
                    })
                } else {
                    swal.fire({
                        title: "เกิดข้อผิดพลาด",
                        text: "กรุณาลองใหม่อีกครั้ง",
                        icon: "error",
                    })
                }
            }).catch(err => {
                console.log(err);
            })
    }

    function closeModal() {
        $('#uploadModal').modal('hide');
    }
</script>