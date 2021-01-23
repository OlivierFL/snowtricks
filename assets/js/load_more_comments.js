const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

$(document).ready(function () {
    const limit = $('.comment').length;
    let offset = limit;
    let spinner = $('#spinner');

    $("#load-more").click(function () {
        spinner.removeClass('hidden');
        spinner.addClass('animate-spin');
        $.getJSON(
            Routing.generate('load_more_comments', {
                offset: offset,
                limit: limit
            }), function (results) {
                console.log(results);
                if (0 === results.length) {
                    $('#load-more').html('Plus aucun commentaire').removeClass('bg-yellow-500 hover:bg-yellow-600').addClass('cursor-not-allowed text-black bg-gray-100');
                    return;
                }
                display(results);
            }).done(function () {
            spinner.addClass('hidden');
            spinner.removeClass('animate-spin');
            offset += limit;
        });
    });

    function display(results) {
        $.each(results, function (key, result) {
            $('#comments-list').append(
                `<div class="flex flex-row justify-center mb-10 comment">
                    <div class="w-2/12 flex flex-col justify-center items-center text-center text-gray-500 mr-2 pr-4">
                      <i class="fas fa-user border border-gray-500 rounded-full p-2"></i>
                      <p class="font-light text-sm">${result.author.username}</p>
                    </div>
                    <div class="w-10/12 p-2 border-b-2 border-l-2 border-yellow-500 text-sm ml-auto">
                      <p>${result.content}</p>
                    </div>
                </div>`);
        });
    }
});
