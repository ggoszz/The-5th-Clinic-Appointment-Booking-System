<?php
/**
 * SQL Injection Assignment - PHP Backend Examples
 * This file demonstrates both vulnerable and secure database operations
 * 
 * IMPORTANT: The vulnerable code is for educational purposes only!
 * NEVER use vulnerable code in production systems!
 */

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinicDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// =============================================================================
// PART A: VULNERABLE SELECT QUERY (FOR DEMONSTRATION ONLY)
// =============================================================================

function vulnerableLogin($conn, $username, $password) {
    echo "<h2>🚨 VULNERABLE LOGIN (Part A) - DON'T USE IN PRODUCTION!</h2>";
    
    // VULNERABLE: Direct string concatenation
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    echo "<strong>Generated SQL Query:</strong><br>";
    echo "<code>" . htmlspecialchars($sql) . "</code><br><br>";
    
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo "<div style='color: green;'>✅ Login successful!</div>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";
        
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["password"]) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div style='color: red;'>❌ Invalid credentials</div>";
    }
    
    echo "<hr>";
}

// =============================================================================
// PART B: VULNERABLE UPDATE QUERY (FOR DEMONSTRATION ONLY)
// =============================================================================

function vulnerableUpdate($conn, $patientId, $newPhone, $newEmail) {
    echo "<h2>🚨 VULNERABLE UPDATE (Part B) - DON'T USE IN PRODUCTION!</h2>";
    
    // Build SET clause
    $setParts = [];
    if (!empty($newPhone)) {
        $setParts[] = "phone = '$newPhone'";
    }
    if (!empty($newEmail)) {
        $setParts[] = "email = '$newEmail'";
    }
    
    if (empty($setParts)) {
        echo "<div style='color: red;'>No fields to update</div>";
        return;
    }
    
    // VULNERABLE: Direct string concatenation
    $sql = "UPDATE patients SET " . implode(', ', $setParts) . " WHERE patient_id = '$patientId'";
    
    echo "<strong>Generated SQL Query:</strong><br>";
    echo "<code>" . htmlspecialchars($sql) . "</code><br><br>";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div style='color: green;'>✅ Update successful!</div>";
        echo "<div>Affected rows: " . $conn->affected_rows . "</div>";
        
        // Show current patient data
        $selectSql = "SELECT * FROM patients";
        $result = $conn->query($selectSql);
        if ($result && $result->num_rows > 0) {
            echo "<h3>Current Patient Data:</h3>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Phone</th><th>Email</th></tr>";
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["patient_id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<div style='color: red;'>❌ Error updating record: " . $conn->error . "</div>";
    }
    
    echo "<hr>";
}

// =============================================================================
// PART C: SECURE SELECT QUERY WITH PREPARED STATEMENTS
// =============================================================================

function secureLogin($conn, $username, $password) {
    echo "<h2>🔒 SECURE LOGIN (Part C) - RECOMMENDED APPROACH</h2>";
    
    // SECURE: Prepared statement
    $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?";
    
    echo "<strong>Prepared Statement:</strong><br>";
    echo "<code>" . htmlspecialchars($sql) . "</code><br>";
    echo "<strong>Parameters:</strong> '" . htmlspecialchars($username) . "', '" . htmlspecialchars($password) . "'<br><br>";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // Bind parameters (s = string)
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            echo "<div style='color: green;'>✅ Secure login successful!</div>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Username</th></tr>";
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<div style='background: #d1ecf1; padding: 10px; margin-top: 10px; border-radius: 5px;'>";
            echo "<strong>🔒 Security Features Active:</strong><br>";
            echo "• SQL structure pre-compiled<br>";
            echo "• User input treated as data only<br>";
            echo "• Special characters automatically escaped<br>";
            echo "• Type-safe parameter binding<br>";
            echo "</div>";
            
        } else {
            echo "<div style='color: orange;'>❌ Invalid credentials</div>";
            
            // Check if this was an injection attempt
            if (strpos($username, "'") !== false || strpos($username, "--") !== false || 
                strpos($username, "OR") !== false || strpos($username, "UNION") !== false) {
                echo "<div style='background: #d1ecf1; padding: 10px; margin-top: 10px; border-radius: 5px;'>";
                echo "<strong>🛡️ SQL Injection Attempt Blocked!</strong><br>";
                echo "• Malicious input detected: " . htmlspecialchars($username) . "<br>";
                echo "• Input treated as literal string<br>";
                echo "• No SQL structure modified<br>";
                echo "• Database security maintained<br>";
                echo "</div>";
            }
        }
        
        $stmt->close();
    } else {
        echo "<div style='color: red;'>❌ Error preparing statement: " . $conn->error . "</div>";
    }
    
    echo "<hr>";
}

// =============================================================================
// SECURE UPDATE WITH PREPARED STATEMENTS (BONUS)
// =============================================================================

