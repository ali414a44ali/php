<?php


//    
//error_reporting(E_ERROR);
	
require_once "vals.php";
require_once "contries.php";
require_once "api.php";
define('API_KEY', $token);
#functions 
function bot($method, $datas = [])
{
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    file_put_contents('error.txt', json_encode(json_decode($res),448)."\n$method".__LINE__, FILE_APPEND);
    
    if (curl_error($ch)) {
        //var_dump(curl_error($ch));
        $res = json_decode($res);
        
        return $res;
    } else {
        $res = json_decode($res);
        #file_get_contents("https://api.telegram.org/bot" . API_KEY . "/sendmessage?chat_id=501030516&text=" . urlencode(json_encode($res,448)."\n$method".__LINE__));
        return $res;
    }
}
//فنكشن ازرار
function sendMessageWithButtons($chat_id, $bot_mode, $buttons = []) {
    if (file_exists("onoroff.txt")) {
        $inline_keyboard = [];
        foreach ($buttons as $button) {
            $inline_keyboard[] = [['text' => $button['text'], 'url' => $button['url']]];
        }

        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => $bot_mode,
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => $inline_keyboard
            ])
        ]);
    } else {
        // إرسال الرسالة بدون أزرار
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => $bot_mode,
            'parse_mode' => "markdown"
        ]);
    }
}

//فنكشن اشتراك
function checkMandatorySubscription($user_id, $chat_id, $message_id = null, $is_callback = false) {
    $channels = explode("\n", file_get_contents("eshterak.txt"));
    $is_subscribed = true;
    $missing_channels = [];

    foreach ($channels as $channel_id) {
        $response = file_get_contents("https://api.telegram.org/bot" . API_KEY . "/getChatMember?chat_id=" . $channel_id . "&user_id=" . $user_id);
        
        if (strpos($response, '"status":"left"') !== false || 
            strpos($response, '"Bad Request: USER_ID_INVALID"') !== false || 
            strpos($response, '"status":"kicked"') !== false) {
            $is_subscribed = false;
            $missing_channels[] = $channel_id;
        }
    }

    if (!$is_subscribed) {
        $buttons = [];
        foreach ($missing_channels as $channel_id) {
            $chat = bot("getChat", ['chat_id' => $channel_id]);
            $channel_name = $chat->result->title;
            $link = $chat->result->invite_link;

            if ($link == null) {
                $link = bot("exportChatInviteLink", ['chat_id' => $channel_id])->result;
            }

            $buttons[] = [['text' => "اشترك في قناة $channel_name", 'url' => $link]];
        }

        $message = "يبدو أنك غير مشترك في بعض القنوات الإجبارية. يرجى الاشتراك في هذه القنوات للمتابعة:";
        if (!empty($buttons)) {
            if ($is_callback) {
                bot("EditMessageText", [
                    "chat_id" => $chat_id,
                    'message_id' => $message_id,
                    "text" => $message,
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $buttons
                    ])
                ]);
            } else {
                bot("sendMessage", [
                    "chat_id" => $chat_id,
                    "text" => $message,
                    "parse_mode" => "Markdown",
                    'reply_markup' => json_encode([
                        'inline_keyboard' => $buttons
                    ])
                ]);
            }
            return false;
        } else {
            bot("sendMessage", [
                "chat_id" => $chat_id,
                "text" => "حدث خطأ ولم أتمكن من العثور على روابط القنوات الإجبارية. يرجى المحاولة مرة أخرى.",
                "parse_mode" => "Markdown",
            ]);
            return false;
        }
    }

    return true;
}

//فنشكن المود
function getMode() {
    if (!file_exists("mode.txt")) {
        file_put_contents("mode.txt", ""); 
    }
    return trim(file_get_contents("mode.txt"));
}
function setMode($mode) {
    file_put_contents("mode.txt", $mode);
}



//$link =  "https://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
//echo file_get_contents("https://api.telegram.org/bot$token/setWebHook?url=$link");
function check_member($id, $chat){
    $join = bot('getChatMember', ["chat_id" => $chat, "user_id" => $id])->result->status;
    if($join == 'left' or $join == 'kicked'){
        return false;
    }else{
        return true;
    }
}
/*
array(
	array(
	text => data,
	text => data
	),
	array(
	text => data,
	)
)
*/

//one line button 
function mkBtn($btn) {
	$res = array();
	foreach ($btn as  $d) {
		$r = array();
		foreach ($d as $k => $v ) {
			$r[] = ['text' => $k , 'callback_data' => $v];
		}
		$res[] = $r;
	}
	return $res;
}

function send($text,$btn=null,$id=null) {
	if ($id == null) {
		global $id;
	}
	$data = array();
	$data['chat_id'] = $id;
	$data['text'] = $text;
	$data['parse_mode'] ='html';
	if($btn != null){
		$data['reply_markup'] = json_encode([
			'inline_keyboard' => $btn
		]);
	}
	return bot('sendMessage',
		$data
	);
}
function edit ($text,$btn=null){
	$data = array();
	global $id;
	global $message_id;
	$data['chat_id'] = $id;
	$data['text'] = $text;
	$data['parse_mode'] ='html';
	$data['message_id']=$message_id;
	if($btn != null){
		$data['reply_markup'] = json_encode([
			'inline_keyboard' => $btn
		]);
	}
	return bot('editMessageText',
		$data
	);
}
function savePoint() {
	global $points;
	if($points != null) {
		file_put_contents("points.json",json_encode($points,448));
	}
}
function saveStats() {
	global $stats;
	if($stats != null) {
		file_put_contents("stats.json",json_encode($stats,448));
	}
}
function saveOp() {
	global $op;
	if($op != null) {
		file_put_contents("operations.json",json_encode($op,448));
	}
}
function saveInvite() {
	global $invite;
	if($invite != null) {
		file_put_contents("invites.json",json_encode($invite,448));
	}
}
function saveBans() {
	global $bans;
	if($bans != null) {
		file_put_contents("bans.json",json_encode($bans,448));
	}
}
function saveInfo() {
	global $info;
	if($info != null) {
		file_put_contents("info.json",json_encode($info,448));
	}
} 
function saveContries() {
	global $contries;
	if($contries != null) {
		file_put_contents("contries.json",json_encode($contries,448));
	}
}

