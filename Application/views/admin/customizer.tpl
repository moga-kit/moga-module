<!doctype html>
<html lang="de" ng-app="app">
<head>
    <title>MOGA Customizer</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/out/moga/src/css/styles.min.css">
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('moga','out/customizer.css')}]">
    [{if 1 > 2}]
        <link rel="stylesheet" href="../../../../../out/moga/src/css/styles.min.css">
        <link rel="stylesheet" href="../../../out/customizer.css">
    [{/if}]
</head>
<body ng-controller="ctrl" class="p-3">

<div class="accordion" id="accordion__customizer">

    [{capture name="colorschema"}]
        <div class="row">
            <div class="col-12 col-md-6 col-xl-4 " ng-repeat="var in accordion.colorschema">
                <div class="row gx-1 py-1">
                    <label class="col-4 col-form-label text-end" ng-for="'colorschema'+var" ng-bind="var+':'"></label>
                    <div class="col-4"  [{**ng-class="{'border border-danger': scssVariables[var].value && scssVariables[var].value != scssVariables[var].default}"*}]>
                        <input type="text" class="form-control text-end ps-2 oe-1" ng-id="'colorschema'+var"
                               ng-model="scssVariables[var].value" ng-value="scssVariables[var].value || scssVariables[var].default">
                        <div ng-if="(scssVariables[var].value || scssVariables[var].default).indexOf('$') === 0 && (scssVariables[var].value || scssVariables[var].default) != resolveScssVariable(var)" class="form-text text-end px-1 my-0" ng-bind="resolveScssVariable(var)"></div>
                    </div>
                    <div class="col-2 col-xl-1">
                        <input type="color" ng-id="var.name" class="form-control form-control-color m-auto me-0"
                               ng-model="scssVariables[var].value" ng-value="resolveScssVariable(var)">
                    </div>
                        <div class="col-2 col-xl-3">
                        <button type="button" class="btn btn-danger" ng-if="scssVariables[var].value && scssVariables[var].value !== scssVariables[var].default" title="reset to default: {{scssVariables[var].default}}" ng-click="scssVariables[var].value = null"><i class="moga-times"></i></button>
                    </div>
                </div>
            </div>
        </div>
    [{/capture}]
    [{defun name="accordion_item" accordion="customizer" id="colorschema" header="Farbschema" content=$smarty.capture.colorschema show=true}]
        <div class="accordion-item">
            <h2 class="accordion-header" id="accordion__[{$accordion}]__heading__[{$id}]">
                <button class="accordion-button [{if !$show}]collapsed[{/if}]" type="button"
                        data-bs-toggle="collapse" data-bs-target="#accordion__[{$accordion}]__collapse__[{$id}]"
                        aria-expanded="true"
                        aria-controls="accordion__[{$accordion}]__collapse__[{$id}]">[{$header}]</button>
            </h2>
            <div id="accordion__[{$accordion}]__collapse__[{$id}]"
                 class="accordion-collapse collapse [{if $show}]show[{/if}]"
                 aria-labelledby="accordion__[{$accordion}]__heading__[{$id}]"
                 data-bs-parent="#accordion__[{$accordion}]">
                <div class="accordion-body">[{$content}]</div>
            </div>
        </div>
    [{/defun}]


    [{capture name="componentcolors"}]
        <div class="row">
            <div class="col-12 col-lg-6" ng-repeat="var in accordion.componentcolors">
                <div class="row gx-1 py-1">
                    <label class="col-4 col-form-label text-end" ng-for="'componentcolors'+var" ng-bind="var+':'"></label>
                    <div class="col-4"  [{**ng-class="{'border border-danger': scssVariables[var].value && scssVariables[var].value != scssVariables[var].default}"*}]>
                        <input type="text" class="form-control text-end ps-2 oe-1" ng-id="'componentcolors'+var"
                               ng-model="scssVariables[var].value" ng-value="scssVariables[var].value || scssVariables[var].default">
                        <div ng-if="(scssVariables[var].value || scssVariables[var].default).indexOf('$') === 0 && (scssVariables[var].value || scssVariables[var].default) != resolveScssVariable(var)" class="form-text text-end px-1 my-0" ng-bind="resolveScssVariable(var)"></div>
                    </div>
                    <div class="col-2 col-xl-1">
                        <input type="color" ng-id="var.name" class="form-control form-control-color m-auto me-0"
                               ng-model="scssVariables[var].value" ng-value="resolveScssVariable(var)">
                    </div>
                    <div class="col-2 col-xl-3">
                        <button type="button" class="btn btn-danger" ng-if="scssVariables[var].value && scssVariables[var].value !== scssVariables[var].default" title="reset to default: {{scssVariables[var].default}}" ng-click="scssVariables[var].value = null"><i class="moga-times"></i></button>
                    </div>
                </div>
            </div>
        </div>
    [{/capture}]
    [{fun name="accordion_item" accordion="customizer" id="componentcolors" header="Layout Farben" content=$smarty.capture.componentcolors}]


    [{capture name="fonts"}]
        <div class="row">
            <div class="col-12 col-xxl-6 " ng-repeat="var in accordion.fontstyle">
                <div class="row gx-1 py-1">
                    <label class="col-4 col-form-label text-end" ng-for="'fontstyle'+var" ng-bind="var+':'"></label>
                    <div class="col-6" [{*ng-class="{'border border-danger': scssVariables[var].value && scssVariables[var].value != scssVariables[var].default}"*}]>
                        <input type="text" class="form-control text-end ps-2 oe-1" ng-id="'fontstyle'+var"
                               ng-model="scssVariables[var].value" ng-value="scssVariables[var].value || scssVariables[var].default">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger" ng-if="scssVariables[var].value && scssVariables[var].value !== scssVariables[var].default" title="reset to default: {{scssVariables[var].default}}" ng-click="scssVariables[var].value = null"><i class="moga-times"></i></button>
                    </div>
                </div>
            </div>
        </div>
    [{/capture}]
    [{fun name="accordion_item" accordion="customizer" id="fonts" header="Schrift" content=$smarty.capture.fonts}]

    [{capture name="variables"}]
        <div class="row">
            <div class="col-12 col-lg-6" ng-repeat="var in scssVariables">
                <div class="row">
                    <label class="col-4 col-form-label text-end" ng-for="var.name" ng-bind="var.name+':'"></label>
                    <div class="col-6" [{*ng-class="{'border border-danger': var.value && var.value != var.default}"*}]>
                        <input type="text" class="form-control text-end ps-2 oe-1" ng-id="var.name"
                               ng-model="var.value" ng-value="var.value || var.default">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger" ng-if="var.value && var.value !== var.default" title="reset to default: {{var.default}}" ng-click="var.value = null"><i class="moga-times"></i></button>
                    </div>
                </div>
            </div>
        </div>
    [{/capture}]
    [{fun name="accordion_item" accordion="customizer" id="variables" header="SCSS Variablen" content=$smarty.capture.variables}]

    [{capture name="editor"}]
        <div id="editor">[{$oView->getCustomStyles()}]</div>
    [{/capture}]
    [{fun name="accordion_item" accordion="customizer" id="editor" header="SCSS Code" content=$smarty.capture.editor}]
