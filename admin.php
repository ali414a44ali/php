<?php 
$lang = json_decode(file_get_contents("langs.json"),1)??[];
$txt = array(
"ar" => array(
"1" => "✅ - تم إعادة شحن حسابك بـ مبلغ __point__",
"2" => "تم سحب __point__  من حسابك",
"3" => "تم حظرك من استخدام البوت",
"4"=> "تم الغاء حظرك من استخدام البوت",
),
"en" => array(
    "1" => "✅ - Your account has been recharged with __point__",
    "2" => "__point__ has been deducted from your account",
    "3" => "You have been banned from using the bot",
    "4" => "You have been unbanned from using the bot",
),
"ru" => array(
    "1" => "✅ - Ваш счет был пополнен на сумму __point__",
    "2" => "__point__ было списано с вашего счета",
    "3" => "Вы были забанены от использования бота",
    "4" => "Вы были разблокированы для использования бота",
),
"fa" => array(
    "1" => "✅ - حساب شما با مبلغ __point__ شارژ شد",
    "2" => "__point__ از حساب شما کسر شد",
    "3" => "شما از استفاده از ربات مسدود شده‌اید",
    "4" => "مسدودیت شما از استفاده از ربات لغو شد",
),
"cht" => array(
    "1" => "✅ - 您的帳戶已加值 __point__",
    "2" => "__point__ 已從您的帳戶中扣除",
    "3" => "您已被禁止使用機器人",
    "4" => "您已被解除禁止使用機器人",
),
"chb" => array(
    "1" => "✅ - 您的账户已充值 __point__",
    "2" => "__point__ 已从您的账户中扣除",
    "3" => "您已被禁止使用机器人",
    "4" => "您已被解除禁止使用机器人",
)
);
if ($text == "/start" || $data == "back") {
	setMode("");
	unlink("Regular.txt");
	$btn = 
	mkBtn(
		array(
			array(
				"اضافة رصيد" => "addPoint",
				"سحب رصيد" => "takePoint"
			),
			array(
				"حظر عضو" => "ban",
				"الغاء  الحظر" => "unban"
			),
			array(
				"اضافة وكيل" => "addWk",
				"حذف وكيل" => "remWk"
			),
			array(
				"الوكلاء" => "wk",
			),
			array(
				"اضافة دولة" => "addContry",
				"حذف دولة" => "remContry"
			),
			array(
				"الاحصائيات" => "stats"
			),
			array(
				"قسم الاشتراك الاجباري" => "eshterak",
				"احصائيات الاعضاء" => "broadcast"
			),
			array(
				"قسم الاذاعة" => "msg",
				"قسم الادمنيه" => "ksmadmin"
			),
			array(
				"تفعيل التواصل" => "communication_on",
				"تعطيل التواصل" => "communication_off"
			),
			array(
				"تشغيل البوت" => "onoroff_on",
				"ايقاف البوت" => "onoroff_off"
			),
			array(
				"اضف زر" => "add_button",
				"حذف زر" => "delete_button"
			),
			array(
				"حذف جميع الازرار" => "delete_buttons"
			)
		)
	
	);
	$info[$id]['action']="";
	saveInfo();
	$tx = "اهلا وسهلا بك عزيزي الادمن\n رصيدك في موقع $balance\n\nاوامر الادمن";
	if ($text)
	send($tx,$btn);
	else
	edit($tx,$btn);
} else if ($data == "addPoint") {
	$tx = "قم بارسال ايدي العضو";
	$info[$id]['action']="addPointId";
	saveInfo();
	edit($tx,$back);
} else if ($text && ($info[$id]['action'] == "addPointId") ){
	echo "-@Ba_ageel-";
	if(!isset ($points[$text])) {
		//user not exist 
		$tx = "لا يوجد مستخدم بهذا الايدي";
		send($tx,$back);
	} else {
		$info[$id]['idPoint']  = $text;
		$info[$id]['action']="addPoint";
		saveInfo();
		$tx = "قم بارسال الرصيد الذي تريد اضافته";
		send($tx,$back);
	}
} else if ($text && $info[$id]['action'] == "addPoint") {
	if( is_numeric($text) && $text > 0 ) {
		$points[$info[$id]['idPoint']] += ($text);
		savePoint();
		$tx = "تم التحويل بنجاح";
		send($tx,$back);
		$tx=str_replace("__point__",$text,$txt[$lang[$info[$id]['idPoint']]][1]);
		send($tx,null,$info[$id]['idPoint']);
		$info[$id]['idPoint']  = "";
		$info[$id]['action']="";
		saveInfo();
	} else {
		$tx = "يجب ان ترسل رقم اكبر من الصفر";
		send($tx,$back);
	}
}else if ($data == "takePoint") {
	$tx = "قم بارسال ايدي العضو";
	$info[$id]['action']="takePointId";
	saveInfo();
	edit($tx,$back);
} else if ($text && ($info[$id]['action'] == "takePointId") ){
	echo "-@Ba_ageel-";
	if(!isset ($points[$text])) {
		//user not exist 
		$tx = "لا يوجد مستخدم بهذا الايدي";
		send($tx,$back);
	} else {
		$info[$id]['idPoint']  = $text;
		$info[$id]['action']="takePoint";
		saveInfo();
		$tx = "قم بارسال الرصيد الذي تريد سحبه من العضو";
		send($tx,$back);
	}
} else if ($text && $info[$id]['action'] == "takePoint") {
	if( is_numeric($text) && $text > 0 ) {
		$points[$info[$id]['idPoint']] -= ($text);
		savePoint();
		$tx = "تم السحب بنجاح";
		send($tx,$back);
		$tx=str_replace("__point__",$text,$txt[$lang[$info[$id]['idPoint']]][2]);
		send($tx,null,$info[$id]['idPoint']);
		$info[$id]['idPoint']  = "";
		$info[$id]['action']="";
		saveInfo();
	} else {
		$tx = "يجب ان ترسل رقم اكبر من الصفر";
		send($tx,$back);
	}
} else if ($data == "ban") {
	$tx = "قم بارسال ايدي العضو";
	$info[$id]['action']="ban";
	saveInfo();
	edit($tx,$back);
} else if ($text && $info[$id]['action'] == "ban") {
	$tx = "تم حظر العضو بنجاح";
	$info[$id]['action']="";
	saveInfo();
	$bans[$text]=$text;
	saveBans();
	send($tx,$back);
	$tx = $txt[$lang[$text]][3];
	send($tx,null,$text);
}else if ($data == "unban") {
	$tx = "قم بارسال ايدي العضو";
	$info[$id]['action']="unban";
	saveInfo();
	edit($tx,$back);
}else if ($text && $info[$id]['action'] == "unban") {
	$tx = "تم الغاء الحظر بنجاح";
	$info[$id]['action']="";
	saveInfo();
	$bans[$text]=null;
	saveBans();
	send($tx,$back);
	$tx = $txt[$lang[$text]][4];
	send($tx,null,$text);
} else if ($data == "addWk") {
	$tx = "قم بارسال الاسم في سطر واليوزر في السطر الثاني";
	$info[$id]['action']="addWk";
	saveInfo();
	edit($tx,$back);
} else if ($text && $info[$id]['action']=="addWk") {
	$extx = explode ("\n",$text);
	if(count ($extx) == 2) {
		$info['bot']['wk'] [] = array(
			"name" => $extx[0],
			"user" => $extx[1],
		);
		$info[$id]['action']="";
		saveInfo ();
		$tx="تمت اضافة الوكيل بنجاح";
		send($tx,$back);
	} else {
		$tx = "قم بارسال الاسم في سطر واليوزر في السطر الثاني";
		send($tx,$back);
	}
} else if ($data == "remWk" ) {
	$btn = array();
	$btn[]= 
		array(
			"الاسم" => "ntn",
			"اليوزر" => "ntn"
		);
	
	foreach ($info['bot']['wk'] as $k => $v ) {
		$d= "remWk-{$k}";
		$btn[] = 
			array(
				"{$v['name']}"??""=> $d,
				"{$v['user']}"??"" => $d,
			);
		
	}
	$btn[]=array (
			"رجوع" => "back"
		);
	$tx ="اختار الوكيل الذي تريد حذفة";
	edit($tx, mkBtn ($btn));
}else if (preg_match("/remWk\-/",$data)) {
	$info['bot']['wk'][explode ("-",$data)[1]]=null;
	unset($info['bot']['wk'][explode ("-",$data)[1]]);
	saveInfo ();
	$tx ="تم الحذف بنجاح";
	edit($tx, mkBtn ($btn));
} else if ($data == "wk") {
	$btn = array();
	$btn[]= 
		array(
			"الاسم" => "ntn",
			"اليوزر" => "ntn"
		);
	
	foreach ($info['bot']['wk'] as $k => $v ) {
		$btn[] = 
			array(
				"{$v['name']}"??""=> "ntn",
				"{$v['user']}"??"" => "ntn",
			);
	}
	$btn[]=array (
			"رجوع" => "back"
		);
	$tx ="جميع الوكلاء";
	edit($tx, mkBtn ($btn));
} else if ($data == "addContry" || $exData[0] == 'next' || $exData[0] == 'before') {
	#Lista:
	$get = $api->getCountries();
	$tx="الدول المتاحة\n";
	if ($data == "addContry" ) {
		$start = 0;
	} else if ($exData[0] == 'next') {
		$start= $exData[1];
		if ($start > count ($get)) {
			bot('answercallbackquery',[
				'callback_query_id'=>$update->callback_query->id,
				'show_alert'=>true,
				'text' => "لا توجد قائمة تاليه"
			]);
			exit;
		}
	} else if ( $exData[0] == 'before') {
		$start= $exData[1];
		if($start >= 30) {$start -=30;}
		else if ($start > 0) { $start = 0;}
		else  {
			bot('answercallbackquery',[
				'callback_query_id'=>$update->callback_query->id,
				'show_alert'=>true,
				'text' => "لا توجد قائمة سابقة"
			]);
			exit;
		}
	}	
	$end = $start + 30;
	$btn =array();
	$bt=array();
	$count=-1;
	$a=1;
	$btn[]=[['text' => "الدولة | الكلفة",'callback_data' => "test" ],['text' => "الدولة | الكلفة ",'callback_data' => "change#$z"]];
	foreach ($get as $k => $p) {
		$count++;
		if($count < $start) continue;
		else if ($count >= $end) break;
		$z = isset($contries[$k])? "✅" : "❌";
		//$btn[]=[['text' => "{$names[$k]} | $p",'callback_data' => "change#$k#$data" ],['text' => "$z",'callback_data' => "change#$k#$data"]];
		if($a%2==0) {
			$bt[]=['text' => "{$names[$k]} | $p",'callback_data' => "add#$k#$data" ];
			$btn[]=$bt;
			$bt=[];
		} else {
			$bt[]=['text' => "{$names[$k]} | $p",'callback_data' => "add#$k#$data" ];
		}
		$a++;
	}
	if(count($bt)>0) $btn[]=$bt;
	$btn[]=array(
		['text' => "السابق ⏮️",'callback_data' => "before#{$start}" ],
		['text' => "⏭️التالي ",'callback_data' => "next#{$end}" ],
	);
	$btn[]=array(
		['text' =>" رجوع 🔙",'callback_data' => "back" ],
	);
	
	edit($tx,$btn);
} /*else if ($exData[0] == "change") {
	if ( !isset($contries[$exData[1]])) {
		$contries[$exData[1]]=$exData[1];
	} else {
		unset($contries[$exData[1]]);
	}
	saveContries ();
	unset($exData[1]);
	unset($exData[0]);
	$data = implode ("#",$exData);
	$exData=explode ("#",$data);
	goto Lista;
}*/ else if ($data == "stats") {
	$a=$stats['all']['trybuy']??0;
	$b= $stats['all']['buy']??0;
	$tx = "عدد عمليات الشراء $a\nعدد العمليات الناجحة$b";
	edit ($tx,$back);
} else if ($exData[0] == "add") {
	$info[$id]['action']="addContry";
	$info[$id]['contry']=$exData[1];
	saveInfo();
	$tx = "قم بارسال سعر البيع";
	$btn =mkBtn (
		array(
			"رجوع🔙" => $exData[2]
		)
	);
	edit($tx,$btn);
} else if ($text && $info[$id]['action']=="addContry") {
	if ( is_numeric($text) && $text > 0 ) {
		$tx = "تمت اضافة الدولة بنجاح";
		$contries[$info[$id]['contry']]=$text;
		$info[$id]['action']="";
		$info[$id]['contry']="";
		saveInfo();
		saveContries ();
	} else {
		$tx="قم بارسال قيمة رقمية اكبر من الصفر";
	}
	send($tx,$back);
} else if ($data == "remContry" || $exData[0] == 'NEXT' || $exData[0] == 'BEFORE') {
	$tx="الدول المتاحة\n";
	if ($data == "remContry") {
		$start = 0;
	} else if ($exData[0] == 'NEXT') {
		$start= $exData[1];
		if ($start > count ($contries)) {
			bot('answercallbackquery',[
				'callback_query_id'=>$update->callback_query->id,
				'show_alert'=>true,
				'text' => "لا توجد قائمة تاليه"
			]);
			exit;
		}
	} else if ( $exData[0] == 'BEFORE') {
		$start= $exData[1];
		if($start >= 30) {$start -=30;}
		else if ($start > 0) { $start = 0;}
		else  {
			bot('answercallbackquery',[
				'callback_query_id'=>$update->callback_query->id,
				'show_alert'=>true,
				'text' => "لا توجد قائمة سابقة"
			]);
			exit;
		}
	}	
	$end = $start + 30;
	$btn =array();
	
	$tx="
	قم باختيار الدولة التي تريد خذفها
	";
	$bt=array();
	$count=-1;
	$a=0;
	foreach ($contries as $k => $p) {
		$count++;
		if($count < $start) continue;
		else if ($count >= $end) break;	
		/*$p = $prices[$k];
		$p = $p+($p*$revenue/100);*/
		if($a%2==0) {
			$btn[]=$bt;
			$bt=[];
			$bt[]=['text' => "{$names[$k]} | $p",'callback_data' => "remove#$k"];
		} else {
			$bt[]=['text' => "{$names[$k]} | $p",'callback_data' => "remove#$k" ];
		}
		$a++;
		//$tx .= "$k | {$names[$k]} | $p \n ";	
	}
	if(count($bt)>0) $btn[]=$bt;
	$btn[]=array(
		['text' => "السابق ⏮️",'callback_data' => "BEFORE#{$start}" ],
		['text' => "⏭️التالي ",'callback_data' => "NEXT#{$end}" ],
	);
	$btn[]=array(
	['text' => "رجوع 🔙",'callback_data' => "back" ],
	);
	edit($tx,$btn);
} else if ($exData[0] == "remove") {
	//send($exData[1]);
	$contries[$exData[1]]=null;
	unset($contries[$exData[1]]);
	saveContries ();
	//send(json_encode($contries));
	$tx="تم الحذف بنجاح";
	edit($tx,$back);
}























