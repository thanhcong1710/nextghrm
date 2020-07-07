/**
 * @package nextgcyber
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt
 * @author url: http://nextgerp.com
 * @author NextG-ERP support@nextgerp.com
 */
var nc_pricing_item = nc_pricing_item || {};
function nc_pricing_item(options) {
    this.id = 0;
    this.name = '';
    this.quantity = 0;
    this.unitprice = 0;
    this.nc_type = '';
    this.nc_module_type = '';
    this.element = '';
    this.parent = '';
    this.depend_parent = '';
    this.depend_child = '';
    this.is_actived = false;
    this.currency = '';
    this.in_tax = 0;
    this.out_tax = 0;
    this.out_tax_name = '';
    this.tax_included = true;
    var object = this;
    if (options) {
        jQuery.each(options, function (index, item) {
            object[index] = item;
        });
    }

    jQuery(this.element).click(function (event) {
        if (jQuery(this).attr('type') == 'checkbox') {
            if (jQuery(this).is(":checked")) {
                object.active();
            } else {
                object.unactive();
            }
            //object.store();
            object.parent.display();
        }
    });

    this.store = function () {
        object.parent.store();
    };

    jQuery(this.element).on('change', function (event) {
        if (jQuery(this).attr('type') == 'text') {
            if (object.parent.type == 'pricing') {
                var quantity = jQuery(this).val();
                if (!jQuery.isNumeric(quantity)) {
                    quantity = 2;
                }
                if (quantity < 0) {
                    quantity = 2;
                }

                if (object.nc_type == 'odoo_bandwidth' && quantity < 1) {
                    quantity = 1;
                }
                
                if (object.nc_type == 'odoo_user' && quantity < 1) {
                    quantity = 1;
                }
                
                if (object.nc_type == 'odoo_storage' && quantity < 1) {
                    quantity = 1;
                }
                
                object.quantity = quantity;
                jQuery(this).val(quantity);
            }
            object.store();
            object.parent.display();
        }

    });

    var parent_item = this.element.parents('.nc-pricing-product');
    this.createLink = function () {
        if (object.depend_parent.length > 0) {
            var link_title = [];
            jQuery.each(object.depend_parent, function (index, item) {
                link_title.push(item.name);
            });
            var link = jQuery('<span class="fa fa-link nc-price-depend" data-toggle="tooltip" data-placement="top" title="Requires:' + link_title.toString() + '"></span>');
            parent_item.append(link);
            link.click(function (event) {
                object.element.click();
            });
            link.tooltip();
        }
    };

    this.active = function () {
        if (object.is_actived) {
            return false;
        }
        object.is_actived = true;
        object.element.prop('checked', true);
        var parent_item = object.element.parents('.nc-pricing-product');
        parent_item.addClass('active');
        jQuery.each(object.depend_parent, function (index, item) {
            item.active();
        });
    };

    this.unactive = function () {
        if (!object.is_actived) {
            return false;
        }
        object.is_actived = false;
        object.element.prop('checked', false);
        var parent_item = object.element.parents('.nc-pricing-product');
        parent_item.removeClass('active');
        jQuery.each(object.depend_parent, function (index, item) {
            item.unactive();

        });
        jQuery.each(object.depend_child, function (index, item) {
            item.unactive();
        });
    };
}

