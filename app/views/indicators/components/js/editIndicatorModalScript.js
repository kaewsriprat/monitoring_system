let divisionDivCount = 0;
    const divLimit = 10;

    document.addEventListener('DOMContentLoaded', async () => {
        let divisionInputDiv = document.getElementById('divisionInputDiv');
        let observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.addedNodes.length > 0) {
                    let addedElement = mutation.addedNodes[0];
                    let divisionSelect = addedElement.querySelector('.indDivisionSelect');
                    let divCounter = divisionSelect.id.split('_')[1];
                    divisionSelect.addEventListener('change', () => {
                        let divisionId = divisionSelect.value;
                        getProjectsByDivision(divCounter, divisionId);
                    });
                    axios
                        .get(`/divisions/getDivisions`)
                        .then((res) => {
                            let divisionsList = res.data;
                            divisionSelect.innerHTML = '';
                            let disabledOption = document.createElement('option');
                            disabledOption.value = '';
                            disabledOption.innerText = 'เลือกหน่วยงาน';
                            disabledOption.disabled = true;
                            disabledOption.selected = true;
                            divisionSelect.appendChild(disabledOption);
                            divisionsList.forEach(division => {
                                let option = document.createElement('option');
                                option.value = division.division_id;
                                option.innerText = division.division_name;
                                option.setAttribute('data-tokens', division.division_name);
                                divisionSelect.appendChild(option);
                            });
                            selectPicker(`indDivisionSelect_${divCounter}`);
                        })
                }
            });
        });
        observer.observe(divisionInputDiv, {
            childList: true
        });
    });

    function showIndModal(classification) {
        modalTitle(classification);
        $('#newIndModal').modal('show');
    }

    function addIndMajaorDivisionInput() {
        event.preventDefault();
        let colors = ['primary', 'success', 'danger', 'warning', 'info', 'dark'];
        if (divisionDivCount >= divLimit) {
            document.getElementById('addDivisionBtn').disabled = true;
            return;
        }
        divisionDivCount++;
        let color = colors[divisionDivCount % colors.length];
        let divisionInputDiv = document.getElementById('divisionInputDiv');
        let divisionInput = document.createElement('div');
        divisionInput.className = 'col-12 mb-3';
        divisionInput.innerHTML = `
        <div class="card mt-3 bg-label-${color}">
            <div class="card-body">
                <h5 class="card-title text-${color} fw-bold">หน่วยงานที่ ${divisionDivCount}</h5>
                <div class="row">
                    <div class="col-11">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <select class="indDivisionSelect selectpicker w-100" data-style="btn-default" data-live-search="true" id="indDivisionSelect_${divisionDivCount}" name="indDivisionSelect_${divisionDivCount}"></select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <select class="form-select" id="indProjectSelect_${divisionDivCount}" name="indProjectSelect_${divisionDivCount}"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="form-floating">
                                <input type="text" class="form-control" id="indDivisionTarget_${divisionDivCount}" name="indDivisionTarget_${divisionDivCount}" placeholder="ค่าเป้าหมาย">
                                <label for="indDivisionTarget_${divisionDivCount}">ค่าเป้าหมาย</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating">
                                <input type="text" class="form-control" id="indDivisionTargetText_${divisionDivCount}" name="indDivisionTargetText_${divisionDivCount}" placeholder="คำอธิบายค่าเป้าหมาย">
                                <label for="indDivisionTargetText_${divisionDivCount}">คำอธิบายค่าเป้าหมาย</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-end my-auto">
                        <button class="btn btn-icon rounded-pill bg-label-danger mt-2" onclick="removeIndDivisionInput(this)"><i class="bx bx-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
        `;
        divisionInputDiv.appendChild(divisionInput);
    }

    function removeSelect(ele) {
        let divisionSelect = document.querySelectorAll('#divisionSelect');
        let divisionSelectArray = Array.from(divisionSelect);
        let selectedValue = ele.value;
        divisionSelectArray.forEach(select => {
            if (select.value === selectedValue) {
                select.value = '';
            }
        });
    }

    function removeIndDivisionInput(e) {
        e.closest('.col-12').remove();
        divisionDivCount--;
        document.getElementById('addDivisionBtn').disabled = false;
    }

    function majorIndFormSubmit() {
        let form = document.getElementById('indicatorForm');
        let formData = new FormData(form);
        const url = '/indicators/newIndicator';
        axios
            .post(url, formData)
            .then((res) => {
                if (res.data.status === 'success') {
                    $('#newIndModal').modal('hide');
                    swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: res.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else {
                    swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: res.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
    }

    function modalTitle(classification) {
        let modalHeader = document.getElementById('modalHeader');
        let modalLabel = document.getElementById('indModalLabel');
        if (classification === 'major') {
            modalHeader.classList.remove('bg-label-warning');
            modalHeader.classList.add('bg-label-primary');
            modalLabel.innerText = 'เพิ่มตัวชี้วัดรวม';
            modalLabel.classList.add('text-primary');
        } else {
            modalHeader.classList.remove('bg-label-primary');
            modalHeader.classList.add('bg-label-warning');
            modalLabel.innerText = 'เพิ่มตัวชี้วัดย่อย';
            modalLabel.classList.add('text-warning');
        }
    }

    function yearSelect() {
        document.getElementById('goalsIndSelect').disabled = false;
        let year = document.getElementById('yearsSelect').value;
        getGoalByYear(year);
        clearDivisionInput();
    }

    function getGoalByYear(year) {
        const classification = <?= json_encode($classification) ?>;
        axios
            .get(`/indicators/getGoalsByYear/${year}/${classification}`)
            .then((res) => {
                let goalsSelect = document.getElementById('goalsIndSelect');
                goalsSelect.innerHTML = '';
                if (res.data.length > 0) {
                    res.data.forEach(goal => {
                        let option = document.createElement('option');
                        option.value = goal.id;
                        option.innerText = goal.title;
                        goalsSelect.appendChild(option);
                        document.getElementById('majorIndFormSubmitBtn').disabled = false;
                    });
                } else {
                    let option = document.createElement('option');
                    option.value = '';
                    option.innerText = 'ไม่พบเป้าหมาย';
                    goalsSelect.appendChild(option);
                    document.getElementById('majorIndFormSubmitBtn').disabled = true;
                }
            })
    }

    function getProjectsByDivision(divCounter, divisionId) {
        let year = document.getElementById('yearsSelect').value;
        axios
            .get(`/projects/getProjectsByDivision/${year}/${divisionId}`)
            .then((res) => {
                let projectSelect = document.getElementById(`indProjectSelect_${divCounter}`);
                projectSelect.innerHTML = '';
                if (res.data.length > 0) {
                    let disableOption = document.createElement('option');
                    disableOption.value = '';
                    disableOption.innerText = 'เลือกโครงการ';
                    disableOption.disabled = true;
                    disableOption.selected = true;
                    projectSelect.appendChild(disableOption);
                    res.data.forEach(project => {
                        let option = document.createElement('option');
                        option.value = project.project_ID;
                        option.innerText = project.project_name;
                        projectSelect.appendChild(option);
                    });
                } else {
                    let option = document.createElement('option');
                    option.value = '';
                    option.innerText = 'ไม่พบโครงการ';
                    projectSelect.appendChild(option);
                }
            })
    }

    function clearDivisionInput() {
        let divisionInputDiv = document.getElementById('divisionInputDiv');
        divisionInputDiv.innerHTML = '';
        divisionDivCount = 0;
    }

    function selectPicker(elementId) {
        $(`#${elementId}`).selectpicker();
    }