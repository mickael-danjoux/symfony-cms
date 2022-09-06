import {ConfirmDeleteComponent} from "../Components/ConfirmDeleteComponent";

const options = {
    searching: true,
    stateSave: true,
}

const event = new Event('datatableDrawn');

export function initDatatable(selector, datatable, options = {}) {
    const dataTableContainer = document.querySelector(selector)
    if (dataTableContainer) {
        $(selector).initDataTables(datatable, options).then(function (dt) {
            dt.on('draw', function () {
                addOnDeleteEvent()
                // On émet un évènement pour pouvoir s'en servir ailleurs
                document.dispatchEvent(event);
            })
        });
    }
}

if (typeof datatable !== 'undefined') {
    document.addEventListener("DOMContentLoaded", function () {
        initDatatable('.dataTable', datatable, options)
    })
}

function addOnDeleteEvent() {
    document.querySelectorAll('.dataTable td .btn-delete').forEach((el) => {
        el.addEventListener('click', (e) => {
            e.preventDefault()
            ConfirmDeleteComponent.fire({}).then((result) => {
                if (result.isConfirmed) {
                    location.href = el.getAttribute('data-href')
                }
            })
        })
    })
}