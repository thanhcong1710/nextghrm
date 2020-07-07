(function ($) {
        $.fn.serializeObject = function ()
        {
                var o = {};
                var a = this.serializeArray();
                $.each(a, function () {
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

        function createModal(config) {
                $('.bs-example-modal-lg').remove();
                var modal = $('<div></div>').addClass('modal fade bs-example-modal-lg');
                modal.attr('tabindex', -1);
                modal.attr('role', 'dialog');
                modal.attr('aria-labelledby', 'myLargeModalLabel');
                var modal_diaglog = $('<div></div>').addClass('modal-dialog modal-lg');
                modal_diaglog.html('<div class="modal-content">\n\
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="site-logo"></div><h3 class="header-title">New message</h3></div>\n\
<div class="modal-body"></div>\n\
<div class="modal-footer">\n\
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>\n\
</div>');
                if (config.title) {
                        modal_diaglog.find('.header-title').html(config.title);
                }

                var image = $('<img>').attr('src', $('.erp-logo').attr('src'));
                modal_diaglog.find('.site-logo').html('').append(image);

                if (config.html) {
                        modal_diaglog.find('.modal-body').html(config.html);
                }

                modal.append(modal_diaglog);
                $('html').append(modal);
                return modal;
        }

        $(document).off('.getContact').on('click', '.getContact', function () {
                var button = $(this);
                var title = button.attr('title');
                var action = "get";
                var request = {
                        'option': 'com_ajax',
                        'module': 'contacteverywhere',
                        'cmd': action,
                        'title': title,
                        'format': 'json'
                };
                $.ajax({
                        type: 'POST',
                        data: request,
                        dataType: 'json',
                        success: function (response) {
                                if (response.data.error) {
                                        return false;
                                } else {
                                        var modal = createModal(response.data);
                                        modal.modal('show');
                                        modal.on('hidden.bs.modal', function (e) {
                                                modal.remove();
                                        });
                                }
                        },
                        error: function (response) {
                                return false;
                        }
                });
                return false;
        });

        $(document).on('click', '.contact-button', function () {
                var button = $(this);
                button.find('span').removeClass("hidden");
                var form = $(this).parents("form");
                var response_div = form.find(".response");
                response_div.html("");
                response_div.removeClass("alert alert-danger alert-success");
                var action = "send";
                var request = {
                        'option': 'com_ajax',
                        'module': 'contacteverywhere',
                        'cmd': action,
                        'data': form.serializeObject(),
                        'format': 'json'
                };
                $.ajax({
                        type: 'POST',
                        data: request,
                        dataType: 'json',
                        success: function (response) {
                                if (response.data.error) {
                                        response_div.addClass("alert alert-danger");
                                        response_div.removeClass("alert-success");
                                        response_div.html(response.data.html);
                                        button.find('span').addClass("hidden");

                                } else {
                                        response_div.removeClass("alert-danger");
                                        response_div.addClass("alert alert-success");
                                        response_div.html(response.data.html);
                                        form.find("input").val("");
                                        form.find("textarea").val("");
                                        button.find('span').addClass("hidden");
                                }
                        },
                        error: function (response) {
                                var data = '',
                                        obj = $.parseJSON(response.responseText);
                                for (key in obj) {
                                        data = data + ' ' + obj[key] + '<br/>';
                                }
                                response_div.html(data);
                                button.find('span').addClass("hidden");
                        }
                });
                return false;
        });
})(jQuery);