$back = mkBtn (array(
		array (
			"رجوع" => "back"
		)
	));
#end functions

$update_json = file_get_contents('php://stdin');
$update = json_decode($update_json);
error_log("\n\n\n\n");
// print_r($update_json);
    error_log("\n\n\n\n");
if (!$update) {
    error_log("WORKER: Failed to decode update from STDIN.");

    exit(1);
}

if (!file_exists("eshterak.txt")) file_put_contents("eshterak.txt", "");
if (!file_exists("mem.txt")) file_put_contents("mem.txt", "");
if (!file_exists("admins.txt")) file_put_contents("admins.txt", "");
if (!file_exists("Regular.txt")) file_put_contents("Regular.txt", "");
if (!file_exists("communication.txt")) file_put_contents("communication.txt", "");
if (!file_exists("azrar.json")) file_put_contents("azrar.json", "[]");

$eshterak = explode("\n", file_get_contents("eshterak.txt"));
$eshterakk = count($eshterak) - 1;
$mem = explode("\n", file_get_contents("mem.txt"));
$memm = count($mem) - 1;
$admins = explode("\n",file_get_contents("admins.txt"));
$adminss = count($admins)-1;
$Regular = file_get_contents("Regular.txt");
$communication = file_get_contents("communication.txt");
$azrar_file = "azrar.json";
$buttons = json_decode(file_get_contents($azrar_file), true);

$bot_id = bot("getme")->result->id;
if (isset($update->message)) {
    $message = $update->message;
    $user = $message->from->username ?? '';
    $chat_id = $message->chat->id;
    error_log($chat_id);
    $text = $message->text ?? '';
    $ex = explode(" ", $text);
    $first_name = $message->from->first_name ?? '';
    $username = $message->from->username ?? '';
    $id = $message->from->id;
    $message_id = $message->message_id;
    $entities = $message->entities ?? [];
    $language_code = $message->from->language_code ?? '';
    $tc = $message->chat->type;
    $re_message = $message->reply_to_message ?? null;
    $re_text = $re_message->text ?? '';
    $name = $message->from->first_name ?? '';
} else if (isset($update->callback_query)) {
    $chat_id = $update->callback_query->message->chat->id;
    $id = $update->callback_query->from->id;
    $first_name = $update->callback_query->from->first_name ?? '';
    $message_id = $update->callback_query->message->message_id;
    $data = $update->callback_query->data ?? '';   
    $exData = explode("#", $data); 
} else {
    exit;
}


#points {id,points}
$points = json_decode(file_get_contents("points.json"),1);
$point = $points[$id]??0;

#stats {sell  ,success ,all_points, price_sold_nums}}
$stats = json_decode(file_get_contents("stats.json"),1)??[];
#operations {id_op {time,buyer_id, county, number,code,price}}
$op = json_decode(file_get_contents("operations.json"),1)??[];
#invites | whoInvitedMe {id ,who send link} , invited{id,who use my link}
$invite = json_decode(file_get_contents("invites.json"),1)??[];
#bans
$bans = json_decode(file_get_contents("bans.json"),1)??[];
#--
$info = json_decode(file_get_contents("info.json"),1)??[];
#--
$contries = json_decode(file_get_contents("contries.json"),1)??[];
#--
$api = new Api($api_key);
if ($id == $admin or in_array($id, $admins)) {
	$balance = $api->getBalance()??0; //api balance for admin
	require "admin.php";
} else {

    if ($message && !in_array($id, $mem) and $tc == "private") {
        file_put_contents("mem.txt", $id . "\n", FILE_APPEND);
    
        if ($user != null) {
            $sf = "@$user";
        } else {
            $sf = "لا يوجد معرف";
        }
        bot('sendmessage', [
            'chat_id' => $admin,
            'text' => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)
**• تم دخول شخص جديد الى البوت 👤**
    •–––––––––––––––––––––––––––––––•

• معلومات الشخص 📜 : 

- الاسم : [$name](tg://user?id=$id)
- المعرف :[ $sf ]
- الايدي : [$id](tg://openmessage?user_id=$id)

    •–––––––––––––––––––––––––––––––•
• عدد الاعضاء الكلي : $memm 📊
",
            "parse_mode" => "markdown",
        ]);

    }


    if (($update->message and !checkMandatorySubscription($id, $chat_id)) or ($update->callback_query and !checkMandatorySubscription($id, $chat_id, $message_id, true))) {
        exit;
    }
    if (file_exists("onoroff.txt")) {
        sendMessageWithButtons($chat_id, $bot_mode, $buttons);
        exit;
    }
	require "member.php";
}







