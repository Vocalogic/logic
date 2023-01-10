'use strict';

/* eslint-disable require-jsdoc, no-unused-vars */

var CalendarList = [];

function CalendarInfo() {
    this.id = null;
    this.name = null;
    this.checked = true;
    this.color = null;
    this.bgColor = null;
    this.borderColor = null;
    this.dragBgColor = null;
}

function addCalendar(calendar) {
    CalendarList.push(calendar);
}

function findCalendar(id) {
    var found;

    CalendarList.forEach(function(calendar) {
        if (calendar.id === id) {
            found = calendar;
        }
    });

    return found || CalendarList[0];
}

function hexToRGBA(hex) {
    var radix = 16;
    var r = parseInt(hex.slice(1, 3), radix),
        g = parseInt(hex.slice(3, 5), radix),
        b = parseInt(hex.slice(5, 7), radix),
        a = parseInt(hex.slice(7, 9), radix) / 255 || 1;
    var rgba = 'rgba(' + r + ', ' + g + ', ' + b + ', ' + a + ')';

    return rgba;
}

(function() {
    var calendar;
    var id = 0;

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'My Calendar';
    calendar.color = 'var(--white-color)';
    calendar.bgColor = 'var(--chart-color1)';
    calendar.dragBgColor = 'var(--chart-color1)';
    calendar.borderColor = 'var(--chart-color1)';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'Company';
    calendar.color = 'var(--white-color)';
    calendar.bgColor = 'var(--chart-color2)';
    calendar.dragBgColor = 'var(--chart-color2)';
    calendar.borderColor = 'var(--chart-color2)';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'Friend';
    calendar.color = 'var(--white-color)';
    calendar.bgColor = 'var(--chart-color3)';
    calendar.dragBgColor = 'var(--chart-color3)';
    calendar.borderColor = 'var(--chart-color3)';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'Birthdays';
    calendar.color = 'var(--white-color)';
    calendar.bgColor = 'var(--chart-color4)';
    calendar.dragBgColor = 'var(--chart-color4)';
    calendar.borderColor = 'var(--chart-color4)';
    addCalendar(calendar);

    calendar = new CalendarInfo();
    id += 1;
    calendar.id = String(id);
    calendar.name = 'National Holidays';
    calendar.color = 'var(--white-color)';
    calendar.bgColor = 'var(--chart-color5)';
    calendar.dragBgColor = 'var(--chart-color5)';
    calendar.borderColor = 'var(--chart-color5)';
    addCalendar(calendar);
})();