if ($data == "broadcast") {
    bot("EditMessageText",[
         "chat_id" => $chat_id, 
         'message_id' => $message_id,
         "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\n 
||======احصائيات البوت=====||

 --> **$memm** <--

||=====احصائيات البوت======||
",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "back"]],
            ]
        ])
    ]);
}




// كود الاشتراك الإجباري

if ($data == "eshterak") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nمرحبا بك في قسم الاشتراك الإجباري",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "+ اضف قناه +", 'callback_data' => "esh"], ['text' => "- حذف قناه -", 'callback_data' => "unesh"]],
                [['text' => " عرض قنوات الاشتراك الإجباري 👁", 'callback_data' => "eshh"]],
                [['text' => " حذف جميع القنوات ❗", 'callback_data' => "uneshh"]],
                [['text' => "• رجوع •", 'callback_data' => "back"]],
            ]
        ])
    ]);
    setMode("");
    exit;
}

if ($data == "esh") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nارفع البوت ادمن في القناة واعطه صلاحية دعوة مستخدمين.\nثم ارسل ايدي القناة أو قم بتوجيه أي رسالة منها.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "eshterak"]],
            ]
        ])
    ]);
    setMode("esh");
    exit;
} elseif ($data == "unesh") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nارسل ايدي القناة التي تريد حذفها من الاشتراك الإجباري.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "eshterak"]],
            ]
        ])
    ]);
    setMode("unesh");
    exit;
}

