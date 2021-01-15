const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

$(document).ready(function () {
    const limit = $('.trick').length;
    let offset = limit;
    let spinner = $('#spinner');

    $("#load-more").click(function () {
        spinner.removeClass('hidden');
        spinner.addClass('animate-spin');
        $.getJSON(
            Routing.generate('load_more_tricks', {
                offset: offset,
                limit: limit
            }), function (results) {
                console.log(results);
                if (0 === results.length) {
                    $('#load-more').html('Plus aucun résultat').removeClass('bg-yellow-500 hover:bg-yellow-600').addClass('cursor-not-allowed text-black bg-gray-100');
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
            $('#tricks-list').append(
                `<div class="bg-gray-100 rounded-md shadow-lg overflow-hidden trick">
                  <a href="${Routing.generate('trick_detail', {slug: result.slug})}">
                      <div class="container">
                          <img src="/build/images/${result.medias[0].url}"
                             alt="${result.medias[0].altText}"
                             class="w-full h-auto max-h-48 object-cover transform duration-500 ease hover:scale-105 rounded-t-md bg-gray-500"
                             loading="lazy"
                             width="600"
                             height="400"
                          >
                      </div>
                  </a>
                      <div class="flex flex-row justify-between items-center p-4">
                      <a href="${Routing.generate('trick_detail', {slug: result.slug})}" class="p-2 font-bold">${result.name}</a>
                        <div class="ml-auto">
                            <i class="fas fa-pen mr-2 text-blue-700 hover:text-blue-800"></i>
                            <i class="fas fa-trash-alt text-red-600 hover:text-red-700"></i>
                        </div>
                  </div>
                </div>`);
        });
    }
});
