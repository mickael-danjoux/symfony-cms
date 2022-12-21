export default () => {
    document.querySelectorAll('.js-validate-form').forEach(function (el) {
        el.addEventListener('click', (event) => {
            document.getElementsByName(event.target.dataset.formName)[0].submit()
        })
    })
}
