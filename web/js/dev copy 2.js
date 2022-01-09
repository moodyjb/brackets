
// ref: https://getbootstrap.com/docs/4.0/components/modal/
// ref: http://thednp.github.io/bootstrap.native/


var dayClicked;         // used to save day for use in apply
var dow;                // used to save dow for use in apply
var noPeeWeeGames=0;
var noJuniorGames=0;
var noSeniorGames=0;
var remainingPeeWeeGames=0;
var remainingJuniorGames=0;
var remainingSeniorGames=0;

//
// Number of Rounds Options
//
  $(document).on('click', '#modalReqNoRounds', function(){
    console.log($(this).attr('value'));
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-body').load("index.php?r=schedule/required-no-rounds");
    $('#modal').modal({show:true, backdrop:'static'});
 });
//
// Times/Diamonds Options
//
  $(document).on('click', '#modalTimesDiamonds', function(){
    console.log($(this).attr('value'));
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-body').load("index.php?r=schedule/times-diamonds-slots");
    $('#modal').modal({show:true, backdrop:'static'});
 });
//
// Schedule Options
//
  $(document).on('click', '#modalScheduleOptions', function(){
    console.log($(this).attr('value'));
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-body').load("index.php?r=schedule/schedule-options");
    $('#modal').modal({show:true, backdrop:'static'});
 });

//
// Print Options
//
  $(document).on('click', '#modalPrintOptions', function(){
    console.log($(this).attr('value'));
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-body').load("index.php?r=schedule/print-options");
    $('#modal').modal({show:true, backdrop:'static'});
 });


//
// SCHEDULE GAMES .... need to revise !!!!
//
// var scheduleGames = document.querySelector('#getschedule');
// scheduleGames.addEventListener('click', (event) => {

//      var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
//      noPeeWeeRounds = document.querySelector("#roundsPeeWee").value;
//      noJuniorRounds = document.querySelector('select[id^="roundsJunior"]').value;
//      noSeniorRounds = document.querySelector("#roundsSenior").value;

//     $.ajax({
//         url: 'index.php?r=schedule/sequence-games',
//         type: 'post',
//         dataType: 'json',
//         data: {_csrf : csrfToken, noPeeWeeRounds: noPeeWeeRounds, noJuniorRounds: noJuniorRounds, noSeniorRounds: noSeniorRounds},
//         async: true,
//         cache: false
//     })
//     .done(function (arrGames) {
//         console.log(arrGames);
//         window.location.href='index.php?r=schedule/dev';

//         // holiday modal uncheck all

//         })
//     .fail(function (x, e) {
//             alert('The call to the server side failed. ' + x.responseText);

