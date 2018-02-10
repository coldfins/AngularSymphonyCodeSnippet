'use strict';
var app = typeof app !== "undefined" ? app : {};
/* Services */
// Demonstrate how to register services
angular.module('app.services', ['config']);
app.factory('userService', function ($rootScope) {
    var shared = {};
    shared.user = {};
    shared.update = function (user) {
        this.user = user ? user : {};
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleUser');
    };
    return shared;
});
app.factory('companyService', function ($rootScope) {
    var shared = {};
    shared.company = {};
    shared.update = function (company) {
        this.company = company ? company : {};
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleCompany');
    };
    return shared;
});
app.factory('eventService', function ($rootScope) {
    var shared = {};
    shared.event = {};
    shared.update = function (event) {
        this.event = event;
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleEvent');
    };
    return shared;
});
app.factory('userFeedService', function ($rootScope) {
    var shared = {};
    shared.userfeed = {};
    shared.update = function (userfeed) {
        this.userfeed = userfeed;
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleUserFeed');
    };
    return shared;
});
app.factory('companyFeedService', function ($rootScope) {
    var shared = {};
    shared.companyfeed = {};
    shared.update = function (companyfeed) {
        this.companyfeed = companyfeed;
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleCompanyFeed');
    };
    return shared;
});
app.factory('connectionsService', function ($rootScope) {
    var shared = {};
    shared.connections = {};
    shared.update = function (connections) {
        this.connections = connections;
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleConnections');
    };
    return shared;
});
app.factory('allOffersService', function ($rootScope) {
    var shared = {};
    shared.allOffers = {};
    shared.update = function (allOffers) {
        this.allOffers = allOffers;
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleAllOffers');
    };
    return shared;
});
app.factory('offersFiltersService', function ($rootScope) {
    var shared = {};
    shared.offersFilters = {};
    shared.update = function (offersFilters) {
        this.offersFilters = offersFilters;
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleOffersFilters');
    };
    return shared;
});
app.factory('loadingOffersService', function ($rootScope) {
    var shared = {};
    shared.loadingOffers = null;
    shared.update = function (loadingOffers) {
        this.loadingOffers = loadingOffers;
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleLoadingOffers');
    };
    return shared;
});
app.factory('alertService', function ($rootScope, $http, userService, ENV) {

    var shared = {};

    shared.update = function () {
        var user = userService.user;
        if (user && user.username) {
            $http.get(ENV.baseUrl + '/json/version.json?' + Date.now(), {cache: false, skipAuthorization: true}).then(function (response) {
                $rootScope.lastVersion = response.data.version;
            });
            if (!user.version || user.version != $rootScope.lastVersion) {
                $rootScope.alerts = [
                    {type: 'danger', msg: 'Mobintouch has new features! Please click here in order to reload your page and get access to them. If this is not enough, please type : CTR + F5 (Windows) or CMD + R (OS X).'}
                ];
            } else {
                $rootScope.alerts = [];
            }
            if (!user.emailValidation && user.emailValidation != null) {
                $rootScope.alerts.push({type: 'success', msg: "Please confirm your email address. We've sent a verification email, check your mailbox and spams." + " <a href='emailvalidation'> If you didn‘t receive your verification email please click here. </a> "});
            }
        }
    };

    /* shared.loadingOffers = null;
     shared.update = function (loadingOffers) {
     this.loadingOffers = loadingOffers;
     this.broadcast();
     };
     shared.broadcast = function () {
     $rootScope.$broadcast('handleLoadingOffers');
     };*/
    return shared;
});
/*app.factory('autocompleteService', function ($rootScope) {
 var shared = {};
 shared.uAutocomplete = {};
 shared.cAutocomplete = {};
 shared.uUpdate = function(uAutocomplete){
 this.uAutocomplete = uAutocomplete;
 this.broadcast();
 }
 shared.cUpdate = function(cAutocomplete){
 this.cAutocomplete = cAutocomplete;
 this.broadcast();
 }
 shared.broadcast = function (){
 $rootScope.$broadcast('handleAutocomplete');
 }
 return shared;
 });*/
