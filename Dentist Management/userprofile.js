document.addEventListener('DOMContentLoaded', function() {
    let originalData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        description: document.getElementById('description').value,
        passwordChanged: false // Track if a new password is provided
    };

    function handleSubmit(event) {
        event.preventDefault();

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmpw').value;
        const description = document.getElementById('description').value;

        var namePattern = /^[A-Za-z\s]+$/;
        if (!namePattern.test(name)) {
            alert("Name must contain only alphabet characters and spaces.");
            return false;
        }

        var emailPattern = /^[\w.-]+@([\w-]+\.)+[A-Za-z]{2,}$/;
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            return false;
        }

        // Validate Password if it was provided (only if user attempts to change it)
        var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
        if (password !== "" || confirmPassword !== "") {
            if (!passwordPattern.test(password)) {
                alert("Password should be at least 8 characters long and contain one special character !@#$%^&*, one lowercase letter, one uppercase letter, and one digit.");
                return false;
            }

            // Validate Confirm Password
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            originalData.passwordChanged = true; // Mark as changed if valid
        }

        // Check if any value has changed
        if (
            name !== originalData.name || 
            email !== originalData.email || 
            description !== originalData.description || 
            originalData.passwordChanged // Only flag for change if the password was updated
        ) {
            alert('Profile updated!');
        } else {
            // Prevent form submission if no changes were made
            event.preventDefault();
            alert('No changes made to the profile.');
        }

       
    }
    document.getElementById('userprofile_form').addEventListener('submit', handleSubmit);
});