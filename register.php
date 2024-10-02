<html>
<head>
    <title>Registration Page</title>
</head>
<body>
    <h2>Create an Account</h2>
    <form method="post" action="register.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirmpw">Confirm Password:</label>
        <input type="password" id="confirmpw" name="confirmpw" required>
        <br>
        <label for="phoneno">Phone No:</label>
        <input type="text" id="phoneno" name="phoneno" required>
        <br>
        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
        </select>
        <br>
        <label for="nationality">Nationality:</label>
        <select id="nationality" name="nationality" required>
                    <option value="Singaporean">Singaporean</option>
                    <option value="Singapore PR">Singapore PR</option>
                    <option value="Foreigner">Foreigner</option>
        </select>
        <br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>
        <br>
        <button type="submit">Register</button>
    </form>
</html>