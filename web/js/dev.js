// ref: https://getbootstrap.com/docs/4.0/components/modal/
// ref: http://thednp.github.io/bootstrap.native/
//
//

// When the user scrolls the page, execute myFunction
window.onscroll = function() {myFunction()};
// Get the header
var header = document.getElementById("myHeader");
var sticky = header.offsetTop;
// Get the offset position of the static portion
// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}

/*
* _layoutSchedule click a checkbox ... write to dB table
*/
function toDb(id, gradeGroup) {

    var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
    $.ajax({
        url: 'index.php?r=schedule/update-schd-layouts',
        type: 'post',
        data: {_csrf : csrfToken, id : id, gradeGroup: gradeGroup},
        async: true,
        cache: false
    })
    .done(function (gradeGroup) {
        })
    .fail(function (x, e) {
            alert('The call to the server side failed. ' + x.responseText);

    });
}
//
// Click a time-diamond single checkbox
document.querySelectorAll('input[type=checkbox]').forEach( (chck) => {
    chck.addEventListener('click', (e) => {
        id =chck.parentNode.getAttribute('id');
        if (chck.checked) {
            var gradeGroup = document.querySelector('#requirednorounds-gradegroup').value;
        } else {
            var gradeGroup = null;
        }
        toDb(id, gradeGroup)
    });
});
//
// Month or Day collection of checkboxes
document.querySelectorAll('button').forEach( (dom) => {
    dom.addEventListener('click', (e) => {
        console.log(dom.getAttribute('id'));
        var bttn = dom.getAttribute('id');
        var bttnId = bttn.split(" ")
        // ... month wide: "2020-05 ^1^Fri"
        // ... day wide    "2020-05-08 ^1^Fri"
        document.querySelectorAll('div[id*="'+bttnId[0]+'"][id*="'+bttnId[1]+'"]').forEach ((div) => {
            var id = div.getAttribute('id');
            console.log(bttn, id);
                var ckbx = document.querySelector('div[id="'+id+'"] input[type="checkbox"]');
                if (ckbx !== null) {
                    var gradeGroup = document.querySelector('#requirednorounds-gradegroup').value;
                    if (ckbx.checked) {
                        ckbx.checked = false;
                        gradeGroup=null;
                    } else {
                        ckbx.checked = true;
                    }
                    toDb(id, gradeGroup);
                }
        });
        // count number of checked boxes
        chckBx = document.querySelectorAll('div#calendar input:checked');
        //console.log(chckBx.length);
        document.querySelectorAll('table#layoutGames tbody tr td')[4].innerHTML = chckBx.length;

    });
});
//
// Upon each checkbox click; calculation the number of checked boxes
document.querySelectorAll('input[type=checkbox]').forEach( (dom) => {
    dom.addEventListener('click', (e) => {
        chckBx = document.querySelectorAll('div#calendar input:checked');
        document.querySelectorAll('table#layoutGames tbody tr td')[4].innerHTML = chckBx.length;

    });
});





// count number of checked boxes
chckBx = document.querySelectorAll('div#calendar input:checked');
document.querySelectorAll('table#layoutGames tbody tr td')[4].innerHTML = chckBx.length;
