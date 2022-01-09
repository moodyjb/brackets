/*
*   Compare player's to Required Teammate teams for Same or Different
*/
const teamMateTeams_idEement = document.querySelector('#teammatetd-teams_id');
teamMateTeams_idEement.addEventListener('change',(event) => {
    var teamMateTeams_id = teamMateTeams_idEement.options[teamMateTeams_idEement.selectedIndex].value;
    console.log('players '+document.getElementById('playerstd-teams_id').value);
    console.log('teamMate '+teamMateTeams_id);
    message = document.getElementById('compareTeams');
    if (!teamMateTeams_id) {
        message.innerHTML = 'No team yet assigned';
        message.style.color = "#000";
    } else if (teamMateTeams_id ==document.getElementById('playerstd-teams_id').value) {
        message.innerHTML = 'Same team';
        message.style.color = "#0000ff";
    } else {
        message.innerHTML = 'Different team';
        message.style.color = "#ff0000";
    }
});

/*
*   Populate player teams
*/
const playerGradeGroupEement = document.querySelector('#playerstd-gradegroup');
playerGradeGroupEement.addEventListener('change',(event) => {
    var gradeGroup = playerGradeGroupEement.options[playerGradeGroupEement.selectedIndex].value;

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    $.ajax({
        url: 'index.php?r=requests/ajax-teams',
        type: 'post',
        data: {_csrf : csrfToken, gradeGroup: gradeGroup},
        dataType: 'json',
        async: true,
        cache: false
    })
    .done(function (data) {
        // Construct the dropDownList
        var playersTeams = document.getElementById('playerstd-teams_id');
        playersTeams.options.length=0;
        playersTeams.options[playersTeams.options.length] = new Option('', '', false, false);
        Object.entries(data).forEach(([key,val]) => {
            console.log('val key='+val,key);
            playersTeams.options[playersTeams.options.length] = new Option(val, key, true, false);
        });
        playersTeams.value = playerSelected;
    })
    .fail(function (x, e) {
            alert("The call to the server side failed. " + x.responseText);
                    
    });
});
/*
*   Populate teammate teams
*/
const teamMateGradeGroupEement = document.querySelector('#teammatetd-gradegroup');
teamMateGradeGroupEement.addEventListener('change',(event) => {
    var gradeGroup = teamMateGradeGroupEement.options[teamMateGradeGroupEement.selectedIndex].value;

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    $.ajax({
        url: 'index.php?r=requests/ajax-teams',
        type: 'post',
        data: {_csrf : csrfToken, gradeGroup: gradeGroup},
        dataType: 'json',
        async: true,
        cache: false
    })
    .done(function (data) {
        // Construct the dropDownList
        var playersTeams = document.getElementById('teammatetd-teams_id');
        playersTeams.options.length=0;
        playersTeams.options[playersTeams.options.length] = new Option('', '', false, false);
        Object.entries(data).forEach(([key,val]) => {
            console.log('val key='+val,key);
            playersTeams.options[playersTeams.options.length] = new Option(val, key, true, false);
        });
    })
    .fail(function (x, e) {
            alert("The call to the server side failed. " + x.responseText);
                    
    });
});
/*
*   Manage page layout and visibilities
*/
const directiveEement = document.querySelector('#playerstd-directive');
directiveEement.addEventListener('change',(event) => {
    var directive = directiveEement.options[directiveEement.selectedIndex].value;


    document.querySelectorAll("[id*='reqtmmateplayers-'],\
                                    [id*='teammatetd-'],\
                                    #playerstd-teams_id,\
                                    #playerstd-reqtmmate_id").forEach(function(item) {
        item.parentElement.style.display = 'none';
    });

    switch (directive) {

        case 'new':
        case 'moveUp':
        case 'volunteer':
            document.querySelector("#playerstd-reqtmmate_id").value = '';
            break;

        case 'return':
            document.querySelector("#playerstd-teams_id").parentElement.style.display = 'block';
            document.querySelector("#playerstd-reqtmmate_id").value = '';
            break;

        case 'relativeCoach':
        case 'relativePlayer':

            document.querySelector("#playerstd-teams_id").parentElement.style.display = 'block';
            document.querySelector("#playerstd-reqtmmate_id").parentElement.style.display = 'block';

            const reqTmMateEement = document.querySelector('#playerstd-reqtmmate_id');
            reqTmMateEement.addEventListener('change',(event2) => {
                var reqTmMate_id = reqTmMateEement.options[reqTmMateEement.selectedIndex].value;
                
                document.querySelectorAll("[id*='reqtmmateplayers-'],\
                                            [id*='teammatetd-']").forEach(function(item) {
                        item.parentElement.style.display = 'block';
                        item.value = '';
                });
                
                if (reqTmMate_id > 0) {

                    document.querySelector('#reqtmmateplayers-searchname').disabled = true;
                    /*
                    * reqTmMate selected from dropDownList and get information and populate fields
                    */
                    var csrfToken = $('meta[name="csrf-token"]').attr("content");

                    $.getJSON(userPlayer, {
                        id: reqTmMate_id
                    })
                    .done(function( data ) {
                        document.getElementById("reqtmmateplayers-id").value = data.id;
                        document.getElementById("reqtmmateplayers-searchname").value = data.first + " " + data.last;
                        document.getElementById("reqtmmateplayers-street").value = data.street;
                        document.getElementById("reqtmmateplayers-zipphrase").value = data.zipPhrase;

                        document.getElementById("teammatetd-directive").value = data.directive;
                        document.getElementById("teammatetd-gradegroup").value = data.gradeGroup;
                        document.getElementById("teammatetd-teams_id").value = data.teams_id;
                        // on load, have to wait until teamamtetd is defined, then trigger comparision.
                        if (!event2.isTrusted) document.getElementById("teammatetd-teams_id").dispatchEvent(new Event("change"));

                    }) 
                    .fail(function( jqXHR, textStatus ) {
                        alert( "Request failed: " + textStatus );
                    });


                } else if (reqTmMate_id < 0) {
                    /*
                    *   Search mode
                    */
                    document.getElementById("reqtmmateplayers-id").value = -1;
                    document.querySelector('#reqtmmateplayers-searchname').disabled = false;
                    document.querySelector('#reqtmmateplayers-searchname').value = '';
                } 

            

            });
        break;
    }

    
});

document.getElementById("playerstd-directive").dispatchEvent(new Event("change"));
document.getElementById("playerstd-reqtmmate_id").dispatchEvent(new Event("change"));
