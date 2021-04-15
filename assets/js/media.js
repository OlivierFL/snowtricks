import Routing from "./helper/routing";
import {refreshCloseModalsList} from "./modal";

let imageElement = $('.modal-body #image');
let videoElement = $('.modal-body #video');
let deleteMediaElement = $('.modal #delete-media');
let updateMediaElement = $('.modal #update-media-form');
let updateCoverImageElement = $('.modal #update-cover-form');
let deleteTrickElement = $('.modal #delete-trick');

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

export function displayModalForm(data) {
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
        case 'trick-delete':
            displayTrickDeleteForm({
                'id': data.id,
                'name': data.name
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
    deleteTrickElement.addClass('hidden');
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
    deleteTrickElement.addClass('hidden');
    $.get(Routing.generate('media_edit', {
        id: data.id,
        slug: data.slug
    }), function (result) {
        $('#update-media-form-content').html(result);
        refreshCloseModalsList();
        updateMediaElement.removeClass('hidden');
    });
}

function displayCoverForm(data) {
    deleteMediaElement.addClass('hidden');
    updateMediaElement.addClass('hidden');
    deleteTrickElement.addClass('hidden');
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
        refreshCloseModalsList();
    });
    updateCoverImageElement.removeClass('hidden');
}

function displayTrickDeleteForm(data) {
    updateCoverImageElement.addClass('hidden');
    updateMediaElement.addClass('hidden');
    deleteMediaElement.addClass('hidden');
    let deleteTrickForm = $('#delete-trick-form');
    deleteTrickForm.attr('action', Routing.generate('trick_delete', {
        id: data.id,
    }));
    $("#trick-id-value").val(data.id);
    $('#delete-trick-name').html(data.name);
    deleteTrickElement.removeClass('hidden');
}
