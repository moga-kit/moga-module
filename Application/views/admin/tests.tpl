<!doctype html>
<html lang="en" ng-app="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.0/angular-csp.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/fontawesome.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/solid.min.css"/>
    <style type="text/css" media="screen">
        #editor {
            height: 500px;
        }
    </style>
    <title>MOGA Customizer</title>
</head>
<body ng-controller="ctrl" class="p-4">

<nav class="navbar navbar-expand navbar-light bg-light" ng-repeat="page in pages">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h2" ng-bind="page.title"></span>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <button class="btn btn-outline-primary" ng-click="screenshot(page.url)">Screenshot</button>
                </li>
            </ul>
        </div>

    </div>
</nav>

<div class="row" id="content">

</div>
<div class="row">

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.0/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
<script src='[{$oViewConf->getModuleUrl("tpl-manager","out/html2canvas.min.js")}]'></script>
<script>
    var app = angular.module('app', []);
    app.controller('ctrl', function ($scope, $http)
    {
        $scope.loading = 0;

        $scope.selected = {};
        $scope.pages = [
            {
                'title': 'Startseite',
                'url': '[{$oViewConf->getBaseDir()}]'
            }
        ];
        $scope.tests = [
            {
                'title': 'PageSpeed Insights',
                'url': 'https://developers.google.com/speed/pagespeed/insights/?url=###'
            }
        ];
        $scope.screenshot = function ($url)
        {
            console.log("screenshot: "+$url);
            $scope.loading++;
            $http.get($url).then(
                function success(response)
                {
                    $scope.loading--;
                    html2canvas(response).then(function(canvas) {
                        document.body.appendChild(canvas);
                    });
                },
                function error(response)
                {
                    $scope.loading--;
                    console.log("error", response);
                });
        }

    });
</script>
</body>
</html>