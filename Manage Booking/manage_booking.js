document.addEventListener('DOMContentLoaded', function() {
    fetch('../Elements/navbar.php')
    .then(response => response.text())
    .then(data => document.getElementById('navbar').innerHTML = data);
    fetch('../Elements/footer.html')
    .then(response => response.text())
    .then(data => document.getElementById('footer').innerHTML = data);
    
    window.setFormAction = function(actionUrl, appointmentId) {
        // Get the form by its ID which is dynamically set with appointment ID
        const form = document.getElementById('appointment_' + appointmentId);
        
        if (form) {
            // Set the form's action attribute to either cancel.php or reschedule.php based on button click
            form.action = actionUrl;
            
            // Submit the form
            form.submit();
        } else {
            console.error('Form with ID "appointment_' + appointmentId + '" not found.');
        }
    };
});
