import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

// Handle modal opening when clicking on image/video thumbnails
let mediaFormElement = $('.update-cover-modal-content .cover-form');
let openModal = document.querySelectorAll('.update-cover-modal-open');

if (null !== openModal) {
    for (const element of openModal) {
        element.addEventListener('click', function (event) {
            event.preventDefault();
            let id = $(this).data('cover-id');
            toggleModal(id);
        })
    }
}

// Handle close modal when clicking out of the modal
const overlay = document.querySelectorAll('.update-cover-modal-overlay');
if (null != overlay) {
    for (const element of overlay) {
        handleModalToggling(element);
    }
}

// Handle close modal when clicking close button on the modal
let closeModal = document.querySelectorAll('.update-cover-modal-close');
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

    if (isEscape && document.body.classList.contains('update-cover-modal-active')) {
        toggleModal();
    }
};

// Show/hide modal
function toggleModal(id = null) {
    const body = document.querySelector('body');
    const modal = document.querySelector('.update-cover-modal');

    if (id) {
        $.get(Routing.generate('cover_edit', {
            id: id,
        }), function (result) {
            $('#cover-form-content').html(result);
            let labels = document.querySelectorAll('#cover_image_cover_image label');
            for (const label of labels) {
                label.innerHTML = `
                    <img src='/uploads/tricks/${label.innerHTML}'
                      alt="${label.innerHTML}"
                      class="w-full lg:h-32 max-h-32 lg:max-h-48 object-contain"
                      width="150"
                      height="100"
                    >`;
            }
        });
    }
    modal.classList.toggle('opacity-0');
    modal.classList.toggle('pointer-events-none');
    body.classList.toggle('update-cover-modal-active');
    mediaFormElement.removeClass('hidden');
}

function handleModalToggling(element) {
    element.addEventListener('click', function () {
        toggleModal();
    });
}
