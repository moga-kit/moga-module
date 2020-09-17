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

<div class="accordion" id="customizerAccordion">
    [{* color scheme *}]
    <div class="card">
        <div class="card-header" id="scssVariablesHeading">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#scssVariablesCollapse" aria-expanded="true" aria-controls="scssVariables">
                    <h3>SCSS Variablen</h3>
                </button>
            </h2>
        </div>

        <div id="scssVariablesCollapse" class="collapse show" aria-labelledby="scssVariablesHeading" data-parent="#customizerAccordion">
            <div class="card-body">
                <form class="row">
                    <div class="col-12 my-3">
                        <div class="float-right">
                            <button ng-click="resetScssColors()" class="btn btn-sm btn-outline-danger">Alle Farben zurücksetzen</button>
                        </div>
                        <h4>Farben</h4>
                    </div>
                    <div ng-repeat="var in scssColors" class="col-12 col-md-6 row g-1">
                        <div class="col-6 col-md-4 col-xxl-3 text-right">
                            <label ng-for="var.name" class="col-form-label mr-1" ng-bind="var.name"></label>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color"
                                       ng-class="{'border border-warning': var.value && var.value != var.default}"
                                       ng-id="var.name" ng-model="var.value" ng-value="var.value || var.default">
                                <button class="btn btn-outline-warning" title="{{var.default}}"
                                        ng-if="var.value && var.value != var.default" ng-click="var.value = ''">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="col-12 my-3">
                        <div class="float-right">
                            <button ng-click="resetScssFontsizes()" class="btn btn-sm btn-outline-danger">Alle Schriftgrößen zurücksetzen</button>
                        </div>
                        <h4>Schriftgrößen</h4>
                    </div>
                    <div ng-repeat="var in scssFontsizes" class="col-12 col-md-6 row g-1">
                        <div class="col-6 col-md-4 col-xxl-3 text-right">
                            <label ng-for="var.name" ng-bind="var.name" class="col-form-label"></label>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="{{var.default}}" ng-id="var.name" ng-model="var.value"
                                       ng-class="{'border border-warning': var.value && var.value != var.default}">
                                <button class="btn btn-outline-warning" title="{{var.default}}"
                                        ng-if="var.value && var.value != var.default" ng-click="var.value = ''">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    [{* custom scss *}]
    <div class="card">
        <div class="card-header" id="custmScssHeading">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#custmScssCollapse" aria-expanded="false" aria-controls="custmScssCollapse">
                    <h3>Custom SCSS</h3>
                </button>
            </h2>
        </div>

        <div id="custmScssCollapse" class="collapse" aria-labelledby="custmScssHeading" data-parent="#customizerAccordion">
            <div class="card-body">
                <div id="editor">[{$oView->getCustomScss()}]</div>
            </div>
        </div>
    </div>

</div>

<div class="row my-3" ng-if="response && response.msg.length > 0">
    <div class="col">
        <div class="alert" ng-class="'alert-'+response.status" ng-bind-html="response.msg | html"></div>
    </div>
</div>

<div class="row my-3">
    <div class="col-4">
        <button class="btn btn-primary btn-block" ng-click="preview();">
            <span ng-if="loading.preview > 0" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> preview
        </button>
    </div>
    <div class="col-4">
        <button class="btn btn-success btn-block" ng-click="save();">
            <span ng-if="loading.save > 0" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> save
        </button>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.0/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.min.js"></script>
<script>
    var app = angular.module('app', []);
    app.filter("html", ['$sce', function ($sce)
    {
        return function (htmlCode)
        {
            return $sce.trustAsHtml(htmlCode);
        }
    }]);
    app.controller('ctrl', function ($scope, $http)
    {

        $scope.loading = {
            'preview': 0,
            'save': 0
        };

        $scope.scssColors = [];
        $scope.getScssColors = function() {
            oxGet('getScssColors', {}).then(
                function success(response) { $scope.scssColors = response.data; },
                function error(response) { $scope.response = response.data; });
        };
        $scope.resetScssColors = function() {
            oxGet('resetScssColors', {}).then(
                function success(response) { $scope.scssColors = response.data; },
                function error(response) { $scope.response = response.data; });
        };

        $scope.scssFontsizes = [];
        $scope.getScssFontsizes = function() {
            oxGet('getScssFontsizes', {}).then(
                function success(response) { $scope.scssFontsizes = response.data; },
                function error(response) { $scope.response = response.data; });
        };
        $scope.resetScssFontsizes = function() {
            oxGet('resetScssFontsizes', {}).then(
                function success(response) { $scope.scssFontsizes = response.data; },
                function error(response) { $scope.response = response.data; });
        };

        ace.config.set('basePath', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/')
        $scope.editor = ace.edit("editor");
        $scope.editor.setTheme("ace/theme/github");
        $scope.editor.session.setMode("ace/mode/sass");

        var oxGet = function ($fnc, $data)
        {
            console.log("sending request to fnc " + $fnc);
            console.log($data);
            return $http({
                method: 'POST',
                url: '[{ $oViewConf->getSelfLink()|replace:"&amp;":"&" }]',
                headers: {
                    "Content-Type": "application/json"
                },
                params: {
                    cl: '[{$oView->getClassName()}]',
                    fnc: $fnc
                },
                data: $data || {}
            });
        };

        $scope.preview = function ()
        {
            $scope.response = {};
            $scope.loading["preview"]++;
            oxGet('preview', {
                colors: $scope.scssColors,
                fontsizes: $scope.scssFontsizes,
                customscss: $scope.editor.getValue()
            }).then(
                function success(response)
                {
                    $scope.loading["preview"]--;
                    console.log("success", response);
                    $scope.response = response.data;


                },
                function error(response)
                {
                    $scope.loading["preview"]--;
                    console.log("error", response);
                    $scope.response = response.data;
                });
        };

        $scope.save = function ()
        {
            $scope.response = {};
            $scope.loading["save"]++;
            oxGet('save', {
                colors: $scope.scssColors,
                fontsizes: $scope.scssFontsizes,
                customscss: $scope.editor.getValue()
            }).then(
                function success(response)
                {
                    $scope.loading["save"]--;
                    console.log("success", response);
                    $scope.response = response.data;


                },
                function error(response)
                {
                    $scope.loading["save"]--;
                    console.log("error", response);
                    $scope.response = response.data;
                });
        };

        // init stuff
        $scope.getScssColors();
        $scope.getScssFontsizes();
    });
</script>
</body>
</html>
