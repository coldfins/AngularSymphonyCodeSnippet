;
'use strict';
/* Controllers */
angular.module('app.controllers', ['pascalprecht.translate', 'ngCookies', 'config'])
        .controller('AppCtrl', ['$filter', '$scope', '$translate', '$localStorage', '$window', '$http', 'userService', 'companyService', '$location', '$state', 'ENV', '$rootScope', '$cookies',
            function ($filter, $scope, $translate, $localStorage, $window, $http, userService, companyService, $location, $state, ENV, $rootScope, $cookies) {
                console.log("### CONTROLLER: AppCtrl ####");
                $rootScope.location = $location;

                // add 'ie' classes to html
                var isIE = !!navigator.userAgent.match(/MSIE/i);
                isIE && angular.element($window.document.body).addClass('ie');
                isSmartDevice($window) && angular.element($window.document.body).addClass('smart');
                // config
                $scope.app = {
                    name: 'Mobintouch',
                    version: '0.0.1',
                    // for chart colors
                    color: {
                        primary: '#E21417',
                        info: '#23b7e5',
                        success: '#27c24c',
                        warning: '#fad733',
                        danger: '#f05050',
                        light: '#e8eff0',
                        dark: '#292929',
                        black: '#2E0405'
                    },
                    settings: {
                        themeID: 1,
                        navbarHeaderColor: 'bg-white-only',
                        navbarCollapseColor: 'bg-white-only',
                        asideColor: 'bg-black',
                        headerFixed: true,
                        asideFixed: false,
                        asideFolded: false,
                        asideDock: false,
                        container: true
                    },
                    baseUrl: ENV.baseUrl,
                    protocol: $location.protocol(),
                    host: $location.host(),
                    latestUpdate: ENV.latestUpdate
                };
                // angular translate
                $scope.lang = {isopen: false};
                $scope.langs = {en: 'English', de_DE: 'German', it_IT: 'Italian', fr_FR: 'French'};
                $scope.selectLang = $scope.langs[$translate.proposedLanguage()] || "English";
                $scope.setLang = function (langKey) {
                    // set the current lang
                    $scope.selectLang = $scope.langs[langKey];
                    // You can change the language during runtime
                    $translate.use(langKey);
                    $scope.lang.isopen = !$scope.lang.isopen;
                };
                function isSmartDevice($window) {
                    // Adapted from http://www.detectmobilebrowsers.com
                    var ua = $window['navigator']['userAgent'] || $window['navigator']['vendor'] || $window['opera'];
                    // Checks for iOs, Android, Blackberry, Opera Mini, and Windows mobile devices
                    return (/iPhone|iPod|iPad|Silk|Android|BlackBerry|Opera Mini|IEMobile/).test(ua);
                }
                $scope.$on('handleCompany', function () {
                    $scope.company = companyService.company;
                });
                // USER
                $scope.user = userService.user;
                $rootScope.userMode = null;
                // GET CURRENT USER
                $http.get(ENV.apiEndpoint + '/api/user', {cache: true})
                        .then(function (response) {
                            $scope.user = response.data;
                            //console.log("MY USER (APPCTRL)");
                            //console.log(data);
                            userService.update(response.data);
                            $cookies.usr_state = $window.localStorage.getItem('id_token');
                            $cookies.ENV = JSON.stringify(ENV);
                            if (!$scope.user.name || !$scope.user.lastname) {
                                $state.go('access.step2');
                            }

                            //Removed email validation redirection
                            /*if ($scope.user.name && $scope.user.lastname && $scope.user.validated == false) {
                             if (($location.path()).indexOf("automatic/email") == -1) {
                             $state.go('access.step10');
                             }
                             }*/

                            if ($scope.user.companyPage && angular.isUndefined($scope.company))
                            {
                                var request = $http.post(ENV.apiEndpoint + '/api/company', {cache: true});
                                request.then(function (response) {
                                    $scope.company = response.data;
                                    $rootScope.companyPercentage = response.data.companyPercentage;
                                    companyService.update($scope.company);
                                });
                            }
                            $rootScope.userMode = 'member';
                        })
                        .catch(function (response) {
                            delete $cookies["usr_state"];
                            delete $cookies["ENV"];
                            localStorage.removeItem("id_token");
                            //console.log(" --- ERROR ---");
                            //console.log(data);
                            //console.log("CURRENT STATE");
                            //console.log($location.path());
                            if ($location.path() == '/')
                                $state.go('access.landing');
                            if ($location.path().indexOf("myprofile") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("mycompany") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("edit/profile") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("edit/company") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("touchmail") > 0)
                                $state.go('access.signin');
                            if ($location.path() == "/intouch")
                                $state.go('access.signin');
                            if ($location.path().indexOf("settings") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("access.step") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("step1-7") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("step2-7") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("step3-7") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("step4-7") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("step5-7") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("addinterests") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("importcontacts") > 0)
                                $state.go('access.signin');
                            if ($location.path().indexOf("invitecontacts") > 0)
                                $state.go('access.signin');
                            $rootScope.userMode = 'visitor';
                        });

                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                // CACHE CLEAR
                $scope.reload = function () {
                    //console.log(ENV.apiEndpoint + '/api/update/user/version');
                    //console.log($rootScope.lastVersion);
                    //console.log(JSON.stringify({"lastVersion": $rootScope.lastVersion}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/update/user/version',
                        data: {
                            lastVersion: $rootScope.lastVersion
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user.version = response.data;
                        userService.update($scope.user);
                        mixpanel.identify($scope.user.username);
                        //mixpanel.track("Sign Up step 2 | Sign 2nd step done",{
                        mixpanel.track("Clear Cache", {
                            "Page": $state.current.url,
                            "Type": "Action",
                            "Text": "Clear Cache",
                            "Success": true,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                        location.reload(true);
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            mixpanel.identify($scope.user.username);
                            //mixpanel.track("Sign Up step 2 | Sign 2nd step done",{
                            mixpanel.track("Clear Cache", {
                                "Page": $state.current.url,
                                "Type": "Action",
                                "Text": "Clear Cache",
                                "Error": true,
                                "ErrorStatus": response.status,
                                "ErrorData": response.data,
                                "Username": $scope.user.username,
                                "$email": $scope.user.email,
                                "Company": $scope.user.company,
                                "Job Title": $scope.user.jobTitle,
                                "Role": $scope.user.companyType,
                                "Subrole": $scope.user.companySubType
                            });
                        }
                    });
                };
                $scope.closeAlert = function (index) {
                    $scope.alerts.splice(index, 1);
                };
                $scope.offersCounter = function () {
                    $http({
                        method: "GET",
                        url: ENV.apiEndpoint + '/api/offers/counter',
                        cache: false,
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        if ($scope.user) {
                            var data = response.data;
                            $scope.user.offersNotifications = data.offersNotifications;
                            $scope.user.alertsNotifications = data.alertsNotifications;
                            $scope.user.alerts = data.alerts;
                            $scope.user.emailsNotifications = data.emailsNotifications;
                            $scope.user.repliedOffers = data.repliedOffers;
                            userService.update($scope.user);
                        }
                    }).catch(function (response) {
                    });
                };
                if ($scope.user) {
                    setInterval(function () {
                        if ($scope.user && $scope.user.username)
                        {
                            $scope.offersCounter();
                        }
                    }, 60000);
                }
                $scope.openWindow = function (offer) {
                    var offerID = null;
                    if (!angular.isUndefined(offer['id']))
                        offerID = offer['id'];
                    if (!angular.isUndefined(offer['_id']) && !angular.isUndefined(offer['_id'].$id))
                        offerID = offer['_id'].$id;
                    var lPlatforms = [], lPricingModel = [], lCountry = [];
                    angular.forEach(offer.countries, function (val, k) {
                        lCountry.push($filter('translate')("country." + val));
                    });
                    angular.forEach(offer.pricingModels, function (bool, val) {
                        lPricingModel.push(val);
                    });
                    angular.forEach(offer.platforms, function (bool, val) {
                        lPlatforms.push(val);
                    });
                    lCountry = lCountry.slice(0, 3);
                    lPricingModel = lPricingModel.slice(0, 3);
                    lPlatforms = lPlatforms.slice(0, 3);
                    var title = "Mobintouch - Mobile traffic offer by " + offer.userLastName + " " + offer.userFirstName + " from " + offer.userCompany;
                    var summary = offer.userLastName + " " + offer.userFirstName + " from " + offer.userCompany + " is looking for mobile traffic in " + lCountry + " on a " + lPricingModel + " basis for " + lPlatforms;
                    $window.open('https://www.linkedin.com/shareArticle?mini=true&url=https%3A//www.mobintouch.com/offer/' + offerID + '?1&title=' + encodeURI(title) + '&summary=' + encodeURI(summary) + '&source=Mobintouch', 'name', 'width=600,height=400')
                };
                $scope.shareEmail = function (offer) {
                    var countryArr = [];
                    var pricingArr = [];
                    var paltformArr = [];
                    var qualityArr = [];
                    var desc = "";
                    var offerid;
                    if (offer.id) {
                        offerid = offer.id;
                    } else {
                        offerid = offer._id['$id'];
                    }
                    angular.forEach(offer.countries, function (country, key) {
                        countryArr.push($filter('translate')("country." + country));
                    });
                    angular.forEach(offer.pricingModels, function (pricingModel, key) {
                        pricingArr.push(key);
                    });
                    angular.forEach(offer.platforms, function (platform, key) {
                        paltformArr.push(key);
                    });
                    angular.forEach(offer.quality, function (q, key) {
                        qualityArr.push(key);
                    });
                    if (offer.description) {
                        desc = " \n\nDetails: " + offer.description;
                    }
                    var body = "\nOffer Url: www.mobintouch.com/offer/" + offerid + "\n\nCountries: " + countryArr.join(', ') + "\n\nPlatforms: " + paltformArr.join(', ') + "\n\nPricing Model: " + pricingArr.join(', ') + desc;
                    var subject = "Have a look on this mobile offer: " + paltformArr.join(', ') + " | " + pricingArr.join(', ');
                    subject = encodeURI(subject);
                    $window.open("mailto:?Subject=" + subject + "&body=" + encodeURI(body), "_self");
                    return false;
                };
                // Update active  log to server
                $scope.updateActiveLog = function () {
                    if ($scope.user && $scope.user.username)
                    {
                        var request = $http({
                            method: "PUT",
                            url: ENV.apiEndpoint + '/api/update/activity/log',
                            headers: {'Content-Type': 'application/json'}
                        });
                        /* Check whether the HTTP Request is Successfull or not. */
                        request.then(function (response) {
                        });
                    }
                };
                $scope.docClick = function () {
                    var startTime = new Date();
                    if (!$rootScope.lastUpdatedTime) {// Fire whene date is not defined.
                        //console.log("in if" + $rootScope.lastUpdatedTime)
                        $rootScope.lastUpdatedTime = startTime;
                        $scope.updateActiveLog();
                    }
                    startTime = new Date($rootScope.lastUpdatedTime);
                    var endTime = new Date();
                    var difference = endTime.getTime() - startTime.getTime(); // This will give difference in milliseconds
                    var resultInMinutes = Math.round(difference / 60000);
                    console.log("Document click fire..." + resultInMinutes);
                    $rootScope.lastUpdatedTime = endTime;
                    if (resultInMinutes > 2) {
                        $scope.updateActiveLog();
                    }
                };
                $scope.docClick();
                $rootScope.updateActiveLink = function (link) {
                    $rootScope.activelink = link;
                    //console.log('Link updated');
                };
            }])
        // Index Route controller
        .controller('LinkedInConnectionsController', ['$scope', '$state', '$location', '$http', 'ENV', 'userService', 'connectionsService', '$window', 'Notification', function ($scope, $state, $location, $http, ENV, userService, connectionsService, $window, Notification) {
                console.log("### CONTROLLER: LinkedInConnectionsController ####");
                $rootScope.ogUrl = $location.absUrl();
                var params = $location.search();
                $scope.syncAuth = function () {
                    $scope.syncModalAuth = true;
                    var expiryDays = 60; // 60 days - linkedin access token
                    var expiryTime = 60 * 60 * 24 * expiryDays; // 60 sec * 60 min * 24 h * *60 days
                    var now = Math.round(+new Date() / 1000);
                    if ($scope.user.hasSyncLinkedin && (now - $scope.user.linkedInAccessTokenDate) < expiryTime)
                        $scope.syncModalAuth = false;
                    if ($scope.connections.length > 0)
                        $scope.isLinkedin = true;
                };
                $scope.loadingLinkedin = false;
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                    $scope.syncAuth();
                });
                $scope.connections = connectionsService.connections;
                $scope.$on('handleConnections', function () {
                    $scope.connections = connectionsService.connections;
                    $scope.syncAuth();
                });
                $scope.syncAuth();
                $scope.getConnections = function () {
                    $scope.loadingLinkedin = true;
                    $scope.isLinkedin = true;
                    //console.log(JSON.stringify({"code": params.code ? params.code : 'alreadyLoggedin', 'redirectBase': $scope.app.protocol + "://" + $scope.app.host}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/linkedin/connections',
                        data: {
                            code: params.code ? params.code : 'alreadyLoggedin',
                            redirectBase: $scope.app.protocol + "://" + $scope.app.host
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user.hasSyncLinkedin = true;
                        $scope.connections = response.data.connections;
                        $scope.user.linkedInAccessTokenDate = response.data.linkedInAccessTokenDate;
                        /*angular.forEach(connections, function(v, k) {
                         var allowedIndustries = ["Marketing and Advertising", "Online Media", "Internet", "Wireless", "Gambling & Casinos"];
                         if(allowedIndustries.indexOf(v.industry)>=0){
                         console.log(v);
                         $scope.connections.push(v);
                         //$rootScope.userprofile.connections.push(v);
                         }
                         });*/
                        userService.update($scope.user);
                        connectionsService.update($scope.connections);
                        $scope.loadingLinkedin = false;
                        //console.log($rootScope.userprofile.connections);
                        //});
                    }).catch(function (response) {
                        $scope.loadingLinkedin = false;
                        $scope.syncModalAuth = true;
                        $scope.isLinkedin = false;
                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        if (response.status == 403) {
                            $scope.user.hasSyncLinkedin = false;
                            $scope.user.linkedInAccessTokenDate = 0;
                            userService.update($scope.user);
                        }
                    });
                };
                if (params.code) {
                    $scope.getConnections();
                } else {
                    //console.log("no params.code");
                }
            }])
        // Index Route controller
        .controller('RouteController', ['$scope', '$rootScope' ,'$state', 'userService', '$location', '$cookies', function ($scope, $rootScope, $state, userService, $location, $cookies) {
                console.log("### CONTROLLER: RouteController ####");
                $scope.user = userService.user;
                $rootScope.ogUrl = $location.absUrl();
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                var loggedin = localStorage.getItem('id_token') != null || ($cookies.id_token != 'null' && !angular.isUndefined($cookies.id_token));
                if (!loggedin) {
                    if (angular.isDefined($state.params.invitedby) && $state.params.invitedby == '404')
                    {
                        $state.go('access.404');
                    } else if (angular.isDefined($state.params.invitedby) && $state.params.invitedby.length == 24)
                    {
                        localStorage.setItem('invitedby', $state.params.invitedby);
                        $state.go('access.landing');
                    } else
                        $state.go('access.landing');
                } else
                    $state.go('app.page.feed');
            }])
        // bootstrap controller
        .controller('AccordionDemoCtrl', ['$scope', function ($scope) {
                console.log("### CONTROLLER: AccordionDemoCtrl ####");
                $scope.oneAtATime = true;
                $scope.groups = [
                    {
                        title: 'Accordion group header - #1',
                        content: 'Dynamic group body - #1'
                    },
                    {
                        title: 'Accordion group header - #2',
                        content: 'Dynamic group body - #2'
                    }
                ];
                $scope.items = ['Item 1', 'Item 2', 'Item 3'];
                $scope.addItem = function () {
                    var newItemNo = $scope.items.length + 1;
                    $scope.items.push('Item ' + newItemNo);
                };
                $scope.status = {
                    isFirstOpen: true,
                    isFirstDisabled: false
                };
            }])
        .controller('SliderCtrl', ['$scope', function ($scope) {
                $scope.val = 10;
                $scope.price = ($scope.val * 99) * 0.80;
                $scope.discount = ($scope.val * 99) * 0.20;
                var updateModel = function (val) {
                    $scope.$apply(function () {
                        $scope.val = val;
                        $scope.price = ($scope.val * 99) * 0.80;
                        $scope.discount = ($scope.val * 99) * 0.20;
                    });
                };
                angular.element("#slider").on('slide', function (data) {
                    updateModel(data.value);
                });
            }])
        .controller('AlertDemoCtrl', ['$scope', function ($scope) {
                console.log("### CONTROLLER: AlertDemoCtrl ####");
                $scope.alerts = [];
                /* { type: 'success', msg: 'Well done! You successfully read this important alert message.' },
                 { type: 'info', msg: 'Heads up! This alert needs your attention, but it is not super important.' },
                 { type: 'warning', msg: 'Warning! Best check yo self, you are not looking too good...' },
                 { type: 'danger', msg: 'No results found...' }
                 ];*/
                $scope.addAlert = function () {
                    $scope.alerts.push({type: 'danger', msg: 'Oh snap! Change a few things up and try submitting again.'});
                };
                $scope.closeAlert = function (index) {
                    $scope.alerts.splice(index, 1);
                };
            }])
        .controller('AvatarModalInstanceCtrl', ['$scope', '$uibModalInstance', 'apiURL', '$http', 'ENV', 'userService', 'Notification', function ($scope, $uibModalInstance, apiURL, $http, ENV, userService, Notification) {
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.close = function (data) {
                    $scope.user.avatar = Object.keys(data).length !== 0 ? data : null;
                    //console.log("close avatar modal instance");
                    //console.log(data);
                    /*if (Object.keys(data).length === 0)
                     {
                     $scope.user.avatar = null;
                     } else {
                     $scope.user.avatar = angular.fromJson(data);
                     }*/
                    userService.update($scope.user);
                    /*var request = $http({
                     method: "GET",
                     url: ENV.apiEndpoint + '/api/edit/get/avatar',
                     headers: { 'Content-Type': 'application/json' }
                     });
                     /* Check whether the HTTP Request is Successfull or not. */
                    /*request.success(function (data, status) {
                     $scope.user.avatar = angular.fromJson(data);
                     
                     userService.update($scope.user);
                     })
                     request.error(function (data, status) {
                     //console.log(status);
                     //console.log(data);
                     if(status===401) $state.go('access.signin');
                     Notification.error({title: 'Error ('+status+')', message: 'Ops! Something went wrong...'});
                     })*/
                    $uibModalInstance.close();
                };
            }])
        .controller('AvatarModalCtrl', ['$scope', '$uibModal', '$log', 'ENV', function ($scope, $uibModal, $log, ENV) {
                $scope.open = function (size) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/avatar.html?v=' + ENV.latestUpdate,
                        controller: 'AvatarModalInstanceCtrl',
                        backdrop: 'static',
                        size: size,
                        resolve: {
                            apiURL: function () {
                                return ENV.apiEndpoint;
                            }
                        }
                    });
                };
            }])
        .controller('ProfileCoverModalInstanceCtrl', ['$scope', '$uibModalInstance', 'apiURL', '$http', 'ENV', 'userService', 'Notification', function ($scope, $uibModalInstance, apiURL, $http, ENV, userService, Notification) {
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.close = function (data) {
                    userService.update($scope.user);
                    $scope.user.cover = Object.keys(data).length !== 0 ? data : null;
                    /*if (Object.keys(data).length === 0)
                     {
                     $scope.user.cover = null;
                     console.log('in if');
                     } else {
                     $scope.user.cover = angular.fromJson(data);
                     console.log('in else');
                     }*/
                    userService.update($scope.user);
                    $uibModalInstance.close();
                };
            }])
        .controller('CoverModalCtrl', ['$scope', '$uibModal', 'ENV', function ($scope, $uibModal, ENV) {
                $scope.open = function (size) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/cover.html?v=' + ENV.latestUpdate,
                        controller: 'ProfileCoverModalInstanceCtrl',
                        size: size,
                        backdrop: 'static',
                        resolve: {
                            apiURL: function () {
                                return ENV.apiEndpoint;
                            }
                        }
                    });
                };
            }])
        .controller('CompanyAvatarModalInstanceCtrl', ['$scope', '$uibModalInstance', 'apiURL', 'companyService', function ($scope, $uibModalInstance, apiURL, companyService) {
                $scope.company = companyService.company;
                $scope.$on('handleCompany', function () {
                    $scope.company = companyService.company;
                });
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.close = function (data) {
                    $scope.company.avatar = Object.keys(data).length !== 0 ? data : null;
                    if ($scope.publicCompany) {
                        $scope.publicCompany.avatar = $scope.company.avatar;
                    }
                    /*if (Object.keys(data).length === 0)
                     {
                     $scope.company.avatar = null;
                     } else {
                     $scope.company.avatar = angular.fromJson(data);
                     }*/
                    companyService.update($scope.company);
                    $uibModalInstance.close();
                };
            }])
        .controller('CompanyAvatarModalCtrl', ['$scope', '$uibModal', '$log', 'ENV', function ($scope, $uibModal, $log, ENV) {
                $scope.open = function (size) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/companyAvatar.html?v=' + ENV.latestUpdate,
                        controller: 'CompanyAvatarModalInstanceCtrl',
                        size: size,
                        backdrop: 'static',
                        resolve: {
                            apiURL: function () {
                                return ENV.apiEndpoint;
                            }
                        }
                    });
                };
            }])
        .controller('CompanyCoverModalInstanceCtrl', ['$scope', '$uibModalInstance', 'apiURL', 'companyService', function ($scope, $uibModalInstance, apiURL, companyService) {
                $scope.company = companyService.company;
                $scope.$on('handleCompany', function () {
                    $scope.company = companyService.company;
                });
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.close = function (data) {
                    $scope.company.cover = Object.keys(data).length !== 0 ? data : null;
                    if ($scope.publicCompany) {
                        $scope.publicCompany.cover = $scope.company.cover;
                    }
                    /*if (Object.keys(data).length === 0)
                     {
                     $scope.company.cover = null;
                     } else {
                     $scope.company.cover = angular.fromJson(data);
                     }*/
                    companyService.update($scope.company);
                    $uibModalInstance.close();
                };
            }])
        .controller('CompanyCoverModalCtrl', ['$scope', '$uibModal', '$log', 'ENV', function ($scope, $uibModal, $log, ENV) {
                $scope.open = function (size) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/companyCover.html?v=' + ENV.latestUpdate,
                        controller: 'CompanyCoverModalInstanceCtrl',
                        size: size,
                        backdrop: 'static',
                        resolve: {
                            apiURL: function () {
                                return ENV.apiEndpoint;
                            }
                        }
                    });
                };
            }])
        .controller('LinkedInSyncModalInstance', ['$scope', '$uibModalInstance', 'userService', '$http', 'ENV', 'Notification', '$location', 'connectionsService', '$window', function ($scope, $uibModalInstance, userService, $http, ENV, Notification, $location, connectionsService, $window) {
                $scope.app = {};
                $scope.app.protocol = $location.protocol();
                $scope.app.host = $location.host();
                $scope.loadingLinkedinProfile = false;
                $scope.syncdone = false;
                $scope.firstVisit = function () {
                    if (angular.isUndefined($scope.user.hasEditedOwnProfile) || $scope.user.hasEditedOwnProfile != true) {
                        mixpanel.identify($scope.user.username);
                        //mixpanel.people.increment('Synced LinkedIn Profile');
                        mixpanel.track('Sync LinkedIn Profile', {
                            "Page": 'Edit Profile Page',
                            "Type": 'Auto',
                            "Position": "Auto",
                            "Text": 'Synchronise with LinkedIn',
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                        $http({
                            method: "POST",
                            url: ENV.apiEndpoint + '/api/linkedin/first-profile-edit',
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            $scope.user.hasEditedOwnProfile = true;
                            userService.update($scope.user);
                        }).catch(function (response) {
                        });
                    }
                };
                $scope.cancel = function (text) {
                    $scope.firstVisit();
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.syncAuth = function () {
                    $scope.syncModalAuth = true;
                    var expiryDays = 60; // 60 days - linkedin access token
                    var expiryTime = 60 * 60 * 24 * expiryDays; // 60 sec * 60 min * 24 h * *60 days
                    var now = Math.round(+new Date() / 1000);
                    if ($scope.user.hasSyncLinkedin && (now - $scope.user.linkedInAccessTokenDate) < expiryTime)
                        $scope.syncModalAuth = false;
                };
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                    $scope.syncAuth();
                });
                $scope.syncAuth();
                $scope.close = function (text) {
                    $scope.firstVisit();
                    $uibModalInstance.close();
                };
                $scope.SyncLinkedinProfile = function () {
                    $scope.firstVisit();
                    $scope.loadingLinkedinProfile = true;
                    // PERSONAL INFORMATIONS
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/linkedin/profile',
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data.user;
                        $scope.user.hasSyncLinkedin = true;
                        $scope.connections = response.data.connections;
                        $scope.user.linkedInAccessTokenDate = response.data.linkedInAccessTokenDate;
                        if (angular.isUndefined($scope.user.buyTraffic))
                            $scope.user.buyTraffic = [];
                        $scope.user.buyTraffic.push({});
                        if (angular.isUndefined($scope.user.sellTraffic))
                            $scope.user.sellTraffic = [];
                        $scope.user.sellTraffic.push({});
                        userService.update($scope.user);
                        connectionsService.update($scope.connections);
                        mixpanel.identify($scope.user.username);
                        mixpanel.people.increment('Synced LinkedIn Profile');
                        mixpanel.track('Synced LinkedIn Profile', {
                            "Page": 'Edit Profile Page',
                            "Type": 'Action',
                            "Position": "Popup",
                            "Text": 'Synchronise with LinkedIn',
                            "Success": true,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                        $scope.loadingLinkedinProfile = false;
                        $scope.syncdone = true;
                    }).catch(function (response) {
                        mixpanel.identify($scope.user.username);
                        mixpanel.track('Synced LinkedIn Profile', {
                            "Page": 'Edit Profile Page',
                            "Type": 'Action',
                            "Position": "Popup",
                            "Text": 'Synchronise with LinkedIn',
                            "Error": true,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                        $scope.loadingLinkedinProfile = false;
                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        if (response.status == 403) {
                            $scope.user.hasSyncLinkedin = false;
                            $scope.user.linkedInAccessTokenDate = 0;
                            userService.update($scope.user);
                        }
                    });
                };
                var params = $location.search();
                if (params.code) {
                    $scope.SyncLinkedinProfile();
                }
            }])
        .controller('LinkedInSyncModalCtrl', ['$scope', '$uibModal', '$log', 'ENV', function ($scope, $uibModal, $log, ENV) {
                $scope.open = function (size) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/LinkedInSync.html?v=' + ENV.latestUpdate,
                        controller: 'LinkedInSyncModalInstance',
                        size: size
                    });
                };
            }])
    
        .controller('DeleteAdminModalInstanceCtrl', ['$scope', '$http', '$state', '$uibModalInstance', 'companyService', 'userService', 'deleteID', 'ENV', 'Notification', function ($scope, $http, $state, $uibModalInstance, companyService, userService, deleteID, ENV, Notification) {
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.company = companyService.company;
                $scope.user = userService.user;
                $scope.$on('handleCompany', function () {
                    $scope.company = companyService.company;
                });
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.trackAdmin = function (name, success, status, data) {
                    mixpanel.identify($scope.user.username);
                    if (success) {
                        mixpanel.track(name, {
                            "Page": "Edit Company Page",
                            "Type": "Action",
                            "Text": name,
                            "Success": true,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                    } else {
                        mixpanel.track(name, {
                            "Page": "Edit Company Page",
                            "Type": "Action",
                            "Text": name,
                            "Error": true,
                            "ErrorStatus": status,
                            "ErrorData": data,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                    }
                };
                $scope.close = function () {
                    //console.log(deleteID);
                    if (!deleteID) {
                        Notification.error({title: 'Error', message: 'Select a valid person'});
                    } else if (deleteID && $scope.company.administrators.length <= 1) {
                        Notification.error({title: 'Error', message: 'Each company needs at least one administrator'});
                    } else {
                        $scope.loadingAdmin = true;
                        //console.log(JSON.stringify({"deleteAdmin": deleteID}));
                        $http({
                            method: "POST",
                            url: ENV.apiEndpoint + '/api/edit/company/admin/delete',
                            data: {
                                deleteAdmin: deleteID
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            mixpanel.identify(deleteID);
                            mixpanel.people.set({
                                "Company Exists": true,
                                "Is Admin": false
                            });
                            $scope.company.administrators = response.data;
                            $scope.trackAdmin('Delete Company Admin', true, 200, null);
                            $scope.loadingAdmin = false;
                            Notification.success({title: 'Deleted', message: 'Administrator deleted successfully'});
                        }).catch(function (response) {
                            //console.log(status);
                            //console.log(data);
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                $scope.trackAdmin('Delete Company Admin', false, response.status, response.data);
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            }
                        });
                    }
                    $uibModalInstance.close();
                };
            }])
        .controller('DeleteAdminModalCtrl', ['$scope', '$uibModal', '$log', 'ENV', function ($scope, $uibModal, $log, ENV) {
                $scope.open = function (size, adminID) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/deleteAdmin.html?v=' + ENV.latestUpdate,
                        controller: 'DeleteAdminModalInstanceCtrl',
                        size: size,
                        resolve: {
                            deleteID: function () {
                                return adminID;
                            }
                        }
                    });
                };
            }])
        .controller('DeleteTeammateModalInstanceCtrl', ['$scope', '$uibModalInstance', '$http', '$state', 'companyService', 'userService', 'deleteID', 'ENV', 'Notification', function ($scope, $uibModalInstance, $http, $state, companyService, userService, deleteID, ENV, Notification) {
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.company = companyService.company;
                $scope.user = userService.user;
                $scope.$on('handleCompany', function () {
                    $scope.company = companyService.company;
                });
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.trackAdmin = function (name, success, status, data) {
                    mixpanel.identify($scope.user.username);
                    if (success) {
                        mixpanel.track(name, {
                            "Page": "Edit Company Page",
                            "Type": "Action",
                            "Text": name,
                            "Success": true,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                    } else {
                        mixpanel.track(name, {
                            "Page": "Edit Company Page",
                            "Type": "Action",
                            "Text": name,
                            "Error": true,
                            "ErrorStatus": status,
                            "ErrorData": data,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                    }
                };
                $scope.close = function () {
                    if (!deleteID) {
                        Notification.error({title: 'Error', message: 'Select a valid person'});
                    } else {
                        $scope.loadingTeam = true;
                        $http({
                            method: "POST",
                            url: ENV.apiEndpoint + '/api/edit/company/teammate/delete',
                            data: {
                                deleteTeammate: deleteID
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            $scope.company.employees = response.data;
                            $scope.trackAdmin('Delete Company Teammate', true, 200, null);
                            $scope.loadingTeam = false;
                            Notification.success({title: 'Deleted', message: 'Teammate deleted successfully'});
                        }).catch(function (response) {
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                $scope.trackAdmin('Delete Company Teammate', true, 200, response.data);
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            }
                        });
                    }
                    $uibModalInstance.close();
                };
            }])
        .controller('DeleteTeammateModalCtrl', ['$scope', '$uibModal', 'ENV', function ($scope, $uibModal, ENV) {
                $scope.open = function (size, teammateID) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/deleteTeammate.html?v=' + ENV.latestUpdate,
                        controller: 'DeleteTeammateModalInstanceCtrl',
                        size: size,
                        resolve: {
                            deleteID: function () {
                                return teammateID;
                            }
                        }
                    });
                };
            }])
        .controller('DeleteCompanyPostModalInstanceCtrl', ['$rootScope', '$scope', '$http', '$state', '$uibModalInstance', 'companyFeedService', 'deleteID', 'ENV', 'Notification', 'userService', 'companyService', function ($rootScope, $scope, $http, $state, $uibModalInstance, companyFeedService, deleteID, ENV, Notification, userService, companyService) {
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $http.post(ENV.apiEndpoint + '/api/company', {cache: true}).then(function (response) {
                    $scope.company = response.data;
                });
                $scope.companyfeed = companyFeedService.companyfeed;
                $scope.$on('handleCompanyFeed', function () {
                    $scope.companyfeed = companyFeedService.companyfeed;
                });
                /*$scope.user = userService.user;
                 $scope.$on('handleUser', function () {
                 $scope.user = userService.user;
                 });*/
                $scope.close = function () {
                    //console.log(deleteID);
                    if (!deleteID) {
                        Notification.error({title: 'Error', message: 'Select a valid post'});
                    } else {
                        //$scope.deletePost = trackJs.watch(function(post) {
                        /*
                         var deleteID;
                         if(!angular.isUndefined(post.id)) deleteID = post.id;
                         if(!angular.isUndefined(post._id) && !angular.isUndefined(post._id.$id)) deleteID = post._id.$id;
                         */
                        //console.log(deleteID);
                        $scope.loadingCompanyFeed = true;
                        companyService.update($scope.company);
                        //console.log(JSON.stringify({"deleteID": deleteID}));
                        $http({
                            method: "POST",
                            url: ENV.apiEndpoint + '/api/social/company/post/delete',
                            data: {
                                deleteID: deleteID
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            $rootScope.companyPercentage = response.data.companyPercentage;
                            $scope.company.companyPercentage = response.data.companyPercentage;
                            angular.forEach($scope.companyfeed, function (val, index) {
                                var currentID = null;
                                if ((!angular.isUndefined(val.isLike) && val.isLike) || (!angular.isUndefined(val.isComment) && val.isComment))
                                    currentID = val.updateID;
                                else {
                                    if (!angular.isUndefined(val.id))
                                        currentID = val.id;
                                    if (!angular.isUndefined(val._id) && !angular.isUndefined(val._id.$id))
                                        currentID = val._id.$id;
                                }
                                if (currentID === deleteID) {
                                    $scope.companyfeed.splice(index, 1);
                                }
                            });
                            companyFeedService.update($scope.companyfeed);
                            $scope.loadingCompanyFeed = false;
                        }).catch(function (response) {
                            //console.log(status);
                            //console.log(data);
                            $scope.loadingCompanyFeed = false;
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            }
                        });
                    }
                    // }
                    $uibModalInstance.close();
                };
            }])
        .controller('DeleteCompanyPostModalCtrl', ['$scope', '$uibModal', 'ENV', function ($scope, $uibModal, ENV) {
                $scope.open = function (size, post) {
                    var deleteID = null;
                    if (!angular.isUndefined(post.id))
                        deleteID = post.id;
                    if (!angular.isUndefined(post._id) && !angular.isUndefined(post._id.$id))
                        deleteID = post._id.$id;
                    $uibModal.open({
                        templateUrl: 'tpl/forms/deleteCompanyPost.html?v=' + ENV.latestUpdate,
                        controller: 'DeleteCompanyPostModalInstanceCtrl',
                        size: size,
                        resolve: {
                            deleteID: function () {
                                return deleteID;
                            }
                        }
                    });
                };
            }])
        .controller('DeleteUserPostModalInstanceCtrl', ['$scope', '$uibModalInstance', '$http', '$state', 'userFeedService', 'deleteID', 'ENV', 'Notification', function ($scope, $uibModalInstance, $http, $state, userFeedService, deleteID, ENV, Notification) {
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.userfeed = userFeedService.userfeed;
                $scope.$on('handleUserFeed', function () {
                    $scope.userfeed = userFeedService.userfeed;
                });
                $scope.close = function () {
                    //console.log(deleteID);
                    if (!deleteID) {
                        Notification.error({title: 'Error', message: 'Select a valid post'});
                    } else {
                        //$scope.deletePost = trackJs.watch(function(post) {
                        /*
                         var deleteID;
                         if(!angular.isUndefined(post.id)) deleteID = post.id;
                         if(!angular.isUndefined(post._id) && !angular.isUndefined(post._id.$id)) deleteID = post._id.$id;
                         */
                        //console.log(deleteID);
                        $scope.isFeedLoading = true;
                        //console.log(JSON.stringify({"deleteID": deleteID}));
                        $http({
                            method: "POST",
                            url: ENV.apiEndpoint + '/api/social/user/post/delete',
                            data: {
                                deleteID: deleteID
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            //console.log(status);
                            //console.log(data);
                            angular.forEach($scope.userfeed, function (val, index) {
                                var currentID = null;
                                if ((!angular.isUndefined(val.isLike) && val.isLike) || (!angular.isUndefined(val.isComment) && val.isComment))
                                    currentID = val.updateID;
                                else {
                                    if (!angular.isUndefined(val.id))
                                        currentID = val.id;
                                    if (!angular.isUndefined(val._id) && !angular.isUndefined(val._id.$id))
                                        currentID = val._id.$id;
                                }
                                if (currentID === deleteID) {
                                    $scope.userfeed.splice(index, 1);
                                }
                            });
                            userFeedService.update($scope.userfeed);
                            $scope.isFeedLoading = false;
                        }).catch(function (response) {
                            //console.log(status);
                            //console.log(data);
                            $scope.isFeedLoading = false;
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            }
                        });
                    }
                    ;
                    // }
                    $uibModalInstance.close();
                };
            }])
        .controller('DeleteUserPostModalCtrl', ['$scope', '$uibModal', 'ENV', function ($scope, $uibModal, ENV) {
                $scope.open = function (size, post) {
                    var deleteID = null;
                    if ((!angular.isUndefined(post.isLike) && post.isLike) || (!angular.isUndefined(post.isComment) && post.isComment))
                        deleteID = post.updateID;
                    else {
                        if (!angular.isUndefined(post.id))
                            deleteID = post.id;
                        if (!angular.isUndefined(post._id) && !angular.isUndefined(post._id.$id))
                            deleteID = post._id.$id;
                    }
                    $uibModal.open({
                        templateUrl: 'tpl/forms/deleteUserPost.html?v=' + ENV.latestUpdate,
                        controller: 'DeleteUserPostModalInstanceCtrl',
                        size: size,
                        resolve: {
                            deleteID: function () {
                                return deleteID;
                            }
                        }
                    });
                };
            }])
        .controller('PrivacyModalInstanceCtrl', ['$scope', '$uibModalInstance', function ($scope, $uibModalInstance) {
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.close = function () {
                    $uibModalInstance.close();
                };
            }])
        .controller('PrivacyModalCtrl', ['$scope', '$uibModal', 'ENV', function ($scope, $uibModal, ENV) {
                $scope.open = function (size) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/privacy.html?v=' + ENV.latestUpdate,
                        controller: 'PrivacyModalInstanceCtrl',
                        size: size
                    });
                };
            }])
        .controller('MessageQuotaExceededInstanceCtrl', ['$scope', '$uibModalInstance', function ($scope, $uibModalInstance) {
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.close = function () {
                    $uibModalInstance.close();
                };
            }])
        .controller('MessageQuotaExceededModalCtrl', ['$scope', '$uibModal', 'ENV', function ($scope, $uibModal, ENV) {
                $scope.open = function (size) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/MessageQuotaExceeded.html?v=' + ENV.latestUpdate,
                        controller: 'MessageQuotaExceededInstanceCtrl',
                        size: size
                    });
                };
            }])
        .controller('UsersListInstanceCtrl', ['$scope', '$uibModalInstance', 'list', 'action', 'ENV', function ($scope, $uibModalInstance, list, action, ENV) {
                $scope.users = list;
                $scope.action = action;
                $scope.app = {'baseUrl': ENV.baseUrl};
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
            }])
        .controller('404Controller', function () {
            console.log("### CONTROLLER: 404Controller ####");
            //$scope.isError404 = true;
        })
        .controller('LinkedInWidgetCtrl', ['$scope', '$http', '$state', 'connectionsService', 'ENV', 'userService', function ($scope, $http, $state, connectionsService, ENV, userService) {
                console.log("### CONTROLLER: LinkedInWidgetCtrl ####");
                //$scope.showBasicButton = true;
                $scope.alreadyRequested = false;
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.syncAuth = function () {
                    $scope.syncModalAuth = true;
                    var expiryDays = 60; // 60 days - linkedin access token
                    var expiryTime = 60 * 60 * 24 * expiryDays; // 60 sec * 60 min * 24 h * *60 days
                    var now = Math.round(+new Date() / 1000);
                    if ($scope.user.hasSyncLinkedin && (now - $scope.user.linkedInAccessTokenDate) < expiryTime) {
                        $scope.syncModalAuth = false;
                        if ((angular.isUndefined($scope.connections) || angular.isUndefined($scope.connections.length) || $scope.connections.length == 0) && !$scope.alreadyRequested) {
                            //console.log(JSON.stringify({"code": 'alreadyLoggedin', "redirectBase": $scope.app.protocol + "://" + $scope.app.host}));
                            $http({
                                method: "POST",
                                url: ENV.apiEndpoint + '/api/linkedin/connections',
                                data: {
                                    code: 'alreadyLoggedin',
                                    redirectBase: $scope.app.protocol + "://" + $scope.app.host
                                },
                                headers: {'Content-Type': 'application/json'}
                            }).then(function (response) {
                                //console.log(status);
                                //console.log(data);
                                $scope.user.hasSyncLinkedin = true;
                                $scope.connections = response.data.connections;
                                $scope.user.linkedInAccessTokenDate = response.data.linkedInAccessTokenDate;
                                $scope.isLinkedin = true;
                                userService.update($scope.user);
                                connectionsService.update($scope.connections);
                                //$scope.showBasicButton = false;
                                $scope.alreadyRequested = true;
                            }).catch(function (response) {
                                //console.log(status);
                                //console.log(data);
                                $scope.alreadyRequested = true;
                            });
                        }
                    }
                };
                $scope.connections = connectionsService.connections;
                $scope.$on('handleConnections', function () {
                    $scope.connections = connectionsService.connections;
                    $scope.totalItems = angular.isUndefined($scope.connections) ? 0 : $scope.connections.length;
                    $scope.syncAuth();
                });
                $scope.syncAuth();
                $scope.track = function (name, type) {
                    mixpanel.identify($scope.user.username);
                    //mixpanel.people.increment(name);
                    mixpanel.track(name, {
                        "Page": $state.current.url,
                        "Type": type,
                        "Position": "Widget",
                        "Text": name,
                        "Username": $scope.user.username,
                        "$email": $scope.user.email,
                        "Company": $scope.user.company,
                        "Job Title": $scope.user.jobTitle,
                        "Role": $scope.user.companyType,
                        "Subrole": $scope.user.companySubType
                    });
                };
            }])

        .controller('notificationModalInstanceCtrl', ['$scope', '$http', '$state', '$window', 'ENV', '$uibModalInstance', 'Notification', 'userService', function ($scope, $http, $state, $window, ENV, $uibModalInstance, Notification, userService) {
                console.log("### CONTROLLER: conformCreateCompanyModalInstanceCtrl ####");
                $scope.app = {'baseUrl': ENV.baseUrl};
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
                $scope.viewAlert = function (alert) {
                    //console.log(alert);
                    //console.log(JSON.stringify({"alertID": alert.id}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/alerts/read',
                        data: {
                            alertID: alert.id
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user.alerts = response.data;
                        userService.update($scope.user);
                        $uibModalInstance.dismiss('cancel');
                        switch (alert.type) {
                            case 1:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.manageconnections', {'type': 'requested'});
                                } else {
                                    $state.go('app.page.manageconnections', {'type': 'requested'});
                                }
                                break;
                            case 2:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.connections');
                                } else {
                                    $state.go('app.page.connections');
                                }
                                break;
                            case 3:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.visitors');
                                } else {
                                    $state.go('app.page.visitors');
                                }
                                break;
                            case 4:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.company');
                                } else {
                                    $state.go('app.page.company');
                                }
                                break;
                            case 5:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                                } else {
                                    $state.go('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                                }
                                break;
                            case 6:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.company/public', {'companyusername': alert.username});
                                } else {
                                    $state.go('app.page.company/public', {'companyusername': alert.username});
                                }
                                break;
                            case 7: //like action
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.public', {'username': alert.username});
                                } else {
                                    $state.go('app.page.public', {'username': alert.username});
                                }
                                break;
                            case 8: //follow action
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.public', {'username': alert.username});
                                } else {
                                    $state.go('app.page.followers');
                                }
                                break;
                            case 9:  //offer details action
                            case 10:  //offer details action - conversation (allow us to limit the notifications)
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.offers.details', {'offerid': alert.id.split('-')[0]});
                                } else {
                                    $state.go('app.offers.details', {'offerid': alert.id.split('-')[0]});
                                }
                                break;
                            case 11: // my replies - offer
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.offers.myreplies');
                                } else {
                                    $state.go('app.offers.myreplies', {}, {reload: true});
                                }
                                break;
                            case 12: //Profile complition 
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                                } else {
                                    $state.go('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                                }
                                break;
                            case 13: //Applied on job offer
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.recruits.manage');
                                } else {
                                    $state.go('app.recruits.manage');
                                }
                                break;
                            case 14: //Posted offer matched filter
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('jobShowcase', {company: alert.username, slug: alert.slug});
                                } else {
                                    $state.go('jobShowcase', {company: alert.username, slug: alert.slug});
                                }
                                break;
                            case 15: //Has posted an ans on your question
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.qdetails', {slug: alert.slug});
                                } else {
                                    $state.go('app.page.qdetails', {slug: alert.slug});
                                }
                                break;
                            default:
                                //$state.go('app.page.public', { 'username': alert.username });
                                break;
                        }
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                    });
                };
            }])
        .controller('AlertsCtrl', ['$scope', '$http', '$state', '$window', '$uibModal', 'ENV', 'userService', 'Notification', function ($scope, $http, $state, $window, $uibModal, ENV, userService, Notification) {
                console.log("### CONTROLLER: AlertsCtrl ####");
                $scope.app = {'baseUrl': ENV.baseUrl};
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.resetAlerts = function () {
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/alerts/resets',
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user.alertsNotifications = 0;
                        userService.update($scope.user);
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                    });
                    mixpanel.identify($scope.user.username);
                    mixpanel.people.increment('Notifications');
                    mixpanel.track('Notifications', {
                        "Page": $state.current.url,
                        "Type": "Action",
                        "Text": 'Notifications',
                        "Username": $scope.user.username,
                        "$email": $scope.user.email,
                        "Company": $scope.user.company,
                        "Job Title": $scope.user.jobTitle,
                        "Role": $scope.user.companyType
                    });
                };
                $scope.viewAlertPopup = function () {
                    $uibModal.open({
                        templateUrl: 'tpl/blocks_general/notifications.html?v=' + ENV.latestUpdate,
                        controller: 'notificationModalInstanceCtrl',
                        size: 'sm'
                    });
                };
                $scope.viewAlert = function (alert) {
                    //console.log(alert);
                    //console.log(JSON.stringify({"alertID": alert.id}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/alerts/read',
                        data: {
                            alertID: alert.id
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user.alerts = response.data;
                        userService.update($scope.user);
                        switch (alert.type) {
                            case 1:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.manageconnections', {'type': 'requested'});
                                } else {
                                    $state.go('app.page.manageconnections', {'type': 'requested'});
                                }
                                break;
                            case 2:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.connections');
                                } else {
                                    $state.go('app.page.connections');
                                }
                                break;
                            case 3:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.visitors');
                                } else {
                                    $state.go('app.page.visitors');
                                }
                                break;
                            case 4:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.company');
                                } else {
                                    $state.go('app.page.company');
                                }
                                break;
                            case 5:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                                } else {
                                    $state.go('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                                }
                                break;
                            case 6:
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.company/public', {'companyusername': alert.username});
                                } else {
                                    $state.go('app.page.company/public', {'companyusername': alert.username});
                                }
                                break;
                            case 7: //like action
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.public', {'username': alert.username});
                                } else {
                                    $state.go('app.page.public', {'username': alert.username});
                                }
                                break;
                            case 8: //follow action
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.public', {'username': alert.username});
                                } else {
                                    $state.go('app.page.followers');
                                }
                                break;
                            case 9:  //offer details action
                            case 10:  //offer details action - conversation (allow us to limit the notifications)
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.offers.details', {'offerid': alert.id.split('-')[0]});
                                } else {
                                    $state.go('app.offers.details', {'offerid': alert.id.split('-')[0]});
                                }
                                break;
                            case 11: // my replies - offer
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.offers.myreplies');
                                } else {
                                    $state.go('app.offers.myreplies', {}, {reload: true});
                                }
                                break;
                            case 12: //Profile complition 
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                                } else {
                                    $state.go('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                                }
                                break;
                            case 13: //Applied on job offer
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.recruits.manage');
                                } else {
                                    $state.go('app.recruits.manage');
                                }
                                break;
                            case 14: //Posted offer matched filter
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('jobShowcase', {company: alert.username, slug: alert.slug});
                                } else {
                                    $state.go('jobShowcase', {company: alert.username, slug: alert.slug});
                                }
                                break;
                            case 15: //Has posted an ans on your question
                                if ($state.current.name === 'invites') {
                                    $window.location.href = $state.href('app.page.qdetails', {slug: alert.slug});
                                } else {
                                    $state.go('app.page.qdetails', {slug: alert.slug});
                                }
                                break;
                            default:
                                //$state.go('app.page.public', { 'username': alert.username });
                                break;
                        }
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                    });
                };
            }])
        .controller('MapCtrl', ['$scope', 'companyService', function ($scope, companyService) {
                console.log("### CONTROLLER: MapCtrl ####");
                var lat = 35.784;
                var lng = -78.670;
                var zoom = 3;
                $scope.mapOK = false;
                $scope.myMarkers = [];
                $scope.updateLatLng = function () {
                    if (!angular.isUndefined($scope.company)) {
                        if ($scope.company.lat != null)
                            lat = $scope.company.lat;
                        if ($scope.company.lng != null)
                            lng = $scope.company.lng;
                        if ($scope.company.lat != null && $scope.company.lng != null) {
                            zoom = 5;
                        }
                    }
                };
                $scope.company = companyService.company;
                $scope.updateLatLng();
                $scope.$on('handleCompany', function () {
                    //$scope.company = companyService.company;
                    $scope.updateLatLng();
                });
                $scope.mapOptions = {
                    center: new google.maps.LatLng(lat, lng),
                    zoom: zoom,
                    disableDefaultUI: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                $scope.$watch('myMap', function (map) {
                    if (zoom != 3) {
                        $scope.myMarkers.push(new google.maps.Marker({
                            map: map,
                            position: new google.maps.LatLng(lat, lng)
                        }));
                    }
                    if (map)
                        google.maps.event.trigger($scope.myMap, "resize");
                });
            }])
        .controller('TypeaheadSearchCtrl', ['$rootScope', '$scope', '$state', 'queryService', 'searchService', '$http', '$q', 'ENV', 'userService', '$cookies', 'AuthService', 'companyService', function ($rootScope, $scope, $state, queryService, searchService, $http, $q, ENV, userService, $cookies, AuthService, companyService) {
                console.log("### CONTROLLER: TypeaheadSearchCtrl ####");
                $scope.user = userService.user;
                $scope.countTo = $scope.user.profilePercentage;
                $scope.countFrom = 0;
                $scope.progressValue = $scope.user.profilePercentage;
                if ($scope.user.profilePercentage > 100) {
                    $scope.user.profilePercentage = 100;
                }
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                    if ($scope.user && $scope.user.profilePercentage > 100) {
                        $scope.user.profilePercentage = 100;
                    }
                });
                //$scope.selected = undefined;
                $scope.query = queryService.query;
                if (angular.isUndefined($scope.query.profileType))
                    $scope.query.profileType = 'people';
                $scope.loadingSearch = false;
                $scope.onChangeType = function (val) {
                    $scope.query.profileType = val;
                    queryService.update($scope.query);
                };
                $scope.$on('handleQuery', function () {
                    $scope.query = queryService.query;
                    $scope.loadingSearch = queryService.loadingSearch;
                });
                $scope.canceler = $q.defer();
                $scope.autocomplete = function (value) {
                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.query.headerString = value;
                    return  $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/public/search',
                        data: {
                            query: value
                        },
                        timeout: $scope.canceler.promise,
                    }).then(function (response) {
                        var data = response.data.slice(0, 5);
                        data.push({type: null, query: value});
                        return data;
                    });
                };

                $scope.itemSelect = function ($item, $model, $label) {
                    var query = $scope.query.headerString;
                    $scope.query.headerString = '';
                    if (!$item || !$item.type) {
                        $state.go('app.page.publicsearch', {'type': 'all', 'query': $item && $item.query ? $item.query : query});
                    } else if ($item.type === 'company') {
                        $state.go('app.page.company/public', {'companyusername': $item.username});
                    } else if ($item.type === 'people') {
                        $state.go('app.page.public', {'username': $item.username});
                    }
                };

                /*$scope.uautocomplete = trackJs.watch(function(value){
                 return $http.post(ENV.apiEndpoint + '/api/public/autocomplete', { query : $scope.query})
                 .then(function(response){
                 return response.data;
                 });
                 };
                 $scope.autocomplete = trackJs.watch(function(value){
                 return $http.post(ENV.apiEndpoint + '/api/public/autocomplete', { query : $scope.query})
                 .then(function(response){
                 return response.data;
                 });
                 };
                 $scope.autocomplete = trackJs.watch(function() {
                 if($scope.query.headerString){
                 //console.log($scope.query);
                 var request = $http({
                 method: "POST",
                 url: ENV.apiEndpoint + '/api/public/autocomplete',
                 //url: ENV.apiEndpoint + '/api/simpleSearch',
                 data: {
                 query:   $scope.query
                 },
                 headers: { 'Content-Type': 'application/json' }
                 });
                 request.success(function (data, status) {
                 console.log("autocomplete");
                 console.log(status);
                 console.log(data);
                 if($scope.query.profileType=='companies'){
                 $scope.cautocomplete = data;
                 autocompleteService.cUpdate($scope.cautocomplete);
                 }else{
                 $scope.uautocomplete = data;
                 autocompleteService.uUpdate($scope.uautocomplete);
                 }
                 })
                 request.error(function (data, status) {
                 //console.log("autocomplete");
                 //console.log(status);
                 //console.log(data);
                 if(status===401) $state.go('access.signin');
                 Notification.error({title: 'Error ('+status+')', message: 'Ops! Something went wrong...'});
                 })
                 }
                 };*/
                $scope.trackHeader = function (name, text) {
                    var params = localStorage.getItem('params');
                    if (!params)
                        params = $cookies.params;
                    mixpanel.identify($scope.user.username);
                    mixpanel.people.increment(name);
                    mixpanel.people.set({
                        "Response Rate": $scope.user.responseRate
                    });
                    if (!angular.isUndefined(params)) {
                        params = JSON.parse(params);
                        mixpanel.track(name, {
                            "Page": $state.current.url,
                            "Type": "Action",
                            "Text": text,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Campaign ID": params.c,
                            "Campaign Version": params.v
                        });
                    } else {
                        mixpanel.track(name, {
                            "Page": $state.current.url,
                            "Type": "Action",
                            "Text": text,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                    }
                };
                $scope.trackLink = function (name, text) {
                    var params = localStorage.getItem('params');
                    if (!params)
                        params = $cookies.params;
                    mixpanel.identify($scope.user.username);
                    if (!angular.isUndefined(params)) {
                        params = JSON.parse(params);
                        mixpanel.track(name, {
                            "Page": $state.current.url,
                            "Type": "Link",
                            "Text": text,
                            "Campaign ID": params.c,
                            "Campaign Version": params.v
                        });
                    } else {
                        mixpanel.track(name, {
                            "Page": $state.current.url,
                            "Type": "Link",
                            "Text": text
                        });
                    }
                };
                $scope.viewProfile = function (username) {
                    //console.log("VIEW PROFILE LOG:");
                    //console.log(username);
                    if ($scope.query.profileType == 'people') {
                        // PEOPLE
                        if (!username)
                            $state.go('app.page.search');
                        else
                            $state.go('app.page.public', {'username': username});
                    } else {
                        // COMPANY
                        if (!username)
                            $state.go('app.page.search');
                        else
                            $state.go('app.page.company/public', {'companyusername': username});
                    }
                };
                $scope.advancedSearch = function () {
                    $scope.loadingSearch = true;
                    //console.log('Advanced Search:');
                    //console.log($scope.query.headerString);
                    if (!angular.isUndefined($scope.query.headerString)) {
                        if ($scope.query.headerString.length > 0)
                            $scope.query.string = $scope.query.headerString;
                    }
                    $scope.query.headerString = '';
                    queryService.update($scope.query);
                    queryService.updateResults(0, {}, '', false, $scope.query.profileType, true);
                    if (angular.isUndefined($scope.query.skip))
                        $scope.query.skip = 0;
                    $scope.query.skip = 0;
                    $scope.query.companyType = 'All';
                    if ($scope.query.string == '' || angular.isUndefined($scope.query.string))
                        $scope.query.explore = true;
                    else
                        $scope.query.explore = false;
                    searchService.search($scope);
                    $state.go('app.page.search');
                };
                $scope.explore = function () {
                    //$scope.loadingSearch = true;
                    //$scope.stopscrollloading = false;
                    //console.log($state.current.url);
                    $scope.trackHeader("Explore", "Explore");
                    $scope.query = {};
                    $scope.query.profileType = 'people';
                    $scope.query.companyType = 'All';
                    $scope.query.explore = true;
                    queryService.update($scope.query);
                    queryService.updateResults(0, {}, '', false, $scope.query.profileType, true);
                    //console.log('Explore Search');
                    if (angular.isUndefined($scope.query.skip))
                        $scope.query.skip = 0;
                    $scope.query.skip = 0;
                    searchService.search($scope);
                    $state.go('app.page.search');
                };
                $scope.logout = function () {
                    $scope.user = {};
                    userService.update($scope.user);
                    AuthService.logout();
                    $state.go('access.signin');
                };
                $scope.removeAccents = function () {
                    var accent = [
                        /[\300-\306]/g, /[\340-\346]/g, // A, a
                        /[\310-\313]/g, /[\350-\353]/g, // E, e
                        /[\314-\317]/g, /[\354-\357]/g, // I, i
                        /[\322-\330]/g, /[\362-\370]/g, // O, o
                        /[\331-\334]/g, /[\371-\374]/g, // U, u
                        /[\321]/g, /[\361]/g, // N, n
                        /[\307]/g, /[\347]/g // C, c
                    ],
                            noaccent = ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n', 'C', 'c'];
                    var temp = $scope.query.string;
                    if (!angular.isUndefined(temp)) {
                        for (var i = 0; i < accent.length; i++) {
                            temp = temp.replace(accent[i], noaccent[i]);
                        }
                    }
                    $scope.query.string = temp;
                    queryService.update($scope.query);
                }
                ;
            }])

        .controller('BrowseCtrl', ['$rootScope', '$scope', '$state', 'queryService', 'searchService', '$http', '$q', 'ENV', 'userService', '$cookies', 'AuthService', 'companyService', function ($rootScope, $scope, $state, queryService, searchService, $http, $q, ENV, userService, $cookies, AuthService, companyService) {
                console.log("### CONTROLLER: BrowseCtrl ####");

                $scope.browseCompaniesByMarkets = function (market) {
                    $state.go('app.page.companies', {m: market});
                };

                $scope.browsePeoplesByLocation = function (location) {

                };

            }])
        //PUBLIC SEARCH CONTROLLER for companies and profile when user click on search for "term" top search bar
        .controller('publicSearchController', ['$scope', '$rootScope', '$state', '$stateParams', '$http', 'userService', 'connectionsService', '$location', 'ENV', 'Notification', '$cookies', '$filter', '$q', '$uibModal', '$sce', function ($scope, $rootScope, $state, $stateParams, $http, userService, connectionsService, $location, ENV, Notification, $cookies, $filter, $q, $uibModal, $sce) {
                console.log('### CONTROLLER: PublicSearchController ###');
                $rootScope.ogUrl = $location.absUrl();
                $scope.canceler = $q.defer();
                $scope.count = 0;
                $scope.list = [];
                $scope.$parent.$parent.$parent.app.settings.container = false;
                $scope.query = {
                    skip: 0,
                    type: 'people',
                    query: ''
                };

                $scope.query.type = $stateParams.type;
                $scope.query.query = $stateParams.query;

                if ($stateParams.type !== 'company' && $stateParams.type !== 'people' && $stateParams.type !== 'all') {
                    $state.go('app.page.feed');
                }

                if ($stateParams.type == 'all') {
                    $rootScope.pageTitle = "Search \"" + $stateParams.query + "\"  - Mobintouch";
                    $rootScope.pageDescription = $stateParams.query + " results in Mobintouch's database of thousands people and companies. Filter by location, team, and more.";
                } else if ($stateParams.type == 'people') {
                    $rootScope.pageTitle = "Search \"" + $stateParams.query + "\" in people - Mobintouch";
                    $rootScope.pageDescription = $stateParams.query + " results in Mobintouch's database of thousands people. Filter by location, team, and more.";
                } else if ($stateParams.type == 'company') {
                    $rootScope.pageTitle = "Search \"" + $stateParams.query + "\" in companies - Mobintouch";
                    $rootScope.pageDescription = $stateParams.query + " results in Mobintouch's database of thousands companies. Filter by location, team, and more.";
                }

                $scope.updateType = function (type) {

                    $state.go($state.current.name, {type: type, query: $scope.query.query});
                };

                $scope.loadList = function () {
                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.loadingList = true;
                    var type = $scope.query.type;
                    if (type == 'people') {
                        type = 'profile';
                    }
                    $http({
                        method: 'GET',
                        url: ENV.apiEndpoint + '/api/public/search/' + type + '/' + $scope.query.query + '/' + $scope.query.skip,
                        cache: true,
                        timeout: $scope.canceler.promise,
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        if ($scope.query.skip > 0) {
                            $scope.list = $scope.list.concat(response.data.list);
                        } else {
                            $scope.list = response.data.list;
                        }
                        $scope.count = response.data.count;
                        if (response.data.count > $scope.list.length) {
                            $scope.isLoadableList = true;
                        } else {
                            $scope.isLoadableList = false;
                        }
                        $scope.loadingList = false;
                        $scope.query.skip++;
                    }).catch(function (response) {
                        if (response.status !== -1) {
                            $scope.loadingList = false;
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };

                $scope.connect = function (connection) {
                    connection.isRequested = true;
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/connection/new',
                        data: {
                            userId: connection.id
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        if (angular.isUndefined($scope.user.inTouch) || $scope.user.inTouch == null)
                        {
                            $scope.user.inTouch = [response.data];
                        } else {
                            var object = angular.extend({}, $scope.user.inTouch, response.data);
                            $scope.user.inTouch = object;
                        }
                        userService.update($scope.user);
                        connection.isRequested = true;
                    }).catch(function (response) {
                        connection.isRequested = false;
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });

                    /*$uibModal.open({
                     templateUrl: 'tpl/forms/connection.html?v=' + ENV.latestUpdate,
                     controller: function ($scope, $uibModalInstance, userService) {
                     $scope.connection = connection;
                     $scope.cancel = function () {
                     $uibModalInstance.dismiss('cancel');
                     };
                     $scope.close = function (data) {
                     $http({
                     method: "POST",
                     url: ENV.apiEndpoint + '/api/connection/new',
                     data: {
                     userId: connection.id
                     },
                     headers: {'Content-Type': 'application/json'}
                     }).then(function (response) {
                     if (angular.isUndefined($scope.user.inTouch) || $scope.user.inTouch == null)
                     {
                     $scope.user.inTouch = [response.data];
                     } else {
                     var object = angular.extend({}, $scope.user.inTouch, response.data);
                     $scope.user.inTouch = object;
                     }
                     userService.update($scope.user);
                     $scope.connection.isRequested = true;
                     }).catch(function (response) {
                     if (response.status === 401)
                     $state.go('access.signin');
                     else {
                     Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                     }
                     });
                     $uibModalInstance.close();
                     };
                     },
                     size: 'sm',
                     scope: $scope
                     });*/
                };

                $scope.disconnect = function (connection) {
                    $scope.connection = connection;
                    $uibModal.open({
                        templateUrl: 'tpl/forms/deleteConnection.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance) {
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                            $scope.close = function () {
                                $http({
                                    method: "POST",
                                    url: ENV.apiEndpoint + '/api/connection/delete',
                                    data: {
                                        userId: connection.id
                                    },
                                    headers: {'Content-Type': 'application/json'}
                                }).then(function (response) {
                                    angular.forEach($scope.user.inTouch, function (v, k) {
                                        if (v['id'] == connection.id)
                                            $scope.user.inTouch[k]['status'] = 0;
                                    });
                                    userService.update($scope.user);
                                    $scope.connection.isConnected = false;
                                }).catch(function (response) {
                                    if (response.status === 401) {
                                        $state.go('access.signin');
                                    } else {
                                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                    }
                                });
                                $uibModalInstance.close();
                            };
                        },
                        size: 'sm',
                        scope: $scope
                    });
                };

                $scope.accept = function (connection) {
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/connection/accept', data: {
                            userId: connection.id
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        var totalInTouch = 0;
                        angular.forEach($scope.user.inTouch, function (v, k) {                             //console.log(v);
                            if (v['id'] == response.data.id) {
                                $scope.user.inTouch[k]['status'] = 3;
                            }
                        });
                        userService.update($scope.user);
                        connection.isRequest = false;
                        connection.isConnected = true;
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };

                $scope.ignore = function (connection) {
                    var request = $http({method: "POST",
                        url: ENV.apiEndpoint + '/api/connection/decline',
                        data: {
                            userId: connection.id
                        },
                        headers: {'Content-Type': 'application/json'}
                    });
                    request.then(function (response) {
                        angular.forEach($scope.user.inTouch, function (v, k) {                             //console.log(v);
                            if (v['id'] == connection.id)
                                $scope.user.inTouch[k]['status'] = 0;
                        });
                        userService.update($scope.user);
                        connection.isRequest = false;
                    });
                    request.catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };

                $scope.viewMutualConnections = function (connection) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/mutualConnections.html?v=' + ENV.latestUpdate,
                        controller: function ($uibModalInstance) {
                            $scope.mConnections = connection.mutualConnections; //mutualConnections;
                            $scope.connectionWith = connection.name + " " + connection.lastname;
                            $scope.messageType = 'onFly';
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };

                            $scope.mutualSendMessage = function () {
                                $uibModalInstance.close();
                                $scope.sendMessage(connection);
                            };
                        },
                        size: 'md',
                        windowClass: 'centered-modal ',
                        scope: $scope
                    });
                };

                $scope.sendMessage = function (connection) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/message.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, Notification) {
                            $scope.messageTo = connection.name + " " + connection.lastname;
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };

                            $scope.selectedAttachedFiles = [];
                            $scope.fileUpload = function (elem) {
                                $scope.selectedAttachedFiles.push(elem.files[0]);
                                $scope.$apply();
                            };

                            $scope.removeAttachment = function (attachment) {
                                var index = $scope.selectedAttachedFiles.indexOf(attachment);
                                $scope.selectedAttachedFiles.splice(index, 1);
                            };

                            $scope.postMessage = function (mail) {
                                if (mail.$invalid) {
                                    return;
                                }
                                $scope.sendingMessage = true;
                                $http({
                                    method: "POST",
                                    url: ENV.apiEndpoint + '/api/mail/send',
                                    data: {
                                        mail: {
                                            'username': connection.username,
                                            'subject': $scope.subject,
                                            'content': $scope.message
                                        }
                                    },
                                    headers: {'Content-Type': 'application/json'}
                                }).then(function (response) {
                                    if (response.data.quotaExceeded) {
                                        $scope.isSendingLoading = false;
                                        Notification.error({title: 'Quota exceeded!', message: 'You have reached the quota of ' + response.data.count + ' mails within 30 days, contact the support to get more messages.'});
                                    } else {
                                        $scope.isSendingLoading = false;
                                        Notification.success({title: 'Congratulations!', message: 'Message sent!'});
                                        $uibModalInstance.close();
                                    }
                                }).catch(function (response) {
                                    if (response.status === 401)
                                        $state.go('access.signin');
                                    else {
                                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                        $scope.isSendingLoading = false;
                                    }
                                });

                            };
                        },
                        size: 'md',
                        windowClass: 'centered-modal',
                        scope: $scope
                    });
                };


            }])

        .controller('DatepickerDemoCtrl', ['$scope', function ($scope) {
                console.log("### CONTROLLER: DatepickerDemoCtrl ####");
                $scope.today = function () {
                    $scope.dt = new Date();
                };
                $scope.today();
                $scope.clear = function () {
                    $scope.dt = null;
                };
                // Disable weekend selection
                $scope.disabled = function (date, mode) {
                    return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
                };
                $scope.toggleMin = function () {
                    $scope.minDate = $scope.minDate ? null : new Date();
                };
                $scope.toggleMin();
                $scope.open = function ($event) {
                    $event.preventDefault();
                    $event.stopPropagation();
                    $scope.opened = true;
                };
                $scope.dateOptions = {
                    formatYear: 'yy',
                    startingDay: 1,
                    class: 'datepicker'
                };
                $scope.initDate = new Date();
                $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
                $scope.format = $scope.formats[0];
            }])
        .controller('TimepickerDemoCtrl', ['$scope', function ($scope) {
                console.log("### CONTROLLER: TimepickerDemoCtrl ####");
                $scope.mytime = new Date();
                $scope.hstep = 1;
                $scope.mstep = 15;
                $scope.options = {
                    hstep: [1, 2, 3],
                    mstep: [1, 5, 10, 15, 25, 30]
                };
                $scope.ismeridian = true;
                $scope.toggleMode = function () {
                    $scope.ismeridian = !$scope.ismeridian;
                };
                $scope.update = function () {
                    var d = new Date();
                    d.setHours(14);
                    d.setMinutes(0);
                    $scope.mytime = d;
                };
                $scope.changed = function () {
                    ////console.log('Time changed to: ' + $scope.mytime);
                };
                $scope.clear = function () {
                    $scope.mytime = null;
                };
            }])
        // Form controller
        .controller('FormDemoCtrl', ['$scope', function ($scope) {
                console.log("### CONTROLLER: FormDemoCtrl ####");
            }])
        // Form controller
        .controller('RightSidebarController', ['$scope', '$state', '$rootScope', 'userService', '$http', 'ENV', function ($scope, $state, $rootScope, userService, $http, ENV) {
                console.log("### CONTROLLER: RightSidebarController ####");
                $scope.track = function (name, text) {
                    mixpanel.identify($scope.user.username);
                    mixpanel.people.increment(name);
                    mixpanel.track(name, {
                        "Page": $state.current.url,
                        "Type": "Link",
                        "Text": text,
                        "Username": $scope.user.username,
                        "$email": $scope.user.email,
                        "Company": $scope.user.company,
                        "Job Title": $scope.user.jobTitle,
                        "Role": $scope.user.companyType,
                        "Subrole": $scope.user.companySubType
                    });
                };
                // GET LAST VERSION
                //$http.get(ENV.baseUrl+'/json/version.json?'+Date.now(), { cache: false, skipAuthorization: true }).
                //success(function(data) {
                $http.get(ENV.baseUrl + '/json/version.json?' + Date.now(), {cache: false, skipAuthorization: true}).then(function (response) {
                    $rootScope.lastVersion = response.data.version;
                });
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                if ($scope.user.username && ($scope.user.version != $rootScope.lastVersion)) {
                    $rootScope.alerts = [
                        {type: 'danger', msg: 'Mobintouch has new features! Please click here in order to reload your page and get access to them. If this is not enough, please type : CTR + F5 (Windows) or CMD + R (OS X).'}
                    ];
                } else {
                    $rootScope.alerts = [];
                }
                if (!$scope.user.emailValidation && $scope.user.username && $scope.user.emailValidation != null) {
                    $rootScope.alerts.push({type: 'success', msg: "Please confirm your email address. We've sent a verification email, check your mailbox and spams." + " <a href='emailvalidation'> If you didn‘t receive your verification email please click here. </a> "});
                }
            }])
        // Form controller
        .controller('ClearCacheController', ['$scope', '$state', 'Notification', '$http', 'ENV', 'userService', 'lastVersion', '$rootScope', function ($scope, $state, Notification, $http, ENV, userService, lastVersion, $rootScope) {
                console.log("### CONTROLLER: ClearCacheController ####");
                $rootScope.lastVersion = lastVersion.version;
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                if ($scope.user.username && ($scope.user.version != $rootScope.lastVersion)) {
                    $rootScope.alerts = [
                        {type: 'danger', msg: 'Mobintouch has new features! Please click here in order to reload your page and get access to them. If this is not enough, please type : CTR + F5 (Windows) or CMD + R (OS X).'}
                    ];
                } else {
                    $rootScope.alerts = [];
                }
                if (!$scope.user.emailValidation && $scope.user.username && $scope.user.emailValidation != null) {
                    $rootScope.alerts.push({type: 'success', msg: "Please confirm your email address. We've sent a verification email, check your mailbox and spams." + " <a href='emailvalidation'> If you didn‘t receive your verification email please click here. </a> "});
                }
            }])
        // signin controller
        .controller('SigninFormController', ['$rootScope', '$scope', '$http', '$state', 'AuthService', 'userService', 'companyService', 'Notification', 'ENV', '$cookies', '$facebook', 'GoogleSignin', '$location', function ($rootScope, $scope, $http, $state, AuthService, userService, companyService, Notification, ENV, $cookies, $facebook, GoogleSignin, $location) {
                console.log("### CONTROLLER: SigninFormController ####");
                $rootScope.ogUrl = $location.absUrl();
                $scope.user = {};

                userService.update($scope.user);
                AuthService.logout();
                $scope.loginFacebook = function () {
                    $facebook.login().then(function (response) {
                        $facebook.api('/me?fields=first_name,last_name,link,picture.width(200).height(200),verified,email,about,birthday,education,location,website,work,cover,languages').then(function (data) {
                            data.platform = 'facebook';
                            $scope.platform = 'Facebook';
                            $scope.loadingSignin = true;
                            $scope.facebook = $facebook.getAuthResponse();
                            $scope.facebook.data = data;
                            AuthService.socialLogin($scope.facebook).then(function (response) {
                                $scope.loginProcess(response);
                            });
                        }).catch(function (response) {
                            $scope.loadingSignin = false;
                            if (response.status === 401)
                                Notification.error({title: 'Error:', message: 'Wrong email or password'});
                            else {
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Internal Error'});
                            }
                        });
                    }, function (err) {
                        console.log(err);
                    });
                };
                $scope.loginGoogle = function () {
                    GoogleSignin.signIn().then(function (response) {
                        gapi.client.load('plus', 'v1', function () {
                            gapi.client.plus.people.get({userId: 'me'}).execute(function (data) {
                                data.platform = 'google';
                                $scope.platform = 'Google';
                                $scope.loadingSignin = true;
                                $scope.google = angular.extend(GoogleSignin.getUser().getAuthResponse(), GoogleSignin.getBasicProfile());
                                $scope.google.data = data;
                                //var request = AuthService.socialLogin($scope.google);
                                AuthService.socialLogin($scope.google).then(function (response) {
                                    $scope.loginProcess(response);
                                }).catch(function (response) {
                                    $scope.loadingSignin = false;
                                    if (response.status === 401)
                                        Notification.error({title: 'Error:', message: 'Wrong email or password'});
                                    else {
                                        Notification.error({title: 'Error (' + response.status + ')', message: 'Internal Error'});
                                    }
                                });
                                //$scope.loginProcess(request);
                            });
                        });
                    }, function (err) {
                        console.log(err);
                    });
                };
                $scope.login = function () {
                    $scope.loadingSignin = true;
                    $scope.platform = 'Email';
                    // Try to login
                    //var request = AuthService.login($scope);
                    AuthService.login($scope).then(function (response) {
                        $scope.loginProcess(response);
                    }).catch(function (response) {
                        $scope.loadingSignin = false;
                        if (response.status === 401)
                            Notification.error({title: 'Error:', message: 'Wrong email or password'});
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Internal Error'});
                        }
                    });
                };
                $scope.loginProcess = function (response) {

                    try {
                        localStorage.setItem('id_token', response.data.token);
                    } catch (e) {
                        try {
                            $cookies.id_token = response.data.token;
                            $cookies.usr_state = response.data.token;
                        } catch (e) {
                            Notification.error({title: 'Error', message: 'Your account has been created but your browser do not allow neither Cookies nor LocalStorage. Please change your browser and login.'});
                            $scope.loadingSignin = false;
                            return;
                        }
                    }

                    $http.get(ENV.apiEndpoint + '/api/user', {cache: true}).then(function (response) {

                        OneSignal.push(["getUserId", function (userId) {
                                response.data.playerId = userId;
                                $http({
                                    method: "PUT",
                                    url: ENV.apiEndpoint + '/api/updateplayerid',
                                    data: {
                                        type: 'set',
                                        player_id: userId
                                    }
                                });
                            }]);
                        $scope.user = response.data;
                        $rootScope.userMode = 'member';
                        userService.update($scope.user);
                        if (response.data.companyPage)
                        {
                            $http.post(ENV.apiEndpoint + '/api/company', {cache: true}).then(function (response) {
                                $scope.company = response.data;
                                $rootScope.companyPercentage = response.data.companyPercentage;
                                companyService.update($scope.company);
                            });
                        }
                        $scope.loadingSignin = false;
                        mixpanel.identify(response.data.email);
                        mixpanel.track("Sign In", {
                            "Page": "Sign In Page",
                            "Type": "Action",
                            "Text": "Sign In",
                            "loginType": $scope.platform,
                            "Success": true,
                            "$email": response.data.email
                        });

                        if ($scope.user.name === null || $scope.user.name.length <= 0 || $scope.user.lastname === null || $scope.user.lastname.length <= 0 || !$scope.user.validated) {
                            console.log("Inside validation else part");
                            $state.go('access.step2');
                        } else if ($scope.user.validated) {
                            if ($rootScope.previousPage) {
                                $state.go($rootScope.previousPage);
                            } else {
                                $state.go('app.page.feed');
                            }
                        }
                    }).catch(function (response) {
                        console.log("Inside catch");
                        mixpanel.track("Sign In", {
                            "Page": "Sign In Page",
                            "Type": "Action",
                            "Text": "Sign In",
                            "loginType": $scope.platform,
                            "Error": true,
                            "ErrorStatus": response.status,
                            "ErrorData": response.data,
                            "$email": $scope.platform == 'Email' ? $scope.user.username : response.data.email
                        });
                        $rootScope.userMode = 'visitor';
                        AuthService.logout();
                        $scope.loadingSignin = false;
                        if (response.status === 401)
                            Notification.error({title: 'Error:', message: 'Wrong email or password'});
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Internal Error'});
                        }
                    });




                    /* Check whether the HTTP Request is Successfull or not. */
                    /* Working code before modification */
                    /*request.then(function (response) {
                     try {
                     localStorage.setItem('id_token', response.data.token);
                     } catch (e) {
                     try {
                     $cookies.id_token = response.data.token;
                     $cookies.usr_state = response.data.token;
                     } catch (e) {
                     Notification.error({title: 'Error', message: 'Your account has been created but your browser do not allow neither Cookies nor LocalStorage. Please change your browser and login.'});
                     }
                     }
                     //$rootScope.user.username = $scope.user.username;
                     // GET CURRENT USER
                     $http.get(ENV.apiEndpoint + '/api/user', {cache: true}).then(function (response) {
                     OneSignal.push(["getUserId", function (userId) {
                     response.data.playerId = userId;
                     $http({
                     method: "PUT",
                     url: ENV.apiEndpoint + '/api/updateplayerid',
                     data: {
                     type: 'set',
                     player_id: userId
                     }
                     });
                     }]);
                     $scope.user = response.data;
                     $rootScope.userMode = 'member';
                     userService.update($scope.user);
                     if (response.data.companyPage)
                     {
                     $http.post(ENV.apiEndpoint + '/api/company', {cache: true}).then(function (response) {
                     $scope.company = response.data;
                     $rootScope.companyPercentage = response.data.companyPercentage;
                     companyService.update($scope.company);
                     });
                     }
                     $scope.loadingSignin = false;
                     mixpanel.identify(response.data.email);
                     mixpanel.track("Sign In", {
                     "Page": "Sign In Page",
                     "Type": "Action",
                     "Text": "Sign In",
                     "loginType": $scope.platform,
                     "Success": true,
                     "$email": response.data.email
                     });
                     if ($scope.user.validated) {
                     if ($rootScope.previousPage) {
                     $state.go($rootScope.previousPage);
                     } else {
                     $state.go('app.page.feed');
                     }
                     } else if ($scope.user.name === null || $scope.user.lastname === null) {
                     $state.go('access.step2');
                     }
                     });
                     }).catch(function (response) {
                     mixpanel.track("Sign In", {
                     "Page": "Sign In Page",
                     "Type": "Action",
                     "Text": "Sign In",
                     "loginType": $scope.platform,
                     "Error": true,
                     "ErrorStatus": response.status,
                     "ErrorData": response.data,
                     "$email": $scope.platform == 'Email' ? $scope.user.username : response.data.email
                     });
                     $rootScope.userMode = 'visitor';
                     AuthService.logout();
                     $scope.loadingSignin = false;
                     if (response.status === 401)
                     Notification.error({title: 'Error:', message: 'Wrong email or password'});
                     else {
                     Notification.error({title: 'Error (' + response.status + ')', message: 'Internal Error'});
                     }
                     });*/
                };
                $scope.track = function (eventName, position, text) {
                    mixpanel.track(eventName, {// Sign In, Sign Up, Get Started
                        "Page": "Sign In Page",
                        "Type": "Link",
                        "Position": position,
                        "Text": text
                    });
                };
            }])
        // signup controller
        .controller('LandingFormController', ['$scope', '$state', 'AuthService', 'userService', '$location', '$cookies', function ($scope, $state, AuthService, userService, $location, $cookies) {
                console.log("### CONTROLLER: LandingFormController ####");
                $scope.user = {};
                userService.update($scope.user);
                AuthService.logout();
                //$rootScope.ogUrl = $location.absUrl();
                //console.log($location.search());
                var params = $location.search();
                if (params.e) {
                    $scope.user.email = params.e;
                    mixpanel.identify(params.e);
                    mixpanel.people.set({
                        "$created": new Date(),
                        "$email": params.e,
                        "Campaign ID": params.c,
                        "Campaign Version": params.v,
                        "Role": params.r,
                        "Subrole": params.s,
                        "Job Title": params.j
                    });
                    try {
                        /*localStorage.setItem('param_email', params.e);
                         localStorage.setItem('param_campaign',params.c);
                         localStorage.setItem('param_version',params.v);
                         localStorage.setItem('param_role',params.r);
                         localStorage.setItem('param_subrole',params.s);
                         localStorage.setItem('param_jobtitle',params.j);*/
                        localStorage.setItem('params', JSON.stringify(params));
                    } catch (e) {
                        try {
                            $cookies.params = JSON.stringify(params);
                            /*$cookies.param_email =  params.e;
                             $cookies.param_campaign = params.c;
                             $cookies.param_version = params.v;
                             $cookies.param_role = params.r;
                             $cookies.param_subrole = params.s;
                             $cookies.param_subrole = params.s;*/
                        } catch (e) {
                            Notification.error({title: 'Error', message: 'Your browser do not allow neither Cookies nor LocalStorage. Please change your browser.'});
                        }
                    }
                }
                $scope.track = function (eventName, position, text) {
                    mixpanel.track(eventName, {// Sign In, Sign Up, Get Started
                        "Page": "Landing Page",
                        "Type": "Link",
                        "Position": position,
                        "Text": text
                    });
                };
                /*$scope.track = trackJs.watch(function(eventName, button){
                 mixpanel.track(eventName,{
                 "Page": "Landing",
                 "Position":
                 "Type": "Action", "Link", "Button"
                 "Error":
                 "Succes":
                 "Email":
                 "Role":
                 "Subrole":
                 "Job title":
                 "Company name":
                 "Username":
                 });
                 };*/
                mixpanel.track("Landing Page", {
                    "Page": "Landing Page",
                    "Type": "Page View",
                    "$email": params.e,
                    "Campaign ID": params.c,
                    "Campaign Version": params.v,
                    "Role": params.r,
                    "Subrole": params.s,
                    "Job Title": params.j
                });
                $scope.gotoSection1 = function () {
                    var element = document.getElementById('section1');
                    element.scrollIntoView({block: "end", behavior: "smooth"});
                };
                $scope.redirectToSignup = function () {
                    mixpanel.track("Sign Up Page", {// Sign In, Sign Up, Get Started
                        "Page": "Landing Page",
                        "Type": "Action",
                        "Position": "Bottom",
                        "Text": "Get started",
                        "$email": $scope.user.email
                    });
                    //console.log("redirectToSignup");
                    //console.log($scope.user);
                    userService.update($scope.user);
                    $state.go('access.signup');
                };
            }])
        // signup controller
        .controller('JoinFormController', ['$scope', '$state', 'AuthService', 'userService', function ($scope, $state, AuthService, userService) {
                console.log("### CONTROLLER: JoinFormController ####");
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.redirectToSignup = function () {
                    //console.log("redirectToSignup");
                    //console.log($scope.user);
                    userService.update($scope.user);
                    $state.go('access.signup');
                };
            }])
        // signup controller
        .controller('SignupFormController', ['$scope', '$http', '$rootScope', '$state', 'AuthService', 'userService', 'Notification', 'ENV', '$cookies', '$facebook', 'GoogleSignin', '$location', function ($scope, $http, $rootScope, $state, AuthService, userService, Notification, ENV, $cookies, $facebook, GoogleSignin, $location) {
                console.log("### CONTROLLER: SignupFormController ####");
                $rootScope.ogUrl = $location.absUrl();
                if (angular.isDefined($state.params.invitedby) && $state.params.invitedby.length == 24) {
                    localStorage.setItem('invitedby', $state.params.invitedby);
                }
                //$scope.user = {};
                AuthService.logout();
                $scope.signUpFacebook = function () {
                    $facebook.login().then(function (response) {
                        $facebook.api('/me?fields=first_name,last_name,link,picture.width(200).height(200),verified,email,about,birthday,education,location,website,work,cover,languages').then(function (data) {
                            data.platform = 'facebook';
                            $scope.platform = 'Facebook';
                            $scope.loadingSignup = true;
                            $scope.facebook = $facebook.getAuthResponse();
                            $scope.facebook.data = data;
                            var request = $http({
                                method: "POST",
                                url: ENV.apiEndpoint + '/api/socialregister',
                                skipAuthorization: true,
                                data: $scope.facebook,
                                headers: {'Content-Type': 'application/json'}
                            });
                            $scope.signupProcess(request, 'facebook');
                        });
                    }, function (err) {
                        console.log(err);
                    });
                };
                $scope.signUpGoogle = function () {
                    GoogleSignin.signIn().then(function (response) {
                        gapi.client.load('plus', 'v1', function () {
                            gapi.client.plus.people.get({userId: 'me'}).execute(function (data) {
                                data.platform = 'google';
                                $scope.loadingSignin = true;
                                $scope.google = angular.extend(GoogleSignin.getUser().getAuthResponse(), GoogleSignin.getBasicProfile());
                                $scope.google.data = data;
                                var request = $http({
                                    method: "POST",
                                    url: ENV.apiEndpoint + '/api/socialregister',
                                    skipAuthorization: true,
                                    data: $scope.google,
                                    headers: {'Content-Type': 'application/json'}
                                });
                                $scope.signupProcess(request, 'google');
                            });
                        });
                    }, function (err) {
                        console.log(err);
                    });
                };
                $scope.signup = function () {
                    $scope.loadingSignup = true;
                    var request = $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/newregister',
                        skipAuthorization: true,
                        data: {
                            email: $scope.user.email,
                            password: $scope.user.password,
                            invitedby: localStorage.getItem('invitedby') ? localStorage.getItem('invitedby') : null,
                        },
                        headers: {'Content-Type': 'application/json'}
                    });
                    $scope.tmppwd = $scope.user.password;
                    $scope.signupProcess(request, 'normal');
                };
                $scope.signupProcess = function (request, type) {
                    request.then(function (response) {
                        if (localStorage.removeItem('invitedby') != null) {
                            localStorage.removeItem('invitedby');
                        }
                        $scope.user = response.data.user;
                        userService.update($scope.user);
                        var params = localStorage.getItem('params');
                        if (!params)
                            params = $cookies.params;
                        if (!angular.isUndefined(params))
                            params = JSON.parse(params);
                        //console.log(params);

                        var email = $scope.user.email;
                        if (params && !angular.isUndefined(params.e))
                            email = params.e;
                        mixpanel.identify(email);
                        mixpanel.people.set({
                            "$first_name": $scope.user.name,
                            "$last_name": $scope.user.lastname,
                            "$created": new Date(),
                            "$email": $scope.user.email,
                            "Username": $scope.user.username,
                            "IP": response.data.ip
                        });
                        if ($scope.user.username != email)
                            mixpanel.alias($scope.user.username, email);
                        if (angular.isUndefined(params)) {
                            mixpanel.track("Sign Up Step 1", {
                                "Page": "Sign Up Page",
                                "Type": "Action",
                                "Text": "Join Now",
                                "Success": true,
                                //"Error": false,
                                "$email": $scope.user.email,
                                "Username": $scope.user.username
                            });
                        } else {
                            mixpanel.track("Sign Up Step 1", {
                                "Page": "Sign Up Page",
                                "Type": "Action",
                                "Text": "Join Now",
                                "Success": true,
                                //"Error": false,
                                "$email": $scope.user.email,
                                "Username": $scope.user.username,
                                "Campaign ID": params.c,
                                "Campaign Version": params.v,
                                "Role": params.r,
                                "Subrole": params.s,
                                "Job Title": params.j
                            });
                        }

                        // Try to login
                        if (type == 'google') {
                            var request = AuthService.socialLogin($scope.google);
                        } else if (type == 'facebook') {
                            var request = AuthService.socialLogin($scope.facebook);
                        } else {
                            $scope.user.password = $scope.tmppwd;
                            var request = AuthService.login($scope);
                        }
                        request.then(function (response) {
                            try {
                                localStorage.setItem('id_token', response.data.token);
                            } catch (e) {
                                try {
                                    $cookies.id_token = response.data.token;
                                } catch (e) {
                                    Notification.error({title: 'Error', message: 'Your account has been created but your browser do not allow neither Cookies nor LocalStorage. Please change your browser and login.'});
                                }
                            }
                            $scope.loadingSignup = false;
                            $rootScope.userMode = 'member';
                            $state.go('access.step2');
                        }).catch(function (response) {
                            mixpanel.track("Sign In", {
                                "Page": "Sign Up Page",
                                "Type": "Action",
                                "Text": "Sign In",
                                "Error": true,
                                "ErrorStatus": response.status,
                                "ErrorData": response.data,
                                "$email": $scope.user.username
                            });
                            $rootScope.userMode = 'visitor';
                            AuthService.logout();
                            $state.go('access.signin');
                        });
                    }).catch(function (response) {
                        if (response.status === 403)
                            Notification.error({title: 'Error', message: 'This email already exists. Please sign in.'});
                        else {
                            Notification.error({title: 'Error', message: 'Server Error'});
                            mixpanel.track("Sign Up Step 1", {
                                "Page": "Sign Up Page",
                                "Type": "Action",
                                "Text": "Join Now",
                                "Error": true,
                                "ErrorStatus": response.status,
                                "ErrorData": response.data,
                                "$email": $scope.user.email
                            });
                        }
                        $scope.loadingSignup = false;
                    });
                };
                $scope.track = function (eventName, position, text) {
                    mixpanel.track(eventName, {// Sign In, Sign Up, Get Started
                        "Page": "Sign Up Page",
                        "Type": "Link",
                        "Position": position,
                        "Text": text
                    });
                };
            }])
        // signup modal controller
        .controller('SignupModalController', ['$scope', '$rootScope', '$uibModal', '$http', '$state', 'AuthService', 'userService', 'Notification', 'ENV', '$cookies', '$facebook', 'GoogleSignin', function ($scope, $rootScope, $uibModal, $http, $state, AuthService, userService, Notification, ENV, $cookies, $facebook, GoogleSignin) {
                console.log("### CONTROLLER: SignupModalController ####");
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.showSignupModal = function (title) {
                    if ((!$scope.user || !$scope.user.username) && $rootScope.userMode == 'visitor') {
                        $uibModal.open({
                            templateUrl: 'tpl/blocks_public/user/signup_modal.html?v=' + ENV.latestUpdate,
                            controller: function ($uibModalInstance) {
                                $scope.title = title ? title : "Signup to mobintouch";
                                $scope.cancel = function () {
                                    $uibModalInstance.dismiss('cancel');
                                };
                                $scope.signUpFacebook = function () {
                                    $facebook.login().then(function (response) {
                                        $facebook.api('/me?fields=first_name,last_name,link,picture.width(200).height(200),verified,email,about,birthday,education,location,website,work,cover,languages').then(function (data) {
                                            data.platform = 'facebook';
                                            $scope.platform = 'Facebook';
                                            $scope.loadingSignup = true;
                                            $scope.facebook = $facebook.getAuthResponse();
                                            $scope.facebook.data = data;
                                            var request = $http({
                                                method: "POST",
                                                url: ENV.apiEndpoint + '/api/socialregister',
                                                skipAuthorization: true,
                                                data: $scope.facebook,
                                                headers: {'Content-Type': 'application/json'}
                                            });
                                            $scope.signupProcess(request, 'facebook');
                                        });
                                    }, function (err) {
                                        console.log(err);
                                    });
                                };
                                $scope.signUpGoogle = function () {
                                    GoogleSignin.signIn().then(function (response) {
                                        gapi.client.load('plus', 'v1', function () {
                                            gapi.client.plus.people.get({userId: 'me'}).execute(function (data) {
                                                data.platform = 'google';
                                                $scope.loadingSignin = true;
                                                $scope.google = angular.extend(GoogleSignin.getUser().getAuthResponse(), GoogleSignin.getBasicProfile());
                                                $scope.google.data = data;
                                                var request = $http({
                                                    method: "POST",
                                                    url: ENV.apiEndpoint + '/api/socialregister',
                                                    skipAuthorization: true,
                                                    data: $scope.google,
                                                    headers: {'Content-Type': 'application/json'}
                                                });
                                                $scope.signupProcess(request, 'google');
                                            });
                                        });
                                    }, function (err) {
                                        console.log(err);
                                    });
                                };
                                $scope.signupProcess = function (request, type) {
                                    request.then(function (response) {
                                        $scope.user = response.data.user;
                                        userService.update($scope.user);

                                        // Try to login
                                        if (type === 'google') {
                                            var request = AuthService.socialLogin($scope.google);
                                        } else if (type === 'facebook') {
                                            var request = AuthService.socialLogin($scope.facebook);
                                        }

                                        request.then(function (response) {
                                            try {
                                                localStorage.setItem('id_token', response.data.token);
                                            } catch (e) {
                                                try {
                                                    $cookies.id_token = response.data.token;
                                                } catch (e) {
                                                    Notification.error({title: 'Error', message: 'Your account has been created but your browser do not allow neither Cookies nor LocalStorage. Please change your browser and login.'});
                                                }
                                            }
                                            $scope.loadingSignup = false;
                                            $rootScope.userMode = 'member';
                                            $state.go('access.step2');
                                        }).catch(function (response) {
                                            $rootScope.userMode = 'visitor';
                                            AuthService.logout();
                                            $state.go('access.signin');
                                        });
                                    }).catch(function (response) {
                                        if (response.status === 403)
                                            Notification.error({title: 'Error', message: 'This email already exists. Please sign in.'});
                                        else {
                                            Notification.error({title: 'Error', message: 'Server Error'});
                                        }
                                        $scope.loadingSignup = false;
                                    });
                                };
                            },
                            size: 'md',
                            windowClass: 'centered-modal ',
                            scope: $scope
                        });
                    }
                };
            }])
        // forgotten password controller
        .controller('ForgottenPasswordFormController', ['$scope', '$http', '$state', 'Notification', 'ENV', function ($scope, $http, $state, Notification, ENV) {
                console.log("### CONTROLLER: ForgottenPasswordFormController ####");
                $scope.sendEmail = function () {
                    //$scope.message = 'A reset link sent to your email address, please check it in 24 hours.';
                    $scope.loadingForgottenPassword = true;
                    // Try to login
                    //console.log(JSON.stringify({"username": $scope.user.email}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/resetting/send-email',
                        data: $.param({username: $scope.user.email}),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                        }
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.data.indexOf("invalidUser") > -1) {
                            //$scope.message = 'The username or email address '+$scope.user.email+' does not exist.';
                            Notification.error({title: 'Error (404)', message: 'The username or email address ' + $scope.user.email + ' does not exist.'});
                        } else if (response.data.indexOf("alreadyRequested") > -1) {
                            //$scope.message = 'The password for this user has already been requested within the last 24 hours.';
                            Notification.warning({title: 'Reminder', message: 'The password for this user has already been requested within the last 24 hours. Please, check you inbox.'});
                        } else {
                            //$scope.message = 'A reset link sent to your email address, please check it in 24 hours.';
                            Notification.success({title: 'Check you inbox', message: 'A reset link sent to your email address, please check it in 24 hours.'});
                        }
                        $scope.loadingForgottenPassword = false;
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 404)
                            Notification.error({title: 'Error', message: 'Wrong Email or Password not right'});
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Server Error'});
                        }
                        $scope.loadingForgottenPassword = false;
                    });
                };
            }])
        // new password controller
        .controller('NewPasswordFormController', ['$scope', '$http', '$state', 'Notification', 'userService', 'AuthService', 'ENV', '$cookies', function ($scope, $http, $state, Notification, userService, AuthService, ENV, $cookies) {
                console.log("### CONTROLLER: NewPasswordFormController ####");
                $scope.token = $state.params.token;
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.newPassword = function () {
                    $scope.message = 'A reset link sent to your email address, please check it in 24 hours.';
                    $scope.loadingResetPassword = true;
                    // Try to login
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/resetting/reset/' + $scope.token,
                        data: {
                            plainPassword_first: $scope.user.first,
                            plainPassword_second: $scope.user.second
                        },
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        var temp = $scope.user.first;
                        $scope.user = response.data;
                        $scope.user.password = temp;
                        //console.log($scope.user.first);
                        // Try to login
                        var request = AuthService.login($scope);
                        /* Check whether the HTTP Request is Successfull or not. */
                        request.then(function (response) {
                            //localStorage.setItem('id_token',data.token);
                            try {
                                localStorage.setItem('id_token', response.data.token);
                            } catch (e) {
                                try {
                                    $cookies.id_token = response.data.token;
                                } catch (e) {
                                    Notification.error({title: 'Error', message: 'Your account has been created but your browser do not allow neither Cookies nor LocalStorage. Please change your browser and login.'});
                                }
                            }
                            $scope.user.password = null;
                            userService.update($scope.user);
                            $scope.loadingSignup = false;
                            if ($scope.user.validated)
                                $state.go('app.page.feed');
                            else
                                $state.go('access.step2');
                        }).catch(function (response) {
                            //console.log(status);
                            //console.log(data);
                            AuthService.logout();
                            $scope.loadingSignup = false;
                            $state.go('access.signin');
                        });
                        $scope.loadingResetPassword = false;
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 404)
                            Notification.error({title: 'Error (' + response.status + ')', message: 'User not found'});
                        else {
                            if (response.status == 400)
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            else
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Server Error'});
                        }
                    });
                };
            }])
        // register step2 controller
        .controller('RegisterStepsFormController', ['$scope', '$http', '$state', 'AuthService', 'userService', 'Notification', 'ENV', '$cookies', '$location', '$window', '$templateCache', '$compile', 'suggestKeywords', 'autocompleteKeywords', '$q', '$filter', 'contactResource', '$uibModal', function ($scope, $http, $state, AuthService, userService, Notification, ENV, $cookies, $location, $window, $templateCache, $compile, suggestKeywords, autocompleteKeywords, $q, $filter, contactResource, $uibModal) {
                console.log("### CONTROLLER: RegisterStepsFormController ####");
                $cookies.new_registration = true;
                $scope.loadingSignup = false;
                var loggedin = localStorage.getItem('id_token') != null || ($cookies.id_token != 'null' && !angular.isUndefined($cookies.id_token));
                if (!loggedin) {
                    $state.go('access.signin');
                }

                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                    $scope.user.currency = $scope.user.currency ? $scope.user.currency : '';
                    $scope.user.currentStatus = $scope.user.currentStatus ? $scope.user.currentStatus : '';
                    if ($scope.user.interestedIn === null) {
                        $scope.user.interestedIn = {"individual": {"jobs": false, "building_network": false, "mobile_news": false, "discover": false}, "company": {"discover": false, "raising_money": false, "promot_company": false, "recruiting": false}};
                    }
                    if ($scope.user.keywords) {
                        $scope.user.keywords = $scope.user.keywords.filter(function (item) {
                            return item != angular.lowercase($scope.user.name) && item != angular.lowercase($scope.user.lastname) && item != angular.lowercase($scope.user.jobTitle) && item != angular.lowercase($scope.user.company) && item != angular.lowercase($scope.user.city);
                        });
                    }
                });
                $scope.months = [
                    {"key": "01", "value": "Jan"}, {"key": "02", "value": "Feb"}, {"key": "03", "value": "Mar"}, {"key": "04", "value": "Apr"}, {"key": "05", "value": "May"}, {"key": "06", "value": "Jun"}, {"key": "07", "value": "Jul"}, {"key": "08", "value": "Aug"}, {"key": "09", "value": "Sep"}, {"key": "10", "value": "Oct"}, {"key": "11", "value": "Nov"}, {"key": "12", "value": "Dec"}
                ];
                $scope.user.birthdayMM = "";
                $scope.years = $.map($(Array(70)), function (val, i) {
                    return i + new Date().getUTCFullYear() - 70;
                });
                $scope.user.birthdayYYYY = '';
                $scope.days = $.map($(Array(31)), function (val, i) {
                    return i + 1 < 10 ? "0" + (i + 1) : i + 1;
                });
                $scope.user.birthdayDD = '';
                $scope.updatePersonalDetails = function () {
                    $scope.loadingSignup = true;
                    if (!angular.isDefined($scope.details)) {
                        $scope.user.location = null;
                        $scope.loadingSignup = false;
                        $window.document.getElementById('location').focus();
                        return;
                    }
                    $scope.user.geometrylocation = $scope.details.geometry.location.toJSON();
                    /*angular.forEach($scope.details.address_components, function (value, key) {
                     if (value.types[0] === 'locality') {
                     $scope.user.city = value.long_name;
                     } else if (value.types[0] === 'country') {
                     $scope.user.basedCountry = value.short_name;
                     }
                     });*/

                    var loc = $filter('locations')($scope.details.address_components);
                    $scope.user.city = loc.city;
                    $scope.user.country = loc.country;
                    $scope.user.basedCountry = loc.basedCountry;

                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/register/step/2/personalinfos',
                        data: {
                            name: $scope.user.name,
                            lastname: $scope.user.lastname,
                            gender: $scope.user.gender,
                            city: $scope.user.city,
                            country: $scope.user.country,
                            basedCountry: $scope.user.basedCountry,
                            birthdayDD: $scope.user.birthdayDD,
                            birthdayMM: $scope.user.birthdayMM,
                            birthdayYYYY: $scope.user.birthdayYYYY,
                            geometrylocation: $scope.user.geometrylocation,
                            formattedaddress: $scope.details.formatted_address
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data.user;
                        userService.update($scope.user);
                        try {
                            localStorage.setItem('id_token', response.data.token);
                        } catch (e) {
                            try {
                                $cookies.id_token = response.data.token;
                            } catch (e) {
                                Notification.error({title: 'Error', message: 'Your account has been created but your browser do not allow neither Cookies nor LocalStorage. Please change your browser and login.'});
                            }
                        }
                        $state.go('access.step3');
                    }).catch(function (response) {
                        console.log(response);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            $scope.loadingSignup = false;
                        }
                    });
                };
                //Step 4 is for profile upload handle by avatarCtrl
                $scope.updateEmployementInfo = function () {
                    $scope.loadingSignup = true;
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/register/step/5/employmentinfos',
                        data: {
                            jobTitle: $scope.user.jobTitle,
                            companyType: $scope.user.companyType,
                            companySubType: $scope.user.companySubType,
                            company: $scope.user.company,
                            companyid: $scope.user.companyid,
                            hasEmployer: $scope.user.hasEmployer,
                            grossSalary: $scope.user.grossSalary,
                            currency: $scope.user.currency,
                            currentStatus: $scope.user.currentStatus
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data;
                        userService.update($scope.user);
                        $state.go('access.step6');
                    }).catch(function (response) {
                        console.log(response);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            $scope.loadingSignup = false;
                        }
                    });
                };
                $scope.updateInterestedIn = function () {
                    $scope.loadingSignup = true;
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/register/step/6/interestedin',
                        data: {
                            interestedin: $scope.user.interestedIn
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data;
                        userService.update($scope.user);
                        $state.go('access.step7');
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            $scope.loadingSignup = false;
                        }
                    });
                };
                $scope.keywords = ["online advertising", "digital marketing", "online marketing", "mobile marketing", "advertising", "social media marketing", "marketing", "mobile advertising", "affiliate marketing", "digital media", "social media", "marketing strategy", "sem", "digital strategy", "mobile devices"];
                $scope.updateKeywords = function () {
                    $scope.loadingSignup = true;
                    if (!$scope.user.keywords || $scope.user.keywords.length <= 0) {
                        Notification.error({title: 'Error', message: 'Please add atleast one keyword'});
                        $scope.loadingSignup = false;
                    } else {
                        $http({
                            method: "PUT",
                            url: ENV.apiEndpoint + '/api/register/step/7/addkeywords',
                            data: {
                                keywords: $scope.user.keywords.map(function (tag) {
                                    return tag.text;
                                })
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            $scope.user = response.data;
                            userService.update($scope.user);
                            if ($cookies.get('linkedin') && $cookies.get('linkedin') === "true") {
                                $state.go('access.step8');
                            } else {
                                $state.go('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                            }

                        }).catch(function (response) {
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                $scope.loadingSignup = false;
                            }
                        });
                    }
                };
                $scope.skipKeywordStep = function () {
                    if ($cookies.get('linkedin') && $cookies.get('linkedin') === "true") {
                        $state.go('access.step8');
                    } else {
                        $state.go('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                    }
                };
                $scope.uploadLinkedinContacts = function () {
                    $scope.$apply(function () {
                        $scope.loadingSignup = true;
                    });
                    var formData = new FormData();
                    formData.append("contacts", $scope.file);
                    $.ajax({
                        url: ENV.apiEndpoint + '/api/importcontacts/linkedin',
                        type: 'POST',
                        beforeSend: function (request)
                        {
                            request.setRequestHeader("Authorization", 'Bearer ' + localStorage.getItem("id_token"));
                        },
                        data: formData,
                        processData: false, // tell jQuery not to process the data
                        contentType: false, // tell jQuery not to set contentType
                        success: function (data, status) {
                            if (data.hasConnectionsToInvite <= 0) {
                                $scope.$apply(function () {
                                    $scope.loadingSignup = false;
                                });
                                Notification.warning({title: 'No contatcs to import', message: 'All contacts are already imported or no contact available in file.'});
                                $scope.fileuploaded = false;
                            } else {
                                $state.go('access.step9');
                            }
                        },
                        error: function (request, status, error) {
                            if (status === 401)
                                $state.go('access.signin');
                            else {
                                $scope.$apply(function () {
                                    $scope.loadingSignup = false;
                                });
                                Notification.error({title: 'Error (' + status + ')', message: 'Ops! Something went wrong...'});
                            }
                        }
                    });
                };

                $scope.downloadedLinkedinConnections = function () {
                    $cookies.put('linkedin', true);
                };

                $scope.skipLinkedinImport = function () {
                    /*$uibModal.open({
                     templateUrl: 'tpl/forms/skipLinkedinImport.html?v=' + ENV.latestUpdate,
                     controller: function ($scope, $cookies, $uibModalInstance) {
                     $scope.cancel = function () {
                     $uibModalInstance.dismiss('cancel');
                     };
                     $scope.skippedLinkedinDownload = function () {
                     $cookies.put('linkedin', false);
                     };
                     },
                     size: 'sm',
                     windowClass: 'centered-modal'
                     });*/

                    $cookies.put('linkedin', false);
                    $state.go('access.step4');

                };

                /*$scope.inviteSelectedContacts = function () {
                 $scope.selectedContacts = $scope.contacts.filter(function (contact) {
                 return contact.selected;
                 });
                 if ($scope.selectedContacts.length > 0) {
                 var request = $http({
                 method: "POST",
                 url: ENV.apiEndpoint + '/api/updateinvitedcontacts',
                 data: {
                 contacts: $scope.selectedContacts
                 },
                 headers: {'Content-Type': 'application/json'}
                 });
                 request.success(function (data, status) {
                 console.log(data);
                 $state.go('app.page.edit/profile');
                 });
                 request.error(function (data, status) {
                 Notification.error({title: 'Error', message: 'Something went wrong! Please try again.'});
                 $scope.inviting = false;
                 });
                 } else {
                 console.log($scope);
                 Notification.warning({title: 'Please select contact', message: 'You must select at least one contact to send invitation mail.'});
                 $scope.inviting = false;
                 }
                 };*/

                $scope.registerSteps = function () {
                    $scope.loadingConfirm = true;
                    // Try to create
                    if (!$scope.user.company || !$scope.user.jobTitle || !$scope.user.companyType) {
                        Notification.error({title: 'Error', message: 'Please fill all the fields'});
                    } else {
                        //console.log(JSON.stringify({"user": $scope.user}));
                        $http({
                            method: "PUT",
                            url: ENV.apiEndpoint + '/api/register/step/2/personalinfos',
                            data: {
                                name: $scope.user.name,
                                lastname: $scope.user.lastname,
                                city: $scope.user.city,
                                basedCountry: $scope.user.basedCountry,
                                birthdayDD: $scope.user.birthdayDD,
                                birthdayMM: $scope.user.birthdayMM,
                                birthdayYYYY: $scope.user.birthdayYYYY,
                                geometrylocation: $scope.user.geometrylocation
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            //console.log($scope.user.username);
                            userService.update($scope.user);
                            //userService.Save($scope.user);
                            $scope.loadingConfirm = false;
                            mixpanel.identify($scope.user.username);
                            mixpanel.people.set({
                                "Company": $scope.user.company,
                                "Job Title": $scope.user.jobTitle,
                                "Role": $scope.user.companyType,
                                "Subrole": $scope.user.companySubType,
                                "Company Exists": $scope.user.companyid ? true : false
                            });
                            //mixpanel.track("Sign Up step 2 | Sign 2nd step done",{
                            mixpanel.track("Sign Up Step 2", {
                                "Page": "Sign Up Step 2 Page",
                                "Type": "Action",
                                "Text": "Create my profile",
                                "Success": true,
                                "Username": $scope.user.username,
                                "$email": $scope.user.email,
                                "Company": $scope.user.company,
                                "Job Title": $scope.user.jobTitle,
                                "Role": $scope.user.companyType,
                                "Subrole": $scope.user.companySubType
                            });
                            //mixpanel.alias($scope.user.id, $scope.user.email);
                            $scope.user = response.data;
                            console.log($scope.user);
                            // $cookies.new_registration = true;
                            window.location = "invite/inviter_inpage";
                            /*if ($scope.user.validated){
                             $state.go('app.page.edit/profile');
                             }
                             else{
                             //$state.go('access.step3');
                             window.location = ENV.baseUrl + "../invite/inviter_inpage";
                             }*/
                        }).catch(function (response) {
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                mixpanel.identify($scope.user.username);
                                mixpanel.track("Sign Up Step 2", {
                                    "Page": "Sign Up Step 2 Page",
                                    "Type": "Action",
                                    "Text": "Create my profile",
                                    //"Success": false,
                                    "Error": true,
                                    "ErrorStatus": response.status,
                                    "ErrorData": response.data,
                                    "Username": $scope.user.username,
                                    "$email": $scope.user.email
                                });
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            }
                        });
                    }
                };
                /* $scope.companyNameSearch = trackJs.watch(function() {
                 //console.log("companyNameSearch");
                 //console.log($scope.user.company);
                 if($scope.user.company){
                 
                 var request = $http({
                 method: "POST",
                 url: ENV.apiEndpoint + '/api/edit/company/search',
                 cache : true,
                 data: {
                 companyName:   $scope.user.company
                 },
                 headers: { 'Content-Type': 'application/json' }
                 });
                 
                 
                 request.success(function (data, status) {
                 //console.log(status);
                 //console.log(data);
                 $scope.foundCompanies = data;
                 
                 })
                 request.error(function (data, status) {
                 //console.log(status);
                 ////console.log(data);
                 //alert('401 => Redirect to signin');
                 if(status===401) $state.go('access.signin');
                 })
                 }
                 };*/
                $scope.canceler = $q.defer();
                $scope.companyNameSearch = function ($viewValue) {
                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.query = {};
                    $scope.query.headerString = $viewValue;
                    $scope.query.profileType = 'companies';
                    return $http.post(ENV.apiEndpoint + '/api/public/autocomplete', {query: $scope.query}, {timeout: $scope.canceler.promise})
                            .then(function (response) {
                                //console.log(response.data);
                                return response.data;
                            });
                };
                $scope.onSelect = function (item) {
                    $scope.user.company = item.name;
                    $scope.user.companyid = item.companyID;
                    userService.update($scope.user);
                };
                $scope.unselectItem = function () {
                    $scope.user.companyid = null;
                    userService.update($scope.user);
                };
                $scope.updateHasEmployer = function () {
                    $scope.hasEmployer = $scope.user.hasEmployer;
                };
                $scope.getSuggestKeywords = function () {

                    $http.get(ENV.apiEndpoint + '/api/keywordssuggestions')
                            .then(function (response) {
                                $scope.keywords = response.data;
                            }, function (error) {

                            });
                };

                //Singup step role autocomplete
                $scope.rolesSearch = function ($viewValue) {
                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.query = {};
                    $scope.query.headerString = $viewValue;
                    $scope.query.profileType = 'roles';
                    return $http.post(ENV.apiEndpoint + '/api/public/autocomplete', {query: $scope.query}, {timeout: $scope.canceler.promise})
                            .then(function (response) {
                                //console.log(response.data);
                                return response.data;
                            });
                };
                $scope.onSelectRole = function (item) {
                    $scope.user.companyType = item.name;
                    userService.update($scope.user);
                };

                $scope.canceler = $q.defer();
                $scope.loadKeywords = function (query) {
                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.loadingkeywords = true;
                    return $http({method: 'GET', url: ENV.apiEndpoint + '/api/autocompletekeywords/' + query, timeout: $scope.canceler.promise}).then(function (response) {
                        $scope.loadingkeywords = false;
                        return response;
                    });
                    //return autocompleteKeywords.get({keyword: query}).$promise;

                    /*var defer = $q.defer();
                     var skeywords = [];
                     angular.forEach($scope.keywords, function (item) {
                     if (item.toLowerCase().indexOf(query.toLowerCase()) !== -1) {
                     skeywords.push(item);
                     }
                     });
                     defer.resolve(skeywords);
                     return defer.promise;*/
                    //return $http.get('http://192.168.2.134:8080/api/addinterests/keywordssuggestions', {timeout: canceler.promise});
                };
                $scope.keywordSelected = function (keyword) {

                    if ($scope.user.keywords) {
                        var exists = $scope.user.keywords.some(function (obj) {
                            return obj.text === keyword;
                        });
                    }

                    if (exists !== true) {
                        if (!$scope.user.keywords)
                            $scope.user.keywords = [];
                        $scope.user.keywords.push({'text': keyword});
                    }

                    $scope.keywords.splice($.inArray(keyword, $scope.keywords), 1);

                    //console.log($scope.user.keywords);
                    //$scope.keywords.pop(keyword);
                };

                $scope.keywordRemoved = function (keyword) {
                    $scope.keywords.unshift(keyword);
                };

                $scope.selectKeyword = function (text) {
                    $scope.keywords.splice($.inArray(text, $scope.keywords), 1);
                };

                $scope.checkfile = function (event, frm) {
                    $scope.$apply(function () {
                        $scope.loadingSignup = true;
                    });
                    $scope.file = event.target.files[0];
                    if (!$scope.file) {
                        $scope.fileuploaded = false;
                        $scope.$apply(function () {
                            $scope.loadingSignup = false;
                        });
                        return;
                    }
                    var extension = $scope.file.name.substring($scope.file.name.lastIndexOf('.') + 1).toLowerCase();
                    if (extension !== 'csv') {
                        Notification.error({title: 'Invalid file', message: 'Please select valid csv contact file to upload your contacts.'});
                        $scope.$apply(function () {
                            $scope.loadingSignup = false;
                        });
                    } else if ($scope.file.size < 800) {
                        Notification.error({title: 'Invalid file', message: 'Please check your csv file either it contains wrong data or empty!'});
                        $scope.$apply(function () {
                            $scope.loadingSignup = false;
                        });
                    } else {
                        $scope.uploadLinkedinContacts();
                    }
                };

                $scope.contacts = [];
                $scope.getContacts = function () {
                    contactResource.get().$promise
                            .then(function (data) {
                                if ($scope.contacts.length > 0) {

                                } else {
                                    $scope.contacts = data.contacts;
                                }
                            }, function (error) {

                            });
                };

                var lc = document.getElementById('places');
                if (lc) {
                    var pService = new google.maps.places.PlacesService(lc);
                    var aService = new google.maps.places.AutocompleteService(lc);
                    $scope.locationResult = [];

                    $scope.$watch('user.location', function (val) {
                        if (val && val.length > 0) {
                            var request = {
                                input: val,
                                types: ['(regions)']
                            };

                            $scope.listing = [];
                            aService.getPredictions(request, function (results, status) {
                                if (status === 'OK') {
                                    $scope.locationResult = results;
                                }
                            });

                            if ($scope.details) {
                                $scope.details = null;
                            }

                            if (!$scope.details) {
                                $scope.frmPersonalDetails.places.$setValidity('required', false);
                            } else {
                                $scope.frmPersonalDetails.places.$setValidity('required', true);
                            }

                        }
                    });

                    $scope.onSelectLocation = function (item) {

                        var city;
                        var basedCountry;
                        var country;

                        pService.getDetails({
                            placeId: item.place_id
                        }, function (place, status) {
                            if (status === google.maps.places.PlacesServiceStatus.OK) {
                                $scope.details = place;
                            }
                        });
                    };
                }
                /*$scope.unselectall = function () {
                 $scope.unselecting = true;
                 var counter = $scope.contacts.length;
                 console.log($scope.unselecting);
                 angular.forEach($scope.contacts, function (v, k) {
                 v.selected = false;
                 counter -= 1;
                 if (counter === 0) {
                 $scope.unselecting = false;
                 console.log($scope.unselecting);
                 }
                 });
                 };
                 
                 $scope.selectall = function () {
                 $scope.selecting = true;
                 var counter = $scope.contacts.length;
                 angular.forEach($scope.contacts, function (v, k) {
                 v.selected = true;
                 counter -= 1;
                 console.log(counter);
                 if (counter === 0) {
                 $scope.selecting = false;
                 console.log($scope.selecting);
                 }
                 });
                 };*/

                $scope.pageLoad = function (page, type) {
                    mixpanel.identify($scope.user.username);
                    mixpanel.track(page, {
                        "Page": page,
                        "Type": type
                    });
                };
            }])
        // Invite Contacts controller
       
        // register step3 controller
        .controller('EmailValidationFormController', ['$scope', '$http', '$state', 'AuthService', 'userService', 'Notification', 'ENV', '$cookies', 'myUser', function ($scope, $http, $state, AuthService, userService, Notification, ENV, $cookies, myUser) {
                console.log("### CONTROLLER: EmailValidationFormController ####");
                var loggedin = localStorage.getItem('id_token') != null || ($cookies.id_token != 'null' && !angular.isUndefined($cookies.id_token));
                if (!loggedin) {
                    $state.go('access.signin');
                } else {
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/register/step/10/emailvalidation',
                        data: {
                            email: myUser.email
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user.validated = response.data.validated;
                        userService.update($scope.user);
                        if ($scope.user.validated == true)
                            $state.go('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                    });
                }
                $scope.status = 200;
                $scope.loadingSeding = false;
                //$scope.user = userService.Restore();
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                //console.log($scope.user);
                $scope.emailValidation = function () {
                    $scope.loadingSeding = true;
                    // Try to create
                    if (typeof $scope.user === 'undefined' || !$scope.user.email) {
                        Notification.error({title: 'Error', message: 'Please fill your email'});
                        $scope.loadingSeding = false;
                    } else {
                        //console.log(JSON.stringify({"email": $scope.user.email}));
                        $http({
                            method: "PUT",
                            url: ENV.apiEndpoint + '/api/register/step/10/emailvalidation',
                            data: {
                                email: $scope.user.email
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            //console.log("EMAIL RESPONSE:");
                            //console.log(data);
                            $scope.user.validated = response.data.validated;
                            //userService.Save(data);
                            userService.update($scope.user);
                            mixpanel.identify($scope.user.username);
                            mixpanel.people.set({
                                "Validated Email": $scope.user.validated,
                                "$email": $scope.user.email
                            });
                            mixpanel.track("Re-send Email Validation", {
                                "Page": "Email Confirmation Page",
                                "Type": "Action",
                                "Text": "Re-send email verification",
                                "Success": true,
                                "Username": $scope.user.username,
                                "$email": $scope.user.email,
                                "Company": $scope.user.company,
                                "Job Title": $scope.user.jobTitle,
                                "Role": $scope.user.companyType,
                                "Subrole": $scope.user.companySubType
                            });
                            $scope.loadingSeding = false;
                            if ($scope.user.validated == true)
                                $state.go('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                            else
                                Notification.success({title: 'Mail sent!', message: 'Please, check your inbox :)'});
                        }).catch(function (response) {
                            mixpanel.identify($scope.user.username);
                            mixpanel.track("Re-send Email Validation", {
                                "Page": "Email Confirmation Page",
                                "Type": "Action",
                                "Text": "Re-send email verification",
                                //"Success": false,
                                "Error": true,
                                "ErrorStatus": response.status,
                                "ErrorData": response.data,
                                "Username": $scope.user.username,
                                "$email": $scope.user.email
                            });
                            //console.log(status);
                            //console.log(data);
                            $scope.status = status;
                            $scope.loadingSeding = false;
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                if (response.status === 400) {
                                    Notification.error({title: 'Error (' + response.status + ')', message: 'This email already exists.'});
                                } else {
                                    Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                }
                            }
                        });
                    }
                };
            }])
        // register step4 controller
        .controller('AutoLoginController', ['$scope', '$http', '$state', 'userService', 'Notification', 'ENV', '$cookies', function ($scope, $http, $state, userService, Notification, ENV, $cookies) {
                console.log("### CONTROLLER: AutoLoginController ####");
                $scope.isEmailValidated = false;
                $scope.hash = $state.params.hash;
                $scope.email = $state.params.email;
                $scope.username = $state.params.username;
                $scope.token = $state.params.token;
                if ((typeof $scope.hash === 'undefined' || typeof $scope.email === 'undefined' || typeof $scope.username === 'undefined' || typeof $scope.token === 'undefined' || $scope.hash == '' || $scope.email == '' || $scope.username == '' || $scope.token == '')) {
                    Notification.error({title: 'Error', message: 'There is a problem with the link. Please contact the support.'});
                    mixpanel.identify($scope.email);
                    mixpanel.track("Email Validation Link", {
                        "Page": "Email Validation Link Page",
                        "Type": "Action",
                        //"Success": false,
                        "Error": true,
                        "ErrorStatus": 404,
                        "ErrorData": "Mandatory Parameters",
                        "Username": $scope.username,
                        "$email": $scope.email
                    });
                } else {
                    /*trackJs.console.log(JSON.stringify({
                     "hash": $scope.hash,
                     "email": $scope.email,
                     "username": $scope.username
                     }));*/
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/public/mail/validation',
                        data: {
                            hash: $scope.hash,
                            email: $scope.email,
                            username: $scope.username
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.data.status === true) {
                            mixpanel.identify($scope.email);
                            mixpanel.people.set({
                                "Validated Email": true
                            });
                            mixpanel.track("Email Validation Link", {
                                "Page": "Email Validation Link Page",
                                "Type": "Action",
                                "Success": true,
                                "ErrorStatus": response.status,
                                "ErrorData": response.data,
                                "Username": $scope.username,
                                "$email": $scope.email
                            });
                            if ($scope.token == 'signin')
                                $state.go('access.signin');
                            else {
                                //localStorage.setItem('id_token',$scope.token);
                                try {
                                    localStorage.setItem('id_token', $scope.token);
                                } catch (e) {
                                    try {
                                        $cookies.id_token = $scope.token;
                                    } catch (e) {
                                        Notification.error({title: 'Error', message: 'Your account has been created but your browser do not allow neither Cookies nor LocalStorage. Please change your browser and login.'});
                                    }
                                }
                                // USER
                                $scope.user = response.data.user;
                                //console.log($scope.user);
                                userService.update($scope.user);
                                if (angular.isUndefined($scope.user.companyType) || $scope.user.companyType == null)
                                    $state.go('access.step2');
                                else
                                    $state.go('app.page.profile', {'firstname': $scope.user.name.toLowerCase(), 'lastname': $scope.user.lastname.toLowerCase()});
                            }
                        } else {
                            Notification.error({title: 'Error', message: 'There is a problem with the link. Please contact the support.'});
                            mixpanel.identify($scope.email);
                            mixpanel.track("Email Validation Link", {
                                "Page": "Email Validation Link Page",
                                "Type": "Action",
                                "Error": true,
                                "ErrorStatus": response.status,
                                "ErrorData": "There is a problem with the link. Please contact the support.",
                                "Username": $scope.username,
                                "$email": $scope.email
                            });
                        }
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: '"There is a problem with the link. Please contact the support.'});
                            mixpanel.identify($scope.email);
                            mixpanel.track("Email Validation Link", {
                                "Page": "Email Validation Link Page",
                                "Type": "Action",
                                "Error": true,
                                "ErrorStatus": response.status,
                                "ErrorData": response.data,
                                "Username": $scope.username,
                                "$email": $scope.email
                            });
                        }
                    });
                }
                ;
            }])
        // tab controller
        .controller('CustomTabController', ['$scope', function ($scope) {
                console.log("### CONTROLLER: CustomTabController ####");
                $scope.tabs = [true, false, false];
                $scope.tab = function (index) {
                    angular.forEach($scope.tabs, function (i, v) {
                        $scope.tabs[v] = false;
                    });
                    $scope.tabs[index] = true;
                };
            }])
        // USER controller
        .controller('UserController', ['$scope', 'userService', 'myUser', '$state', '$cookies', '$filter', '$uibModal', function ($scope, userService, myUser, $state, $cookies, $filter, $uibModal) {
                console.log("### CONTROLLER: UserController ####");
                //$scope.user = userService.user;
                $scope.user = myUser;
                if (($scope.user.competences && $scope.user.competences.length) || ($scope.user.languages && $scope.user.languages.length) || $scope.user.summary || ($scope.user.experiences && $scope.user.experiences.length && $scope.user.experiences[0].jobtitle)) {
                    $scope.activetab = 'aboutme';
                } else {
                    $scope.activetab = 'mobiletraffic';
                }
                $scope.changetab = function (tab) {
                    $scope.activetab = tab;
                };
                /*trackJs.configure({
                 // Custom session identifier.
                 sessionId: $scope.user.id,
                 // Custom user identifier.
                 userId: $scope.user.username,
                 // Custom application identifier.
                 version: JSON.stringify($scope.user)
                 
                 });*/
                //console.log($scope.user);
                var loggedin = localStorage.getItem('id_token') != null || ($cookies.id_token != 'null' && !angular.isUndefined($cookies.id_token));
                if (!loggedin)
                    $state.go('access.signin');
                userService.update($scope.user);
                /*if(angular.isUndefined($scope.user.hasVisitedOwnProfile) || $scope.user.hasVisitedOwnProfile!=true){
                 $modal.open({
                 templateUrl: 'tpl/forms/LinkedInShare.html?v=0.5.0',
                 controller: 'LinkedInShareModalInstance',
                 size: 'sm'
                 });
                 }*/
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });
                $scope.hasTracking = function (trackingServices) {
                    var has = false;
                    var other = false;
                    angular.forEach(trackingServices, function (val, key) {
                        if (Array.isArray(val)) {
                            if (val.length > 0 && other) {
                                has = true;
                            }
                        } else if (val == true) {
                            if (key == 'other') {
                                other = true;
                            } else {
                                has = true;
                            }
                        }
                    });
                    return has;
                };
                $scope.dateArray = [];
                angular.forEach($scope.user.buyTraffic, function (buy, key) {
                    if (angular.isUndefined(buy.dateType)) {
                        var tempDate = 'Anytime';
                        if (buy.fromperiod || buy.toperiod) {
                            tempDate = $filter('date')(buy.fromperiod || 'now', 'dd MMM yyyy');
                            tempDate += ' - ';
                            tempDate += $filter('date')(buy.toperiod, 'dd MMM yyyy');
                        }
                        $scope.dateArray[key] = tempDate;
                    } else {
                        switch (buy.dateType) {
                            case 'year':
                                $scope.dateArray[key] = 'This year';
                                break;
                            case 'anytime':
                                $scope.dateArray[key] = 'Any time';
                                break;
                            case 'pick':
                                var tempDate = 'Undefined';
                                if (buy.fromperiod && !buy.toperiod) {
                                    tempDate = $filter('date')(buy.fromperiod, 'dd MMM yyyy');
                                }
                                if (!buy.fromperiod && buy.toperiod) {
                                    tempDate = $filter('date')(buy.toperiod, 'dd MMM yyyy');
                                }
                                if (buy.fromperiod && buy.toperiod) {
                                    tempDate = $filter('date')(buy.fromperiod, 'dd MMM yyyy');
                                    tempDate += ' - ';
                                    tempDate += $filter('date')(buy.toperiod, 'dd MMM yyyy');
                                }
                                $scope.dateArray[key] = tempDate;
                                break;
                            case 'undefined':
                            default:
                                $scope.dateArray[key] = 'Undefined';
                                break;
                        }
                    }
                });
                mixpanel.identify($scope.user.username);
                mixpanel.alias($scope.user.id, $scope.user.email);
                mixpanel.people.set({
                    "$first_name": $scope.user.name,
                    "$last_name": $scope.user.lastname,
                    "Role": $scope.user.companyType,
                    "Subrole": $scope.user.companySubType,
                    "$email": $scope.user.email,
                    "Username": $scope.user.username,
                    "Company": $scope.user.company,
                    "Job Title": $scope.user.jobTitle,
                    "Company Exists": (angular.isUndefined($scope.user.companyPage) || $scope.user.companyPage == null) ? false : true,
                    "Is Admin": (!angular.isUndefined($scope.user.companyPage) && $scope.user.companyPage != null && !angular.isUndefined($scope.user.companyPage.administrator) && $scope.user.companyPage.administrator != null) ? true : false
                });
                mixpanel.track("My Profile Page", {
                    "Page": "My Profile Page",
                    "Type": "Page View"
                });
                mixpanel.alias($scope.user.id, $scope.user.email);
            }])
        // SHOW USER controller
        .controller('ShowUserController', ['$scope', 'userService', '$location','$state', 'viewUser', '$filter', '$rootScope', 'ENV', '$uibModal', '$timeout', function ($scope, userService, $location,$state, viewUser, $filter, $rootScope, ENV, $uibModal, $timeout) {
                console.log("### CONTROLLER: ShowUserController ####");
                //console.log("APP.JS VIEW USER:");
                //console.log(viewUser);
                if (angular.isUndefined(viewUser) || !viewUser) {
                    $state.go('app.page.feed');
                }
                $scope.selectedIndex = 0;
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                });

                if (angular.isUndefined(viewUser.customBoxname) || !viewUser.customBoxname) {
                    viewUser.customBoxname = 'Offers';
                }

                if (angular.isUndefined(viewUser.profileOrder) || !viewUser.profileOrder) {
                    viewUser.profileOrder = [
                        {text: 'Services', i: 1},
                        {text: 'Experience', i: 2},
                        {text: 'Education', i: 3},
                        {text: 'About', i: 4},
                        {text: viewUser.customBoxname, i: 5}
                    ];
                } else if (viewUser.profileOrder.length <= 4) {
                    viewUser.profileOrder.push({text: viewUser.customBoxname, i: 5});
                }

                if (viewUser.keywords) {
                    viewUser.keywords = viewUser.keywords.filter(function (item) {
                        return item != angular.lowercase(viewUser.name) && item != angular.lowercase(viewUser.lastname) && item != angular.lowercase(viewUser.jobTitle) && item != angular.lowercase(viewUser.company) && item != angular.lowercase(viewUser.city);
                    });
                }

                $scope.serviceTabs = [];
                angular.forEach(viewUser.services, function (v, k) {
                    $scope.serviceTabs.push({
                        'service': v.service,
                        'key': k
                    });
                });
                $scope.updateSelectedIndex = function (index) {
                    $scope.selectedIndex = index;
                };
                /*$scope.viewModelImage = false;
                 var img = new Image();
                 
                 img.onload = function () {
                 if (this.width > 350 && this.height > 350)
                 {
                 $scope.viewModelImage = true;
                 
                 }
                 };
                 
                 var url = ENV.baseUrl + viewUser.avatar;
                 $scope.profileOriginalUrl = url.replace(/^(.+)(\..+)$/, '$1-original$2');
                 img.src = $scope.profileOriginalUrl;*/
                //$scope.profileOriginalUrl = ENV.baseUrl + viewUser.avatar;
                if (viewUser.avatar) {
                    viewUser.avatar = viewUser.avatar.replace(/^(.+)(\..+)$/, '$1-original$2');
                }

                $scope.viewProifleImage = function () {
                    //if ($scope.viewModelImage) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/userProfileAvatar.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                        },
                        size: 'share',
                        windowClass: 'centered-modal modal-w-auto',
                        scope: $scope
                    });
                    //}
                };
                $scope.viewUser = viewUser;
                var user = $filter('capitalize')(viewUser.name + " " + viewUser.lastname);
                $rootScope.pageTitle = user;
                if (viewUser.company) {
                    $rootScope.pageTitle = $rootScope.pageTitle + ', ' + viewUser.company;
                      $rootScope.ogTitle = $rootScope.pageTitle;
                }
                $rootScope.pageTitle = $rootScope.pageTitle + " - Mobintouch";
                if (viewUser.company && viewUser.company !== null && !angular.isUndefined(viewUser.company))
                {
                     $rootScope.ogUrl = $location.absUrl();
                     if($scope.viewUser.avatar != null){
                        $rootScope.ogImage = ENV.baseUrl + $scope.viewUser.avatar;
                     }
                    //$rootScope.pageDescription = "View the profile of " + pageTitle + ", " + viewUser.jobTitle + " at " + viewUser.company + ". Mobintouch, the Mobile Advertising Social Network.";
                    $rootScope.pageDescription = viewUser.jobTitle + ' at ' + viewUser.company + ' - View ' + user + '\'s profile on Mobintouch, the mobile startup and apps network.';
                } else {
                     $rootScope.ogUrl = $location.absUrl();
                     if($scope.viewUser.avatar != null){
                    $rootScope.ogImage = ENV.baseUrl + $scope.viewUser.avatar;
                }
                    //$rootScope.pageDescription = "View the profile of " + pageTitle + ", " + viewUser.jobTitle + ". Mobintouch, the Mobile Advertising Social Network.";
                    $rootScope.pageDescription = viewUser.jobTitle + ' - View ' + user + '\'s profile on Mobintouch, the mobile startup and apps network.';
                }

                $scope.viewMutualConnections = function (connection) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/mutualConnections.html?v=' + ENV.latestUpdate,
                        controller: function ($uibModalInstance) {
                            $scope.mConnections = connection; //mutualConnections;
                            $scope.connectionWith = viewUser.name + " " + viewUser.lastname;
                            //console.log(mutualConnections);
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                        },
                        size: 'md',
                        windowClass: 'centered-modal',
                        scope: $scope
                    });
                };

                $scope.viewMoreUserInterests = function () {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/userInterests.html?v=' + ENV.latestUpdate,
                        controller: function ($uibModalInstance) {
                            $scope.interestedin = viewUser.keywords;
                            $scope.interestUser = viewUser.name + " " + viewUser.lastname;
                            //console.log(mutualConnections);
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                        },
                        size: 'md',
                        windowClass: 'centered-modal',
                        scope: $scope
                    });
                };

                //Scroll page to the particular element provided on url hash
                $timeout(function () {
                    var hash = window.location.hash;
                    // now scroll to element with that id
                    if (typeof hash != 'undefined' && angular.element(hash).length > 0)
                        angular.element('html, body').animate({scrollTop: angular.element(hash).offset().top - 90});
                }, 2000);

                //console.log($scope.viewUser);
                return;
                if ((viewUser.competences && viewUser.competences.length) || (viewUser.languages && viewUser.languages.length) || viewUser.summary || (viewUser.experiences && viewUser.experiences.length && viewUser.experiences[0].jobtitle)) {
                    $scope.activetab = 'aboutme';
                } else {
                    $scope.activetab = 'mobiletraffic';
                }
                $scope.changetab = function (tab) {
                    $scope.activetab = tab;
                };
                //$scope.user = userService.user;
                //console.log($scope.user);
                $scope.username = $state.params.username;
                // Retrieve user from the database
                if ($scope.username) {
                    //var request = $http.get(ENV.apiEndpoint + '/api/public/user/'+$scope.username);
                    //request.success(function(data) {
                    var data = viewUser;
                    var pageTitle = $filter('capitalize')(viewUser.name + " " + viewUser.lastname);
                    $rootScope.pageTitle = pageTitle + " | Mobintouch";
                    if (viewUser.company && viewUser.company !== null && !angular.isUndefined(viewUser.company))
                    {
                        $rootScope.pageDescription = "View the profile of " + pageTitle + ", " + viewUser.jobTitle + " at " + viewUser.company + ". Mobintouch, the Mobile Advertising Social Network.";
                    } else
                    {
                        $rootScope.pageDescription = "View the profile of " + pageTitle + ", " + viewUser.jobTitle + ". Mobintouch, the Mobile Advertising Social Network.";
                    }
                    //console.log("VIEW USER:");
                    //console.log(data);
                    $scope.view = data;
                    $scope.sellTraffic = $scope.view.sellTraffic;
                    $scope.boxSelltraffic = [];
                    $scope.pricing = [];
                    var index = 0;
                    angular.forEach($scope.sellTraffic, function (array, key) {
                        //console.log(array);
                        if (angular.isUndefined(array.pricing)) {
                            array.pricing = [
                                {
                                    "cpa": false,
                                    "cps": false,
                                    "cpc": false,
                                    "cpv": false,
                                    "cpd": false,
                                    "cpi": false,
                                    "cpl": false,
                                    "cpm": false,
                                    "dclick": false,
                                    "ctc": false,
                                    "ppcall": false
                                }
                            ];
                        }
                        if (angular.isUndefined(array.platform)) {
                            array.platform = [
                                {
                                    "ios": false,
                                    "android": false,
                                    "windows": false,
                                    "blackberry": false,
                                    "web": false,
                                    "unity": false,
                                    "baba": false
                                }
                            ];
                        }
                        angular.forEach(array.countries, function (v, k) {
                            ////console.log(v);
                            $scope.boxSelltraffic.push({
                                "country": v,
                                pricing: {"cpa": array.pricing[0].cpa,
                                    "cps": array.pricing[0].cps,
                                    "cpc": array.pricing[0].cpc,
                                    "cpv": array.pricing[0].cpv,
                                    "cpd": array.pricing[0].cpd,
                                    "cpi": array.pricing[0].cpi,
                                    "cpl": array.pricing[0].cpl,
                                    "cpm": array.pricing[0].cpm,
                                    "dclick": array.pricing[0].dclick,
                                    "ctc": array.pricing[0].ctc,
                                    "ppcall": array.pricing[0].ppcall
                                },
                                "isOtherPricing": array.isOtherPricing,
                                "otherPricing": array.otherPricing,
                                "incentivized": array.incentivized,
                                "nonincentivized": array.nonincentivized,
                                platform: {"ios": array.platform[0].ios,
                                    "android": array.platform[0].android,
                                    "windows": array.platform[0].windows,
                                    "blackberry": array.platform[0].blackberry,
                                    "web": array.platform[0].web,
                                    "unity": array.platform[0].unity,
                                    "baba": array.platform[0].baba},
                                "isOtherPlatform": array.isOtherPlatform,
                                "otherPlatform": array.otherPlatform,
                                "adformat": array.adformat,
                                "userType": array.userType,
                                "targeting": array.targeting,
                                "trading": array.trading
                            });
                        });
                    });
                    //console.log("table selling traffic");
                    //console.log($scope.sellTraffic);
                    /*  }).
                     error(function(data, status) {
                     //console.log(data);
                     if(status==404) $state.go('access.404');
                     });
                     */
                    mixpanel.identify($scope.user.username);
                    mixpanel.people.increment("Visit Profile");
                    mixpanel.track('Visit Profile', {
                        "Page": "Public Profile Page",
                        "Type": "Page View",
                        "Text": pageTitle,
                        "Success": true,
                        "Username": $scope.user.username,
                        "$email": $scope.user.email,
                        "Company": $scope.user.company,
                        "Job Title": $scope.user.jobTitle,
                        "Role": $scope.user.companyType,
                        "Subrole": $scope.user.companySubType,
                        "visitedProfileCompanyName": $scope.user.company,
                        "visitedProfileJobTitle": $scope.user.jobTitle,
                        "visitedProfileRole": $scope.user.companyType,
                        "visitedProfileSubrole": $scope.user.companySubType
                    });
                    /*
                     $scope.mutualConnections = [];
                     angular.forEach($scope.user.inTouch, function (value, uIntouchIndex) {
                     angular.forEach($scope.view.inTouch, function (object, vIntouchIndex) {
                     if (value.username == object.username && object.status == 3) {
                     $scope.mutualConnections.push(object);
                     }
                     });
                     });
                     
                     
                     $scope.test = $scope.user.inTouch.filter(function (n) {
                     return $scope.view.inTouch.indexOf(n.username) != -1;
                     });
                     
                     console.log($scope.mutual);
                     */
                }
                $scope.hasTracking = function (trackingServices) {
                    var has = false;
                    var other = false;
                    angular.forEach(trackingServices, function (val, key) {
                        if (Array.isArray(val)) {
                            if (val.length > 0 && other) {
                                has = true;
                            }
                        } else if (val == true) {
                            if (key == 'other') {
                                other = true;
                            } else {
                                has = true;
                            }
                        }
                    });
                    return has;
                };
                $scope.dateArray = [];
                angular.forEach($scope.view.buyTraffic, function (buy, key) {
                    if (angular.isUndefined(buy.dateType)) {
                        var tempDate = 'Anytime';
                        if (buy.fromperiod || buy.toperiod) {
                            tempDate = $filter('date')(buy.fromperiod || 'now', 'dd MMM yyyy');
                            tempDate += ' - ';
                            tempDate += $filter('date')(buy.toperiod, 'dd MMM yyyy');
                        }
                        $scope.dateArray[key] = tempDate;
                    } else {
                        switch (buy.dateType) {
                            case 'year':
                                $scope.dateArray[key] = 'This year';
                                break;
                            case 'anytime':
                                $scope.dateArray[key] = 'Any time';
                                break;
                            case 'pick':
                                var tempDate = 'Undefined';
                                if (buy.fromperiod && !buy.toperiod) {
                                    tempDate = $filter('date')(buy.fromperiod, 'dd MMM yyyy');
                                }
                                if (!buy.fromperiod && buy.toperiod) {
                                    tempDate = $filter('date')(buy.toperiod, 'dd MMM yyyy');
                                }
                                if (buy.fromperiod && buy.toperiod) {
                                    tempDate = $filter('date')(buy.fromperiod, 'dd MMM yyyy');
                                    tempDate += ' - ';
                                    tempDate += $filter('date')(buy.toperiod, 'dd MMM yyyy');
                                }
                                $scope.dateArray[key] = tempDate;
                                break;
                            case 'undefined':
                            default:
                                $scope.dateArray[key] = 'Undefined';
                                break;
                        }
                    }
                });
            }])

        // Edit Profile controller
        .controller('ProfileFormController', ['$rootScope', '$scope', '$http', '$state', '$filter', '$timeout', 'AuthService', 'userService', 'companyService', 'Notification', 'ENV', 'myUser', '$location', '$uibModal', '$cookies', '$q', 'suggestKeywords', 'connectionSuggestions', '$window', '$templateCache', '$compile', 'autocompleteKeywords', function ($rootScope, $scope, $http, $state, $filter, $timeout, AuthService, userService, companyService, Notification, ENV, myUser, $location, $uibModal, $cookies, $q, suggestKeywords, connectionSuggestions, $window, $templateCache, $compile, autocompleteKeywords) {
                console.log("### CONTROLLER: ProfileFormController ####");
                delete $cookies["new_registration"];
                $rootScope.updateActiveLink('profile');
                $scope.user.companyid = null;
                $scope.fromJobModal = false;
                $scope.editprofile = false;
                $scope.editinterest = false;
                $scope.updateProfileDetails = false;
                $scope.updateInstantContacts = false;
                $scope.updateInterestedKeywords = false;
                $scope.loadingConnectionSuggestions = true;
                $scope.editModeExperience = false;
                $scope.connectionSuggestions = [];
                $scope.experience = {};
                $scope.about = {};
                $scope.experience.type = 'Employee';
                $scope.service = {};
                $scope.subServicesTpl = {
                    'Marketing': 'tpl/forms/sub_services/marketing.html',
                    'Monetization': 'tpl/forms/sub_services/monetization.html',
                    'Software Development': 'tpl/forms/sub_services/software_development.html',
                    'Design': 'tpl/forms/sub_services/design.html',
                    'Administrative Support': 'tpl/forms/sub_services/administrative_support.html',
                    'Human Resources': 'tpl/forms/sub_services/human_resources.html',
                    'Writing': 'tpl/forms/sub_services/writing.html'
                };
                $scope.education = {};


                $timeout(function () {
                    if ($rootScope.fromJobModal && $rootScope.fromJobModal !== null) {
                        $scope.fromJobModal = true;
                    }
                    $rootScope.fromJobModal = null;
                }, 300);



                $scope.myUser = myUser;
                var user = $filter('capitalize')(myUser.name + " " + myUser.lastname);
                $rootScope.pageTitle = user;

                $rootScope.pageTitle = $rootScope.pageTitle + " - Mobintouch";
                if (myUser.company && myUser.company !== null && !angular.isUndefined(myUser.company))
                {
                    $rootScope.ogUrl = $location.absUrl();
                    if(myUser.avatar != null){
                        $rootScope.ogImage = ENV.baseUrl + myUser.avatar;
                    }
                    //$rootScope.pageDescription = "View the profile of " + pageTitle + ", " + myUser.jobTitle + " at " + myUser.company + ". Mobintouch, the Mobile Advertising Social Network.";
                    $rootScope.pageDescription = myUser.jobTitle + ' at ' + myUser.company + ' - View ' + user + '\'s profile on Mobintouch, the mobile startup and apps network.';
                } else {
                     $rootScope.ogUrl = $location.absUrl();
                     if(myUser.avatar!=null){
                        $rootScope.ogImage = ENV.baseUrl + myUser.avatar;
                    }
                    //$rootScope.pageDescription = "View the profile of " + pageTitle + ", " + myUser.jobTitle + ". Mobintouch, the Mobile Advertising Social Network.";
                    $rootScope.pageDescription = myUser.jobTitle + ' - View ' + user + '\'s profile on Mobintouch, the mobile startup and apps network.';
                }

                //New Design Code
                if (myUser.keywords) {
                    myUser.keywords = myUser.keywords.filter(function (item) {
                        return item != angular.lowercase($scope.user.name) && item != angular.lowercase($scope.user.lastname) && item != angular.lowercase($scope.user.jobTitle) && item != angular.lowercase($scope.user.company) && item != angular.lowercase($scope.user.city);
                    });
                }

                if (angular.isUndefined(myUser.hasVisitedOwnProfile) || !myUser.hasVisitedOwnProfile) {
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/linkedin/first-profile-visit',
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        myUser.hasVisitedOwnProfile = true;
                    }).catch(function (response) {

                    })
                }

                if (angular.isUndefined(myUser.customBoxname) || !myUser.customBoxname) {
                    myUser.customBoxname = 'Offers';
                }

                if (angular.isUndefined(myUser.profileOrder) || !myUser.profileOrder) {
                    myUser.profileOrder = [
                        {text: 'Services', i: 1},
                        {text: 'Experience', i: 2},
                        {text: 'Education', i: 3},
                        {text: 'About', i: 4},
                        {text: myUser.customBoxname, i: 5}
                    ];
                } else if (myUser.profileOrder.length <= 4) {
                    myUser.profileOrder.push({text: myUser.customBoxname, i: 5});
                } else if (myUser.profileOrder.length <= 5) {
                    angular.forEach(myUser.profileOrder, function (v, k) {
                        if (v.text == 'CustomServices') {
                            v.text = 'Offers';
                        }
                    });
                }


                $scope.serviceStep = 1;
                $scope.selectedIndex = 0;
                $scope.addedServices = [];
                if (angular.isUndefined(myUser.services) || !myUser.services || myUser.services.length <= 0) {
                    $scope.serviceStep = 2;
                } else {
                    angular.forEach(myUser.services, function (service, key) {
                        $scope.addedServices.push(service.service);
                    });
                    $scope.serviceTabs = [];
                    angular.forEach(myUser.services, function (v, k) {
                        $scope.serviceTabs.push({
                            'service': v.service,
                            'key': k
                        });
                    });
                }


                $scope.user = myUser;
                $scope.initUser = angular.copy($scope.user);
                userService.update($scope.user);
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user;
                    if ($scope.user.keywords) {
                        $scope.user.keywords = $scope.user.keywords.filter(function (item) {
                            return item != angular.lowercase($scope.user.name) && item != angular.lowercase($scope.user.lastname) && item != angular.lowercase($scope.user.jobTitle) && item != angular.lowercase($scope.user.company) && item != angular.lowercase($scope.user.city);
                        });
                    }
                    $scope.initUser.cover = $scope.user.cover;
                    $scope.initUser.avatar = $scope.user.avatar;

                    if (angular.isUndefined($scope.user.customBoxname) || !$scope.user.customBoxname) {
                        $scope.user.customBoxname = 'Offers';
                    }

                    if (angular.isUndefined($scope.user.profileOrder) || !$scope.user.profileOrder) {
                        $scope.user.profileOrder = [
                            {text: 'Services', i: 1},
                            {text: 'Experience', i: 2},
                            {text: 'Education', i: 3},
                            {text: 'About', i: 4},
                            {text: $scope.user.customBoxname, i: 5}
                        ];
                    } else if ($scope.user.profileOrder.length <= 4) {
                        $scope.user.profileOrder.push({text: $scope.user.customBoxname, i: 5});
                    }

                });
                $scope.months = [
                    {"key": "01", "value": "Jan"}, {"key": "02", "value": "Feb"}, {"key": "03", "value": "Mar"}, {"key": "04", "value": "Apr"}, {"key": "05", "value": "May"}, {"key": "06", "value": "Jun"}, {"key": "07", "value": "Jul"}, {"key": "08", "value": "Aug"}, {"key": "09", "value": "Sep"}, {"key": "10", "value": "Oct"}, {"key": "11", "value": "Nov"}, {"key": "12", "value": "Dec"}
                ];
                $scope.years = $.map($(Array(70)), function (val, i) {
                    return i + new Date().getUTCFullYear() - 69;
                });
                $scope.days = $.map($(Array(31)), function (val, i) {
                    return i + 1 < 10 ? "0" + (i + 1) : i + 1;
                });
                $scope.updateHasEmployer = function () {
                    $scope.hasEmployer = $scope.user.hasEmployer;
                };
                $scope.editPersonalDetails = function (form) {

                    if (!form.firstname.$valid) {
                        form.firstname.$setDirty();
                        form.firstname.$setValidity('required', false);
                    }

                    if (!form.lastname.$valid) {
                        form.lastname.$setDirty();
                        form.lastname.$setValidity('required', false);
                    }

                    if (!form.formatedAddress.$valid) {
                        form.formatedAddress.$setDirty();
                        form.formatedAddress.$setValidity('required', false);
                    }

                    if (!form.miniResume.$valid) {
                        form.miniResume.$setDirty();
                        form.miniResume.$setValidity('required', false);
                    }

                    if (!form.currentstatus.$valid) {
                        form.currentstatus.$setDirty();
                        form.currentstatus.$setValidity('required', false);
                    }

                    if (!form.gender.$valid) {
                        form.gender.$setDirty();
                        form.gender.$setValidity('required', false);
                    }

                    if (!form.birthmonth.$valid) {
                        form.birthmonth.$setDirty();
                        form.birthmonth.$setValidity('required', false);
                    }

                    if (!form.birthday.$valid) {
                        form.birthday.$setDirty();
                        form.birthday.$setValidity('required', false);
                    }

                    if (!form.birthyear.$valid) {
                        form.birthyear.$setDirty();
                        form.birthyear.$setValidity('required', false);
                    }

                    if (!form.jobtitle.$valid) {
                        form.jobtitle.$setDirty();
                        form.jobtitle.$setValidity('required', false);
                    }

                    if (!form.role.$valid) {
                        form.role.$setDirty();
                        form.role.$setValidity('required', false);
                    }



                    if (form.$invalid) {
                        Notification.error({title: 'Invalid details', message: 'Please provide correct informations!'});
                        return;
                    }

                    if (!$scope.user.name) {
                        Notification.error({title: 'Invalid details', message: 'First name is required!'});
                        return;
                    } else if (!$scope.user.lastname) {
                        Notification.error({title: 'Invalid details', message: 'Last name is required!'});
                        return;
                    } else if (!$scope.user.jobTitle) {
                        Notification.error({title: 'Invalid details', message: 'Jobtitle is required!'});
                        return;
                    }

                    $scope.updateProfileDetails = true;
                    if ($scope.details) {
                           $scope.user.geometrylocation = $scope.details.geometry.location.toJSON();

                        var loc = $filter('locations')($scope.details.address_components);
                        $scope.user.city = loc.city;
                        $scope.user.country = loc.country;
                        $scope.user.basedCountry = loc.basedCountry;

                        /*angular.forEach($scope.user.addressDetails.address_components, function (value, key) {
                         if (value.types[0] === 'locality') {
                         $scope.user.city = value.long_name;
                         } else if (value.types[0] === 'country') {
                         $scope.user.basedCountry = value.short_name;
                         }
                         });*/
                    }

                    if ($scope.user.hasEmployer == true || $scope.user.hasEmployer == 'true') {
                        $scope.user.hasEmployer = true;
                    } else {
                        if ($scope.user.companyPage && !$scope.user.companyPage.administrator) {
                            $scope.user.company = '';
                            $scope.user.companyid = null;
                            $scope.user.hasEmployer = false;
                        } else {
                            $scope.user.hasEmployer = true;
                        }
                    }
                    
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/personaldetails',
                        data: {
                            name: $scope.user.name,
                            lastname: $scope.user.lastname,
                            gender: $scope.user.gender,
                            city: $scope.user.city,
                            country: $scope.user.country,
                            basedCountry: $scope.user.basedCountry,
                            birthdayDD: $scope.user.birthdayDD,
                            birthdayMM: $scope.user.birthdayMM,
                            birthdayYYYY: $scope.user.birthdayYYYY,
                            geometrylocation: $scope.user.geometrylocation,
                            formattedaddress: $scope.user.formatedAddress,
                            jobTitle: $scope.user.jobTitle,
                            companyType: $scope.user.companyType,
                            companySubType: $scope.user.companySubType,
                            company: $scope.user.company,
                            companyid: $scope.user.companyid,
                            hasEmployer: $scope.user.hasEmployer,
                            grossSalary: $scope.user.grossSalary,
                            currency: $scope.user.currency,
                            currentStatus: $scope.user.currentStatus,
                            miniResume: $scope.user.miniResume ? $scope.user.miniResume : '',
                            linkedin: $scope.user.linkedin,
                            twitter: $scope.user.twitter,
                            facebook: $scope.user.facebook,
                            github: $scope.user.github,
                            stackOverflow: $scope.user.stackOverflow,
                            dribbble: $scope.user.dribbble,
                            behance: $scope.user.behance,
                            instagram: $scope.user.instagram,
                            pinterest: $scope.user.pinterest,
                            website: $scope.user.website,
                            otherLink: $scope.user.otherLink
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data;
                        userService.update(response.data);
                        $scope.updateProfileDetails = false;
                        $scope.initUser = angular.copy($scope.user);
                        if ($scope.user.companyPage)
                        {
                            var request = $http.post(ENV.apiEndpoint + '/api/company', {cache: true});
                            request.then(function (response) {
                                $scope.company = response.data;
                                $rootScope.companyPercentage = response.data.companyPercentage;
                                companyService.update($scope.company);
                            });
                        }
                        if (form)
                        {
                            form.$setPristine(true);
                        }
                        $scope.personalDetailsForm(false);
                        Notification.success({title: 'Saved', message: 'Profile details updated successfully'});
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.updateProfileDetails = false;
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.editInstantContactDetails = function (form) {
                    $scope.updateInstantContacts = true;
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/instantcontactdetails',
                        data: {
                            contactEmail: $scope.user.contactEmail,
                            phone: $scope.user.phone,
                            imContacts: $scope.user.imContacts
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data;
                        userService.update(response.data);
                        $scope.updateInstantContacts = false;
                        $scope.initUser = angular.copy($scope.user);
                        if (form)
                        {
                            form.$setPristine(true);
                        }
                        Notification.success({title: 'Saved', message: 'Instact contact information updated successfully'});
                        setTimeout(function () {
                            angular.element('html,body').trigger('click');
                        }, 500);
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.updateInstantContacts = false;
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.editInterestedKeywords = function (form) {
                    $scope.updateInterestedKeywords = true;
                    if ($scope.user.keywords.length <= 0) {
                        Notification.error({title: 'Error', message: 'Please add atleast one keyword'});
                        $scope.updateInterestedKeywords = false;
                    } else {
                        $http({
                            method: "PUT",
                            url: ENV.apiEndpoint + '/api/register/step/7/addkeywords',
                            data: {
                                keywords: $scope.user.keywords.map(function (tag) {
                                    return tag.text;
                                })
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            $scope.user = response.data;
                            userService.update($scope.user);
                            $scope.initUser = angular.copy($scope.user);
                            $scope.updateInterestedKeywords = false;
                            if (form)
                            {
                                form.$setPristine(true);
                            }
                            $scope.interestedKeywordsForm(false);
                            Notification.success({title: 'Saved', message: 'Your interested keywords has been updated.'});
                        }).catch(function (response) {
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                $scope.updateInterestedKeywords = false;
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            }
                        });
                    }
                };
                $scope.shareProfile = function (size) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/shareProfile.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                            $scope.copyToClipBoard = function (link) {
                                if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
                                    var textarea = document.createElement("textarea");
                                    textarea.textContent = 'https://www.mobintouch.com/profile/' + $scope.user.username;
                                    textarea.style.position = "fixed";
                                    document.body.appendChild(textarea);
                                    textarea.select();
                                    try {
                                        document.execCommand("copy"); // Security exception may be thrown by some browsers.
                                        Notification.success({title: 'Copied!', message: 'Link has been copied into clipboard.'});
                                    } catch (ex) {
                                        console.warn("Copy to clipboard failed.", ex);
                                    } finally {
                                        document.body.removeChild(textarea);
                                    }
                                }
                            }
                        },
                        scope: $scope,
                        windowClass: 'centered-modal',
                        size: 'md'
                    });
                };

                $scope.queryParamaters = $location.search();
                if (!angular.isUndefined($scope.queryParamaters.code) && $scope.queryParamaters.code.length > 0) {
                    $.post('https://api.medium.com/v1/tokens', {
                        grant_type: 'authorization_code',
                        code: $scope.queryParamaters.code,
                        client_secret: '5256f80cf25ab0dd988caecc922ca0e844f69445',
                        client_id: 'ff28a7659a6f',
                        redirect_uri: window.location.origin + window.location.pathname,
                    }).then(function (response) {
                        console.log(response);
                        /*$.ajax({
                         type: 'GET',
                         url: "https://api.medium.com/v1/me",
                         beforeSend: function (request) {
                         request.setRequestHeader("Authorization", 'Bearer ' + response.access_token);
                         },
                         processData: false, // tell jQuery not to process the data
                         contentType: false, // tell jQuery not to set contentType,
                         success: function (response) {
                         console.log(response);
                         }
                         })*/

                        /*if (!angular.isUndefined(response.access_token)) {
                         $.get('https://slack.com/api/users.list?token=' + response.access_token + '&presence=0&pretty=0', function (response) {
                         if (!angular.isUndefined(response.ok) && response.ok && response.members.length > 0)
                         {
                         var connections = [];
                         angular.forEach(response.members, function (member, index) {
                         if (member.name !== 'slackbot') {
                         connections.push({
                         'firstname': member.profile.first_name,
                         'lastname': member.profile.last_name,
                         'email': member.profile.email
                         });
                         }
                         });
                         //console.log(connections);
                         $scope.importConnections(connections, 'slack');
                         $scope.importingSlackConnections = false;
                         }
                         });
                         }*/
                    });
                }

                $scope.profileOreder = function () {
                    var modalInstance = $uibModal.open({
                        templateUrl: 'tpl/forms/sortServices.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {

                            $scope.cancel = function () {
                                $scope.user = angular.copy($scope.initUser);
                                userService.update($scope.user);
                                $uibModalInstance.dismiss('cancel');
                            };
                            $scope.editProfileOrder = function () {
                                $scope.updateProfileOrder = true;
                                $http({
                                    method: "PUT",
                                    data: {
                                        'profileOrder': $scope.user.profileOrder
                                    },
                                    url: ENV.apiEndpoint + '/api/edit/profile/order',
                                    headers: {'Content-Type': 'application/json'}
                                }).then(function (response) {
                                    $scope.user = response.data;
                                    userService.update($scope.user);
                                    Notification.success({title: 'Saved', message: 'Your profile order saved successfully.'});
                                    $scope.updateProfileOrder = false;
                                    $uibModalInstance.close($scope.user);
                                }).catch(function (response) {
                                    if (response.status === 401)
                                        $state.go('access.signin');
                                    else {
                                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                    }
                                    $scope.user = angular.copy($scope.initUser);
                                    userService.update($scope.user);
                                    $scope.updateProfileOrder = false;
                                });
                            };
                        },
                        windowClass: 'modal-w-auto centered-modal',
                        scope: $scope
                    });
                    modalInstance.result.then(function (data) {
                        $scope.initUser = angular.copy($scope.user);
                    }, function () {
                        $scope.user = angular.copy($scope.initUser);
                        userService.update($scope.user);
                    });
                };
                $scope.getSuggestKeywords = function () {
                    $http.get(ENV.apiEndpoint + '/api/keywordssuggestions')
                            .then(function (response) {
                                $scope.keywords = response.data;
                            })
                            .catch(function (response) {

                            });
                };
                $scope.keywordRemoved = function (keyword) {
                    $scope.keywords.unshift(keyword);
                };
                $scope.canceler = $q.defer();
                $scope.loadKeywords = function (query) {
                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.loadingkeywords = true;
                    return $http({method: 'GET', url: ENV.apiEndpoint + '/api/autocompletekeywords/' + query, timeout: $scope.canceler.promise}).then(function (response) {
                        $scope.loadingkeywords = false;
                        return response;
                    });
                };
                $scope.selectKeyword = function (keyword) {
                    $scope.keywords.splice($.inArray(keyword, $scope.keywords), 1);
                };
                $scope.addkeyword = function (keyword) {
                    if ($scope.user.keywords) {
                        var exists = $scope.user.keywords.some(function (obj) {
                            return obj.text === keyword;
                        });
                    }
                    if (exists !== true) {
                        $scope.user.keywords.push({'text': keyword});
                        $scope.keywords.splice($.inArray(keyword, $scope.keywords), 1);
                    }

                };
                $scope.cancel = function (form) {
                    $scope.user = angular.copy($scope.initUser);
                    userService.update($scope.user);
                    if (form)
                    {
                        form.$setPristine(true);
                    }
                };
                $scope.personalDetailsForm = function (value) {
                    if (value) {

                        angular.element('html,body').animate({scrollTop: 0}, 300);


                        $scope.$watch('user.formatedAddress', function (val) {

//                    if (!$scope.editCompanyDetails) {
//                        return;
//                    }
                         //   var form = angular.element("#editPersonalDetailsForm").scope().editPersonalDetailsForm;

                            if (val) {
                                var lc = document.getElementById('places');
                                var aService = new google.maps.places.AutocompleteService(lc);
                                if (val && val.length > 0) {
                                    var request = {
                                        input: val,
                                        types: ['(regions)']
                                    };

                                    $scope.listing = [];
                                    aService.getPredictions(request, function (results, status) {
                                        if (status === 'OK') {
                                            $scope.locationResult = results;
                                        }
                                    });

                                    if ($scope.details) {
                                        $scope.details = null;
                                    }

                                  /*  if (!$scope.details) {
                                        form.formatedAddress.$setValidity('required', false);
                                    } else {
                                        form.formatedAddress.$setValidity('required', true);
                                    }*/
                                }
                            }
                        });

                        $scope.onSelectLocation = function (item) {
                            var lc = document.getElementById('places');
                            var pService = new google.maps.places.PlacesService(lc);
                            var city;
                            var basedCountry;
                            var country;

                            pService.getDetails({
                                placeId: item.place_id
                            }, function (place, status) {
                                if (status === google.maps.places.PlacesServiceStatus.OK) {

                                    /*  angular.forEach(place.address_components, function (value, key) {
                                     if (value.types[0] === 'locality') {
                                     $scope.publicCompany.city = value.long_name;
                                     } else if (value.types[0] === 'country') {
                                     $scope.publicCompany.basedCountry = value.short_name;
                                     $scope.publicCompany.country = value.long_name;
                                     }
                                     });*/



                                    $scope.details = place;
                                    $scope.$apply('details');
                                }
                            });
                        };
                    }

                    $scope.editprofile = value;
                };
                $scope.interestedKeywordsForm = function (value) {
                    $scope.editinterest = value;
                    if (value)
                        angular.element('html,body').animate({scrollTop: 0}, 300);
                };
                $scope.getConnectionSuggestions = function () {
                    connectionSuggestions.get().$promise
                            .then(function (data) {
                                $scope.connectionSuggestions = data;
                                $scope.loadingConnectionSuggestions = false;
                            });
                };
                if (myUser.inTouch && myUser.inTouch.length > 0) {
                    $scope.getConnectionSuggestions();
                }
                $scope.notNow = function (index, userId) {
                    //$scope.connectionSuggestions.splice(index, 1);
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/connection/notnow',
                        data: {
                            userId: userId
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        if (angular.isUndefined($scope.user.notNow) || $scope.user.notNow == null)
                        {
                            $scope.user.notNow = [response.data];
                        } else {
                            var object = angular.extend({}, $scope.user.notNow, response.data);
                            $scope.user.notNow = object;
                        }
                        userService.update($scope.user);
                        $scope.connectionSuggestions.splice(index, 1);
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.connect = function (index, connection) {
                    connection.requesting = true;
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/connection/new',
                        data: {
                            userId: connection.id
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        if (angular.isUndefined($scope.user.inTouch) || $scope.user.inTouch == null)
                        {
                            $scope.user.inTouch = [response.data];
                        } else {
                            var object = angular.extend({}, $scope.user.inTouch, response.data);
                            $scope.user.inTouch = object;
                        }
                        userService.update($scope.user);
                        $scope.connectionSuggestions.splice(index, 1);
                    }).catch(function (response) {
                        console.log(response);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                    /*$uibModal.open({
                     templateUrl: 'tpl/forms/connection.html?v=' + ENV.latestUpdate,
                     controller: function ($scope, $uibModalInstance, userService) {
                     //console.log($scope.$parent);
                     $scope.user = userService.user;
                     $scope.$on('handleUser', function () {
                     $scope.user = userService.user;
                     });
                     $scope.cancel = function () {
                     $uibModalInstance.dismiss('cancel');
                     };
                     $scope.close = function (data) {
                     //console.log(JSON.stringify({"userId": userid}));
                     $http({
                     method: "POST",
                     url: ENV.apiEndpoint + '/api/connection/new',
                     data: {
                     userId: userid
                     },
                     headers: {'Content-Type': 'application/json'}
                     }).then(function (response) {
                     if (angular.isUndefined($scope.user.inTouch) || $scope.user.inTouch == null)
                     {
                     $scope.user.inTouch = [data];
                     } else {
                     var object = angular.extend({}, $scope.user.inTouch, data);
                     $scope.user.inTouch = object;
                     }
                     userService.update($scope.user);
                     $scope.connectionSuggestions.splice(index, 1);
                     }).catch(function (response) {
                     //console.log(status);
                     //console.log(data);
                     if (response.status === 401)
                     $state.go('access.signin');
                     else {
                     Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                     }
                     });
                     $uibModalInstance.close();
                     };
                     },
                     size: size,
                     scope: $scope
                     });*/
                };
                $scope.user.profileOrder.sort(function (a, b) {
                    return a.i > b.i;
                });
                $scope.sortableOptions = {
                    stop: function (e, ui) {
                        for (var index in $scope.user.profileOrder) {
                            $scope.user.profileOrder[index].i = index;
                        }
                    }
                };
                $scope.checkLocation = function (selectedIndex) {
                    if ($scope.user.services[selectedIndex].location && $scope.user.services[selectedIndex].location.geometry) {
                        $scope.user.services[selectedIndex].formatted_address = $scope.user.services[selectedIndex].location.formatted_address;
                    }
                };
                $scope.updateSelectedIndex = function (index) {
                    $scope.selectedIndex = index;
                };
                $scope.changeServiceStep = function (step) {
                    $scope.serviceStep = step;
                };
                $scope.backService = function () {
                    $scope.user.services = angular.copy($scope.initUser.services);
                    userService.update($scope.user);
                    $scope.changeServiceStep(2);
                    if ($scope.serviceAction == 'add') {
                        $scope.updateSelectedIndex($scope.selectedPrevIndex);
                    }
                    $scope.savingService = false;
                    $scope.formSubmited = false;
                };
                $scope.cancelService = function (selectedIndex) {
                    $scope.user.services = angular.copy($scope.initUser.services);
                    userService.update($scope.user);
                    $scope.changeServiceStep(1);
                    if (angular.isUndefined($scope.user.services) || !$scope.user.services)
                        $scope.changeServiceStep(2);
                    $scope.updateSelectedIndex(selectedIndex);
                    //$scope.serviceTabs[selectedIndex].active = true;
                    if ($scope.serviceAction == 'add') {
                        $scope.updateSelectedIndex($scope.selectedPrevIndex);
                        //$scope.serviceTabs[$scope.selectedPrevIndex].active = true;
                    }
                    $scope.savingService = false;
                    $scope.formSubmited = false;
                    $scope.otherService = false;
                };
                $scope.selectServiceToAdd = function (service) {


                    $scope.$watch('user.services[selectedIndex].formatted_address', function (val) {

//                    if (!$scope.editCompanyDetails) {
//                        return;
//                    }
                        var form = angular.element("#formService").scope().formService;

                        if (val) {
                            var lc = document.getElementById('location');
                            var aService = new google.maps.places.AutocompleteService(lc);
                            if (val && val.length > 0) {
                                var request = {
                                    input: val,
                                    types: ['(regions)']
                                };

                                $scope.listing = [];
                                aService.getPredictions(request, function (results, status) {
                                    if (status === 'OK') {
                                        $scope.locationResult = results;
                                    }
                                });

                                if ($scope.details) {
                                    $scope.details = null;
                                }

                                if (!$scope.details) {
                                    form.location.$setValidity('required', false);
                                } else {
                                    form.location.$setValidity('required', true);
                                }
                            }
                        }
                    });

                    $scope.onSelectLocation = function (item) {
                        var lc = document.getElementById('location');
                        var pService = new google.maps.places.PlacesService(lc);
                        var city;
                        var basedCountry;
                        var country;

                        pService.getDetails({
                            placeId: item.place_id
                        }, function (place, status) {
                            if (status === google.maps.places.PlacesServiceStatus.OK) {

                                /*  angular.forEach(place.address_components, function (value, key) {
                                 if (value.types[0] === 'locality') {
                                 $scope.publicCompany.city = value.long_name;
                                 } else if (value.types[0] === 'country') {
                                 $scope.publicCompany.basedCountry = value.short_name;
                                 $scope.publicCompany.country = value.long_name;
                                 }
                                 });*/



                                $scope.details = place;
                                $scope.$apply('details');
                            }
                        });
                    };
                    $scope.service = service;
                    $scope.serviceTpl = $scope.subServicesTpl[service];
                    if (angular.isUndefined($scope.user.services) || !$scope.user.services)
                        $scope.user.services = [];
                    $scope.user.services.push({});
                    $scope.user.services[$scope.user.services.length - 1].service = service;
                    $scope.user.services[$scope.user.services.length - 1].whoCanSendMessage = 'inNetwork';
                    $scope.selectedPrevIndex = $scope.selectedIndex;
                    $scope.updateSelectedIndex($scope.user.services.length - 1);
                    $scope.serviceAction = 'add';
                    $scope.changeServiceStep(3);
                };
                $scope.editService = function (selectedIndex) {
                    $scope.selectedIndex = selectedIndex;
                    $scope.serviceStep = 3;
                    $scope.user.services[selectedIndex].formatted_address = $scope.user.services[selectedIndex].location.formatted_address;
                    $scope.serviceTpl = $scope.subServicesTpl[$scope.user.services[selectedIndex].service];
                    $scope.serviceAction = 'edit';
                };
                $scope.saveService = function (form, selectedIndex) {
                    
                    $scope.formSubmited = true;
                    $scope.savingService = true;
                  /*  if ($scope.user.services[selectedIndex].location && $scope.user.services[selectedIndex].location.geometry && $scope.user.services[selectedIndex].location.address_components) {
                        $scope.user.services[selectedIndex].location.geometrylocation = $scope.user.services[selectedIndex].location.geometry.location.toJSON();
                        angular.forEach($scope.user.services[selectedIndex].location.address_components, function (value, key) {
                            if (value.types[0] === 'locality') {
                                $scope.user.services[selectedIndex].location.city = value.long_name;
                            } else if (value.types[0] === 'country') {
                                $scope.user.services[selectedIndex].location.basedCountry = value.short_name;
                            }
                        });
                        $scope.user.services[selectedIndex].formatted_address = $scope.user.services[selectedIndex].location.formatted_address;
                    }*/
                    if ($scope.user.services[selectedIndex].location && $scope.user.services[selectedIndex].location.geometry && $scope.user.services[selectedIndex].location.address_components) {
                            $scope.user.services[selectedIndex].geometrylocation = $scope.details.geometry.location.toJSON();
                            var loc = $filter('locations')($scope.details.address_components);
                         $scope.user.services[selectedIndex].city = loc.city;
                            $scope.user.services[selectedIndex].basedCountry = loc.basedCountry;
                    }
                    if (form.$invalid || ($scope.serviceTpl && (!$scope.user.services[selectedIndex].subServices || $scope.user.services[selectedIndex].subServices.length <= 0))) {
                        $scope.savingService = false;
                        return;
                    }
                    console.log($scope.user.services[selectedIndex]);
                    $scope.user.services[selectedIndex].selectedIndex = selectedIndex;
                    $http({
                        method: "PUT",
                        url: $scope.serviceAction == 'edit' ? ENV.apiEndpoint + '/api/edit/profile/edit/service' : ENV.apiEndpoint + '/api/edit/profile/add/service',
                        data: $scope.user.services[selectedIndex],
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.addedServices.push($scope.user.services[$scope.selectedIndex].service);
                        $scope.user.services = response.data.services;
                        $scope.initUser.services = angular.copy($scope.user.services);
                        userService.update($scope.user);
                        if ($scope.serviceAction == 'edit')
                        {
                            Notification.success({title: 'Saved', message: 'Service details has been updated successfully.'});
                        } else {
                            Notification.success({title: 'Saved', message: 'Service has been added successfully.'});
                            if (angular.isUndefined($scope.serviceTabs) || !$scope.serviceTabs)
                                $scope.serviceTabs = [];
                            $scope.serviceTabs.push({
                                'service': $scope.user.services[selectedIndex].service,
                                'key': selectedIndex,
                                'active': true,
                            });
                        }
                        if (form)
                        {
                            form.$setPristine(true);
                        }
                        $scope.savingService = false;
                        $scope.formSubmited = false;
                        $scope.updateSelectedIndex($scope.selectedIndex);
                        $scope.changeServiceStep(1);
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                        $scope.user.services = angular.copy($scope.initUser.services);
                        userService.update($scope.user);
                        $scope.savingService = false;
                        $scope.formSubmited = false;
                    });
                };
                $scope.removeService = function () {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/deleteService.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                            $scope.close = function () {
                                $scope.removingService = true;
                                $http({
                                    method: "PUT",
                                    url: ENV.apiEndpoint + '/api/edit/profile/remove/service',
                                    data: {
                                        'selectedIndex': $scope.selectedIndex
                                    },
                                    headers: {'Content-Type': 'application/json'}
                                }).then(function (response) {
                                    $scope.addedServices.pop($scope.user.services[$scope.selectedIndex].service);
                                    $scope.serviceTabs.pop($scope.serviceTabs[$scope.selectedIndex]);
                                    $scope.user = response.data;
                                    $scope.initUser.services = angular.copy($scope.user.services);
                                    userService.update($scope.user);
                                    Notification.success({title: 'Removed', message: 'Service has been removed successfully.'});
                                    $scope.removingService = false;
                                    $scope.updateSelectedIndex(0);
                                    $scope.changeServiceStep(2);
                                    if ($scope.user.services.length > 0)
                                        $scope.changeServiceStep(1);
                                    $uibModalInstance.dismiss('cancel');
                                }).catch(function (response) {
                                    if (response.status === 401)
                                        $state.go('access.signin');
                                    else {
                                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                    }
                                    $scope.user = angular.copy($scope.initUser);
                                    userService.update($scope.user);
                                    $scope.removingService = false;
                                    $uibModalInstance.dismiss('cancel');
                                });
                            };
                        },
                        size: 'sm',
                        scope: $scope
                    });
                };
                $scope.addServiceClient = function (size) {

                    /*
                     if (angular.isUndefined($scope.user.services[$scope.selectedIndex].clients) || !$scope.user.services[$scope.selectedIndex].clients)
                     $scope.user.services[$scope.selectedIndex].clients = [];
                     $scope.user.services[$scope.selectedIndex].clients.push({});
                     
                     console.log($scope.addedServices);
                     console.log($scope.user.services[$scope.selectedIndex].clients);
                     */
                    /*
                     if (angular.isUndefined($scope.user.services) || !$scope.user.services)
                     $scope.user.services = {};
                     $scope.user.services.push({});
                     $scope.user.services[$scope.user.services.length - 1].service = service;
                     $scope.user.services[$scope.user.services.length - 1].whoCanSendMessage = 'inNetwork';
                     */


                    //$scope.user.services[$scope.selectedIndex].clients[0] = {};

                    $uibModal.open({
                        templateUrl: 'tpl/forms/addService.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {
                            $scope.user = userService.user;
                            $scope.clientForService = String($scope.selectedIndex);
                            $scope.selectedIndex = String($scope.selectedIndex);
                            $scope.clientCompanyId = null;
                            $scope.clientCompany = '';
                            $scope.loading_companies = false;
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                            $scope.close = function () {
                                $scope.loadingClients = true;
                                $http({
                                    method: "PUT",
                                    url: ENV.apiEndpoint + '/api/edit/profile/add/serviceclient',
                                    data: {
                                        'clientCompanyId': $scope.clientCompanyId,
                                        'clientCompany': $scope.clientCompany,
                                        'clientForService': $scope.clientForService
                                    },
                                    headers: {'Content-Type': 'application/json'}
                                }).then(function (response) {
                                    $scope.user.services = response.data.services;
                                    $scope.initUser.services = angular.copy($scope.user.services);
                                    userService.update($scope.user);
                                    Notification.success({title: 'Saved', message: 'Service client has been added successfully.'});
                                    $scope.loadingClients = false;
                                    $uibModalInstance.dismiss('cancel');
                                }).catch(function (response) {
                                    $scope.loadingClients = false;
                                    if (response.status === 401)
                                        $state.go('access.signin');
                                    else if (response.status === 403) {
                                        Notification.error({title: 'Client already exists', message: 'You have already added this client before.'});
                                    } else {
                                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                    }
                                });
                            };
                            $scope.unselectClient = function () {
                                $scope.clientCompanyId = null;
                                $scope.loading_companies = false;
                            };
                            $scope.onSelect = function (item) {
                                $scope.clientCompanyId = item.companyID;
                                $scope.clientCompany = item.name;
                                $scope.loading_companies = false;
                            };
                        },
                        windowClass: 'modal-w-auto centered-modal',
                        scope: $scope
                    });
                };
                $scope.removeServiceClient = function (serviceIndex, clientIndex, size) {
//                    console.log(serviceIndex);
//                    console.log(clientIndex);
//                    console.log(size);                    
                    $uibModal.open({
                        templateUrl: 'tpl/forms/deleteClient.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                            $scope.close = function () {
                                $scope.loadingClients = true;
                                $http({
                                    method: "PUT",
                                    url: ENV.apiEndpoint + '/api/edit/profile/remove/serviceclient',
                                    data: {
                                        'serviceIndex': serviceIndex,
                                        'clientIndex': clientIndex
                                    },
                                    headers: {'Content-Type': 'application/json'}
                                }).then(function (response) {
                                    $scope.user.services = response.data.services;
                                    $scope.initUser.services = angular.copy($scope.user.services);
                                    userService.update($scope.user);
                                    Notification.success({title: 'Removed', message: 'Service client removed successfully.'});
                                    $scope.loadingClients = false;
                                    $uibModalInstance.dismiss('cancel');
                                }).catch(function (response) {
                                    $scope.loadingClients = false;
                                    if (response.status === 401)
                                        $state.go('access.signin');
                                    else {
                                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                    }
                                });
                            };
                        },
                        size: size,
                        scope: $scope
                    });
                };
                $scope.addClientAvatar = function (clientIndex) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/clientAvatar.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {
                            $scope.clientIndex = clientIndex;
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                            $scope.close = function (data) {
                                $scope.user.services[$scope.selectedIndex].clients[$scope.clientIndex].avatar = Object.keys(data).length !== 0 ? data : null;
                                /*if (Object.keys(data).length === 0)
                                 {
                                 $scope.user.services[$scope.selectedIndex].clients[$scope.clientIndex].avatar = null;
                                 } else {
                                 $scope.user.services[$scope.selectedIndex].clients[$scope.clientIndex].avatar = angular.fromJson(data);
                                 }*/
                                $uibModalInstance.close();
                            };
                        },
                        size: 'lg',
                        backdrop: 'static',
                        scope: $scope
                    });
                };
                $scope.addServiceSuggestion = function (mode) {
                    $scope.otherService = mode;
                };
                $scope.addServiceForSuggestion = function () {
                    $scope.loadingServiceSuggestion = true;
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/edit/profile/add/servicesuggestion',
                        data: {
                            serviceName: $scope.service.serviceName
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data;
                        userService.update($scope.user);
                        $scope.initUser = angular.copy($scope.user);
                        $scope.otherService = false;
                        $scope.service.serviceName = '';
                        $scope.loadingServiceSuggestion = false;
                        Notification.success({title: 'Service saved', message: 'Service you have suggested stored successfully.'});
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.loadingServiceSuggestion = false;
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.canceler = $q.defer();
                $scope.companyNameSearch = function ($viewValue) {
                    console.log('Callled autocomplete...');
                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.query = {};
                    $scope.query.headerString = $viewValue;
                    $scope.query.profileType = 'companies';
                    return $http.post(ENV.apiEndpoint + '/api/public/autocomplete', {query: $scope.query}, {timeout: $scope.canceler.promise})
                            .then(function (response) {
                                return response.data;
                            });
                };
                $scope.onSelectExperienceCompany = function (item) {
                    $scope.experience.company = item.name;
                    $scope.experience.companyId = item.companyID;
                };
                $scope.unselectExperienceCompany = function () {
                    $scope.experience.companyId = null;
                };
                $scope.addExperience = function (form) {

                    if (form.$invalid) {
                        //console.log(form);
                        angular.element("input[name='company']").trigger('focus');
                        return;
                    }

                    $scope.addingExperiences = true;
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/add/experience',
                        data: {
                            'type': $scope.experience.type,
                            'companyId': $scope.experience.companyId,
                            'company': $scope.experience.company
                        },
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(function (response) {
                        $scope.user.experiences = response.data.experiences;
                        $scope.initUser.experiences = angular.copy($scope.user.experiences);
                        userService.update($scope.user);
                        $scope.changeEditModeExperience(true);
                        $scope.changeEditExperience($scope.user.experiences.length - 1);
                        $scope.experience.company = '';
                        $scope.experience.companyId = '';
                        if (form)
                        {
                            form.$setPristine(true);
                        }
                        setTimeout(function () {
                            var experienceIndex = $scope.user.experiences.length - 1;
                            angular.element('html,body').animate({scrollTop: angular.element('#role_' + experienceIndex + '_0').offset().top - 200}, 800);
                            $scope.addingExperiences = false;
                        }, 1000);
                        Notification.success({title: 'Removed', message: 'Experience has been removed successfully.'});
                    }).catch(function (response) {
                        $scope.addingExperiences = false;
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.changeEditModeExperience = function (mode) {
                    $scope.editModeExperience = mode;
                    if (!$scope.editModeExperience) {
                        $scope.user.experiences = angular.copy($scope.initUser.experiences);
                        $scope.cancelEditExperience();
                    } else {
                        $scope.initUser.experiences = angular.copy($scope.user.experiences);
                    }
                };
                $scope.changeEditExperience = function (index) {
                    $scope.editExperience = index;
                    if (angular.isUndefined($scope.user.experiences[index].roles)) {
                        $scope.user.experiences[index].roles = [];
                        $scope.user.experiences[index].roles.push({
                            'jobtitle': $scope.user.experiences[index].jobtitle, 'description': $scope.user.experiences[index].description,
                            'frommonth': $scope.user.experiences[index].fromperiod ? moment($scope.user.experiences[index].fromperiod).format('MM') : '',
                            'fromyear': $scope.user.experiences[index].fromperiod ? moment($scope.user.experiences[index].fromperiod).format('YYYY') : '',
                            'tomonth': $scope.user.experiences[index].toperiod ? moment($scope.user.experiences[index].fromperiod).format('MM') : '',
                            'toyear': $scope.user.experiences[index].toperiod ? moment($scope.user.experiences[index].toperiod).format('YYYY') : '',
                            'currently': $scope.user.experiences[index].currently});
                        console.log($scope.user.experiences[index].roles);
                    } else if ($scope.user.experiences[index].roles.length == 0) {
                        $scope.user.experiences[index].roles.push({});
                    }
                    //console.log($scope.user.experiences[index]);
                };
                $scope.cancelEditExperience = function () {
                    $scope.editExperience = null;
                    $scope.user.experiences = angular.copy($scope.initUser.experiences);
                };
                $scope.removeExperienceRole = function (editExperienceIndex, role) {
                    var roleIndex = $scope.user.experiences[editExperienceIndex].roles.indexOf(role);
                    $scope.user.experiences[editExperienceIndex].roles.splice(roleIndex, 1);
                };
                $scope.addExperienceRole = function (index) {
                    $scope.user.experiences[index].roles.push({});
                };
                $scope.updateFromDate = function (role) {
                    role.fromperiod = null;
                    if (role.frommonth && role.fromyear && role.frommonth != '' && role.fromyear != '') {
                        var dt = new Date(role.fromyear + '-' + role.frommonth);
                        role.fromperiod = dt.toISOString();
                    }
                };
                $scope.updateToDate = function (role) {
                    role.toperiod = null;
                    if (role.tomonth && role.toyear && role.tomonth != '' && role.toyear != '') {
                        var dt = new Date(role.toyear + '-' + role.tomonth);
                        role.toperiod = dt.toISOString();
                    }
                };
                $scope.changeExperienceType = function (editExperience) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/changeExperienceType.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {
                            $scope.editExperience = editExperience;
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                        },
                        size: 'sm',
                        windowClass: 'centered-modal',
                        scope: $scope
                    });
                };
                $scope.deleteExperience = function (item) {                     //$scope.user.experiences.splice($scope.user.experiences.indexOf(item), 1);
                    /*console.log(index);
                     console.log($scope.user.experiences);
                     $scope.editExperience = null;
                     //delete $scope.user.experiences[index];
                     //$scope.user.experiences.slice(index, 1);
                     $scope.user.experiences = angular.copy($scope.user.experiences.slice(index, 1));
                     console.log($scope.user.experiences);*/
                    $uibModal.open({
                        templateUrl: 'tpl/forms/deleteExperience.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                            $scope.close = function () {
                                $scope.removingExperience = true;
                                $http({
                                    method: "PUT",
                                    url: ENV.apiEndpoint + '/api/edit/profile/remove/experience', data: {
                                        'experienceIndex': $scope.user.experiences.indexOf(item),
                                    },
                                    headers: {'Content-Type': 'application/json'}
                                }).then(function (response) {
                                    $scope.user.experiences = response.data.experiences;
                                    userService.update($scope.user);
                                    $scope.initUser.experiences = angular.copy($scope.user.experiences);
                                    $scope.changeEditModeExperience(false);
                                    $scope.removingExperience = false;
                                    Notification.success({title: 'Removed', message: 'Experience has been removed successfully.'});
                                    $uibModalInstance.dismiss('cancel');
                                }).catch(function (response) {
                                    $scope.removingExperience = false;
                                    if (response.status === 401)
                                        $state.go('access.signin');
                                    else {
                                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                    }
                                });
                                $uibModalInstance.close();
                            };
                        },
                        size: 'sm',
                        scope: $scope
                    });
                };
                $scope.saveExperience = function (experience, index) {
                    if (!angular.isUndefined(experience.roles)) {
                        angular.forEach(experience.roles, function (role, key) {
                            $scope.updateFromDate(role);
                            /*if (!angular.isUndefined(role.fromperiod) && angular.isUndefined(role.toperiod)) {
                             role.currently = true;
                             }*/
                        });
                    }

                    $scope.editingExperiences = true;
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/edit/experience',
                        data: {
                            'experience': experience,
                            'experienceIndex': index
                        },
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(function (response) {
                        $scope.user.experiences = response.data.experiences;
                        $scope.initUser.experiences = angular.copy($scope.user.experiences);
                        userService.update($scope.user);
                        $scope.changeEditExperience(index);
                        $scope.editingExperiences = false;
                        $scope.cancelEditExperience();
                        Notification.success({title: 'Saved', message: 'Experience saved successfully.'});
                    }).catch(function (response) {
                        $scope.editingExperiences = false;
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.addExperienceCompanyAvatar = function (experienceIndex) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/experienceCompanyAvatar.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {
                            $scope.experienceIndex = experienceIndex;
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                            $scope.close = function (data) {
                                $scope.user.experiences[$scope.experienceIndex].logo = Object.keys(data).length !== 0 ? data : null;
                                /*if (Object.keys(data).length === 0)
                                 {
                                 $scope.user.experiences[$scope.experienceIndex].logo = null;
                                 } else {
                                 $scope.user.experiences[$scope.experienceIndex].logo = angular.fromJson(data);
                                 $scope.initUser.experiences = angular.copy($scope.user.experiences);
                                 userService.update($scope.user);
                                 }*/
                                $uibModalInstance.close();
                            };
                        },
                        size: 'lg',
                        backdrop: 'static',
                        scope: $scope
                    });
                };
                $scope.canceler = $q.defer();
                $scope.collegeNameSearch = function ($viewValue) {
                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.query = {};
                    $scope.query.headerString = $viewValue;
                    $scope.query.profileType = 'colleges';
                    return $http.post(ENV.apiEndpoint + '/api/public/autocomplete', {query: $scope.query}, {timeout: $scope.canceler.promise})
                            .then(function (response) {
                                return response.data;
                            });
                };
                $scope.onSelectCollege = function (item) {
                    $scope.education.college = item.name;
                    if (!angular.isUndefined(item.id))
                        $scope.education.collegeId = item.id;
                    if (!angular.isUndefined(item._id) && !angular.isUndefined(item._id.$id))
                        $scope.education.collegeId = item._id.$id;
                };
                $scope.unselectCollege = function () {
                    $scope.education.collegeId = null;
                };
                $scope.changeEditEducation = function (index) {
                    $scope.editEducation = index;
                };
                $scope.cancelEditEducation = function () {
                    $scope.editEducation = null;
                    $scope.user.educations = angular.copy($scope.initUser.educations);
                };
                $scope.addEducation = function (form) {
                    if (form.$invalid) {
                        angular.element("input[name='college']").trigger('focus');
                        return;
                    }
                    $scope.addingEducation = true;
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/add/education',
                        data: {
                            'graduation': $scope.education.graduation,
                            'collegeId': $scope.education.collegeId,
                            'college': $scope.education.college
                        },
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(function (response) {
                        $scope.user.educations = response.data.educations;
                        $scope.initUser.educations = angular.copy($scope.user.educations);
                        userService.update($scope.user);
                        $scope.editEducation = $scope.user.educations.length - 1;
                        $scope.education.graduation = '';
                        $scope.education.collegeId = null;
                        $scope.education.college = '';
                        if (form)
                        {
                            form.$setPristine(true);
                        }
                        setTimeout(function () {
                            angular.element('html,body').animate({scrollTop: angular.element('#education_' + $scope.editEducation).offset().top - 200}, 800);
                            $scope.addingEducation = false;
                        }, 1000);
                        Notification.success({title: 'Saved', message: 'Education has been added successfully.'});
                    }).catch(function (response) {
                        $scope.addingEducation = false;
                        if (response.status === 401)
                            $state.go('access.signin');
                        else if (response.status === 403)
                            Notification.error({title: 'Error', message: 'Look like you have already added college.'});
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.onSelectUpdateCollege = function (education, item) {
                    education.college = item.name;
                    if (!angular.isUndefined(item.id))
                        education.collegeid = item.id;
                    if (!angular.isUndefined(item._id) && !angular.isUndefined(item._id.$id))
                        education.collegeid = item._id.$id;
                };
                $scope.unselectUpdateCollege = function (education) {
                    education.collegeid = null;
                };
                $scope.updateEducation = function (education) {
                    $scope.updatingEducation = true;
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/edit/education',
                        data: {
                            'educationIndex': $scope.editEducation,
                            'college': education.college,
                            'collegeid': education.collegeid,
                            'degree': education.degree ? education.degree : '',
                            'graduation': education.graduation
                        },
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(function (response) {
                        $scope.user.educations = response.data.educations;
                        $scope.initUser.educations = angular.copy($scope.user.educations);
                        userService.update($scope.user);
                        $scope.updatingEducation = false;
                        $scope.cancelEditEducation();
                        Notification.success({title: 'Saved', message: 'Education details has been updated successfully.'});
                    }).catch(function (response) {
                        $scope.updatingEducation = false;
                        if (response.status === 401)
                            $state.go('access.signin');
                        else if (response.status === 403)
                            Notification.error({title: 'Error', message: 'Look like you have already added college.'});
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.removeEducation = function (index) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/deleteCollege.html?v=' + ENV.latestUpdate,
                        controller: function ($scope, $uibModalInstance, userService) {
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                            $scope.close = function () {
                                $scope.removingCollege = true;
                                $http({
                                    method: "PUT",
                                    url: ENV.apiEndpoint + '/api/edit/profile/remove/education',
                                    data: {
                                        'educationIndex': index,
                                    },
                                    headers: {'Content-Type': 'application/json'}
                                }).then(function (response) {
                                    $scope.user.educations = response.data.educations;
                                    userService.update($scope.user);
                                    $scope.initUser.educations = angular.copy($scope.user.educations);
                                    $scope.editEducation = null;
                                    $scope.removingCollege = false;
                                    Notification.success({title: 'Removed', message: 'Education were removed successfully.'});
                                    $uibModalInstance.dismiss('cancel');
                                }).catch(function (response) {
                                    $scope.removingCollege = false;
                                    if (response.status === 401)
                                        $state.go('access.signin');
                                    else {
                                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                    }
                                    $uibModalInstance.close();
                                });
                            };
                        },
                        size: 'sm',
                        scope: $scope
                    });
                };
                $scope.changeEditModeAbout = function (mode) {
                    $scope.editAbout = mode;
                    if (!mode) {
                        $scope.user = angular.copy($scope.initUser);
                    }
                };
                $scope.canceler = $q.defer();
                $scope.skillSearch = function ($viewValue) {

                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.query = {};
                    $scope.query.headerString = $viewValue;
                    $scope.query.type = 'skills';
                    return $http.post(ENV.apiEndpoint + '/api/public/autocompleteabout', {query: $scope.query}, {timeout: $scope.canceler.promise})
                            .then(function (response) {
                                console.log(response);
                                return response.data;
                            });
                };
                $scope.onSelectSkill = function (item) {
                    if ($scope.user.competences.indexOf(item) < 0 && item && item.trim().length > 0) {
                        $scope.user.competences.push(item);
                    }
                    $scope.about.skill = '';
                    item = '';
                };
                $scope.removeSkill = function (item) {
                    $scope.user.competences.splice($scope.user.competences.indexOf(item), 1);
                };
                $scope.canceler = $q.defer();
                $scope.languageSearch = function ($viewValue) {
                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.query = {};
                    return ['English', 'Hindi'];
                    $scope.query.headerString = $viewValue;
                    $scope.query.profileType = 'colleges';
                    return $http.post(ENV.apiEndpoint + '/api/public/autocomplete', {query: $scope.query}, {timeout: $scope.canceler.promise})
                            .then(function (response) {
                                return response.data;
                            });
                };
                $scope.onSelectLanguage = function (item) {
                    if ($scope.user.languages.indexOf(item) < 0 && item && item.trim().length > 0) {
                        $scope.user.languages.push(item);
                    }
                    $scope.about.language = '';
                    item = '';
                };
                $scope.removeLanguage = function (item) {
                    $scope.user.languages.splice($scope.user.languages.indexOf(item), 1);
                };
                $scope.saveAboutMe = function () {
                    $scope.loadingCompetences = true;
                    //console.log($scope.user.newcompetence);
                    //console.log($scope.user.newlanguage);
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/edit/competences',
                        data: {
                            summary: $scope.user.summary,
                            competences: $scope.user.competences,
                            languages: $scope.user.languages
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data.user;
                        userService.update($scope.user);
                        $scope.initUser = angular.copy($scope.user);
                        $scope.loadingCompetences = false;
                        $scope.changeEditModeAbout(false);
                        Notification.success({title: 'Saved', message: 'Competences saved successfully'});
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.loadingCompetences = false;
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };

                $scope.addCustomService = function () {
                    if (angular.isUndefined($scope.user.customServices) || !$scope.user.customServices) {
                        $scope.user.customServices = [];
                    }
                    $scope.user.customServices.push({'market': '', 'action': '', 'values': []});
                };

                $scope.changeEditModeCustomServices = function (mode) {
                    $scope.editCustomServices = mode;
                    if (!mode) {
                        $scope.user = angular.copy($scope.initUser);
                    } else if (!$scope.user.customServices || $scope.user.customServices.length <= 0) {
                        $scope.addCustomService();
                    }
                };
                $scope.onSelectValue = function (service, value) {
                    if (service.values.indexOf(value) < 0 && value && value.trim().length > 0) {
                        service.values.push(value);
                    }
                    service.value = '';
                    value = '';
                };
                $scope.removeValue = function (service, value) {
                    service.values.splice(service.values.indexOf(value), 1);
                };

                $scope.deleteService = function (service) {
                    var index = $scope.user.customServices.indexOf(service);
                    $scope.user.customServices.splice(index, 1);
                    $scope.saveCustomService();
                    if ($scope.user.customServices.length <= 1) {
                        $scope.user.customServices.push({'market': '', 'action': '', 'values': []});
                    }
                };

                $scope.saveCustomService = function () {
                    var request = $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/customservices',
                        data: {
                            customBoxname: $scope.user.customBoxname,
                            customServices: $scope.user.customServices
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user.customBoxname = response.data.customBoxname;
                        $scope.user.customServices = response.data.customServices;
                        $scope.user.profileOrder = response.data.profileOrder;
                        userService.update($scope.user);
                        $scope.initUser = angular.copy($scope.user);
                        if ($scope.user.customServices.length <= 0) {
                            $scope.user.customServices.push({'market': '', 'action': '', 'values': []});
                        }
                        Notification.success({title: 'Saved', message: 'Custom service saved successfully'});
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };

                $scope.viewMutualConnections = function (connection) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/mutualConnections.html?v=' + ENV.latestUpdate,
                        controller: function ($uibModalInstance) {
                            $scope.mConnections = connection.mutualConnections;
                            $scope.connectionWith = connection.name + ' ' + connection.lastname;
                            //console.log(mutualConnections);
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };
                        },
                        size: 'md',
                        windowClass: 'centered-modal',
                        scope: $scope
                    });
                };

                $scope.checkMiniresumeValidity = function (form) {
                    if ($scope.fromJobModal && (!$scope.user.miniResume || $scope.user.miniResume.length <= 0)) {
                        $timeout(function () {
                            form.miniResume.$setDirty();
                            form.miniResume.$setValidity('required', false);
                        }, 300);
                    }
                };

                //Edit profile roles autocomplete
                $scope.rolesSearch = function ($viewValue) {
                    $scope.canceler.resolve();
                    $scope.canceler = $q.defer();
                    $scope.query = {};
                    $scope.query.headerString = $viewValue;
                    $scope.query.profileType = 'roles';
                    return $http.post(ENV.apiEndpoint + '/api/public/autocomplete', {query: $scope.query}, {timeout: $scope.canceler.promise})
                            .then(function (response) {
                                //console.log(response.data);
                                return response.data;
                            });
                };
                $scope.onSelect = function (item) {
                    $scope.user.companyType = item.name;
                    userService.update($scope.user);
                };

                //Scroll page to the particular element provided on url hash
                $timeout(function () {
                    var hash = window.location.hash;
                    // now scroll to element with that id
                    if (typeof hash != 'undefined' && angular.element(hash).length > 0)
                        angular.element('html, body').animate({scrollTop: angular.element(hash).offset().top - 90});
                }, 1500);

                /*$scope.checkScroll = function(){
                 var hash = window.location.hash;
                 // now scroll to element with that id
                 if (typeof hash != 'undefined')
                 $('html, body').animate({scrollTop: $(hash).offset().top - 90});
                 };*/

                /*$scope.$on('$viewContentLoaded', function () {
                 $location.hash('education');
                 $anchorScroll();
                 });*/
                return;


                //                $scope.updateService = function (form, selectedIndex) {
//                    $scope.formSubmited = true;
//                    $scope.updatingService = true;
//                    if ($scope.user.services[selectedIndex].location && $scope.user.services[selectedIndex].location.geometry && $scope.user.services[selectedIndex].location.address_components) {
//                        $scope.user.services[selectedIndex].location.geometrylocation = $scope.user.services[selectedIndex].location.geometry.location.toJSON();
//                        angular.forEach($scope.user.services[selectedIndex].location.address_components, function (value, key) {
//                            if (value.types[0] === 'locality') {
//                                $scope.user.services[selectedIndex].location.city = value.long_name;
//                            } else if (value.types[0] === 'country') {
//                                $scope.user.services[selectedIndex].location.basedCountry = value.short_name;
//                            }
//                        });
//                        $scope.user.services[selectedIndex].formatted_address = $scope.user.services[selectedIndex].location.formatted_address;
//                    }
//
//                    if (form.$invalid || $scope.user.services[selectedIndex].subServices.length <= 0) {
//                        return;
//                    }
//
//                    $scope.user.services[selectedIndex].selectedIndex = selectedIndex;
//                    var request = $http({
//                        method: "PUT",
//                        url: ENV.apiEndpoint + '/api/edit/profile/edit/service',
//                        data: $scope.user.services[selectedIndex],
//                        headers: {'Content-Type': 'application/json'}
//                    });
//                    /* Check whether the HTTP Request is Successfull or not. */
//                    request.success(function (data, status) {
//                        $scope.serviceStep = 1;
//                        $scope.action = '';
//                        $scope.user = data;
//                        $scope.initUser = angular.copy($scope.user);
//                        userService.update($scope.user);
//                        Notification.success({title: 'Saved', message: 'Service details has been updated successfully.'});
//                        $scope.updatingService = false;
//                    });
//                    request.error(function (data, status) {
//                        if (status === 401)
//                            $state.go('access.signin');
//                        else {
//                            Notification.error({title: 'Error (' + status + ')', message: 'Ops! Something went wrong...'});
//                        }
//                        $scope.user = angular.copy($scope.initUser);
//                        userService.update($scope.user);
//                        $scope.updatingService = false;
//                    });
//                };

                //End of new design code

                if (!angular.isUndefined($scope.app.activetab) && $scope.app.activetab !== null) {
                    $scope.activetab = $scope.app.activetab;
                    $scope.app.activetab = null;
                } else {
                    $scope.activetab = 'aboutme';
                }
                $scope.changetab = function (tab) {
                    $scope.activetab = tab;
                };
                $scope.foundCompanies = {};
                $scope.user.companyid = null;
                $scope.loadingPersonal = 0;
                $scope.loadingDeleteBuyTraffic = {};
                $scope.loadingBuyTraffic = {};
                $scope.loadingDeleteSellTraffic = {};
                $scope.loadingSellTraffic = {};
                $scope.loadingDeleteExperiences = {};
                $scope.loadingExperiences = {};
                $scope.loadingDeleteIosApp = {};
                $scope.loadingDeleteAndroidApp = {};
                $scope.app.itunesURL = '';
                $scope.app.googlePlayURL = '';
                var params = $location.search();
                //console.log(params);
                //Linkedin popup modal code
                /*if (params.code && params.state == 'linkedin') {
                 //console.log(JSON.stringify({"code": params.code, "redirectBase": $scope.app.protocol + "://" + $scope.app.host}));
                 $http({
                 method: "POST",
                 url: ENV.apiEndpoint + '/api/linkedin/auth',
                 data: {
                 code: params.code,
                 redirectBase: $scope.app.protocol + "://" + $scope.app.host},
                 headers: {'Content-Type': 'application/json'}
                 }).then(function (response) {
                 //console.log(status);
                 //console.log(data);
                 //console.log("Open modal");
                 $uibModal.open({
                 templateUrl: 'tpl/forms/LinkedInSync.html?v=' + ENV.latestUpdate,
                 controller: 'LinkedInSyncModalInstance',
                 size: 'lg'
                 });
                 }).catch(function (response) {
                 //console.log(status);
                 //console.log(data);
                 });
                 } else if (angular.isUndefined($scope.user.hasEditedOwnProfile) || $scope.user.hasEditedOwnProfile != true) {
                 //delete $cookies["new_registration"];
                 $uibModal.open({
                 templateUrl: 'tpl/forms/LinkedInSync.html?v=' + ENV.latestUpdate,
                 controller: 'LinkedInSyncModalInstance',
                 size: 'lg'
                 });
                 }*/
                $scope.trackProfile = function (name, success, status, data) {
                    mixpanel.identify($scope.user.username);
                    if (success) {
                        mixpanel.track(name, {
                            "Page": "Edit Profile Page",
                            "Type": "Action",
                            "Text": name,
                            "Success": true,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                    } else {
                        mixpanel.track(name, {
                            "Page": "Edit Profile Page",
                            "Type": "Action",
                            "Text": name,
                            "Error": true,
                            "ErrorStatus": status,
                            "ErrorData": data,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                    }
                };
                mixpanel.identify($scope.user.username);
                mixpanel.track("Edit Profile Page", {
                    "Page": "Edit Profile Page",
                    "Type": "Page View"
                });
                $scope.trackLinkedin = function (box) {
                    mixpanel.identify($scope.user.username);
                    //mixpanel.people.increment('Synced LinkedIn Profile');
                    mixpanel.track('Sync LinkedIn Profile', {
                        "Page": 'Edit Profile Page',
                        "Type": 'Action',
                        "Position": box + " box",
                        "Text": 'Sync',
                        "Username": $scope.user.username,
                        "$email": $scope.user.email,
                        "Company": $scope.user.company,
                        "Job Title": $scope.user.jobTitle,
                        "Role": $scope.user.companyType,
                        "Subrole": $scope.user.companySubType
                    });
                };
                //$scope.user = userService.user;
                // Retrieve user from the database
                /*$http.get(ENV.apiEndpoint + '/api/user').
                 success(function(data) {
                 //  $scope.user = data;
                 //  //console.log($scope.user);
                 }).
                 error(function(data) {
                 ////console.log('error');
                 $scope.user = null;
                 $state.go('access.signin');
                 });
                 */
                $scope.addBuyTrafficForm = function () {
                    //console.log("addBuyTrafficForm");
                    if (angular.isUndefined($scope.user.buyTraffic))
                        $scope.user.buyTraffic = [];
                    $scope.user.buyTraffic.push({});
                };
                $scope.addSellTrafficForm = function () {
                    if (angular.isUndefined($scope.user.sellTraffic))
                        $scope.user.sellTraffic = [];
                    $scope.user.sellTraffic.push({});
                };
                /*$scope.addSellTrafficForm = trackJs.watch(function() {
                 $scope.user.sellTraffic.push({
                 "cpa": false,
                 "cps" : false,
                 "cpc" : false,
                 "cpv" : false,
                 "cpd" : false,
                 "cpi" : false,
                 "cpl" : false,
                 "cpm" : false,
                 "dclick" : false,
                 "c2call" : false,
                 "ppcall" : false,
                 "incentivized" : false,
                 "nonincentivized" : false,
                 "ios" : false,
                 "android" : false,
                 "windows" : false,
                 "blackberry" : false,
                 "web" : false,
                 "unity" : false,
                 "baba" : false
                 //"kind": false,
                 //"trafficType" : false,
                 //"targeting" : false,
                 //"trading" : false,
                 //"country" : false
                 });
                 };*/
                $scope.addExperiencesForm = function () {
                    if (angular.isUndefined($scope.user.experiences))
                        $scope.user.experiences = [];
                    //$scope.user.experiences.push({});
                };
                /*
                 $scope.avatar = trackJs.watch(function() {
                 $scope.loadingAvatar = true;
                 //console.log('avatar function:');
                 //console.log($scope.newfile);
                 var request = $http({
                 method: "POST",
                 url: ENV.apiEndpoint + '/api/edit/profile/avatar',
                 data: {
                 avatar: $scope.newfile
                 },
                 headers: {'Content-Type': undefined }
                 });
                 
                 // Check whether the HTTP Request is Successfull or not.
                 request.success(function (data, status) {
                 //console.log(status);
                 //console.log(data);
                 //userService.Save($rootScope.user);
                 $scope.loadingAvatar = false;
                 //$uibModalInstance.close();
                 })
                 request.error(function (data, status) {
                 //console.log(status);
                 //console.log(data);
                 if(status===401) $state.go('access.signin');
                 
                 Notification.error({title: 'Error ('+status+')', message: 'Ops! Something went wrong...'});
                 
                 })
                 
                 };*/
                /*$scope.cover = function () {
                 $scope.loadingCover = true;
                 //console.log('popup cover function:');
                 //console.log($scope.user.cover);
                 console.log(JSON.stringify({"cover": $scope.user.cover}));
                 var request = $http({
                 method: "POST",
                 url: $scope.apiUrl + '/api/edit/profile/cover',
                 data: {
                 cover: $scope.user.cover
                 },
                 headers: {'Content-Type': 'application/json'}
                 });
                 request.success(function (data, status) {
                 //console.log(status);
                 //console.log(data);
                 $scope.user.cover = data; //this should avoid us to re-load the page
                 //userService.Save(data);
                 userService.update($scope.user);
                 $scope.loadingCover = false;
                 });
                 request.error(function (data, status) {
                 //console.log(status);
                 //console.log(data);
                 if (response.status === 401)
                 $state.go('access.signin');
                 else {
                 Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                 }
                 });
                 };*/
                $scope.pushMin = function () {
                    if (angular.isUndefined($scope.user.buyTraffic))
                        $scope.user.buyTraffic = [];
                    if (angular.isUndefined($scope.user.sellTraffic))
                        $scope.user.sellTraffic = [];
                    if (angular.isUndefined($scope.user.experiences))
                        $scope.user.experiences = [];
                    if ($scope.user.buyTraffic.length == 0)
                        $scope.user.buyTraffic.push({});
                    if ($scope.user.sellTraffic.length == 0)
                        $scope.user.sellTraffic.push({});
                    //if ($scope.user.experiences.length == 0)
                    //$scope.user.experiences.push({});
                };
                $scope.pushMin();
                $scope.onChangePersonal = function () {
                    $scope.loadingPersonal = 1;
                };
                $scope.savePersonal = function () {
                    $scope.loadingPersonal = 2;
                    //console.log($scope.user);
                    //console.log(JSON.stringify({"user": $scope.user}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/personal',
                        data: {
                            //gender:   $scope.user.gender,
                            name: $scope.user.name, lastname: $scope.user.lastname,
                            company: $scope.user.company,
                            jobTitle: $scope.user.jobTitle,
                            //birthday:   $scope.user.birthday,
                            city: $scope.user.city, basedCountry: $scope.user.basedCountry,
                            companyid: $scope.user.companyid
                                    // cover:   $scope.user.cover
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);

                        $scope.user = response.data;
                        $scope.pushMin();
                        mixpanel.identify($scope.user.username);
                        mixpanel.people.set({
                            "$first_name": $scope.user.name,
                            "$last_name": $scope.user.lastname,
                            "$created": new Date(),
                            "$email": $scope.user.email,
                            "Username": $scope.user.username,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Company Exists": (angular.isUndefined($scope.user.companyPage) || $scope.user.companyPage == null) ? false : true

                        });
                        $scope.trackProfile('Save Profile Card', true, 200, null); //$scope.$apply(function() {
                        //     $scope.user.cover = data;
                        //    $scope.user.cover = "/uploads/profile/covers/josepmarti.png?1420731242";
                        //});
                        //userService.Save(data);
                        userService.update($scope.user);
                        if ($scope.user.companyPage)
                        {
                            $http.post(ENV.apiEndpoint + '/api/company', {cache: true}).then(function (response) {
                                $scope.company = response.data;
                                companyService.update($scope.company);
                            });
                        }
                        $scope.loadingPersonal = 3;
                        //$state.reload();
                        //window.location.reload();
                        Notification.success({title: 'Saved', message: 'Profile changed successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            $scope.trackProfile('Save Profile Card', false, response.status, response.data);
                        }
                    });
                };
                $scope.contact = function () {
                    if ($scope.user.email) {
                        $scope.loadingContact = true;
                        //console.log($scope.user);
                        //console.log(JSON.stringify({"user": $scope.user}));
                        $http({
                            method: "PUT",
                            url: ENV.apiEndpoint + '/api/edit/profile/contact',
                            data: {
                                email: $scope.user.email,
                                skype: $scope.user.skype,
                                linkedin: $scope.user.linkedin,
                                twitter: $scope.user.twitter,
                                phone: $scope.user.phone,
                                website: $scope.user.website
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            //userService.Save(data);
                            $scope.trackProfile('Contact', true, 200, null);
                            userService.update(response.data);
                            $scope.loadingContact = false;
                            Notification.success({title: 'Saved', message: 'Profile changed successfully'});
                        });
                        request.catch(function (response) {
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                $scope.trackProfile('Contact', false, response.status, response.data);
                            }
                        });
                    }
                };
                $scope.addIosApp = function () {
                    $scope.loadingIosApp = true;
                    //console.log($scope.app.itunesURL);
                    //console.log(JSON.stringify({"itunesURL": $scope.app.itunesURL}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/apps/ios/add',
                        data: {
                            itunesURL: $scope.app.itunesURL
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data.user;
                        $scope.user.iosApps = response.data.apps;
                        //userService.Save(data);
                        userService.update($scope.user);
                        $scope.pushMin();
                        //console.log(status);
                        //console.log(data);
                        $scope.app.itunesURL = '';
                        $scope.trackProfile('Add iOS Apps', true, 200, null);
                        $scope.loadingIosApp = false;
                        Notification.success({title: 'Saved', message: 'App added successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        $scope.loadingIosApp = false;
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            $scope.trackProfile('Add iOS Apps', false, response.status, response.data);
                        }
                    });
                    $scope.itunesURL = '';
                };
                $scope.deleteIosApp = function (appid, index) {
                    $scope.loadingDeleteIosApp[index] = true;
                    //console.log(appid);
                    //console.log(JSON.stringify({"appid": appid}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/apps/ios/delete',
                        data: {
                            appid: appid
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data.user;
                        $scope.user.iosApps = response.data.apps;
                        //userService.Save(data);
                        userService.update($scope.user);
                        $scope.pushMin();
                        //console.log(status);
                        //console.log(data);
                        $scope.trackProfile('Delete iOS App', true, 200, null);
                        $scope.loadingDeleteIosApp[index] = false;
                        Notification.success({title: 'Deleted', message: 'App deleted successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Delete iOS App', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            $scope.loadingDeleteIosApp[index] = false;
                        }
                    });
                };
                $scope.addAndroidApp = function () {
                    $scope.loadingAndroidApp = true;
                    //console.log("googlePlayURL:");
                    //console.log($scope.app.googlePlayURL);
                    //console.log(JSON.stringify({"googlePlayURL": $scope.app.googlePlayURL}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/apps/android/add',
                        data: {
                            googlePlayURL: $scope.app.googlePlayURL
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.user.androidApps = response.data;
                        //userService.Save(data);
                        userService.update($scope.user);
                        //$scope.pushMin();
                        $scope.app.googlePlayURL = '';
                        $scope.trackProfile('Add Android App', true, 200, null);
                        $scope.loadingAndroidApp = false;
                        Notification.success({title: 'Saved', message: 'App added successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            $scope.trackProfile('Add Android App', false, response.status, response.data);
                            $scope.loadingAndroidApp = false;
                        }
                    });
                    $scope.googlePlayURL = '';
                };
                $scope.deletAndroidApp = function (appid, index) {
                    $scope.loadingDeleteAndroidApp[index] = true;
                    //console.log(appid);
                    //console.log(JSON.stringify({"appid": appid}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/apps/android/delete',
                        data: {
                            appid: appid
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user.androidApps = response.data;
                        //userService.Save(data);
                        userService.update($scope.user);
                        $scope.pushMin();
                        //console.log(status);
                        //console.log(data);
                        $scope.trackProfile('Delete Android App', true, 200, null);
                        $scope.loadingDeleteAndroidApp[index] = false;
                        Notification.success({title: 'Deleted', message: 'App deleted successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            $scope.trackProfile('Delete Android App', false, response.status, response.data);
                            $scope.loadingDeleteAndroidApp[index] = false;
                        }
                    });
                };
                $scope.addCategory = function () {
                    if ($scope.user.newcategory) {
                        $scope.loadingCategories = true;
                        //console.log($scope.user.newcategory);
                        //console.log(JSON.stringify({"category": $scope.user.newcategory}));
                        $http({
                            method: "PUT",
                            url: ENV.apiEndpoint + '/api/edit/profile/category/add',
                            data: {
                                category: $scope.user.newcategory
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            $scope.user = response.data;
                            //userService.Save(data);
                            userService.update($scope.user);
                            $scope.pushMin();
                            $scope.loadingCategories = false;
                            Notification.success({title: 'Saved', message: 'Category added successfully'});
                        }).catch(function (response) {
                            //console.log(status);
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                            }
                        });
                    }
                };
                $scope.deletCategory = function (category) {
                    //console.log(category);
                    //console.log(JSON.stringify({"category": category}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/category/delete',
                        data: {
                            category: category
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data;
                        //userService.Save(data);
                        userService.update($scope.user);
                        $scope.pushMin();
                        Notification.success({title: 'Deleted', message: 'Category deleted successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.saveCompetences = function () {
                    $scope.loadingCompetences = true;
                    //console.log($scope.user.newcompetence);
                    //console.log($scope.user.newlanguage);
                    //console.log(JSON.stringify({"competences": $scope.user.competences, "languages": $scope.user.languages}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/competences',
                        data: {
                            competences: $scope.user.competences,
                            languages: $scope.user.languages
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log("result:");
                        //console.log(data);
                        $scope.user = response.data.user;
                        $scope.user.competences = response.data.competences;
                        $scope.user.languages = response.data.languages; //userService.Save(data);
                        userService.update($scope.user);
                        $scope.pushMin();
                        $scope.trackProfile('Save Competence', true, 200, null);
                        $scope.loadingCompetences = false;
                        Notification.success({title: 'Saved', message: 'Competences saved successfully'});
                    });
                    request.catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Save Competence', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                /*$scope.deleteCompetence = trackJs.watch(function(competence) {
                 //console.log(competence);
                 
                 var request = $http({
                 method: "PUT",
                 url: ENV.apiEndpoint + '/api/edit/profile/competence/delete',
                 data: {
                 competence:   competence
                 },
                 headers: { 'Content-Type': 'application/json' }
                 });
                 
                 /* Check whether the HTTP Request is Successfull or not. */
                /*    request.success(function (data, status) {
                 $scope.user = data;
                 userService.Save($scope.user);
                 $scope.pushMin();
                 })
                 request.error(function (data, status) {
                 //console.log(status);
                 if(status===401) $state.go('access.signin');
                 
                 Notification.error({title: 'Error ('+status+')', message: 'Ops! Something went wrong...'});
                 
                 })
                 
                 };*/
                /*$scope.addLanguage = trackJs.watch(function() {
                 $scope.loadingAddLanguage = true;
                 //console.log($scope.user.newlanguage);
                 
                 var request = $http({
                 method: "PUT",
                 url: ENV.apiEndpoint + '/api/edit/profile/language/add',
                 data: {
                 newlanguage:   $scope.user.newlanguage
                 },
                 headers: { 'Content-Type': 'application/json' }
                 });*/
                /* Check whether the HTTP Request is Successfull or not. */
                /*    request.success(function (data, status) {
                 $scope.user = data;
                 userService.Save($scope.user);
                 $scope.pushMin();
                 $scope.loadingAddLanguage = false;
                 
                 })
                 request.error(function (data, status) {
                 //console.log(status);
                 if(status===401) $state.go('access.signin');
                 
                 Notification.error({title: 'Error ('+status+')', message: 'Ops! Something went wrong...'});
                 })
                 
                 };
                 $scope.deleteLanguage = trackJs.watch(function(language) {
                 $scope.loadingDeleteLanguage = true;
                 //console.log(language);
                 
                 var request = $http({
                 method: "PUT",
                 url: ENV.apiEndpoint + '/api/edit/profile/language/delete',
                 data: {
                 language:   language
                 },
                 headers: { 'Content-Type': 'application/json' }
                 });*/
                /* Check whether the HTTP Request is Successfull or not. */
                /*    request.success(function (data, status) {
                 $scope.user = data;
                 userService.Save($scope.user);
                 $scope.pushMin();
                 //console.log(data);
                 $scope.loadingDeleteLanguage = false;
                 
                 })
                 request.error(function (data, status) {
                 //console.log(status);
                 //console.log(data);
                 if(status===401) $state.go('access.signin');
                 
                 Notification.error({title: 'Error ('+status+')', message: 'Ops! Something went wrong...'});
                 
                 })
                 
                 };*/
                $scope.saveBuytraffic = function (index) {
                    $scope.arePlatformCheckBoxesSelected = [];
                    $scope.arePricingCheckBoxesSelected = [];
                    angular.forEach($scope.user.buyTraffic, function (val, k) {
                        $scope.arePlatformCheckBoxesSelected[k] = false;
                        $scope.arePricingCheckBoxesSelected[k] = false;
                        // Check Platform check boxes
                        if (angular.isUndefined($scope.user.buyTraffic[k].platform)) {
                            // do nothing
                        } else {
                            if (!angular.isUndefined($scope.user.buyTraffic[k].platform[0].ios) && $scope.user.buyTraffic[k].platform[0].ios)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].platform[0].android) && $scope.user.buyTraffic[k].platform[0].android)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].platform[0].windows) && $scope.user.buyTraffic[k].platform[0].windows)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].platform[0].blackberry) && $scope.user.buyTraffic[k].platform[0].blackberry)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].platform[0].web) && $scope.user.buyTraffic[k].platform[0].web)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].platform[0].unity) && $scope.user.buyTraffic[k].platform[0].unity)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].platform[0].bada) && $scope.user.buyTraffic[k].platform[0].bada)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                        }
                        // Check Pricing check boxes
                        if (angular.isUndefined($scope.user.buyTraffic[k].pricing)) {
                            // do nothing
                        } else {
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].cpa) && $scope.user.buyTraffic[k].pricing[0].cpa)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].cps) && $scope.user.buyTraffic[k].pricing[0].cps)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].cpc) && $scope.user.buyTraffic[k].pricing[0].cpc)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].cpv) && $scope.user.buyTraffic[k].pricing[0].cpv)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].cpd) && $scope.user.buyTraffic[k].pricing[0].cpd)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].cpi) && $scope.user.buyTraffic[k].pricing[0].cpi)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].cpl) && $scope.user.buyTraffic[k].pricing[0].cpl)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].cpm) && $scope.user.buyTraffic[k].pricing[0].cpm)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].dclick) && $scope.user.buyTraffic[k].pricing[0].dclick)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].ctc) && $scope.user.buyTraffic[k].pricing[0].ctc)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.buyTraffic[k].pricing[0].ppcall) && $scope.user.buyTraffic[k].pricing[0].ppcall)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                        }
                    });
                    var arePlatformCheckBoxesSelected = true;
                    var arePricingCheckBoxesSelected = true;
                    angular.forEach($scope.user.buyTraffic, function (val, k) {
                        if ($scope.arePlatformCheckBoxesSelected[k] == false)
                            arePlatformCheckBoxesSelected = false;
                        if ($scope.arePricingCheckBoxesSelected[k] == false)
                            arePricingCheckBoxesSelected = false;
                    });
                    if (arePlatformCheckBoxesSelected && arePricingCheckBoxesSelected) {
                        $scope.loadingBuyTraffic[index] = true;
                        //console.log(ENV.apiEndpoint);
                        //console.log($scope.user.buyTraffic);
                        //console.log(JSON.stringify({"buytraffic": $scope.user.buyTraffic}));
                        $http({
                            method: "PUT",
                            url: ENV.apiEndpoint + '/api/edit/profile/buytraffic',
                            data: {
                                buytraffic: $scope.user.buyTraffic
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            //console.log(status);
                            //console.log(data);
                            $scope.user = response.data.user
                            $scope.user.buyTraffic = response.data.buytraffic;
                            //userService.Save(data);
                            userService.update($scope.user);
                            $scope.pushMin();
                            //$scope.user.buyTraffic.push({});
                            $scope.trackProfile('Save Buying Traffic', true, 200, null);
                            mixpanel.identify($scope.user.username);
                            mixpanel.people.set({
                                "Buying Traffic Boxes": $scope.user.buyTraffic.length
                            });
                            $scope.loadingBuyTraffic[index] = false;
                            Notification.success({title: 'Saved', message: 'Buy traffic saved successfully'});
                        }).catch(function (response) {
                            //console.log(status);
                            //console.log(data);
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                $scope.trackProfile('Save Buying Traffic', false, response.status, response.data);
                                $scope.loadingBuyTraffic[index] = false;
                            }
                        });
                    } else {
                        Notification.error({title: 'Mandatory parameters', message: 'Please select pricing and platform options'});
                    }
                };
                $scope.deleteBuyTraffic = function (item) {
                    var index = $scope.user.buyTraffic.indexOf(item); // find item in array
                    $scope.loadingDeleteBuyTraffic[index] = true;
                    $scope.user.buyTraffic.splice(index, 1); // delete item from array
                    //console.log(JSON.stringify({"buytraffic": $scope.user.buyTraffic}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/buytraffic',
                        data: {
                            buytraffic: $scope.user.buyTraffic
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data.user;
                        $scope.user.buyTraffic = response.data.buytraffic;
                        // $scope.user.buyTraffic = data;
                        //userService.Save(data);

                        //$scope.user.buyTraffic.push({});
                        //$scope.loadingDeleteBuyTraffic = false;
                        $scope.trackProfile('Delete Buying Traffic', true, 200, null);
                        mixpanel.identify($scope.user.username);
                        mixpanel.people.set({
                            "Buying Traffic Boxes": $scope.user.buyTraffic.length
                        });
                        $scope.pushMin();
                        userService.update($scope.user);
                        $scope.loadingDeleteBuyTraffic[index] = false;
                        Notification.success({title: 'Deleted', message: 'Buy traffic deleted successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Delete Buying Traffic', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.saveSellTraffic = function (index) {
                    //console.log("save sell traffic");
                    $scope.arePlatformCheckBoxesSelected = [];
                    $scope.arePricingCheckBoxesSelected = [];
                    angular.forEach($scope.user.sellTraffic, function (val, k) {
                        $scope.arePlatformCheckBoxesSelected[k] = false;
                        $scope.arePricingCheckBoxesSelected[k] = false;
                        // Check Platform check boxes
                        if (angular.isUndefined($scope.user.sellTraffic[k].platform)) {
                            // do nothing
                        } else {
                            if (!angular.isUndefined($scope.user.sellTraffic[k].platform[0].ios) && $scope.user.sellTraffic[k].platform[0].ios)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].platform[0].android) && $scope.user.sellTraffic[k].platform[0].android)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].platform[0].windows) && $scope.user.sellTraffic[k].platform[0].windows)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].platform[0].blackberry) && $scope.user.sellTraffic[k].platform[0].blackberry)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].platform[0].web) && $scope.user.sellTraffic[k].platform[0].web)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].platform[0].unity) && $scope.user.sellTraffic[k].platform[0].unity)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].platform[0].bada) && $scope.user.sellTraffic[k].platform[0].bada)
                                $scope.arePlatformCheckBoxesSelected[k] = true;
                        }
                        // Check Pricing check boxes
                        if (angular.isUndefined($scope.user.sellTraffic[k].pricing)) {
                            // do nothing
                        } else {

                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].cpa) && $scope.user.sellTraffic[k].pricing[0].cpa)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].cps) && $scope.user.sellTraffic[k].pricing[0].cps)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].cpc) && $scope.user.sellTraffic[k].pricing[0].cpc)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].cpv) && $scope.user.sellTraffic[k].pricing[0].cpv)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].cpd) && $scope.user.sellTraffic[k].pricing[0].cpd)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].cpi) && $scope.user.sellTraffic[k].pricing[0].cpi)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].cpl) && $scope.user.sellTraffic[k].pricing[0].cpl)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].cpm) && $scope.user.sellTraffic[k].pricing[0].cpm)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].dclick) && $scope.user.sellTraffic[k].pricing[0].dclick)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].ctc) && $scope.user.sellTraffic[k].pricing[0].ctc)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                            if (!angular.isUndefined($scope.user.sellTraffic[k].pricing[0].ppcall) && $scope.user.sellTraffic[k].pricing[0].ppcall)
                                $scope.arePricingCheckBoxesSelected[k] = true;
                        }
                    });
                    var arePlatformCheckBoxesSelected = true;
                    var arePricingCheckBoxesSelected = true;
                    angular.forEach($scope.user.sellTraffic, function (val, k) {
                        if ($scope.arePlatformCheckBoxesSelected[k] == false)
                            arePlatformCheckBoxesSelected = false;
                        if ($scope.arePricingCheckBoxesSelected[k] == false)
                            arePricingCheckBoxesSelected = false;
                    });
                    if (arePlatformCheckBoxesSelected && arePricingCheckBoxesSelected) {
                        $scope.loadingSellTraffic[index] = true;
                        //console.log("Senging info:");
                        //console.log($scope.user.sellTraffic);
                        //console.log(JSON.stringify({"selltraffic": $scope.user.sellTraffic}));
                        $http({
                            method: "PUT",
                            url: ENV.apiEndpoint + '/api/edit/profile/selltraffic',
                            data: {
                                selltraffic: $scope.user.sellTraffic
                            },
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            //console.log(status);
                            //console.log(data);
                            $scope.user = response.data.user
                            $scope.user.sellTtraffic = response.data.sellTraffic; //userService.Save(data);
                            userService.update($scope.user);
                            $scope.pushMin();
                            $scope.trackProfile('Save Selling Traffic', true, 200, null);
                            mixpanel.identify($scope.user.username);
                            mixpanel.people.set({
                                "Selling Traffic Boxes": $scope.user.sellTraffic.length
                            });
                            $scope.loadingSellTraffic[index] = false;
                            Notification.success({title: 'Saved', message: 'Sell traffic saved successfully'});
                        }).catch(function (response) {
                            //console.log(status);
                            //console.log(data);
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                                $scope.trackProfile('Save Selling Traffic', false, response.status, response.data);
                                $scope.loadingSellTraffic[index] = false;
                            }
                        });
                    } else {
                        Notification.error({title: 'Mandatory parameters', message: 'Please select pricing and platform options'});
                    }
                };
                $scope.deleteSellTraffic = function (item) {                     //console.log("delete sell traffic");
                    var index = $scope.user.sellTraffic.indexOf(item); // find item in array
                    $scope.loadingDeleteSellTraffic[index] = true;
                    $scope.user.sellTraffic.splice(index, 1); // delete item from array
                    //console.log(JSON.stringify({"selltraffic": $scope.user.sellTraffic}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/selltraffic',
                        data: {
                            selltraffic: $scope.user.sellTraffic
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data.user
                        $scope.user.sellTtraffic = response.data.sellTraffic; //userService.Save(data);
                        $scope.trackProfile('Delete Selling Traffic', true, 200, null);
                        mixpanel.identify($scope.user.username);
                        mixpanel.people.set({
                            "Selling Traffic Boxes": $scope.user.sellTraffic.length
                        });
                        $scope.pushMin();
                        userService.update($scope.user);
                        $scope.loadingDeleteSellTraffic[index] = false;
                        Notification.success({title: 'Deleted', message: 'Sell traffic deleted successfully'});
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Delete Selling Traffic', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.someSelected = function (object) {
                    return Object.keys(object).some(function (key) {
                        return object[key];
                    });
                };
                $scope.savePayment = function () {
                    $scope.loadingPayment = true;
                    //console.log($scope.user.paymentTerms);
                    //console.log($scope.user.paymentMethods);
                    //console.log(JSON.stringify({"newPaymentTerms": $scope.user.paymentTerms, "newPaymentMethods": $scope.user.paymentMethods}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/payment',
                        data: {
                            newPaymentTerms: $scope.user.paymentTerms,
                            newPaymentMethods: $scope.user.paymentMethods
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.user = response.data.user;
                        $scope.user.paymentTerms = response.data.paymentTerms;
                        $scope.user.paymentMethods = response.data.paymentMethods;
                        //userService.Save(data);
                        userService.update($scope.user);
                        $scope.trackProfile('Save Payment', true, 200, null);
                        $scope.loadingPayment = false;
                        Notification.success({title: 'Saved', message: 'Payment information saved successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Save Payment', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.saveSummary = function () {
                    $scope.loadingSummary = true;
                    //console.log($scope.user.paymentTerms);
                    //console.log($scope.user.paymentMethods);
                    //console.log(JSON.stringify({"summary": $scope.user.summary}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/summary',
                        data: {
                            summary: $scope.user.summary
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        //   $scope.user.summary = angular.fromJson(data);
                        $scope.user = response.data.user;
                        $scope.user.summary = response.data.summary;
                        //userService.Save(data);
                        userService.update($scope.user);
                        $scope.trackProfile('Save Summary', true, 200, null);
                        Notification.success({title: 'Saved', message: 'Summary information saved successfully'});
                        $scope.loadingSummary = false;
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Save Summary', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                        $scope.loadingSummary = false;
                    });
                };
                $scope.saveTrackingServices = function () {
                    $scope.loadingTrackingServices = true;
                    //console.log($scope.user.trackingServices);
                    //console.log(JSON.stringify({"trackingServices": $scope.user.trackingServices}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/trackingServices',
                        data: {
                            trackingServices: $scope.user.trackingServices
                                    /*name:   $scope.user.services[key].name,
                                     extra:   $scope.user.services[key].extra,
                                     description:   $scope.user.services[key].description*/
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.user = response.data.user
                        $scope.user.trackingServices = response.data.trackingServices;
                        //userService.Save(data);
                        userService.update($scope.user);
                        $scope.trackProfile('Save Tracking Services', true, 200, null);
                        $scope.loadingTrackingServices = false;
                        Notification.success({title: 'Saved', message: 'Tracking services saved successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Save Tracking Services', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.references = function () {
                    $scope.loadingReferences = true;
                    //console.log(JSON.stringify({"company": $scope.user.company}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/references',
                        data: {
                            company: $scope.user.company
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.trackProfile('Save Reference', true, 200, null);
                        $scope.loadingReferences = false;
                        Notification.success({title: 'Saved', message: 'Reference saved successfully'});
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Save Reference', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.saveExperiences = function (index) {
                    $scope.loadingExperiences[index] = true;
                    //console.log($scope.user.experiences);
                    //console.log(JSON.stringify({"experiences": $scope.user.experiences}));
                    $http({method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/experiences',
                        data: {
                            experiences: $scope.user.experiences
                                    /*
                                     fromperiod:   $scope.user.experiences[key].fromperiod,
                                     toperiod:   $scope.user.experiences[key].toperiod,
                                     company:   $scope.user.experiences[key].company,
                                     description:   $scope.user.experiences[key].description,
                                     logo:   $scope.user.experiences[key].logo*/
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.user = response.data.user
                        $scope.user.experiences = response.data.experiences; //userService.Save(data);
                        userService.update($scope.user);
                        $scope.trackProfile('Save Experience', true, 200, null);
                        $scope.loadingExperiences[index] = false;
                        Notification.success({title: 'Saved', message: 'Experience saved successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Save Experience', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.deleteExperiences = function (item, event) {
                    var index = $scope.user.experiences.indexOf(item); // find item in array
                    $scope.loadingDeleteExperiences[index] = true;
                    $scope.user.experiences.splice(index, 1); // delete item from array
                    //console.log($scope.user.experiences);
                    //console.log(JSON.stringify({"experiences": $scope.user.experiences}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/experiences',
                        data: {
                            experiences: $scope.user.experiences
                                    /*
                                     fromperiod:   $scope.user.experiences[key].fromperiod,
                                     toperiod:   $scope.user.experiences[key].toperiod,
                                     company:   $scope.user.experiences[key].company,
                                     description:   $scope.user.experiences[key].description,
                                     logo:   $scope.user.experiences[key].logo*/
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user = response.data.user
                        $scope.user.experiences = response.data.experiences;
                        userService.update($scope.user);
                        $scope.trackProfile('Delete Experience', true, 200, null);
                        $scope.loadingDeleteExperiences[index] = false;
                        Notification.success({title: 'Deleted', message: 'Experience deleted successfully'});
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Delete Experience', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.events = function () {
                    //console.log(JSON.stringify({"company": $scope.user.company}));
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/edit/profile/events',
                        data: {
                            company: $scope.user.company
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.trackProfile('Save Event', true, 200, null);
                        Notification.success({title: 'Saved', message: 'Event saved successfully'});
                    }).catch(function (response) {
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            $scope.trackProfile('Save Event', false, response.status, response.data);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                /*
                 $scope.companyNameSearch = trackJs.watch(function() {
                 //console.log("companyNameSearch");
                 //console.log($scope.user.company);
                 $scope.user.companyid = null;
                 
                 $scope.loadingPersonal = 1;
                 
                 if($scope.user.company){
                 var request = $http({
                 method: "POST",
                 url: ENV.apiEndpoint + '/api/edit/company/search',
                 cache : true,
                 data: {
                 companyName:   $scope.user.company
                 },
                 headers: { 'Content-Type': 'application/json' }
                 });
                 
                 request.success(function (data, status) {
                 //console.log(status);
                 //console.log(data);
                 $scope.foundCompanies = data;
                 
                 })
                 request.error(function (data, status) {
                 //console.log(status);
                 //console.log(data);
                 if(status===401) $state.go('access.signin');
                 })
                 }
                 };*/
            }
        ])
      
      .controller('FeedCtrl', ['$scope', '$rootScope', '$stateParams', '$http', '$state', 'Notification', 'userService', 'myUser', 'ENV', '$interval', 'queryService', 'searchService', 'postPreviewService', 'userFeedService', '$location', '$anchorScroll', '$uibModal', 'storestastics', function ($scope, $rootScope, $stateParams, $http, $state, Notification, userService, myUser, ENV, $interval, queryService, searchService, postPreviewService, userFeedService, $location, $anchorScroll, $uibModal, storestastics) {
                console.log("### CONTROLLER: FeedCtrl ####");
                $rootScope.ogUrl = $location.absUrl();
                document.cookie = 'widget=; path=/; expires=' + new Date(0).toUTCString();
                $scope.itemsPerPage = 9; // MUST BE SYNC WITH SERVER LIMIT
                $scope.feedPerPage = 30; // MUST BE SYNC WITH SERVER LIMIT
                $scope.feedUpdateCounter = 0;
                $scope.feedFilter = 'all';
                $scope.feedType = 'all';
                //$scope.newsFeed = {};
                $scope.feedSkip = 0;
                $scope.newsSkip = 0;
                $scope.isFeedLoading = false;
                $scope.newPost = {};
                $scope.loadingSendingPost = false;
                $scope.sourceIDs = {};
                $scope.sourceIDs['2944392'] = 'eMarketer';
                $scope.sourceIDs['2909040'] = 'VentureBeat';
                $scope.sourceIDs['2909038'] = 'GamesIndustry';
                $scope.sourceIDs['3028337'] = 'MobileAdvertisingWatch';
                $scope.sourceIDs['2909036'] = 'MobileMarketer';
                $scope.sourceIDs['2909034'] = 'MobileMarketingMagazine';
                $scope.sourceIDs['2909033'] = 'PocketGamer';
                $scope.sourceIDs['2897233'] = 'MobileMarketingWatch';
                $scope.sourceIDs['2895208'] = 'TheNextWeb';
                $scope.sourceIDs[ '2886070'] = 'TechCrunch';                 //$scope.userfeed = {};
                $scope.userfeed = userFeedService.userfeed;
                $scope.$on('handleUserFeed', function () {
                    $scope.userfeed = userFeedService.userfeed;
                });
                $scope.user = myUser;
                userService.update($scope.user);
                /*
                 trackJs.configure({
                 // Custom session identifier.
                 sessionId: $scope.user.id,
                 // Custom user identifier.
                 userId: $scope.user.username,
                 // Custom application identifier.
                 version: JSON.stringify($scope.user)
                 
                 });
                 */
                $scope.changeFeedType = function (newtype) {
                    $scope.userfeed = {};
                    $scope.feedType = newtype;
                    $scope.filterFeed('all');
                    $scope.isLoadableFeed = true;
                };
                $scope.getTooltip = function (liked, username) {
                    var tooltip = '';
                    angular.forEach(liked, function (v, k) {
                        if (username != v.username) {
                            if (v.username == $scope.user.username)
                                tooltip = tooltip + ' You\n';
                            else
                                tooltip = tooltip + ' ' + v.name + ' ' + v.lastname + '\n';
                        }
                    });
                    return tooltip.replace(/(?:^|\s)\S/g, function (a) {
                        return a.toUpperCase();
                    });
                };
                /*
                 $scope.goToAnchor = trackJs.watch(function (feedID, index) {
                 console.log(index);
                 console.log(feedID+'-comment-'+index);
                 $location.hash(feedID+'-comment-'+index);
                 $anchorScroll();
                 });*/
                $scope.loadFeed = function (skip, newtype) {
                    $scope.isFeedLoading = true;
                    $scope.feedType = newtype;
                    //console.log(JSON.stringify({"filter": $scope.feedFilter, "skip": $scope.feedSkip}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/social/feed',
                        cache: false,
                        data: {
                            filter: $scope.feedFilter,
                            type: $scope.feedType,
                            skip: $scope.feedSkip},
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (skip > 0) {
                            $scope.userfeed = $scope.userfeed.concat(response.data.feed);
                        } else
                            $scope.userfeed = response.data.feed;
                        userFeedService.update($scope.userfeed);
                        //$scope.userfeed = data;
                        $scope.feedUpdateCounter = 0;
                        //console.log(data.length);
                        //console.log('userfeed:');
                        //console.log($scope.userfeed);
                        if (response.data.counter > $scope.userfeed.length)
                            $scope.isLoadableFeed = true;
                        else
                            $scope.isLoadableFeed = false;
                        $scope.isFeedLoading = false;
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.isFeedLoading = false;
                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                    });
                };
                $scope.loadAllFeed = function () {
                    $scope.feedFilter = 'all';
                    $scope.feedType = 'all';
                    $scope.feedSkip = 0;
                    $scope.loadFeed($scope.feedSkip, $scope.feedType);
                };
                $scope.postPreview = function () {
                    var preview = postPreviewService.preview($scope);
                    $scope.newPost = preview.newPost;
                    $scope.loadingSendingPost = preview.loadingSendingPost;
                };
                $scope.resetPostMedia = function () {
                    var text = $scope.newPost.text;
                    $scope.newPost.image = null;
                    $scope.newPost = {};
                    $scope.newPost.text = text;
                };
                $scope.feedCounter = function () {                     //console.log($scope.mail);
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/social/feedcounter',
                        cache: false,
                        data: {
                            filter: $scope.feedFilter
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.feedUpdateCounter = response.data;
						                    
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                    });
                };
                $scope.openCommentsBox = function (feedIndex) {
                    var elem = document.getElementById(feedIndex + '-feed');
                    //elem.scrollTop = elem.scrollHeight;
                    elem.scrollTop = 9999999;
                };
                $scope.filterFeed = function (filter) {
                    $scope.feedSkip = 0;
                    $scope.userfeed = {};
                    $scope.feedFilter = filter;
                    $scope.refreshFeed();
                    //$scope.loadFeed($scope.feedSkip, $scope.feedType);
                };
                $scope.like = function (feed) {                     // WE MUST INVEST TIME TO REFACTOR THIS!!!!!!
                    $scope.isLikeLoading = true;
                    var updateID = null;
                    if (feed.isLike == true || feed.isComment == true)
                        updateID = feed.updateID;
                    else {
                        if (!angular.isUndefined(feed.id))
                            updateID = feed.id;
                        if (!angular.isUndefined(feed._id) && !angular.isUndefined(feed._id.$id))
                            updateID = feed._id.$id;
                    }
                    //console.log("updateID");
                    //console.log(updateID);
                    var userFeed = {};
                    //if (isNewsFeed) userFeed = $scope.newsFeed;
                    //else userFeed = $scope.userfeed;
                    userFeed = $scope.userfeed;
                    angular.forEach(userFeed, function (v, k) {                         //var f = v['id'];
                        var currentID = null;
                        if (feed.isLike == true || feed.isComment == true)
                            currentID = v['updateID'];
                        else {
                            if (!angular.isUndefined(v['id']))
                                currentID = v['id'];
                            if (!angular.isUndefined(v['_id']) && !angular.isUndefined(v['_id'].$id))
                                currentID = v['_id'].$id;
                        }
                        //console.log("currentID");
                        //console.log(currentID);
                        if (currentID == updateID) {
                            v.liked.push({
                                'userID': $scope.user.id, 'username': $scope.user.username,
                                'name': $scope.user.name, 'lastname': $scope.user.lastname,
                                'avatar': $scope.user.avatar,
                                'jobTitle': $scope.user.jobTitle,
                                'company': $scope.user.company
                            });
                        }
                    });
                    //if (isNewsFeed) $scope.newsFeed = userFeed;
                    //else $scope.userfeed = userFeed;
                    $scope.userfeed = userFeed;
                    userFeedService.update($scope.userfeed);
                    //console.log('updateID: '+updateID);
                    //console.log(JSON.stringify({"updateID": updateID}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/social/like',
                        cache: false,
                        data: {
                            updateID: updateID
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        //console.log($scope.userfeed);
                        mixpanel.identify($scope.user.username);
                        mixpanel.people.increment("Liked a post");
                        mixpanel.track('Liked a post', {
                            "Page": "Feed Page",
                            "Type": "Action",
                            "Text": "Like",
                            "Action type": feed.action,
                            "Success": true,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                        $scope.isLikeLoading = false;
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.isLikeLoading = false;
                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                    });
                };
                $scope.unlike = function (feed) {                     // WE MUST INVEST TIME TO REFACTOR THIS!!!!!!
                    $scope.isLikeLoading = true;
                    var updateID = null;
                    if (feed.isLike == true || feed.isComment == true)
                        updateID = feed.updateID;
                    else {
                        if (!angular.isUndefined(feed.id))
                            updateID = feed.id;
                        if (!angular.isUndefined(feed._id) && !angular.isUndefined(feed._id.$id))
                            updateID = feed._id.$id;
                    }
                    //console.log("updateID");
                    //console.log(updateID);
                    //console.log($scope.userfeed);
                    var userFeed = {};
                    //if (isNewsFeed) userFeed = $scope.newsFeed;
                    //else userFeed = $scope.userfeed;
                    userFeed = $scope.userfeed;
                    angular.forEach(userFeed, function (v, k) {
                        var currentID = null;
                        if (feed.isLike == true || feed.isComment == true)
                            currentID = v['updateID'];
                        else {
                            if (!angular.isUndefined(v['id']))
                                currentID = v['id'];
                            if (!angular.isUndefined(v['_id']) && !angular.isUndefined(v['_id'].$id))
                                currentID = v['_id'].$id;
                        }
                        //console.log("currentID");
                        //console.log(currentID);
                        if (currentID == updateID) {
                            var index = -1;
                            angular.forEach(v.liked, function (v, k) {                                 //console.log("v");
                                //console.log(v);
                                if (v.username == $scope.user.username)
                                    index = k;
                            });
                            /*var index = v.liked.indexOf({
                             'userID': $scope.user.id,
                             'username': $scope.user.username,
                             'name': $scope.user.name,
                             'lastname': $scope.user.lastname,
                             'avatar': $scope.user.avatar,
                             'jobTitle': $scope.user.jobTitle,
                             'company': $scope.user.company
                             }); // find item in array
                             */
                            //console.log("index");
                            //console.log(index);
                            if (index >= 0)
                                v.liked.splice(index, 1); // delete item from array
                        }
                    });
                    //if (isNewsFeed) $scope.newsFeed = userFeed;
                    //else $scope.userfeed = userFeed;
                    $scope.userfeed = userFeed;
                    userFeedService.update($scope.userfeed);
                    //console.log($scope.userfeed);
                    //console.log('updateID: '+updateID);
                    //console.log(JSON.stringify({"updateID": updateID}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/social/unlike',
                        cache: false,
                        data: {
                            updateID: updateID
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.isLikeLoading = false;
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.isLikeLoading = false;
                        Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                    });
                };
                $scope.addComment = function (feed, feedIndex) {
                    //console.log(feed);
                    if (!angular.isUndefined(feed.newcomment) && feed.newcomment != null && feed.newcomment.length > 0) {
                        var updateID = null;
                        if (feed.isLike == true || feed.isComment == true)
                            updateID = feed.updateID;
                        else {
                            if (!angular.isUndefined(feed.id))
                                updateID = feed.id;
                            if (!angular.isUndefined(feed._id) && !angular.isUndefined(feed._id.$id))
                                updateID = feed._id.$id;
                        }
                        //console.log(JSON.stringify({"updateID": updateID}));
                        if (angular.isUndefined(feed.comments) || feed.comments == null)
                            feed.comments = [];
                        feed.comments.push({
                            'userID': $scope.user.id, 'username': $scope.user.username,
                            'name': $scope.user.name, 'lastname': $scope.user.lastname,
                            'avatar': $scope.user.avatar,
                            'text': feed.newcomment,
                            'date': (Date.now() / 1000),
                            'jobTitle': $scope.user.jobTitle,
                            'company': $scope.user.company
                        });
                        //$scope.goToAnchor(feed.userID, feed.comments.length - 1);
                        $http({
                            method: "POST",
                            url: ENV.apiEndpoint + '/api/social/comment',
                            data: {
                                updateID: updateID,
                                newcomment: feed.newcomment},
                            headers: {'Content-Type': 'application/json'}
                        }).then(function (response) {
                            //console.log(status);
                            //console.log(data);
                            //userService.update($scope.user);
                            mixpanel.identify($scope.user.username);
                            mixpanel.people.increment('Comment Update');
                            mixpanel.track('Comment Update', {
                                "Page": $state.current.url,
                                "Type": "Action",
                                "Text": 'Comment Update', "updateID": updateID,
                                "Success": true,
                                "Username": $scope.user.username,
                                "$email": $scope.user.email,
                                "Company": $scope.user.company,
                                "Job Title": $scope.user.jobTitle,
                                "Role": $scope.user.companyType,
                                "Subrole": $scope.user.companySubType
                            });
                            //document.getElementById(feedIndex+'-feed').scrollTop = 9999999;
                            var elem = document.getElementById(feedIndex + '-feed');
                            elem.scrollTop = elem.scrollHeight;
                        }).catch(function (response) {
                            //console.log(status);
                            //console.log(data);
                            if (response.status === 401)
                                $state.go('access.signin');
                            else {
                                mixpanel.identify($scope.user.username);
                                mixpanel.track('Comment Update', {
                                    "Page": $state.current.url,
                                    "Type": "Action",
                                    "Text": 'Comment Update', "updateID": updateID,
                                    "Error": true,
                                    "Username": $scope.user.username,
                                    "$email": $scope.user.email,
                                    "Company": $scope.user.company,
                                    "Job Title": $scope.user.jobTitle,
                                    "Role": $scope.user.companyType,
                                    "Subrole": $scope.user.companySubType
                                });
                                Notification.error({
                                    title: 'Error (' + response.status + ')',
                                    message: 'Ops! Something went wrong...'
                                });
                            }
                        });
                        feed.newcomment = null;
                        //$scope.new.conversation = null;
                    }
                };
                $scope.loadNews = function (skip, newtype) {
                    $scope.isFeedLoading = true;
                    $scope.feedType = newtype;
                    //console.log(JSON.stringify({"skip": skip}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/social/news',
                        cache: false,
                        data: {
                            skip: skip
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (skip > 0) {
                            $scope.userfeed = $scope.userfeed.concat(response.data.news);
                        } else
                            $scope.userfeed = respone.data.news;
                        userFeedService.update($scope.userfeed);
                        if (response.data.counter > $scope.userfeed.length)
                            $scope.isLoadableFeed = true;
                        else
                            $scope.isLoadableFeed = false;
                        $scope.isFeedLoading = false;
                    }).catch(function (response) {
                        // log error
                        //console.log(status);
                        //console.log(data);
                        $scope.isFeedLoading = false;
                    });
                };
                $scope.loadMyPosts = function (skip, newtype) {
                    $scope.isFeedLoading = true;
                    $scope.feedType = newtype;
                    //console.log(ENV.baseUrl+'/json/news-'+skip+'.json?'+Date.now());
                    //$http.get(ENV.baseUrl+'/json/news-'+skip+'.json?'+Date.now(), { cache: false, skipAuthorization: true }).
                    //$http.get(ENV.baseUrl+'/api/social/news/'+skip, { cache: false, skipAuthorization: false }).
                    //console.log(JSON.stringify({"skip": skip}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/social/myposts', cache: false,
                        data: {
                            skip: skip
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (skip > 0) {
                            $scope.userfeed = $scope.userfeed.concat(response.data.posts);
                        } else
                            $scope.userfeed = response.data.posts;
                        userFeedService.update($scope.userfeed);
                        if (response.data.counter > $scope.userfeed.length)
                            $scope.isLoadableFeed = true;
                        else
                            $scope.isLoadableFeed = false;
                        $scope.isFeedLoading = false;
                    }).catch(function (response) {
                        // log error
                        //console.log(status);
                        //console.log(data);
                        $scope.isFeedLoading = false;
                    });
                };
                $scope.isLoadableNews = true;
                $scope.isLoadableFeed = true;
                $scope.loadFeed($scope.feedSkip, $scope.feedType);
                $scope.loadMoreUpdates = function () {
                    $scope.feedSkip = $scope.feedSkip + 1;
                    switch ($scope.feedType) {
                        case 'touchnews':
                            $scope.loadNews($scope.feedSkip, $scope.feedType);
                            break;
                        case 'myposts':
                            $scope.loadMyPosts($scope.feedSkip, $scope.feedType);
                            break;
                        case 'all':
                        case 'touchfeed':
                        default:
                            $scope.loadFeed($scope.feedSkip, $scope.feedType);
                            break;
                    }
                };
                $scope.refreshFeed = function () {
                    $scope.feedSkip = 0;
                    switch ($scope.feedType) {
                        case 'touchnews':
                            $scope.loadNews($scope.feedSkip, $scope.feedType);
                            break;
                        case 'myposts':
                            $scope.loadMyPosts($scope.feedSkip, $scope.feedType);
                            break;
                        case 'all':
                        case 'touchfeed':
                        default:
                            $scope.loadFeed($scope.feedSkip, $scope.feedType);
                            break;
                    }
                };
                /*$scope.loadMoreFeed = trackJs.watch(function () {
                 $scope.feedSkip = $scope.feedSkip + 1;
                 $scope.loadFeed($scope.feedSkip, $scope.feedType);
                 });
                 
                 $scope.loadMoreNews = trackJs.watch(function () {
                 $scope.newsSkip = $scope.newsSkip + 1;
                 $scope.loadNews($scope.newsSkip);
                 });
                 
                 $scope.refreshNews = trackJs.watch(function () {
                 $scope.newsSkip = 0;
                 $scope.loadNews($scope.newsSkip);
                 });*/
                $scope.trackNews = function (text, title, source) {
                    mixpanel.identify($scope.user.username);
                    mixpanel.people.increment('Read news');
                    mixpanel.track('Read news', {
                        "Page": $state.current.url,
                        "Type": "Link",
                        "Text": text,
                        "Username": $scope.user.username,
                        "$email": $scope.user.email,
                        "Company": $scope.user.company,
                        "Job Title": $scope.user.jobTitle,
                        "Role": $scope.user.companyType,
                        "Subrole": $scope.user.companySubType,
                        "title": title,
                        "source": source
                    });
                };
                $scope.trackEvent = function (eventName) {
                    //console.log(eventName);
                    mixpanel.identify($scope.user.username);
                    mixpanel.people.increment("Event Registration");
                    mixpanel.track("Event Registration", {
                        "Page": $state.current.url,
                        "Type": "Action",
                        "Text": "Register Now", "Position": "Feed",
                        "Event Name": eventName,
                        "Success": true,
                        "Username": $scope.user.username,
                        "$email": $scope.user.email,
                        "Company": $scope.user.company,
                        "Job Title": $scope.user.jobTitle,
                        "Role": $scope.user.companyType,
                        "Subrole": $scope.user.companySubType
                    });
                };
                $scope.explore = function () {
                    $scope.loadingSearch = true;
                    $scope.query = {};
                    $scope.query.profileType = 'people';
                    $scope.query.companyType = 'All';
                    $scope.query.explore = true;
                    queryService.update($scope.query);
                    queryService.updateResults(0, {}, '', false, $scope.query.profileType, true);
                    //console.log('Explore Search');
                    if (angular.isUndefined($scope.query.skip))
                        $scope.query.skip = 0;
                    $scope.query.skip = 0;
                    searchService.search($scope);
                    $state.go('app.page.search');
                };
                $scope.sendUserPost = function () {
                    $scope.loadingSendingPost = true;
                    var postType = 'Text';
                    if ($scope.newPost.image)
                        postType = 'Photo';
                    if ($scope.newPost.link)
                        postType = 'Article';
                    if ($scope.newPost.vimeo || $scope.newPost.youtube)
                        postType = 'Video';
                    //console.log(JSON.stringify({"newPost": $scope.newPost}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/social/user/post/create',
                        data: {
                            newPost: $scope.newPost
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.refreshFeed();
                        $scope.newPost.image = null;
                        $scope.newPost = {};
                        mixpanel.identify($scope.user.username);
                        mixpanel.people.increment("User Post");
                        mixpanel.track('User Post', {
                            "Page": "Feed",
                            "Type": "Action",
                            "Text": "Post",
                            "Post type": postType,
                            "Success": true,
                            "Username": $scope.user.username,
                            "$email": $scope.user.email,
                            "Company": $scope.user.company,
                            "Job Title": $scope.user.jobTitle,
                            "Role": $scope.user.companyType,
                            "Subrole": $scope.user.companySubType
                        });
                        $scope.loadingSendingPost = false;
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        $scope.loadingSendingPost = false;
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            mixpanel.identify($scope.user.username);
                            mixpanel.track('User Post', {
                                "Page": "Feed",
                                "Type": "Action",
                                "Text": "Post",
                                "Post type": postType,
                                "Error": true,
                                "Username": $scope.user.username,
                                "$email": $scope.user.email,
                                "Company": $scope.user.company,
                                "Job Title": $scope.user.jobTitle,
                                "Role": $scope.user.companyType,
                                "Subrole": $scope.user.companySubType
                            });
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.showUsersList = function (action, list) {
                    $uibModal.open({
                        templateUrl: 'tpl/forms/usersList.html?v=' + ENV.latestUpdate,
                        controller: 'UsersListInstanceCtrl',
                        size: 'sm',
                        resolve: {
                            list: function () {
                                return list;
                            },
                            action: function () {
                                return action;
                            }
                        }
                    });
                };
                $scope.storestastics = function (feed, type) {
                    storestastics.update({'postid': btoa(feed), 'type': btoa(type)});
                };
                setInterval(function () {
                    if ($scope.user.username)
                    {
                        $scope.feedCounter();
                    }
                    // 60000 = 1 minute
                }, 60000);
            }])
    
 // Edit Profile controller
        .controller('ListFollowersController', ['$scope', '$http', '$state', 'AuthService', 'userService', 'Notification', 'ENV', 'myCompany', '$location', function ($scope, $http, $state, AuthService, userService, Notification, ENV, myCompany,$location) {
                console.log("### CONTROLLER: FollowController ####");
                $rootScope.ogUrl = $location.absUrl();
                $scope.company = myCompany;
                //console.log($scope.company);
            }])
        // Edit Profile controller
        .controller('FollowController', ['$scope', '$http', '$state', 'AuthService', 'userService', 'Notification', 'ENV', '$location', function ($scope, $http, $state, AuthService, userService, Notification, ENV, $location) {
                console.log("### CONTROLLER: FollowController ####");
                $scope.currentFollowing = {};
                $scope.user = userService.user;
                $scope.$on('handleUser', function () {
                    $scope.user = userService.user; //    $scope.inTouchStatus = $scope.getInTouchStatus();
                });
                /*  $scope.getInTouchStatus = function (){
                 if(angular.isUndefined($scope.user.inTouch)) return 0;
                 else if($scope.user.inTouch==null || angular.isUndefined($scope.user.inTouch[$scope.view.id])) return 0;
                 else return $scope.user.inTouch[$scope.view.id]['status'];
                 return 0;
                 };
                 
                 $scope.inTouchStatus = $scope.getInTouchStatus();*/
                $scope.skip = function () {                     //console.log($scope.user.id);
                    $http({
                        method: "PUT",
                        url: ENV.apiEndpoint + '/api/update/user/version',
                        data: {
                            skipStatus: "true"
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        $scope.user.skipStatus = true;
                    }).catch(function (response) {
                    });
                };
                $scope.follow = function (username) {
                    //console.log(username);
                    //$scope.currentFollowing[username] = 0;
                    //console.log(JSON.stringify({"username": username}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/follow',
                        data: {
                            username: username
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {

                        try {
                            if (angular.isUndefined($scope.user.following) || $scope.user.following == null)
                                $scope.user.following = [];
                            $scope.user.following.push(response.data.newfollowing);
                            //userService.Save(data);
                            userService.update($scope.user);
                            if ($scope.publicCompany) {
                                $scope.publicCompany.followers.push(response.data.newfollower);
                            } else if ($location.path() != '/' && $scope.company && username == $scope.company.username) {
                                if (angular.isUndefined($scope.company.followers) || $scope.company.followers == null)
                                    $scope.company.followers = [];
                                $scope.company.followers.push(response.data.newfollower);
                            }
                            $scope.currentFollowing[username] = 1;
                        } catch (e) {
                            console.log(e);
                        }


                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
                $scope.unfollow = function (username) {
                    //console.log(JSON.stringify({"username": username}));
                    $http({
                        method: "POST",
                        url: ENV.apiEndpoint + '/api/unfollow',
                        data: {
                            username: username
                        },
                        headers: {'Content-Type': 'application/json'}
                    }).then(function (response) {
                        var updatedFollowing = Object.keys(response.data.user.following).map(function (k) {
                            return response.data.user.following[k]
                        });
                        $scope.user.following = updatedFollowing; //userService.Save(data);
                        userService.update($scope.user);
                        if ($scope.publicCompany) {
                            var index = $scope.publicCompany.followers.indexOf(response.data.follower); // find post in array
                            $scope.publicCompany.followers.splice(index, 1);
                        } else if ($location.path() != '/' && $scope.company && username == $scope.company.username && $scope.company && $scope.company.followers) {
                            var index = $scope.company.followers.indexOf(response.data.follower); // find post in array
                            $scope.company.followers.splice(index, 1); // delete post from array
                            //$scope.company.followers.splice(data.follower);
                        }
                        $scope.currentFollowing[username] = -1;
                    }).catch(function (response) {
                        //console.log(status);
                        //console.log(data);
                        if (response.status === 401)
                            $state.go('access.signin');
                        else {
                            console.log(response);
                            Notification.error({title: 'Error (' + response.status + ')', message: 'Ops! Something went wrong...'});
                        }
                    });
                };
            }])