app.factory('queryService', function ($rootScope) {
    var shared = {};
    shared.query = {};
    shared.results = {};
    shared.loadingSearch = false;
    shared.stopscrollloading = false;
    shared.string = '';
    shared.notLoggedIn = false;
    shared.profileType = 'people';
    shared.noResults = false;
    shared.count = 0;
    shared.update = function (query) {
        this.query = query;
        this.broadcast();
    };
    shared.updateResults = function (count, results, string, notLoggedIn, profileType, loadingSearch) {
        //console.log("UPDATE RESULTS SERVICE");
        //console.log(results);
        //console.log(results.length);
        if (angular.isUndefined(results.length) || results.length === 0) {
            //console.log("IF");
            this.results = results;
        }
        //else this.results.push(results);
        //else if (results.length>0){
        else if (angular.isUndefined(this.results.length)) {
            //console.log("ELSE IF");
            this.results = results;
        } else {
            //console.log("ELSE");
            this.results = this.results.concat(results);
        }
        this.string = string;
        this.notLoggedIn = notLoggedIn;
        this.profileType = profileType;
        if (results.length === 0)
            this.noResults = true;
        else
            this.noResults = false;
        this.loadingSearch = loadingSearch;
        this.stopscrollloading = loadingSearch;
        this.count = count;
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleQuery');
    };
    return shared;
});
app.factory('searchService', function ($http, queryService, $state, ENV, Notification) {
    return {
        search: function ($scope) {
            $scope.loadingSearch = true;
            if ($scope.user.username) {
                //console.log("QUERY:");
                //console.log($scope.query);
                var limit = 200;
                var serverPagination = 10; // MUST BE CHANGED ON SERVER SIDE ALSO
                if (($scope.query.skip * serverPagination) >= limit) {
                    $scope.loadingSearch = false;
                    $scope.stopscrollloading = true;
                    Notification.warning({
                        title: 'You have reached a limit!',
                        message: 'Sorry, the number of results are limited to ' + limit + ' per request.'
                    });
                } else {
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/search',
                        data: {
                            query: $scope.query
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log("RESULT SERVICE:");
                        //console.log(data);
                        var data = response.data;
                        queryService.updateResults(data.count, data.users, $scope.query.string, false, $scope.query.profileType, false);
                        $scope.loadingSearch = false;
                        $scope.stopscrollloading = false;
                    }).catch(function (data, status) {
                        if (status === 401)
                            $state.go('access.signin');
                    });
                }
            } else {
                $scope.stopscrollloading = false;
                queryService.updateResults(0, {}, '', true, $scope.query.profileType, false);
            }
        }
    };
});
app.factory('AuthService', ['$http', '$state', 'ENV', 'userService', '$cookies', function ($http, $state, ENV, userService, $cookies) {
        var currentUser;
        //console.log("AuthService...");
        //console.log(currentUser);
        return {
            login: function ($scope) {
                //$templateCache.removeAll();
                // Authenticate
                return $http({
                    method: "POST",
                    url: ENV.apiEndpoint + '/api/authenticate',
                    skipAuthorization: true,
                    data: {
                        _username: $scope.user.username,
                        _password: $scope.user.password
                    },
                    headers: {'Content-Type': 'application/json'}
                }).then(function (response) {
                    currentUser = response.data;
                    $cookies.put("usr_state", response.data.token);
                    $cookies.putObject("ENV", ENV);
                    return response;
                });
            },
            socialLogin: function ($scope) {
                return $http({
                    method: "POST",
                    url: ENV.apiEndpoint + '/api/socialauthenticate',
                    skipAuthorization: true,
                    data: $scope,
                    headers: {'Content-Type': 'application/json'}
                }).then(function (response) {
                    currentUser = response.data;
                    $cookies.put("usr_state", response.data.token);
                    $cookies.putObject("ENV", ENV);
                    return response;
                });
            },
            logout: function () {
                //console.log('logout AuthService');
                // Erase the token if the user fails to log in
                //localStorage.clear();                // Delete any other items, too
                if (localStorage.getItem("id_token") !== null)
                {
                    var updateplayer = $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/updateplayerid',
                        data: {
                            type: 'unset'
                        },
                        headers: {Authorization: 'Bearer ' + localStorage.getItem("id_token")}
                    });
                }
                localStorage.removeItem("id_token");
                $cookies.remove('id_token');
                $cookies.remove('usr_state');
                $cookies.remove('ENV');
                currentUser = {};
                userService.update({});
                //$state.go('access.signin');
                /*localStorage.removeItem("id_token");
                 
                 $cookies.id_token = 'null';
                 $cookies.usr_state = 'null';
                 $cookies.ENV = 'null';
                 
                 currentUser = {};
                 //$state.go('access.signin');*/
            }
        };
    }]);
