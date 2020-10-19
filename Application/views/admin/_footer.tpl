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
        $scope.loading = {};

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

        [{$smarty.capture.ng}]
    });
</script>
</body>
</html>