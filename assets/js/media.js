import Routing from "./helper/routing";

let imageElement = $('.modal-body #image');
let videoElement = $('.modal-body #video');
let deleteMediaElement = $('.modal #delete-media');
let updateMediaElement = $('.modal #update-media-form');
let updateCoverImageElement = $('.modal #update-cover-form');

// Display media in modal
export function displayMedia(data) {
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

export function displayMediaForm(data) {
    switch (data.action) {
        case 'update':
            displayUpdateMediaForm({
                'id': data.id,
                'slug': data.slug
            });
            break;
        case 'delete':
            displayDeleteMediaForm({
                'id': data.id,
                'slug': data.slug
            });
            break;
        case 'cover-update':
            displayCoverForm({
                'id': data.id
            });
            break;
    }
}

function displayDeleteMediaForm(data) {
    updateCoverImageElement.addClass('hidden');
    updateMediaElement.addClass('hidden');
    let deleteMediaForm = $('#delete-media-form');
    deleteMediaForm.attr('action', Routing.generate('media_delete', {
        id: data.id,
        slug: data.slug
    }));
    $("#media-id-value").val(data.id);
    deleteMediaElement.removeClass('hidden');
}

function displayUpdateMediaForm(data) {
    updateCoverImageElement.addClass('hidden');
    deleteMediaElement.addClass('hidden');
    $.get(Routing.generate('media_edit', {
        id: data.id,
        slug: data.slug
    }), function (result) {
        $('#update-media-form-content').html(result);
        updateMediaElement.removeClass('hidden');
    });
}

function displayCoverForm(data) {
    deleteMediaElement.addClass('hidden');
    updateMediaElement.addClass('hidden');
    $.get(Routing.generate('cover_edit', {
        id: data.id,
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
    updateCoverImageElement.removeClass('hidden');
}