// A RESTful factory for retreiving mails from 'mails.json'
app.factory('mails', ['$http', function ($http) {
        var path = 'js/app/mail/mails.json';
        var mails = $http.get(path).then(function (resp) {
            return resp.data.mails;
        });
        var factory = {};
        factory.all = function () {
            return mails;
        };
        factory.get = function (id) {
            return mails.then(function (mails) {
                for (var i = 0; i < mails.length; i++) {
                    if (mails[i].id === id)
                        return mails[i];
                }
                return null;
            });
        };
        return factory;
    }]);
app.factory('MailService', function ($rootScope) {
    var shared = {};
    shared.mails = {};
    shared.count = 0;
    shared.skip = 0;
    shared.update = function (mails, count, skip) {
        if (angular.isUndefined(this.mails))
            this.mails = mails;
        else {
            if (skip > 0)
                this.mails = this.mails.concat(mails);
            else
                this.mails = mails;
        }
        this.count = count;
        this.skip = skip;
        //console.log(this);
        this.broadcast();
    };
    shared.broadcast = function () {
        $rootScope.$broadcast('handleMail');
    };
    return shared;
});
app.factory('go', ['$state', function ($state) {
        return {
            SignIn: function () {
                $state.go('access.signin');
            },
            NotFound: function () {
                $state.go('access.404');
            }
        };
    }]);
app.factory('offerService', ['$filter', function ($filter) {
        return {
            offers: function (allOffers, offersFilters) {
                var offers = [];
                angular.forEach(allOffers, function (off, k) {
                    var temp = off;
                    var Pricing = Object.keys(off.pricingModels).map(function (v) {
                        return v;
                    });
                    var Quality = Object.keys(off.quality).map(function (v) {
                        return v;
                    });
                    var Platforms = Object.keys(off.platforms).map(function (v) {
                        return v;
                    });
                    if (!angular.isUndefined(offersFilters.country) && offersFilters.country.length > 0) {
                        if (($filter('orFilter')(off.countries, offersFilters.country)).length === 0) {
                            temp = null;
                        }
                    }
                    if (!angular.isUndefined(offersFilters.pricing) && offersFilters.pricing.length > 0 && temp !== null) {
                        if (($filter('orFilter')(Pricing, offersFilters.pricing)).length === 0) {
                            temp = null;
                        }
                    }
                    if (!angular.isUndefined(offersFilters.quality) && offersFilters.quality.length > 0 && temp !== null) {
                        if (($filter('orFilter')(Quality, offersFilters.quality)).length === 0) {
                            temp = null;
                        }
                    }
                    if (!angular.isUndefined(offersFilters.platform) && offersFilters.platform.length > 0 && temp !== null) {
                        if (($filter('orFilter')(Platforms, offersFilters.platform)).length === 0) {
                            temp = null;
                        }
                    }
                    // IS OFFER INCLUDED ON THE FILTER
                    if (temp !== null)
                        offers.push(off);
                });
                return offers;
            }
        };
    }]);
