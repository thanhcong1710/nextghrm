jQuery(document).ready(function () {
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

        jQuery('.kiwias-video-search-box .inline-search').on('click', function () {
                var form = jQuery(this).parents('form');
                var group = jQuery(this).parents('.kiwias-video-group');
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