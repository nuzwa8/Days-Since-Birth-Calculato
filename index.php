<?php
/**
 * Date of Birth to Days Calculator - index.php
 * یہ فائل فارم کا ڈھانچہ اور تاریخوں کے حساب (Days Calculation) کی لاجی (Logic) فراہم کرتی ہے۔
 * JavaScript (AJAX) اس فائل کو فارم ڈیٹا بھیجنے کے لیے استعمال کرے گا۔
 */

// نتائج (Results) اور غلطی (Error) کے پیغامات کے لیے متغیرات (Variables)
$result_message = "";
$error_message = "";

// فارم جمع (submit) ہونے پر لاجی (Logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // یوزر کی ان پٹ (Input) کو صاف (Sanitize) کرنا
    $dob_input = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!empty($dob_input)) {
        try {
            // تاریخ پیدائش (DOB) اور موجودہ تاریخ (Current Date) کے لیے DateTime آبجیکٹس (Objects) بنانا
            $dob = new DateTime($dob_input);
            $current_date = new DateTime();

            // تاریخ کی تصدیق (Validation)
            if ($dob > $current_date) {
                $error_message = "تاریخ پیدائش موجودہ تاریخ سے آگے نہیں ہو سکتی۔ (Date of Birth cannot be in the future)";
            } else {
                // دونوں تاریخوں کے درمیان فرق (Difference) معلوم کرنا
                $interval = $dob->diff($current_date);
                
                // فرق کو دنوں (Days) میں تبدیل کرنا
                $total_days = $interval->days;

                // نتیجے کا پیغام (Result Message) تیار کرنا
                $result_message = "🎉 آپ نے اپنی زندگی کے کل **" . number_format($total_days) . "** دن گزارے ہیں۔";
            }

        } catch (Exception $e) {
            // غلط تاریخ فارمیٹ (Invalid Date Format) کو ہینڈل کرنا
            $error_message = "درست تاریخ درج کریں۔ (Please enter a valid date format)";
        }
    } else {
        $error_message = "براہ کرم اپنی تاریخ پیدائش درج کریں۔ (Please enter your Date of Birth)";
    }

    // اگر یہ AJAX درخواست ہے، تو صرف نتیجہ (Result) یا غلطی (Error) کا پیغام دکھائیں
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo $result_message ? "<p class='success-message'>$result_message</p>" : "<p class='error-message'>$error_message</p>";
        // چونکہ ہم AJAX کے ذریعے جواب دے رہے ہیں، اس لیے یہاں ایگزٹ (Exit) کر دیں گے
        exit; 
    }
}

// اگر یہ عام پیج لوڈ (Page Load) ہے، تو پورا HTML دکھائیں
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOB to Days Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <header>
        <h1 class="main-heading">📅 دنوں کا شمار (Day Count)</h1>
        <p class="subtitle">اپنی تاریخ پیدائش درج کریں اور جانیں کہ آپ کتنے دن کے ہو چکے ہیں۔</p>
    </header>

    <form id="dobForm" method="POST" action="index.php">
        <div class="input-group">
            <label for="dob">تاریخ پیدائش (Date of Birth)</label>
            <input type="date" id="dob" name="dob" required max="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($dob_input ?? ''); ?>">
        </div>

        <button type="submit" id="calculateBtn" class="purple-button">
            حساب لگائیں (Calculate Days)
        </button>
    </form>
    
    <div id="resultBox" class="result-box">
        <?php
        // عام پیج لوڈ پر نتائج (Results) دکھائیں (اگر AJAX استعمال نہ ہو تو)
        if (!empty($result_message)) {
            echo "<p class='success-message'>$result_message</p>";
        } elseif (!empty($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        } else {
            // ابتدائی پیغام (Initial Message)
            echo "<p class='initial-message'>نتیجہ یہاں دکھایا جائے گا۔</p>";
        }
        ?>
    </div>

</div>

<script src="script.js"></script>
</body>
</html>
<?php
// PHP کوڈ اوپن اینڈڈ (Open-Ended) رکھنا ہے، اس لیے یہاں کوئی بندش والا ٹیگ (Closing Tag) نہیں ہے۔
// یہ **فیز 1** مکمل ہوا۔
