<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Phone Book</title>
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.css"/>
    <link rel="stylesheet" href="/styles/main.css"/>
    <script src="/bower_components/angular/angular.js"></script>
    <script src="/scripts/main.js"></script>
</head>
<body>

<div ng-app="app" ng-controller="phonebookCtrl">

    <form>
        <div class="form-group">
            <label>ФИО</label>
            <input type="text" ng-model="name" class="form-control"/>
        </div>
        <div class="form-group">
            <label>№ телефона</label>
            <input type="text" ng-model="phone" class="form-control"/>
        </div>
        <div>
            <button class="btn btn-primary" ng-click="addNewPerson()" ng-hide="currentPersonId > -1">Добавить</button>
            <button class="btn btn-success" ng-click="savePerson()" ng-show="currentPersonId > -1">Сохранить</button>
        </div>
    </form>

    <table id="phonebook" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>№</th>
            <th>ФИО</th>
            <th>№ телефона</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="person in phonebook">
            <td>{{ $index + 1 }}</td>
            <td>{{ person.name }}</td>
            <td>{{ person.phone }}</td>
            <td>
                <button class="btn btn-warning" ng-click="editPerson(person.id, $index)">Редактировать</button>
                <button class="btn btn-delete" ng-click="deletePerson(person.id, $index)">Удалить</button>
            </td>
        </tr>
        </tbody>
    </table>

</div>
</body>
</html>
