
 // Players Help "?"
 $(document).on('click', '#modalPlayersHelp', function(){
    console.log($(this).attr('value'));
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-body').load("index.php?r=family/help-players");
    $('#modal').modal({show:true, backdrop:'static'});
 });
 // Players Teammates Help "?"
 $(document).on('click', '#modalTeammatesHelp', function(){
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-body').load("index.php?r=family/help-players");
    $('#modal').modal({show:true, backdrop:'static'});
 });
 // Coaches Help "?"
 $(document).on('click', '#modalCoachesHelp', function(){
    console.log($(this).attr('value'));
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-title').append("<h3>Voluneer Coaches</h3>");
    $('#modal').find('.modal-body').load("index.php?r=family/help-coaches");
    $('#modal').modal({show:true, backdrop:'static'});
 });
 // Coaches Teammates Help "?"
 $(document).on('click', '#modalCoachmatesHelp', function(){
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-body').load("index.php?r=family/help-coachmates");
    $('#modal').modal({show:true, backdrop:'static'});
 });


    //
    // Insert blank lines with New or  Add buttons
    //
    var table = document.getElementById('teammatesTable');
    var lastId = null;
    let colors = ['#d7d7d7', '#ffff'];
    var backgroundColor = 0;

/*
*   Get any player of coach checked teammate box and push into an array
*/
function getSelected() {
    const chkboxes = document.querySelectorAll('input[name="teammateChecked"]');
    user = [];

    for (const chkbox of chkboxes) {
        if (chkbox.checked) {
            user.push([chkbox.value, chkbox.getAttribute('mode')]);
        }
    }
    return user;
}

/*
* Either New or add checked
* #teammatesTable > tbody:nth-child(2) > tr:nth-child(1) > td:nth-child(1) > a:nth-child(1)
*/
const toGroup = document.querySelectorAll('#newChecked, a[name="addChecked"]').forEach(function(item) {
    item.addEventListener('click',(event) => {

        users = getSelected();
        teammateGroup = item.getAttribute('teammategroup');

        // AUDIT SELECTIONS

        // Limied to only one of player 'unknown' or coach 'unknown'
        otherCount = users.filter( x => x[0]==-1).length;
        // Limit teammate coaches to 1 selection
        noCoaches = users.filter( x => x[1]=='coach').length;

        if (users.length == 0) {
            alert("NO players/coaches selected");
            return false;
        }
        if (noCoaches > 1) {
            alert("Can NOT request more than 1 coach");
            return false;

        } else if (otherCount > 1) {
            alert("Can NOT check both Player 'Other' and Coaches 'Other non-family'");
            return false;
        }
        // check if coach already requested ... only needed for 'add'.

        // ... continue auditing
        if (!teammateGroup) {
            // 'New' clicked.
            familyMember = users.find( function(element) {
                if (element[0] > 0) {
                    return true;
                }
             });
            if (familyMember === undefined) {
                alert('Must select at least one named player/coach');
                return false;
            }
            if (users.length < 2) {
                alert('Must select at least 2 player(s) and/or coach(es)');
                return false;
            }

        } else {
            // 'Add' clicked
            if (users.length < 1) {
                alert('Must select at least 1 player(s) and/or coach(es)');
                return false;
            }
            // check if coach already in the group
            coachMember = users.find( function(element) {
                if (element[1] == 'coach') {
                    return true;
                }
             });
            //
            if (coachMember !== undefined) {
                // a coach checked
                if (noCoachGroup != null && typeof noCoachGroup[teammateGroup] != undefined &&  noCoachGroup[teammateGroup] > 0) {
                    alert('Limited to 1 coach per group');
                    return false;
                }
            }
        }

        // save group id of the new/add button in $_SESSION['teammateGroup']
        // 'item' is the clicked 'new' or 'add' object
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("demo").innerHTML = 'Saved teammateGroup';
            }
        };
        xhttp.open("GET", "index.php?r=family/save-teammate-group&teammateGroup="+item.getAttribute('teammategroup'), true);
        xhttp.send();

        // Get checked item
        console.log(users);
        checkedUsers = JSON.stringify(users);
        console.log(checkedUsers);


        // Save users (all checked teammates) into a SESSION
        var xhttp2 = new XMLHttpRequest();
        xhttp2.onreadystatechange = function() {
            if (xhttp2.readyState == 4 && xhttp2.status == 200) {
                // Any 'other' requested ?
                otherRequested = users.find( function(element) {
                    if (element[0] == -1) {
                        return true;
                    }
                });
                // Check if any 'Other' checked
                if (otherRequested !== undefined) {
                    // 'Other' checked so need to get identity and add to checked users
                    window.location.href = "index.php?r=family/add-other&otherRequested="+JSON.stringify(otherRequested);
                } else {
                    // only existing players and/or coaches selected, add a group teammateGroup to
                    // these person's directives
                    window.location.href = "index.php?r=family/assign-membership";
                }
            }
        };
        xhttp2.open("GET", "index.php?r=family/save-requested-teammates&checkedUsers="+
            encodeURIComponent(checkedUsers), true);
        xhttp2.send();


    });
});

