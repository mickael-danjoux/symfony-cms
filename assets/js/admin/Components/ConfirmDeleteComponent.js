import Swal from 'sweetalert2'

export const ConfirmDeleteComponent = Swal.mixin({
    title: 'Veuillez confirmer la suppression',
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

export const initConfirmDeleteButtons = () => {
    document.querySelectorAll('.js-btn-delete').forEach((item) => {
        item.addEventListener('click', (e) => {
            e.preventDefault()
            ConfirmDeleteComponent.fire({
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = item.getAttribute('data-href')
                }
            })
        })
    })
}