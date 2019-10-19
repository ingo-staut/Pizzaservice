function pizzaOnClick(pizzaName, pizzaNummer, pizzaPreis) {
    "use strict";

    let shoppingcart = document.getElementById('shoppingcart');
    let opt = document.createElement('option');
    opt.value = pizzaNummer;
    opt.text = pizzaName;
    opt.setAttribute('preis', pizzaPreis);
    shoppingcart.appendChild(opt);
    preisBerechnung();
    isBestellungErlaubt();
}

function preisBerechnung() {
    "use strict";
    
    let shoppingcart = document.getElementById('shoppingcart');
    let totalPrice_tmp = 0.0;
    for (let i = 0; i < shoppingcart.length; i++) {
        totalPrice_tmp += parseFloat(shoppingcart[i].getAttribute('preis'));
    }
    let div = document.getElementById('preisDiv');
    if (document.getElementById('totalPrice')) {
        let p = document.getElementById('totalPrice');
        p.parentNode.removeChild(p);
    }
    let p = document.createElement('p');
    p.setAttribute('id', 'totalPrice');
    let text = document.createTextNode(totalPrice_tmp.toFixed(2) + " €");
    p.appendChild(text);
    div.appendChild(p);
}

function deleteAll() {
    "use strict";
    
    document.getElementById('shoppingcart').options.length = 0;
    preisBerechnung();
    disableButtonBestellung();
}

function deleteAllSelected() {
    "use strict";
    
    let x = document.getElementById("shoppingcart");
    for (let i = x.options.length - 1; i >= 0; i--) {
        x.remove(x.selectedIndex);
    }
    preisBerechnung();
    isBestellungErlaubt();
}

function selectAllPizzaInshoppingcart() {
    "use strict";
    
    let selectBox = document.getElementById("shoppingcart");

    for (let i = 0; i < selectBox.options.length; i++) {
        selectBox.options[i].selected = true;
    }
}


function enableButtonBestellung() {
    "use strict";
    
    document.getElementById('orderingbutton').disabled = false;
}

function disableButtonBestellung() {
    "use strict";
    
    document.getElementById('orderingbutton').disabled = true;
}

function isBestellungErlaubt() {
    "use strict";

    if(document.getElementById('addressfield').value === "" || document.getElementById('shoppingcart').options.length === 0){
        disableButtonBestellung();
    }
    else {
        enableButtonBestellung();
    }
}

// schaltet zwischen Burger und normalem Menü um
function switchNavBar() {
    'use strict';
    
    let x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
