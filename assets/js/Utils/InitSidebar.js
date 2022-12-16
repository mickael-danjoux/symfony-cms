export default () => {
    const el = document.getElementById('wrapper')
    document.querySelector('.burger').onclick = function() {
        el.classList.toggle('nav-open')
        el.classList.toggle('nav-close')
    }
}