if ($data == "eshh") {
    $eshterak_list = "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nقنوات الاشتراك الإجباري :\n\n";
    $nombre = 0;
    foreach ($eshterak as $channel_id) {
        $chat_info = bot("getChat", ['chat_id' => $channel_id])->result;
        $nombre++;
        $title = $chat_info->title;
        $invite_link = $chat_info->invite_link ?? bot("exportChatInviteLink", ['chat_id' => $channel_id])->result;
        $eshterak_list .= "[$title]($invite_link)\n\n";
    }

    $message_text = "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nالقنوات *" . ($nombre - 1) . "*\n\n" . $eshterak_list;

    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => $message_text,
        "parse_mode" => "markdown",
        "disable_web_page_preview" => true,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "eshterak"]],
            ]
        ])
    ]);
} elseif ($data == "uneshh") {
    unlink("eshterak.txt");
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nتم حذف جميع قنوات الاشتراك الإجباري.",
        "parse_mode" => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "eshterak"]],
            ]
        ])
    ]);
}


$mode = getMode();

if ($message && $mode == "esh") {
    $pattern = '/\b\d{7,13}\b/';
    
    if (preg_match($pattern, $text, $matches)) {
        if (isset($update->message->forward_from_chat)) {
            $channel_id = $update->message->forward_from_chat->id;
        } else {
            $channel_id = "-100" . $text;
        }
        
        $channel_info = json_decode(file_get_contents("https://api.telegram.org/bot" . API_KEY . "/getChatMember?chat_id=$channel_id&user_id=$bot_id"), true);
        if ($channel_info['result']['status'] != 'administrator') {
            bot("sendmessage", [
                "chat_id" => $chat_id,
                "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nحدث خطأ ❌
يرجى رفع البوت ادمن في القناة أولاً ❗",
                "parse_mode" => "Markdown",
            ]);
            exit;
        }
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nتمت إضافة القناة لقائمة الاشتراك الاجباري ✅",
            'parse_mode' => "markdown",
        ]);
        file_put_contents("eshterak.txt", $channel_id . "\n", FILE_APPEND);
        setMode("");
        exit;
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nحدث خطأ أو الأيدي غير صحيح، يرجى إرسال الأيدي مجددًا.",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• الغاء •", 'callback_data' => "eshterak"]],
                ]
            ])
        ]);
    }
} elseif ($message && $mode == "unesh") {
    $pattern = '/\b\d{8,12}\b/';

    if (preg_match($pattern, $text, $matches)) {
        if (isset($update->message->forward_from_chat)) {
            $channel_id = $update->message->forward_from_chat->id;
        } else {
            $channel_id = "-100" . $text;
        }
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nتم حذف القناة من قائمة الاشتراك الاجباري.",
            'parse_mode' => "markdown",
        ]);
        $eshterak = str_replace($channel_id, "", $eshterak);
        file_put_contents('eshterak.txt', $eshterak);
        setMode("");
        exit;
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nحدث خطأ أو الأيدي غير صحيح، يرجى إرسال الأيدي مجددًا.",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• الغاء •", 'callback_data' => "eshterak"]],
                ]
            ])
        ]);
    }
}




















