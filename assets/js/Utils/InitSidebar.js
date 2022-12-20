export default () => {
    const el = document.getElementById('wrapper')
    const toggleButton = document.querySelector('.burger')

    toggleButton.onclick = function () {
        el.classList.toggle('nav-open')
        el.classList.toggle('nav-close')
    }
    const navItems = document.querySelectorAll('.nav-items')
    navItems.forEach(function (nav) {
        nav.querySelectorAll('.has-dropdown').forEach(function (item) {
            item.addEventListener('click', () => {
                const subNav = item.querySelector('.sub-nav-items')
                if (item.classList.contains("open")) {
                    // Masquer le contenu en réduisant la hauteur à 0
                    subNav.style.height = "0"

                    // Timeout égale à la durée de l'animation
                    setTimeout(function () {
                        item.classList.remove("open")
                    }, 350)
                } else {
                    // Afficher le contenu en réglant la hauteur sur la hauteur totale du contenu
                    subNav.style.height = "0"
                    item.classList.add("open")
                    subNav.style.height = subNav.scrollHeight + "px"
                }
            })
        })
    })

}
