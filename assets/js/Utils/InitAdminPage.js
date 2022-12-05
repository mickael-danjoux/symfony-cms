export const initAdminPage = () => {
    const path = window.location.href;
    const links = document.querySelectorAll('#layoutSidenav_nav .sb-sidenav a.nav-link');
    const sidebarToggler = document.querySelector('#sidebarToggle');
    const body = document.querySelector('body');
    const sidebarContent = document.querySelector('#layoutSidenav_content');

    if(links.length > 0){
        links.forEach((link) => {
            if ((path.includes(link.href)) && !link.classList.contains('no-active')) {
                link.classList.add('active');
            }
        });
        sidebarToggler.addEventListener('click', (event) => {
            event.preventDefault();
            body.classList.toggle('sb-sidenav-toggled');
            sidebarToggler.classList.toggle('fold')
        });

        sidebarContent.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                body.classList.remove('sb-sidenav-toggled');
            }
        });
    }
}