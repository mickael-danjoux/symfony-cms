export default () => {
    const el = document.getElementById('wrapper')
    const toggleButton = document.querySelector('.burger')

    if(localStorage.getItem('sidebarState')){
        el.classList.remove('sidebar-close')
        el.classList.remove('sidebar-open')
        el.classList.add(localStorage.getItem('sidebarState'))
    }

    toggleButton.onclick = function () {
        el.classList.toggle('sidebar-open')
        el.classList.toggle('sidebar-close')
        localStorage.setItem('sidebarState',el.classList[0])
    }
    const menuItems = document.querySelectorAll('.menu-items')
    menuItems.forEach(function (menu) {
        menu.querySelectorAll('.has-dropdown').forEach(function (item) {
            item.addEventListener('click', () => {
                const subMenu = item.querySelector('.sub-menu-items')
                if (item.classList.contains("open")) {
                    // Masquer le contenu en réduisant la hauteur à 0
                    subMenu.style.height = "0"

                    // Timeout égale à la durée de l'animation
                    setTimeout(function () {
                        item.classList.remove("open")
                    }, 350)
                } else {
                    // Afficher le contenu en réglant la hauteur sur la hauteur totale du contenu
                    subMenu.style.height = "0"
                    item.classList.add("open")
                    subMenu.style.height = subMenu.scrollHeight + "px"
                }
            })
        })
    })
}
