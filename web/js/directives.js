const directive = document.getElementById('directives-directive');
directive.addEventListener('change',(event) => {

    var dirDDL = document.querySelector('#directives-teams_id');


    if (directive.value == 'return') {
        dirDDL.parentElement.style.display='block';
        document.querySelector("#directives-teams_id").parentElement.classList.add("required")
    } else {
        document.querySelector("#directives-teams_id").parentElement.classList.remove("required")
        dirDDL.parentElement.style.display='none';
    }
});

document.getElementById("directives-directive").dispatchEvent(new Event("change"));