//     });
// });
//
//  Holidays ... show modal holiday calendar
//
var holidays = document.querySelector('#getHolidays');
holidays.addEventListener('click', (event) => {
    // sync db table  with modal
    var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
    $.ajax({
        url: 'index.php?r=schedule/get-holidays',
        type: 'post',
        dataType: 'json',
        data: {_csrf : csrfToken},
        async: true,
        cache: false
    })
    .done(function (arrHolidays) {
        console.log(arrHolidays);
        // holiday modal uncheck all
        document.querySelectorAll('#holidays input[type="checkbox"]').forEach( (hol) => {
            hol.checked=false;
        })
        //add holidays to modal from the table schedule
        arrHolidays.forEach( (date) => {
            slctr = '#holidays input[id="'+date+'"]';
            document.querySelector(slctr).checked = true;
        });
        })
    .fail(function (x, e) {
            alert('The call to the server side failed. ' + x.responseText);

    });
    $('#holidays').modal({show:true, backdrop:'static'});
});
//
// Save Holidays and update screen calendar (avoids as refresh)
//
var applyHolidays = document.querySelector('#applyHolidays');
applyHolidays.addEventListener('click', (event) => {
    // From holiday  modal create array of holidays
    var holidays = [];
    document.querySelectorAll('#holidays input:checked').forEach( (day) => {
        holidays.push(day.getAttribute('id'));
    })

    // Save holidays to table schedule
    var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
    $.ajax({
        url: 'index.php?r=schedule/save-holidays',
        type: 'post',
        dataType: "json",
        data: {_csrf : csrfToken, holidays : JSON.stringify(holidays)},
        async: true,
        cache: false
    })
    .done(function (gradeGroup) {
        // update Holidays screen to avoid a refresh
        // ... delete all holidays
        document.querySelectorAll("#calendar .noGames").forEach( (div) => {
            div.className = 'any';
            div.textContent = '';
        })
        // ... add chosen holidays
        holidays.forEach( (date) =>{
            document.querySelectorAll('div[id^="'+date+'"][id*=":00^"]').forEach( (div) => {
                div.className = 'noGames';
                div.textContent = '';
            })
        });
        // this may force some rescheduling
        matchReq2Slots();
    })
    .fail(function (x, e) {
            alert('The call to the server side failed. ' + x.responseText);

    });
});
/*
*   After any slot designation or number of games changes, this function recalculates
*   games to be played and assigns them to representaive slots. Respresentaive
*   slots are denoted by class = 'junior' or 'junior reserved'
*/
function matchReq2Slots()
{
    /*
    *   For each gradeGoup get number of teams and  required number of games
    */
 var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
    $.ajax({
        url: 'index.php?r=schedule/get-req-games',
        type: 'post',
        dataType: 'json',
        data: {_csrf : csrfToken},
        async: true,
        cache: false
    })
    .done(function (schdConfig) {
        // For each gradeGroup returns number of teams and the number of rounds to be played
        for (var attr in schdConfig) {
            console.log('attr= '+attr);
            document.querySelector('select[id="'+attr+'"]').value = schdConfig[attr];
        }
        // Trigger a recalculation of the number of games by gradeGroup
        document.querySelectorAll('select[id^="rounds"]').forEach( (sel) => {
            sel.dispatchEvent(new Event("change"));
        })
        console.log('noJuniorGames='+noJuniorGames);
        console.log('remainingJuniorGames='+remainingJuniorGames);
        /*
        * Iterate over each slots and assign a game by gradeGroups and display the remaining games
        */
        document.querySelectorAll('#calendar .peewee, #calendar .junior, #calendar .senior').forEach( (slot) => {
            switch (slot.className) {
                case 'peewee':
                case 'peewee reserved':
                    if (remainingPeeWeeGames > 0) {
                        remainingPeeWeeGames--;
                        slot.textContent = remainingPeeWeeGames;
                        slot.className = 'peewee';
                    }  else  {
                        // Not needed; mark as reserved but still assignable as a peewee slot
                        //slot.classList.add('reserved');
                        slot.textContent='';
                    }
                    break;

                case 'junior':
                case 'junior reserved':
                    if (remainingJuniorGames > 0) {
                        remainingJuniorGames--;
                        slot.textContent = remainingJuniorGames;
                        slot.className = 'junior';
                    }  else  {
                        // Not needed; mark as reserved but still assignable as a peewee slot
                        //slot.classList.add('reserved');
                        slot.textContent='';
                    }
                    break;

                case 'senior':
                case 'senior reserved':
                    if (remainingSeniorGames > 0) {
                        remainingSeniorGames--;
                        slot.textContent = remainingSeniorGames;
                        slot.className = 'senior';
                    } else  {
                        // Not needed; mark as reserved but still assignable as a peewee slot
                        //slot.classList.add('reserved');
                        slot.textContent='';
                    }
                    break;
            }
        });
        /*
        *   Put each slot into an array
        */
        var saveBuffer = [];
        document.querySelectorAll('#calendar div[id*=":00^"]').forEach( (sel) => {
            saveBuffer.push([sel.getAttribute('id'), sel.className, sel.textContent]);
        });

        /*
        *   Write each slot of calendar to table 'schedule'.
        */
        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        $.ajax({
            url: 'index.php?r=schedule/save-template',
            type: 'post',
            data: {_csrf : csrfToken, elmnts : JSON.stringify(saveBuffer)},
            async: true,
            cache: false
        })
        .done(function (gradeGroup) {
            })
        .fail(function (x, e) {
                alert('The call to the server side failed. ' + x.responseText);

        });


    })
    .fail(function (x, e) {
            alert('The call to the server side failed. ' + x.responseText);

    });
    return;
}

//
// Number of Games
// click button to show Number of Games modal
// //
// var requiredNoGames = document.querySelector('#getRequiredNoGames');
// requiredNoGames.addEventListener('click', (event) => {
//     // check if any slots have been allocated
//     var noSlots = 0;
//     document.querySelectorAll('div[id*=":00^"]').forEach( (div) => {
//         if (['peewee','junior','senior'].indexOf(div.className) > -1) {
//             noSlots++;
//         }
//     });
//     if (noSlots==0) {
//         document.querySelector('#noSlotsWarning').innerHTML=`Suggest that you click Slots and
//         allocate  grade groups t0 specific diamond slots`;
//     }


