/**
 *
 */
//alert(window.location.href);
$(document).ready(function () {
    if ($('#time').length) {
        countdown(300);
        //reScrole();
    }
});

//countdown(300);
//setTimeout(function () {
//    window.location = window.location.href;
//}, 60000 * 5);

function reScrole() {
    if (window.location.href.indexOf('page_y') != -1) {
        var match = window.location.href.split('?')[1].split("&")[0].split("=");
        document.getElementsByTagName("body")[0].scrollTop = match[1];
    }
}

function reload() {
    //var page_y = document.getElementsByTagName("body")[0].scrollTop;
    window.location = window.location.href;
    //window.location.href = window.location.href.split('?')[0] + '?page_y=' + page_y;
}

function ownWaitStart() {
    var spin = document.getElementById('own-spin');
    spin.focus();
    spinningWaiter(true);
    $(spin).fadeIn('fast');
}

function ownWaitStop() {
    var spin = document.getElementById('own-spin');
    $(spin).fadeOut('fast', function () {
        spinningWaiter(false);
    });

}

function spinningWaiter(startStop) {
    var opts = {
        lines: 13 // The number of lines to draw
        , length: 0 // The length of each line
        , width: 14 // The line thickness
        , radius: 42 // The radius of the inner circle
        , scale: 1 // Scales overall size of the spinner
        , corners: 1 // Corner roundness (0..1)
        , color: '#007AFF' // #rgb or #rrggbb or array of colors
        , opacity: 0.25 // Opacity of the lines
        , rotate: 0 // The rotation offset
        , direction: 1 // 1: clockwise, -1: counterclockwise
        , speed: 1 // Rounds per second
        , trail: 60 // Afterglow percentage
        , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
        , zIndex: 2e9 // The z-index (defaults to 2000000000)
        , className: 'spinner' // The CSS class to assign to the spinner
        , top: '51%' // Top position relative to parent
        , left: '50%' // Left position relative to parent
        , shadow: false // Whether to render a shadow
        , hwaccel: false // Whether to use hardware acceleration
        , position: 'absolute' // Element positioning

    };
    var target = document.getElementById('own-spin');
    var spinner = new Spinner(opts);
    if (startStop) {
        spinner.spin(target);
    } else {
        spinner.stop();
    }
}

function change(id1, id2) {

    var idNo2 = $('#' + id2);
    var idNo1 = $('#' + id1);

    idNo2.fadeOut("fast", function () {
        idNo1.fadeOut("fast", function () {
            idNo2.fadeIn("fast", function () {
            });
        });
    });
}

function countdown(time) {

    //time brauchen wir sp�ter noch
    var t = time;
    //Tage berechnen
    var d = Math.floor(t / (60 * 60 * 24)) % 24;
    // Stunden berechnen
    var h = Math.floor(t / (60 * 60)) % 24;

    // Minuten berechnen
    // Sekunden durch 60 ergibt Minuten
    // Minuten gehen von 0-59
    //also Modulo 60 rechnen


    m = Math.floor(t / 60) % 60;
    // Sekunden berechnen
    s = t % 60;

    //Zeiten formatieren
    d = (d > 0) ? d + "d " : "";

    h = (h < 10) ? "0" + h : h;
    m = (m < 10) ? "0" + m : m;
    s = (s < 10) ? "0" + s : s;
    // Ausgabestring generieren
    strZeit = m + ":" + s;

    // Falls der Countdown noch nicht zur�ckgez�hlt ist

    //alert('test');
    if (time > 0) {
        //Countdown-Funktion erneut aufrufen
        //diesmal mit einer Sekunde weniger
        window.setTimeout('countdown(' + --time + ')', 1000);
    }
    else {
        //f�hre eine funktion aus oder refresh die seite
        //dieser Teil hier wird genau einmal ausgef�hrt und zwar
        //wenn die Zeit um ist.
        window.location = window.location.href;
        strZeit = "Fertig";
    }
    // Ausgabestring in Tag mit id="id" schreiben
    var a = document.getElementById('time');
    a.innerHTML = strZeit;
}


function asyncKommunikation(path) {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.open("GET", path, true);
    xmlhttp.send();
}

function timmedreload() {
    ownWaitStart();
    setTimeout(function () {
        reload()
    }, 200);
}

function dropdownOpen(id) {
    var obj = document.getElementById(id);
    $(obj).slideDown();
}

function dropdownClose(id) {
    var obj = document.getElementById(id);
    //setTimeout(function () {
    $(obj).slideUp();
    //}, 2000);

}

function readSelect(sObj) {
    with (sObj) return options[selectedIndex].value;
}

function timmedFunction(time, runFunction) {
    setTimeout(function () {
        runFunction()
    }, time);
}

function isKeyEvent(obj, keyCode) {

    obj.onkeyup = function (event) {
        return event.keyCode == keyCode;

    };
}

function rowSlideUp(id) {
    var obj = document.getElementById(id);
    $(obj).closest('tr')
        .children('td')
        .animate({padding: 0})
        .wrapInner('<div />')
        .children()
        .slideUp(function () {
            $(this).closest('tr').remove();
        });
    return false;
}
