
// ref: https://getbootstrap.com/docs/4.0/components/modal/
// ref: http://thednp.github.io/bootstrap.native/

//
//  Month click
//
var month;
var monthId;
var noPeeWeeGames = 24;
var noJuniorGames = 72;
var noSeniorGames = 108;

//
// Calendar button to show 'Requirements' modal
//
var requirements = document.querySelector('#getRequirements');
requirements.addEventListener('click', (event) => {
    $('#requirements').modal({show:true, backdrop:'static'});
});

//
// Requirements modal
// Specify number of teams and rounds and calculate games
// display backgroundColor and remaining games
// document.querySelectorAll('div#requirements select').forEach( (sel) => {
//     sel.addEventListener('change', (event) => {
//         if (sel.className == 'peewee') {
//             selClass = 'PeeWee';
//         } else {
//             selClass = sel.className.charAt(0).toUpperCase()+sel.className.slice(1);
//         }

//         rounds=document.querySelector('select[id^="rounds'+selClass+'"]').value;
//         noTeams=document.querySelector('select[id^="no'+selClass+'Teams"]').value;
//         if (rounds && noTeams) {
//             noGames=rounds*noTeams*(noTeams-1)/2;
//             document.querySelector('div.'+sel.className).textContent = noGames;
//             if (sel.className == 'peewee') {
//                 noPeeWeeGamnoPeeWeeGameses = noGames;
//             } else if (sel.className == 'junior') {
//                 noJuniorGames = noGames;
//             } else if (sel.className == 'senior') {
//                 noSeniorGames = noGames;
//             }
//             // fill in available slots
//             document.querySelectorAll('#calendar .'+sel.className).forEach( (item) => {

//                 if (noGames > 0) {
//                     item.className = sel.className+"Assigned";
//                     item.textContent = noGames;
//                     noGames--;
//                 }
//             })
//         } else {
//             noGames='';
//         }
//     });
// });
//
// Display scheduled gradeGroups defined in Requirments modal
// var modReq = document.querySelector('#saveRequirments');
// modReq.addEventListener('click', (event) => {
//     // iterate through modal's each day of week and time of dat and dia
//     // It the modal template is default 'na', then the corresponding calendar day set to 'na'
//     document.querySelectorAll('div#requirements div.inner-grid select').forEach( (sel) => {
//         allocClass = sel.value;
//         timeDiaDow = sel.getAttribute('id');
//         // For each calendar month
//         document.querySelectorAll('.nameOfMonth').forEach( (mon) => {
//             yearMonth = mon.getAttribute('id');
//             // set coresponding calendat 'div'
//             document.querySelectorAll('div[id^="'+yearMonth+'"][id$="'+timeDiaDow+'"]').forEach( (cal) => {
//                 //console.log('div[id^="'+yearMonth+'"][id$="'+timeDiaDow+'"]' + '  class='+allocClass);
//                 cal.className = allocClass;
//             });
//         });
//     });
    /*
    * Need to process allocated spaces in chronological  sequence
    */
    // document.querySelectorAll('#calendar .peewee, #calendar .junior, #calendar .senior').forEach( (slot) => {
    //     //console.log(slot.getAttribute('id')+'  '+slot.className);
    //     switch (slot.className) {
    //         case 'peewee':
    //             if (noPeeWeeGames > 0) {
    //                 noPeeWeeGames--;
    //                 slot.textContent = noPeeWeeGames;
    //             } else {
    //                 slot.className = 'na';
    //             }
    //             break;
    //         case 'junior':
    //             if (noJuniorGames > 0) {
    //                 noJuniorGames--;
    //                 slot.textContent = noJuniorGames;
    //             } else {
    //                 slot.className = 'na';
    //             }
    //             break;
    //         case 'senior':
    //             if (noSeniorGames > 0) {
    //                 noSeniorGames--;
    //                 slot.textContent = noSeniorGames;
    //             } else {
    //                 slot.className = 'na';
    //             }
    //             break;
    //     }
    // })
    // if (noPeeWeeGames>0 || noJuniorGames>0 || noSeniorGames>0) {
    //     //alert('!!! check, some gradeGroup\'s games are not completed layed out');
    // }
    // // Need to save each calendar element
    // let elmnts = [];
    // document.querySelectorAll('div[id$="Mon"], div[id$="Tue"], div[id$="Wed"], div[id$="Thr"], div[id$="Fri"]').forEach( (dom) => {
    //     tmp = dom.getAttribute('id');
    //     parts = tmp.split("^");
    //     index = parts.pop();
    //     id = parts.join("^");   // need to drop ^Tue
    //     console.log('id='+id+'   '+dom.className);
    //     elmnts.push([id, dom.className]);
    // });

    // var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
    // $.ajax({
    //     url: 'index.php?r=schedule/save-template',
    //     type: 'post',
    //     data: {_csrf : csrfToken, elmnts : JSON.stringify(elmnts)},
    //     async: true,
    //     cache: false
    // })
    // .done(function (gradeGroup) {
    //     })
    // .fail(function (x, e) {
    //         alert('The call to the server side failed. ' + x.responseText);

    // });



});

