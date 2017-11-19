$(document).ready( function () {
  var table = $('#example').DataTable({
	dom: 'Blfrtip',
	buttons: ['csv', 'copy'],
    colReorder: true,
    select: true
});
 
} );