if ($data == "msg") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "• اختر نوع الاذاعة •",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• اذاعة بالتوجيه •", 'callback_data' => "Radio_guidance"], ['text' => "• اذاعة بالتوجيه والتثبيت •", 'callback_data' => "radio_routing_install"]],
                [['text' => "• اذاعة عاديه •", 'callback_data' => "Regular_radio"], ['text' => "• اذاعة عاديه بالتثبيت •", 'callback_data' => "Radio_Aidiya_Install"]],
                [['text' => "• رجوع •", 'callback_data' => "back"]]
            ]
        ])
    ]);
    unlink("Regular.txt");
}

$Regular = file_get_contents("Regular.txt");

if($data == "Radio_guidance"){
    bot('EditMessageText',[
        'chat_id'=>$chat_id,
        'message_id'=>$message_id,
        'text'=>"قم برسال التوجيه الان 💚",
        'reply_markup'=>json_encode([ 
            'inline_keyboard'=>[
                [['text'=>'🔙' ,'callback_data'=>"msg"]],
            ]
        ])
    ]);
    file_put_contents("Regular.txt","Radio_guidance");
}
if($message and $Regular == "Radio_guidance"){
    bot("sendmessage",[
        "chat_id"=>$chat_id,
        "text"=>"تم توجيه الرساله ",
        'reply_markup'=>json_encode([ 
            'inline_keyboard'=>[
                [['text'=>'🔙' ,'callback_data'=>"msg"]],
            ]
        ])
    ]);
    for($i=0;$i<count($mem); $i++){
        bot('forwardMessage', [
            'chat_id'=>$mem[$i],
            'from_chat_id'=>$chat_id,
            'message_id'=>$message->message_id
        ]);
        unlink("Regular.txt");
    }
    exit;
}

