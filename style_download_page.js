var app = angular.module('app', []);            
app.controller('FirstCtrl', ['$scope','$http', '$filter', function($scope, $http, $filter){
$scope.tableDatas = [
    {name: 'value1', format:'FASTA', fileName:'SIPs_peptides.fasta',desc:'Stress-induced peptides (SIPs)', filePath: 'blastdb/SIPs_peptides.fasta', selected: true},
    {name: 'value2', format:'FASTA', fileName:'SIPs_nucl.fasta',desc:'Transcriptionally active regions (TARs)', filePath: 'blastdb/SIPs_nucl.fasta', selected: true},
    {name: 'value3', format:'FASTA', fileName:'sORFs_peptides.fasta',desc:'sORFs; peptide sequences by Hanada et al, 2007,2013', filePath: 'blastdb/sORFs_peptides.fasta', selected: true},
    {name: 'value4', format:'FASTA', fileName:'sORFs_nucl.fasta',desc:'sORFs; nucleotide sequences by Hanada et al., 2007,2013', filePath: 'blastdb/sORFs_nucl.fasta', selected: true},
    {name: 'value5', format:'FASTA', fileName:'LW_peptides.fasta',desc:'SecretedPeptides; peptide sequences by Lease and Walker, 2006', filePath: 'blastdb/LW_peptides.fasta', selected: true},
    {name: 'value6', format:'FASTA', fileName:'LW_nucl.fasta',desc:'SecretedPeptides; nucleotide sequences by Lease and Walker, 2006', filePath: 'blastdb/LW_nucl.fasta', selected: true},
    {name: 'value7', format:'BED', fileName:'ARA-PEPs.tar.gz',desc:'ARA-PEPs full peptide co-ordinates', filePath: 'blastdb/ARA-PEPs.tar.gz', selected: true},
  ];   
$scope.application = [];   

$scope.selected = function() {
    $scope.application = $filter('filter')($scope.tableDatas, {
      checked: true
    });
}
$scope.downloadAll = function(){
    //$scope.selectedone = [];     
    angular.forEach($scope.application,function(val){
       //$scope.selectedone.push(val.name);
       $scope.id = val.name;        
       angular.element('#'+val.name).closest('tr').find('.downloadable')[0].click();
    });
}         


}]);