//
// Reset Requirements to null
var resetRequirements = document.querySelector('#resetRequirments');
resetRequirements.addEventListener('click', (event) => {
    document.querySelectorAll('#calendar .junior,#calendar .senior,#calendar .peewee, #calendar .na').forEach( (item) => {
        item.classList.remove("peewee","junior","senior","na");
        item.textContent = '';
    });
});

//
//  Calendar click Month to Show Modal & reset all selects to 'any'
document.querySelectorAll('.nameOfMonth').forEach( (mon) => {
    mon.addEventListener('click', (event) => {
        month = mon.textContent;
        monthId=mon.getAttribute('id');
        document.querySelector('#monthModal .modal-title').textContent = month;
        // Reset all select indexes
        document.querySelectorAll('.nameOfMonth select').forEach( (item) => {

            slot=item.getAttribute('id');
            console.log("\n"+'slot='+slot);
            item.selectedIndex=0;

            // Set template to saved valued

            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');

            $.ajax({
                url: 'index.php?r=schedule/get-template',
                type: 'post',
                data: {_csrf : csrfToken, yearMonth : monthId, timeDiaDow: slot},
                async: false,
                cache: false
            })
            .done(function (gradeGroup) {

                item.value = gradeGroup;
                //
                // update calendar to these default values
                //
                //console.log('monthid='+monthId+' slot='+slot+' gradeGroup='+gradeGroup);
                document.querySelectorAll('div[id^="'+monthId+'"][id$="'+slot+'"]').forEach( (cal) => {
                    console.log('yearMonth='+monthId+' slot= '+slot+ ' id='+cal.getAttribute('id'));
                    cal.className = gradeGroup;
                    cal.textContent='';
                    cal.style.backgroundColor='#fff';
                });
                })
            .fail(function (x, e) {
                    alert('The call to the server side failed. ' + x.responseText);

            });
        });
        $('#monthModal').modal({show:true, backdrop:'static'});
    });

});
// Assign games
// var noJuniorGames = 12;
// var i = 0;
// var assgnGms = document.querySelector('#assignGames');
//     assgnGms.addEventListener('click', (event) => {
//         document.querySelectorAll('.junior').forEach( (item) => {

//             if (noJuniorGames > 0) {
//                 item.style.backgroundColor = "#00ff00";
//                 item.textContent = noJuniorGames;
//                 noJuniorGames--;
//             }
//         })

//     });

//
// modal 'select' onchange copy to calendar
    document.querySelectorAll('div#monthModal select').forEach((slct) => {
        slct.addEventListener('change', (event) => {
            slot=slct.getAttribute('id');
            console.log("modal selections="+monthId+" "+slot+" "+slct.value);
            // apply to corresponding month in the calendar
            document.querySelectorAll('div[id^="'+monthId+'"][id$="'+slot+'"]').forEach(( (cal) => {
                cal.className = slct.value;
            }))
            // write to file

            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');

            $.ajax({
                url: 'index.php?r=schedule/save-template',
                type: 'post',
                data: {_csrf : csrfToken, yearMonth : monthId, timeDiaDow: slot, gradeGroup:slct.value},
                async: true,
                cache: false
            })
            .done(function (person) {
                console.log(person);
                if(person == 'error') {
                    window.location.href ='index.php?r=teammates/index';
                }
                })
            .fail(function (x, e) {
                    alert('The call to the server side failed. ' + x.responseText);

            });
        });
    })
//
// Apply requirments
var applyReq = document.querySelector('#applyMonth');
applyReq.addEventListener('click', (event) => {

    document.querySelectorAll('div#monthModal select').forEach((slct) => {
        slct.dispatchEvent(new Event("change"));
    });

});
//
// Reset modal monthlyrequirments
var resetReq = document.querySelector('#resetMonth');
resetReq.addEventListener('click', (event) => {

    document.querySelectorAll('div#monthModal select').forEach((slct) => {
        slct.value='';
    });

});

//
// Apply global requirments
var applyReq = document.querySelector('#applyRequirments');
applyReq.addEventListener('click', (event) => {

    document.querySelectorAll('input[name=templateMonth').forEach((month) => {

        document.querySelectorAll('input[name=templateDow').forEach((dow) => {

            document.querySelectorAll('input[name=templateTime').forEach((time) => {

                document.querySelectorAll('input[name=templateDiamond').forEach((time) => {

                });

            });
        });

    });

});
//
//  Calendar Day click
//
document.querySelectorAll('.dom').forEach( (dom) => {

    dom.addEventListener('click', (e) => {
        date=dom.getAttribute('id');
        dom.parentElement.querySelectorAll('div[id^="'+date+'"][id*="^"]').forEach((gm) =>{
            console.log(gm.textContent);
            gm.className = 'junior';
        })
    });
});
