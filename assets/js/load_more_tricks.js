import Routing from './helper/routing';

export function displayTricks(results) {
    $.each(results, function (key, result) {
        let coverImageUrl = null;
        let coverImageAltText = null;
        for (const trickMedia of result.tricksMedia) {
            if (true === trickMedia.isCoverImage) {
                coverImageUrl = trickMedia.media.url;
                coverImageAltText = trickMedia.media.altText;
            }
        }
        $('#tricks-list').append(
            `<div class="flex flex-col bg-gray-100 rounded-md shadow-lg overflow-hidden trick">
              <a href="${Routing.generate('trick_detail', {slug: result.slug})}">
                  <div class="container">
                      <img src="${coverImageUrl ? "/uploads/tricks/" + coverImageUrl : "/build/img/hero.jpg"}"
                         alt="${coverImageAltText ?? "Snowtricks hero image"}"
                         class="w-full h-auto max-h-48 object-cover transform duration-500 ease hover:scale-105 rounded-t-md bg-gray-500"
                         loading="lazy"
                         width="600"
                         height="400"
                      >
                  </div>
              </a>
                  <div class="flex flex-row mt-auto justify-between items-center p-4">
                    <a href="${Routing.generate('trick_detail', {slug: result.slug})}" class="p-2 font-bold">${result.name}</a>
                    <div class="ml-auto">
                        <a href="${Routing.generate('trick_edit', {slug: result.slug})}">
                            <i class="cursor-pointer fas fa-pen mr-2 text-blue-700 hover:text-blue-800"></i>
                        </a>
                        <i class="fas fa-trash-alt text-red-600 hover:text-red-700"></i>
                    </div>
              </div>
            </div>`);
    });
}
