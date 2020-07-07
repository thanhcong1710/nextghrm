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
        $(document).on('click', '.agentregister-button', function () {
                var button = $(this);
                button.find('span').removeClass("hidden");
                var form = $(this).parents("form");
                var response_div = form.find(".response");
                response_div.html("");
                response_div.removeClass("alert alert-danger alert-success");
                var action = "send";
                var request = {
                        'option': 'com_ajax',
                        'module': 'kiwiagentregister',
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