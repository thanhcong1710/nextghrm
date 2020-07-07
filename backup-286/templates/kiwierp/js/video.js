jQuery(document).ready(function () {
        jQuery(document).off('.video-item.active a').on('click', '.video-item.active a', function () {
                jQuery(this).parents('.video-item').click();
                return false;
        });

        function createModal(config) {
                jQuery('.bs-example-modal-lg').remove();
                var modal = jQuery('<div></div>').addClass('modal fade bs-example-modal-lg');
                modal.attr('tabindex', -1);
                modal.attr('role', 'dialog');
                modal.attr('aria-labelledby', 'myLargeModalLabel');
                var modal_diaglog = jQuery('<div></div>').addClass('modal-dialog modal-lg');
                modal_diaglog.html('<div class="modal-content">\n\
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="site-logo"></div><h3 class="header-title">New message</h3></div>\n\
<div class="modal-body"></div>\n\
<div class="modal-footer">\n\
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>\n\
</div>');
                if (config.title) {
                        modal_diaglog.find('.header-title').html(config.title);
                }

                var image = jQuery('<img>').attr('src', jQuery('.erp-logo').attr('src'));
                modal_diaglog.find('.site-logo').html('').append(image);

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

        jQuery(document).off('.video-item.active').on('click', '.video-item.active', function () {
                var button = jQuery(this);
                var id = button.attr('data-id');
                var action = "get";
                var request = {
                        'option': 'com_ajax',
                        'plugin': 'kiwivideo',
                        'cmd': action,
                        'id': id,
                        'format': 'json'
                };
                jQuery.ajax({
                        type: 'POST',
                        data: request,
                        dataType: 'json',
                        success: function (response) {
                                if (response.data.error) {
                                        return false;
                                } else {
                                        var modal = createModal(response.data[0]);
                                        modal.modal('show');
                                        modal.on('hidden.bs.modal', function (e) {
                                                modal.remove();
                                        });
                                        request.cmd = 'hit';
                                        jQuery.ajax({
                                                type: 'POST',
                                                data: request,
                                                dataType: 'json',
                                                success: function (response) {
                                                        if (response.data.error) {
                                                                return false;
                                                        }
                                                }
                                        });
                                }
                        },
                        error: function (response) {
                                return false;
                        }
                });
        });

        jQuery.fn.serializeObject = function ()
        {
                var o = {};
                var a = this.serializeArray();
                jQuery.each(a, function () {
                        if (o[this.name]) {
                                if (!o[this.name].push) {
                                        o[this.name] = [o[this.name]];
                                }
                                o[this.name].push(this.value || '');
                        } else {
                                o[this.name] = this.value || '';
                        }
                });
                return o;
        };

        jQuery('.kiwierp-template .inline-search').on('click', function () {
                var form = jQuery(this).parents('form');
                var group = jQuery(this).parents('.kiwerp-video-group');
                var request = {
                        'option': 'com_ajax',
                        'plugin': 'kiwivideo',
                        'cmd': 'search',
                        'format': 'json',
                        'data': form.serializeObject()
                };
                jQuery.ajax({
                        type: 'POST',
                        data: request,
                        dataType: 'json',
                        success: function (response) {
                                if (response.data.error) {
                                        return false;
                                } else {
                                        group.find('.video-response').html(response.data[0].html);
                                }
                        },
                        error: function (response) {
                                return false;
                        }
                });
                return false;
        });
});