</div>
<div class="container">
    <div class="row">
        <div class="col-12 pt-3" ng-repeat="m in messages track by $index">
            <div class="alert alert-{{m.type}}" role="alert" ng-bind-html="m.txt|html"></div>
        </div>
    </div>
    <div class="row row-cols-2 pt-3">
        <button type="button" class="btn col btn-success" ng-click="preview();">preview</button>
        <button type="button" class="btn col btn-warning" ng-click="live();">live</button>
    </div>
</div>
<pre ng-bind="scss|json"></pre>
<!--
<div flex-column="column">
    <lx-tabs lx-active-tab="vm.activeTab">
        <lx-tab lx-label="">

            <lx-button lx-type="raised" lx-color="red" flex-item="3" ng-click="resetScssColors()">
                Alles zurücksetzen
            </lx-button>
        </lx-tab>

        <lx-tab lx-label="Schriftgrößen">
            </div>
            <lx-button lx-type="raised" lx-color="red" flex-item="3" ng-click="resetScssFontsizes()">
                Alles zurücksetzen
            </lx-button>
        </lx-tab>

        <lx-tab lx-label="Alle Bootstrap Variablen">
            asdadasd
        </lx-tab>
    </lx-tabs>


    <div class="px+ mv++" ng-if="response && response.msg.length > 0">
        <div class="card p+" ng-class="'bgc-'+response.status+'-200'" ng-bind-html="response.msg | html"></div>
    </div>
    <lx-progress lx-type="linear" lx-color="teal" ng-if="(loading.preview + loading.live) > 0"></lx-progress>
    <div flex-container="row">
        <lx-button flex-item lx-type="raised" lx-color="yellow" ng-click="preview();"><b>preview</b></lx-button>
        <lx-button flex-item lx-type="raised" lx-color="light-blue" ng-click="live();"><b>live</b></lx-button>
    </div>
</div>
-->

<script src="[{$oViewConf->getModuleUrl('moga','out/angular.min.js')}]"></script>
<script src="[{$oViewConf->getModuleUrl('moga','out/ngStorage.min.js')}]"></script>
<script src="[{$oViewConf->getModuleUrl('moga','out/bootstrap.bundle.min.js')}]"></script>
<script src="[{$oViewConf->getModuleUrl('moga','out/ace/ace.js')}]"></script>
[{if 1 > 2}]
    <script src="../../../out/angular.min.js"></script>
    <script src="../../../out/ngStorage.min.js"></script>
    <script src="../../../out/bootstrap.bundle.min.js"></script>
    <script src="../../../out/ace/ace.js"></script>
