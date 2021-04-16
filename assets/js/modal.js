import {displayMedia, displayModalForm} from "./media";
import {handleComment} from "./comment";

// Handle modal opening
let modalsList = getModalsList();

if (null !== modalsList) {
    for (const modal of modalsList) {
        modal.addEventListener('click', openModal);
    }
}

export function getModalsList() {
    return document.querySelectorAll('.modal-open');
}

export function openModal() {
    open.call(this);
}

export function refreshModalsListeners(type) {
    if ('open' === type) {
        refreshOpenModalsListeners();
    }

    if ('close' === type) {
        refreshCloseModalsListeners();
    }
}

function refreshOpenModalsListeners() {
    modalsList = getModalsList();
    if (null === modalsList) {
        return;
    }
    for (const element of modalsList) {
        element.removeEventListener('click', openModal);
        element.addEventListener('click', openModal);
    }
}

function open() {
    if ($(this).data('media-id')) {
        displayMedia({
            'type': $(this).data('type'),
            'id': $(this).data('media-id')
        });
    } else if ('comment' === $(this).data('type')) {
        handleComment({
            'action': $(this).data('action'),
            'id': $(this).data('id')
        });
    } else {
        displayModalForm({
            'id': $(this).data('id'),
            'slug': $(this).data('slug'),
            'name': $(this).data('name'),
            'action': $(this).data('action'),
        });
    }
    toggleModal();
}

// Handle close modal when clicking out of the modal
const overlay = document.querySelector('.modal-overlay');
if (null != overlay) {
    overlay.addEventListener('click', function () {
        clearModalData();
        toggleModal();
    });
}

// Handle close modal when clicking close button on the modal
let closeModalsList = getCloseModalsList();

function closeModal() {
    clearModalData();
    toggleModal();
}

if (null !== closeModalsList) {
    for (const element of closeModalsList) {
        element.addEventListener('click', closeModal);
    }
}

function getCloseModalsList() {
    return document.querySelectorAll('.modal-close');
}

function refreshCloseModalsListeners() {
    closeModalsList = getCloseModalsList();
    if (null === closeModalsList) {
        return;
    }
    for (const closeElement of closeModalsList) {
        closeElement.removeEventListener('click', closeModal);
        closeElement.addEventListener('click', closeModal);
    }
}

// Handle close modal when hitting "Escape" key on keyboard
document.onkeydown = function (evt) {
    let isEscape;
    if ("key" in evt) {
        isEscape = (evt.key === "Escape" || evt.key === "Esc");
    }

    if (isEscape && document.body.classList.contains('modal-active')) {
        clearModalData();
        toggleModal();
    }
};

// Show/hide modal
function toggleModal() {
    const body = document.querySelector('body');
    const modal = document.querySelector('.modal');
    modal.classList.toggle('opacity-0');
    modal.classList.toggle('pointer-events-none');
    body.classList.toggle('modal-active');
}

// On mobile display, show/hide image/video section
let showMediaButton = document.querySelector('#show-media');
if (null !== showMediaButton) {
    showMediaButton.addEventListener('click', function (event) {
        event.preventDefault();
        const mediaGallery = document.querySelector('#media-gallery');
        mediaGallery.classList.toggle('hidden');
    });
}

// Remove "src" data and hide image/iframe tag
function clearModalData() {
    let imageElement = $('.modal-body #image');
    let videoElement = $('.modal-body #video');
    let commentElement = $('.modal-body #comment');
    if ('' !== imageElement.attr('src')) {
        imageElement.attr('src', '').addClass('hidden');
    } else if ('' !== videoElement.attr('src')) {
        videoElement.attr('src', '').addClass('hidden');
    } else if (!commentElement.hasClass('hidden')) {
        commentElement.addClass('hidden');
    }
}
