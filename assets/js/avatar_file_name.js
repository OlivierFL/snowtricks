$('#edit_profile_form_avatar').on('change', function (event) {
    let inputFile = event.currentTarget;
    $(inputFile).parent()
        .find('.js-file-label')
        .html(inputFile.files[0].name);
});
