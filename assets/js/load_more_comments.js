export function displayComments(results) {
    $.each(results, function (key, result) {
        let updatedAt = formatUpdatedAt(result.updatedAt);
        if (!result.author.avatar) {
            renderComment(result, updatedAt);
        } else {
            renderCommentWithAvatar(result, updatedAt);
        }
    });
}

function renderComment(result, updatedAt) {
    $('#comments-list').append(
        `<div class="flex flex-col lg:flex-row justify-center mb-10 comments">
          <div class="lg:w-2/12 flex flex-col justify-center items-center text-center text-gray-500 mr-2 pr-4">
             <div class="flex justify-center items-center w-6 h-6 lg:w-8 lg:h-8 rounded-full border-2 border-yellow-500 bg-gray-500 text-white p-5 capitalize"
                 title="${result.author.username}">
            <div class="text-xl cursor-default">${result.author.username.slice(0, 1)}</div>
            </div>
            <p class="font-light text-sm mb-2">${result.author.username}</p>
            <p class="font-light text-sm">${updatedAt}</p>
          </div>
          <div class="w-full lg:w-10/12 p-2 border-b-2 border-l-2 border-yellow-500 text-sm lg:ml-auto">
            <p>${result.content}</p>
          </div>
        </div>
    `);
}

function renderCommentWithAvatar(result, updatedAt) {
    $('#comments-list').append(
        `<div class="flex flex-col lg:flex-row justify-center mb-10 comments">
          <div class="lg:w-2/12 flex flex-col justify-center items-center text-center text-gray-500 mr-2 pr-4">
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
          <div class="w-full lg:w-10/12 p-2 border-b-2 border-l-2 border-yellow-500 text-sm lg:ml-auto">
            <p>${result.content}</p>
          </div>
        </div>
    `);
}

function formatUpdatedAt(updatedAt) {
    let day = new Intl.DateTimeFormat('fr-FR').format(new Date(updatedAt));
    let hour = new Intl.DateTimeFormat('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(updatedAt));
    return day + ' à ' + hour;
}
