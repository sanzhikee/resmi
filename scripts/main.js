var app = angular.module('app', [])
    .controller('phonebookCtrl', function ($scope, $http) {
            $scope.currentPersonId = -1;
            $scope.currentPersonIndex = -1;

            $scope.phonebook = [];
            $scope.config = {
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8'
                }
            };

            $http.get('/api/phones').then(function (res) {
                if (res.data.success == true) {
                    $scope.phonebook = res.data.items;
                }
            });

            $scope.addNewPerson = function () {
                if ($scope.name != '') {
                    var data = {
                        'name': $scope.name,
                        'phone': $scope.phone
                    };

                    $http.post('/api/phones', data, $scope.config).then(function (res) {
                        if (res.data.name != '') {
                            $scope.phonebook.push({
                                name: res.data.name,
                                phone: res.data.phone,
                            });
                            $scope.name = '';
                            $scope.phone = '';
                        }
                    });
                }
            }

            $scope.savePerson = function () {
                if ($scope.currentPersonId > -1) {
                    var id = $scope.currentPersonId;
                    var index = $scope.currentPersonIndex;

                    var data = {
                        'name': $scope.name,
                        'phone': $scope.phone
                    };

                    $http.put('/api/phones/'+id, data, $scope.config).then(function (res) {
                        if (res.data.name != '') {
                            $scope.phonebook[index].name = $scope.name;
                            $scope.phonebook[index].phone = $scope.phone;

                            $scope.name = '';
                            $scope.phone = '';
                        }
                    });

                    $scope.currentPersonId = -1;
                    $scope.currentPersonIndex = -1;
                }
            }
            $scope.editPerson = function (id, index) {
                $scope.currentPersonId = id;
                $scope.currentPersonIndex = index;
                $scope.name = $scope.phonebook[index].name;
                $scope.phone = $scope.phonebook[index].phone;
                $scope.email = $scope.phonebook[index].email;
            }
            $scope.deletePerson = function (id, index) {
                $http.delete('/api/phones/'+id).then(function (res) {
                    $scope.phonebook.splice(index, 1);
                });
            }
        }
    );