if($data == "Regular_radio"){
    bot('EditMessageText',[
        'chat_id'=>$chat_id,
        'message_id'=>$message_id,
        'text'=>"قم برسال المراد الاذاعه له الان 💛",
        'reply_markup'=>json_encode([ 
            'inline_keyboard'=>[
                [['text'=>'🔙' ,'callback_data'=>"msg"]],
            ]
        ])
    ]);
    file_put_contents("Regular.txt","Regular_radio");
}
if($text and $Regular == "Regular_radio"){
    bot("sendmessage",[
        "chat_id"=>$chat_id,
        "text"=>'تم النشر بنجاح  ✅',
        'reply_markup'=>json_encode([ 
            'inline_keyboard'=>[
                [['text'=>'🔙' ,'callback_data'=>"Regular"]],
            ]
        ])
    ]);
    for($i=0;$i<count($mem); $i++){
        bot('sendMessage', [
            'chat_id'=>$mem[$i],
            'text'=>$text
        ]);
        unlink("Regular.txt");
    }
    exit;
}

if($data == "radio_routing_install"){
    bot('EditMessageText',[
        'chat_id'=>$chat_id,
        'message_id'=>$message_id,
        'text'=>"قم بارسال التوجيه للتثبيت الآن 💜",
        'reply_markup'=>json_encode([ 
            'inline_keyboard'=>[
                [['text'=>'🔙' ,'callback_data'=>"msg"]],
            ]
        ])
    ]);
    file_put_contents("Regular.txt","radio_routing_install");
}
if($message and $Regular == "radio_routing_install"){
    bot("sendmessage",[
        "chat_id"=>$chat_id,
        "text"=>"تم توجيه الرساله وتثبيتها",
        'reply_markup'=>json_encode([ 
            'inline_keyboard'=>[
                [['text'=>'🔙' ,'callback_data'=>"msg"]],
            ]
        ])
    ]);
    for($i=0;$i<count($mem); $i++){
        $b = bot('forwardMessage', [
            'chat_id'=>$mem[$i],
            'from_chat_id'=>$chat_id,
            'message_id'=>$message->message_id
        ]);
        bot('pinChatMessage', [
            'chat_id' => $mem[$i],
            'message_id' => $b->result->message_id,
        ]);
        unlink("Regular.txt");
    }
    exit;
}