var nc_pricing = nc_pricing || {};
function nc_pricing(options) {
    this.items = [];
    this.type = 'pricing';
    this.subtotal = 0;
    this.pricing_board = jQuery('.pricing-board');
    this.button = jQuery('.nc-start-trial');
    this.addons_button = jQuery('.nc-addons-button');
    this.store_button = jQuery('.nc-price-store');
    this.subdomain = jQuery('.nc-input-subdomain');
    this.customdomain = jQuery('.nc-input-domain_id');
    this.tax_included = true;
    this.couponcode_discount_percentage = 0;
    this.couponcode_discount_input = jQuery('#nc-discount-value');
    this.couponcode_apply_button = jQuery('#nc-discount-apply-button');
    this.period_discount_percentage = 0;
    this.period_element = jQuery('#nc-payment-period-id');
    var object = this;
    if (options) {
        jQuery.each(options, function (index, item) {
            object[index] = item;
        });
    }

    this.store = function () {
        var apps = [];
        jQuery.each(object.items, function (index, item) {
            if (item.nc_type != 'odoo_module') {
                if (item.element.is(':checked')) {
                    apps.push({'id': item.id, 'quantity': item.quantity, 'nc_type': item.nc_type});
                } else {
                    var item_price = Number(item.unitprice) * Number(item.quantity);
                    if (item_price > 0) {
                        apps.push({'id': item.id, 'quantity': item.quantity, 'nc_type': item.nc_type});
                    }
                }

            } else if (item.nc_type == 'odoo_module') {
                if (item.element.is(':checked')) {
                    apps.push({'id': item.id, 'quantity': item.quantity, 'nc_type': item.nc_type});
                }
            }
        });
        jQuery.ajax({type: "GET",
            data: {'apps': apps, 'option': 'com_nextgcyber', 'task': 'form.store', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {

            }
        });
    };

    this.createDepend = function () {
        jQuery.each(object.items, function (index, item) {
            var depend_parent = item.depend_parent;
            var parent_list = [];
            if (depend_parent) {
                var parent = depend_parent.split(",");
                jQuery.each(parent, function (key, value) {
                    jQuery.each(object.items, function (index2, item2) {
                        if (item2.id == Number(value) && item2.id != item.id) {
                            parent_list.push(item2);
                        }
                    });
                });
            }
            item.depend_parent = parent_list;

            var depend_child = item.depend_child;
            var child_list = [];
            if (depend_child) {
                var child = depend_child.split(",");
                jQuery.each(child, function (key, value) {
                    jQuery.each(object.items, function (index2, item2) {
                        if (item2.id == Number(value)) {
                            child_list.push(item2);
                        }
                    });
                });
            }
            item.depend_child = child_list;
            item.createLink();

        });
        jQuery.each(object.items, function (index, item) {
            if (item.element.is(':checked')) {
                item.active();
            }
        });
    };

    this.init = function () {

        jQuery('.nc-pricing-item').each(function () {
            var element = jQuery(this);
            var id = element.attr('data-id');
            var in_tax = element.attr('data-in-tax');
            var out_tax = element.attr('data-out-tax');
            var out_tax_name = element.attr('data-out-tax-name');
            var name = element.attr('data-name');
            var unitprice = element.attr('data-price');
            var currency = element.attr('data-currency');
            var depend_parent = element.attr('data-depend-parent');
            var depend_child = element.attr('data-depend-child');
            var quantity = 1;
            var type = element.attr('type');
            if (type == 'text') {
                quantity = element.val();
            } else if (type == 'checkbox') {
                quantity = 1;
            }

            var nc_type = element.attr('data-nc-type');
            var nc_module_type = element.attr('data-nc-module-type');
            var item = new nc_pricing_item({'parent': object, 'id': id, 'unitprice': unitprice, 'name': name, 'quantity': quantity, 'nc_type': nc_type, 'element': element, 'depend_parent': depend_parent, 'depend_child': depend_child, 'currency': currency, 'in_tax': in_tax, 'out_tax': out_tax, 'out_tax_name': out_tax_name, 'nc_module_type' : nc_module_type});
            object.items.push(item);
        });
        object.createDepend();
    };

    this.display = function () {
        this.couponcode_discount_percentage = this.couponcode_discount_input.val();
        var target = jQuery('.nc-pricing-table tbody');
        target.html('');
        var app_count = 0;
        var app_price = 0;
        var total_untax = 0;
        var total_tax = 0;
        var total_price = 0;
        var currency = 'USD';
        var tax_percentage = 0;
        var tax_name = '';

        var period_select = object.period_element.find(":selected");
        var month = Number(period_select.attr('data-month'));
        jQuery.each(object.items, function (index, item) {
            if (item.nc_type != 'odoo_module') {
                var type = item.element.attr('type');
                var price = Number(item.unitprice);
                var base_price = price / (1 + Number(item.in_tax));
                var subtotal = base_price * Number(item.quantity) * month;
                //var tax = subtotal * (Number(item.in_tax) + Number(item.out_tax));
                tax_percentage = Number(item.out_tax);
                tax_name = item.out_tax_name;
                var tr = '<tr class="nc-pricing-table-tr-' + item.id + '"><td>' + item.name + '</td><td class="align-right">' + subtotal.toFixed(2) + ' ' + item.currency + '</td></tr>';
                if (type == 'text') {
                    if (subtotal > 0) {
                        target.append(tr);
                    }
                } else {
                    if (item.element.is(':checked')) {
                        target.append(tr);
                    }
                }
                total_untax += subtotal;

            } else if (item.nc_type == 'odoo_module') {
                if (item.element.is(':checked')) {
                    var price = Number(item.unitprice);
                    var base_price = price / (1 + Number(item.in_tax));
                    var subtotal = base_price * Number(item.quantity) * month;
                    tax_percentage = Number(item.out_tax);
                    //var tax = subtotal * (Number(item.in_tax) + Number(item.out_tax));
                    app_count += 1;
                    //total_tax += tax;
                    total_untax += subtotal;
                    app_price += subtotal;
                }
            }
            currency = item.currency;
        });

        var couponcode_discount = 0;
        var couponcode_discount_percentage = Number(object.couponcode_discount_percentage);
        if (couponcode_discount_percentage) {
            couponcode_discount = total_untax * couponcode_discount_percentage / 100;
        }
        jQuery('.nc-pricing-table-coupon-code-discount-percentage span').html(couponcode_discount_percentage.toFixed(0));
        jQuery('.nc-pricing-table-coupon-code-discount-amount span').html('-' + couponcode_discount.toFixed(2));
        if (couponcode_discount) {
            jQuery('.nc-pricing-table-coupon-code-discount').show();
        } else {
            jQuery('.nc-pricing-table-coupon-code-discount').hide();
        }

        var pediod_discount = 0;
        object.period_discount_percentage = period_select.attr('data-discount');
        pediod_discount = total_untax * object.period_discount_percentage / 100;
        jQuery('.nc-pricing-table-period-discount-percentage span').html(object.period_discount_percentage);
        jQuery('.nc-pricing-table-period-discount-amount span').html('-' + pediod_discount);
        if (pediod_discount) {
            jQuery('.nc-pricing-table-period-discount').show();
        } else {
            jQuery('.nc-pricing-table-period-discount').hide();
        }

        // Display before modify
        jQuery('.nc-pricing-table-untaxed-amount-0 span').html(total_untax.toFixed(2));

        total_untax = total_untax - couponcode_discount - pediod_discount;
        total_tax = total_untax * tax_percentage;
        total_price = total_untax + total_tax;
        jQuery('.nc-pricing-table-total span').html(total_price.toFixed(2));
        jQuery('.nc-pricing-table-untaxed-amount span').html(total_untax.toFixed(2));

        jQuery('.nc-pricing-table-taxs-amount span').html(total_tax.toFixed(2));
        if (total_tax) {
            jQuery('.nc-pricing-table-tax').show();
        } else {
            jQuery('.nc-pricing-table-tax').hide();
        }
        jQuery('.tax-name').html('<strong>' + tax_name + '</strong>');

        if (app_count) {
            object.button.removeClass('disabled');
            var tr = '<tr class="nc-pricing-table-tr"><td>' + app_count + ' Apps</td><td class="align-right">' + app_price.toFixed(2) + ' ' + currency + '</td></tr>';
            target.append(tr);
        } else {
            object.button.addClass('disabled');
        }
    };

    this.button.click(function () {
        if (jQuery(this).hasClass('disabled')) {
            return false;
        }
        var parent = jQuery(this).parents('.nextgcyber-pricing');
        var domain = object.subdomain.val();
        var customdomain_id = object.customdomain.val();
        if (domain.length <= 0 && customdomain_id.length <= 0) {
            parent.find('#headingOne a.collapsed').click();
            object.subdomain.focus();
            object.subdomain.css({'border': '1px solid red'});
            object.subdomain.popover('show');
            return false;
        }
        var modal = new nc_modal({'label': jQuery(this).html()});
        modal.setLoading();
        modal.display();
        var apps = [];
        jQuery.each(object.items, function (index, item) {
            if (item.nc_type != 'odoo_module') {
                if (item.element.is(':checked')) {
                    apps.push({'id': item.id, 'quantity': item.quantity, 'nc_type': item.nc_type});
                } else {
                    var item_price = Number(item.unitprice) * Number(item.quantity);
                    if (item_price > 0) {
                        apps.push({'id': item.id, 'quantity': item.quantity, 'nc_type': item.nc_type});
                    }
                }
            } else if (item.nc_type == 'odoo_module') {
                if (item.element.is(':checked')) {
                    apps.push({'id': item.id, 'quantity': item.quantity, 'nc_type': item.nc_type});
                }
            }
        });
        var task = 'instance.startTrial';

        if (jQuery(this).hasClass('nc-pay-now')) {
            task = 'instance.payNow';
        }
        var paymentperiod_id = object.period_element.val();
        jQuery.ajax({type: "GET",
            data: {'domain': domain, 'customdomain_id': customdomain_id, 'apps': apps, 'option': 'com_nextgcyber', 'task': task, 'format': 'json', 'paymentperiod_id': paymentperiod_id},
            dataType: 'json',
            success: function (response)
            {
                var modal = new nc_modal(response.data);
                modal.display();
            }
        });
    });

    this.addons_button.click(function () {
        if (jQuery(this).hasClass('disabled')) {
            return false;
        }
        var modal = new nc_modal({'label': jQuery(this).html()});
        modal.setLoading();
        modal.display();
        var apps = [];
        jQuery.each(object.items, function (index, item) {
            if (item.nc_type != 'odoo_module') {
                if (item.element.is(':checked')) {
                    apps.push({'id': item.id, 'quantity': item.quantity, 'nc_type': item.nc_type});
                } else {
                    var item_price = Number(item.unitprice) * Number(item.quantity);
                    if (item_price > 0) {
                        apps.push({'id': item.id, 'quantity': item.quantity, 'nc_type': item.nc_type});
                    }
                }
            } else if (item.nc_type == 'odoo_module') {
                if (item.element.is(':checked')) {
                    apps.push({'id': item.id, 'quantity': item.quantity, 'nc_type': item.nc_type});
                }
            }
        });
        var task = 'instance.addAddons';
        var instance_id = jQuery(this).attr('data-id');
        var return_url = jQuery(this).attr('data-return');
        jQuery.ajax({type: "POST",
            data: {'apps': apps, 'option': 'com_nextgcyber', 'task': task, 'format': 'json', 'instance_id': instance_id, 'return': return_url},
            dataType: 'json',
            success: function (response)
            {
                var modal = new nc_modal(response.data);
                modal.display();
            }
        });
    });

    this.couponcode_apply_button.on('click', function () {
        var code = jQuery('#promotional_code_input').val();
        jQuery.ajax({type: "GET",
            data: {'code': code, 'option': 'com_nextgcyber', 'task': 'form.confirmPromotion', 'format': 'json'},
            dataType: 'json',
            success: function (response)
            {
                object.couponcode_discount_input.val(response.data.value);
                if (response.data.html) {
                    jQuery('.nc-coupon-discount-msg').html('<div class="alert alert-success">' + response.data.html + '</div>');
                }
                if (response.data.error) {
                    jQuery('.nc-coupon-discount-msg').html('<div class="alert alert-danger">' + response.data.error + '</div>');
                }
                object.display();
            }
        });

    });

    this.period_element.on('change', function () {
        object.display();
    });

    this.pricing_board.on('mouseleave', function () {
        object.store();
    });

    this.prepareForm = function () {
        jQuery('input.form-control').popover();
        jQuery('input.form-control').focus(function () {
            var another = jQuery('input.form-control').not(this);
            another.popover('hide');
        });
        jQuery('input.form-control').mouseleave(function () {
            jQuery(this).popover('hide');
        });
    };
}

jQuery(document).ready(function () {
    var price = new nc_pricing();
    price.init();
    price.display();
    price.prepareForm();
});
