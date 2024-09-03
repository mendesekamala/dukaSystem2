function formValidation() {    
    const form = document.forms["signUpForm"];
    const username = form["username"].value;
    const password = form["password"].value
    let isValid = true;  // Flag to track validation status
    const messages = [];  // Array to collect error messages

    
    // Validate username
    if (username.length > 8) {
        messages.push("Username should not exceed 8 characters.");
        isValid = false;
    } else {
        const usernameRegex = /^[A-Za-z]+$/;
        if (!usernameRegex.test(username)) {
            messages.push("Username should contain only letters with nothing else even.");
            isValid = false;
        }
    }


    // Validate password
    if (password.length < 10) {
        messages.push("Password should be at least 10 characters long.");
        isValid = false;
    } 
    // Validate password
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@!#\$%\^\&*\)\(+=._-])[A-Za-z\d@!#\$%\^\&*\)\(+=._-]+$/;
    if (!passwordRegex.test(password)) {
        messages.push("Password should be alphanumeric with special characters.");
        isValid = false;
    }
    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        messages.push("Please enter a valid email address.");
        isValid = false;
    }           

    // If there are any validation messages, display them and return false
    if (!isValid) {
        alert(messages.join("\n"));
        return false;
    }

    return true; // Allow form submission
}
