const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

const limit = $('.comment').length;
let offset = limit;
let spinner = $('#spinner-comments');

$("#load-more-comments-btn").click(function () {
    spinner.removeClass('hidden');
    spinner.addClass('animate-spin');
    $.getJSON(
        Routing.generate('load_more_comments', {
            offset: offset,
            limit: limit
        }), function (results) {
            if (0 === results.length) {
                $('#load-more-comments-btn').html('Plus aucun commentaire').removeClass('bg-yellow-500 hover:bg-yellow-600').addClass('cursor-not-allowed text-black bg-gray-100');
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
        let updatedAt = formatUpdatedAt();
        if (!result.author.avatar) {
            $('#comments-list').append(
                `<div class="flex flex-row justify-center mb-10 comment">
                        <div class="w-2/12 flex flex-col justify-center items-center text-center text-gray-500 mr-2 pr-4">
                           <div class="flex justify-center items-center w-6 h-6 lg:w-8 lg:h-8 rounded-full border-2 border-yellow-500 bg-gray-500 text-white p-5 capitalize"
                         title="${result.author.username}">
                             <div class="text-xl cursor-default">${result.author.username.slice(0, 1)}</div>
                           </div>
                          <p class="font-light text-sm mb-2">${result.author.username}</p>
                          <p class="font-light text-sm">${updatedAt}</p>
                        </div>
                        <div class="w-10/12 p-2 border-b-2 border-l-2 border-yellow-500 text-sm ml-auto">
                          <p>${result.content}</p>
                      </div>
                  </div>
                `);
        } else {
            $('#comments-list').append(
                `<div class="flex flex-row justify-center mb-10 comment">
                        <div class="w-2/12 flex flex-col justify-center items-center text-center text-gray-500 mr-2 pr-4">
                            <img src="/uploads/avatars/${result.author.avatar}"
                                 alt="${result.author.username}"
                                 title="${result.author.username}"
                                 class="object-cover w-11 h-11 rounded-full border-yellow-500 border-2"
                                 width="44"
                                 height="44"
                            >
                      <p class="font-light text-sm mb-2">${result.author.username}</p>
                      <p class="font-light text-sm">${updatedAt}</p>
                    </div>
                    <div class="w-10/12 p-2 border-b-2 border-l-2 border-yellow-500 text-sm ml-auto">
                      <p>${result.content}</p>
                    </div>
                </div>
                `)
        }

        function formatUpdatedAt() {
            let day = new Intl.DateTimeFormat('fr-FR').format(new Date(result.updatedAt));
            let hour = new Intl.DateTimeFormat('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            }).format(new Date(result.updatedAt))
            return day + ' à ' + hour;
        }
    });
}
