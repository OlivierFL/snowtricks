// Get the ul that holds the collection of tags
let $mediasCollectionHolder = $('ul.medias');
// count the current form inputs we have (e.g. 2), use that as the new
// index when inserting a new item (e.g. 2)
$mediasCollectionHolder.data('index', $mediasCollectionHolder.find('input').length);

$('body').on('click', '.js-add-item-link', function (e) {
    let $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
    // add a new tag form (see next code block)
    addFormToCollection($collectionHolderClass);
});

function addFormToCollection($collectionHolderClass) {
    // Get the ul that holds the collection of tags
    let $collectionHolder = $('.' + $collectionHolderClass);

    // Get the data-prototype explained earlier
    let prototype = $collectionHolder.data('prototype');

    // get the new index
    let index = $collectionHolder.data('index');

    let newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a media" link li
    let $newFormLi = $('<li class="mb-6 js-media-select"></li>').append(newForm);
    // Add the new form at the end of the list
    $collectionHolder.append($newFormLi);

    const imageRadio = $('#trick_tricksMedia_' + index + '_media_type_0');
    const videoRadio = $('#trick_tricksMedia_' + index + '_media_type_1');

    imageRadio.bind('contentChanged', function (radioType) {
        displayField(radioType);
    });
    imageRadio.change(function (event) {
        event.preventDefault();
        imageRadio.trigger('contentChanged', 'image');
    });

    videoRadio.bind('contentChanged', function (radioType) {
        displayField(radioType);
    });
    videoRadio.change(function (event) {
        event.preventDefault();
        videoRadio.trigger('contentChanged', 'video');
    });

    function displayField(radioType) {
        $('label[for="trick_tricksMedia_' + index + '_media_altText"]').removeClass('hidden');
        $('#trick_tricksMedia_' + index + '_media_altText').removeClass('hidden');

        if ('image' === radioType.currentTarget.value) {
            $('label[for="trick_tricksMedia_' + index + '_media_video_url"]').addClass('hidden');
            $('#trick_tricksMedia_' + index + '_media_video_url').addClass('hidden');
            $('#trick_tricksMedia_' + index + '_media_video_url_help').addClass('hidden');
            $('label[for="trick_tricksMedia_' + index + '_media_image"]').removeClass('hidden');
            $('#trick_tricksMedia_' + index + '_media_image').removeClass('hidden');
            $('#trick_tricksMedia_' + index + '_media_image_help').removeClass('hidden');
        } else if ('video' === radioType.currentTarget.value) {
            $('label[for="trick_tricksMedia_' + index + '_media_image"]').addClass('hidden');
            $('#trick_tricksMedia_' + index + '_media_image').addClass('hidden');
            $('#trick_tricksMedia_' + index + '_media_image_help').addClass('hidden');
            $('label[for="trick_tricksMedia_' + index + '_media_video_url"]').removeClass('hidden');
            $('#trick_tricksMedia_' + index + '_media_video_url').removeClass('hidden');
            $('#trick_tricksMedia_' + index + '_media_video_url_help').removeClass('hidden');
        }
    }
}
