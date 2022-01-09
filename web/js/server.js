  var evtSource = new EventSource('index.php?r=messages/server-side-sender');
  console.log(evtSource.withCredentials);
  console.log(evtSource.readyState);
  console.log(evtSource.url);
  var eventList = document.getElementById('eventList');


  evtSource.onopen = function() {
    console.log("Connection to server opened.");
  };
  evtSource.onmessage = function(e) {
    params = JSON.parse(e.data);
    //console.log('params='+params);
    if (params.error == 'No messages availble to send.') {
      document.querySelector('#error').innerHTML=params.error;
      evtSource.close();
    }

    if (params.totalMsg) {
      document.querySelector("#iMsg").innerHTML=params.iMsg;
      document.querySelector("#totalMsg").innerHTML=params.totalMsg;
      document.querySelector("#pctMsg").innerHTML="  "+params.pctMsg+"%";
      document.querySelector(".msg > div:nth-child(1) > span:nth-child(1)").style.width=params.pctMsg+'%';
    }
    //var newElement = document.createElement("li");
    //newElement.textContent = "message: " + e.data+'%';
    //eventList.appendChild(newElement);
    //console.log(params.pctMsg);

    if (params.pctMsg == 100 ) {
           console.log("**** close connection *****");
           evtSource.close();
    }
  };
  evtSource.onerror = function() {
    console.log("EventSource failed.");
  };



const zbutton = document.querySelector('#closeSse');
console.log(zbutton.outerHTML);
zbutton.addEventListener('click',(event) => {
    evtSource.close();
    alert("close");
});
