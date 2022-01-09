
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
document.querySelectorAll('button').forEach( (bttn) => {
  bttn.addEventListener('click', (event) => {

    // parent=bttn.getAttribute('data-parent');
    // lChild=bttn.getAttribute('data-lChild');
    // rChild=bttn.getAttribute('data-rChild');
    // console.log($(this).attr('id'));
    // $lTeam = document.querySelector("#node_"+lChild).textContent;
    // $rTeam = document.querySelector("#node_"+rChild).textContent;
    console.log(bttn.getAttribute('id'));
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-body').load("index.php?r=brackets/edit-node&node_id="+bttn.getAttribute('id'));
    $('#modal').modal({show:true, backdrop:'static'});
 });
});
