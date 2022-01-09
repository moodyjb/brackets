
// ref: https://getbootstrap.com/docs/4.0/components/modal/
// ref: http://thednp.github.io/bootstrap.native/
document.querySelectorAll('.slot').forEach(function(item) {

    item.addEventListener('click', (event) => {

        gameDate = item.parentElement.parentElement.getElementsByTagName("span")[0].textContent;
        gameTime = item.parentElement.getElementsByTagName("span")[0].textContent;

        var postphoned = document.querySelector('.reSchedule');
        if(postphoned !== null) {
            //console.log(item.innerHTML);
            //item.innerHtml = "99-66";
            item.innerHTML = postphoned.textContent;
            item.style.color='#000';
            item.style.backgroundColor='#fff';
            postphoned.innerHTML = "-";
            postphoned.classList.remove('reSchedule');
            return false;
        }

        console.log(item.innerHTML);
        teams=item.innerHTML.match(/\d+/g);
        if (teams === null) {
           teams = ['', ''];
        }
        diamond = item.id.split("^")[1];

        document.querySelector('h5.modal-title').innerHTML = "<h2>"+gameDate + "<br>"+gameTime +   "<br>Diamond:"+diamond+"</h2>";
        //document.querySelector('#home').value = teams[0];
        //document.querySelector('#vistor').value = teams[1];

        $('#myModal').modal({show:true, backdrop:'static'});
        //document.querySelector('#error').innerHTML ='';

        function rescheduleButton(e) {
            // get values from modal
            home = document.querySelector('#home').value;
            vistor = document.querySelector('#vistor').value;
            // highlight text
            item.classList.add('reSchedule');
            item.innerHTML = "<span style='color:#fff; background-color:#000'>"+home+"-"+vistor+"</span>";

            // have to remove to avoid accumulating events
            document.querySelector("#reschedule").removeEventListener('click',rescheduleButton);
        };

        function preventClose(e) {
             e.preventDefault();
        }
        function addButton(e) {
            home = document.querySelector('#home').value;
            vistor = document.querySelector('#vistor').value;
            gradeGroup = document.querySelector('#gradeGroup').value;
            document.querySelector('#error').innerHTML ='';
            if (!gradeGroup || !home || !vistor) {
                document.querySelector('#error').innerHTML = 'Home, Vistor, and GradeGroup required';
                // Modal should remain exposed
                $('#myModal').on('hide.bs.modal', preventClose);
               return;
            }
            // get values from modal
            home = document.querySelector('#home').value;
            vistor = document.querySelector('#vistor').value;

            // highlight texti
            item.innerHTML = home+"-"+vistor;

            // have to remove to avoid accumulating events
            document.querySelector("#add").removeEventListener('click',addButton);
            $('#myModal').off('hide.bs.modal', preventClose);
        };
        function updateButton(e) {
            home = document.querySelector('#home').value;
            vistor = document.querySelector('#vistor').value;
            document.querySelector('#error').innerHTML ='';
            if (!home || !vistor) {
                document.querySelector('#error').innerHTML = 'Home and Vistor required';
                // Modal should remain exposed
                $('#myModal').on('hide.bs.modal', preventClose);
               return;
            }
            // get values from modal
            home = document.querySelector('#home').value;
            vistor = document.querySelector('#vistor').value;

            // highlight texti
            item.innerHTML = home+"-"+vistor;

            // have to remove to avoid accumulating events
            document.querySelector("#add").removeEventListener('click',addButton);
            $('#myModal').off('hide.bs.modal', preventClose);
        };

        function cancelButton(e) {
            item.innerHTML = '-';
            // have to remove to avoid accumulating events
            document.querySelector("#cancel").removeEventListener('click',cancelButton);
        };
        function closeButton(e) {
            $('#myModal').off('hide.bs.modal', preventClose);
        };

        document.querySelector("#close").addEventListener('click',closeButton);
        document.querySelector("#reschedule").addEventListener('click',rescheduleButton);
        document.querySelector("#add").addEventListener('click',addButton);
        document.querySelector("#cancel").addEventListener('click',cancelButton);
        document.querySelector("#update").addEventListener('click',updateButton);

    });
});
