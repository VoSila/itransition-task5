document.getElementById('exportButton').addEventListener('click', function () {
  exportTableToExcel('responseContainer', 'generate_users');
});

function exportTableToExcel(tableID, filename = ''){
  var wb = XLSX.utils.table_to_book(document.getElementById(tableID));
  XLSX.writeFile(wb, filename + '.xlsx');
}
