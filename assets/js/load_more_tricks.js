import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

export function displayTricks(results) {
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
