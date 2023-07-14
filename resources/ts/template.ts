import { replace as featherReplace } from 'feather-icons';

export default function initTemplate() {
    window.addEventListener('load', loadPreloader, false);
    clickablemenu();
    window.addEventListener('scroll', (ev) => {
        ev.preventDefault();
        windowScroll();
    });
    window.onscroll = function () {
        scrollFunction();
    };
    loadSidebar();
    loadDDMenu();
    loadTooltips();
    loadSmallMenu();

    featherReplace();
}

function loadPreloader() {
    // Preloader
    const preloader = document.getElementById('preloader');
    if (preloader) {
        if (preloader.getAttribute('data-nohide') !== null) {
            setTimeout(() => {
                preloader.style.visibility = 'hidden';
                preloader.style.opacity = '0';
            }, 350);
        }
    }
}

//Menu
// Toggle menu
window['toggleMenu'] = function toggleMenu() {
    document.getElementById('isToggle')?.classList.toggle('open');
    const isOpen = document.getElementById('navigation');
    if (isOpen?.style.display === 'block') {
        isOpen.style.display = 'none';
    } else if (isOpen) {
        isOpen.style.display = 'block';
    }
};

//Menu Active
function getClosest(elem, selector) {
    // Get the closest matching element
    for (; elem && elem !== document; elem = elem.parentNode) {
        if (elem.matches(selector)) return elem;
    }
    return null;
}

// Clickable Menu
function clickablemenu() {
    const navigation = document.getElementById('navigation');
    if (navigation) {
        const elements = navigation.getElementsByTagName('a');
        for (let i = 0, len = elements.length; i < len; i++) {
            elements[i].onclick = function (event) {
                let elem = event.target as HTMLElement;
                if (elem.getAttribute('href') === 'javascript:void(0)') {
                    let submenu = elem?.nextElementSibling?.nextElementSibling;
                    submenu?.classList.toggle('open');
                }
            };
        }
    }
}

// Menu sticky
function windowScroll() {
    const navbar = document.getElementById('topnav');
    if (navbar) {
        if (document.body.scrollTop >= 50 || document.documentElement.scrollTop >= 50) {
            navbar.classList.add('nav-sticky');
        } else {
            navbar.classList.remove('nav-sticky');
        }
    }
}

function scrollFunction() {
    let mybutton = document.getElementById('back-to-top');
    if (mybutton) {
        if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
            mybutton.style.display = 'block';
        } else {
            mybutton.style.display = 'none';
        }
    }
}

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

//Active Sidebar
function loadSidebar() {
    var current = location.pathname.substring(location.pathname.lastIndexOf('/') + 1);
    if (current === '') return;
    const menuItems = document.querySelectorAll('.sidebar-nav a');
    for (let i = 0, len = menuItems.length; i < len; i++) {
        let item = menuItems[i];
        if (item.parentElement && item.getAttribute('href')?.indexOf(current) !== -1) {
            item.parentElement.className += ' active';
        }
    }
}

// dd-menu
function loadDDMenu() {
    const ddmenu = document.getElementsByClassName('dd-menu');
    for (let i = 0, len = ddmenu.length; i < len; i++) {
        let ddelem = ddmenu[i] as HTMLElement;
        ddelem.onclick = function (elem) {
            elem.stopPropagation();
        };
    }
}

import * as bootstrap from 'bootstrap';

//Tooltip
function loadTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

import { Gumshoe } from 'gumshoejs';

//small menu
function loadSmallMenu() {
    try {
        var spy = new Gumshoe('#navmenu-nav a');
    } catch (err) {}
}
