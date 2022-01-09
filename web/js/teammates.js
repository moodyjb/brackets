//
// Add blank row between group numbers
//

var table = document.getElementsByTagName('tbody')[0];

lastGroup = -999;
for (var i = 0, row; row = table.rows[i]; i++) {
    if (lastGroup != row.cells[0].textContent) {
        lastGroup = row.cells[0].textContent
        if (i > 0) {
            let newRow = table.insertRow(i);
            newRow.outerHTML = "<tr style='background-color: #deedf7;'><td colspan=9>&nbsp;</td></tr>";
        }
    }
    console.log(row.cells[0].textContent);
}
//
// Assign specific team to a player or coach
//
document.querySelectorAll('select[name="assignedTeams"]').forEach( (user) => {
    user.addEventListener('change', (event) => {
        assignedTeams_id = user.value;
        id = user.parentElement.parentElement.getAttribute('data-key');
        console.log(id, assignedTeams_id);
        $.ajax({
                url: assignCoach,
                type: 'post',
                data: {id:id, assignedTeams_id:assignedTeams_id}
            })
            .done(function(response) {
                if (!response) {
                    alert("Error ... could not assigned this team to this coach");
                }
            })
            .fail(function() {
                console.log("error");
            });
    });
});
