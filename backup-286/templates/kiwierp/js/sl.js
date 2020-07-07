var nc_slide_item = nc_slide_item || {};
function nc_slide_item(options) {
    this.index = 0;
    this.element = '';
    this.parent = '';
    this.is_active = false;
    var object = this;
    if (options) {
        jQuery.each(options, function (index, item) {
            object[index] = item;
        });
    }

    this.active = function () {
        object.element.addClass('active');
        object.is_active = true;
        var src = object.element.find('img').attr('src');
        var link = object.element.find('a');
        var title = link.html();
        var content_url = link.attr('data-content');
        var demo_url = link.attr('data-demo');
        var trial_url = link.attr('data-trial');
        object.parent.monitor.find('img').attr('src', src);
        object.parent.monitor.find('.monitor-left a').html(title).attr('href', content_url);
        object.parent.monitor.find('.livedemo').attr('href', demo_url);
        object.parent.monitor.find('.freetrial').attr('href', trial_url);
        object.parent.index = object.index;
    };

    this.unactive = function () {
        object.element.removeClass('active');
        object.is_active = false;
    };

    this.element.find('a').click(function () {
        object.parent.reset();
        object.active();
        return false;
    });
}

var nc_slide = nc_slide || {};
function nc_slide(options) {
    this.items = [];
    this.monitor = jQuery('.monitor');
    this.index = 0;
    this.delay = 6000;
    this.running = '';
    var object = this;
    if (options) {
        jQuery.each(options, function (index, item) {
            object[index] = item;
        });
    }

    this.init = function () {
        jQuery('.top3-slider-item').each(function (index, item) {
            var element = jQuery(this);
            var item = new nc_slide_item({'parent': object, 'element': element, 'index': index});
            object.items.push(item);
        });
    };

    this.reset = function () {
        jQuery.each(object.items, function (index, item) {
            item.unactive();
        });
    };

    this.display = function () {
        object.reset();
        object.index = 0;
        object.items[object.index].active();
        object.running = setTimeout(function () {
            object.next();
        }, object.delay);
    };

    this.next = function () {
        var total = object.items.length;
        var current = object.index;
        var next = current + 1;
        if (next >= total) {
            next = 0;
        }
        object.index = next;
        object.reset();
        object.items[object.index].active();
        object.running = setTimeout(function () {
            object.next();
        }, object.delay);
    };

    this.prev = function () {
        var total = object.items.length;
        var current = object.index;
        var prev = current - 1;
        if (prev < 0) {
            prev = total - 1;
        }
        object.index = prev;
        object.reset();
        object.items[object.index].active();
        object.running = setTimeout(function () {
            object.prev();
        }, object.delay);
    };
}

jQuery(document).ready(function () {
    var element = jQuery('.top3-slider');
    if (element.length > 0) {
        var nc_slide1 = new nc_slide();
        nc_slide1.init();
        nc_slide1.display();
    }

});