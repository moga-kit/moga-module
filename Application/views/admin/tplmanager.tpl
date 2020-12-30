<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lumx/1.9.11/lumx.min.css"/>
[{include file=$oViewConf->getModulePath("tpl-manager","Application/views/admin/_head.tpl")}]
<div flex-container="row" flex-align="center center" ng-repeat="(_configurable, _templates) in configurables">
    <div flex-item="3" class="right">
        <p><b ng-bind="_configurable"></b></p>
    </div>
    <div flex-item="5">
        <lx-select ng-model="vm.selectModel.selectedPerson"
                   lx-allow-clear="false"
                   lx-choices="_templates">
            <lx-select-selected>{{ $selected }}</lx-select-selected>
            <lx-select-choices>{{ $choice }}</lx-select-choices>
        </lx-select>

    </div>
</div>
<table width="100%">
    <tr>
        <td>
            <ul>
                [{foreach from=$oView->getTemplates() key="dir" item="entries"}]
                    <li>[{$dir}]</li>
                [{/foreach}]
            </ul>


        </td>
        <td>
            <pre>[{$oView->getTemplateOptions("layout/header/*")|@var_dump}]</pre>
        </td>
    </tr>
</table>
<button ng-click="testoverride()">test override</button>
<pre ng-bind="configurables|json">
</pre>
<script>
    [{capture name="ng"}]
    $scope.configurables = [{$oView->getConfigurableTemplates()|@json_encode}];
    $scope.overrides = {};
    $scope.testoverride = function ()
    {
        $scope.response = {};
        oxGet('setDynamicOverrides').then(
            function success(response)
            {
                console.log("success", response);
            },
            function error(response)
            {
                console.log("error", response);
            });
    };
    [{/capture}]
</script>
[{include file=$oViewConf->getModulePath("tpl-manager","Application/views/admin/_footer.tpl")}]