app.factory('postPreviewService', ['$http', 'ENV', '$filter', function ($http, ENV, $filter) {
        return {
            preview: function ($scope) {
                if (!$scope.newPost.image && !$scope.newPost.youtube && !$scope.newPost.vimeo && !$scope.newPost.userProfile && !$scope.newPost.companyPage) {
                    $scope.loadingSendingPost = true;
                    var url = $scope.newPost.text;
                    var regExpYoutube = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
                    var regExpVimeo = /https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)/;
                    var regMobintouch = (window.location.host.indexOf('www.mobintouch.com') >= 0) ? /^https:\/\/www.mobintouch.com\/(profile|company|offer|offers)\/([a-zA-Z0-9_-]*)/ : /^https:\/\/www-dev.mobintouch.com\/(profile|company|offer|offers)\/([a-zA-Z0-9_-]*)/;
                    var regurl = (window.location.host.indexOf('www.mobintouch.com') >= 0) ? "www.mobintouch.com" : "https://www-dev.mobintouch.com";
                    var matchYoutube = url.match(regExpYoutube);
                    var matchVimeo = url.match(regExpVimeo);
                    var matchMobintouch = url.match(regMobintouch);
                    if (matchYoutube || matchVimeo) {
                        $scope.loadingSendingPost = true;
                        if (matchYoutube) {
                            if (!angular.isUndefined(matchYoutube[7])) {
                                var youtube = matchYoutube[7].split(' ');
                                youtube = youtube[0];
                                if (youtube.length === 11) {
                                    $scope.newPost.youtube = youtube;
                                    $scope.loadingSendingPost = false;
                                } else {
                                    $scope.loadingSendingPost = false;
                                }
                            } else {
                            }
                        }
                        if (!$scope.newPost.youtube && matchVimeo) {
                            $scope.loadingSendingPost = true;
                            if (!angular.isUndefined(matchVimeo[3])) {
                                var vimeo = matchVimeo[3].split(' ');
                                vimeo = vimeo[0];
                                if (vimeo.length > 7) {
                                    $scope.newPost.vimeo = vimeo;
                                    $scope.loadingSendingPost = false;
                                } else {
                                    $scope.loadingSendingPost = false;
                                }
                            } else {
                            }
                        } else {
                        }
                    } else if (matchMobintouch) {
                        $scope.loadingSendingPost = true;
                        $scope.newPost.youtube = null;
                        $scope.newPost.vimeo = null;
                        $scope.newPost.image = null;
                        //var url = ENV.apiEndpoint + '/api/public/'+matchMobintouch[1]+'/'+matchMobintouch[2];
                        //console.log(matchMobintouch);
                        if (matchMobintouch[1] === "offer") {
                            var response = $http({
                                method: "POST",
                                data: {
                                    offerID: matchMobintouch[2]
                                },
                                url: ENV.apiEndpoint + '/api/offer/publicview',
                                headers: {'Content-Type': 'application/json'}
                            });
                        } else {
                            //public/company/{companyUsername}
                            var response = $http({
                                method: "GET",
                                url: ENV.apiEndpoint + '/api/public/' + matchMobintouch[1] + '/' + matchMobintouch[2],
                                headers: {'Content-Type': 'application/json'}
                            });
                        }
                        response.then(function (response) {
                            var data = response.data;
                            if (matchMobintouch[1] === 'profile') {
                                $scope.newPost.userProfile = true;
                                $scope.newPost.sharedUserAvatar = data.avatar;
                                $scope.newPost.sharedUserFullName = data.name + '' + data.lastname;
                                $scope.newPost.sharedUserJobTitle = data.jobTitle;
                                $scope.newPost.sharedUserCompany = data.company;
                                $scope.newPost.sharedUsername = data.username;
                                $scope.loadingSendingPost = false;
                            } else if (matchMobintouch[1] === 'company') {
                                $scope.newPost.companyPage = true;
                                $scope.newPost.sharedCompanyAvatar = data.avatar;
                                $scope.newPost.sharedCompanyName = data.name;
                                $scope.newPost.sharedCompanyUsername = data.username;
                                $scope.newPost.sharedCompanyFollowers = data.followers.length;
                                $scope.newPost.sharedCompanyType = data.companyType;
                                $scope.loadingSendingPost = false;
                            } else if (matchMobintouch[1] === 'offer') {
                                var lPlatforms = [], lPricingModel = [], lCountry = [], lquality = [];
                                angular.forEach(data.countries, function (val, k) {
                                    lCountry.push($filter('translate')("country." + val));
                                });
                                angular.forEach(data.pricingModels, function (bool, val) {
                                    lPricingModel.push(val);
                                });
                                angular.forEach(data.platforms, function (bool, val) {
                                    lPlatforms.push(val);
                                });
                                angular.forEach(data.quality, function (bool, val) {
                                    lquality.push(val);
                                });
                                $scope.newPost.newsTitle = "Mobintouch - Mobile traffic offer by " + data.userLastName + " " + data.userFirstName + " from " + data.userCompany;
                                $scope.newPost.newsImage = "https://cdn.mobintouch.com/img/stackoffers-sharing.jpg";
                                $scope.newPost.newsSourceURL = regurl;
                                $scope.newPost.newsSource = regurl;
                                $scope.newPost.newsURL = $scope.newPost.text;
                                var d = new Date();
                                $scope.newPost.newsDate = d.getTime();
                                $scope.newPost.link = true;
                                $scope.loadingSendingPost = false;
                            } else {
                                $scope.newPost.link = true;
                                $scope.newPost.newsTitle = "StackOffer - Mobile Traffic Offer";
                                $scope.newPost.newsImage = "https://cdn.mobintouch.com/img/stackoffers-sharing.jpg";
                                $scope.newPost.newsSourceURL = regurl;
                                $scope.newPost.newsSource = regurl;
                                var d = new Date();
                                $scope.newPost.newsDate = d.getTime();
                                $scope.loadingSendingPost = false;
                            }
                        });
                        response.catch(function (response) {
                            //console.log(status);
                            //console.log(data);
                            var data = response.data;
                            if (matchMobintouch[1] === 'offers') {
                                $scope.newPost.link = true;
                                $scope.newPost.newsTitle = "StackOffer - Mobile Traffic Offer";
                                $scope.newPost.newsImage = "https://cdn.mobintouch.com/img/stackoffers-sharing.jpg";
                                $scope.newPost.newsSourceURL = "https://www.mobintouch.com";
                                $scope.newPost.sharedOffersDescription = data.userLastName + " " + data.userFirstName + " from " + data.userCompany + " is looking for mobile traffic in ";
                                $scope.loadingSendingPost = false;
                            } else {
                                $scope.newPost.userProfile = null;
                                $scope.newPost.companyPage = null;
                                $scope.newPost.offers = null;
                                $scope.newPost.offer = null;
                                $scope.newPost.image = null;
                                $scope.loadingSendingPost = false;
                            }
                        });
                    } else {
                        $scope.loadingSendingPost = true;
                        $scope.newPost.youtube = null;
                        $scope.newPost.vimeo = null;
                        $scope.newPost.userProfile = null;
                        $scope.newPost.companyPage = null;
                        // IMAGE URL
                        var imageRegex = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?(gif|png|jpg|jpeg)/i;
                        var matchImage = url.match(imageRegex);
                        // SIMPLE URL
                        var urlRegex = /(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
                        var matchURL = url.match(urlRegex);
                        if (matchImage) {
                            var image = matchImage[0].split(' ');
                            $scope.newPost.image = image[0];
                            $scope.loadingSendingPost = false;
                        } else if (matchURL) {
                            $scope.loadingSendingPost = true;
                            var link = matchURL[0].split(' ');
                            link = link[0];
                            link = link.replace(/\/$/, '');
                            var request = $http({
                                method: "POST",
                                url: ENV.apiEndpoint + '/api/social/crawl/link',
                                data: {
                                    link: link
                                },
                                headers: {'Content-Type': 'application/json'}
                            }).then(function (response) {
                                //console.log(status);
                                //console.log(data);
                                var data = response.data;
                                $scope.newPost.link = true;
                                $scope.newPost.newsTitle = data.newsTitle;
                                $scope.newPost.newsImage = data.newsImage;
                                $scope.newPost.newsSource = data.newsSource;
                                $scope.newPost.newsSourceURL = data.newsSourceURL;
                                $scope.newPost.newsURL = data.newsURL;
                                $scope.newPost.newsDate = data.newsDate;
                                $scope.loadingSendingPost = false;
                            }).catch(function (response) {
                                //console.log(status);
                                //console.log(data);
                                $scope.newPost.link = false;
                                $scope.newPost.image = null;
                                $scope.loadingSendingPost = false;
                            });
                        } else {
                            $scope.loadingSendingPost = false;
                            $scope.newPost.image = null;
                        }
                    }
                }
                return $scope;
            }
        };
    }]);
