// Handle modal opening when clicking on image/video thumbnails
let imageElement = $('.modal-body #image');
let videoElement = $('.modal-body #video');
let openModal = document.querySelectorAll('.modal-open');
if (null !== openModal) {
    for (let i = 0; i < openModal.length; i++) {
        openModal[i].addEventListener('click', function (event) {
            event.preventDefault();
            if ($(this).data('media-id')) {
                let mediaId = $(this).data('media-id');
                switch ($(this).data('type')) {
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
    for (let i = 0; i < closeModal.length; i++) {
        closeModal[i].addEventListener('click', function () {
            clearModalData();
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
    }
}
