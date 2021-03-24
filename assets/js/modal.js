import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

// Handle modal opening when clicking on image/video thumbnails
let imageElement = $('.modal-body #image');
let videoElement = $('.modal-body #video');
let commentElement = $('.modal-body #comment');
let openModal = document.querySelectorAll('.modal-open');

if (null !== openModal) {
    for (const element of openModal) {
        element.addEventListener('click', function (event) {
            event.preventDefault();
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
            }
            toggleModal();
        })
    }
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
let closeModal = document.querySelectorAll('.modal-close');
if (null !== closeModal) {
    for (const element of closeModal) {
        element.addEventListener('click', function () {
            clearModalData();
            toggleModal();
        });
    }
}

// Handle close modal when hitting "Escape" key on keyboard
document.onkeydown = function (evt) {
    let isEscape;
    console.log('toto');
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
    if ('' !== imageElement.attr('src')) {
        imageElement.attr('src', '').addClass('hidden');
    } else if ('' !== videoElement.attr('src')) {
        videoElement.attr('src', '').addClass('hidden');
    } else if (!commentElement.hasClass('hidden')) {
        commentElement.addClass('hidden');
    }
}

// Display media in modal
function displayMedia(data) {
    let mediaId = data.id;
    switch (data.type) {
        case 'image':
            imageElement.removeClass('hidden').attr('src', mediaId);
            break;
        case 'youtube':
            videoElement.removeClass('hidden').attr('src', 'https://youtube.com/embed/' + mediaId);
            break;
        case 'vimeo':
            videoElement.removeClass('hidden').attr('src', 'https://player.vimeo.com/video/' + mediaId);
            break;
    }
}

// Handle comment moderation display in modal
function handleComment(data) {
    $("#comment .comment-modal-content").html(getContent(data.action));
    $("#comment .id-value").val(data.id);
    commentElement.removeClass('hidden');

    if ('approve' === data.action) {
        $('#modal-form-comment').attr('action', Routing.generate('admin_moderate_comment', {
            isValid: 1
        }));
    } else if ('delete' === data.action) {
        $('#modal-form-comment').attr('action', Routing.generate('admin_moderate_comment', {
            isValid: 0
        }));
    }
}

// Get HTML content for comment moderation in modal
function getContent(action) {
    if ('approve' === action) {
        return 'Confirmer la publication du commentaire ?';
    } else if ('delete' === action) {
        return 'Confirmer la modération du commentaire ? Il ne sera plus visible, mais pas définitivement supprimé';
    }
}
