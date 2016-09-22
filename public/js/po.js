(function(){
    var app = angular.module('tutapos', [ ]);

    app.controller("SearchItemCtrl", [ '$scope', '$http', function($scope, $http) {
        $scope.items = [ ];
        $http.get('../api/item').success(function(data) {
            $scope.items = data;
        });
        $scope.potemp = [ ];
        $scope.newpotemp = { };
        $http.get('../api/potemp').success(function(data, status, headers, config) {
            $scope.potemp = data;
        });
        $scope.addPoTemp = function(item, newpotemp) {
            $http.post('../api/potemp', { item_id: item.id, cost_price: item.cost_price, selling_price: item.selling_price }).
            success(function(data, status, headers, config) {
                $scope.potemp.push(data);
                    $http.get('../api/potemp').success(function(data) {
                    $scope.potemp = data;
                    });
            });
        }
        $scope.updatePoTemp = function(newpotemp) {
            
            $http.put('../api/potemp/' + newpotemp.id, { quantity: newpotemp.quantity, total_cost: newpotemp.cost_price * newpotemp.quantity,
                total_selling: newpotemp.item.selling_price * newpotemp.quantity, cost_price:  newpotemp.cost_price}).
            success(function(data, status, headers, config) {
                
                });
        }
        $scope.removePoTemp = function(id) {
            $http.delete('../api/potemp/' + id).
            success(function(data, status, headers, config) {
                $http.get('../api/potemp').success(function(data) {
                        $scope.potemp = data;
                        });
                });
        }
        $scope.sum = function(list) {
            var total=0;
            angular.forEach(list , function(newpotemp){
                total+= parseFloat(newpotemp.cost_price * newpotemp.quantity);
            });
            return total;
        }

    }]);
})();