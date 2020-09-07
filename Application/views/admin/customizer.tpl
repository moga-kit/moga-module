<!doctype html>
<html lang="en" ng-app="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="[{$oViewConf->getResourceUrl()}]main.css">
    <link rel="stylesheet" href="[{$oViewConf->getResourceUrl()}]colors_[{$oViewConf->getEdition()|lower}].css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.0/angular-csp.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css"/>
    <style type="text/css" media="screen">
        #editor {
            height: 500px;
        }
    </style>
    <title>MOGA Customizer</title>
</head>
<body ng-controller="ctrl" class="p-4">

[{include file="inc_error.tpl" Errorlist=$Errors.default}]

<form action="[{$oViewConf->getSelfLink()}]" class="row">
    [{$oViewConf->getHiddenSid()}]

    <h2>Farbschema</h2>
    <input type="hidden" name="cl" value="mogacustomizer">
    <div ng-repeat="var in bsColors" class="col-6 row">
        <div class="col-2"><input type="color" class="form-control form-control-color" ng-id="var.name" ng-model="var.value" ng-value="var.value || var.default"></div>
        <div class="col-auto"><label ng-for="var.name" class="form-label" ng-bind="var.name"></label></div>
    </div>
    <div class="row my-3">
        <div class="col-4">
            <button type="submit" name="fnc" value="preview" class="btn btn-primary btn-block">preview</button>
        </div>
        <div class="col-4">
            <button type="submit" name="fnc" value="save" class="btn btn-success btn-block">save</button>
        </div>
    </div>
</form>

<h2>Custom SCSS Code</h2>
<div id="editor">[{$oView->getCustomScss()}]</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.0/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.min.js"></script>
<script>
    var app = angular.module('app', []);
    app.controller('ctrl', function ($scope, $http)
    {
        $scope.bsColors = [
            {
                'name': 'primary',
                'value': '',
                'default': '#0d6efd'
            }, {
                'name': 'secondary',
                'value': '',
                'default': '#6c757d'
            }, {
                'name': 'success',
                'value': '',
                'default': '#28a745'
            }, {
                'name': 'info',
                'value': '',
                'default': '#17a2b8'
            }, {
                'name': 'warning',
                'value': '',
                'default': '#ffc107'
            }, {
                'name': 'danger',
                'value': '',
                'default': '#dc3545'
            }, {
                'name': 'light',
                'value': '',
                'default': '#f8f9fa'
            }, {
                'name': 'dark',
                'value': '',
                'default': '#343a40'
            },
        ];
        $scope.customVariables = {};

        ace.config.set('basePath', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/')
        $scope.editor = ace.edit("editor");
        $scope.editor.setTheme("ace/theme/github");
        $scope.editor.session.setMode("ace/mode/sass");

    });

</script>
</body>
</html>
