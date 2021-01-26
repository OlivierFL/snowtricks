$('#edit_profile_form_avatar').on('change', function (event) {
    let inputFile = event.currentTarget;
    $(inputFile).parent()
        .find('.js-file-label')
        .html(inputFile.files[0].name);
});

function readURL(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            let avatar = $('#avatar');
            if (avatar.is(':hidden')) {
                avatar.removeClass('hidden');
                $('#initials').addClass('hidden');
            }

            avatar.attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#edit_profile_form_avatar").change(function () {
    readURL(this);
});