if($data == "Radio_Aidiya_Install"){
    bot('EditMessageText',[
        'chat_id'=>$chat_id,
        'message_id'=>$message_id,
        'text'=>"قم بإرسال الرسالة العادية لتثبيتها الآن 🧡",
        'reply_markup'=>json_encode([ 
            'inline_keyboard'=>[
                [['text'=>'🔙' ,'callback_data'=>"msg"]],
            ]
        ])
    ]);
    file_put_contents("Regular.txt","Radio_Aidiya_Install");
}
if($text and $Regular == "Radio_Aidiya_Install"){
    bot("sendmessage",[
        "chat_id"=>$chat_id,
        "text"=>"تم نشر وتثبيت الرسالة بنجاح ✅",
        'reply_markup'=>json_encode([ 
            'inline_keyboard'=>[
                [['text'=>'🔙' ,'callback_data'=>"Regular"]],
            ]
        ])
    ]);
    for($i=0;$i<count($mem); $i++){
        $b = bot('sendMessage', [
            'chat_id'=>$mem[$i],
            'text'=>$text
        ]);
        bot('pinChatMessage', [
            'chat_id' => $mem[$i],
            'message_id' => $b->result->message_id,
        ]);
        unlink("Regular.txt");
    }
    exit;
}









if (explode("_", $data)[0] == "communication") {
    if ($id != $admin) {
        bot("EditMessageText", [
            "chat_id" => $chat_id,
            'message_id' => $message_id,
            "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nلا يمكنك التعامل مع هذا الامر لأنه يخص مطور البوت وحده ",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• رجوع •", 'callback_data' => "back"]],
                ]
            ])
        ]);
        exit;
    }
    
    if (explode("_", $data)[1] == "on") {
        file_put_contents("communication.txt", "on");
        bot("EditMessageText", [
            "chat_id" => $chat_id,
            'message_id' => $message_id,
            "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\n تم تفعيل التواصل √",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• رجوع •", 'callback_data' => "back"]],
                ]
            ])
        ]);
    } else if (explode("_", $data)[1] == "off") {
        unlink("communication.txt");
        bot("EditMessageText", [
            "chat_id" => $chat_id,
            'message_id' => $message_id,
            "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\n تم تعطيل التواصل ✗",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• رجوع •", 'callback_data' => "back"]],
                ]
            ])
        ]);
    }
}













// = = = = = = رفع ادمن = = = = = = = = //
if ($data == "ksmadmin") {
    if($id != $admin) {
        bot("EditMessageText", [
            "chat_id" => $chat_id,
            'message_id' => $message_id,
            "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nلا يمكنك الوصول الى هذا القسم **وحده صاحب البوت يمكنه الوصول لهذا القسم**",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• رجوع •", 'callback_data' => "back"]],
                ]
            ])
        ]);
        exit;
    }
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nمرحبا بك في قسم الادمنيه",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رفع ادمن •", 'callback_data' => "admins"], ['text' => "• حذف ادمن •️", 'callback_data' => "unadmins"]],
                [['text' => "• عرض جميع الادمنيه •", 'callback_data' => "adminss"]],
                [['text' => "• حذف جميع الادمنيه •", 'callback_data' => "unadminss"]],
                [['text' => "• رجوع •", 'callback_data' => "back"]],
            ]
        ])
    ]);
}

