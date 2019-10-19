function process(jsonString) {
    'use strict';

    let a = JSON.stringify(jsonString);
    if (!(a === undefined)) {
        let Status = JSON.parse(jsonString);
        let bestellungen = document.getElementById("Bestellungen");
        while (bestellungen.firstChild) {
            bestellungen.removeChild(bestellungen.firstChild);
        }
        let i;
        for (i in Status) {
            let p = document.createElement("p");
            let text = document.createTextNode(Status[i].PizzaName + ": " + Status[i].Status);
            let div = document.getElementById('Bestellungen');

            p.appendChild(text);
            div.appendChild(p);
        }
    }
}

let request = new XMLHttpRequest();

function requestData() { // requests the data asynchronously
    'use strict';

    request.open("GET", "KundenStatus.php"); // url for HTTP-GET
    request.onreadystatechange = processData; // assign callback handler
    request.send(null); // send request
}


function processData() {
    'use strict';

    if (request.readyState === 4) { // DONE
        if (request.status === 200) {   // OK
            if (request.responseText != null)
                process(request.responseText); // process data
            else console.error("Dokument ist leer");
        } else console.error("Uebertragung fehlgeschlagen");
    } else ; // transmission is still running
}

function onloadPage() {
    'use strict';

    window.setInterval(requestData, 2000);
}