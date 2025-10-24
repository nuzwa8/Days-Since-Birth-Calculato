/**
 * Date of Birth to Days Calculator - script.js
 * یہ فائل فارم کو AJAX کے ذریعے جمع (Submit) کرنے کا انتظام کرتی ہے۔
 */

document.addEventListener('DOMContentLoaded', function() {
    // ضروری DOM ایلیمنٹس (Elements) کو حاصل کرنا
    const form = document.getElementById('dobForm');
    const resultBox = document.getElementById('resultBox');
    const dobInput = document.getElementById('dob');

    // تاریخ پیدائش کی ان پٹ (Input) پر زیادہ سے زیادہ تاریخ (Max Date) سیٹ کرنا
    // یہ PHP میں بھی کیا گیا ہے، لیکن JavaScript میں اضافی احتیاط کے لیے
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // مہینے (Month) 0 سے شروع ہوتے ہیں
    const dd = String(today.getDate()).padStart(2, '0');
    dobInput.max = `${yyyy}-${mm}-${dd}`;

    // فارم جمع ہونے (Submission) پر ایونٹ لسنر (Event Listener) لگانا
    form.addEventListener('submit', function(event) {
        // فارم کے ڈیفالٹ (Default) جمع ہونے کے عمل کو روکنا
        event.preventDefault();

        // فارم کا ڈیٹا (Data) حاصل کرنا
        const formData = new FormData(form);
        
        // AJAX درخواست (Request) شروع کرنا
        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        
        // یہ ہیڈر (Header) PHP کو بتاتا ہے کہ یہ AJAX درخواست ہے
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        // نتیجے کا باکس (Result Box) صاف کرنا اور لوڈنگ (Loading) کا پیغام دکھانا
        resultBox.innerHTML = '<p class="loading-message">... حساب لگایا جا رہا ہے (Calculating) ...</p>';

        // جواب (Response) آنے پر کیا کرنا ہے
        xhr.onload = function() {
            if (xhr.status === 200) {
                // PHP سے موصول ہونے والے HTML مواد (Content) کو رزلٹ باکس میں شامل کرنا
                // PHP فائل صرف نتیجہ (Result) یا غلطی (Error) کا پیغام بھیجے گی۔
                resultBox.innerHTML = xhr.responseText;
            } else {
                // HTTP غلطی (Error) کو ہینڈل کرنا
                resultBox.innerHTML = '<p class="error-message">❌ سرور سے رابطہ نہیں ہو سکا (Server error: ' + xhr.status + ')</p>';
            }
        };

        // درخواست بھیجنا
        xhr.send(formData);
    });
});
