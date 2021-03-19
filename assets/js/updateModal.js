import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

// Handle modal opening when clicking on image/video thumbnails
let mediaFormElement = $('.update-modal-content .media-form');
let openModal = document.querySelectorAll('.update-modal-open');
let key = null;

if (null !== openModal) {
    for (let i = 0; i < openModal.length; i++) {
        openModal[i].addEventListener('click', function (event) {
            event.preventDefault();
            key = $(this).data('target');
            toggleModal();
        })
    }
}

// Handle close modal when clicking out of the modal
const overlay = document.querySelectorAll('.update-modal-overlay');
if (null != overlay) {
    for (let i = 0; i < overlay.length; i++) {
        overlay[i].addEventListener('click', function () {
            toggleModal();
        });
    }
}

// Handle close modal when clicking close button on the modal
let closeModal = document.querySelectorAll('.update-modal-close');
if (null !== closeModal) {
    for (let i = 0; i < closeModal.length; i++) {
        closeModal[i].addEventListener('click', function () {
            toggleModal();
        });
    }
}

// Handle close modal when hitting "Escape" key on keyboard
document.onkeydown = function (evt) {
    let isEscape;
    if ("key" in evt) {
        isEscape = (evt.key === "Escape" || evt.key === "Esc");
    }

    if (isEscape && document.body.classList.contains('update-modal-active')) {
        toggleModal();
    }
};

// Show/hide modal
function toggleModal() {
    const body = document.querySelector('body');
    const modal = document.querySelector(key);
    modal.classList.toggle('opacity-0');
    modal.classList.toggle('pointer-events-none');
    body.classList.toggle('update-modal-active');
    mediaFormElement.removeClass('hidden');
}
