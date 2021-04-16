import Routing from './helper/routing';

export function displayTricks(results) {
    let tricks = results.tricks;
    let currentUser = results.user;
    tricks.forEach((trick) => {
        let {coverImageUrl, coverImageAltText} = getCoverImage(trick);
        let user = {
            'isAuthenticated': false,
            'isAuthor': false
        };
        if (null !== currentUser) {
            user.isAuthenticated = true;
            if (currentUser.id === trick.author.id || currentUser.roles.includes('ROLE_ADMIN')) {
                user.isAuthor = true;
            }
        }
        addHtmlTemplate(trick, coverImageUrl, coverImageAltText, user);
    });
}

function addHtmlTemplate(trick, coverImageUrl, coverImageAltText, user) {
    const icons = getIcons(user, trick);
    let editIcon = icons.editIcon;
    let deleteIcon = icons.deleteIcon;
    $('#tricks-list').append(
        `<div class="flex flex-col bg-gray-100 rounded-md shadow-lg overflow-hidden trick">
              <a href="${Routing.generate('trick_detail', {slug: trick.slug})}">
                  <div class="container">
                      <img src="${coverImageUrl ? "/uploads/tricks/" + coverImageUrl : "/build/images/hero_02.jpg"}"
                         alt="${coverImageAltText ?? "Snowtricks hero image"}"
                         class="w-full h-auto max-h-48 object-cover transform duration-500 ease hover:scale-105 rounded-t-md bg-gray-500"
                         loading="lazy"
                         width="600"
                         height="400"
                      >
                  </div>
              </a>
                  <div class="flex flex-row mt-auto justify-between items-center p-4">
                    <a href="${Routing.generate('trick_detail', {slug: trick.slug})}" class="p-2 font-bold">${trick.name}</a>
                    <div class="ml-auto flex">
                        ${editIcon}
                        ${deleteIcon}
                    </div>
              </div>
            </div>`
    );
}

function getCoverImage(trick) {
    let coverImageUrl = null;
    let coverImageAltText = null;
    for (const trickMedia of trick.tricksMedia) {
        if (true === trickMedia.isCoverImage) {
            coverImageUrl = trickMedia.media.url;
            coverImageAltText = trickMedia.media.altText;
        }
    }
    return {coverImageUrl, coverImageAltText};
}

function getIcons(user, trick) {
    let editIcon = '';
    let deleteIcon = '';
    if (user.isAuthenticated) {
        editIcon = `<a href="${Routing.generate('trick_edit', {slug: trick.slug})}">
                            <i class="cursor-pointer fas fa-pen mr-2 text-blue-700 hover:text-blue-800"></i>
                        </a>`
    }
    if (user.isAuthor) {
        deleteIcon = `<div class="modal-open"
                       data-id="${trick.id}"
                       data-name="${trick.name}"
                       data-action="trick-delete"
                  >
                    <i class="cursor-pointer fas fa-trash-alt text-red-600 hover:text-red-700"></i>
                  </div>`
    }
    return {editIcon, deleteIcon};
}
