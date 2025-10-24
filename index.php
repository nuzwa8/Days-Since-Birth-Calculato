<?php
/**
 * Date of Birth to Days Calculator - index.php
 * یہ فائل HTML ڈھانچہ (Structure) اور تاریخوں کے حساب (Days Calculation) کی لاجی (Logic) فراہم کرتی ہے۔
 * یہ AJAX اور ریگولر فارم سبمیشن (Regular Form Submission) دونوں کو ہینڈل کرتی ہے۔
 */

// نتائج اور غلطی کے پیغامات کے لیے متغیرات (Variables)
$result_message = "";
$error_message = "";
$dob_input = ""; // فارم فیلڈ میں ویلیو (Value) کو برقرار رکھنے کے لیے

// فارم جمع (submit) ہونے پر لاجی (Logic)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['dob'])) {
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
                // HTML ٹیگز کو AJAX جواب کے لیے تیار کر رہے ہیں
                $total_days_formatted = number_format($total_days);
                $result_message = "<p class='success-message'>🎉 آپ نے اپنی زندگی کے کل <strong>$total_days_formatted</strong> دن گزارے ہیں۔</p>";
            }

        } catch (Exception $e) {
            // غلط تاریخ فارمیٹ (Invalid Date Format) کو ہینڈل کرنا
            $error_message = "درست تاریخ درج کریں۔ (Please enter a valid date format)";
        }
    } else {
        $error_message = "براہ کرم اپنی تاریخ پیدائش درج کریں۔ (Please enter your Date of Birth)";
    }

    // اگر یہ AJAX درخواست ہے، تو صرف نتیجہ یا غلطی کا پیغام دکھائیں
    // یہ خاص ہیڈر (Header) JavaScript میں سیٹ کیا گیا ہے
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo $result_message ? $result_message : "<p class='error-message'>❌ $error_message</p>";
        exit; // AJAX جواب کے بعد باقی HTML کو لوڈ ہونے سے روکنا
    }
}

// HTML ڈھانچہ (Structure)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date of Birth to Days Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <header>
        <h1 class="main-heading">📅 کل دنوں کا شمار (Total Day Count)</h1>
        <p class="subtitle">اپنی تاریخ پیدائش درج کریں اور جانیں کہ آپ کتنے دن کے ہو چکے ہیں۔</p>
    </header>

    <form id="dobForm" method="POST" action="index.php">
        <div class="input-group">
            <label for="dob">تاریخ پیدائش (Date of Birth)</label>
            <input 
                type="date" 
                id="dob" 
                name="dob" 
                required 
                max="<?php echo date('Y-m-d'); ?>" 
                value="<?php echo htmlspecialchars($dob_input); ?>"
            >
        </div>

        <button type="submit" id="calculateBtn" class="purple-button">
            حساب لگائیں (Calculate Days)
        </button>
    </form>
    
    <div id="resultBox" class="result-box">
        <?php
        // اگر کوئی نتیجہ یا غلطی کا پیغام موجود ہو (غیر-AJAX سبمیشن کی صورت میں)
        if (!empty($result_message)) {
            echo $result_message;
        } elseif (!empty($error_message)) {
            echo "<p class='error-message'>❌ $error_message</p>";
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
// PHP کوڈ اوپن اینڈڈ (Open-Ended) رکھنا ہے
