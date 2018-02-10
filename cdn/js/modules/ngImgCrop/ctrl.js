app.controller('ImgCropCtrl', ['$scope', '$http', 'ENV', function ($scope, $http, ENV) {
        $scope.myImage = '';
        $scope.myCroppedImage = '';
        $scope.cropType = "circle";

        $scope.setCropType = function (type) {
            $scope.cropType = type;
        };

        var handleFileSelect = function (evt) {
            var file = evt.currentTarget.files[0];
            var reader = new FileReader();
            reader.onload = function (evt) {
                $scope.$apply(function ($scope) {
                    $scope.myImage = evt.target.result;
                });
            };
            reader.readAsDataURL(file);
        };

        $scope.isRotating = false;
        $scope.rotate = function (isClockwise) {
            if (!$scope.myImage)
                return;
            $scope.isRotating = true;
            rotateBase64Image($scope.myImage, isClockwise, function (result) {
                $scope.$apply(function () {  // $apply is required because we were called back outside of angular system
                    $scope.myImage = result;
                    $scope.isRotating = false;
                });
            });
        };

        function rotateBase64Image(base64data, isClockwise, callback) {
            var image = new Image();
            image.onload = function () {
                var canvas = document.createElement('canvas');
                canvas.width = image.height;
                canvas.height = image.width;
                var ctx = canvas.getContext("2d");
                var deg = isClockwise ? Math.PI / 2 : Math.PI / -2;
                // translate to center-canvas 
                // the origin [0,0] is now center-canvas
                ctx.translate(canvas.width / 2, canvas.height / 2);
                // roate the canvas by +90% (==Math.PI/2)
                ctx.rotate(deg);
                // draw the signature
                // since images draw from top-left offset the draw by 1/2 width & height
                ctx.drawImage(image, -image.width / 2, -image.height / 2);
                // un-rotate the canvas by -90% (== -Math.PI/2)
                ctx.rotate(-deg);
                // un-translate the canvas back to origin==top-left canvas
                ctx.translate(-canvas.width / 2, -canvas.height / 2);
                callback(canvas.toDataURL());
            };
            image.crossOrigin = "Anonymous";
            image.src = base64data;
        }

        $scope.avatar = function () {
            $scope.loadingAvatar = true;
            //console.log('popup avatar fcoverunction:');
            //console.log($scope.myCroppedImage);
            //console.log($scope.myImage);
            var request = $http({
                method: "POST",
                url: ENV.apiEndpoint + '/api/edit/profile/avatar',
                data: {
                    avatar: $scope.myCroppedImage,
                    uploadedAvatar: $scope.myImage
                },
                headers: {'Content-Type': 'application/json'}
            }).then(function (response) {
                //console.log(status);
                //console.log(data);
                //$scope.user.avatar = data; this should avoid us to re-load the page
                //userService.Save($rootScope.user);
                $scope.loadingAvatar = false;
                $scope.close(response.data);
            }).catch(function (response) {
                //console.log(status);
                //console.log(data);
                if (response.status === 401)
                    $state.go('access.signin');
                $scope.authError = 'Error: ' + response.status;
            });
        };

        $scope.removeAvatar = function () {
            $scope.removingAvatar = true;
            $http({
                method: "POST",
                url: ENV.apiEndpoint + '/api/remove/profile/avatar',
                headers: {'Content-Type': 'application/json'}
            }).then(function (response) {
                //console.log(status);
                //console.log(data);
                //$scope.user.avatar = data; this should avoid us to re-load the page
                //userService.Save($rootScope.user);
                $scope.removingAvatar = false;
                $scope.close(response.data);
            }).catch(function (response) {
                //console.log(status);
                //console.log(data);
                if (response.status === 401)
                    $state.go('access.signin');
                $scope.authError = 'Error: ' + response.status;
            });
        };

        $scope.companyAvatar = function () {
            $scope.loadingAvatar = true;
            //console.log('popup company avatar function:');
            //console.log($scope.myCroppedImage);
            $http({
                method: "POST",
                url: ENV.apiEndpoint + '/api/edit/company/avatar',
                data: {
                    avatar: $scope.myCroppedImage
                },
                headers: {'Content-Type': 'application/json'}
            }).then(function (response) {
                //console.log(status);
                //console.log(data);
                //$scope.user.avatar = data; this should avoid us to re-load the page
                //userService.Save($rootScope.user);
                $scope.loadingAvatar = false;
                $scope.close(response.data);
            }).catch(function (response) {
                //console.log(status);
                //console.log(data);
                if (response.status === 401)
                    $state.go('access.signin');
                $scope.authError = 'Error: ' + response.status;
            });
        };

        $scope.removeCompanyAvatar = function () {
            $scope.removingCompanyAvatar = true;
            //console.log('popup company avatar function:');
            //console.log($scope.myCroppedImage);
            $http({
                method: "POST",
                url: ENV.apiEndpoint + '/api/remove/company/avatar',
                headers: {'Content-Type': 'application/json'}
            }).then(function (response) {
                //console.log(status);
                //console.log(data);
                //$scope.user.avatar = data; this should avoid us to re-load the page
                //userService.Save($rootScope.user);
                $scope.removingCompanyAvatar = false;
                $scope.close(response.data);
            }).catch(function (response) {
                //console.log(status);
                //console.log(data);
                if (response.status === 401)
                    $state.go('access.signin');
                $scope.authError = 'Error: ' + response.status;
            });
        };


        $scope.cover = function () {
            $scope.loadingCover = true;
            //console.log('popup cover function:');
            //console.log($scope.user.cover);
            //console.log(JSON.stringify({"cover": $scope.user.cover}));
            $http({
                method: "POST",
                url: ENV.apiEndpoint + '/api/edit/profile/cover',
                data: {
                    cover: $scope.myCroppedImage
                },
                headers: {'Content-Type': 'application/json'}
            }).then(function (response) {
                //console.log(status);
                //console.log(data);
                $scope.loadingCover = false;
                $scope.close(response.data);
            }).catch(function (response) {
                //console.log(status);
                //console.log(data);
                if (response.status === 401)
                    $state.go('access.signin');
                $scope.authError = 'Error: ' + response.status;
            });
        };

        $scope.removeCover = function () {
            $scope.removingCover = true;
            $http({
                method: "POST",
                url: ENV.apiEndpoint + '/api/remove/profile/cover',
                headers: {'Content-Type': 'application/json'}
            }).then(function (response) {
                //console.log(status);
                //console.log(data);
                $scope.removingCover = false;
                $scope.close(response.data);
            }).catch(function (response) {
                //console.log(status);
                //console.log(data);
                if (response.status === 401)
                    $state.go('access.signin');
                $scope.authError = 'Error: ' + response.status;
            });
        };

        $scope.companyCover = function () {
            $scope.loadingCover = true;
            //console.log('popup cover function:');
            //console.log($scope.user.cover);
            //console.log(JSON.stringify({"cover": $scope.company.cover}));
            $http({
                method: "POST",
                url: ENV.apiEndpoint + '/api/edit/company/cover',
                data: {
                    cover: $scope.myCroppedImage
                },
                headers: {'Content-Type': 'application/json'}
            }).then(function (response) {
                //console.log(status);
                //console.log(data);
                $scope.loadingCover = false;
                $scope.close(response.data);
            }).catch(function (response) {
                //console.log(status);
                //console.log(data);
                if (response.status === 401)
                    $state.go('access.signin');
                $scope.authError = 'Error: ' + response.status;
            });
        };

        $scope.removeCompanyCover = function () {
            $scope.removingCompanyCover = true;
            $http({
                method: "POST",
                url: ENV.apiEndpoint + '/api/remove/company/cover',
                headers: {'Content-Type': 'application/json'}
            }).then(function (response) {
                //console.log(status);
                //console.log(data);
                $scope.removingCompanyCover = false;
                $scope.close(response.data);
            }).catch(function (response) {
                //console.log(status);
                //console.log(data);
                if (response.status === 401)
                    $state.go('access.signin');
                $scope.authError = 'Error: ' + response.status;
            });
        };

        $scope.clientAvatar = function (serviceIndex, clientIndex) {
            $scope.loadingClientAvatar = true;

            $http({
                method: "POST",
                url: ENV.apiEndpoint + '/api/edit/profile/add/client/avatar',
                data: {
                    avatar: $scope.myCroppedImage,
                    serviceIndex: $scope.selectedIndex,
                    clientIndex: $scope.clientIndex
                },
                headers: {'Content-Type': 'application/json'}
            }).then(function (response) {
                $scope.loadingClientAvatar = false;
                $scope.close(response.data);
            }).catch(function (response) {
                if (response.status === 401)
                    $state.go('access.signin');
                $scope.authError = 'Error: ' + response.status;
                $scope.loadingClientAvatar = false;
            });
        };

        $scope.experienceCompanyAvatar = function (experienceIndex) {
            $scope.loadingExperienceCompanyAvatar = true;
            $http({
                method: "POST",
                url: ENV.apiEndpoint + '/api/edit/profile/add/experience/company/avatar',
                data: {
                    avatar: $scope.myCroppedImage,
                    experienceIndex: $scope.experienceIndex
                },
                headers: {'Content-Type': 'application/json'}
            }).then(function (response) {
                $scope.loadingExperienceCompanyAvatar = false;
                $scope.close(response.data);
            }).catch(function (response) {
                if (response.status === 401)
                    $state.go('access.signin');
                $scope.authError = 'Error: ' + response.status;
                $scope.loadingExperienceCompanyAvatar = false;
            });
        };

        angular.element(document.querySelector('#fileInput')).on('change', handleFileSelect);
    }]);