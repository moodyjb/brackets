
    
/*
    * When directive changes, need to submit form and remove any validations that could block submission.
    */
const selectDirective = document.querySelector('#teamdirectives-directive');
const playerSelected = document.querySelector('#teamdirectives-reqtmmate_id').value;
const teamSelected = document.querySelector('#teamdirectives-teams_id').value;

selectDirective.addEventListener('change',(event) => {

    var directive = selectDirective.options[selectDirective.selectedIndex].value;

    console.log(directive);
    // clear reqtmmateplayers fields && directive's reqTmMate_id
    document.querySelectorAll("[id*='reqtmmateplayers-']").forEach(function(item){
        item.parentElement.style.display = 'none';
        item.parentElement.classList.remove('required');
        item.value = '';
    });
    // clear teamDirectives fields teams_id and reqTmMate_id
    document.querySelectorAll('#teamdirectives-teams_id, #teamdirectives-reqtmmate_id').forEach(function(item) {
        item.parentElement.style.display = 'none';
        item.parentElement.classList.remove('required');
        if (event.isTrusted) item.value = '';               // only empty if an actual clicked selection event
        console.log(item.getAttribute('id'),  item.value );
    });
    // make surrounding box clear
    
    document.getElementById('borderBox').classList.remove('surroundBox');

    switch (directive) {
        case 'draft':
        case 'new':
        case 'moveUp':
        case 'volunteer':
            break;

        case 'return':
            console.log('teamSelected='+teamSelected);
            document.querySelector('#teamdirectives-teams_id').parentElement.style.display = 'block';
            // if current team empty and lastSeason team exists 
            if (teamSelected === '') {
                document.querySelector('#teamdirectives-teams_id').value = lastSeasonTeams_id;
            }
            break;

        case 'relativeCoach':
        case 'relativePlayer':
            document.querySelector('#teamdirectives-reqtmmate_id').parentElement.style.display = 'block';
            const selectReqTmMate_id = document.querySelector('#teamdirectives-reqtmmate_id');
            // Only clear if a clicked event.
            if (event.isTrusted) selectReqTmMate_id.value = '';

            // assign reqtmmate_id dropDownList that is either Players or Adults
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            $.ajax({
                url: 'index.php?r=team-directives/ajax-'+ (directive=="relativePlayer" ? 'players' : 'adults'),
                type: 'post',
                data: {_csrf : csrfToken},
                dataType: 'json',
                async: true,
                cache: false
            })
            .done(function (data) {
                // Construct the dropDownList
                selectReqTmMate_id.options.length=0;
                selectReqTmMate_id.options[selectReqTmMate_id.options.length] = new Option('', '', false, false);
                Object.entries(data).forEach(([key,val]) => {
                    selectReqTmMate_id.options[selectReqTmMate_id.options.length] = new Option(val, key, true, false);
                });
                selectReqTmMate_id.options[selectReqTmMate_id.options.length] = new Option('-- Other --', '-1', false, false);
                selectReqTmMate_id.value = playerSelected;
            })
            .fail(function (x, e) {
                    alert("The call to the server side failed. " + x.responseText);
                            
            });


            //
            // Player/Coach selection ==== Loop ====
            //
            selectReqTmMate_id.addEventListener('change',(event) => {
                var reqTmMate_id = selectReqTmMate_id.options[selectReqTmMate_id.selectedIndex].value;

                // hide and clear reqTmMate relative fields ... if 'Other' selected blank out any previous values
                document.querySelectorAll("[id*='reqtmmateplayers-']").forEach(function(item){
                    item.parentElement.classList.remove('required');
                    item.value = '';
                    item.parentElement.style.display = 'none';
                    });

                    document.getElementById('borderBox').classList.remove('surroundBox');
                // assignment must be after values set = ''
                document.getElementById('reqtmmateplayers-id').value  = reqTmMate_id;
                
                if (reqTmMate_id == '') return;
                
                document.getElementById('borderBox').classList.add('surroundBox');
                document.getElementById('reqtmmateplayers-warning').parentElement.style.display = 'block';
                if (directive == 'relativeCoach') {
                    document.getElementById('reqtmmateplayers-relativecoach').parentElement.style.display = 'block';
                } else {
                    document.getElementById('reqtmmateplayers-relativeplayer').parentElement.style.display = 'block';
                }

                // Iterate through each field
                document.querySelectorAll("[id*='reqtmmateplayers-']").forEach(function(item){
                    field = item.getAttribute('id').split('-');
                    switch(field[1]) {
                        case 'street':
                        case 'zipphrase':
                            item.parentElement.classList.add('required');
                        case 'street2':
                            item.parentElement.style.display = 'block';
                            break;

                        case 'mobile':
                        case 'email':
                            // required only for a coach
                            item.parentElement.style.display = 'block';
                            if (directive == 'relativeCoach') {
                                item.parentElement.classList.add('required');
                            }
                            break;

                        case 'searchname':
                            if (role == 'admin' && reqTmMate_id < 0) {
                                item.parentElement.style.display = 'block';
                                item.parentElement.classList.add('required');
                            }
                            break;

                        case 'first':
                        case 'last':
                            if ( role != 'admin' || reqTmMate_id > 0) {
                                item.parentElement.style.display = 'block';
                                item.parentElement.classList.add('required');
                            }
                            break;

                        case 'birthdate':
                            if ( directive == 'relativePlayer' && role != 'admin') {
                                item.parentElement.style.display = 'block';
                                item.parentElement.classList.add('required');
                            }
                    }
                });

                if (reqTmMate_id > 0) {
                    // get selected player information
                    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
                    //var csrfToken = $('meta[name="csrf-token"]').attr("content");
                    $.ajax({
                        url: 'index.php?r=volunteer-coaches/player-address',
                        type: 'post',
                        data: {_csrf : csrfToken, players_id : reqTmMate_id},
                        dataType: 'json',
                        async: true,
                        cache: false
                    })
                    .done(function (player) {
                        if (role == 'admin' && reqTmMate_id < 0) {
                            document.getElementById("reqtmmateplayers-searchname").value = player.first+ ' ' + player.last;
                        } else {
                            document.getElementById("reqtmmateplayers-first").value = player.first;
                            document.getElementById("reqtmmateplayers-last").value = player.last;
                            if(directive == 'relativePlayer') {
                                document.getElementById("reqtmmateplayers-birthdate").value = player.birthdate;
                            }
                        }
                        document.getElementById("reqtmmateplayers-street").value = player.street;
                        document.getElementById("reqtmmateplayers-street2").value = player.street2;
                        document.getElementById("reqtmmateplayers-zipphrase").value = player.zipPhrase;
                        document.getElementById("reqtmmateplayers-email").value = player.email;
                        document.getElementById("reqtmmateplayers-mobile").value = player.mobile;
                    })
                    .fail(function (x, e) {
                            alert("The call to the server side failed. " + x.responseText);
                                    
                    });
                }
            });
    }
});
    
// Need to initialized based upon loaded values from the table.
document.getElementById("teamdirectives-directive").dispatchEvent(new Event("change"));
document.getElementById("teamdirectives-reqtmmate_id").dispatchEvent(new Event("change"));