function secureUpdate($conn, $patientId, $newPhone, $newEmail) {
    echo "<h2>🔒 SECURE UPDATE (Bonus) - RECOMMENDED APPROACH</h2>";
    
    // Build dynamic query safely
    $setParts = [];
    $types = "";
    $params = [];
    
    if (!empty($newPhone)) {
        $setParts[] = "phone = ?";
        $types .= "s";
        $params[] = $newPhone;
    }
    
    if (!empty($newEmail)) {
        $setParts[] = "email = ?";
        $types .= "s";
        $params[] = $newEmail;
    }
    
    if (empty($setParts)) {
        echo "<div style='color: red;'>No fields to update</div>";
        return;
    }
    
    // Add patient ID parameter
    $types .= "i"; // i = integer
    $params[] = (int)$patientId;
    
    $sql = "UPDATE patients SET " . implode(', ', $setParts) . " WHERE patient_id = ?";
    
    echo "<strong>Prepared Statement:</strong><br>";
    echo "<code>" . htmlspecialchars($sql) . "</code><br>";
    echo "<strong>Parameters:</strong> " . implode(", ", array_map('htmlspecialchars', $params)) . "<br><br>";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            echo "<div style='color: green;'>✅ Secure update successful!</div>";
            echo "<div>Affected rows: " . $stmt->affected_rows . "</div>";
        } else {
            echo "<div style='color: red;'>❌ Error executing update: " . $stmt->error . "</div>";
        }
        
        $stmt->close();
    } else {
        echo "<div style='color: red;'>❌ Error preparing statement: " . $conn->error . "</div>";
    }
    
    echo "<hr>";
}

// =============================================================================
// MAIN EXECUTION (FOR TESTING)
// =============================================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<html><head><title>SQL Injection Assignment Results</title></head><body>";
    echo "<h1>SQL Injection Assignment - PHP Backend Results</h1>";
    
    // Part A: Vulnerable SELECT
    if (isset($_POST['action']) && $_POST['action'] === 'vulnerable_login') {
        vulnerableLogin($conn, $_POST['username'], $_POST['password']);
    }
    
    // Part B: Vulnerable UPDATE
    if (isset($_POST['action']) && $_POST['action'] === 'vulnerable_update') {
        vulnerableUpdate($conn, $_POST['patient_id'], $_POST['new_phone'], $_POST['new_email']);
    }
    
    // Part C: Secure SELECT
    if (isset($_POST['action']) && $_POST['action'] === 'secure_login') {
        secureLogin($conn, $_POST['username'], $_POST['password']);
    }
    
    // Bonus: Secure UPDATE
    if (isset($_POST['action']) && $_POST['action'] === 'secure_update') {
        secureUpdate($conn, $_POST['patient_id'], $_POST['new_phone'], $_POST['new_email']);
    }
    
    echo "</body></html>";
}

// =============================================================================
// EXAMPLE USAGE AND TESTING
// =============================================================================

// Uncomment the following lines to test directly:

/*
echo "<h1>SQL Injection Assignment - Testing Examples</h1>";

// Test normal login
echo "<h2>Testing Normal Login:</h2>";
secureLogin($conn, "john", "password1");

// Test injection attempts
echo "<h2>Testing Injection Attempts:</h2>";
echo "<h3>1. Comment Injection:</h3>";
vulnerableLogin($conn, "admin' --", "anything");
secureLogin($conn, "admin' --", "anything");

echo "<h3>2. OR Condition Injection:</h3>";
vulnerableLogin($conn, "' OR '1'='1", "anything");
secureLogin($conn, "' OR '1'='1", "anything");

echo "<h3>3. UNION Attack:</h3>";
vulnerableLogin($conn, "' UNION SELECT id, username, password FROM users --", "anything");
secureLogin($conn, "' UNION SELECT id, username, password FROM users --", "anything");
*/

$conn->close();
?>

<!-- 
HTML form for testing (uncomment to use):

<html>
<head>
    <title>SQL Injection Testing Interface</title>
</head>
<body>
    <h1>SQL Injection Assignment - Testing Interface</h1>
    
    <h2>Part A: Vulnerable Login (SELECT)</h2>
    <form method="POST">
        <input type="hidden" name="action" value="vulnerable_login">
        Username: <input type="text" name="username" placeholder="Try: admin' --"><br><br>
        Password: <input type="password" name="password" placeholder="anything"><br><br>
        <input type="submit" value="Vulnerable Login">
    </form>
    
    <h2>Part C: Secure Login (SELECT)</h2>
    <form method="POST">
        <input type="hidden" name="action" value="secure_login">
        Username: <input type="text" name="username" placeholder="Try: admin' --"><br><br>
        Password: <input type="password" name="password" placeholder="anything"><br><br>
        <input type="submit" value="Secure Login">
    </form>
    
    <h2>Part B: Vulnerable Update</h2>
    <form method="POST">
        <input type="hidden" name="action" value="vulnerable_update">
        Patient ID: <input type="text" name="patient_id" placeholder="Try: 1' OR '1'='1"><br><br>
        New Phone: <input type="text" name="new_phone" placeholder="555-HACK"><br><br>
        New Email: <input type="text" name="new_email" placeholder="hacked@evil.com"><br><br>
        <input type="submit" value="Vulnerable Update">
    </form>
</body>
</html>
-->