app.factory('authHttpResponseInterceptor', ['$q', function ($q) {
        return {
            /*response: function(response){
             if (response.status === 401) {
             //console.log("Response 401");
             
             }
             return response || $q.when(response);
             },*/
            responseError: function (rejection) {
                if (rejection.status === 401) {
                    //console.log("Response Error 401",rejection);
                    //$state.go('access.signin');
                } else if (rejection.status === 404) {
                    //console.log("Response Error 401",rejection);
                    //$state.go('access.404');
                }
                return $q.reject(rejection);
            }
        };
    }]);
app.factory('sessionRecoverer', ['$q', '$injector', '$location', function ($q, $injector, $location) {
        var sessionRecoverer = {
            responseError: function (response) {
                // Session has expired
                if (response.status === 401) {
                    //console.log("sessionRecoverer : Error 401");
                    return $location.path('/');
                    //console.log("Response Error 401");
                    //$state.go('access.signin');
                    /*var SessionService = $injector.get('SessionService');
                     var $http = $injector.get('$http');
                     var deferred = $q.defer();
                     
                     // Create a new session (recover the session)
                     // We use login method that logs the user in using the current credentials and
                     // returns a promise
                     SessionService.login().then(deferred.resolve, deferred.reject);*/

                    // When the session recovered, make the same backend call again and chain the request
                    /*return deferred.promise.then(function() {
                     return $http(response.config);
                     });*/
                } else if (response.status === 404) {
                    //console.log("sessionRecoverer : Error 404");
                    return $location.path('/access/404');
                }
                return $q.reject(response);
            }
        };
        return sessionRecoverer;
    }]);
