'use strict';

// Declare app level module which depends on filters, and services
var app = angular.module('app', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngStorage',
    'ui.router',
    'ui.bootstrap',
    'ui.load',
    'ui.jq',
    'ui.validate',
    'oc.lazyLoad',
    'pascalprecht.translate',
    'app.filters',
    'app.services',
    'app.directives',
    'app.controllers',
    'angular-jwt',
    'ngTagsInput',
    'ui-notification',
    'ngAutocomplete',
    'ngSanitize',
    'ui.utils',
    'ui.map',
    'monospaced.elastic',
    'angular-inview',
    'ngFacebook',
    'google-signin',
    'ui.sortable',
    'textAngular'
])
        .run(['$rootScope', '$state', '$stateParams', '$window', '$location', '$anchorScroll', '$timeout', '$http', 'userService', 'ENV', 'alertService', function ($rootScope, $state, $stateParams, $window, $location, $anchorScroll, $timeout, $http, userService, ENV, alertService) {
                $rootScope.cdnBaseUrl = ENV.baseUrl;
                $rootScope.$state = $state;
                $rootScope.$stateParams = $stateParams;
                $rootScope.alerts = [];
                $rootScope.lastVersion = 0.1;

                //$rootScope.appState.pageTitle = $rootScope.appState || {};

                //$rootScope.pageDescription = 'Mobintouch is the First Mobile Advertising Social Network which gather in one place advertisers and Media sellers...';

                // Load the facebook SDK asynchronously
                (function () {
                    // If we've already installed the SDK, we're done
                    if (document.getElementById('facebook-jssdk')) {
                        return;
                    }

                    // Get the first script element, which we'll use to find the parent node
                    var firstScriptElement = document.getElementsByTagName('script')[0];

                    // Create a new script element and set its id
                    var facebookJS = document.createElement('script');
                    facebookJS.id = 'facebook-jssdk';

                    // Set the new script's source to the source of the Facebook JS SDK
                    facebookJS.src = '//connect.facebook.net/en_US/all.js';

                    // Insert the Facebook JS SDK into the DOM
                    firstScriptElement.parentNode.insertBefore(facebookJS, firstScriptElement);
                }());

                $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams, current) {
                    var hash = $location.hash();
                    if (hash) {
                        toParams['#'] = hash;
                    }
                    $rootScope.fromState = fromState;
                    $rootScope.previousPage = "";
                    if ((fromState.name == "stack.publicOffer" || fromState.name == "stack.offerdetail") && toState.name == "access.signin") {
                        $rootScope.previousPage = "app.offers.all";
                    }
                    $rootScope.updateActiveLink('');

                });
                $rootScope.$on("$stateChangeSuccess", function (event, toState, toParams, fromState, fromParams) {
                    // INTERCOM
                    $rootScope.previousname = toState;
                    $rootScope.previousparams = toParams;
                    if (toState.url !== fromState.url)
                        $window.ga('send', 'pageview', {page: $location.url()});
                    var user = userService.user ? userService.user : {};
                    alertService.update();
                    angular.element('.op_effect').remove();
                    angular.element('body').css({'overflow-y': 'auto', 'position': 'relative'});

                    /*if (user.username !== 'undefined' && typeof (user.playerId) === 'undefined')
                     {
                     OneSignal.push(["getUserId", function (userId) {
                     $http({
                     method: "PUT",
                     url: ENV.apiEndpoint + '/api/updateplayerid',
                     data: {
                     type: 'set',
                     player_id: userId
                     }
                     });
                     }]);
                     }*/
                    if (!angular.isUndefined(user) && !angular.isUndefined(user.username) && !angular.isUndefined(user.id) && !angular.isUndefined(user.email) && !angular.isUndefined(user.company)) {
                        window.Intercom('boot', {
                            app_id: (window.location.host.indexOf('www.mobintouch.com') >= 0) ? "tp8bvnjt" : "rapfa6z9",
                            email: user.email,
                            user_id: user.id,
                            username: user.username,
                            name: user.name + ' ' + user.lastname,
                            company: user.company,
                            created_at: (parseInt((user.id).toString().substring(0, 8), 16))
                        });
                    }
                    $rootScope.ogImage = 'https://cdn.mobintouch.com/img/mobintouch-logo-325square.png';

                    if (toState.pageTitle)
                        $rootScope.pageTitle = toState.pageTitle;
                    if (toState.pageDescription)
                        $rootScope.pageDescription = toState.pageDescription;
                    if (toState.isNoIndex)
                        $rootScope.isNoIndex = toState.isNoIndex;
                    if (toState.isError404)
                        $rootScope.isError404 = toState.isError404;
                });


                /*$rootScope.$on("$stateChangeSuccess", function (event, toState, toParams, fromState, fromParams) {
                 
                 //TODO: We need to update the title and meta here, and then retrieve it and assign it to appState
                 $rootScope.appState.pageTitle = toState.pageTitle;
                 });*/

                $rootScope.getMutualConnectionList = function (connections) {
                    var html = angular.element("<ul/>");
                    if (connections.length > 10) {
                        angular.forEach(connections.slice(0, 9), function (i) {
                            html.append("<li>" + i.name + " " + i.lastname + "</li>");
                        });
                        html.append("<li>and " + (connections.length - 10) + " more</li>");
                    } else {
                        angular.forEach(connections, function (i) {
                            html.append("<li>" + i.name + " " + i.lastname + "</li>");
                        });
                    }
                    return html.html();

                };
            }
        ])
        .config(['$stateProvider', '$urlRouterProvider', '$controllerProvider', '$compileProvider', '$filterProvider', '$provide', '$locationProvider', 'ENV', '$facebookProvider', 'GoogleSigninProvider', '$uibTooltipProvider', '$qProvider',
            function ($stateProvider, $urlRouterProvider, $controllerProvider, $compileProvider, $filterProvider, $provide, $locationProvider, ENV, $facebookProvider, GoogleSigninProvider, $uibTooltipProvider, $qProvider) {
                $uibTooltipProvider.setTriggers({'mouseenter manual': 'manual'});
                $qProvider.errorOnUnhandledRejections(false); //to prevent error log on console for cancel
                if (window.innerWidth < 767) {
                    $uibTooltipProvider.options({trigger: 'dontTrigger'});

                    /*addEventListener(document, "touchstart", function (e) {
                     console.log(e.defaultPrevented);  // will be false
                     console.log('called...');
                     e.preventDefault();   // does nothing since the listener is passive
                     console.log(e.defaultPrevented);  // still false
                     }, Modernizr.passiveeventlisteners ? {passive: true} : false);*/
                }

                window.addEventListener('orientationchange', function ()
                {
                    if (window.innerHeight > window.innerWidth)
                    {
                        $('body').css({'transform': 'rotate(0deg)'});
                    }
                });

                $provide.decorator("$exceptionHandler", ["$delegate", "$window", "ENV", function ($delegate, $window, ENV) {
                        return function (exception, cause) {
                            if (ENV.name === "production") {
                                $window.sessionStorage.log = $window.sessionStorage.log || [];
                            } else {
                                $delegate(exception, cause);
                            }
                            $delegate(exception, cause);
                            ga(
                                    'send',
                                    'event',
                                    'AngularJS error',
                                    exception.message,
                                    exception.stack,
                                    0,
                                    true
                                    );

                        };
                    }]);
                //Register Facebook and google app ids to login and signup with facebook and google

                ENV.facebook_app_id = '';
                ENV.google_app_id = '';
                ENV.twitter_app_id = '';
                ENV.slack_app = {};
                ENV.microsoft_app_id = '';
                if (window.location.host.indexOf('www.mobintouch.com') >= 0) {
                    ENV.facebook_app_id = '1827353147484296';
                    ENV.google_app_id = '597727911242-1ak46sccu22q60lu9t6nci7gamr589uj.apps.googleusercontent.com';
                    ENV.twitter_app_id = 'Cae4pVYCzQhhAfnKISeLUMN8g';
                    ENV.slack_app.app_id = '133972618656.135495307511';
                    ENV.slack_app.secret = 'bc4aace43fa6cae3b18c0a41e52f2f1d';
                    ENV.microsoft_app_id = '876d2938-f1b3-4072-a58c-51880c7d7550';
                } else if (window.location.host.indexOf('www-dev.mobintouch.com') >= 0) {
                    ENV.facebook_app_id = '1827353147484296';
                    ENV.google_app_id = '1005303139908-o84k8qobo3lubrl5vm2iqhto18hrs59m.apps.googleusercontent.com';
                    ENV.twitter_app_id = 'Cae4pVYCzQhhAfnKISeLUMN8g';
                    ENV.slack_app.app_id = '12786713204.136547630259';
                    ENV.slack_app.secret = '75b6c27a7e0d7e003708e4184cfc577d';
                    ENV.microsoft_app_id = '876d2938-f1b3-4072-a58c-51880c7d7550';
                } else {
                    ENV.facebook_app_id = '1540324049618062';
                    ENV.google_app_id = '229604075028-kibdkg6r1oec69ndgrc4hobl5albtcgr.apps.googleusercontent.com';
                    ENV.twitter_app_id = 'Cae4pVYCzQhhAfnKISeLUMN8g';
                    ENV.slack_app.app_id = '133972618656.135495307511';
                    ENV.slack_app.secret = 'bc4aace43fa6cae3b18c0a41e52f2f1d';
                    ENV.microsoft_app_id = 'd7d061a0-e5f6-41c8-946a-0d7ab83017ac';
                }

                hello.init({
                    facebook: ENV.facebook_app_id,
                    google: ENV.google_app_id,
                    twitter: ENV.twitter_app_id,
                    slack: ENV.slack_app,
                    windows: ENV.microsoft_app_id
                }, {});

                $facebookProvider.setAppId(ENV.facebook_app_id);
                $facebookProvider.setVersion("v2.7");
                $facebookProvider.setPermissions(['user_friends', 'email']);
                GoogleSigninProvider.init({
                    client_id: ENV.google_app_id,
                });

                // lazy controller, directive and service
                app.controller = $controllerProvider.register;
                app.directive = $compileProvider.directive;
                app.filter = $filterProvider.register;
                app.factory = $provide.factory;
                app.service = $provide.service;
                app.constant = $provide.constant;
                app.value = $provide.value;

                // var DefaultDescription = "Connect with people in the Mobile Advertising industry. Tell what kind of mobile traffic you sell or buy. Build and engage with your professional network.";
                var DefaultDescription = "Join and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job";
                /*var DefaultDescription = "",pathname = window.location.pathname;
                 if(pathname == '/' || pathname == ''){
                 //alert();
                 DefaultDescription = "=>2Connect with people in the Mobile Advertising industry. Tell what kind of mobile traffic you sell or buy. Build and engage with your professional network.";
                 }else if(pathname == '/offers/public/all'){
                 DefaultDescription = "==>: With StackOffers, find your new traffic sources in 3 easy steps: Post your mobile traffic offer, Receive inquiries, and Select the best traffic sources. It's totally free!...";
                 //alert();
                 }*/
                //	console.log($urlRouterProvider);
                $urlRouterProvider
                        .otherwise('/404');
                $stateProvider
                        .state('invites', {
                            url: '/invite/inviter_inpage?service'
                        })
                        .state('index', {
                            url: '/',
                            pageDescription: DefaultDescription,
                            controller: 'RouteController'
                        })
                        .state('stack', {
                            url: '',
                            templateUrl: 'tpl/offers/all-publicl-offers.html?v=' + ENV.latestUpdate,
                            //pageTitle: "Stack | Mobintouch" ,
                            //pageDescription: "Welcome to Mobintouch. Sign up now and Connect with people in the Mobile Advertising industry. Mobintouch, the Mobile Advertising Social Network.",
                            //isNoIndex: true,
                            // isError404: false,

                        })
                        .state('stack.publicOffer', {
                            url: '/offers/public/all',
                            templateUrl: 'tpl/offers/public-offer.html?v=' + ENV.latestUpdate,
                            pageTitle: "StackOffer - Mobile Traffic Offer",
                            pageDescription: "With StackOffers, find your new traffic sources in 3 easy steps: Post your mobile traffic offer, Receive inquiries, and Select the best traffic sources. It's totally free!...",
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                allpublicOffersResource: 'allpublicOffersResource',
                                allOffers: function (allpublicOffersResource, go) {
                                    return allpublicOffersResource.get().$promise
                                            .then(function (data) {
                                                // success handler
                                                return data;
                                            }, function (error) {

                                                if (error.status == 401)
                                                    go.SignIn();
                                                else
                                                    go.NotFound();
                                            });
                                }
                            },
                            //controller : 'OffersController'
                            controller: 'publicOffersController'
                        })
                        .state('stack.offerdetail', {
                            url: '/offer/:offerId',
                            templateUrl: 'tpl/forms/PagePublicOfferRepl.html?v=' + ENV.latestUpdate,
                            pageTitle: "Stack | Mobintouch",
                            pageDescription: "Welcome to Mobintouch. Sign up now and Connect with people in the Mobile Advertising industry. Mobintouch, the Mobile Advertising Social Network.",
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                allpublicOffersResource: 'allpublicOffersResource',
                                allOffers: function (allpublicOffersResource, go) {
                                    return allpublicOffersResource.get().$promise
                                            .then(function (data) {
                                                // success handler
                                                return data;
                                            }, function (error) {

                                                if (error.status == 401)
                                                    go.SignIn();
                                                else
                                                    go.NotFound();
                                            });
                                }
                            },
                            //controller : 'OffersController'
                            controller: 'publicOffersDetailController'
                        })
                        .state('app', {
                            abstract: true,
                            url: '',
                            templateUrl: 'tpl/app.html?v=' + ENV.latestUpdate,
                            resolve: {
                                versionResource: 'versionResource',
                                lastVersion: function (versionResource) {
                                    // Return a promise to make sure the customer is completely
                                    // resolved before the controller is instantiated
                                    return versionResource.get().$promise;
                                }
                            },
                            controller: 'ClearCacheController'
                        })
                        // pages
                        .state('app.page', {
                            url: '',
                            template: '<div ui-view class=""></div>'
                        })
                        // legal
                        .state('app.legal', {
                            url: '/legal',
                            template: '<div ui-view class="legal"></div>'
                        })
                        .state('app.page.profile', {
                            url: '/:firstname-:lastname/edit',
                            templateUrl: 'tpl/blocks_personal/user/page_profile.html?v=' + ENV.latestUpdate,
                            pageTitle: " - Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'ngImgCrop',
                                                files: ['cdn/js/modules/ngImgCrop/ng-img-crop.css',
                                                    'cdn/js/modules/ngImgCrop/ng-img-crop.js',
                                                    'cdn/js/modules/ngImgCrop/ctrl.js']
                                            },
                                            {
                                                name: 'checklist-model',
                                                files: [
                                                    'cdn/js/libs/angular/checklist-model.js'
                                                ]
                                            }
                                        ]);
                                    }],
                                myUserResource: 'myUserResource',
                                myUser: function (myUserResource) {
                                    return myUserResource.get().$promise;
                                }
                            },
                            controller: 'ProfileFormController'
                        })
                        /*.state('app.page.public', {
                         url: '/profile/:username',
                         templateUrl: 'tpl/page_profile_public.html'
                         })*/
                        .state("app.page.public", {
                            url: "/profile/:username",
                            templateUrl: 'tpl/blocks_public/user/page_profile.html?v=' + ENV.latestUpdate,
                            isNoIndex: false,
                            isError404: false,
                            resolve: {
                                publicUserResource: 'publicUserResource',
                                viewUser: function (publicUserResource, $stateParams, go) {

                                    // Return a promise to make sure the customer is completely
                                    // resolved before the controller is instantiated
                                    //return publicUserResource.get({username: $stateParams.username}).$promise;
                                    return publicUserResource.get({username: $stateParams.username}).$promise
                                            .then(function (data) {
                                                // success handler
                                                return data;
                                            }, function (error) {
                                                if (error.status == 404)
                                                    go.NotFound();
                                                // if(error.status==401) go.SignIn();
                                                // else go.NotFound();
                                            });

                                }

                            },
                            controller: 'ShowUserController'
                        })
