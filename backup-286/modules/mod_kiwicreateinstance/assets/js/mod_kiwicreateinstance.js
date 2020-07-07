jQuery(document).ready(function () {
        function createNewModal(config) {
                jQuery('.bs-example-modal-lg').remove();
                var modal = jQuery('<div></div>').addClass('modal fade bs-example-modal-lg');
                modal.attr('tabindex', -1);
                modal.attr('role', 'dialog');
                modal.attr('aria-labelledby', 'myLargeModalLabel');
                var modal_diaglog = jQuery('<div></div>').addClass('modal-dialog modal-small');
                modal_diaglog.html('<div class="modal-content">\n\
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">New message</h4></div>\n\
<div class="modal-body"></div>\n\
<div class="modal-footer">\n\
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>\n\
</div>');
                if (config.title) {
                        modal_diaglog.find('.modal-title').html(config.title);
                }

                if (config.html) {
                        modal_diaglog.find('.modal-body').html(config.html);
                        var iframe = modal_diaglog.find('iframe');
                        iframe.attr('width', '100%');
                        var iframe_url = iframe.attr('src');
                        iframe_url += '?autoplay=1';
                        iframe.attr('src', iframe_url);
                }

                modal.append(modal_diaglog);
                jQuery('html').append(modal);
                return modal;
        }

        function createProgress(percent) {
                var element = jQuery('<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">60%</div></div>');
                element.find('.progressbar').attr('aria-valuenow', percent);
                element.find('.progressbar').html(percent);
                element.find('.progressbar').attr('style', 'width: ' + percent + '%;');
                return element;
        }

        jQuery('.create_instance').on('click', function () {
                var button = jQuery(this);
                var form = button.parents('form');
                form.submit();
                return false;
        });
});