/*
 app.factory('Page', function() {
 var title = "The world's 1st Mobile Advertising Social Network | Mobintouch";
 return {
 title: function() { return title; },
 setTitle: function(newTitle) { title = newTitle }
 };
 });*/

app.factory('publicUserResource', ['$resource', 'ENV', '$cookies', function ($resource, ENV, $cookies) {
        var loggedin = localStorage.getItem('id_token') !== null || ($cookies.id_token != 'null' && !angular.isUndefined($cookies.id_token));
        if (loggedin) {
            //console.log('/api/private/user/:username');
            return $resource(ENV.apiEndpoint + '/api/private/user/:username', {cache: true});
        } else {
            //console.log('/api/public/user/:username');
            return $resource(ENV.apiEndpoint + '/api/public/user/:username', {cache: true});
        }
    }]);
app.factory('versionResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.baseUrl + '/json/version.json?' + Date.now(), {
            cache: false,
            skipAuthorization: true
        }, {get: {method: 'get'}});
        //return $resource(ENV.baseUrl+'/json/version.json?'+Date.now(), { cache: false, skipAuthorization: true }, { get : { method: 'get', isArray:true }})
    }]);
app.factory('myUserResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/user', {cache: true});
    }]);
app.factory('myUserResourceWithMutualConnections', ['$resource', 'ENV', function ($resource, ENV) {
        return {
            inTouch: $resource(ENV.apiEndpoint + '/api/user?mutualconnections=inTouch', {cache: true}),
            whoVisitedMe: $resource(ENV.apiEndpoint + '/api/user?mutualconnections=whoVisitedMe', {cache: true}),
            iVisited: $resource(ENV.apiEndpoint + '/api/user?mutualconnections=iVisited', {cache: true}),
        };
    }]);
app.factory('whoVisistedMe', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/who-visisted-me', {cache: true});
    }]);
app.factory('lastVisistedProfiles', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/last-visisted-profiles', {cache: true});
    }]);
app.factory('publicCompanyResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/public/company/:companyusername', {cache: true})
    }]);
app.factory('publicCompanyResourceWithMutualConnections', ['$resource', 'ENV', function ($resource, ENV) {
        return {
            employees: $resource(ENV.apiEndpoint + '/api/public/company/:companyusername?mutualconnections=employees', {cache: true})
        };
    }]);
app.factory('myCompanyResource', ['$resource', 'ENV', function ($resource, ENV) {
        //return $resource(ENV.apiEndpoint + '/api/company', { cache: true, headers: { 'Content-Type': 'application/json' }})
        return $resource(ENV.apiEndpoint + '/api/company', {cache: true, headers: {'Content-Type': 'application/json'}}, {
            post: {method: 'POST'}
        });
    }]);
