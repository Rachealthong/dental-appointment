document.addEventListener('DOMContentLoaded', function() {
    fetch('../Elements/navbar.php')
        .then(response => response.text())
        .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('../Elements/footer.html')
        .then(response => response.text())
        .then(data => document.getElementById('footer').innerHTML = data);

    window.setFormAction = function(actionUrl, appointmentId) {
        const form = document.getElementById('appointment_' + appointmentId);
        if (form) {
            form.action = actionUrl;
            form.submit();
        } else {
            console.error('Form with ID "appointment_' + appointmentId + '" not found.');
        }
    };

    // Open tab function
    window.openTab = function(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.classList.add("active");
    };

    // Set default active tab to "Upcoming"
    document.querySelector('.tablink').click();
});
