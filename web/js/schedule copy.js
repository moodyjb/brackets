const colors = ['#006400','#00008b','#b03060','#ff4500','#ffd700','#7cfc00','#00ffff',
'#ff00ff', '#6495ed','#ffdab9'];
const tmOrgin = {'peewee':1, 'junior':21, 'senior':41};
console.log(tmOrgin);
console.log(tmOrgin.junior);
//document.querySelectorAll('.peewee, .junior, .senior, .unknown').forEach(function(item) {
document.querySelectorAll('.unknown, .peewee, .junior, .senior').forEach(function(item) {

    item.addEventListener('blur', (event) => {

        // Exact any team ids
        teams=item.innerHTML.match(/\d+/g);

        // check if gradegroup selected
        gradeGroup=document.querySelector("input[name=gEntry]:checked").value;
        if (teams && gradeGroup == 'unknown') {
            // How about blank !!!!
            alert('Must select a grade group at the top');
            item.innerHTML='<span></span>-<span></span>';
            return;
        }

        game='';
        if (!teams) {
            item.innerHTML='<span></span>-<span></span>';
        } else if (!teams[1]) {
            alert('Need 2 teams');
            item.innerHTML='<span></span>-<span></span>';
            return;
        } else {
            item.innerHTML='<span>'+teams[0]+'</span>-<span>'+teams[1]+'</span>';
            game = teams[0]+"-"+teams[1];
        }

        console.log('teams= '+teams);
        console.log('game= '+game);

        item.className = gradeGroup;
        var xhttp = new XMLHttpRequest();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        xhttp.open("post", "index.php?r=schedule/qwe", true);
        xhttp.setRequestHeader('x-csrf-token', csrfToken);
        xhttp.setRequestHeader("Content-Type", 'application/x-www-form-urlencoded');
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Response
                //console.log('response='+xhttp.responseText);
            }
        };
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        xhttp.send('id='+encodeURIComponent(item.id)+'&game='+encodeURIComponent(game)+'&gradeGroup='+encodeURIComponent(gradeGroup));
    });
});

document.querySelectorAll('#sat, #sun').forEach(function(item) {
    item.addEventListener('click', (event) => {
        var dow = item.id;
        noDow = 5;
        if (document.querySelector("#sun").checked) noDow++;
        if (document.querySelector("#sat").checked) noDow++;
        //document.querySelector("#week").className = "days"+noDow;
        document.querySelectorAll("div[name='week']").forEach (function(wk) {
            console.log('another week');
            wk.className = "days"+noDow;
        });
        document.querySelectorAll("header[name='main-header']").forEach (function(wk) {
            console.log('another week');
            wk.className = "main-header"+noDow;
        });
        document.querySelectorAll('.'+dow).forEach( (chbx) => {
            chbx.classList.toggle('removeDay');
        });
    });
});

// Hide/Show by gradeGroups
document.querySelectorAll('#peewee, #junior, #senior').forEach(function(item) {
    item.addEventListener('click', (event) => {
        var gradeGroup = item.id;
        console.log(gradeGroup);
        document.querySelectorAll('.'+gradeGroup).forEach( (chbx) => {
            chbx.classList.toggle('hideGradeGroup');
        });
    });
});

// Color teams
document.querySelectorAll('#cunknown, #cpeewee, #cjunior, #csenior').forEach(function(item) {
    item.addEventListener('click', (event) => {
        gradeGroup = item.id.substring(1);
        console.log('gradegroup='+gradeGroup);

        // remove all colors
        document.querySelectorAll('span').forEach(function(team) {
            team.style.backgroundColor = "#fff";
        });
        // gradeGroups clicked

        // document.querySelectorAll('span').forEach(function(opponent) {
        //     opponent.style.backgroundColor = rgb(255,0,0);
        // });


        document.querySelectorAll('.'+gradeGroup).forEach(function(game) {
            document.querySelectorAll('span').forEach(function(opponent) {
                team=opponent.innerHTML
                color=colors[parseInt(team)-tmOrgin[gradeGroup]];
                opponent.style.backgroundColor = color;
            });

        });

    });
});
