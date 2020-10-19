[{include file=$oViewConf->getModulePath("tpl-manager","Application/views/admin/_head.tpl")}]
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
<pre>
    [{$oView->getDynamicOverrides()|@var_dump}]
</pre>
<script>
    [{capture name="ng"}]
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