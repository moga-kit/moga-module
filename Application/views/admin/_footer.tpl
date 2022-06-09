[{if 1 < 2}]
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
[{else}]
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    [{* <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/2.0.6/velocity.min.js"></script> *}]
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lumx/1.9.11/lumx.js"></script>
[{/if}]
<script src="https://cdn.jsdelivr.net/npm/angular@1.8.2/angular.min.js"></script>
[{oxscript}]
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
            "save":0,
            "preview":0
        };

        var oxGet = function ($fnc, $data)
        {
            //console.log("sending request to fnc " + $fnc);
            return $http({
                method: 'POST',
                url: '[{ $oViewConf->getSelfLink()|replace:"&amp;":"&" }]',
                headers: { "Content-Type": "application/json" },
                params: {
                    cl: '[{$oView->getClassName()}]',
                    fnc: $fnc
                },
                data: $data || {}
            });
        };

        [{$smarty.capture.ng}]
    });
</script>
</body>
</html>