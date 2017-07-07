/*
 * Lazy Load - jQuery plugin for lazy loading images
 *
 * Copyright (c) 2007-2013 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/lazyload
 *
 * Version:  1.9.3
 *
 */

(function($, window, document, undefined) {
    var $window = $(window);

    $.fn.lazyload = function(options) {
        var elements = this;
        var $container;
        var settings = {
            threshold       : 0,
            failure_limit   : 0,
            event           : "scroll",
            effect          : "show",
            container       : window,
            data_attribute  : "original",
            skip_invisible  : true,
            appear          : null,
            load            : null,
            placeholder     : "data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABQAAD/4QMraHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkNGNUNCRjBCNDVDNjExRTdBMzU3OUQ5RjQ2MDcwNzAyIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkNGNUNCRjBDNDVDNjExRTdBMzU3OUQ5RjQ2MDcwNzAyIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6Q0Y1Q0JGMDk0NUM2MTFFN0EzNTc5RDlGNDYwNzA3MDIiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Q0Y1Q0JGMEE0NUM2MTFFN0EzNTc5RDlGNDYwNzA3MDIiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAACAgICAgICAgICAwICAgMEAwIC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"
        };

        function update() {
            var counter = 0;

            elements.each(function() {
                var $this = $(this);
                if (settings.skip_invisible && !$this.is(":visible")) {
                    return;
                }
                if ($.abovethetop(this, settings) ||
                    $.leftofbegin(this, settings)) {
                        /* Nothing. */
                } else if (!$.belowthefold(this, settings) &&
                    !$.rightoffold(this, settings)) {
                        $this.trigger("appear");
                        /* if we found an image we'll load, reset the counter */
                        counter = 0;
                } else {
                    if (++counter > settings.failure_limit) {
                        return false;
                    }
                }
            });

        }

        if(options) {
            /* Maintain BC for a couple of versions. */
            if (undefined !== options.failurelimit) {
                options.failure_limit = options.failurelimit;
                delete options.failurelimit;
            }
            if (undefined !== options.effectspeed) {
                options.effect_speed = options.effectspeed;
                delete options.effectspeed;
            }

            $.extend(settings, options);
        }

        /* Cache container as jQuery as object. */
        $container = (settings.container === undefined ||
                      settings.container === window) ? $window : $(settings.container);

        /* Fire one scroll event per scroll. Not one scroll event per image. */
        if (0 === settings.event.indexOf("scroll")) {
            $container.bind(settings.event, function() {
                return update();
            });
        }

        this.each(function() {
            var self = this;
            var $self = $(self);

            self.loaded = false;

            /* If no src attribute given use data:uri. */
            if ($self.attr("src") === undefined || $self.attr("src") === false) {
                if ($self.is("img")) {
                    $self.attr("src", settings.placeholder);
                }
            }

            /* When appear is triggered load original image. */
            $self.one("appear", function() {
                if (!this.loaded) {
                    if (settings.appear) {
                        var elements_left = elements.length;
                        settings.appear.call(self, elements_left, settings);
                    }
                    $("<img />")
                        .bind("load", function() {

                            var original = $self.attr("data-" + settings.data_attribute);
                            $self.hide();
                            if ($self.is("img")) {
                                $self.attr("src", original);
                            } else {
                                $self.css("background-image", "url('" + original + "')");
                            }
                            $self[settings.effect](settings.effect_speed);

                            self.loaded = true;

                            /* Remove image from array so it is not looped next time. */
                            var temp = $.grep(elements, function(element) {
                                return !element.loaded;
                            });
                            elements = $(temp);

                            if (settings.load) {
                                var elements_left = elements.length;
                                settings.load.call(self, elements_left, settings);
                            }
                        })
                        .attr("src", $self.attr("data-" + settings.data_attribute));
                }
            });

            /* When wanted event is triggered load original image */
            /* by triggering appear.                              */
            if (0 !== settings.event.indexOf("scroll")) {
                $self.bind(settings.event, function() {
                    if (!self.loaded) {
                        $self.trigger("appear");
                    }
                });
            }
        });

        /* Check if something appears when window is resized. */
        $window.bind("resize", function() {
            update();
        });

        /* With IOS5 force loading images when navigating with back button. */
        /* Non optimal workaround. */
        if ((/(?:iphone|ipod|ipad).*os 5/gi).test(navigator.appVersion)) {
            $window.bind("pageshow", function(event) {
                if (event.originalEvent && event.originalEvent.persisted) {
                    elements.each(function() {
                        $(this).trigger("appear");
                    });
                }
            });
        }

        /* Force initial check if images should appear. */
        $(document).ready(function() {
            update();
        });

        return this;
    };

    /* Convenience methods in jQuery namespace.           */
    /* Use as  $.belowthefold(element, {threshold : 100, container : window}) */

    $.belowthefold = function(element, settings) {
        var fold;

        if (settings.container === undefined || settings.container === window) {
            fold = (window.innerHeight ? window.innerHeight : $window.height()) + $window.scrollTop();
        } else {
            fold = $(settings.container).offset().top + $(settings.container).height();
        }

        return fold <= $(element).offset().top - settings.threshold;
    };

    $.rightoffold = function(element, settings) {
        var fold;

        if (settings.container === undefined || settings.container === window) {
            fold = $window.width() + $window.scrollLeft();
        } else {
            fold = $(settings.container).offset().left + $(settings.container).width();
        }

        return fold <= $(element).offset().left - settings.threshold;
    };

    $.abovethetop = function(element, settings) {
        var fold;

        if (settings.container === undefined || settings.container === window) {
            fold = $window.scrollTop();
        } else {
            fold = $(settings.container).offset().top;
        }

        return fold >= $(element).offset().top + settings.threshold  + $(element).height();
    };

    $.leftofbegin = function(element, settings) {
        var fold;

        if (settings.container === undefined || settings.container === window) {
            fold = $window.scrollLeft();
        } else {
            fold = $(settings.container).offset().left;
        }

        return fold >= $(element).offset().left + settings.threshold + $(element).width();
    };

    $.inviewport = function(element, settings) {
         return !$.rightoffold(element, settings) && !$.leftofbegin(element, settings) &&
                !$.belowthefold(element, settings) && !$.abovethetop(element, settings);
     };

    /* Custom selectors for your convenience.   */
    /* Use as $("img:below-the-fold").something() or */
    /* $("img").filter(":below-the-fold").something() which is faster */

    $.extend($.expr[":"], {
        "below-the-fold" : function(a) { return $.belowthefold(a, {threshold : 0}); },
        "above-the-top"  : function(a) { return !$.belowthefold(a, {threshold : 0}); },
        "right-of-screen": function(a) { return $.rightoffold(a, {threshold : 0}); },
        "left-of-screen" : function(a) { return !$.rightoffold(a, {threshold : 0}); },
        "in-viewport"    : function(a) { return $.inviewport(a, {threshold : 0}); },
        /* Maintain BC for couple of versions. */
        "above-the-fold" : function(a) { return !$.belowthefold(a, {threshold : 0}); },
        "right-of-fold"  : function(a) { return $.rightoffold(a, {threshold : 0}); },
        "left-of-fold"   : function(a) { return !$.rightoffold(a, {threshold : 0}); }
    });

})(jQuery, window, document);
