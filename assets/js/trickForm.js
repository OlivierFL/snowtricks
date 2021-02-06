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
    let $newFormLi = $('<li class="mb-6"></li>').append(newForm);
    // Add the new form at the end of the list
    $collectionHolder.append($newFormLi)
}

