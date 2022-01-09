

//
//  Loop through teammates table
//


// var table = document.getElementById('teammatesTable');
// // add dummy last row to break on the end of table
// var lastRow = table.insertRow(-1);
// var cellTeammateGroup = lastRow.insertCell(0);
// var cellName = lastRow.insertCell(1);
// cellTeammateGroup.innerHTML = -1;
// cellName.innerHTML = 'EOF';
// errMsg='';
// var lastId = 0;
// let colors = ['#d7d7d7', '#ffff'];
// var backgroundColor = 0;
// for (var i = 1, row; row = table.rows[i]; i++) {
//     if (lastId != row.cells[0].innerHTML) {

//         if (i>1) {
//             console.log('lastId='+lastId+'   size='+gradeGroup.size);
//             errMsg='';
//             if (gradeGroup.size == 0) {
//                 errMsg = 'Missing gradeGroup';
//             } else if (gradeGroup.size > 1) {
//                 errMsg = 'Mismatched gradeGroups';
//             }
//             if (requestedTeam.size > 1) {
//                 if (errMsg.length > 0) {
//                     errMsg += "<br>";
//                 }
//                 errMsg += "Conflicting teams";
//             }
//             if (errCoach.length > 0) {
//                 if (errMsg.length > 0) {
//                     errMsg += "<br>";
//                 }
//                 errMsg += errCoach;

//             }
//             if (errMsg.length > 0) {
//                 let newRow0 = table.insertRow(i);
//                 newRow0.innerHTML = '<tr><td >'+lastId+'</td><td style="color:#ff0000">Error</td><td colspan=8 style="color:#ff0000;">'+errMsg+'</td></tr>';
//                 newRow0.bgColor = '#ffeded';
//                 i = i + 1;
//             }
//             //continue;
//             if (row.cells[0].innerHTML == -1) {
//                 break;
//             }
//         }


//         backgroundColor++;;
//         lastId = row.cells[0].innerHTML;
//         gradeGroup = new Set();
//         requestedTeam = new Set();
//         errCoach = '';
//         // Insert a row
//         let newRow = table.insertRow(i);
//         newRow.innerHTML = '<tr><td colspan=8 style="padding:24px 0px 2px 0px;">\
//         <a class="btn btn-primary btn-sm" href="/basic02/web/index.php?r=teammates%2Finsert&amp;teammateGroup='+lastId+'"\
//          title="Add another player or coach to this group">\
//         Insert\
//         </a></td></tr>';
//     }
//     if (errMsg.length == 0) {
//         row.style.backgroundColor = colors[backgroundColor % colors.length];
//     }
//     console.log('<'+row.cells[5].innerHTML+'>');
//     if (['peewee','junior','senior'].indexOf(row.cells[5].innerHTML) > -1) {
//         gradeGroup.add(row.cells[5].innerHTML);
//     }
//     if (row.cells[7].innerHTML != '(not set)') {
//         requestedTeam.add(row.cells[7].innerHTML);

//     }
//     if (row.cells[3].innerHTML == 'coach' && row.cells[6].innerHTML == 'requested') {
//         errCoach = row.cells[1].innerHTML + ' has NOT volunteered';
//     }
// }
// // remove dummay last row
// var rowCount = table.rows.length;
// table.deleteRow(rowCount -1);

// //
// //  Team assignment
// //
// function assign(user_id, teams_id) {
//     var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
//     console.log( user_id, teams_id);
//     $.ajax({
//         url: 'index.php?r=teammates/assign-team',
//         type: 'post',
//         data: {_csrf : csrfToken, user_id : user_id, teams_id: teams_id},
//         async: true,
//         cache: false
//     })
// .done(function (person) {
//     console.log(person);
//     if(person == 'error') {
//         window.location.href ='index.php?r=teammates/index';
//     }
//     })
// .fail(function (x, e) {
//         alert('The call to the server side failed. ' + x.responseText);

// });
// }
