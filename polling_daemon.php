<?php

// Production-ready settings
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'runtime_errors.log');
set_time_limit(0);

require_once "vals.php";

define('API_KEY', $token);

function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    curl_setopt($ch, CURLOPT_TIMEOUT, 65); // 60s long polling
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        error_log("cURL Error in bot(): " . curl_error($ch));
        return false;
    }
    return json_decode($res);
}
function makeAndSendBackup() {
    global $token, $admin;

    try {
        $backup_dir = __DIR__;
        $zip_file_name = 'backup-' . date('Y-m-d_H-i-s') . '.zip';
        $zip_file_path = $backup_dir . '/' . $zip_file_name;

        if (!class_exists('ZipArchive')) {
            throw new Exception("📦 مكتبة ZipArchive غير موجودة!");
        }

        $zip = new ZipArchive();
        if ($zip->open($zip_file_path, ZipArchive::CREATE) !== TRUE) {
            throw new Exception("❌ فشل فتح ملف النسخة الاحتياطية.");
        }

        $files_to_backup = array_filter(
            array_merge(glob("$backup_dir/*.json"), glob("$backup_dir/*.txt")),
            fn($f) => !(substr($f, -4) === '.zip') && is_file($f)
        );

        if (empty($files_to_backup)) {
            throw new Exception("⚠️ لا توجد ملفات مناسبة للنسخ الاحتياطي.");
        }

        foreach ($files_to_backup as $file) {
            $zip->addFile($file, basename($file));
        }

        $zip->close();

        $cFile = new CURLFile(realpath($zip_file_path));
        $post_fields = [
            'chat_id' => $admin,
            'document' => $cFile,
            'caption' => "📦 نسخة احتياطية\n📅 " . date('Y-m-d H:i:s')
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot$token/sendDocument");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("❌ خطأ أثناء إرسال النسخة: " . curl_error($ch));
        }

        curl_close($ch);

        $result = json_decode($response);
        if (!$result || !$result->ok) {
            throw new Exception("❌ فشل في إرسال النسخة للادمن:\n" . print_r($result, true));
        }

        unlink($zip_file_path);
        return true;

    } catch (Throwable $e) {
        sendAdminMessage("📛 حدث خطأ أثناء عمل نسخة احتياطية:\n" . $e->getMessage());
        return false;
    }
}

function sendAdminMessage($text) {
    global $token, $admin;
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";

    $data = [
        'chat_id' => $admin,
        'text' => $text
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_exec($ch);
    curl_close($ch);
}


// تجاهل التحديثات القديمة:
$initial = bot('getUpdates');
$offset = 0;

if (!empty($initial->result)) {
    $last_update = end($initial->result);
    $offset = $last_update->update_id + 1;
}

while (true) {
    $updates = bot('getUpdates', ['offset' => $offset, 'timeout' => 60]);

    if (!empty($updates->result)) {
        foreach ($updates->result as $update) {
            $offset = $update->update_id + 1;

            $json = json_encode($update);


            $descriptorspec = [
                0 => ["pipe", "r"], // STDIN
                1 => ["pipe", "w"], // STDOUT
                2 => ["pipe", "w"]  // STDERR
            ];

            $process = proc_open("php index.php", $descriptorspec, $pipes);

            if (is_resource($process)) {
                fwrite($pipes[0], $json); // ابعت التحديث لـ STDIN
                fclose($pipes[0]);

                $output = stream_get_contents($pipes[1]);
                fclose($pipes[1]);

                $errors = stream_get_contents($pipes[2]);
                fclose($pipes[2]);

                proc_close($process);

                if ($output) echo "Output: $output\n";
                if ($errors) echo "Errors: $errors\n";
            }
        }
    }


    // نسخة احتياطية مرة واحدة باليوم بعد الساعة 1 صباحًا
    static $last_backup_day = null;

    $current_day = date('Y-m-d');
    $current_hour = (int)date('G');

    if ($last_backup_day !== $current_day && $current_hour >= 1) {
        if (makeAndSendBackup()) {
            $last_backup_day = $current_day;
        }
    }


    // usleep(50000);
}
