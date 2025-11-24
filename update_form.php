<?php
include 'session.php';
include 'connection.php';

if (!$dbhandle) {
    die("Database connection failed: " . htmlspecialchars(mysqli_connect_error()));
}

$user = [
    'id' => '',
    'lname' => '',
    'fname' => '',
    'mname' => '',
    'gender' => '', 
    'birthday' => '',
    'cno' => '',
];

// Check if the user ID is stored in the session
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $id = (int)$_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $dbhandle->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }
    $stmt->close();
} else {
    echo "<script>
            alert('No user session found. Please log in.');
            window.location.href = 'login.php';
          </script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $id = (int)$_SESSION['user_id'];
    $lname = $_POST['lname'] ?? '';
    $fname = $_POST['fname'] ?? '';
    $mname = $_POST['mname'] ?? '';
    $gender = $_POST['gender'] ?? 'm';
    $birthday = $_POST['birthday'] ?? '';
    $contact_no = $_POST['cno'] ?? '';
    
    $sql = "UPDATE users SET 
            lname = ?, 
            fname = ?, 
            mname = ?, 
            gender = ?, 
            birthday = ?, 
            cno = ? 
            WHERE id = ?";
    
    $stmt = $dbhandle->prepare($sql);
    $stmt->bind_param("ssssssi", $lname, $fname, $mname, $gender, $birthday, $contact_no, $id);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('User Information updated successfully!');
                window.location.href = 'Settings.php';
              </script>";
    } else {
        echo "<script>alert('User Information update failed: " . $dbhandle->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Update Profile</title>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #dbeafe, #f0f9ff);
            height: 100vh;
            overflow: hidden;
        }
    </style>
</head>
<body class="flex flex-col h-screen">

<!-- HEADER -->
<header class="bg-white/70 backdrop-blur shadow-sm py-3 px-6 flex items-center justify-between border-b flex-shrink-0">
    <div class="flex items-center gap-3">
        <img src="images/stresssense_logo.png" class="w-10 h-10" alt="Logo">
        <span class="text-xl font-semibold tracking-wide text-gray-700">STRESS SENSE</span>
    </div>

    <nav class="space-x-4">
        <a href="Home.php" class="text-gray-700 hover:text-blue-700 text-sm">HOME</a>
        <a href="Assessment.php" class="text-gray-700 hover:text-blue-700 text-sm">ASSESSMENT</a>
        <a href="History.php" class="text-gray-700 hover:text-blue-700 text-sm">HISTORY</a>
        <a href="Tips And Resources.php" class="text-gray-700 hover:text-blue-700 text-sm">TIPS & RESOURCES</a>
        <a href="Settings.php" class="text-blue-700 font-semibold hover:underline text-sm">SETTINGS</a>
    </nav>
</header>

<!-- MAIN SECTION -->
<main class="flex-grow flex items-center justify-center px-4 py-2 overflow-hidden">
    <div class="bg-white/90 backdrop-blur p-6 rounded-2xl shadow-xl w-full max-w-md border border-blue-100/70">
        <h1 class="text-2xl font-bold text-center text-blue-700 mb-1">Update Your Profile</h1>
        <p class="text-center text-gray-600 text-sm mb-4">Keep your information up to date for better insights</p>

        <form method="POST" action="update_form.php" class="space-y-3">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            
            <!-- Name Fields -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="fname" class="block text-gray-700 text-sm font-medium mb-1">First Name</label>
                    <input 
                        type="text" 
                        id="fname" 
                        name="fname" 
                        value="<?php echo htmlspecialchars($user['fname']); ?>" 
                        class="w-full px-3 py-2 text-sm border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required
                    >
                </div>
                
                <div>
                    <label for="lname" class="block text-gray-700 text-sm font-medium mb-1">Last Name</label>
                    <input 
                        type="text" 
                        id="lname" 
                        name="lname" 
                        value="<?php echo htmlspecialchars($user['lname']); ?>" 
                        class="w-full px-3 py-2 text-sm border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required
                    >
                </div>
            </div>
            
            <div>
                <label for="mname" class="block text-gray-700 text-sm font-medium mb-1">Middle Name</label>
                <input 
                    type="text" 
                    id="mname" 
                    name="mname" 
                    value="<?php echo htmlspecialchars($user['mname']); ?>" 
                    class="w-full px-3 py-2 text-sm border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                >
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="gender" class="block text-gray-700 text-sm font-medium mb-1">Gender</label>
                    <select 
                        id="gender" 
                        name="gender" 
                        class="w-full px-3 py-2 text-sm border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required
                    >
                        <option value="m" <?php echo ($user['gender'] === 'm') ? 'selected' : ''; ?>>Male</option>
                        <option value="f" <?php echo ($user['gender'] === 'f') ? 'selected' : ''; ?>>Female</option>
                        <option value="x" <?php echo ($user['gender'] === 'x') ? 'selected' : ''; ?>>Prefer not to say</option>
                    </select>
                </div>
                
                <div>
                    <label for="birthday" class="block text-gray-700 text-sm font-medium mb-1">Birthday</label>
                    <input 
                        type="date" 
                        id="birthday" 
                        name="birthday" 
                        value="<?php echo htmlspecialchars($user['birthday']); ?>" 
                        class="w-full px-3 py-2 text-sm border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required
                    >
                </div>
            </div>
            
            <div>
                <label for="cno" class="block text-gray-700 text-sm font-medium mb-1">Contact Number</label>
                <input 
                    type="text" 
                    id="cno" 
                    name="cno" 
                    value="<?php echo htmlspecialchars($user['cno']); ?>" 
                    class="w-full px-3 py-2 text-sm border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                    required
                >
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-3 pt-2">
                <button 
                    type="submit" 
                    name="update_user" 
                    class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-medium 
                           hover:bg-blue-700 transition shadow-md"
                >
                    Update Profile
                </button>
                <a 
                    href="Settings.php" 
                    class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg font-medium text-center
                           hover:bg-gray-400 transition shadow-md"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</main>

<!-- FOOTER -->
<footer class="bg-white/80 backdrop-blur py-2 text-center text-gray-600 text-xs border-t flex-shrink-0">
    &copy; 2025 StressSense. All Rights Reserved |
    <a href="About Us.php" class="hover:underline">About Us</a> |
    <a href="Privacy Policy.php" class="hover:underline">Privacy Policy</a> |
    <a href="Terms Of Service.php" class="hover:underline">Terms</a> |
    <a href="Contact.php" class="hover:underline">Contact</a>
</footer>

</body>
</html>