if ($data == "admins") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nحسنا ارسل الايدي بتاعه حالا",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• الغاء •", 'callback_data' => "ksmadmin"]],
            ]
        ])
    ]);
    setMode("admins");
    exit;
} elseif ($data == "unadmins") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nحسنا ارسل ايدي البرنس دا حالا",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• الغاء •", 'callback_data' => "ksmadmin"]],
            ]
        ])
    ]);
    setMode("unadmins");
    exit;
}

if ($text && $mode == 'admins') {
    $pattern = '/\b\d{8,12}\b/';
    if (preg_match($pattern, $text, $matches)) {
        if (!in_array($text, $admins)) {
            bot("sendmessage", [
                "chat_id" => $chat_id,
                "text" => "تم رفع [العضو](tg://user?id=$text) ادمن بنجاح 🌹",
                'parse_mode' => "markdown",
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => "• رجوع •", 'callback_data' => "ksmadmin"]],
                    ]
                ])
            ]);
            bot("sendmessage", [
                "chat_id" => $text,
                "text" => "مرحبا.. 🌹\nتم رفعك ادمن في البوت بواسطة [المطور](tg://user?id=$asmin) ♥",
                'parse_mode' => "markdown",
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => "• اظهار الاعدادات •", 'callback_data' => "back"]],
                    ]
                ])
            ]);
            file_put_contents("admins.txt", $text . "\n", FILE_APPEND);
            setMode("");
        } else {
            bot('sendmessage', [
                'chat_id' => $chat_id,
                'text' => "• [الادمن](tg://user?id=$text) موجود في القائمة مسبقاً ⚙️",
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => "• رجوع •", 'callback_data' => "ksmadmin"]],
                    ]
                ])
            ]);
        }
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "حدث خطأ او ان الايدي خاطئ\nارسل الايدي مجددا",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• الغاء •", 'callback_data' => "ksmadmin"]],
                ]
            ])
        ]);
    }
} elseif ($text && $mode == 'unadmins') {
    $pattern = '/\b\d{8,12}\b/';
    if (preg_match($pattern, $text, $matches)) {
        if (in_array($text, $admins)) {
            bot("sendmessage", [
                "chat_id" => $chat_id,
                "text" => "تم سحب الادمن من [العضو](tg://user?id=$text) بنجاح 💯",
                'parse_mode' => "markdown",
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => "• رجوع •", 'callback_data' => "ksmadmin"]],
                    ]
                ])
            ]);
            bot("sendmessage", [
                "chat_id" => $text,
                "text" => "تم سحب الادمنيه منك بواسطة [المطور](tg://user?id=$asmin)",
                'parse_mode' => "markdown",
            ]);
            $admins = str_replace($text, "", $admins);
            file_put_contents('admins.txt', $admins);
            setMode("");
        } else {
            bot('sendmessage', [
                'chat_id' => $chat_id,
                'text' => "• هذا مجرد [العضو](tg://user?id=$text) وليس ادمن اصلا ⚙️",
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => "• رجوع •", 'callback_data' => "ksmban"]],
                    ]
                ])
            ]);
        }
    } else {
        bot("sendmessage", [
            "chat_id" => $chat_id,
            "text" => "حدث خطأ او ان الايدي خاطئ\nارسل الايدي مجددا",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• الغاء •", 'callback_data' => "abdo"]],
                ]
            ])
        ]);
    }
}
if ($data == "adminss") {
    $admins = file("admins.txt", FILE_IGNORE_NEW_LINES);
    $names = '';

    foreach ($admins as $id) {
        $id = trim($id);
        $user_info = bot('getChatMember', ['chat_id' => $id, 'user_id' => $id])->result;

        if ($user_info) {
            $username = $user_info->user->username ?? '';
            $name = $user_info->user->first_name ?? '';
            $names .= "ID: $id\n";
            $names .= "Username: " . ($username ? "@$username" : "N/A") . "\n";
            $names .= "Name: [$name](tg://user?id=$id)\n\n";
        } else {
            $names .= "User ID: $id\n";
            $names .= "No user info found\n\n";
        }
    }

    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\n*الادمنيه* :\n$names",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "ksmadmin"]],
            ]
        ])
    ]);
}

if ($data == "unadminss") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nتم حذف جميع الادمنيه بنجاح",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "ksmadmin"]],
            ]
        ])
    ]);

    if (file_exists("admins.txt")) {
        unlink("admins.txt");
    }
}










