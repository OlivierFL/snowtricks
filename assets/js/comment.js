// Handle comment moderation display in modal
import Routing from "./helper/routing";

let commentElement = $('.modal-body #comment');

export function handleComment(data) {
    $("#comment .comment-modal-content").html(getCommentContent(data.action));
    $("#comment .id-value").val(data.id);
    commentElement.removeClass('hidden');

    if ('approve' === data.action) {
        $('#modal-form-comment').attr('action', Routing.generate('admin_moderate_comment', {
            isValid: 1
        }));
    } else if ('delete' === data.action) {
        $('#modal-form-comment').attr('action', Routing.generate('admin_moderate_comment', {
            isValid: 0
        }));
    }
}

// Get HTML content for comment moderation in modal
function getCommentContent(action) {
    if ('approve' === action) {
        return 'Confirmer la publication du commentaire ?';
    } else if ('delete' === action) {
        return 'Confirmer la modération du commentaire ? Il ne sera plus visible, mais pas définitivement supprimé';
    }
}
