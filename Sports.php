<?php

// 1. تحديد تاريخ اليوم تلقائياً بصيغة YYYY-MM-DD
$targetDate = date('Y-m-d'); 

// إذا كنت تريد تحديد تاريخ معين يدوياً، قم بإلغاء التعليق عن السطر التالي:
// $targetDate = "2026-06-18"; 

// 2. بناء رابط الـ API مع المتغيرات
$url = "https://api.alkora.app/v2/matches?date=" . urlencode($targetDate) . "&timezone=Africa%2FCairo&version_code=49&version_name=2.2.9&platform=0";

// 3. تهيئة اتصال cURL
$ch = curl_init();

// 4. ضبط خيارات cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // إرجاع النتيجة كسلسلة نصية بدلاً من طباعتها مباشرة
curl_setopt($ch, CURLOPT_TIMEOUT, 30); // وقت انتهاء المحاولة (30 ثانية)

// إضافة Headers (مهمة جداً لبعض الـ APIs لتجنب الحظر 403)
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Accept: application/json'
]);

// 5. تنفيذ الطلب وتخزين الاستجابة
$response = curl_exec($ch);

// 6. التحقق من وجود أخطاء في الاتصال
if (curl_errno($ch)) {
    echo 'خطأ في الاتصال بـ cURL: ' . curl_error($ch);
} else {
    // الحصول على كود حالة الرد (HTTP Status Code)
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode == 200) {
        // تحويل النص المستلم (JSON) إلى مصفوفة PHP للتعامل معها بسهولة
        $data = json_decode($response, true);
        
        // طباعة البيانات بشكل منظم ومقروء
        echo "<h2>تم جلب البيانات بنجاح لليوم: {$targetDate}</h2>";
        echo "<pre>";
        print_r($data); 
        echo "</pre>";
        
        /* هنا يمكنك البدء في استعراض المباريات، مثلاً:
        foreach ($data['matches'] as $match) {
             echo $match['home_team'] . " VS " . $match['away_team'];
        }
        */
        
    } else {
        echo "فشل الطلب. كود الحالة الرقمي: " . $httpCode . "<br>";
        echo "الرد المستلم: " . htmlspecialchars($response);
    }
}

// 7. إغلاق الاتصال لتوفير موارد السيرفر
curl_close($ch);

?>