[{/if}]
<script>
    angular.module('app', ['ngStorage'])
        .filter("html", ['$sce', function ($sce) {
            return function (htmlCode) {
                return $sce.trustAsHtml(htmlCode);
            }
        }])
        .controller('ctrl', function ($scope, $http, $localStorage, $sessionStorage) {
            $scope.messages = [{$aMessages|@json_encode}];
            $scope.loading = {
                "save": 0,
                "preview": 0
            };

            var oxPost = function ($fnc, $data) {
                //console.log("sending request to fnc " + $fnc);
                return $http({
                    method: 'POST',
                    url: '[{ $oViewConf->getSelfLink()|replace:"&amp;":"&" }]',
                    headers: {"Content-Type": "application/json"},
                    params: {
                        cl: '[{$oView->getClassName()}]',
                        fnc: $fnc
                    },
                    data: $data || {}
                });
            };

            $scope.accordion = {
                'colorschema': ["primary","secondary","success","danger","warning","info","white",
                    "gray-100","gray-200","gray-300","gray-400","gray-500","gray-600","gray-700","gray-800","gray-900",
                    "black","body-bg","body-color","link-color","link-hover-color"],
                'componentcolors': ["ox-header-bg","ox-header-color","ox-header-link-color",
                    "ox-footer-bg","ox-footer-color","ox-footer-link-color",
                    "navbar-default-bg","navbar-default-color",
                    "navbar-default-link-active-bg","navbar-default-link-active-color",
                    "navbar-default-link-hover-bg","navbar-default-link-hover-color",
                    "ox-checkout-steps-active-bg","ox-checkout-steps-active-color",
                    "ox-checkout-steps-passed-bg","ox-checkout-steps-passed-color",
                    "ox-checkout-steps-bg","ox-checkout-steps-color"],
                'fontstyle': ["font-family-base","font-size-base","line-height-base","headings-font-family","headings-font-weight",
                    "h1-font-size","h2-font-size","h3-font-size","h4-font-size","h5-font-size","h6-font-size"]
            };

            $scope.scss = $localStorage;
            $scope.scssVariables = [{$oView->getScssVariables()|@json_encode}];

            $scope.deleteUsaved = function (varname) {
                delete $scope.scss[varname];
            };

            $scope.resolveScssVariable = function (varname) {
                var $var = $scope.scssVariables[varname];
                //console.log(varname);
                //console.log($var);
                var $val = $var.value || $var.default;
                if ($val.indexOf('$') === 0) return $scope.resolveScssVariable($val.replace('$',''));

                if ($val.indexOf('#') === 0 && $val.length === 4) {
                    $val = '#' + $val.replace('#','').split('').map(function (hex) {
                        return hex + hex;
                    }).join('');
                }

                return $val;
            };

            ace.config.set('basePath', '[{$oViewConf->getModuleUrl('moga','out/ace/')}]')
            $scope.editor = ace.edit("editor");
            $scope.editor.setTheme("ace/theme/github");
            $scope.editor.session.setMode("ace/mode/sass");

            $scope.preview = function () {
                $scope.messages = [];
                $scope.loading["preview"]++;
                var combinedScssVariables = $scope.scssVariables;
                /*console.log(Object.keys($scope.scss).length);
                if(Object.keys($scope.scss).length > 0) for (key in Object.keys($scope.scss)) {
                    if($scope.scss[key]) {
                        console.log("setting "+key+" to "+$scope.scss[key]);
                        combinedScssVariables[key].value = $scope.scss[key];
                    }
                }*/
                oxPost('preview', {
                    scssVariables: combinedScssVariables,
                    customstyles: $scope.editor.getValue()
                }).then(
                    function success(response) {
                        $scope.loading["preview"]--;
                        console.log("success", response);
                        $scope.messages = response.data;
                    },
                    function error(response) {
                        $scope.loading["preview"]--;
                        console.log("error", response);
                        //$scope.response = response.data;
                    });
            };
            $scope.live = function () {
                $scope.messages = [];
                $scope.loading["live"]++;
                oxPost('live', {
                    scssVariables: $scope.scssVariables,
                    customsstyles: $scope.editor.getValue()
                }).then(
                    function success(response) {
                        $scope.loading["live"]--;
                        console.log("success", response);
                        $scope.messages = response.data;


                    },
                    function error(response) {
                        $scope.loading["live"]--;
                        console.log("error", response);
                        $scope.messages = response.data;
                    });
            };
        });
</script>
</body>
</html>