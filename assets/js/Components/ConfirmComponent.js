import Swal from 'sweetalert2'

export const ConfirmComponent = (message) => Swal.mixin({
    title: message?? 'Veuillez confirmer votre action',
    customClass: {
        confirmButton: 'btn btn-success me-2',
        denyButton: 'btn btn-danger ms-2'
    },
    buttonsStyling: false,
    showDenyButton: true,
    showCancelButton: false,
    confirmButtonText: 'Confimer',
    denyButtonText: `Annuler`,
})

export const initConfirmButtons = () => {
    document.querySelectorAll('.js-btn-confirm').forEach((item) => {
        item.addEventListener('click', (e) => {
            e.preventDefault()
            ConfirmComponent(item.getAttribute('data-message')).fire({
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = item.getAttribute('href')
                }
            })
        })
    })
}
