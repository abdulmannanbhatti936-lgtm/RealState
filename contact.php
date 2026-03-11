<?php
require_once 'db.php';
include 'includes/header.php';
$property_name = isset($_GET['property']) ? $_GET['property'] : '';
$success_msg = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'] ?? 'General Inquiry';
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO contacts (name, email, property_title, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $success_msg = "Thank you! Your message has been sent. We will get back to you soon.";
    }
}
?>

<main class="container">
    <h2 class="section-title">Contact Us</h2>

    <div
        style="max-width: 600px; margin: 0 auto; background: var(--white); padding: 30px; border-radius: 8px; box-shadow: var(--shadow); margin-bottom: 50px;">
        <?php if ($success_msg): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="subject">Property of Interest / Subject</label>
                <input type="text" id="subject" name="subject"
                    value="<?php echo htmlspecialchars($property_name ? 'Inquiry: ' . $property_name : ''); ?>"
                    placeholder="General Inquiry">
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required placeholder="How can we help you?"></textarea>
            </div>
            <button type="submit" name="submit" class="btn" style="width: 100%; border: none; cursor: pointer;">Send
                Message</button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>