FD31.installer("EasyDiscuss", "resources", function($){
$.require.template.loader({"easydiscuss\/field.form.attachments.item":"<li data-attachment-item class=\"attachment-item new\">\n\t<i class=\"icon\"><\/i>\n\t<span data-attachment-title><\/span>\n\t<a data-attachment-remove-button href=\"javascript:void(0);\"> &bull; Remove<\/a>\n\t<input type=\"file\" name=\"filedata[]\" size=\"50\" data-attachment-file \/>\n<\/li><script type=\"text\/javascript\"><li data-attachment-item class=\"attachment-item new\">\n\t<i class=\"icon\"><\/i>\n\t<span data-attachment-title><\/span>\n\t<a data-attachment-remove-button href=\"javascript:void(0);\"> &bull; Remove<\/a>\n\t<input type=\"file\" name=\"filedata[]\" size=\"50\" data-attachment-file \/>\n<\/li><\/script>","easydiscuss\/conversation.read.item":"<li class=\"[%= post.className %]\">\n\t<div class=\"discuss-item discuss-item-message\">\n\t\t<div class=\"discuss-item-right\">\n\t\t\t<div class=\"discuss-item discuss-item-media\">\n\t\t\t\t<div>\n\t\t\t\t\t<div class=\"media\">\n\t\t\t\t\t\t<div class=\"media-object\">\n\t\t\t\t\t\t\t<a class=\"discuss-user-name\" href=\"[%= post.authorLink %]\">\n\t\t\t\t\t\t\t\t<div class=\"discuss-avatar avatar-medium\">\n\t\t\t\t\t\t\t\t\t<img src=\"[%= post.authorAvatar %]\" alt=\"[%= post.authorName %]\" \/>\n\t\t\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t\t<\/a>\n\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t<div class=\"media-body\">\n\t\t\t\t\t\t\t<div class=\"discuss-message-box\">\n\t\t\t\t\t\t\t\t<div class=\"discuss-user-name\">\n\t\t\t\t\t\t\t\t\t<a href=\"[%= post.authorLink %]\">[%= post.authorName %]<\/a>\n\t\t\t\t\t\t\t\t<\/div>\n\n\t\t\t\t\t\t\t\t<div class=\"discuss-message-content\">\n\t\t\t\t\t\t\t\t\t[%= post.message %]\n\t\t\t\t\t\t\t\t<\/div>\n\n\t\t\t\t\t\t\t\t<div class=\"discuss-date\">\n\t\t\t\t\t\t\t\t\t[%= post.lapsed %]\n\t\t\t\t\t\t\t\t\t<time datetime=\"[%= post.created %]\"><\/time>\n\t\t\t\t\t\t\t\t<\/div>\n\n\t\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t<\/div>\n\n\t\t\t\t\t<\/div>\n\t\t\t\t<\/div>\n\n\t\t\t<\/div>\n\t\t<\/div>\n\t<\/div>\n<\/li>\n<script type=\"text\/javascript\"><li class=\"[%= post.className %]\">\n\t<div class=\"discuss-item discuss-item-message\">\n\t\t<div class=\"discuss-item-right\">\n\t\t\t<div class=\"discuss-item discuss-item-media\">\n\t\t\t\t<div>\n\t\t\t\t\t<div class=\"media\">\n\t\t\t\t\t\t<div class=\"media-object\">\n\t\t\t\t\t\t\t<a class=\"discuss-user-name\" href=\"[%= post.authorLink %]\">\n\t\t\t\t\t\t\t\t<div class=\"discuss-avatar avatar-medium\">\n\t\t\t\t\t\t\t\t\t<img src=\"[%= post.authorAvatar %]\" alt=\"[%= post.authorName %]\" \/>\n\t\t\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t\t<\/a>\n\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t<div class=\"media-body\">\n\t\t\t\t\t\t\t<div class=\"discuss-message-box\">\n\t\t\t\t\t\t\t\t<div class=\"discuss-user-name\">\n\t\t\t\t\t\t\t\t\t<a href=\"[%= post.authorLink %]\">[%= post.authorName %]<\/a>\n\t\t\t\t\t\t\t\t<\/div>\n\n\t\t\t\t\t\t\t\t<div class=\"discuss-message-content\">\n\t\t\t\t\t\t\t\t\t[%= post.message %]\n\t\t\t\t\t\t\t\t<\/div>\n\n\t\t\t\t\t\t\t\t<div class=\"discuss-date\">\n\t\t\t\t\t\t\t\t\t[%= post.lapsed %]\n\t\t\t\t\t\t\t\t\t<time datetime=\"[%= post.created %]\"><\/time>\n\t\t\t\t\t\t\t\t<\/div>\n\n\t\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t<\/div>\n\n\t\t\t\t\t<\/div>\n\t\t\t\t<\/div>\n\n\t\t\t<\/div>\n\t\t<\/div>\n\t<\/div>\n<\/li>\n<\/script>","easydiscuss\/field.form.polls.answer":"<li class=\"pollAnswers mb-5\">\n\t<div class=\"input-append\">\n\t\t<input type=\"text\" name=\"pollitems[]\" class=\"input-xlarge answerText\" \/>\n\t[% if( showRemove ){ %]\n\t<a href=\"javascript:void(0);\" class=\"btn btn-danger removeItem\"><i class=\"icon-remove\"><\/i> <\/a>\n\t[% } %]\n\t<\/div>\n<\/li>\n<script type=\"text\/javascript\"><li class=\"pollAnswers mb-5\">\n\t<div class=\"input-append\">\n\t\t<input type=\"text\" name=\"pollitems[]\" class=\"input-xlarge answerText\" \/>\n\t[% if( showRemove ){ %]\n\t<a href=\"javascript:void(0);\" class=\"btn btn-danger removeItem\"><i class=\"icon-remove\"><\/i> <\/a>\n\t[% } %]\n\t<\/div>\n<\/li>\n<\/script>","easydiscuss\/comment.form":"<form name=\"discussCommentForm\">\n<div class=\"discuss-comment-form\">\n\t\t<div class=\"clearfull\">\n\t\t\t<div class=\"textarea_wrap\">\n\t\t\t\t<textarea id=\"comment\" name=\"comment\" class=\"textarea full-width commentMessage\"><\/textarea>\n\t\t\t<\/div>\n\t\t<\/div>\n\n\t\t<div class=\"row-fluid\">\n\t\t\t\t\t\t<div class=\"pull-left mt-5\">\n\t\t\t\t<label class=\"checkbox\">\n\t\t\t\t\t<input type=\"checkbox\" name=\"tnc\" value=\"1\" class=\"commentTnc\" \/>\n\t\t\t\t\tI have read and agreed to the <a href=\"javascript:void(0);\" class=\"comment-terms termsLink\">Terms and Conditions<\/a>\n\t\t\t\t<\/label>\n\t\t\t<\/div>\n\t\t\t\n\t\t\t<div class=\"pull-right mt-5\">\n\t\t\t\t<a href=\"javascript:void(0);\" class=\"btn btn-small cancelButton\">Cancel<\/a>\n\t\t\t\t<a href=\"javascript:void(0);\" class=\"btn btn-small btn-primary saveButton\">Submit<\/a>\n\t\t\t\t<span class=\"pull-right commentLoader discuss-loader\" style=\"display: none;\"><\/span>\n\t\t\t<\/div>\n\t\t<\/div>\n\n\t<\/div>\n<\/div>\n<input type=\"hidden\" name=\"post_id\" class=\"postId\" value=\"[%= id %]\">\n<\/form>\n<script type=\"text\/javascript\"><form name=\"discussCommentForm\">\n<div class=\"discuss-comment-form\">\n\t\t<div class=\"clearfull\">\n\t\t\t<div class=\"textarea_wrap\">\n\t\t\t\t<textarea id=\"comment\" name=\"comment\" class=\"textarea full-width commentMessage\"><\/textarea>\n\t\t\t<\/div>\n\t\t<\/div>\n\n\t\t<div class=\"row-fluid\">\n\t\t\t\t\t\t<div class=\"pull-left mt-5\">\n\t\t\t\t<label class=\"checkbox\">\n\t\t\t\t\t<input type=\"checkbox\" name=\"tnc\" value=\"1\" class=\"commentTnc\" \/>\n\t\t\t\t\tI have read and agreed to the <a href=\"javascript:void(0);\" class=\"comment-terms termsLink\">Terms and Conditions<\/a>\n\t\t\t\t<\/label>\n\t\t\t<\/div>\n\t\t\t\n\t\t\t<div class=\"pull-right mt-5\">\n\t\t\t\t<a href=\"javascript:void(0);\" class=\"btn btn-small cancelButton\">Cancel<\/a>\n\t\t\t\t<a href=\"javascript:void(0);\" class=\"btn btn-small btn-primary saveButton\">Submit<\/a>\n\t\t\t\t<span class=\"pull-right commentLoader discuss-loader\" style=\"display: none;\"><\/span>\n\t\t\t<\/div>\n\t\t<\/div>\n\n\t<\/div>\n<\/div>\n<input type=\"hidden\" name=\"post_id\" class=\"postId\" value=\"[%= id %]\">\n<\/form>\n<\/script>","easydiscuss\/post.notification":"<div class=\"discussNotification\">\n\t<div class=\"replyContainer\"[% if(newReply < 1) { %] style=\"display: none;\"[% } %]><span class=\"replyCount\">[%= newReply %]<\/span> <span class=\"replyText\">new reply<\/span><\/div>\n\n\t<div class=\"commentContainer\"[% if(newComment < 1) { %] style=\"display: none;\"[% } %]><span class=\"commentCount\">[%= newComment %]<\/span> <span class=\"commentText\">new comment<\/span><\/div>\n\n\t<a href=\"javascript:document.location.reload(true)\" class=\"btn btn btn-mini btn-success\">Refresh page<\/a>\n\n<\/div>\n<script type=\"text\/javascript\"><div class=\"discussNotification\">\n\t<div class=\"replyContainer\"[% if(newReply < 1) { %] style=\"display: none;\"[% } %]><span class=\"replyCount\">[%= newReply %]<\/span> <span class=\"replyText\">new reply<\/span><\/div>\n\n\t<div class=\"commentContainer\"[% if(newComment < 1) { %] style=\"display: none;\"[% } %]><span class=\"commentCount\">[%= newComment %]<\/span> <span class=\"commentText\">new comment<\/span><\/div>\n\n\t<a href=\"javascript:document.location.reload(true)\" class=\"btn btn btn-mini btn-success\">Refresh page<\/a>\n\n<\/div>\n<\/script>"});
$.require.language.loader({"COM_EASYDISCUSS_EXCEED_ATTACHMENT_LIMIT":"You have reached the attachment limit per post.","COM_EASYDISCUSS_TERMS_PLEASE_ACCEPT":"Please accept the terms and conditions first","COM_EASYDISCUSS_COMMENT_SUCESSFULLY_ADDED":"Comment successfully added.","COM_EASYDISCUSS_COMMENT_LOAD_MORE":"Load more comments","COM_EASYDISCUSS_COMMENT_LOADING_MORE_COMMENTS":"Loading more comments","COM_EASYDISCUSS_COMMENT_LOAD_ERROR":"Load error","COM_EASYDISCUSS_CONVERSATION_EMPTY_CONTENT":"Please enter some message.","COM_EASYDISCUSS_CUSTOMFIELDS_DISPLAY_ERROR":"Display error.","COM_EASYDISCUSS_BBCODE_BOLD":"Bold","COM_EASYDISCUSS_BBCODE_ITALIC":"Italic","COM_EASYDISCUSS_BBCODE_UNDERLINE":"Underline","COM_EASYDISCUSS_BBCODE_URL":"Link","COM_EASYDISCUSS_BBCODE_TITLE":"Title","COM_EASYDISCUSS_BBCODE_PICTURE":"Picture","COM_EASYDISCUSS_BBCODE_VIDEO":"Video","COM_EASYDISCUSS_BBCODE_BULLETED_LIST":"Bulleted list","COM_EASYDISCUSS_BBCODE_NUMERIC_LIST":"Numeric list","COM_EASYDISCUSS_BBCODE_LIST_ITEM":"List item","COM_EASYDISCUSS_BBCODE_QUOTES":"Quotes","COM_EASYDISCUSS_BBCODE_CODE":"Code","COM_EASYDISCUSS_BBCODE_HAPPY":"Happy","COM_EASYDISCUSS_BBCODE_SMILE":"Smile","COM_EASYDISCUSS_BBCODE_SURPRISED":"Surprised","COM_EASYDISCUSS_BBCODE_TONGUE":"Tongue","COM_EASYDISCUSS_BBCODE_UNHAPPY":"Unhappy","COM_EASYDISCUSS_BBCODE_WINK":"Wink","COM_EASYDISCUSS_FAVOURITE_BUTTON_UNFAVOURITE":"Unfavourite","COM_EASYDISCUSS_FAVOURITE_BUTTON_FAVOURITE":"Mark as Favourite","COM_EASYDISCUSS_UNLIKE_THIS_POST":"Unlike this post.","COM_EASYDISCUSS_LIKE_THIS_POST":"Like this post.","COM_EASYDISCUSS_UNLIKE":"Unlike","COM_EASYDISCUSS_LIKES":"Likes","COM_EASYDISCUSS_NOTIFICATION_NEW_REPLIES":"new replies","COM_EASYDISCUSS_NOTIFICATION_NEW_COMMENTS":"new comments","COM_EASYDISCUSS_PLEASE_SELECT_CATEGORY_DESC":"Please select a category first before submitting the form.","COM_EASYDISCUSS_POST_TITLE_CANNOT_EMPTY":"Please enter a title for your discussion","COM_EASYDISCUSS_POST_CONTENT_IS_EMPTY":"Content is empty, please enter some content.","COM_EASYDISCUSS_SUCCESS":"Success","COM_EASYDISCUSS_FAIL":"Fail","COM_EASYDISCUSS_REPLY_LOADING_MORE_COMMENTS":"Loading more replies","COM_EASYDISCUSS_REPLY_LOAD_ERROR":"Load error"});
});
