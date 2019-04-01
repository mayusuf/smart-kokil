var d = new Date();

var weekday = new Array(7);

weekday[0] = "Mon";
weekday[1] = "Tue";
weekday[2] = "Wed";
weekday[3] = "Thu";
weekday[4] = "Fri";
weekday[5] = "Sat";
weekday[6] = "Sun";

var monthname = new Array(12);

monthname[0] = "Jan";
monthname[1] = "Feb";
monthname[2] = "Mar";
monthname[3] = "Apr";
monthname[4] = "May";
monthname[5] = "Jun";
monthname[6] = "July";
monthname[7] = "Aug";
monthname[8] = "Sep";
monthname[9] = "Oct";
monthname[10] = "Nov";
monthname[11] = "Dec";


var date = d.getDate();
var day = d.getDay();
var mon = d.getMonth();
//            var date = d.getDate();
//            var date = d.getDate();

document.getElementById("date-box1").innerHTML = date + "  " + weekday[day];
document.getElementById("date-box2").innerHTML = monthname[mon];

var timerVar = setInterval(countTimer, 1000);

var totalSeconds = (d.getHours() * 3600) + (d.getMinutes() * 60) + d.getSeconds();

function countTimer() {

    ++totalSeconds;
    var hour = Math.floor(totalSeconds / 3600);
    var minute = Math.floor((totalSeconds - hour * 3600) / 60);
    var seconds = totalSeconds - (hour * 3600 + minute * 60);

    document.getElementById("date-box3").innerHTML = hour;
    document.getElementById("date-box4").innerHTML = minute + ":" + seconds;
}

//            document.getElementById("date-box3").innerHTML = date;
//            document.getElementById("date-box4").innerHTML = date;