//                        .state('app.page.edit/profile', {
//                            url: '/edit/profile',
//                            templateUrl: 'tpl/blocks_edition/user/page_profile_edit.html?v=' + ENV.latestUpdate,
//                            pageTitle: "Edit my profile | Mobintouch",
//                            pageDescription: DefaultDescription,
//                            isNoIndex: true,
//                            isError404: false,
//                            resolve: {
//                                deps: ['$ocLazyLoad',
//                                    function ($ocLazyLoad) {
//                                        return $ocLazyLoad.load(
//                                                [{
//                                                        name: 'ngImgCrop',
//                                                        files: ['cdn/js/modules/ngImgCrop/ng-img-crop.css',
//                                                            'cdn/js/modules/ngImgCrop/ng-img-crop.js',
//                                                            'cdn/js/modules/ngImgCrop/ctrl.js']
//                                                    },
//                                                    {
//                                                        name: 'checklist-model',
//                                                        files: [
//                                                            'cdn/js/libs/angular/checklist-model.js'
//                                                        ]
//                                                    }]
//                                                );
//                                    }],
//                                myUserResource: 'myUserResource',
//                                myUser: function (myUserResource) {
//
//                                    // Return a promise to make sure the customer is completely
//                                    // resolved before the controller is instantiated
//                                    return myUserResource.get().$promise;
//                                    /*myUserResource.get().$promise
//                                     .then(function(data) {
//                                     // success handler
//                                     return data;
//                                     }, function(error) {
//                                     if(error.status==401) go.SignIn();
//                                     else go.NotFound();
//                                     });*/
//                                }
//                            },
//                            controller: 'EditProfileFormController'
//                        })
//                        .state('app.page.company', {
//                            url: '/mycompany',
//                            templateUrl: 'tpl/page_company.html?v=' + ENV.latestUpdate,
//                            pageTitle: "My company page | Mobintouch",
//                            pageDescription: DefaultDescription,
//                            isNoIndex: true,
//                            isError404: false,
//                            resolve: {
//                                deps: ['$ocLazyLoad',
//                                    function ($ocLazyLoad) {
//                                        return $ocLazyLoad.load(
//                                                {
//                                                    name: 'ngImgCrop',
//                                                    files: ['cdn/js/modules/ngImgCrop/ng-img-crop.css',
//                                                        'cdn/js/modules/ngImgCrop/ng-img-crop.js',
//                                                        'cdn/js/modules/ngImgCrop/ctrl.js']
//                                                },
//                                                {
//                                                    name: 'infinite-scroll',
//                                                    files: ['cdn/js/modules/ng-infinite-scroll.min.js']
//                                                }
//                                        );
//                                    }],
//                                myCompanyResourceWithMutualConnections: 'myCompanyResourceWithMutualConnections',
//                                //goSignIn: 'goSignIn',
//                                myCompany: function (myCompanyResourceWithMutualConnections) {
//
//                                    // Return a promise to make sure the company is completely
//                                    // resolved before the controller is instantiated
//
//                                    return myCompanyResourceWithMutualConnections.employees.post().$promise;
//                                    /*
//                                     .then(function(data) {
//                                     // success handler
//                                     console.log("STATE PROVIDER");
//                                     console.log(data);
//                                     return data;
//                                     }, function(error) {
//                                     console.log(error);
//                                     if(error.status==401) go.SignIn();
//                                     else go.NotFound();
//                                     });*/
//
//                                }
//                            },
//                            controller: 'CompanyShowController'
//                        })
                        .state('app.page.company', {
                            url: '/my/company',
                            templateUrl: 'tpl/blocks_personal/company/page_company.html?v=' + ENV.latestUpdate,
                            pageTitle: "My company page | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: [
                                                    'cdn/js/modules/ng-infinite-scroll.min.js'
                                                ]
                                            },
                                            {
                                                name: 'ngImgCrop',
                                                files: [
                                                    'cdn/js/modules/ngImgCrop/ng-img-crop.css',
                                                    'cdn/js/modules/ngImgCrop/ng-img-crop.js',
                                                    'cdn/js/modules/ngImgCrop/ctrl.js'
                                                ]
                                            }
                                        ]);
                                    }],
                                myCompanyResourceWithMutualConnections: 'myCompanyResourceWithMutualConnections',
                                myCompany: function (myCompanyResourceWithMutualConnections, go) {
                                    return myCompanyResourceWithMutualConnections.employees.post().$promise.then(function (data) {
                                        return data;
                                    }, function (error) {
                                        if (error.status === 404)
                                            go.NotFound();
                                    });
                                }
                            },
                            controller: 'CompanyShowController'
                        })
                        .state('app.page.company/public', {
                            url: '/company/:companyusername',
                            //templateUrl: 'tpl/page_company_public.html?v=' + ENV.latestUpdate,
                            templateUrl: 'tpl/blocks_public/company/page_company.html?v=' + ENV.latestUpdate,
                            isNoIndex: false,
                            isError404: false,
                            resolve: {
                                publicCompanyResourceWithMutualConnections: 'publicCompanyResourceWithMutualConnections',
                                publicCompany: function (publicCompanyResourceWithMutualConnections, $stateParams, go) {
                                    return publicCompanyResourceWithMutualConnections.employees.get({companyusername: $stateParams.companyusername}).$promise
                                            .then(function (data) {
                                                return data;
                                            }, function (error) {
                                                if (error.status === 404)
                                                    go.NotFound();
                                            });
                                },
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: ['cdn/js/modules/ng-infinite-scroll.min.js']
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'CompanyPublicShowController'
                        })
                        .state('app.page.createCompany', {
                            url: '/create/company',
                            templateUrl: 'tpl/page_create_company.html?v=' + ENV.latestUpdate,
                            pageTitle: "Create my company page | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.createCompany2', {
                            url: '/create/company/:companyusername',
                            templateUrl: 'tpl/page_create_company2.html?v=' + ENV.latestUpdate,
                            pageTitle: "Create my company page | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            //controller : 'CompanyCreateFormController',
                            controller: 'SearchCompanyCreateFormController'
                        })
                        .state('app.page.edit/company', {
                            url: '/edit/company',
                            templateUrl: 'tpl/page_company_edit.html?v=' + ENV.latestUpdate,
                            pageTitle: "Edit my company page | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load(
                                                {
                                                    name: 'ngImgCrop',
                                                    files: ['cdn/js/modules/ngImgCrop/ng-img-crop.css',
                                                        'cdn/js/modules/ngImgCrop/ng-img-crop.js',
                                                        'cdn/js/modules/ngImgCrop/ctrl.js']
                                                }
                                        );
                                    }]
                            }
                        })
                     /*   .state('app.page.search', {
                            url: '/search',
                            templateUrl: 'tpl/page_search.html?v=' + ENV.latestUpdate,
                            pageTitle: "Explore | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                myUserResource: 'myUserResource',
                                myUser: function (myUserResource, go) {

                                    // Return a promise to make sure the customer is completely
                                    // resolved before the controller is instantiated
                                    //return myUserResource.get().$promise;
                                    return myUserResource.get().$promise
                                            .then(function (data) {
                                                // success handler
                                                return data;
                                            }, function (error) {
                                                if (error.status == 401)
                                                    return null;
                                                else
                                                    go.NotFound();
                                            });
                                },
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: ['cdn/js/modules/ng-infinite-scroll.min.js']
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'SearchFormController'
                        })
                        .state('app.page.simpleSearch', {
                            url: '/search/:query',
                            templateUrl: 'tpl/page_search.html'
                        })*/
                        .state('app.page.publicsearch', {
                            url: '/search?t=:type&q=:query',
                            templateUrl: 'tpl/search/search.html?v=' + ENV.latestUpdate,
                            pageTitle: "Search All - Mobintouch",
                            pageDescription: "Search Mobintouch's database of thousands people and companies. Filter by location, team, and more.",
                            isNoIndex: false,
                            isError404: false,
                            params: {
                                query: {squash: true, value: null}
                            },
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: [
                                                    'cdn/js/modules/ng-infinite-scroll.min.js'
                                                ]
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'publicSearchController'
                        })
                        .state('app.page.product', {
                            url: '/product',
                            templateUrl: 'tpl/page_product_list.html?v=' + ENV.latestUpdate,
                            pageTitle: "Product | Mobintouch",
                            pageDescription: 'Discover great tools and services for Mobile Advertising industry',
                            isNoIndex: false,
                            isError404: false
                        })
                        .state('app.page.product/appannie', {
                            url: '/product/appannie',
                            templateUrl: 'tpl/products/page_product_appannie.html?v=' + ENV.latestUpdate,
                            pageTitle: "App Annie | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.product/appsflyer', {
                            url: '/product/appsflyer',
                            templateUrl: 'tpl/products/page_product_appsflyer.html?v=' + ENV.latestUpdate,
                            pageTitle: "AppsFlyer | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.product/emma', {
                            url: '/product/emma',
                            templateUrl: 'tpl/products/page_product_emma.html?v=' + ENV.latestUpdate,
                            pageTitle: "Emma | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state("app.page.public/event", {
                            url: "/event/:eventname/attendees",
                            templateUrl: 'tpl/page_event_attendees.html?v=' + ENV.latestUpdate,
                            isNoIndex: false,
                            isError404: false,
                            resolve: {
                                eventResource: 'eventResource',
                                myevent: function (eventResource, $stateParams, go) {

                                    return eventResource.get({eventname: $stateParams.eventname}).$promise
                                            .then(function (data) {
                                                // success handler
                                                return data;
                                            }, function (error) {
                                                if (error.status == 404)
                                                    go.NotFound();
                                            });

                                }

                            },
                            controller: 'EventAttendeesController'
                        })
                        .state('app.page.invoice', {
                            url: '/invoice',
                            templateUrl: 'tpl/page_invoice.html?v=' + ENV.latestUpdate,
                            pageTitle: "Invoice | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        /*.state('app.page.plans', {
                         url: '/plans',
                         templateUrl: 'tpl/page_plans.html'
                         })
                         .state('app.page.plans', {
                         url: '/plans',
                         templateUrl: 'tpl/page_plans_coming_soon.html?v=0.5.0',
                         pageTitle: "Plans",
                         pageDescription: DefaultDescription
                         })*/
                        .state('app.page.feed', {
                            url: '/',
                            templateUrl: 'tpl/feed/page_feed.html?v=' + ENV.latestUpdate,
                            pageTitle: "Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: false,
                            isError404: false,
                            resolve: {
                                myUserResource: 'myUserResource',
                                myUser: function (myUserResource) {

                                    // Return a promise to make sure the customer is completely
                                    // resolved before the controller is instantiated
                                    return myUserResource.get().$promise;
                                    /*myUserResource.get().$promise
                                     .then(function(data) {
                                     // success handler
                                     return data;
                                     }, function(error) {
                                     if(error.status==401) go.SignIn();
                                     else go.NotFound();
                                     });*/
                                },
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: ['cdn/js/modules/ng-infinite-scroll.min.js']
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'FeedCtrl'
                        })
                        .state('app.page.favorites', {
                            url: '/favorites',
                            templateUrl: 'tpl/page_favorites.html?v=' + ENV.latestUpdate,
                            pageTitle: "Favorites | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.visitors', {
                            url: '/profile-visitors',
                            templateUrl: 'tpl/page_who_visited_my_profile.html?v=' + ENV.latestUpdate,
                            pageTitle: "Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                whoVisistedMe: 'whoVisistedMe',
                                myUser: function (whoVisistedMe) {
                                    return whoVisistedMe.get().$promise;
                                }
                            },
//                            resolve: {
//                                myUserResource: 'myUserResource',
//                                myUser: function (myUserResource, go) {
//                                    // Return a promise to make sure the customer is completely
//                                    // resolved before the controller is instantiated
//                                    return myUserResource.get().$promise;
//                                            /*.then(function (data) {
//                                                console.log(data);
//                                                return data;
//                                            }, function (error) {
//                                                if (error.status == 401)
//                                                    go.SignIn();
//                                                else
//                                                    go.NotFound();
//                                            });*/
//                                }
//                            },
                            controller: 'WhoVisitedController'
                        })
                        .state('app.page.history', {
                            url: '/profile-viewed',
                            templateUrl: 'tpl/page_last_visited_profiles.html?v=' + ENV.latestUpdate,
                            pageTitle: "Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                lastVisistedProfiles: 'lastVisistedProfiles',
                                myUser: function (lastVisistedProfiles) {
                                    return lastVisistedProfiles.get().$promise;
                                }
                            },
//                            resolve: {
//                                myUserResourceWithMutualConnections: 'myUserResourceWithMutualConnections',
//                                myUser: function (myUserResourceWithMutualConnections) {
//
//                                    // Return a promise to make sure the customer is completely
//                                    // resolved before the controller is instantiated
//                                    return myUserResourceWithMutualConnections.iVisited.get().$promise;
//                                    /*myUserResource.get().$promise
//                                     .then(function(data) {
//                                     // success handler
//                                     return data;
//                                     }, function(error) {
//                                     if(error.status==401) go.SignIn();
//                                     else go.NotFound();
//                                     });*/
//                                }
//                            },
                            controller: 'LastVisitsController'
                        })
                        .state('app.page.followers', {
                            url: '/followers',
                            templateUrl: 'tpl/page_followers.html?v=' + ENV.latestUpdate,
                            pageTitle: "Followers | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                myCompanyResourceWithMutualConnections: 'myCompanyResourceWithMutualConnections',
                                //goSignIn: 'goSignIn',
                                myCompany: function (myCompanyResourceWithMutualConnections) {

                                    // Return a promise to make sure the company is completely
                                    // resolved before the controller is instantiated

                                    return myCompanyResourceWithMutualConnections.followers.post().$promise;
                                }
                            },
                            controller: 'ListFollowersController'
                        })
                        .state('app.page.following', {
                            url: '/following',
                            templateUrl: 'tpl/page_following.html?v=' + ENV.latestUpdate,
                            pageTitle: "Following | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.addconnection', {
                            url: '/add-connection',
                            templateUrl: 'tpl/page_connections.html?v=' + ENV.latestUpdate,
                            pageTitle: "connections | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                myUserResourceWithMutualConnections: 'myUserResourceWithMutualConnections',
                                myUser: function (myUserResourceWithMutualConnections) {

                                    // Return a promise to make sure the customer is completely
                                    // resolved before the controller is instantiated
                                    return myUserResourceWithMutualConnections.inTouch.get().$promise;
                                    /*myUserResource.get().$promise
                                     .then(function(data) {
                                     // success handler
                                     return data;
                                     }, function(error) {
                                     if(error.status==401) go.SignIn();
                                     else go.NotFound();
                                     });*/
                                }
                            },
                            controller: 'ConnectionController'
                        })
                        .state('app.page.connections', {
                            url: '/connections/people/:type',
                            templateUrl: 'tpl/connections/connections.html?v=' + ENV.latestUpdate,
                            pageTitle: "Your network - Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            params: {
                                type: {squash: true, value: null}
                            },
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: ['cdn/js/modules/ng-infinite-scroll.min.js']
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'ConnectionsController'
                        })
                        .state('app.page.peoples', {
                            url: '/peoples/:type?{l:json}',
                            templateUrl: 'tpl/connections/connections.html?v=' + ENV.latestUpdate,
                            pageTitle: "Peoples | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            params: {
                                type: {squash: true, value: null}
                            },
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: ['cdn/js/modules/ng-infinite-scroll.min.js']
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'ConnectionsController'
                        })
                        .state("app.page.dirpeoples", {
                            url: "/people/directory/:startwith/:skip",
                            templateUrl: 'tpl/directory/peoples.html?v=' + ENV.latestUpdate,
                            pageTitle: "People Directory a - Mobintouch",
                            pageDescription: "People Directory: a - Mobintouch",
                            isNoIndex: false,
                            isError404: false,
                            params: {
                                skip: {squash: true, value: null}
                            },
                            resolve: {
                                peopleDirectoryResource: 'peopleDirectoryResource',
                                peopleDirectory: function (peopleDirectoryResource, $stateParams) {
                                    return peopleDirectoryResource.get({startwith: $stateParams.startwith, skip: $stateParams.skip}).$promise;
                                }
                            },
                            controller: 'peopleDirectoryController'
                        })
                        .state('app.page.peopleyoumayknow', {
                            url: '/:firstname-:lastname/people-you-may-know',
                            templateUrl: 'tpl/connections/page_connection_suggestions.html?v=' + ENV.latestUpdate,
                            pageTitle: "People you may know - Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                connectionSuggestions: 'connectionSuggestions',
                                connectionSuggestion: function (connectionSuggestions, $stateParams) {
                                    return connectionSuggestions.get().$promise;
                                }
                            },
                            controller: 'PeopleSuggestionsController'
                        })
                        .state('app.page.addconnections', {
                            url: '/people/add',
                            templateUrl: 'tpl/connections/page_import_connection.html?v=' + ENV.latestUpdate,
                            pageTitle: "Your network - Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.manageconnections', {
                            url: '/connections/people/manage/:type',
                            templateUrl: 'tpl/connections/manage_connections.html?v=' + ENV.latestUpdate,
                            pageTitle: "Your network - Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            params: {
                                type: {squash: true, value: null}
                            },
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: ['cdn/js/modules/ng-infinite-scroll.min.js']
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'ConnectionsController'
                        })
                        .state('app.page.importlinkedinconnections', {
                            url: '/import-linkedin-connections',
                            templateUrl: 'tpl/connections/page_linkedin_file_upload.html?v=' + ENV.latestUpdate,
                            pageTitle: "Import your linkedin connections | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                myUserResource: 'myUserResource',
                                myUser: function (myUserResource, go) {
                                    return myUserResource.get().$promise
                                            .then(function (data) {
                                                return data;
                                            }, function (error) {
                                                if (error.status == 401)
                                                    go.SignIn();
                                                else
                                                    go.NotFound();
                                            });
                                }
                            }
                        })
                        .state('app.page.inviteconnections', {
                            url: '/invite-connections/:service',
                            templateUrl: 'tpl/connections/page_invite_connection.html?v=' + ENV.latestUpdate,
                            pageTitle: "Import Connections | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                contactResource: 'contactResource',
                                contact: function (contactResource, $state, $stateParams) {
                                    return contactResource.get({'service': $stateParams.service}).$promise
                                            .then(function (data) {
                                                if (data.contacts.length <= 0) {
                                                    $state.go('app.page.addconnections');
                                                } else {
                                                    return data.contacts;
                                                }
                                            });
                                }
                            },
                            controller: 'InviteConnectionsController'
                        })
                        .state('app.page.jobs', {
                            url: '/jobs/:type',
                            templateProvider: ['$cookies', '$templateRequest',
                                function ($cookies, templateRequest)
                                {
                                    var loggedin = localStorage.getItem('id_token') != null || ($cookies.id_token != 'null' && !angular.isUndefined($cookies.id_token));
                                    if (loggedin) {
                                        return templateRequest('tpl/job/jobs_member.html?v=' + ENV.latestUpdate);
                                    } else {
                                        return templateRequest('tpl/job/jobs_visitor.html?v=' + ENV.latestUpdate);
                                    }
                                }
                            ],
                            //templateUrl: 'tpl/job/jobs.html?v=' + ENV.latestUpdate,
                            pageTitle: "Mobile Startup Jobs - Mobintouch",
                            pageDescription: "See all our mobile startup jobs. Get salary and equity. Apply privately.",
                            isNoIndex: false,
                            isError404: false,
                            params: {
                                type: {squash: true, value: null}
                            },
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: [
                                                    'cdn/js/modules/ng-infinite-scroll.min.js'
                                                ]
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'JobsController'
                        })
                        .state('app.recruits', {
                            abstract: true,
                            url: '/recruit',
                            templateUrl: 'tpl/recruits/recruits.html?v=' + ENV.latestUpdate,
                        })
                        .state('access.recruit', {
                            url: '/recruit',
                            templateUrl: 'tpl/recruits/recruit_visitor.html?v=' + ENV.latestUpdate,
                            pageTitle: "Recruit - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            resolve: {
                                randomProfileResource: 'randomProfileResource',
                                profiles: function (randomProfileResource) {
                                    return randomProfileResource.get().$promise
                                            .then(function (data) {
                                                return data.profile;
                                            });
                                }
                            },
                            controller: function ($rootScope, $scope, profiles,$location) {
                                $rootScope.updateActiveLink('recruit');
                                $scope.profiles = profiles;
                                $rootScope.ogUrl = $location.absUrl();
                            }
                        })
                        .state('jobShowcase', {
                            url: '/company/:company/jobs/showcase/:slug',
                            isNoIndex: false,
                            templateProvider: ['$stateParams', '$templateRequest',
                                function ($stateParams, templateRequest)
                                {
                                    if ($stateParams.slug) {
                                        return templateRequest('tpl/job/job_showcase.html?v=' + ENV.latestUpdate);
                                    } else {
                                        return templateRequest('tpl/company/jobs_showcase.html?v=' + ENV.latestUpdate);
                                    }
                                }
                            ],
                            //templateUrl: 'tpl/recruits/job-showcase.html?v=' + ENV.latestUpdate,
                            params: {
                                slug: {squash: true, value: null}
                            },
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'ngImgCrop',
                                                files: ['cdn/js/modules/ngImgCrop/ng-img-crop.css',
                                                    'cdn/js/modules/ngImgCrop/ng-img-crop.js',
                                                    'cdn/js/modules/ngImgCrop/ctrl.js']
                                            }
                                        ]);
                                    }],
                                publicCompanyResource: 'publicCompanyResource',
                                //goSignIn: 'goSignIn',
                                myCompany: function (publicCompanyResource, $stateParams, go) {
                                    return publicCompanyResource.get({companyusername: $stateParams.company}).$promise.then(function (data) {
                                        return data;
                                    }, function (error) {
                                        if (error.status === 404)
                                            go.NotFound();
                                    });
                                }
                            },
                            controller: 'JobShowcaseController'
                        })
                        .state('app.recruits.manage', {
                            url: '/jobs/manage',
                            templateUrl: 'tpl/recruits/manage.html?v=' + ENV.latestUpdate,
                            pageTitle: "Recruit - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: ['cdn/js/modules/ng-infinite-scroll.min.js']
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'RecruitsController'
                        })
                        .state('app.page.addjobtutorial', {
                            url: '/onboarding',
                            templateUrl: 'tpl/recruits/create-job-tutorial.html?v=' + ENV.latestUpdate,
                            pageTitle: "Recruit - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: false,
                            isError404: false,
                            controller: 'JobPostTutorialController'
                        })
                        .state('app.recruits.addjob', {
                            url: '/jobs/post',
                            templateUrl: 'tpl/recruits/create-job.html?v=' + ENV.latestUpdate,
                            pageTitle: "Post job - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: true,
                            isError404: false,
                            controller: 'JobController'
                        })
                        .state('app.recruits.editjob', {
                            url: '/jobs/edit/:slug',
                            templateUrl: 'tpl/recruits/edit-job.html?v=' + ENV.latestUpdate,
                            pageTitle: "Edit job - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: true,
                            isError404: false,
                            controller: 'JobController'
                        })
                        .state('app.recruits.applicants', {
                            url: '/jobs/applicants',
                            templateUrl: 'tpl/recruits/applicants.html?v=' + ENV.latestUpdate,
                            pageTitle: "Recruit - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: ['cdn/js/modules/ng-infinite-scroll.min.js']
                                            }
                                        ]);
                                    }],
                            },
                            controller: 'RecruitsController'
                        })
                        .state('app.recruits.jobshowcase', {
                            url: '/job-showcase',
                            templateUrl: 'tpl/recruits/job-showcase.html?v=' + ENV.latestUpdate,
                            pageTitle: "Job Showcase | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            controller: 'JobShowcaseController'
                        })
                        .state('app.page.companies', {
                            url: '/companies/:type?q&m&t&l',
                            templateUrl: 'tpl/company/companies.html?v=' + ENV.latestUpdate,
                            pageTitle: "Mobile Startups Database - Mobintouch",
                            pageDescription: "Search Mobintouch's database of thousands companies. Filter by location, team, and more.",
                            isNoIndex: false,
                            isError404: false,
                            params: {
                                type: {squash: true, value: null}
                            },
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: [
                                                    'cdn/js/modules/ng-infinite-scroll.min.js'
                                                ]
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'CompaniesController'
                        })
                        .state("app.page.dircompanies", {
                            url: "/companies/directory/:startwith/:skip",
                            templateUrl: 'tpl/directory/companies.html?v=' + ENV.latestUpdate,
                            pageTitle: "Companies Directory ",
                            pageDescription: "Companies Directory: ",
                            isNoIndex: false,
                            isError404: false,
                            params: {
                                skip: {squash: true, value: null}
                            },
                            resolve: {
                                companyDirectoryResource: 'companyDirectoryResource',
                                companyDirectory: function (companyDirectoryResource, $stateParams) {
                                    return companyDirectoryResource.get({startwith: $stateParams.startwith, skip: $stateParams.skip}).$promise;
                                }
                            },
                            controller: 'companyDirectoryController'
                        })
                        .state('app.page.qa', {
                            url: '/qa/:type',
                            templateUrl: 'tpl/q_and_a/question_answers.html?v=' + ENV.latestUpdate,
                            pageTitle: "Questions - Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: false,
                            isError404: false,
                            params: {
                                type: {squash: true, value: null}
                            },
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: [
                                                    'cdn/js/modules/ng-infinite-scroll.min.js'
                                                ]
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'QAController'
                        })
                        .state('app.page.myqa', {
                            url: '/qa/my/:type',
                            templateUrl: 'tpl/q_and_a/question_answers.html?v=' + ENV.latestUpdate,
                            pageTitle: "Questions - Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: false,
                            isError404: false,
                            params: {
                                type: {squash: true, value: null}
                            },
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: [
                                                    'cdn/js/modules/ng-infinite-scroll.min.js'
                                                ]
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'QAController'
                        })
                        .state('app.page.svqa', {
                            url: '/qa/:type/questions',
                            templateUrl: 'tpl/q_and_a/question_answers.html?v=' + ENV.latestUpdate,
                            pageTitle: "Questions - Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: false,
                            isError404: false,
                            params: {
                                type: {squash: true, value: null}
                            },
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: [
                                                    'cdn/js/modules/ng-infinite-scroll.min.js'
                                                ]
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'QAController'
                        })
                        .state('app.page.askquestion', {
                            url: '/qa/question/ask',
                            templateUrl: 'tpl/q_and_a/ask_question.html?v=' + ENV.latestUpdate,
                            pageTitle: "Ask a question - Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: false,
                            isError404: false,
                            resolve: {
                                questionStatsResource: 'questionStatsResource',
                                questionStats: function (questionStatsResource, go) {
                                    return questionStatsResource.get().$promise
                                            .then(function (data) {
                                                return data;
                                            }, function (error) {
                                                if (error.status == 404)
                                                    go.NotFound();
                                            });
                                }
                            },
                            controller: 'AskQuestionController'
                        })
                        .state('app.page.editquestion', {
                            url: '/edit-question/:slug',
                            templateUrl: 'tpl/q_and_a/edit_question.html?v=' + ENV.latestUpdate,
                            pageTitle: "Edit your question | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                questionDetailsResource: 'questionDetailsResource',
                                questionDetails: function ($stateParams, questionDetailsResource, go) {
                                    return questionDetailsResource.get({slug: $stateParams.slug}).$promise
                                            .then(function (data) {
                                                return data;
                                            }, function (error) {
                                                if (error.status == 404)
                                                    go.NotFound();
                                            });
                                }
                            },
                            controller: 'EditQuestionController'
                        })
                        .state('app.page.qdetails', {
                            url: '/question/:slug',
                            templateUrl: 'tpl/q_and_a/question_details.html?v=' + ENV.latestUpdate,
                            pageTitle: "Question details | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: false,
                            isError404: false,
                            resolve: {
                                questionDetailsResource: 'questionDetailsResource',
                                questionDetails: function ($stateParams, $rootScope, questionDetailsResource, go) {
                                    return questionDetailsResource.get({slug: $stateParams.slug}).$promise
                                            .then(function (data) {
                                                return data;
                                            }, function (error) {
                                                if (error.status == 404)
                                                    go.NotFound();
                                            });
                                },
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: [
                                                    'cdn/js/modules/ng-infinite-scroll.min.js'
                                                ]
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'QDetailsController'
                        })
                        .state('app.page.qtag', {
                            url: '/qa/tag/:tag',
                            templateUrl: 'tpl/q_and_a/questions_tag.html?v=' + ENV.latestUpdate,
                            pageTitle: "Question tag | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: false,
                            isError404: false,
                            resolve: {
                                questionTagResource: 'questionTagResource',
                                questionTag: function ($stateParams, questionTagResource, go) {
                                    var tag = $stateParams.tag.replace(/\-+/g, ' ');
                                    return questionTagResource.get({limit: 20, offset: 0, tag: tag}).$promise
                                            .then(function (data) {
                                                return data;
                                            }, function (error) {
                                                if (error.status === 404)
                                                    go.NotFound();
                                            });
                                },
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'infinite-scroll',
                                                files: [
                                                    'cdn/js/modules/ng-infinite-scroll.min.js'
                                                ]
                                            }
                                        ]);
                                    }]
                            },
                            controller: 'QTagsController'
                        })
                        .state('app.page.notifications', {
                            url: '/notifications',
                            templateUrl: 'tpl/page_notifications.html'
                        })
                        .state('app.page.purchase', {
                            url: '/purchase',
                            templateUrl: 'tpl/page_purchase.html?v=' + ENV.latestUpdate,
                            pageTitle: "Purchase | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.thanks/purchase', {
                            url: '/thanks-purchase',
                            templateUrl: 'tpl/page_premium_thanks.html?v=' + ENV.latestUpdate,
                            pageTitle: "Thank you | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.premium', {
                            url: '/premium',
                            templateUrl: 'tpl/page_premium.html?v=' + ENV.latestUpdate,
                            pageTitle: "Premium | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.premium/edit', {
                            url: '/edit-premium',
                            templateUrl: 'tpl/page_premium_edit.html?v=' + ENV.latestUpdate,
                            pageTitle: "Premium | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.settings', {
                            url: '/settings',
                            templateUrl: 'tpl/blocks_edition/settings_personal.html?v=' + ENV.latestUpdate,
                            pageTitle: "Settings - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: true,
                            isError404: false,
                            controller: 'SettingsController' 
                        })
                        .state('app.page.settings_password', {
                            url: '/settings/password',
                            templateUrl: 'tpl/blocks_edition/settings_password.html?v=' + ENV.latestUpdate,
                            pageTitle: "Settings - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.settings_notifications', {
                            url: '/settings/notifications',
                            templateUrl: 'tpl/blocks_edition/settings_notifications.html?v=' + ENV.latestUpdate,
                            pageTitle: "Settings - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                settingNotificationResource: 'settingNotificationResource',
                                settingNotification: function (settingNotificationResource) {
                                    return settingNotificationResource.get().$promise
                                            .then(function (data) {
                                                return data;
                                            });
                                }
                            },
                            controller: function (settingNotification, $scope, userService,$location) {
                                $scope.user.settings.notifications = settingNotification.notifications;
                                $scope.user.settings.privacy = settingNotification.privacy;
                                userService.update($scope.user);
                                $rootScope.ogUrl = $location.absUrl();
                            }
                        })
                        .state('app.page.settings_privacy', {
                            url: '/settings/privacy',
                            templateUrl: 'tpl/blocks_edition/settings_privacy.html?v=' + ENV.latestUpdate,
                            pageTitle: "Settings - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: false,
                            isError404: false
                        })
                        .state('app.page.settings_billing', {
                            url: '/settings/billing',
                            templateUrl: 'tpl/blocks_edition/settings_billing.html?v=' + ENV.latestUpdate,
                            pageTitle: "Settings | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.settings_company', {
                            url: '/settings/company',
                            templateUrl: 'tpl/blocks_edition/settings_company.html?v=' + ENV.latestUpdate,
                            pageTitle: "Settings | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.settings_invoice', {
                            url: '/settings/invoice',
                            templateUrl: 'tpl/blocks_edition/settings_invoice.html?v=' + ENV.latestUpdate,
                            pageTitle: "Settings | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.page.about', {
                            url: '/about',
                            templateUrl: 'tpl/page_about.html?v=' + ENV.latestUpdate,
                            pageTitle: "About - Mobintouch",
                            pageDescription: "About us. Welcome to Mobintouch, a community of Mobile Apps & Games creators",
                            isNoIndex: false,
                            isError404: false
                        })
                        .state('app.legal.privacy', {
                            url: '/privacy-policy',
                            templateUrl: 'tpl/page_privacy.html?v=' + ENV.latestUpdate,
                            pageTitle: "Privacy Policy - Mobintouch",
                            pageDescription: "This Privacy Policy applies to Mobintouch.com, Mobintouch-branded apps and other Mobintouch-related sites, apps and communications",
                            isNoIndex: false,
                            isError404: false
                        })
                        .state('app.legal.cookies', {
                            url: '/cookie-policy',
                            templateUrl: 'tpl/page_cookies.html?v=' + ENV.latestUpdate,
                            pageTitle: "Cookie Policy - Mobintouch",
                            pageDescription: "This policy provides detailed information about how and when we use cookies. This cookie policy applies to any Mobintouch product.",
                            isNoIndex: false,
                            isError404: false
                        })
                        .state('app.legal.user_agreement', {
                            url: '/user-agreement',
                            templateUrl: 'tpl/page_user_agreement.html?v=' + ENV.latestUpdate,
                            pageTitle: "User Agreement - Mobintouch",
                            pageDescription: "If you do not agree to this contract (“Contract” or “User Agreement”), do not click “Sign up” (or similar) and do not access or otherwise use any of our Services.",
                            isNoIndex: false,
                            isError404: false
                        })
                        .state('app.page.invite', {
                            url: '/invite-contacts',
                            templateUrl: 'tpl/page_invite_contacts.html?v=' + ENV.latestUpdate,
                            pageTitle: "Invite | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            controller: 'LinkedInConnectionsController'
                        })
                        .state('app.offers', {
                            abstract: true,
                            url: '/offers',
                            templateUrl: 'tpl/offers.html?v=' + ENV.latestUpdate,
                            //pageTitle: "Offers | Mobintouch" ,
                            //pageDescription: DefaultDescription,
                            //isNoIndex: true,
                            //isError404: false
                            resolve: {
                                myUserResource: 'myUserResource',
                                myUser: function (myUserResource, go) {

                                    // Return a promise to make sure the customer is completely
                                    // resolved before the controller is instantiated
                                    return myUserResource.get().$promise
                                            .then(function (data) {
                                                // success handler
                                                return data;
                                            }, function (error) {
                                                if (error.status == 401)
                                                    go.SignIn();
                                                else
                                                    go.NotFound();
                                            });
                                }
                            },
                            controller: 'GeneralOffersController'
                        })
                        .state('app.offers.all', {
                            url: '/all',
                            templateUrl: 'tpl/offers/all-offers.html?v=' + ENV.latestUpdate,
                            pageTitle: "Offers | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                reset: function (resetOffersNotificationsResource, go) {
                                    resetOffersNotificationsResource.get();
                                },
                                publicOffersResource: 'publicOffersResource',
                                allOffers: function (publicOffersResource, go) {
                                    return publicOffersResource.get().$promise
                                            .then(function (data) {
                                                // success handler
                                                return data;
                                            }, function (error) {
                                                if (error.status == 401)
                                                    go.SignIn();
                                                else
                                                    go.NotFound();
                                            });
                                }
                            },
                            controller: 'OffersController'
                        })

                        .state('offers', {
                            url: '/offers/public',
                            templateUrl: 'tpl/offers/public-offers.html?v=' + ENV.latestUpdate,
                            pageTitle: "Offers | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            controller: 'OffersController'
                        })
                        .state('app.offers.myoffers', {
                            url: '/myoffers',
                            templateUrl: 'tpl/offers/my-offers.html?v=' + ENV.latestUpdate,
                            pageTitle: "Offers | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                myOffersResource: 'myOffersResource',
                                allOffers: function (myOffersResource, go) {
                                    return myOffersResource.get().$promise
                                            .then(function (data) {
                                                // success handler
                                                return data;
                                            }, function (error) {
                                                if (error.status == 401)
                                                    go.SignIn();
                                                else
                                                    go.NotFound();
                                            });
                                }
                            },
                            controller: 'OffersController'
                        })
                        .state('app.offers.myreplies', {
                            url: '/myreplies',
                            templateUrl: 'tpl/offers/my-replies.html?v=' + ENV.latestUpdate,
                            pageTitle: "Offers | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                myRepliesResource: 'myRepliesResource',
                                allOffers: function (myRepliesResource, go) {
                                    return myRepliesResource.get().$promise
                                            .then(function (data) {
                                                // success handler
                                                return data;
                                            }, function (error) {
                                                if (error.status == 401)
                                                    go.SignIn();
                                                else
                                                    go.NotFound();
                                            });
                                }
                            },
                            controller: 'OffersController'
                        })
                        .state('app.offers.details', {
                            url: '/details/:offerid',
                            templateUrl: 'tpl/offers/details.html?v=' + ENV.latestUpdate,
                            pageTitle: "Offer id | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                offerDetailsResource: 'offerDetailsResource',
                                details: function (offerDetailsResource, $stateParams, go) {
                                    return offerDetailsResource.get({offerid: $stateParams.offerid}).$promise
                                            .then(function (data) {
                                                // success handler
                                                return data;
                                            }, function (error) {
                                                if (error.status == 404)
                                                    go.NotFound();
                                                if (error.status == 403)
                                                    go.NotFound();
                                            });
                                }
                            },
                            controller: 'OfferDetailsController'
                        })
                        .state('access', {
                            url: '',
                            template: '<div ui-view class=""></div>'
                        })
                        /*.state('access.linkedin', {
                         url: '/linkedin/auth',
                         templateUrl: 'tpl/page_linkedin_auth.html?v=0.5.0',
                         pageTitle: "Mobintouch | Mobile Advertising Social Network",
                         pageDescription: DefaultDescription,
                         isNoIndex: true,
                         isError404: false,
                         resolve: {
                         linkedinResource: 'linkedinResource',
                         linkedinauth: function(linkedinResource){
                         
                         // Return a promise to make sure the customer is completely
                         // resolved before the controller is instantiated
                         return linkedinResource.post().$promise;
                         }
                         },
                         controller : 'LinkedInAuthController'
                         })*/
                        .state('access.landing', {
                            url: '',
                            templateUrl: 'tpl/page_home.html?v=' + ENV.latestUpdate,
                            pageTitle: "Mobintouch ",
                            pageDescription: "Sign in and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: false,
                            isError404: false
                        })
                        .state('access.stackoffer', {
                            url: '',
                            templateUrl: 'tpl/page_landing.html?v=' + ENV.latestUpdate,
                            pageTitle: "Mobintouch ",
                            pageDescription: "Sign in and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: false,
                            isError404: false
                        })
                        .state('access.signin', {
                            url: '/signin',
                            templateUrl: 'tpl/page_signin.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign in - Mobintouch",
                            pageDescription: "Sign in and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: false,
                            isError404: false
                        })
                        .state('access.signup', {
                            url: '/signup',
                            templateUrl: 'tpl/signup/page_signup_step_1.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign up - Mobintouch",
                            pageDescription: "Sign up and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: false,
                            isError404: false,
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load([
                                            {
                                                name: 'google-signin',
                                                files: ['cdn/js/modules/ng-google-signin.js']
                                            },
                                            {
                                                name: 'ngFacebook',
                                                files: ['cdn/js/modules/ngFacebook.js']
                                            }
                                        ]);
                                    }]
                            }

                        })
                        .state('access.step2', {
                            url: '/step1-7', //personalinfos
                            templateUrl: 'tpl/signup/page_signup_step_2.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign up - Mobintouch",
                            pageDescription: "Sign up and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.step3', {
                            url: '/step2-7', //import-linkedin-contacts
                            templateUrl: 'tpl/signup/page_signup_step_3.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign up - Mobintouch",
                            pageDescription: "Sign up and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.step4', {
                            url: '/step3-7', //uploadprofilephoto
                            templateUrl: 'tpl/signup/page_signup_step_4.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign up - Mobintouch",
                            pageDescription: "Sign up and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                deps: ['$ocLazyLoad',
                                    function ($ocLazyLoad) {
                                        return $ocLazyLoad.load(
                                                {
                                                    name: 'ngImgCrop',
                                                    files: ['cdn/js/modules/ngImgCrop/ng-img-crop.css',
                                                        'cdn/js/modules/ngImgCrop/ng-img-crop.js',
                                                        'cdn/js/modules/ngImgCrop/ctrl.js']
                                                }
                                        );
                                    }]
                            }
                        })
                        .state('access.step5', {
                            url: '/step4-7', //employmentinfos
                            templateUrl: 'tpl/signup/page_signup_step_5.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign up - Mobintouch",
                            pageDescription: "Sign up and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.step6', {
                            url: '/step5-7', //interestedin
                            templateUrl: 'tpl/signup/page_signup_step_6.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign up - Mobintouch",
                            pageDescription: "Sign up and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.step7', {
                            url: '/step6-7', //addinterests
                            templateUrl: 'tpl/signup/page_signup_step_7.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign up - Mobintouch",
                            pageDescription: "Sign up and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.step8', {
                            url: '/step7-7', //importcontacts
                            templateUrl: 'tpl/signup/page_signup_step_8.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign up - Mobintouch",
                            pageDescription: "Sign up and connect with thousands of Mobile Startups, Apps and Games creators, discover Products and find a great startup Job",
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.step9', {
                            url: '/invitecontacts',
                            templateUrl: 'tpl/signup/page_signup_step_9.html?v=' + ENV.latestUpdate,
                            pageTitle: "Invite imported linkedin contacts | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                contactResource: 'contactResource',
                                contact: function (contactResource, $state) {
                                    return contactResource.get().$promise
                                            .then(function (data) {
                                                if (data.contacts.length <= 0) {
                                                    $state.go('access.step8');
                                                } else {
                                                    return data.contacts;
                                                }
                                            });
                                }
                            },
                            controller: 'InviteContactsController'
                        })
                        /*.state('access.step3', {
                         url: '/emailvalidation',
                         templateUrl: 'tpl/page_signup_step_3.html?v=' + ENV.latestUpdate,
                         pageTitle: "Email validation | Mobintouch",
                         pageDescription: DefaultDescription,
                         isNoIndex: true,
                         isError404: false
                         })
                         .state('access.step4', {
                         url: '/automatic/email/validation/:hash/:email/:username/:token',
                         templateUrl: 'tpl/page_signup_step_4.html?v=' + ENV.latestUpdate,
                         pageTitle: "Email validation | Mobintouch",
                         pageDescription: DefaultDescription,
                         isNoIndex: true,
                         isError404: false
                         })*/
                        .state('access.step10', {
                            url: '/emailvalidation',
                            templateUrl: 'tpl/signup/page_signup_step_10.html?v=' + ENV.latestUpdate,
                            pageTitle: "Email validation | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false,
                            resolve: {
                                myUserResource: 'myUserResource',
                                myUser: function (myUserResource, go) {
                                    return myUserResource.get().$promise
                                            .then(function (data) {
                                                return data;
                                            }, function (error) {
                                                if (error.status == 401)
                                                    return null;
                                                else
                                                    go.NotFound();
                                            });
                                }
                            },
                            controller: 'EmailValidationFormController'
                        })
                        .state('access.step11', {
                            url: '/automatic/email/validation/:hash/:email/:username/:token',
                            templateUrl: 'tpl/signup/page_signup_step_11.html?v=' + ENV.latestUpdate,
                            pageTitle: "Email validation | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.signupinvited', {
                            url: '/signup/:invitedby',
                            templateUrl: 'tpl/page_signup.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign Up | Mobintouch",
                            pageDescription: "Welcome to Mobintouch. Sign up now and Connect with people in the Mobile Advertising industry. Mobintouch, the Mobile Advertising Social Network.",
                            isNoIndex: false,
                            isError404: false
                        })
                        /*.state('access.step5', {
                         url: '/suggestion-follow',
                         templateUrl: 'tpl/page_signup_suggestion_follow.html'
                         })
                         .state('access.step7', {
                         url: '/invite-contacts-linkedin',
                         templateUrl: 'tpl/page_signup_invite_contacts_linkedin.html?v=' + ENV.latestUpdate,
                         pageTitle: "Invite linkedin contacts | Mobintouch",
                         pageDescription: DefaultDescription,
                         isNoIndex: true,
                         isError404: false
                         })*/
                        .state('access.linkedin/signup', {
                            url: '/linkedin/signup',
                            templateUrl: 'tpl/page_linkedin_signup.html?v=' + ENV.latestUpdate,
                            pageTitle: "Linkedin Signup | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.forgotpwd', {
                            url: '/forgotpwd',
                            templateUrl: 'tpl/page_forgotpwd.html?v=' + ENV.latestUpdate,
                            pageTitle: "Forgot password | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.newpwd', {
                            url: '/reset/pwd/:token',
                            templateUrl: 'tpl/page_newpwd.html?v=' + ENV.latestUpdate,
                            pageTitle: "New password | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.404', {
                            url: '/404',
                            templateUrl: 'tpl/page_404.html?v=' + ENV.latestUpdate,
                            pageTitle: "404 | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: false,
                            isError404: true,
                            controller: '404Controller'
                        })

                        // mail
                        .state('app.mail', {
                            abstract: true,
                            url: '/messages',
                            templateUrl: 'tpl/mail.html?v=' + ENV.latestUpdate,
                            // use resolve to load other dependences
                            resolve: {
                                deps: ['uiLoad',
                                    function (uiLoad) {
                                        return uiLoad.load([
                                            'cdn/js/libs/moment.min.js']);
                                    }]
                            }
                        })
                        .state('app.mail.list', {
                            url: '/:fold',
                            templateUrl: 'tpl/mail.list.html?v=' + ENV.latestUpdate,
                            pageTitle: "Inbox - Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: true,
                            isError404: false,
                            controller: function($rootScope,$stateParams){
                                $rootScope.pageTitle = $stateParams.fold.charAt(0).toUpperCase() + $stateParams.fold.slice(1).toLowerCase() + " - Mobintouch"; 
                            }
                        })
                        .state('app.mail.detail', {
                            url: '^/{mailId}',
                            templateUrl: 'tpl/mail.detail.html?v=' + ENV.latestUpdate,
                            pageTitle: "TouchMail | Mobintouch",
                            pageDescription: DefaultDescription,
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('app.mail.compose', {
                            url: '/compose/:username',
                            templateUrl: 'tpl/mail.new.html?v=' + ENV.latestUpdate,
                            pageTitle: "Compose | Mobintouch",
                            pageDescription: "Mobintouch is a community of Mobile Apps & Games creators",
                            isNoIndex: true,
                            isError404: false
                        })
                        .state('access.contactsInvite', {
                            url: '/contacts-invite',
                            templateUrl: 'tpl/contect_invite.html?v=' + ENV.latestUpdate,
                            pageTitle: "Sign In | Mobintouch",
                            pageDescription: "Welcome back to Mobintouch. Sign in now and discover what's up-and-coming on Mobintouch, keep in touch and catch up on news from the people you know.",
                            isNoIndex: false,
                            isError404: false
                        })
                        .state('invited', {
                            url: '/invites/:invitedby',
                            pageDescription: DefaultDescription,
                            controller: 'RouteController'
                        });


                // use the HTML5 History API
                $locationProvider.html5Mode(true);
                $locationProvider.hashPrefix('!');

            }

        ])

        // JWT interceptor will take care of sending the JWT in every request.
        .config(function Config($httpProvider, jwtInterceptorProvider) {
            jwtInterceptorProvider.tokenGetter = function ($cookies) {
                var token = null;
                token = localStorage.getItem('id_token');
                if (!token)
                    token = $cookies.id_token;
                return token;
                //return localStorage.getItem('id_token');
            }
            $httpProvider.interceptors.push('jwtInterceptor');
            //$httpProvider.interceptors.push('sessionRecoverer');
        })

        //textAngular Configuration
        .config(function Config($provide) {
            $provide.decorator('taOptions', ['$delegate', function (taOptions) {
                    // $delegate is the taOptions we are decorating
                    // here we override the default toolbars and classes specified in taOptions.
                    taOptions.forceTextAngularSanitize = true; // set false to allow the textAngular-sanitize provider to be replaced
                    taOptions.keyMappings = []; // allow customizable keyMappings for specialized key boards or languages
                    taOptions.toolbar = [
                        ['bold', 'italics', 'ul', 'ol'],
                        ['insertImage', 'insertLink', 'html']
                    ];
                    taOptions.classes = {
                        //focussed: 'focussed',
                        toolbar: 'btn-toolbar',
                        toolbarGroup: 'btn-group',
                        toolbarButton: 'btn btn-default',
                        toolbarButtonActive: 'active',
                        disabled: 'disabled',
                        textEditor: 'form-control',
                        htmlEditor: 'form-control'
                    };
                    return taOptions; // whatever you return will be the taOptions
                }]);

            $provide.decorator('taTools', ['$delegate', function (taTools) {
                    taTools.insertLink.class = 'pull-right btn btn-default';
                    taTools.insertImage.class = 'pull-right btn btn-default';
                    return taTools;
                }]);
        })

        // translate config
        .config(['$translateProvider', function ($translateProvider) {

                // Register a loader for the static files
                // So, the module will search missing translation tables under the specified urls.
                // Those urls are [prefix][langKey][suffix].
                $translateProvider.useStaticFilesLoader({
                    prefix: 'cdn/l10n/',
                    suffix: '.js'
                });

                // Tell the module what language to use by default
                $translateProvider.preferredLanguage('en');

                // Tell the module to store the language in the local storage
                $translateProvider.useLocalStorage();

            }])

        /**
         * jQuery plugin config use ui-jq directive , config the js and css files that required
         * key: function name of the jQuery plugin
         * value: array of the css js file located
         */
        .constant('JQ_CONFIG', {
            easyPieChart: ['cdn/js/jquery/charts/easypiechart/jquery.easy-pie-chart.js'],
            sparkline: ['cdn/js/jquery/charts/sparkline/jquery.sparkline.min.js'],
            plot: ['cdn/js/jquery/charts/flot/jquery.flot.min.js',
                'cdn/js/jquery/charts/flot/jquery.flot.resize.js',
                'cdn/js/jquery/charts/flot/jquery.flot.tooltip.min.js',
                'cdn/js/jquery/charts/flot/jquery.flot.spline.js',
                'cdn/js/jquery/charts/flot/jquery.flot.orderBars.js',
                'cdn/js/jquery/charts/flot/jquery.flot.pie.min.js'],
            slimScroll: ['cdn/js/jquery/slimscroll/jquery.slimscroll.min.js'],
            sortable: ['cdn/js/jquery/sortable/jquery.sortable.js'],
            nestable: ['cdn/js/jquery/nestable/jquery.nestable.js',
                'cdn/js/jquery/nestable/nestable.css'],
            filestyle: ['cdn/js/jquery/file/bootstrap-filestyle.min.js'],
            slider: ['cdn/js/jquery/slider/bootstrap-slider.js',
                'cdn/js/jquery/slider/slider.css'],
            chosen: ['cdn/js/jquery/chosen/chosen.jquery.min.js',
                'cdn/js/jquery/chosen/chosen.css'],
            TouchSpin: ['cdn/js/jquery/spinner/jquery.bootstrap-touchspin.min.js',
                'cdn/js/jquery/spinner/jquery.bootstrap-touchspin.css'],
            wysiwyg: ['cdn/js/jquery/wysiwyg/bootstrap-wysiwyg.js',
                'cdn/js/jquery/wysiwyg/jquery.hotkeys.js'],
            dataTable: ['cdn/js/jquery/datatables/jquery.dataTables.min.js',
                'cdn/js/jquery/datatables/dataTables.bootstrap.js',
                'cdn/js/jquery/datatables/dataTables.bootstrap.css'],
            vectorMap: ['cdn/js/jquery/jvectormap/jquery-jvectormap.min.js',
                'cdn/js/jquery/jvectormap/jquery-jvectormap-world-mill-en.js',
                'cdn/js/jquery/jvectormap/jquery-jvectormap-us-aea-en.js',
                'cdn/js/jquery/jvectormap/jquery-jvectormap.css'],
            footable: ['cdn/js/jquery/footable/footable.all.min.js',
                'cdn/js/jquery/footable/footable.core.css'],
        }
        )

        // modules config
        .constant('MODULE_CONFIG', {
            select2: ['cdn/js/jquery/select2/select2.css',
                'cdn/js/jquery/select2/select2-bootstrap.css',
                'cdn/js/jquery/select2/select2.min.js',
                'cdn/js/modules/ui-select2.js']
        }
        )

        // oclazyload config
        .config(['$ocLazyLoadProvider', function ($ocLazyLoadProvider) {
                // We configure ocLazyLoad to use the lib script.js as the async loader
                $ocLazyLoadProvider.config({
                    debug: false,
                    events: true,
                    modules: [
                        {
                            name: 'ngGrid',
                            files: [
                                'cdn/js/modules/ng-grid/ng-grid.min.js',
                                'cdn/js/modules/ng-grid/ng-grid.css',
                                'cdn/js/modules/ng-grid/theme.css'
                            ]
                        },
                        {
                            name: 'toaster',
                            files: [
                                'cdn/js/modules/toaster/toaster.js',
                                'cdn/js/modules/toaster/toaster.css'
                            ]
                        }
                    ]
                });
            }]);
