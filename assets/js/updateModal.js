import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

// Handle modal opening when clicking on image/video thumbnails
let mediaFormElement = $('.update-modal-content .media-form');
let openModal = document.querySelectorAll('.update-modal-open');
let currentModal = localStorage.getItem('currentModal') ?? null;

if (null !== openModal) {
    for (const element of openModal) {
        element.addEventListener('click', function (event) {
            event.preventDefault();
            let key = $(this).data('target');
            currentModal = $('#update-modal-' + key);
            let currentModalContent = $('#media-form-content-' + key);
            localStorage.setItem('currentModal', currentModal);
            let id = $(this).data('id');
            let slug = $(this).data('slug');
            toggleModal(id, slug, currentModalContent);
        })
    }
}

// Handle close modal when clicking out of the modal
const overlay = document.querySelectorAll('.update-modal-overlay');
if (null != overlay) {
    for (const element of overlay) {
        handleModalToggling(element);
    }
}

// Handle close modal when clicking close button on the modal
let closeModal = document.querySelectorAll('.update-modal-close');
if (null !== closeModal) {
    for (const element of closeModal) {
        handleModalToggling(element);
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
function toggleModal(id = null, slug = null, currentModalContent = null) {
    const body = document.querySelector('body');
    const modal = currentModal.get(0);
    if (id && slug) {
        $.get(Routing.generate('media_edit', {
            id: id,
            slug: slug
        }), function (result) {
            currentModalContent.html(result);
        });
    }
    modal.classList.toggle('opacity-0');
    modal.classList.toggle('pointer-events-none');
    body.classList.toggle('update-modal-active');
    mediaFormElement.removeClass('hidden');
}

function handleModalToggling(element) {
    element.addEventListener('click', function () {
        toggleModal();
    });
}
