// Teammate Group details ....
const tmGroup = document.querySelectorAll('.tmGroup').forEach(function(item) {
    item.addEventListener('click',(event) => {

    console.log(item.getAttribute('class'));
    tmg=item.innerHTML;
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-title').empty().append("Teammate Group ("+tmg+") Members Details");
    $('#modal').find('.modal-body').load("index.php?r=coaches/teammate-group&tmg="+tmg);
    $('#modal').modal({show:true, backdrop:'static'});
    });
});


document.querySelectorAll('select[name="assignedTeams"]').forEach( (coach) => {
    coach.addEventListener('change', (event) => {
        assignedTeams_id = coach.value;
        id = coach.parentElement.parentElement.getAttribute('data-key');
        //console.log(id, assignedTeams_id);
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
