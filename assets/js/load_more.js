import Routing from './helper/routing';
import {displayComments} from "./load_more_comments";
import {displayTricks} from "./load_more_tricks";
import {refreshModalsList} from "./modal";

const tricksLimit = $('.tricks').length;
let tricksOffset = tricksLimit;
const commentsLimit = $('.comments').length;
let commentsOffset = commentsLimit;

$("#load-more-comments-btn").click(function () {
    loadResults('comments', commentsLimit, commentsOffset);
});

$("#load-more-tricks-btn").click(function () {
    loadResults('tricks', tricksLimit, tricksOffset);
});

function loadResults(type, limit, offset) {
    let spinner = $('#spinner-' + type);
    showSpinner(spinner);
    let id = $('#load-more-comments-btn').data('trick-id') ?? null;
    $.getJSON(
        Routing.generate('load_more_' + type, {
            offset,
            limit,
            id
        }), function (results) {
            if (0 === results.length || 0 === results.tricks?.length) {
                showNoMoreResultsButton(type);
                return;
            }
            if ('comments' === type) {
                displayComments(results);
                commentsOffset += commentsLimit;
            } else if ('tricks' === type) {
                displayTricks(results);
                tricksOffset += tricksLimit;
            }
        }).done(function () {
        refreshModalsList();
        hideSpinner(spinner);
    });
}

function showSpinner(spinner) {
    spinner.removeClass('hidden');
    spinner.addClass('animate-spin');
}

function hideSpinner(spinner) {
    spinner.addClass('hidden');
    spinner.removeClass('animate-spin');
}

function showNoMoreResultsButton(type) {
    if ('comments' === type) {
        $('#load-more-comments-btn').html('Plus aucun commentaire').removeClass('bg-yellow-500 hover:bg-yellow-600').addClass('cursor-not-allowed text-black bg-gray-100');
    } else {
        $('#load-more-tricks-btn').html('Plus aucun résultat').removeClass('bg-yellow-500 hover:bg-yellow-600').addClass('cursor-not-allowed text-black bg-gray-100');
    }
}
