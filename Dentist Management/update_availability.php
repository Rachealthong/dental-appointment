<?php
session_start();
include '../dbconnect.php';
$dentist_id = $_SESSION['dentist_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Booking</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
</head>
<body>
<div id="wrapper">
    <header>
        <div id="navbar"></div>
    </header>

    <h1 style="text-align: center;">Dentist's Schedule</h1>
    <?php echo "<input type='hidden' id='dentist_id' value='$dentist_id'>"; ?>
    <div id="calendar"></div>
    <input class='button' type='reset' value='Reset' onclick='window.location.reload();'>
    <button class = 'button' id="submit-button">Submit Unavailable Slots</button>

    <div id="footer"></div>

    <script>
        fetch('navbar_dentist.php')
            .then(response => response.text())
            .then(data => document.getElementById('navbar').innerHTML = data);
        fetch('footer_dentist.html')
        .then(response => response.text())
        .then(data => document.getElementById('footer').innerHTML = data);

        document.addEventListener('DOMContentLoaded', function () {
            const dentist_id = document.getElementById('dentist_id').value;
            const calendarEl = document.getElementById('calendar');
            let selectedSlots = [];

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                timeZone: 'Asia/Singapore',
                selectable: true,
                select: function (info) {
                    const overlappingEvents = calendar.getEvents().filter(event =>
                        event.start < info.end && event.end > info.start && event.backgroundColor === 'red'
                    );

                    if (overlappingEvents.length > 0) {
                        alert('Cannot select unavailable (red) slots.');
                        calendar.unselect();
                        return;
                    }

                    handleSlotSelection(info.startStr, info.endStr, 'red');
                },
                events: function (fetchInfo, successCallback, failureCallback) {
                    fetchAvailableSlots(dentist_id, successCallback, failureCallback);
                },
                eventClick: function (info) {
                    if (info.event.backgroundColor === 'green' || info.event.backgroundColor === 'yellow') {
                        toggleSlotAvailability(info.event);
                    } else {
                        alert('This slot is unavailable and cannot be toggled.');
                    }
                },
                slotDuration: '00:30:00', 
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5],
                    startTime: '09:00',
                    endTime: '18:00',
                },
                slotMinTime: '09:00:00', 
                slotMaxTime: '18:00:00',
                height: 'auto',
                contentHeight: 600,
            });

            calendar.render();

            document.getElementById('submit-button').addEventListener('click', function () {
                if (selectedSlots.length === 0) {
                    alert('No slots selected!');
                    return;
                }

                updateMultipleSlots(dentist_id, selectedSlots)
                    .then(() => {
                        alert('Slots updated successfully!');
                        selectedSlots = [];
                        calendar.refetchEvents();
                    })
                    .catch((error) => {
                        alert('Error updating slots: ' + error);
                    });
            });

            function handleSlotSelection(startStr, endStr, color) {
                const [date, time] = startStr.split('T');
                selectedSlots.push({ date, time });

                calendar.addEvent({
                    start: startStr,
                    end: endStr,
                    backgroundColor: color,
                    display: 'background'
                });
            }

            function toggleSlotAvailability(event) {
                const { start, backgroundColor } = event;
                const [date, time] = start.toISOString().split('T');
                const slotIndex = selectedSlots.findIndex(slot => slot.date === date && slot.time === time.slice(0, 5));
                if (backgroundColor === 'green') {
                    event.setProp('backgroundColor', 'yellow');
                    if (slotIndex === -1) selectedSlots.push({ date, time: time.slice(0, 5) });
                } else if (backgroundColor === 'yellow') {
                    event.setProp('backgroundColor', 'green');
                    if (slotIndex !== -1) selectedSlots.splice(slotIndex, 1);
                }
            }

            function fetchAvailableSlots(dentist_id, successCallback, failureCallback) {
                fetch(`fetch_available_slots.php?dentist_id=${encodeURIComponent(dentist_id)}`)
                    .then(response => response.text())
                    .then(data => {
                        const params = new URLSearchParams(data);

                        const slots = [];
                        const dates = params.getAll('date[]');
                        const times = params.getAll('time[]');
                        const statuses = params.getAll('status[]');

                        for (let i = 0; i < dates.length; i++) {
                            slots.push({
                                title: '',
                                start: `${dates[i]}T${times[i]}:00`,
                                end: `${dates[i]}T${add30Minutes(times[i])}`,
                                backgroundColor: statuses[i] === '1' ? 'green' : 'red',
                                textColor: 'white',
                                extendedProps: { availabilityStatus: statuses[i] === '1' }
                            });
                        }

                        successCallback(slots);
                    })
                    .catch(error => failureCallback(error));
            }

            function add30Minutes(time) {
                const [hours, minutes] = time.split(':').map(Number);
                const date = new Date();
                date.setHours(hours);
                date.setMinutes(minutes + 30);
                return date.toTimeString().slice(0, 5);
            }
            function updateMultipleSlots(dentist_id, slots) {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'update_multiple_slots.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onload = function () {
                        if (xhr.status === 200) resolve();
                        else reject(xhr.responseText);
                    };

                    xhr.onerror = function () {
                        reject('Request failed');
                    };

                    // Convert slots array to URL-encoded format
                    const slotParams = slots.map(slot => 
                        `date[]=${encodeURIComponent(slot.date)}&time[]=${encodeURIComponent(slot.time)}`
                    ).join('&');

                    const payload = `dentist_id=${encodeURIComponent(dentist_id)}&${slotParams}`;
                    xhr.send(payload);
                    console.log(payload);
                });
            }
        });

    </script>
</div>
</body>
</html>
