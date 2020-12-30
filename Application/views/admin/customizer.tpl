[{include file=$oViewConf->getModulePath("moga","Application/views/admin/_head.tpl")}]
[{if 1 > 2}]
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lumx/1.9.11/lumx.min.css"/>
[{/if}]
<div flex-column="column">

    <div class="ph+ mv++">
        <div flex-container="row" flex-align="justify-content">
            <h1 flex-item="">Farben</h1>
            <lx-button lx-type="raised" lx-color="red" flex-item="3" ng-click="resetScssColors()">
                Alles zurücksetzen
            </lx-button>
        </div>
        <div flex-container="row" flex-wrap="wrap" class="pt+">
            <div ng-repeat="var in scssColors" flex-item="4" class="pb fs-headline">

                <input type="color" ng-id="var.name" ng-model="var.value" ng-value="var.value || var.default">

                <label ng-for="var.name" class=" " ng-bind="var.name"></label>

                <lx-button lx-type="icon" lx-color="red" ng-title="var.default"
                           ng-if="var.value && var.value != var.default" ng-click="var.value = ''">
                    <i class="mdi mdi-undo"></i>
                </lx-button>
            </div>
        </div>
    </div>

    <hr/>

    <div class="ph+ mv++">
        <div flex-container="row" flex-align="justify-content">
            <h1 flex-item="">Schriftgrößen</h1>
            <lx-button lx-type="raised" lx-color="red" flex-item="3" ng-click="resetScssFontsizes()">
                Alles zurücksetzen
            </lx-button>
        </div>
        <div flex-container="row" flex-wrap="wrap" class="pt+">
            <div ng-repeat="var in scssFontsizes" flex-item="6" class="pb fs-headline">

                <input type="text" placeholder="{{var.default}}" ng-id="var.name"
                       ng-model="var.value" ng-class="{'border border-warning': var.value && var.value != var.default}">

                <label ng-for="var.name" class=" " ng-bind="var.name"></label>

                <lx-button lx-type="icon" lx-color="red" ng-title="var.default"
                           ng-if="var.value && var.value != var.default" ng-click="var.value = ''">
                    <i class="mdi mdi-undo"></i>
                </lx-button>
            </div>
        </div>
    </div>
    </form>

    <hr/>

    <div class="px+ mv++">
        <div id="editor">[{$oView->getCustomScss()}]</div>
    </div>

    <div class="px+ mv++" ng-if="response && response.msg.length > 0">
        <div class="card p+" ng-class="'bgc-'+response.status+'-200'" ng-bind-html="response.msg | html"></div>
    </div>
    <lx-progress lx-type="linear" lx-color="teal" ng-if="(loading.preview + loading.save) > 0"></lx-progress>
    <div flex-container="row">
        <lx-button flex-item lx-type="raised" lx-color="yellow" ng-click="preview();"><b>preview</b></lx-button>
        <lx-button flex-item lx-type="raised" lx-color="light-blue" ng-click="save();"><b>save</b></lx-button>
    </div>
</div>
<script>
    [{capture name="ng"}]

    $scope.scssColors = [];
    $scope.getScssColors = function () {
        oxGet('getScssColors', {}).then(
            function success(response) {
                $scope.scssColors = response.data;
            },
            function error(response) {
                $scope.response = response.data;
            });
    };
    $scope.resetScssColors = function () {
        oxGet('resetScssColors', {}).then(
            function success(response) {
                $scope.scssColors = response.data;
            },
            function error(response) {
                $scope.response = response.data;
            });
    };

    $scope.scssFontsizes = [];
    $scope.getScssFontsizes = function () {
        oxGet('getScssFontsizes', {}).then(
            function success(response) {
                $scope.scssFontsizes = response.data;
            },
            function error(response) {
                $scope.response = response.data;
            });
    };
    $scope.resetScssFontsizes = function () {
        oxGet('resetScssFontsizes', {}).then(
            function success(response) {
                $scope.scssFontsizes = response.data;
            },
            function error(response) {
                $scope.response = response.data;
            });
    };

    ace.config.set('basePath', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/')
    $scope.editor = ace.edit("editor");
    $scope.editor.setTheme("ace/theme/github");
    $scope.editor.session.setMode("ace/mode/sass");

    $scope.preview = function () {
        $scope.response = {};
        $scope.loading["preview"]++;
        oxGet('preview', {
            colors: $scope.scssColors,
            fontsizes: $scope.scssFontsizes,
            customscss: $scope.editor.getValue()
        }).then(
            function success(response) {
                $scope.loading["preview"]--;
                console.log("success", response);
                $scope.response = response.data;


            },
            function error(response) {
                $scope.loading["preview"]--;
                console.log("error", response);
                $scope.response = response.data;
            });
    };

    $scope.save = function () {
        $scope.response = {};
        $scope.loading["save"]++;
        oxGet('save', {
            colors: $scope.scssColors,
            fontsizes: $scope.scssFontsizes,
            customscss: $scope.editor.getValue()
        }).then(
            function success(response) {
                $scope.loading["save"]--;
                console.log("success", response);
                $scope.response = response.data;


            },
            function error(response) {
                $scope.loading["save"]--;
                console.log("error", response);
                $scope.response = response.data;
            });
    };

    // init stuff
    $scope.getScssColors();
    $scope.getScssFontsizes();
    [{/capture}]
</script>
[{include file=$oViewConf->getModulePath("moga","Application/views/admin/_footer.tpl")}]