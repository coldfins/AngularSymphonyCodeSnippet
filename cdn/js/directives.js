'use strict';

/* Directives */
// All the directives rely on jQuery.

angular.module('app.directives', ['ui.load'])
        .directive('uiModule', ['MODULE_CONFIG', 'uiLoad', '$compile', function (MODULE_CONFIG, uiLoad, $compile) {
                return {
                    restrict: 'A',
                    compile: function (el, attrs) {
                        var contents = el.contents().clone();
                        return function (scope, el, attrs) {
                            el.contents().remove();
                            uiLoad.load(MODULE_CONFIG[attrs.uiModule])
                                    .then(function () {
                                        $compile(contents)(scope, function (clonedElement, scope) {
                                            el.append(clonedElement);
                                        });
                                    });
                        }
                    }
                };
            }])
        .directive('uiShift', ['$timeout', function ($timeout) {
                return {
                    restrict: 'A',
                    link: function (scope, el, attr) {
                        // get the $prev or $parent of this el
                        var _el = $(el),
                                _window = $(window),
                                prev = _el.prev(),
                                parent,
                                width = _window.width()
                                ;

                        !prev.length && (parent = _el.parent());

                        function sm() {
                            $timeout(function () {
                                var method = attr.uiShift;
                                var target = attr.target;
                                _el.hasClass('in') || _el[method](target).addClass('in');
                            });
                        }

                        function md() {
                            parent && parent['prepend'](el);
                            !parent && _el['insertAfter'](prev);
                            _el.removeClass('in');
                        }

                        (width < 768 && sm()) || md();

                        _window.resize(function () {
                            if (width !== _window.width()) {
                                $timeout(function () {
                                    (_window.width() < 768 && sm()) || md();
                                    width = _window.width();
                                });
                            }
                        });
                    }
                };
            }])
        .directive('uiToggleClass', ['$timeout', '$document', function ($timeout, $document) {
                return {
                    restrict: 'AC',
                    link: function (scope, el, attr) {
                        el.on('click', function (e) {
                            e.preventDefault();
                            var classes = attr.uiToggleClass.split(','),
                                    targets = (attr.target && attr.target.split(',')) || Array(el),
                                    key = 0;
                            angular.forEach(classes, function (_class) {
                                var target = targets[(targets.length && key)];
                                (_class.indexOf('*') !== -1) && magic(_class, target);
                                $(target).toggleClass(_class);
                                key++;
                            });
                            $(el).toggleClass('active');

                            function magic(_class, target) {
                                var patt = new RegExp('\\s' +
                                        _class.
                                        replace(/\*/g, '[A-Za-z0-9-_]+').
                                        split(' ').
                                        join('\\s|\\s') +
                                        '\\s', 'g');
                                var cn = ' ' + $(target)[0].className + ' ';
                                while (patt.test(cn)) {
                                    cn = cn.replace(patt, ' ');
                                }
                                $(target)[0].className = $.trim(cn);
                            }
                        });
                    }
                };
            }])
        .directive('uiNav', ['$timeout', function ($timeout) {
                return {
                    restrict: 'AC',
                    link: function (scope, el, attr) {
                        var _window = $(window),
                                _mb = 768,
                                wrap = $('.app-aside'),
                                next,
                                backdrop = '.dropdown-backdrop';
                        // unfolded
                        el.on('click', 'a', function (e) {
                            next && next.trigger('mouseleave.nav');
                            var _this = $(this);
                            _this.parent().siblings(".active").toggleClass('active');
                            _this.next().is('ul') && _this.parent().toggleClass('active') && e.preventDefault();
                            // mobile
                            _this.next().is('ul') || ((_window.width() < _mb) && $('.app-aside').removeClass('show off-screen'));
                        });

                        // folded & fixed
                        el.on('mouseenter', 'a', function (e) {
                            next && next.trigger('mouseleave.nav');
                            $('> .nav', wrap).remove();
                            if (!$('.app-aside-fixed.app-aside-folded').length || (_window.width() < _mb) || $('.app-aside-dock').length)
                                return;
                            var _this = $(e.target)
                                    , top
                                    , w_h = $(window).height()
                                    , offset = 50
                                    , min = 150;

                            !_this.is('a') && (_this = _this.closest('a'));
                            if (_this.next().is('ul')) {
                                next = _this.next();
                            } else {
                                return;
                            }

                            _this.parent().addClass('active');
                            top = _this.parent().position().top + offset;
                            next.css('top', top);
                            if (top + next.height() > w_h) {
                                next.css('bottom', 0);
                            }
                            if (top + min > w_h) {
                                next.css('bottom', w_h - top - offset).css('top', 'auto');
                            }
                            next.appendTo(wrap);

                            next.on('mouseleave.nav', function (e) {
                                $(backdrop).remove();
                                next.appendTo(_this.parent());
                                next.off('mouseleave.nav').css('top', 'auto').css('bottom', 'auto');
                                _this.parent().removeClass('active');
                            });

                            $('.smart').length && $('<div class="dropdown-backdrop"/>').insertAfter('.app-aside').on('click', function (next) {
                                next && next.trigger('mouseleave.nav');
                            });

                        });

                        wrap.on('mouseleave', function (e) {
                            next && next.trigger('mouseleave.nav');
                            $('> .nav', wrap).remove();
                        });
                    }
                };
            }])
        .directive('uiScroll', ['$location', '$anchorScroll', function ($location, $anchorScroll) {
                return {
                    restrict: 'AC',
                    link: function (scope, el, attr) {
                        el.on('click', function (e) {
                            $location.hash(attr.uiScroll);
                            $anchorScroll();
                        });
                    }
                };
            }])
        .directive('uiFullscreen', ['uiLoad', '$document', '$window', function (uiLoad, $document, $window) {
                return {
                    restrict: 'AC',
                    template: '<i class="fa fa-expand fa-fw text"></i><i class="fa fa-compress fa-fw text-active"></i>',
                    link: function (scope, el, attr) {
                        el.addClass('hide');
                        uiLoad.load('js/libs/screenfull.min.js').then(function () {
                            // disable on ie11
                            if (screenfull.enabled && !navigator.userAgent.match(/Trident.*rv:11\./)) {
                                el.removeClass('hide');
                            }
                            el.on('click', function () {
                                var target;
                                attr.target && (target = $(attr.target)[0]);
                                screenfull.toggle(target);
                            });
                            $document.on(screenfull.raw.fullscreenchange, function () {
                                if (screenfull.isFullscreen) {
                                    el.addClass('active');
                                } else {
                                    el.removeClass('active');
                                }
                            });
                        });
                    }
                };
            }])
        .directive('uiButterbar', ['$rootScope', '$anchorScroll', function ($rootScope, $anchorScroll) {
                return {
                    restrict: 'AC',
                    template: '<span class="bar"></span>',
                    link: function (scope, el, attrs) {
                        el.addClass('butterbar hide');
                        scope.$on('$stateChangeStart', function (event) {
                            $anchorScroll();
                            el.removeClass('hide').addClass('active');
                        });
                        scope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState) {

                            event.targetScope.$watch('$viewContentLoaded', function () {
                                el.addClass('hide').removeClass('active');
                            })
                        });
                    }
                };
            }])
        .directive('setNgAnimate', ['$animate', function ($animate) {
                return {
                    link: function ($scope, $element, $attrs) {
                        $scope.$watch(function () {
                            return $scope.$eval($attrs.setNgAnimate, $scope);
                        }, function (valnew, valold) {
                            $animate.enabled(!!valnew, $element);
                        });
                    }
                };
            }])
        .directive('uiFocus', function ($timeout, $parse) {
            return {
                link: function (scope, element, attrs) {
                    var model = $parse(attrs.uiFocus);
                    scope.$watch(model, function (value) {
                        if (value === true) {
                            $timeout(function () {
                                element[0].focus();
                            });
                        }
                    });
                    element.bind('blur', function () {
                        scope.$apply(model.assign(scope, false));
                    });
                }
            };
        })
        .directive("fileread", [function () {
                return {
                    scope: {
                        fileread: "="
                    },
                    link: function (scope, element, attributes) {
                        element.bind("change", function (changeEvent) {
                            var reader = new FileReader();
                            reader.onload = function (loadEvent) {
                                scope.$apply(function () {
                                    scope.fileread = loadEvent.target.result;
                                });
                            }
                            reader.readAsDataURL(changeEvent.target.files[0]);
                        });
                    }
                }
            }])

        .directive('preventDefault', function () {
            return {
                restrict: 'A',
                link: function (scope, element, attrs) {
                    element.bind('click', function (event) {
                        alert('preventDefault');
                        event.preventDefault();
                        event.stopPropagation();
                    });
                }
            }
        })
        .directive('resize', function ($window) {
            return function (scope, element) {
                var w = angular.element($window);
                scope.getWindowDimensions = function () {
                    return {
                        'h': w.height(),
                        'w': w.width()
                    };
                };
                scope.$watch(scope.getWindowDimensions, function (newValue, oldValue) {
                    scope.windowHeight = newValue.h;
                    scope.windowWidth = newValue.w;

                    scope.style = function () {
                        return {
                            'height': (newValue.h - 100) + 'px',
                            'width': (newValue.w - 100) + 'px'
                        };
                    };

                }, true);

                w.bind('resize', function () {
                    scope.$apply();
                });
            };
        })
        .directive('popoverHtmlUnsafePopup', function () {
            return {
                restrict: 'EA',
                replace: true,
                scope: {title: '@', content: '@', placement: '@', animation: '&', isOpen: '&'},
                template: '<div class="popover {{placement}}" ng-class="{ in: isOpen(), fade: animation() }"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title" bind-html-unsafe="title" ng-show="title"></h3><div class="popover-content" bind-html-unsafe="content"></div></div></div>'
            };
        })
        .directive('popoverHtmlUnsafe', ['$tooltip', function ($tooltip) {
                return $tooltip('popoverHtmlUnsafe', 'popover', 'click');
            }])
        .directive('ngEnter', function () {
            return function (scope, element, attrs) {
                element.bind("keydown keypress", function (event) {
                    if (event.which === 13) {
                        scope.$apply(function () {
                            scope.$eval(attrs.ngEnter);
                        });

                        event.preventDefault();
                    }
                });
            };
        })
        .directive('showonhoverparent',
                function () {
                    return {
                        link: function (scope, element, attrs) {
                            element.parent().bind('mouseenter', function () {
                                element.show();
                            });
                            element.parent().bind('mouseleave', function () {
                                element.hide();
                            });
                        }
                    };
                });

