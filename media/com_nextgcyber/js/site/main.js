/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
var nc_modal = nc_modal || {};
function nc_modal(options) {
    this.id = '';
    this.modal = '';
    this.html = '';
    this.label = '';
    this.footer = '';
    this.subtitle = '';
    this.subtitle_class = '';
    var object = this;
    if (options) {
        jQuery.each(options, function (index, item) {
            object[index] = item;
        });
    }

    this.random = function (n) {
        if (!n)
        {
            n = 20;
        }

        var text = '';
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        for (var i = 0; i < n; i++)
        {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return text;
    };

    this.getDialog = function () {
        var logo = jQuery('body').find('.erp-logo');
        var img = '<img src="' + logo.attr('src') + '"/>';
        var dialog = '<div class="modal-content">\
                                        <div class="modal-header">\
                                            <button type="button" role="presentation" class="close" data-dismiss="modal">×</button>\
                                            <div class="site-logo">' + img + '</div>\
                                            <h3 class="header-title">' + this.label + '</h3>\
                                        </div>\
                                        <div class="modal-subtitle ' + this.subtitle_class + '">' + this.subtitle + '</div>\
                                        <div class="modal-body">\
                                                <div class="main">' + this.html + '</div>\
                                        </div>\
                                        <div class="modal-footer">' + this.footer + '</div>\
                                </div>';
        return dialog;
    };

    this.getModalHtml = function () {
        var dialog = object.getDialog();
        this.modal = '\
                <div class="nextgcyber-modal modal fade in" id="' + this.id + '" aria-hidden="" data-backdrop="static" data-keyboard="false">\
                        <div class="modal-dialog">' + dialog + '</div>\
                </div>';
        return this.modal;
    };

    this.init = function () {
        jQuery('.nextgcyber-modal').remove();
        this.id = 'nc-modal-' + object.random();
        jQuery("#" + object.id).remove();
        this.modal = this.getModalHtml();
        var check = jQuery("body").find("#" + this.id);
        if (check.length <= 0) {
            jQuery("body").append(this.modal);
        } else {
            object.init();
        }
    };
    this.init();

    this.remove = function () {
        jQuery('#' + object.id).remove();
    };

    this.display = function () {
        var modal = jQuery("#" + object.id);
        modal.modal('show');
        modal.on('hidden', function () {
            object.remove();
        });
    };

    this.update = function () {
        jQuery("#" + object.id).find('.modal-dialog').html(object.getDialog());
    };

    this.setBody = function (html) {
        object.html = html;
        object.update();
    };

    this.setError = function (html) {
        object.html = '<div class="alert alert-danger">' + html + '</div>';
        object.update();
    };

    if (this.error) {
        this.setError(this.error);
    }

    this.setHeader = function (html) {
        object.label = html;
        object.update();
    };

    this.setFooter = function (html) {
        object.footer = html;
        object.update();
    };

    this.setSubTitle = function (html) {
        object.subtitle = html;
        object.update();
    };

    this.setSubTitleClass = function (html) {
        object.subtitle_class = html;
        object.update();
    };

    this.setLoading = function () {
        object.html = '<div class="align-center"><span class="fa fa-cog fa-spin fa-5x"></span></div>';
        object.update();
    };

    this.setRedirect = function (url) {
        object.redirect_url = url;
        setTimeout(function () {
            window.location.href = object.redirect_url;
            return;
        }, 5000);
    };

    this.displayProgressBar = function (percentage, log) {
        object.html = '<div class="align-center"><span class="fa fa-cog fa-spin fa-5x"></span></div>';
        object.html += '<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="' + percentage + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + percentage + '%;">' + percentage + '%</div></div>';
        object.html += '<div class="align-center">' + log + '</div>';
        object.log = log;
        object.update();
    };

    if (object.redirect_url) {
        object.setRedirect(object.redirect_url);
    }

    if (object.progress) {
        var instance = new nc_instance({'id': object.instance_id});
        instance.getProgress(object);
    }

}

var nc_instance = nc_instance || {};
function nc_instance(options) {
    var object = this;
    if (options) {
        jQuery.each(options, function (index, item) {
            object[index] = item;
        });
    }

    this.getNotification = function (element) {
        element.css({'opacity': 0.4});
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'option': 'com_nextgcyber', 'task': 'instance.getNotification', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                element.css({'opacity': 1});
                if (response.data.html) {
                    element.html(response.data.html);
                }
                if (response.data.error) {
                    element.html(response.data.error).addClass('alert alert-danger');
                }
            }
        });
    };

    this.getValidate = function (button) {
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'option': 'com_nextgcyber', 'task': 'instance.getValidate', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                var modal = new nc_modal(response.data);
                var subtitle = button.attr('data-subtitle');
                if (subtitle) {
                    modal.setSubTitle(subtitle);
                    modal.setSubTitleClass('alert alert-danger');
                }
                modal.setHeader(button.html());
                modal.display();
                jQuery('#InputPassword').focus();
            }
        });
    };

    this.getCustomDomainForm = function () {
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'option': 'com_nextgcyber', 'task': 'instance.getCustomDomainForm', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                var modal = new nc_modal(response.data);
                modal.display();
            }
        });
    };

    this.isRunning = function (element) {
        element.removeClass('alert alert-danger alert-success');
        element.html('<i class="fa fa-circle-o-notch fa-spin"></i>');
        var parent = element.parents('.nc-instance-detail');
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'option': 'com_nextgcyber', 'task': 'instance.isRunning', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                if (response.data.html) {
                    element.html(response.data.html);
                }
                if (response.data.error) {
                    element.html(response.data.error).addClass('alert alert-danger');
                }
                if (response.data.status == 1) {
                    parent.find('.nc-start').addClass('disabled');
                    parent.find('.nc-stop').removeClass('disabled');
                    parent.find('.nc-restart').removeClass('disabled');
                } else if (response.data.status == 0) {
                    parent.find('.nc-start').removeClass('disabled');
                    parent.find('.nc-stop').addClass('disabled');
                    parent.find('.nc-restart').addClass('disabled');
                } else {
                    parent.find('.nc-start').addClass('disabled');
                    parent.find('.nc-stop').addClass('disabled');
                    parent.find('.nc-restart').addClass('disabled');
                }
            }
        });
    };

    this.restart = function (element) {
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'option': 'com_nextgcyber', 'task': 'instance.restart', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                if (response.data.html) {
                    element.html(response.data.html);
                }
                if (response.data.error) {
                    element.html(response.data.error).addClass('alert alert-danger');
                }
                object.isRunning(element);
            }
        });
    };

    this.start = function (element) {
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'option': 'com_nextgcyber', 'task': 'instance.start', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                if (response.data.html) {
                    element.html(response.data.html);
                }
                if (response.data.error) {
                    element.html(response.data.error).addClass('alert alert-danger');
                }
                object.isRunning(element);
            }
        });
    };

    this.stop = function (element) {
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'option': 'com_nextgcyber', 'task': 'instance.stop', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                if (response.data.html) {
                    element.html(response.data.html);
                }
                if (response.data.error) {
                    element.html(response.data.error).addClass('alert alert-danger');
                }
                object.isRunning(element);
            }
        });
    };

    this.verifyPassword = function (pwd) {
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'pwd': pwd, 'option': 'com_nextgcyber', 'task': 'instance.verifyPassword', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                if (response.data.token) {
                    var target = jQuery('.nc-toValidate');
                    target.attr('data-token', response.data.token);
                    target.removeClass('nc-toValidate');
                    target.removeClass('nc-validate');
                    target.click();
                } else {
                    var modal = new nc_modal(response.data);
                    modal.display();
                }
            }
        });
    };

    this.addCustomDomain = function (domain) {
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'domain': domain, 'option': 'com_nextgcyber', 'task': 'instance.addCustomDomain', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                var modal = new nc_modal(response.data);
                modal.display();
            }
        });
    };

    this.validateDomain = function () {
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'option': 'com_nextgcyber', 'task': 'instance.validateDomain', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                var modal = new nc_modal(response.data);
                modal.display();
                if (response.data.domain_id) {
                    jQuery('.nc-input-domain_id').val(response.data.domain_id);
                    jQuery('.nc-input-domain-label').html(response.data.domain_name);
                    jQuery('.nc-addDomain').addClass('disabled');
                }
            }
        });
    };

    this.redeploy = function (button, token) {
        var modal = new nc_modal();
        modal.setHeader(button.html());
        modal.setLoading();
        modal.display();
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'token': token, 'option': 'com_nextgcyber', 'task': 'instance.redeploy', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                if (response.data.label) {
                    modal.setHeader(response.data.label);
                }
                if (response.data.html) {
                    modal.setBody(response.data.html);
                }
                if (response.data.error) {
                    modal.setError(response.data.error);
                }
                if (response.data.redirect_url) {
                    modal.setRedirect(response.data.redirect_url);
                    return false;
                }

                if (response.data.progress) {
                    object.getProgress(modal);
                }

            }
        });
    };

    this.delete = function (button, token) {
        var modal = new nc_modal();
        modal.setHeader(button.html());
        modal.setLoading();
        modal.display();
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'token': token, 'option': 'com_nextgcyber', 'task': 'instance.delete', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                if (response.data.label) {
                    modal.setHeader(response.data.label);
                }
                if (response.data.html) {
                    modal.setBody(response.data.html);
                }
                if (response.data.error) {
                    modal.setError(response.data.error);
                }
                if (response.data.redirect_url) {
                    modal.setRedirect(response.data.redirect_url);
                    return false;
                }

            }
        });
    };

    this.upgrade = function (button, token, payment_period_id, coupon_code) {
        var modal = new nc_modal();
        modal.setHeader(button.html());
        var return_url = button.attr('data-return');
        modal.setLoading();
        modal.display();
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'token': token, 'payment_period_id': payment_period_id, 'coupon_code': coupon_code, 'option': 'com_nextgcyber', 'task': 'instance.upgrade', 'format': 'json', 'return': return_url},
            dataType: 'json',
            success: function (response)
            {
                if (response.data.label) {
                    modal.setHeader(response.data.label);
                }
                if (response.data.html) {
                    modal.setBody(response.data.html);
                }
                if (response.data.error) {
                    modal.setError(response.data.error);
                }
                if (response.data.redirect_url) {
                    modal.setRedirect(response.data.redirect_url);
                    return false;
                }

            }
        });
    };

    this.getPriceList = function (button, token) {
        var return_url = button.attr('data-return');
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'token': token, 'option': 'com_nextgcyber', 'task': 'instance.getPriceList', 'format': 'json', 'return': return_url},
            dataType: 'json',
            success: function (response)
            {
                var modal = new nc_modal(response.data);
                modal.display();
            }
        });
    };

    this.getAddonsForm = function (button, token) {
        var return_url = button.attr('data-return');
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'option': 'com_nextgcyber', 'task': 'instance.getAddonsForm', 'format': 'json', 'token': token, 'return': return_url},
            dataType: 'json',
            success: function (response)
            {
                var modal = new nc_modal(response.data);
                modal.display();
                var price = new nc_pricing({'type': 'instance'});
                price.init();
                price.display();
                price.prepareForm();
            }
        });
    };

    this.getProgress = function (modal) {
        jQuery.ajax({type: "GET",
            data: {id: this.id, 'option': 'com_nextgcyber', 'task': 'instance.getProgress', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                if (typeof (response.data.percentage) != "undefined") {
                    modal.displayProgressBar(response.data.percentage, response.data.log);
                }

                if (response.data.error) {
                    modal.setError(response.data.error);
                }
                if (response.data.redirect_url) {
                    modal.setRedirect(response.data.redirect_url);
                    return false;
                }
                if (response.data.percentage < 100) {
                    setTimeout(function () {
                        object.getProgress(modal);
                    }, 1000);
                }
            }
        });
    };
}
jQuery(document).ready(function () {
    jQuery(document).on('click', '.nc-button', function () {
        var button = jQuery(this);
        var parent = button.parents('.nc-instance-detail');
        if (button.hasClass('disabled')) {
            return false;
        }
        var id = button.attr('data-id');
        var action = button.attr('data-action');
        var token = button.attr('data-token');

        if (!id || !action) {
            return false;
        }

        var instance = new nc_instance({'id': id});
        if (button.hasClass('nc-validate')) {
            instance.getValidate(button);
            jQuery('.nc-button').removeClass('nc-toValidate');
            button.addClass('nc-toValidate');
            return false;
        }

        if (action == 'getNotification') {
            instance.getNotification(parent.find('.list-group.instance-info'));
        }
        if (action == 'isRunning') {
            instance.isRunning(parent.find('.isRunning'));
        }
        if (action == 'start') {
            instance.start(parent.find('.isRunning'));
        }
        if (action == 'stop') {
            instance.stop(parent.find('.isRunning'));
        }
        if (action == 'restart') {
            instance.restart(parent.find('.isRunning'));
        }
        if (action == 'getCustomDomainForm') {
            instance.getCustomDomainForm();
        }
        if (action == 'verifyPassword') {
            var pwd = jQuery('#InputPassword').val();
            instance.verifyPassword(pwd);
        }
        if (action == 'redeploy') {
            instance.redeploy(button, token);
        }
        if (action == 'delete') {
            instance.delete(button, token);
        }

        if (action == 'getPriceList') {
            instance.getPriceList(button, token);
        }

        if (action == 'upgrade') {
            var payment_period_id = jQuery('#nc_input_payment_period_id').val();
            var coupon_code = jQuery('#promotional_code_input').val();
            instance.upgrade(button, token, payment_period_id, coupon_code);
        }

        if (action == 'addCustomDomain') {
            var domain = jQuery('#customDomain').val();
            instance.addCustomDomain(domain);
        }
        if (action == 'validateDomain') {
            instance.validateDomain();
        }

        if (action == 'getAddonsForm') {
            instance.getAddonsForm(button, token);
        }

        return false;
    });

    jQuery('.nc-auto').each(function () {
        jQuery(this).click();
    });

    jQuery(document).off('nc-input.form-control').on('keypress', '.nc-input.form-control', function (e) {
        if (e.which == 13) {
            var form = jQuery(this).parents('form');
            form.find('.nc-button').click();
            return false;
        }
    });
    jQuery('.nc-button').hover(function () {
        jQuery(this).popover('show');
    }, function () {
        jQuery(this).popover('hide');
    });
});