//     // copy table scheduleConfig teams & rounds to this modal
//     var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
//     $.ajax({
//         url: 'index.php?r=schedule/get-req-games',
//         type: 'post',
//         dataType: 'json',
//         data: {_csrf : csrfToken},
//         async: true,
//         cache: false
//     })
//     .done(function (schdConfig) {
//         // For each gradeGroup returns number of teams and the number of rounds to be played
//         for (var attr in schdConfig) {
//             document.querySelector('select[id="'+attr+'"]').value = schdConfig[attr];
//             console.log(attr);
//         }
//         // Trigger a recalculation of the number of games by gradeGroup
//         document.querySelectorAll('select[id^="rounds"]').forEach( (sel) => {
//             sel.dispatchEvent(new Event("change"));
//         })
//     })
//     .fail(function (x, e) {
//             alert('The call to the server side failed. ' + x.responseText);

//     });
//     $('#requiredNoGames').modal({show:true, backdrop:'static'});
// });

//
// Number of Games
//   teams or Rounds changed, update the number of games.
//   also invoked when loading 'Number of teams'
//
document.querySelectorAll('select[id^="rounds"]').forEach( (sel) => {
    sel.addEventListener('change', (reqSchdComponent) => {


        console.log('req game ddl change'+sel.className);
        if (sel.className == 'peewee') {
            selClass = 'PeeWee';
        } else {
            selClass = sel.className.charAt(0).toUpperCase()+sel.className.slice(1);
        }
        console.log('selClass='+selClass);
        rounds=document.querySelector('select[id^="rounds'+selClass+'"]').value;
        noTeams=document.querySelector('div[id^="no'+selClass+'Teams"]').textContent;
        console.log("rounds="+rounds+"   noTeams="+noTeams);
        if (rounds && noTeams) {
            noGames=rounds*noTeams*(noTeams-1)/2;
            document.querySelector('div[id^="no'+selClass+'Games"]').textContent = noGames;
            // save global variable the number of games
            if (sel.className == 'peewee') {
                noPeeWeeGames = noGames;
                remainingPeeWeeGames = noGames;
            } else if (sel.className == 'junior') {
                noJuniorGames = noGames;
                remainingJuniorGames = noGames;
            } else if (sel.className == 'senior') {
                noSeniorGames = noGames;
                remainingSeniorGames = noGames;
            }
        }
    });
});

