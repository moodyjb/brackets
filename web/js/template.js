
// ref: https://getbootstrap.com/docs/4.0/components/modal/
// ref: http://thednp.github.io/bootstrap.native/
var dateSelected;
document.querySelectorAll('.mon,.tue,.wed,.thu,.fri').forEach(function(item) {

    item.addEventListener('click', (event) => {
        dateSelected = item.getAttribute('id');
        // document.querySelector("#myModal").querySelectorAll('select').forEach(function(e) {
        //     e.value='na';
        // })
        date = item.querySelectorAll('span')[0].innerHTML;
        document.querySelector('h5.modal-title').innerHTML = "<h2>"+date + "</h2>";


        // set default selects
        //$('#myModal').modal({show:true, backdrop:'static'});
        $('#monthModal').modal({show:true, backdrop:'static'});

        console.log('count='+document.querySelectorAll('select').length);
        document.querySelectorAll('select').forEach((slct) => {
            console.log(item.getAttribute('id'));
            slct.selectedIndex = 0
        });
        // copy existing to modal ... need blank '2020-05-11 '
        document.querySelectorAll('div[id^="'+dateSelected+' "]').forEach(function(slot) {
            tmp=slot.getAttribute('id').split(" ")
            console.log(slot.getAttribute('class'));
            console.log(tmp);
            document.querySelector("#"+CSS.escape(tmp[1])).value = slot.getAttribute('class');
        });


    });
});
// All times both diamonds
var allDay = document.querySelector('#T00\\:00\\:00');
allDay.addEventListener('change', (e) => {
    allDayValue = allDay.options[allDay.selectedIndex].value;
    // set all selects to this value
    console.log(allDayValue)
    document.querySelectorAll('#myModal select').forEach(function(slct) {
        slct.value = allDayValue;
    });

});

// Select diamond #1 all times
var selectedDia1 = document.querySelector('#T00\\:00\\:00\\^1');
selectedDia1.addEventListener('change', (e) => {
    selDiaValue = selectedDia1.options[selectedDia1.selectedIndex].value;
    // set all selects to this value
    console.log(selDiaValue)
    document.querySelectorAll('select[id*="^1"]').forEach(function(slct) {
        slct.value = selDiaValue;
    });

});
// Select diamond #2 all times
var selectedDia2 = document.querySelector('#T00\\:00\\:00\\^2');
selectedDia2.addEventListener('change', (e) => {
    selDiaValue = selectedDia2.options[selectedDia2.selectedIndex].value;
    // set all selects to this value
    console.log(selDiaValue)
    document.querySelectorAll('select[id*="^2"]').forEach(function(slct) {
        slct.value = selDiaValue;
    });

});

//
// SAVE modal values to screen and database
var close = document.querySelector('#saveMonth');
close.addEventListener('click', (e) => {
    modalMonth = document.querySelector("#monthModal");
    monthModal.querySelectorAll("select").forEach(function(slct) {
        console.log(slct.getAttribute('id'));

    });
    alert("here");

});
//
// SAVE modal values to screen and database
var close = document.querySelector('#save');
close.addEventListener('click', (e) => {

    var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
    // iterate through each modal select element
    document.querySelectorAll('select[id^="17"],select[id^="19"],select[id^="20"] ').forEach(function(item) {
        id = dateSelected+' '+item.getAttribute('id');
        qS = CSS.escape(id);
        // modal selected item
        gradeGroup = item.options[item.selectedIndex].value;
        // select corresponding screen element and assign gradeGroup as a class
        document.querySelector("#" + qS).className = gradeGroup;

        console.log('id='+qS+'   gradeGroup='+gradeGroup);
        // update database
        $.ajax({
            url: 'index.php?r=schedule/assign-grade-group',
            type: 'post',
            data: {_csrf : csrfToken, id : id, gradeGroup: gradeGroup},
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

    })
});