/*
* COACHES HELP
*/
// const helpCoaches = document.getElementById('helpCoaches');

// helpCoaches.addEventListener('click',(event) => {
//     $('#modal').find('.modal-title').html(`
//     Two purposes
//     <ol>
//     <li>Volunteer a family member to coach and/or request that they coach your player's team.</li>
//     <li>Select a non-family coach for whom your daughter wants to play.</li>
//     </ol>
//     `);

//     $('#modal').find('.modal-body').empty().html(`
//     <b>Volunteer to coach</b>
//     Select desired adult from the dropdown list <select></select>. Then click <button class='btn-xs btn-primary'>Add</button>.
//     If not in the list, select --Other Family-- and then click <button class='btn-xs btn-primary'>Add</button>.\n
//     <b>Select a non-family coach for whom your daughter wants to play</b>
//     If the coach's table is not visible, select 'Show Coaches Panel' from the dropdown list <select></select>
//     Then check target player's Teammate checkbox and Coaches 'Request non-family teammate coach' Teammate checkbox.
//     Then click the Teammates panel <button type='button' class='btn btn-default'>New</button>

//     `);
//     $('#modal').find('.modal-footer').empty().append(`
//         <button type='button' class='btn btn-default btn-ok' data-dismiss='modal'>OK</button>
//     `);
//     $('#modal').modal('show');
// });

// COACHES onselect toggle anchor
// const potentialCoaches = document.getElementById('potentialCoaches');
// potentialCoaches.addEventListener('change',(event) => {
//     var coach = potentialCoaches.options[potentialCoaches.selectedIndex].value;
//     var anchorCoaches = document.getElementById('anchorCoaches');
//     if (coach == '') {
//         document.getElementById('tableCoaches').style.display='none';
//         anchorCoaches.style.display='none';

//     } else if(coach == -999) {
//         document.getElementById('tableCoaches').style.display='';
//         anchorCoaches.style.display='none';
//         // show coaches table
//     } else {
//         anchorCoaches.style.display='inline';
//     }
// });

//
//  COACHES ... Either add from dropdowmList or creating a new user
//  If --Other-- selected and user_id < 0,
//      then the default values of [user/create] and callback used
//
// const addCoaches = document.querySelector('a[id="anchorCoaches"]');
// addCoaches.addEventListener('click',(event) => {

//     event.preventDefault();
//     var nameDDL = document.getElementById('potentialCoaches');
//     var user_id = nameDDL.options[nameDDL.selectedIndex].value;
//     if (user_id > 0) {
//         // Replace the default value.
//         addCoaches.setAttribute('href','index.php?r=family/add-coach-d-d-l');
//         var dataParams = JSON.parse(addSelected.getAttribute('data-params'));
//         dataParams.user_id = user_id;
//         dataParams.mode = 'coach';
//         addCoaches.setAttribute('data-params',JSON.stringify(dataParams));
//     }
// });


// COACHES teammates HELP
// const helpCoachesTeammate = document.getElementById('helpCoachesTeammate');
// helpCoachesTeammate.addEventListener('click',(event) => {

//     $('#modal').find('.modal-title').text('Select a coach for which you would like your daughter to play.');
//     $('#modal').find('.modal-body').html(
//         `To select a coach that you want your daughter to play for,\
//     check the <input type='checkbox'> associated with them.
//     If the desired coach is not listed,\
//     check 'Request non-family teammate coach' <input type='checkbox'>. Then in the section 'Teammates',\
//     click <button class='btn-xs'>New</button> or <button class='btn-xs'>Add</button>
//     `);
//     $('#modal').find('.modal-footer').empty().append(`
//         <button type='button' class='btn btn-default btn-ok' data-dismiss='modal'>OK</button>
//     `);
//     $('#modal').modal('show');
// });

// TEAMMATES
// const helpTeammates = document.getElementById('helpTeammates');
// helpTeammates.addEventListener('click',(event) => {
//     $('#modal').find('.modal-title').html('Group players/coaches to request that they be on the same team ');
//     $('#modal').find('.modal-body').empty().html(`

//         Check one or more player's Teammate checkboxes and/or coach's Teammate checkboxes, then\
//         click <button type='button' class='btn btn-default'>New</button> to request that they\
//         be placed on the same team.

//         To add a player to an existing group click that player's Teammate checkbox and then
//         click  <button type='button' class='btn btn-default'>Add</button>.
//         Rules<ul><li>A person may only be in 1 group</li><li>A group may only have 1 coach</li>\
//         <li>A New group must have at least 2 members</li>\
//         </ul>

//     `);
//     $('#modal').find('.modal-footer').empty().append(`
//         <button type='button' class='btn btn-default btn-ok' data-dismiss='modal'>OK</button>
//     `);
//     $('#modal').modal('show');
//});
