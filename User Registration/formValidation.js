function validateForm() {
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmpw").value;
    var phoneNo = document.getElementById("phoneno").value;
    var dob = document.getElementById("dob").value;

    // Name validation: alphabet characters and spaces
    var namePattern = /^[A-Za-z\s]+$/;
    if (!namePattern.test(name)) {
        alert("Name must contain only alphabet characters and spaces.");
        return false;
    }

    // Email validation
    var emailPattern = /^[\w.-]+@([\w-]+\.){1,3}[A-Za-z]{2,3}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    // Password validation
    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
    if (!passwordPattern.test(password)) {
        alert("Password should be at least 8 characters long and contain one of the special characters !@#$%^&*, one lowercase letter, one uppercase letter, and one digit.");
        return false;
    } 

    // Confirm Password validation
    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    // Phone number validation
    var phonePattern = /^\d{8}$/;
    if (!phonePattern.test(phoneNo)) {
        alert("Phone number should be 8 digits long.");
        return false;
    }

    // DOB validation: cannot be today or in the future
    var today = new Date();
    var selectedDate = new Date(dob);
    if (selectedDate >= today) {
        alert("Date of Birth cannot be today or in the future.");
        return false;
    }

    return true;
}