//
// Number of Games
//  APPLY the number of games
var applyReq = document.querySelector('#applyNoGames');
applyReq.addEventListener('click', (event) => {
    /*
    * copy number of teams and number of rounds to table scheduleConfig
    *   note that teamStats is an object {}
    */
    var teamStats = {};
    console.log("Applied ");
    document.querySelectorAll('div.getReq select').forEach( (sel) => {
        teamStats[sel.getAttribute('id')] = sel.value;
        console.log(sel.getAttribute('id'));
        console.log('no rnds='+sel.value);
   });
   console.log('teamStats='+teamStats);
    var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
    console.log(JSON.stringify(teamStats));
    $.ajax({
        url: 'index.php?r=schedule/save-number-of-games',
        type: 'post',
        data: {_csrf : csrfToken, teamStats : JSON.stringify(teamStats)},
        async: true,
        cache: false
    })
    .done(function (gradeGroup) {
        })
    .fail(function (x, e) {
            alert('The call to the server side failed. ' + x.responseText);

    });
    matchReq2Slots();

});
//
// Reset
//   set slot assignments to null && truncate the table.
var resetRequirements = document.querySelector('#resetRequirments');
resetRequirements.addEventListener('click', (event) => {
    //document.querySelectorAll('#calendar .junior,#calendar .senior,#calendar .peewee, #calendar .na').forEach( (item) => {
    document.querySelectorAll('div[id*=":00^"]').forEach( (item) => {
        item.className = 'any'
        item.textContent = '';
    });
    var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
    $.ajax({
        url: 'index.php?r=schedule/reset',
        type: 'post',
        data: {_csrf : csrfToken},
        async: true,
        cache: false
    })
    .done(function (data) {
    })
    .fail(function (x, e) {
            alert('The call to the server side failed. ' + x.responseText);
    });

});
//
// Slots
//   click "slots" button to display associated modal
// //
// var requirements = document.querySelector('#getRequirements');
// requirements.addEventListener('click', (event) => {
//     // require that the number of games be set
//     if ( noPeeWeeGames == 0 || noJuniorGames == 0 || noSeniorGames == 0) {
//         alert('Set the Number of Games first');
//     } else {
//         $('#requirements').modal({show:true, backdrop:'static'});
//     }
// });
//
// Slots
//   Clear slot requests
var applyReq = document.querySelector('#clearAll');
applyReq.addEventListener('click', (event) => {
    document.querySelectorAll('input[type=checkbox]').forEach( (sel) => {
        sel.checked = false;
    })
    document.querySelector('#templateGradeGroup').value='';
});
//
// Slots
// Assign gradeGroup to selected Month, Day of week, Time, and diamond slots
//   Apply selects to corresponding calendar and
//   reassign gasmes to slots showing remaining games
var applyReq = document.querySelector('#applyRequirments');
applyReq.addEventListener('click', (event) => {

    gradeGroup = document.querySelector('select[id=templateGradeGroup]').value;

    document.querySelectorAll('input[name=templateMonth]:checked').forEach((month) => {

        document.querySelectorAll('input[name=templateDow]:checked').forEach((dow) => {

            document.querySelectorAll('input[name=templateTime]:checked').forEach((time) => {

                document.querySelectorAll('input[name=templateDiamond]:checked').forEach((diamond) => {
                    //console.log(month.value, dow.value, time.value, diamond.value);
                    id1 = month.value;
                    id2 = time.value+"^"+diamond.value+"^"+dow.value;
                    document.querySelectorAll('div[id^="'+id1+'"][id$="'+id2+'"]').forEach( (slot) => {
                        if (slot.className != 'noGames') {
                        //console.log(query, gradeGroup);
                             slot.className = gradeGroup;
                             slot.textContent = '';
                        }
                    })
                });
            });
        });

    });

    matchReq2Slots();
});
//
// Day header 'Thr May-14'
//  click on a header label like 'Thr May-14'
// Need to set day modal slots to match day clicked
document.querySelectorAll('.dom').forEach( (dom) => {
    dom.addEventListener('click', (e) => {
        document.querySelector('#dayModal .modal-title').textContent = dom.textContent;
        // need dayClicked and Dow when click 'Apply'
        dayClicked=dom.getAttribute('id');
        dow = dom.textContent.slice(0,3);

        dom.parentElement.querySelectorAll('div[id^="'+dayClicked+'"][id*=":00^"]').forEach( (slot) => {
            // clicked calendar days each time-dia slot ... e.g. "2020-07-22 19:00:00^2^Wed"
            // strip out "hh:mm::ss^dia" segment
            var dayId = slot.getAttribute('id').substring(11,21);
            // corresponding modal day slot .. e.g. "20:30:00^1"
            gradeGroup = slot.className.split(" ")[0];
            document.querySelector('select[id="'+dayId+'"]').value = gradeGroup;
        });
        $('#dayModal').modal({show:true, backdrop:'static'});
    });
});

//
// Day header 'Thr May-14'
// Selected an option from select id='allDay'
//   entire id='allDay' copied to each of the 6 selects
//
var allDay = document.querySelector('select#allDay');
allDay.addEventListener('change', (e) => {
    // copy to each select
    console.log(allDay.value)
    document.querySelectorAll('#dayModal select[id*=":00^"]').forEach( (sel) => {
        sel.value = allDay.value;
    })
});

//
// Day header 'Thr May-14'
// click 'Apply'
//  Each of the 6 selects copies to the corresponding calendar day
//  and recalculate assigned & remaining games
//
var applyDay = document.querySelector('#applyDay');
applyDay.addEventListener('click', (event) => {
    document.querySelectorAll('#dayModal select[id*=":00^"]').forEach( (sel) => {
        id = dayClicked+' '+sel.getAttribute('id')+"^"+dow;
        document.querySelector('div[id="'+id+'"]').className=sel.value;
        document.querySelector('div[id="'+id+'"]').textContent = '';
    });
    /*
    * recalculate games and save each day
    */
    matchReq2Slots();
});

// Initializevar csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
//matchReq2Slots();