app.factory('myCompanyResourceWithMutualConnections', ['$resource', 'ENV', function ($resource, ENV) {
        return{
            followers: $resource(ENV.apiEndpoint + '/api/company?mutualconnections=followers', {cache: true, headers: {'Content-Type': 'application/json'}}, {post: {method: 'POST'}}),
            employees: $resource(ENV.apiEndpoint + '/api/company?mutualconnections=employees', {cache: true, headers: {'Content-Type': 'application/json'}}, {post: {method: 'POST'}}),
        };
    }]);
app.factory('linkedinResource', ['$resource', '$location', function ($resource, $location) {
        var params = $location.search();
        var url = "https://www.linkedin.com/uas/oauth2/accessToken?grant_type=authorization_code&code=" + params.code + "&redirect_uri=http://angular.dev/linkedin/auth&client_id=77qs31x0xkh7tz&client_secret=fzruvzqCLHPUrWE0";
        return $resource(url, {cache: false, headers: {'Content-Type': 'application/x-www-form-urlencoded'}}, {
            post: {method: 'POST', skipAuthorization: true}
        });
    }]);
app.factory('eventResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/public/event/:eventname', {cache: true});
    }]);
app.factory('resetOffersNotificationsResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/offers/reset', {cache: true}, {get: {method: 'get'}});
    }]);
app.factory('allpublicOffersResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/offers/publicoffers', {cache: true}, {get: {method: 'get', isArray: true}});
    }]);
app.factory('publicOffersResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/offers/all', {cache: true}, {get: {method: 'get', isArray: true}});
    }]);
app.factory('myRepliesResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/offers/myreplies', {cache: true}, {get: {method: 'get', isArray: true}});
    }]);
app.factory('myOffersResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/offers/myoffers', {cache: true}, {get: {method: 'get', isArray: true}});
    }]);
app.factory('offerDetailsResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/offer/get/:offerid', {cache: false});
    }]);
app.factory('storestastics', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/public/storestastics/:postid/:type', {cache: true}, {update: {method: 'PUT', params: {postid: '@postid', type: '@type'}}});
    }]);
app.factory('leftQuota', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/mail/leftquota', {cache: true});
    }]);
app.factory('suggestKeywords', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/addinterests/keywordssuggestions', {cache: true});
    }]);
app.factory('autocompleteKeywords', ['$resource', 'ENV', '$q', function ($resource, ENV, $q) {
        return $resource(ENV.apiEndpoint + '/api/autocompletekeywords/:keyword', {cache: true}, {get: {method: 'get', isArray: true}});
    }]);
app.factory('contactResource', ['$resource', 'ENV', '$q', function ($resource, ENV, $q) {
        return $resource(ENV.apiEndpoint + '/api/getcontactlist/:service', {cache: true}, {get: {method: 'get', params: {service: '@service'}}});
    }]);
app.factory('connectionSuggestions', ['$resource', 'ENV', '$q', function ($resource, ENV, $q) {
        return $resource(ENV.apiEndpoint + '/api/connection/suggestions', {cache: true}, {get: {method: 'get', isArray: true}});
    }]);
app.factory('questionDetailsResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/public/question/slug/:slug', {cache: true}, {get: {method: 'get', params: {slug: '@slug'}}});
    }]);
app.factory('questionTagResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/public/question/tag/:limit/:offset/:tag', {cache: true}, {get: {method: 'get', params: {limit: '@limit', offset: '@offset', slug: '@tag'}}});
    }]);
app.factory('questionStatsResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/public/qa/get-stats', {cache: true}, {get: {method: 'get'}});
    }]);
app.factory('randomProfileResource', ['$resource', 'ENV', '$q', function ($resource, ENV, $q) {
        return $resource(ENV.apiEndpoint + '/api/public/random-user-profiles', {cache: true}, {get: {method: 'get'}});
    }]);
app.factory('peopleDirectoryResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/public/directory/people/:startwith/:skip', {cache: true}, {get: {method: 'get', params: {startwith: '@startwith',skip: '@skip'}}});
    }]);
app.factory('settingNotificationResource', ['$resource', 'ENV', function ($resource, ENV) {
        return $resource(ENV.apiEndpoint + '/api/settings/notifications', {cache: true}, {get: {method: 'get'}});
    }]);