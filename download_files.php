<?php
include 'header.html'; 
include 'menu.html'; 
include 'color_sequence.php';
?>

<!DOCTYPE html>
<html ng-app="app">

<br> 
 <style>
      .table-downloads .checkbox input[type=checkbox]{
    margin-left:0;
}
    </style>
  </head>
  <body ng-cloack="">
    <div class="container" ng-controller="FirstCtrl">
      <table class="table table-bordered table-downloads">
        <thead>
          <tr>
             <th>Select</th>
            <th>Format</th>
            <th>File names</th>
            <th>Description</th>
            <th>Downloads</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="tableData in tableDatas">
            <td>
              <div class="checkbox">
                <input type="checkbox" name="{{tableData.name}}" id="{{tableData.name}}" value="{{tableData.name}}" ng-model="tableData.checked" ng-change="selected()" />
              </div>
            </td>
            <td>{{tableData.format}}</td>
            <td>{{tableData.fileName}}</td>
            <td>{{tableData.desc}}</td>
            <td>
              <a target="_self" id="download-{{tableData.name}}" ng-href="{{tableData.filePath}}" class="btn btn-success pull-right downloadable" download="">download</a>
            </td>
          </tr>
        </tbody>
      </table>
      <a class="btn btn-success pull-right" ng-click="downloadAll()">download selected</a>
      <p>{{selectedone}}</p>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="style_download_page.js"></script>
  </body>

</html>

<?php
mysql_close($dbhandle);
include 'footer.html';
?>  


