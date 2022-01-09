


var table = document.getElementById('teamByGrade');

noRows = table.rows.length;
noCols = table.rows[0].cells.length;
let newRow1 = table.insertRow(noRows-1);
newRow1.innerHTML = '<tr><td colspan='+noCols+' style="padding:24px 0px 2px 0px;"></td></tr>'; 