if (explode("|", $data)[1] == "Reply_message") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nحسنا عزيزي المالك، ارسل الرد الذي تريده",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• الغاء الامر •", 'callback_data' => "elgaa"]],
            ]
        ])
    ]);
    $id_member = explode("|", $data)[0]; 
    setMode("Reply_message_" . $id_member); 
    exit;
} elseif ($data == "elgaa") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nتم الغاء الامر ✓\n -/start",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• ارجوع •", 'callback_data' => "back"]],
            ]
        ])
    ]);
    setMode("");
    exit;
} elseif ($text and strpos($mode, "Reply_message_") === 0) { 
    $id_member = str_replace("Reply_message_", "", $mode); 
    bot("sendmessage", [
        "chat_id" => $chat_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nتم ارسال رسالتك بنجاح ✓",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• ارجوع •", 'callback_data' => "back"]],
            ]
        ])
    ]);
    bot("sendmessage", [
        "chat_id" => $id_member,
        "text" => "**تم استلام رد من المطور:** \n" . $text,
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• ارسال رسالة اخرى •", 'callback_data' => "communication"]],
            ]
        ])
    ]);
    setMode("");
    exit;
}

















if (explode("_", $data)[0] == "onoroff") {
    if ($id != $admin) {
        bot("EditMessageText", [
            "chat_id" => $chat_id,
            'message_id' => $message_id,
            "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\nلا يمكنك التعامل مع هذا الامر لأنه يخص مطور البوت وحده ",
            'parse_mode' => "markdown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• رجوع •", 'callback_data' => "back"]],
                ]
            ])
        ]);
        exit;
    }

    $status = file_exists("onoroff.txt") ? "off" : "on";

    if (explode("_", $data)[1] == "on") {
        if ($status == "on") {
            $message = "البوت يعمل بالفعل √";
        } else {
            unlink("onoroff.txt");
            $message = "تم اعادة تشغيل البوت بنجاح √";
        }
    } elseif (explode("_", $data)[1] == "off") {
        if ($status == "off") {
            $message = "البوت في وضع الصيانة بالفعل 🔧";
        } else {
            file_put_contents("onoroff.txt", "off");
            $message = "تم وضع البوت تحت الصيانة 🔧";
        }
    }

    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "[ᶠʳᵒᵐ ʲᵘˢᵗ ᴬᵇᵈᵒ](tg://user?id=6969088145)\n" . $message,
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "back"]],
            ]
        ])
    ]);
    exit;
}


















if (!file_exists($azrar_file)) {
    file_put_contents($azrar_file, json_encode([]));
}



if ($data == "add_button") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "أرسل اسم الزر الآن:",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• الغاء •", 'callback_data' => "back"]],
            ]
        ])
    ]);
    file_put_contents("step.txt", "waiting_name");
    setMode("waiting_name");
    exit;
}

if ($mode == "waiting_name") {
    setMode("waiting_link|$text");
    bot("sendmessage", [
        "chat_id" => $chat_id,
        "text" => "أرسل الرابط الآن:",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• الغاء •", 'callback_data' => "back"]],
            ]
        ])
    ]);
    exit;
}

if (file_exists("step.txt") && strpos($mode, "waiting_link|") === 0) {
    $step_data = explode("|", $mode);
    $name = $step_data[1];
    $link = $text;

    $buttons[] = ['text' => $name, 'url' => $link];
    file_put_contents($azrar_file, json_encode($buttons));
    setMode("");

    bot("sendmessage", [
        "chat_id" => $chat_id,
        "text" => "تم إضافة الزر بنجاح.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "back"]],
            ]
        ])
    ]);
    exit;
}


if ($data == "delete_button") {
    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "أرسل اسم الزر الذي تريد حذفه:",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• الغاء •", 'callback_data' => "back"]],
            ]
        ])
    ]);
    setMode("delete_name");
    exit;
}

if ($mode == "delete_name") {
    $name_to_delete = $text;
    $updated_buttons = array_filter($buttons, function ($button) use ($name_to_delete) {
        return $button['text'] !== $name_to_delete;
    });

    file_put_contents($azrar_file, json_encode(array_values($updated_buttons)));
    setMode("");

    bot("sendmessage", [
        "chat_id" => $chat_id,
        "text" => "تم حذف الزر بنجاح.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "back"]],
            ]
        ])
    ]);
    exit;
}

if ($data == "delete_buttons") {
    file_put_contents($azrar_file, json_encode([]));

    bot("EditMessageText", [
        "chat_id" => $chat_id,
        'message_id' => $message_id,
        "text" => "تم حذف جميع الأزرار بنجاح.",
        'parse_mode' => "markdown",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "• رجوع •", 'callback_data' => "back"]],
            ]
        ])
    ]);
    exit;
}



