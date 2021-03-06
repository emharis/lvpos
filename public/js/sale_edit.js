(function(){
    var app = angular.module('tutapos', [ ]);

    app.controller("SearchItemCtrl", [ '$scope', '$http', function($scope, $http) {
        $scope.items = [ ];
        $http.get('../../api/item').success(function(data) {
            $scope.items = data;
        });
        $scope.saletemp = [ ];
        $scope.newsaletemp = { };
        
        $scope.shipping_price = document.getElementById('shipping_price').value;
        $scope.parseFloat = function(value)
        {   

            return parseFloat(value);
        }
         $http.get('../../api/saletemp').success(function(data, status, headers, config) {
            $scope.saletemp = data;
        });

        $http.get('../../api/saletemp').success(function(data, status, headers, config) {
            $scope.saletemp = data;
        });

        $scope.addSaleTemp = function(item, newsaletemp) {
            $http.post('../../api/saletemp', { item_id: item.id, cost_price: item.cost_price, selling_price: item.selling_price, price_ref: item.price_ref }).
            success(function(data, status, headers, config) {
                $scope.saletemp.push(data);
                    $http.get('../../api/saletemp').success(function(data) {
                    $scope.saletemp = data;
                    });
            });
        }
        $scope.updateSaleTemp = function(newsaletemp) {
            
            $http.put('../../api/saletemp/' + newsaletemp.id, { quantity: newsaletemp.quantity, selling_price: newsaletemp.selling_price, total_cost: newsaletemp.item.cost_price * newsaletemp.quantity,
                total_selling: newsaletemp.selling_price * newsaletemp.quantity }).
            success(function(data, status, headers, config) {
                
                });
        }
        $scope.removeSaleTemp = function(id) {
            $http.delete('../../api/saletemp/' + id).
            success(function(data, status, headers, config) {
                $http.get('../../api/saletemp').success(function(data) {
                        $scope.saletemp = data;
                        });
                });
        }
        $scope.sum = function(list) {
            var total=0;
          
            angular.forEach(list , function(newsaletemp){
                total+= parseFloat(newsaletemp.selling_price * newsaletemp.quantity);
            });
           
            return total;

            
        }

    }]);
})();