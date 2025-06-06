<?php

require_once('../include/cache_start.php');

date_default_timezone_set('PRC');
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', false);
while (@ob_end_flush()) {
}
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');

// 未登录禁止使用
if (isset($_SESSION['user_id'])) {
    $uid = $mysqli->real_escape_string($_SESSION['user_id']);
} else {
    echo "data: " . json_encode(["code" => "499", "error" => "No user"]) . "\n\n";
    flush();
    exit();
}

// 检查请求头，确保是通过 EventSource 发起的请求
if ($_SERVER['HTTP_ACCEPT'] !== 'text/event-stream') {
    header('HTTP/1.1 403 Forbidden');
    echo "This endpoint can only be accessed via EventSource.";
    exit();
}

// 查询是否禁用AI功能
$sql = "SELECT * FROM more_settings";
$res = $mysqli->query($sql);
$row = $res->fetch_assoc();
$ai_module = $row['ai_model'];
if ($ai_module == 0) {
    echo "data: " . json_encode(["code" => "498", "error" => "AI module is disabled"]) . "\n\n";
    flush();
    exit();
}

// 查询当前用户是否正处于AI对话中

$currentTime = time();
if (isset($_SESSION['last_chat_time'])) {
    $lastChatTime = $_SESSION['last_chat_time'];
    if (($currentTime - $lastChatTime) < 5) {
        echo "data: " . json_encode(["code" => "497", "error" => "In Conversation now"]) . "\n\n";
        flush();
        exit();
    }
}
$_SESSION['last_chat_time'] = $currentTime;


require_once '../include/static.php';
require '../class/Class.DFA.php';
require '../class/Class.AICore.php';

echo 'data: ' . json_encode(['time' => date('Y-m-d H:i:s'), 'content' => '']) . PHP_EOL . PHP_EOL;
flush();

$question = urldecode($_GET['q'] ?? '');
if (empty($question)) {
    echo "event: close" . PHP_EOL;
    echo "data: Connection closed" . PHP_EOL . PHP_EOL;
    flush();
    exit();
}
$question = str_ireplace('{[$add$]}', '+', $question);

$system_instruction = urldecode($_GET['sys'] ?? '');
if (empty($system_instruction)) {
    $system_instruction = "你是杭州师范大学在线测评系统智能代码助手，你负责解答C语言的相关问题";
}

// api 和 模型选择 和 交互模式
// $chat = new AICore([
//     "url" =>  "http://$AI_HOST:11434/api/chat",
//     "model" => "$AI_MODEL1",
//     "type" => "chat",
//     "stream" => true
// ]);
$chat = new AICore([
    // "url" => "http://$AI_HOST:". (rand(1, 100) <= 50 ? "8000" : "8001") ."/v1/chat/completions",
    "url" => "http://$AI_HOST/vllm/api/v1/chat/completions",
    "api_key" => "$AI_API_KEY",
    "model" => "$AI_MODEL_VLLM",
    "type" => "vllm-chat",
    "stream" => true
]);

$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
$dfa = new DFA([
    'words_file' => "$DOCUMENT_ROOT/OJ/plugins/hznuojai/dict.txt",
]);
$chat->set_dfa($dfa);

// 开始提问
$chat->qa([
    'system' => $system_instruction,
    'tip' => '你是杭州师范大学在线测评系统智能代码助手，你负责且只负责回答学习、代码相关的问题，并且使用中文回答。格式使用标准markdown，代码部分使用`或者```包围，下面是问题：',
    'question' => $question,
]);

unset($_SESSION['last_chat_time']);

if (file_exists('../include/cache_end.php'))
    require_once('../include/cache_end.php');
