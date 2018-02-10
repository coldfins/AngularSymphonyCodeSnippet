/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
(function e(t, n, r) {
    function s(o, u) {
        if (!n[o]) {
            if (!t[o]) {
                var a = typeof require == "function" && require;
                if (!u && a)
                    return a(o, !0);
                if (i)
                    return i(o, !0);
                var f = new Error("Cannot find module '" + o + "'");
                throw f.code = "MODULE_NOT_FOUND", f
            }
            var l = n[o] = {exports: {}};
            t[o][0].call(l.exports, function (e) {
                var n = t[o][1][e];
                return s(n ? n : e)
            }, l, l.exports, e, t, n, r)
        }
        return n[o].exports
    }
    var i = typeof require == "function" && require;
    for (var o = 0; o < r.length; o++)
        s(r[o]);
    return s
})({1: [function (require, module, exports) {
            'use strict';
            var _createClass = function () {
                function defineProperties(target, props) {
                    for (var i = 0; i < props.length; i++) {
                        var descriptor = props[i];
                        descriptor.enumerable = descriptor.enumerable || false;
                        descriptor.configurable = true;
                        if ("value" in descriptor)
                            descriptor.writable = true;
                        Object.defineProperty(target, descriptor.key, descriptor);
                    }
                }
                return function (Constructor, protoProps, staticProps) {
                    if (protoProps)
                        defineProperties(Constructor.prototype, protoProps);
                    if (staticProps)
                        defineProperties(Constructor, staticProps);
                    return Constructor;
                };
            }();
            var _Header = require('HeaderPeople');
            function _classCallCheck(instance, Constructor) {
                if (!(instance instanceof Constructor)) {
                    throw new TypeError("Cannot call a class as a function");
                }
            }

            function _possibleConstructorReturn(self, call) {
                if (!self) {
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                }
                return call && (typeof call === "object" || typeof call === "function") ? call : self;
            }

            function _inherits(subClass, superClass) {
                if (typeof superClass !== "function" && superClass !== null) {
                    throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
                }
                subClass.prototype = Object.create(superClass && superClass.prototype, {constructor: {value: subClass, enumerable: false, writable: true, configurable: true}});
                if (superClass)
                    Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
            } /* Custom Components */


            /* Render Main Controller */

            var App = function (_React$Component) {
                _inherits(App, _React$Component);
                function App() {
                    _classCallCheck(this, App);
                    return _possibleConstructorReturn(this, Object.getPrototypeOf(App).apply(this, arguments));
                }
                return App;
            }(React.Component);
            ;
            /* Render App */

        }, {"HeaderPeople": 4}], 3: [function (require, module, exports) {
            'use strict';
            Object.defineProperty(exports, "__esModule", {
                value: true
            });
            var _extends = Object.assign || function (target) {
                for (var i = 1; i < arguments.length; i++) {
                    var source = arguments[i];
                    for (var key in source) {
                        if (Object.prototype.hasOwnProperty.call(source, key)) {
                            target[key] = source[key];
                        }
                    }
                }
                return target;
            };
            var _createClass = function () {
                function defineProperties(target, props) {
                    for (var i = 0; i < props.length; i++) {
                        var descriptor = props[i];
                        descriptor.enumerable = descriptor.enumerable || false;
                        descriptor.configurable = true;
                        if ("value" in descriptor)
                            descriptor.writable = true;
                        Object.defineProperty(target, descriptor.key, descriptor);
                    }
                }
                return function (Constructor, protoProps, staticProps) {
                    if (protoProps)
                        defineProperties(Constructor.prototype, protoProps);
                    if (staticProps)
                        defineProperties(Constructor, staticProps);
                    return Constructor;
                };
            }();
            function _classCallCheck(instance, Constructor) {
                if (!(instance instanceof Constructor)) {
                    throw new TypeError("Cannot call a class as a function");
                }
            }

            function _possibleConstructorReturn(self, call) {
                if (!self) {
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                }
                return call && (typeof call === "object" || typeof call === "function") ? call : self;
            }

            function _inherits(subClass, superClass) {
                if (typeof superClass !== "function" && superClass !== null) {
                    throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
                }
                subClass.prototype = Object.create(superClass && superClass.prototype, {constructor: {value: subClass, enumerable: false, writable: true, configurable: true}});
                if (superClass)
                    Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
            }

            var Graphic = exports.Graphic = function (_React$Component) {
                _inherits(Graphic, _React$Component);
                _createClass(Graphic, [{
                        key: 'render',
                        value: function render() {
                            return React.createElement(
                                    'aside',
                                    _extends({}, this.props, {ref: 'aside', className: this.props.direction, id: this.props.uniqueName}),
                                    display[this.props.uniqueName] ? display[this.props.uniqueName](this.state.size) : undefined
                                    );
                        }
                    }]);
                return Graphic;
            }(React.Component);
// Set headcount
            function setSize() {
                if (window.innerWidth <= 768) {
                    return 'mobile';
                } else if (window.innerWidth > 768) {
                    return 'desktop';
                }
            }

        }, {}], 4: [function (require, module, exports) {
            'use strict';
            Object.defineProperty(exports, "__esModule", {
                value: true
            });
            exports.Introduction = undefined;
            var _createClass = function () {
                function defineProperties(target, props) {
                    for (var i = 0; i < props.length; i++) {
                        var descriptor = props[i];
                        descriptor.enumerable = descriptor.enumerable || false;
                        descriptor.configurable = true;
                        if ("value" in descriptor)
                            descriptor.writable = true;
                        Object.defineProperty(target, descriptor.key, descriptor);
                    }
                }
                return function (Constructor, protoProps, staticProps) {
                    if (protoProps)
                        defineProperties(Constructor.prototype, protoProps);
                    if (staticProps)
                        defineProperties(Constructor, staticProps);
                    return Constructor;
                };
            }();
            var _points = require('points');
            var _images = require('images');
            function _classCallCheck(instance, Constructor) {
                if (!(instance instanceof Constructor)) {
                    throw new TypeError("Cannot call a class as a function");
                }
            }

            function _possibleConstructorReturn(self, call) {
                if (!self) {
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                }
                return call && (typeof call === "object" || typeof call === "function") ? call : self;
            }

            function _inherits(subClass, superClass) {
                if (typeof superClass !== "function" && superClass !== null) {
                    throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
                }
                subClass.prototype = Object.create(superClass && superClass.prototype, {constructor: {value: subClass, enumerable: false, writable: true, configurable: true}});
                if (superClass)
                    Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
            } // Content


// Components


            var mouse = {
                _threshold: 92,
                _x: 0,
                _y: 0
            };
            var Introduction = exports.Introduction = function (_React$Component) {
                _inherits(Introduction, _React$Component);
                function Introduction(props) {
                    _classCallCheck(this, Introduction);
                    var _this = _possibleConstructorReturn(this, Object.getPrototypeOf(Introduction).call(this, props));
                    _this.componentDidMount = function () {
                        /*
                        window.addEventListener('resize', function () {

                            _this.setState({mouse: undefined});
                            _this._renderGraph();
                        });
                        // Render the Canvas background
                        */
                        _this._renderGraph();
                    };
                    _this._mouse = function (e) {
                        _this._renderGraph();
                        var x_distance = e.pageX;
                        var y_distance = e.pageY - document.getElementById('heads').offsetTop;
                        // Animate the mouse position
                        TweenMax.to([mouse], 0.1, {_x: x_distance, _y: y_distance, ease: Elastic.easeOut.config(1, 0.25)});
                        _this.setState({mouse: mouse});
                    };
                    _this._renderGraph = function () {

                        // Setup Canvas
                        var canvas = document.getElementById('people');
                        var context = canvas.getContext('2d');
                        var heads = document.getElementById('heads');
                        // Setup Canvas Size
                        if (window.innerWidth > 1024) {
                            canvas.width = window.innerWidth;
                            canvas.height = window.innerHeight - heads.offsetTop - 70;
                        } else {
                            canvas.width = 1024;
                            // Adjust height for tabs
                            if (window.innerWidth >= 768) {
                                canvas.height = window.innerHeight - heads.offsetTop - 70;
                            } else {
                                canvas.height = window.innerHeight - heads.offsetTop;
                            }
                        }

                        // Override height if needed
                        if (window.innerHeight < 900 && window.innerWidth >= 768) {
                            canvas.height = 480;
                        }

                        if (window.innerHeight < 667 && window.innerWidth < 768) {
                            canvas.height = 400;
                        }

                        // Render the lines
                        var renderLines = function renderLines() {

                            var canvasW = canvas.width;
                            var canvasH = canvas.height;
                            // Clear for re-render
                            context.globalCompositeOperation = 'source-over';
                            context.clearRect(0, 0, canvasW, canvasH);
                            var pointX = void 0,
                                    pointY = void 0,
                                    childX = void 0,
                                    childY = void 0;
                            _this.setState({size: setHeadcount()});
                            // Go through points
                            _points.points.map(function (point, i) {

                                point.childs.map(function (child, i) {
                                    context.beginPath();
                                    context.lineWidth = 1;
                                    context.strokeStyle = 'rgba(224,227,229,1)';
                                    var childID = _points.points[child - 1];
                                    //console.log(childID.x + " " + childID.y);
                                    //console.log(calcPercentage(point.x, point.y, canvasW, canvasH));
                                    var parentPosition = calcPercentage(point.x, point.y, canvasW, canvasH);
                                    var childPosition = calcPercentage(childID.x, childID.y, canvasW, canvasH, childID.id);
                                    //console.log(context.moveTo(1,1));
                                    context.moveTo(parentPosition.x, parentPosition.y);
                                    context.lineTo(childPosition.x, childPosition.y);
                                    context.stroke();
                                    context.closePath();
                                });
                            });
                        };
                        renderLines();
                    };
                    _this.state = {
                        mouse: undefined,
                        size: setHeadcount()
                    };
                    return _this;
                }

                _createClass(Introduction, [{
                        key: 'render',
                        value: function render() {
                            return React.createElement(
                                    'aside',
                                    {onMouseMove: this._mouse},
                                    React.createElement(Images, {size: this.state.size, mouse: this.state.mouse}),
                                    React.createElement('canvas', {id: 'people'})
                                    );
                        }
                    }]);
                return Introduction;
            }(React.Component);
// Set headcount


            function setHeadcount() {
                if (window.innerWidth < 768) {
                    return 'mobile';
                } else if (window.innerWidth >= 768 && window.innerWidth < 1024) {
                    return 'tablet';
                } else if (window.innerWidth >= 1024 && window.innerWidth <= 1140) {
                    return 'landscape';
                } else {
                    return 'all';
                }
            }

// Calculate positions
            function calcPercentage(x, y, w, h) {

                var threshold = mouse._threshold;
                if (w <= 1024) {
                    w = 1024;
                }
                if (w > 1024 && w <= 1280) {
                    threshold = mouse._threshold / 3 * 2;
                }

                var obj = {
                    x: Math.round(x / 1440 * w),
                    y: Math.round(y / 620 * h)
                };
                if (window.innerWidth > 1024 && obj.x - mouse._x < threshold && obj.x - mouse._x > -threshold && obj.y - mouse._y < threshold && obj.y - mouse._y > -threshold) {
                    obj.x = mouse._x - mouse._x / x;
                    obj.y = mouse._y - mouse._y / y;
                }

                return obj;
            }

            var Images = function (_React$Component2) {
                _inherits(Images, _React$Component2);
                function Images() {
                    var _Object$getPrototypeO;
                    var _temp, _this2, _ret;
                    _classCallCheck(this, Images);
                    for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
                        args[_key] = arguments[_key];
                    }

                    return _ret = (_temp = (_this2 = _possibleConstructorReturn(this, (_Object$getPrototypeO = Object.getPrototypeOf(Images)).call.apply(_Object$getPrototypeO, [this].concat(args))), _this2), _this2.componentDidMount = function () {

                        var element = document.getElementById('imageHolder').getElementsByClassName('image');
                        TweenMax.staggerTo(element, 0.3, {scale: 1, opacity: 1}, 0.02);
                    }, _this2.componentWillReceiveProps = function (nextProps) {

                        if (_this2.props.size !== nextProps.size) {

                            setTimeout(function () {
                                var element = document.getElementById('imageHolder').getElementsByClassName('image');
                                TweenMax.staggerTo(element, 0.3, {scale: 1, opacity: 1}, 0.02);
                            }, 150);
                        }
                    }, _this2._buildImages = function (img) {

                        // Setup Canvas
                        var canvas = document.getElementById('people');
                        var canvasWidth = void 0,
                                canvasHeight = void 0;
                        if (window.innerWidth > 1024) {
                            // All the width setups
                            canvasWidth = window.innerWidth;
                            canvasHeight = window.innerHeight - heads.offsetTop - 70;
                        } else {

                            // Lock width
                            canvasWidth = 1024;
                            // Adjust height for tabs
                            if (window.innerWidth >= 768) {
                                canvasHeight = window.innerHeight - heads.offsetTop - 70;
                            } else {
                                canvasHeight = window.innerHeight - heads.offsetTop;
                            }
                        }

                        // Override height if needed
                        if (window.innerHeight < 900 && window.innerWidth >= 768) {
                            canvasHeight = 480;
                        }
                        if (window.innerHeight < 667 && window.innerWidth < 768) {
                            canvasHeight = 400;
                        }

                        var position = calcPercentage(_points.points[img.point - 1].x, _points.points[img.point - 1].y, canvasWidth, canvasHeight);
                        var style = {
                            transform: 'translate3d(' + position.x + 'px, ' + (position.y + 'px') + ', 0)',
                            marginLeft: '-' + img.size / 2 + 'px',
                            marginTop: '-' + img.size / 2 + 'px',
                            width: img.size + 'px'
                        };
                        return style;
                    }, _temp), _possibleConstructorReturn(_this2, _ret);
                }

                _createClass(Images, [{
                        key: 'render',
                        value: function render() {
                            var _this3 = this;
                            return React.createElement(
                                    'div',
                                    {id: 'imageHolder'},
                                    this.props.size ? _images.imageList[this.props.size].map(function (image, i) {
                                return React.createElement(Image, {index: image, data: _images.images[image], key: i, style: _this3._buildImages(_images.images[image])});
                            }) : undefined
                                    );
                        }
                    }]);
                return Images;
            }(React.Component);
            var Image = function (_React$Component3) {
                _inherits(Image, _React$Component3);
                function Image() {
                    var _Object$getPrototypeO2;
                    var _temp2, _this4, _ret2;
                    _classCallCheck(this, Image);
                    for (var _len2 = arguments.length, args = Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
                        args[_key2] = arguments[_key2];
                    }

                    return _ret2 = (_temp2 = (_this4 = _possibleConstructorReturn(this, (_Object$getPrototypeO2 = Object.getPrototypeOf(Image)).call.apply(_Object$getPrototypeO2, [this].concat(args))), _this4), _this4._renderImage = function () {
                        if (_this4.props.data.company) {
                            return React.createElement(
                                    'a',
                                    {className: 'scale', href: _this4.props.data.url, target: '_blank'},
                                    React.createElement(
                                            'span',
                                            {className: 'image-wrap'},
                                            React.createElement('img', {src: _this4.props.data.name, id: 'image-' + (_this4.props.data.point - 1)})
                                            ),
                                    React.createElement(
                                            'span',
                                            {className: 'company-logo', style: {background: 'url(\'https://cdn-dev.mobintouch.com/img/landing/company-logo/' + _this4.props.data.company + '\') center center no-repeat'}}

                                    )
                                    );
                        } else {
                            return React.createElement(
                                    'a',
                                    {className: 'scale', href: _this4.props.data.url, target: '_blank'},
                                    React.createElement(
                                            'span',
                                            {className: 'image-wrap'},
                                            React.createElement('img', {src: _this4.props.data.name, id: 'image-' + (_this4.props.data.point - 1)})
                                            )
                                    );
                        }
                    }, _temp2), _possibleConstructorReturn(_this4, _ret2);
                }

                _createClass(Image, [{
                        key: 'render',
                        value: function render() {
                            return React.createElement(
                                    'div',
                                    {className: 'image', 'data-index': this.props.index, style: this.props.style},
                                    this._renderImage()
                                    );
                        }
                    }]);
                return Image;
            }(React.Component);
            ReactDOM.render(React.createElement(Introduction, null), document.getElementById('heads'));
        }, {"images": 8, "points": 9}], 8: [function (require, module, exports) {
            'use strict';
            Object.defineProperty(exports, "__esModule", {
                value: true
            });

            /*
            var elem = angular.element(document.querySelector('[data-ng-app]'));
            var injector = elem.injector();
            var apiEndpoint = injector.get('ENV').apiEndpoint;
            console.log(apiEndpoint);
            */

            var images = exports.images = [
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/jeanmarcalomassor.jpeg', size: 105, point: 1, test: 120, url: 'https://www.mobintouch.com/profile/jeanmarcalomassor', company: 'mobpartner.png'}, // Index: 0
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/hughpu.jpeg', size: 62, point: 2, url: 'https://www.mobintouch.com/profile/hughpu', company: 'hughpu-Alibaba-Group-UC-Mobile-Business-Group.png'}, // Index: 1
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/simonastaburuaga.jpeg', size: 72, point: 3, url: 'https://www.mobintouch.com/profile/simonastaburuaga', company: 'jampp.svg'}, // Index: 2
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/rudytabasa.jpeg', size: 60, point: 4, url: 'https://www.mobintouch.com/profile/rudytabasa', company: 'operamediaworks.png'}, // Index: 3
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/arielporchera.png', size: 90, point: 5, url: 'https://www.mobintouch.com/profile/arielporchera', company: 'waypedia.png'}, // Index: 4
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/alinavasilyeva.jpeg', size: 60, point: 6, url: 'https://www.mobintouch.com/profile/alinavasilyeva', company: 'gowide.png'}, // Index: 5
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/ashwinshekhar.png', size: 90, point: 7, url: 'https://www.mobintouch.com/profile/ashwinshekhar', company: 'glispa.png'}, // Index: 6
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/edwardbenkendorfer2.jpeg', size: 90, point: 8, url: 'https://www.mobintouch.com/profile/edwardbenkendorfer2', company: 'fyber.svg'}, // Index: 7
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/jingweiliu.png', size: 80, point: 9, url: 'https://www.mobintouch.com/profile/jingweiliu', company: 'funplus.png'}, // Index: 8
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/marcusimaizumi.png', size: 72, point: 10, url: 'https://www.mobintouch.com/profile/marcusimaizumi', company: 'startapp.png'}, // Index: 9
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/charlygrange.jpeg', size: 70, point: 11, url: 'https://www.mobintouch.com/profile/charlygrange', company: 'adcash.svg'}, // Index: 10
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/bradhall.jpeg', size: 88, point: 12, url: 'https://www.mobintouch.com/profile/bradhall', company: 'operamediaworks.png'}, // Index: 11
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/millarddivinagracia.png', size: 62, point: 13, url: 'https://www.mobintouch.com/profile/millarddivinagracia', company: 'bluagile.png'}, // Index: 12
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/maximbezpaliy1.jpeg', size: 110, point: 14, url: 'https://www.mobintouch.com/profile/maximbezpaliy1', company: 'Vulcan-Partners-Limited.png'}, // Index: 13
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/robcrumpler.jpeg', size: 72, point: 15, url: 'https://www.mobintouch.com/profile/robcrumpler', company: 'altrooz.png'}, // Index: 14
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/manishsinghrajawat.png', size: 62, point: 17, url: 'https://www.mobintouch.com/profile/manishsinghrajawat', company: 'airpush.png'}, // Index: 15
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/kareldebeule.jpeg', size: 86, point: 18, url: 'https://www.mobintouch.com/profile/kareldebeule', company: 'kimia.png'}, // Index: 16
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/robweber.jpeg', size: 72, point: 19, url: 'https://www.mobintouch.com/profile/robweber', company: 'nativex.png'}, // Index: 17
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/jimcrowley.jpeg', size: 80, point: 20, url: 'https://www.mobintouch.com/profile/jimcrowley', company: 'skyhook.png'}, // Index: 18
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/timmccloud.jpeg', size: 78, point: 21, url: 'https://www.mobintouch.com/profile/timmccloud', company: 'big-fish.png'}, // Index: 19
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/irinamorozova.png', size: 94, point: 23, url: 'https://www.mobintouch.com/profile/irinamorozova', company: 'mail-ru.png'}, // Index: 20
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/tamarabasova.jpeg', size: 112, point: 25, url: 'https://www.mobintouch.com/profile/tamarabasova', company: 'pixonic.png'}, // Index: 21
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/anthonydimayuga.png', size: 68, point: 26, url: 'https://www.mobintouch.com/profile/anthonydimayuga', company: 'operamediaworks.png'}, // Index: 22
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/dmitrysoldatenko.png', size: 78, point: 27, url: 'https://www.mobintouch.com/profile/dmitrysoldatenko', company: 'gameinsight.png'}, // Index: 23
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/mireiarivero.jpeg', size: 62, point: 28, url: 'https://www.mobintouch.com/profile/mireiarivero', company: 'socialpoint.png'}, // Index: 24
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/nikolettapiatkowska.jpeg', size: 100, point: 29, url: 'https://www.mobintouch.com/profile/nikolettapiatkowska', company: 'game-genetics.png'}, // Index: 25
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/tommoniak.jpeg', size: 90, point: 30, url: 'https://www.mobintouch.com/profile/tommoniak', company: 'apptap.png'}, // Index: 26
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/roifainstein.png', size: 80, point: 31, url: 'https://www.mobintouch.com/profile/roifainstein', company: 'inneractive.png'}, // Index: 27
                {name: 'https://cdn.mobintouch.com/img/profile/avatars/benjaminroodman.jpeg', size: 70, point: 32, url: 'https://www.mobintouch.com/profile/benjaminroodman', company: 'appsflyer.png'}, // Index: 28
                {name: 'https://cdn.mobintouch.com/img/company/avatars/fiksu.png', size: 86, point: 36, url: 'https://www.mobintouch.com/company/fiksu'}, // Index: 29
                {name: 'https://cdn.mobintouch.com/img/company/avatars/appnext.png', size: 88, point: 37, url: 'https://www.mobintouch.com/company/appnext'}, // Index: 30
                {name: 'https://cdn.mobintouch.com/img/company/avatars/matomy-media-group.png', size: 74, point: 40, url: 'https://www.mobintouch.com/company/matomy-media-group'}, // Index: 31
                {name: 'https://cdn.mobintouch.com/img/company/avatars/smaato.png', size: 70, point: 41, url: 'https://www.mobintouch.com/company/smaato'} // Index: 32
            ];
            var imageList = exports.imageList = {
                mobile: [12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22],
                tablet: [5, 6, 7, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28],
                landscape: [2, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29],
                all: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32]
            };
        }, {}], 9: [function (require, module, exports) {
            "use strict";
            Object.defineProperty(exports, "__esModule", {
                value: true
            });
            var points = exports.points = [{id: 1, x: 107, y: 55, childs: [2, 3, 6]}, {id: 2, x: 48, y: 166, childs: [3, 4, 6]}, {id: 3, x: 164, y: 223, childs: [4, 5, 6, 7, 8]}, {id: 4, x: 50, y: 274, childs: [5]}, {id: 5, x: 87, y: 407, childs: [8, 9]}, {id: 6, x: 255, y: 67, childs: [7, 8, 10]}, {id: 7, x: 297, y: 170, childs: [8, 10, 11, 13]}, {id: 8, x: 257, y: 317, childs: [9, 10, 11, 12]}, {id: 9, x: 236, y: 482, childs: [10, 11, 12, 15]}, {id: 10, x: 404, y: 240, childs: [12, 13, 14]}, {id: 11, x: 411, y: 385, childs: [12, 13, 14, 15]}, {id: 12, x: 375, y: 520, childs: [15, 16]}, {id: 13, x: 510, y: 178, childs: [14, 17, 18]}, {id: 14, x: 541, y: 344, childs: [17, 18, 19, 20]}, {id: 15, x: 527, y: 489, childs: [16, 18, 19, 20]}, {id: 16, x: 501, y: 582, childs: [19, 24]}, {id: 17, x: 636, y: 237, childs: [18, 20]}, {id: 18, x: 709, y: 377, childs: [19, 20, 22, 23, 25]}, {id: 19, x: 664, y: 509, childs: [21, 23, 24]}, {id: 20, x: 767, y: 239, childs: [21, 22, 25]}, {id: 21, x: 918, y: 202, childs: [22, 27, 28, 31]}, {id: 22, x: 877, y: 311, childs: [23, 25, 27, 28]}, {id: 23, x: 792, y: 493, childs: [25, 26, 29]}, {id: 24, x: 761, y: 593, childs: [25, 26]}, {id: 25, x: 898, y: 385, childs: [26, 28, 29, 32]}, {id: 26, x: 913, y: 535, childs: [28, 29]}, {id: 27, x: 1019, y: 157, childs: [28, 30, 31, 32, 33]}, {id: 28, x: 1000, y: 300, childs: [29, 31, 32, 34]}, {id: 29, x: 1017, y: 483, childs: [32]}, {id: 30, x: 1177, y: 140, childs: [31, 33, 36, 40]}, {id: 31, x: 1122, y: 271, childs: [33, 34, 35]}, {id: 32, x: 1150, y: 411, childs: [33, 34]}, {id: 33, x: 1192, y: 251, childs: [34, 35, 36, 39]}, {id: 34, x: 1198, y: 383, childs: [35, 37]}, {id: 35, x: 1242, y: 335, childs: [36, 37, 38]}, {id: 36, x: 1260, y: 252, childs: [37, 38, 39]}, {id: 37, x: 1308, y: 382, childs: [38, 41, 47]}, {id: 38, x: 1347, y: 251, childs: [41, 41, 47]}, {id: 39, x: 1313, y: 143, childs: [40, 41, 45]}, {id: 40, x: 1354, y: 89, childs: [41, 45]}, {id: 41, x: 1399, y: 218, childs: [45, 46, 47]}, {id: 42, x: -53, y: 106, childs: [1, 2, 4, 6]}, {id: 43, x: -64, y: 227, childs: [2, 4]}, {id: 44, x: -15, y: 340, childs: [3, 4, 5]}, {id: 45, x: 1472, y: 94, childs: [46]}, {id: 46, x: 1480, y: 198, childs: [47]}, {id: 47, x: 1487, y: 380, childs: []}];
        }, {}]}, {}, [1]);


