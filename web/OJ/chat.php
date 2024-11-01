<?php

// 设置时区为东八区
date_default_timezone_set('PRC');

// 这行代码用于关闭输出缓冲。关闭后，脚本的输出将立即发送到浏览器，而不是等待缓冲区填满或脚本执行完毕。
ini_set('output_buffering', 'off');

// 这行代码禁用了 zlib 压缩。通常情况下，启用 zlib 压缩可以减小发送到浏览器的数据量，但对于服务器发送事件来说，实时性更重要，因此需要禁用压缩。
ini_set('zlib.output_compression', false);

// 这行代码使用循环来清空所有当前激活的输出缓冲区。ob_end_flush() 函数会刷新并关闭最内层的输出缓冲区，@ 符号用于抑制可能出现的错误或警告。
while (@ob_end_flush()) {
}

// 这行代码设置 HTTP 响应的 Content-Type 为 text/event-stream，这是服务器发送事件（SSE）的 MIME 类型。
header('Content-Type: text/event-stream');

// 这行代码设置 HTTP 响应的 Cache-Control 为 no-cache，告诉浏览器不要缓存此响应。
header('Cache-Control: no-cache');

// 这行代码设置 HTTP 响应的自定义头部 X-Accel-Buffering 为 no，用于禁用某些代理或 Web 服务器（如 Nginx）的缓冲。
header('X-Accel-Buffering: no');

require_once './include/static.php';

// 引入敏感词检测类
require './class/Class.DFA.php';

// 引入流处理类
require './class/Class.StreamHandler.php';

// 引入调用 OpenAI 接口类
require './class/Class.ChatGPT.php';

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

// api 和 模型选择
$chat = new OllamaChat(
    "http://$DB_HOST:11434/api/generate",
    "$AI_MODEL"
);

$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
$dfa = new DFA([
    'words_file' => "$DOCUMENT_ROOT/OJ/plugins/code-helper/dict.txt",
]);
$chat->set_dfa($dfa);


// 开始提问
$chat->qa([
    'system' => '你是HznuOnlineJudge的智能代码助手，只负责和代码相关的问题',
    'question' => $question,
]);
