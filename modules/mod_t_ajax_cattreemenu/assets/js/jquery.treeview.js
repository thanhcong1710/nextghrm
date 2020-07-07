/*
 * Treeview 1.4 - jQuery plugin to hide and show branches of a tree
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-treeview/
 * http://docs.jquery.com/Plugins/Treeview
 *
 * Copyright (c) 2007 JÃ¶rn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id: jquery.treeview.js 4684 2008-02-07 19:08:06Z joern.zaefferer $
 *
 */

;
(function ($) {

        $.extend($.fn, {
                swapClass: function (c1, c2) {
                        var c1Elements = this.filter('.' + c1);
                        this.filter('.' + c2).removeClass(c2).addClass(c1);
                        c1Elements.removeClass(c1).addClass(c2);
                        return this;
                },
                replaceClass: function (c1, c2) {
                        return this.filter('.' + c1).removeClass(c1).addClass(c2).end();
                },
                hoverClass: function (className) {
                        className = className || "hover";
                        return this.hover(function () {
                                $(this).addClass(className);
                        }, function () {
                                $(this).removeClass(className);
                        });
                },
                heightToggle: function (animated, callback) {
                        animated ?
                                this.animate({height: "toggle"}, animated, callback) :
                                this.each(function () {
                                        jQuery(this)[ jQuery(this).is(":hidden") ? "show" : "hide" ]();
                                        if (callback)
                                                callback.apply(this, arguments);
                                });
                },
                heightHide: function (animated, callback) {
                        if (animated) {
                                this.animate({height: "hide"}, animated, callback);
                        } else {
                                this.hide();
                                if (callback)
                                        this.each(callback);
                        }
                },
                prepareBranches: function (settings) {
                        if (!settings.prerendered) {
                                // mark last tree items
                                this.filter(":last-child:not(ul)").addClass(CLASSES.last);
                                // collapse whole tree, or only those marked as closed, anyway except those marked as open
                                this.filter((settings.collapsed ? "" : "." + CLASSES.closed) + ":not(." + CLASSES.open + ")").find(">ul").hide();
                        }
                        // return all items with sublists
                        return this.filter(":has(>ul)");
                },
                applyShowMore: function (showMore) {
                        this.find("a.tree-more").click(function (event) {
                                showMore.apply($(this));
                        });
                },
                applyClasses: function (settings, toggler) {
                        // event on click span link
                        this.filter(":has(>ul):not(:has(>a))").find(">span").click(function (event) {
                                //toggler.apply($(this).next());
                        }).add($("a", this)).hoverClass();

                        if (!settings.prerendered) {
                                // handle closed ones first
                                this.filter(":has(>ul:hidden)")
                                        .addClass(CLASSES.expandable)
                                        .replaceClass(CLASSES.last, CLASSES.lastExpandable);

                                // handle open ones
                                this.not(":has(>ul:hidden)")
                                        .addClass(CLASSES.collapsable)
                                        .replaceClass(CLASSES.last, CLASSES.lastCollapsable);

                                // create hitarea
                                this.prepend("<div class=\"" + CLASSES.hitarea + "\"/>").find("div." + CLASSES.hitarea).each(function () {
                                        var classes = "";
                                        $.each($(this).parent().attr("class").split(" "), function () {
                                                classes += this + "-hitarea ";
                                        });
                                        $(this).addClass(classes);
                                });
                        }

                        // apply event to hitarea
                        this.find("div." + CLASSES.hitarea).click(toggler);
                },
                treeview: function (settings) {
                        if (settings.add) {
                                return this.trigger("add", [settings.add]);
                        }

                        if (typeof (settings.paddingTop) == 'undefined' || settings.paddingTop === "") {
                                settings = $.extend({
                                        paddingTop: 60
                                }, settings);
                        }

                        if (settings.toggle) {
                                var callback = settings.toggle;
                                settings.toggle = function () {
                                        return callback.apply($(this).parent()[0], arguments);
                                };
                        }

                        if (settings.showMore) {
                                var callback = settings.showMore;
                                settings.showMore = function () {
                                        return callback.apply($(this).parent()[0], arguments);
                                };
                        }

                        // factory for treecontroller
                        function treeController(tree, control) {
                                // factory for click handlers
                                function handler(filter) {
                                        return function () {
                                                // reuse toggle event handler, applying the elements to toggle
                                                // start searching for all hitareas
                                                toggler.apply($("div." + CLASSES.hitarea, tree).filter(function () {
                                                        // for plain toggle, no filter is provided, otherwise we need to check the parent element
                                                        return filter ? $(this).parent("." + filter).length : true;
                                                }));
                                                return false;
                                        };
                                }
                                // click on first element to collapse tree
                                $("a:eq(0)", control).click(handler(CLASSES.collapsable));
                                // click on second to expand tree
                                $("a:eq(1)", control).click(handler(CLASSES.expandable));
                                // click on third to toggle tree
                                $("a:eq(2)", control).click(handler());
                        }

                        /**
                         *
                         * Event on user click show more button
                         */
                        function showMore() {
                                var button = $(this);
                                var ul = $(this).parent('.sub-tree');
                                if (ul.length > 0) {
                                        var parent = ul.parent('.cat-tree-item');
                                        var tree = parent.parents('.main-tree');
                                } else {
                                        var parent = $(this).parent('ul');
                                        var ul = parent;
                                        var tree = parent;
                                }

                                var data_id = parent.attr('data-id');
                                var mid = parent.attr('data-mid');
                                var offset = $(this).attr('data-offset');
                                var limit = $(this).attr('data-limit');
                                //console.log(limit);
                                var data = {
                                        option: 'com_ajax',
                                        module: 't_ajax_cattreemenu',
                                        format: 'json',
                                        method: 'showMore',
                                        data_id: data_id,
                                        mid: mid,
                                        offset: offset,
                                        limit: limit
                                };
                                // Call Ajax request
                                jQuery.ajax({type: "GET",
                                        data: data,
                                        dataType: 'json',
                                        success: function (response)
                                        {
                                                button.remove();
                                                if (response.data.limit) {
                                                        parent.attr('data-displayed', response.data.limit);
                                                }
                                                if (response.data.html) {
                                                        ul.append(response.data.html).show();

                                                        // update branches
                                                        branches = tree.find("li:has(>ul)");
                                                        parent.addClass('loaded');
                                                        // handle closed ones first
                                                        ul.find('> li').filter(":has(>ul:hidden):not(:has(.hitarea))")
                                                                .addClass(CLASSES.expandable)
                                                                .replaceClass(CLASSES.last, CLASSES.lastExpandable);

                                                        ul.find("li:last-child").filter(":not(:has(>ul:hidden))").addClass(CLASSES.last);
                                                        ul.find("li:last-child").filter(":has(>ul:hidden)").addClass(CLASSES.lastExpandable);

                                                        // create hitarea
                                                        ul.find('> li').filter(":has(>ul:hidden):not(:has(.hitarea))").prepend("<div class=\"" + CLASSES.hitarea + "\"/>").find("div." + CLASSES.hitarea).each(function () {
                                                                var classes = "";
                                                                $.each($(this).parent().attr("class").split(" "), function () {
                                                                        classes += this + "-hitarea ";
                                                                });
                                                                $(this).addClass(classes);
                                                                $(this).parent().find("div." + CLASSES.hitarea).click(toggler);
                                                        });
                                                        // Register event
                                                        ul.find('> a.tree-more').click(showMore);
                                                        serialize();
                                                }
                                                else {
                                                        ul.find("li:last-child").filter(":not(:has(>ul:hidden))").addClass(CLASSES.last);
                                                        ul.find("li:last-child").filter(":has(>ul:hidden)").addClass(CLASSES.lastExpandable);
                                                }
                                        }
                                });
                        }

                        // handle toggle event
                        function toggler() {
                                $("#" + settings.tree_id).attr('max-scroll', '');
                                if ($(this).hasClass('expandable-hitarea')) {
                                        var parent = $(this).parent('.cat-tree-item');
                                        if (!parent.hasClass('loaded')) {
                                                var data_id = parent.attr('data-id');
                                                var mid = parent.attr('data-mid');
                                                var data = {
                                                        option: 'com_ajax',
                                                        module: 't_ajax_cattreemenu',
                                                        format: 'json',
                                                        method: 'getItem',
                                                        data_id: data_id,
                                                        mid: mid
                                                };
                                                parent.find('.sub-tree').addClass('loadding');
                                                // Call Ajax request
                                                jQuery.ajax({type: "GET",
                                                        data: data,
                                                        dataType: 'json',
                                                        success: function (response)
                                                        {
                                                                parent.find('.sub-tree').removeClass('loadding');
                                                                if (response.data.html) {
                                                                        parent.find('.sub-tree').html(response.data.html).show();
                                                                        // update branches
                                                                        var tree = parent.parents('.main-tree');
                                                                        branches = tree.find("li:has(>ul)");
                                                                        parent.addClass('loaded');
                                                                        // handle closed ones first
                                                                        parent.find('>.sub-tree > li').filter(":has(>ul:hidden)")
                                                                                .addClass(CLASSES.expandable)
                                                                                .replaceClass(CLASSES.last, CLASSES.lastExpandable);

                                                                        parent.find("li:last-child").filter(":not(:has(>ul:hidden))").addClass(CLASSES.last);
                                                                        parent.find("li:last-child").filter(":has(>ul:hidden)").addClass(CLASSES.lastExpandable);

                                                                        // create hitarea
                                                                        parent.find('>.sub-tree > li').filter(":has(>ul:hidden)").prepend("<div class=\"" + CLASSES.hitarea + "\"/>").find("div." + CLASSES.hitarea).each(function () {
                                                                                var classes = "";
                                                                                $.each($(this).parent().attr("class").split(" "), function () {
                                                                                        classes += this + "-hitarea ";
                                                                                });
                                                                                $(this).addClass(classes);
                                                                        });
                                                                        // Register event
                                                                        parent.find('.sub-tree').find("div." + CLASSES.hitarea).click(toggler);
                                                                        parent.find('a.tree-more').click(showMore);

                                                                }
                                                        }
                                                });
                                        }
                                }
                                $(this)
                                        .parent()
                                        // swap classes for hitarea
                                        .find(">.hitarea")
                                        .swapClass(CLASSES.collapsableHitarea, CLASSES.expandableHitarea)
                                        .swapClass(CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea)
                                        .end()
                                        // swap classes for parent li
                                        .swapClass(CLASSES.collapsable, CLASSES.expandable)
                                        .swapClass(CLASSES.lastCollapsable, CLASSES.lastExpandable)
                                        // find child lists
                                        .find(">ul")
                                        // toggle them
                                        .heightToggle(settings.animated, settings.toggle);

                                swapFont($(this).parent());

                                if (settings.unique) {
                                        $(this).parent()
                                                .siblings()
                                                // swap classes for hitarea
                                                .find(">.hitarea")
                                                .replaceClass(CLASSES.collapsableHitarea, CLASSES.expandableHitarea)
                                                .replaceClass(CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea)
                                                .end()
                                                .replaceClass(CLASSES.collapsable, CLASSES.expandable)
                                                .replaceClass(CLASSES.lastCollapsable, CLASSES.lastExpandable)
                                                .find(">ul")
                                                .heightHide(settings.animated, settings.toggle);
                                        $(this).parent()
                                                .siblings().find('> a >.tree-icon').replaceClass('fa-folder-open-o', 'fa-folder-o');
                                        $(this).parent()
                                                .siblings().find('> a >.tree-icon').replaceClass('icon-folder-open', 'icon-folder-close');
                                }
                        }

                        function swapFont(element) {
                                var icon = element.find('> a >.tree-icon');
                                icon.swapClass('fa-folder-o', 'fa-folder-open-o');
                                icon.swapClass('icon-folder-close', 'icon-folder-open');
                        }

                        // Cookie Persistence
                        function serialize() {
                                if (typeof (settings.cookieId) == 'undefined' || settings.cookieId === "") {
                                        return;
                                }
                                function binary(arg) {
                                        return arg ? 1 : 0;
                                }

                                var jsonData = {};
                                var tree_menu = $("#" + settings.tree_id);
                                if (tree_menu.attr('data-displayed')) {
                                        jsonData[tree_menu.attr('data-id')] = {"id": tree_menu.attr('data-id'), "limit": tree_menu.attr('data-displayed')};
                                }

                                branches.each(function (i, e) {
                                        var data_id = $(e).attr('data-id');
                                        var limit = $(e).attr('data-displayed');
                                        if (data_id && $(e).is(":has(>ul:visible)")) {
                                                jsonData[data_id] = {"id": data_id, "limit": limit};
                                        }

                                });
                                //console.log(jsonData);
                                var cookie = JSON.stringify(jsonData);
                                // Remove cookie
                                $.cookie(settings.cookieId, '', {path: '/'});
                                // Recreate cookie
                                $.cookie(settings.cookieId, cookie, {expires: 1, path: '/'});
                        }

                        function deserialize() {
                                if (typeof (settings.cookieId) == 'undefined' || settings.cookieId === "") {
                                        return;
                                }
                                var stored = $.cookie(settings.cookieId);
                                //console.log(stored);
                                if (stored) {
                                        var cookie = JSON.parse(stored);
                                        //console.log(cookie);
                                        branches.each(function (i, e) {
                                                var data_id = $(e).attr('data-id');
                                                jQuery.each(cookie, function (index, result) {
                                                        if (result.id == data_id) {
                                                                $(e).find(">ul").show();
                                                                swapFont($(e));
                                                        }
                                                });

                                        });
                                }
                        }

                        // add treeview class to activate styles
                        this.addClass("treeview");

                        // prepare branches and find all tree items with child lists
                        var branches = this.find("li").prepareBranches(settings);
                        switch (settings.persist) {
                                case "cookie":
                                        var toggleCallback = settings.toggle;
                                        settings.toggle = function () {
                                                serialize();
                                                if (toggleCallback) {
                                                        toggleCallback.apply(this, arguments);
                                                }
                                        };
                                        deserialize();
                                        break;
                                case "location":
                                        var current = this.find("a").filter(function () {
                                                return this.href.toLowerCase() == location.href.toLowerCase();
                                        });
                                        if (current.length) {
                                                current.addClass("selected").parents("ul, li").add(current.next()).show();
                                        }
                                        break;
                        }

                        branches.applyClasses(settings, toggler);
                        //branches.applyShowMore(showMore);
                        this.applyShowMore(showMore);
                        // if control option is set, create the treecontroller and show it
                        if (settings.control) {
                                treeController(this, settings.control);
                                $(settings.control).show();
                        }

                        if (typeof (settings.cookieId) == 'undefined' || settings.cookieId === "") {
                                $(this).find('li.loaded > .hitarea').click();
                        } else {
                                $(this).find('li.loaded.active.expandable > .hitarea').click();
                        }

                        function scrollMenu() {
                                if (typeof (settings.scroll) == 'undefined' || settings.scroll === "" || !settings.scroll) {
                                        return;
                                }
                                var window_width = $(document).width();
                                if (window_width > 768) {
                                        // Create variable
                                        var scrollTopVal = $(window).scrollTop();
                                        var window_height = $(window).height();
                                        var full_height = $(document).height();
                                        //console.log(full_height);
                                        var tree_menu = $("#" + settings.tree_id);
                                        var parent = tree_menu.parent();
                                        // Current position from parent element to top of page
                                        // This value will be changed when scroll
                                        var parent_top = parent.offset().top;
                                        var parent_height = parent.height();

                                        var reset = parseInt(tree_menu.attr('reset'));
                                        if (!reset) {
                                                reset = 0;
                                                tree_menu.attr('reset', reset);
                                        }

                                        // Init limit scroll with current document height
                                        var max_scroll = parseInt(tree_menu.attr('max-scroll'));
                                        if (!max_scroll) {
                                                tree_menu.attr('max-scroll', full_height);
                                                max_scroll = parseInt(full_height);
                                        }

                                        var parentTopStored = parent.attr('top-position');
                                        if (!parentTopStored) {
                                                parentTopStored = 0;
                                        }

                                        var lastScroll = parent.attr('lastscroll');
                                        if (!lastScroll) {
                                                lastScroll = scrollTopVal;
                                        }
                                        parent.attr('lastscroll', scrollTopVal);

                                        window_height = parseInt(window_height) - settings.paddingTop - 20;

                                        // Clear data when scroll on top of page
                                        if (parentTopStored && scrollTopVal < parentTopStored || parent_top < parentTopStored) {
                                                parent.removeClass('height_fixed moving stopped limited');
                                                tree_menu.attr('max-scroll', '');
                                                tree_menu.attr('reset', '');
                                                parent.css({'margin-top': '', 'height': 'auto'});
                                                parentTopStored = 0;
                                        }

                                        // Scroll to end of page
                                        if (full_height > max_scroll || (scrollTopVal - parentTopStored) > max_scroll) {
                                                // reset max scroll if full height modified
                                                if (reset < 1 && full_height > max_scroll) {
                                                        tree_menu.attr('max-scroll', full_height);
                                                        reset += 1;
                                                        tree_menu.attr('reset', reset);
                                                } else {
                                                        parent.addClass('limited');
                                                }
                                        }

                                        // store top postiton
                                        if (scrollTopVal >= parent_top && !parent.hasClass('stopped')) {
                                                if (!parent.hasClass('moving')) {
                                                        parent.attr('top-position', parent_top);
                                                        parent.addClass('moving');
                                                }
                                        }

                                        if (parentTopStored > 0) {

                                                // Scrolling up
                                                if (scrollTopVal < parent_top && lastScroll > scrollTopVal) {
                                                        parent.removeClass('limited');
                                                        parent.removeClass('stopped');
                                                }

                                                // Scrolling down
                                                if (!parent.hasClass('limited')) {
                                                        parent.removeClass('stopped');
                                                        parent.addClass('moving');
                                                        parent.css({'margin-top': scrollTopVal - parentTopStored + settings.paddingTop + 'px'});
                                                } else {
                                                        parent.addClass('stopped');
                                                        parent.removeClass('moving');
                                                }

                                                // Create scroll bar on tree menu if menu tree height larger than window height
                                                if (parent_height > window_height && !parent.hasClass('height_fixed')) {
                                                        parent.addClass('height_fixed');
                                                        parent.css({'overflow-y': 'auto', 'height': window_height + 'px'});
                                                        tree_menu.attr('max-scroll', '');
                                                }
                                        }
                                }
                        }

                        $(window).scroll(function () {
                                scrollMenu();
                        });

                        return this.bind("add", function (event, branches) {
                                $(branches).prev()
                                        .removeClass(CLASSES.last)
                                        .removeClass(CLASSES.lastCollapsable)
                                        .removeClass(CLASSES.lastExpandable)
                                        .find(">.hitarea")
                                        .removeClass(CLASSES.lastCollapsableHitarea)
                                        .removeClass(CLASSES.lastExpandableHitarea);
                                $(branches).find("li").andSelf().prepareBranches(settings).applyClasses(settings, toggler);
                                $(branches).find('li').applyShowMore(showMore);
                        });
                }
        });

        // classes used by the plugin
        // need to be styled via external stylesheet, see first example
        var CLASSES = $.fn.treeview.classes = {
                open: "open",
                closed: "closed",
                expandable: "expandable",
                expandableHitarea: "expandable-hitarea",
                lastExpandableHitarea: "lastExpandable-hitarea",
                collapsable: "collapsable",
                collapsableHitarea: "collapsable-hitarea",
                lastCollapsableHitarea: "lastCollapsable-hitarea",
                lastCollapsable: "lastCollapsable",
                lastExpandable: "lastExpandable",
                last: "last",
                hitarea: "hitarea"
        };

        // provide backwards compability
        $.fn.Treeview = $.fn.treeview;

})(jQuery);