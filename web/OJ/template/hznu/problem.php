<?php
/**
* This file is created
* by yybird
* @2016.03.23
* last modified
* by yybird
* @2016.05.25
* by D_Star
* @2016.08.xx
**/
?>


<?php $title=$view_title;?>

<?php

if (isset($_GET['cid'])) require_once("contest_header.php");
else require_once("header.php");

//比赛期间暂时关闭
if (!isset($_GET['cid']) && !HAS_PRI('enter_admin_page') && $OJ_FORBIDDEN) {
    $view_errors = "The page is temporarily closed!";
    return require_once("error.php");
}

function sss($str){
    $after = preg_replace( '/<[^<]+?>/' ,'FUCK$0FUCK', $str);
    $after = preg_replace( '/(?<!FUCK)</' ,'&lt;', $after);
    $after = preg_replace( '/FUCK(?=<)/' ,'', $after);
    $after = preg_replace( '/>(?!FUCK)/' ,'&gt;', $after);
    $after = preg_replace( '/(?<=>)FUCK/' ,'', $after);
    return $after;
}
?>

<!-- Sample Input 和 Sample Output 的背景色 start -->
<style type="text/css">
.sampledata {
    /*background: none repeat scroll 0 0 rgba(0,0,0,.075);*/
    font-family: Monospace;
    white-space: pre-wrap;
    font-size: 10pt;
}
.sample-outer {
    /* border: 1px solid; */
    margin-bottom: 20px;
    border-bottom: 1px solid #ccc;
}
.sample-bg {
    background: #f0efef;
    color: #7e2222;
    /* border-top: 1px solid; */
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    padding: 5px;
    line-height: 1;
}
.sample-title {
    background: white;
    border: 1px solid #ccc;
    padding-left: 5px;
    padding-right: 5px;
    padding-top: 1px;
    padding-bottom: 1px;
}
</style>
<!-- Sample Input 和 Sample Output 的背景色 end -->

<div class="am-container">
    <?php
//the header of problem page
    if(!isset($_GET['cid'])) {
        echo <<<HTML
        <div class="am-avg-md-1" style="margin-top: 20px; margin-bottom: 20px;">
        <ul class="am-nav am-nav-tabs">
        <li class="am-active"><a href="/OJ/problemset.php">Problems</a></li>
        <li><a href="/OJ/status.php">Status</a></li>
        <li><a href="/OJ/ranklist.php">Standings</a></li>
        </ul>
        </div>
HTML;
    }
    else {
        echo "<ul class=\"am-nav am-nav-tabs\" style='margin-top: 30px;'>";
        for($i = 0 ; $i<$problem_cnt ; ++$i) {
            $label = PID($i);
            $class = $i == $pid? "am-active": "";
            echo <<<HTML
            <li class="$class"><a href="problem.php?cid=$cid&pid=$i">$label</a></li>
HTML;
        }
        echo "</ul>";
    }
    ?>
    <h1 style="text-align:center;margin-top:40px;">
        <?php
        echo $row->title;
        ?>
        <!-- is contest problem -->
        <?php
        if($has_accepted) {
            echo "<span class='am-badge am-badge-success am-text-lg'><i class='am-icon-check'></i>AC</span>";
        }
        $now=time();
        ?>
        <?php if ($is_practice || isset($_GET['cid']) && ($now>$end_time || HAS_PRI("edit_contest"))): ?>
        <span class="am-badge am-badge-primary am-text-lg">
            <a href="problem.php?id=<?php echo $real_id ?>" style="color: white;">
                <?php echo $real_id ?>
            </a>
        </span>
    <?php endif ?>
</h1>
<?php
if ($show_tag && !isset($_GET['cid'])) {
    ?>
    <form id='tagForm' class='am-form am-form-inline' style="text-align:center" action=''>
        <div class="am-form-group">
            <?php
            echo "<span><i class='am-icon-tag'></i> Tags:</span>";
            for ($i=0; $i<count($problem_tag); ++$i) {
                if ($i == 0) echo "&nbsp;&nbsp;<span class='am-badge am-badge-danger am-round'>".$problem_tag[$i]."</span>";
                else if ($i == 1) echo "&nbsp;&nbsp;<span class='am-badge am-badge-warning am-round'>".$problem_tag[$i]."</span>";
                else if ($i == 2) echo "&nbsp;&nbsp;<span class='am-badge am-badge-primary am-round'>".$problem_tag[$i]."</span>";
                else if ($i == 3) echo "&nbsp;&nbsp;<span class='am-badge am-badge-secondary am-round'>".$problem_tag[$i]."</span>";
                else if ($i == 4) echo "&nbsp;&nbsp;<span class='am-badge am-badge-success am-round'>".$problem_tag[$i]."</span>";
                else echo "&nbsp;&nbsp;<span class='am-badge am-badge-default am-round'>".$problem_tag[$i]."</span>";
            }
            ?>
        </div>
        <?php
            //暂时关闭
            if ($is_solved && 0) {
        ?>
            &nbsp;&nbsp;
            <div class='am-form-group'><span> My tag: &nbsp;</span></div>
            <div class='am-form-group'>
                <input class='col-sm-9' type='text' style="width:80px;height:20px;font-size:10px" value='<?php echo $my_tag; ?>' name='myTag'></input>
            </div>
            <div class='am-form-group'>
                <button type='button' id='tagSubmit' style='border:none;background-color:transparent;'><i class='am-icon-check' ></i></button>
            </div>
            <input type='hidden' value='<?php echo $id ?>' name='id'></input>
        <?php
            }
            echo "</form>";
        }
        ?>


    <div style="text-align:center;">
        Time Limit:&nbsp;&nbsp;<span class="am-badge am-badge-warning"><?php echo $row->time_limit?> s</span>
        &nbsp;&nbsp;&nbsp;&nbsp; Memory Limit: &nbsp;&nbsp;<span class="am-badge am-badge-warning"><?php echo $row->memory_limit?> MB</span></span>
        <?php if($row->spj) echo "<span class='am-badge am-badge-primary'>Special Judge</span>"?>
    </div>
    <div style="text-align:center;">
        Submission：<span class="am-badge am-badge-secondary"><?php echo $submit_num?></span>&nbsp;&nbsp;&nbsp;&nbsp;
        AC：<span class="am-badge am-badge-success"><?php echo $ac_num?></span>&nbsp;&nbsp;&nbsp;&nbsp;
        <?php if(!isset($contest_score)):?>
            <?php
            $score_class = "am-badge-default";
            if ($row->score >= 82) $score_class='am-badge-danger';
            else if ($row->score >= 64) $score_class='am-badge-warning';
            else if ($row->score >= 46) $score_class='am-badge-primary';
            else if ($row->score >= 28) $score_class='am-badge-secondary';
            ?>
            Score：<span class='am-badge <?php echo $score_class ?>'><?php echo $row->score?></span>
            <?php else:?>
                Score：<span class='am-badge am-badge-success'><?php echo $contest_score?></span>
            <?php endif;?>

        </div>
        <br/>

        <!-- 提交等按钮 start -->
        <div class="am-text-center">
            <a href="
            <?php
            if ($pr_flag){
                echo "submitpage.php?id=$id";
                }else{
                    echo "submitpage.php?cid=$cid&pid=$pid&langmask=$langmask";
                }
                ?>
                " style="color:white">
                <button type="button" class="am-btn am-btn-sm am-btn-success ">Submit</button>
            </a>

            <?php
            if(!isset($_GET['cid']) || $is_practice==1) {
                echo<<<HTML
                <a href="problemstatus.php?id={$row->problem_id}" style="color:white">
                <button type="button" class="am-btn am-btn-sm am-btn-primary ">
                Codes
                </button>
                </a>
HTML;
            }
            if (HAS_PRI("edit_".$set_name."_problem")) {
                echo<<<HTML
                <a href="/OJ/admin/problem_edit.php?id=$row->problem_id&getkey={$_SESSION['getkey']}" style='color:white'>
                <button type='button' class='am-btn am-btn-sm am-btn-danger '>
                Edit
                </button>
                </a>
                <a href="/OJ/admin/quixplorer/index.php?action=list&dir=$row->problem_id&order=name&srt=yes" style='color:white'>
                <button type='button' class='am-btn am-btn-sm am-btn-warning '>
                Test Data
                </button>
                </a>
HTML;
            }
            ?>
            <button type="button" id="ai-code-tips" class="am-btn am-btn-sm am-btn-secondary ">AI Code Tips</button>
        </div>
        <!-- 提交等按钮 end -->

        <?php
            $str=sss($row->description);
            if($str) {
                //编码转义未解决！
                //$tt=htmlspecialchars($row->description);
                echo <<<HTML
                <h2>Description</h2>
                <p>
                $str
                </p>
HTML;
            }
        ?>

        <?php
            $str=sss($row->input);
            if($str) {
                echo <<<HTML
                <h2>Input</h2>
                <p>
                $str
                </p>
HTML;
        }
        ?>

        <?php
            $str=sss($row->output);
            if($str) {
                echo <<<HTML
                <h2>Output</h2>
                <p>
                $str
                </p>
HTML;
        }
        ?>

        <?php
        $html_samples="";
        foreach ($samples as $sample) {
            $text_input=htmlentities($sample['input']);
            $text_output=htmlentities($sample['output']);
            if($sample['show_after']){
                $html_samples.= <<<HTML
                <div style='color: grey;'>
                Show after trying {$sample['show_after']} times:
                </div>
HTML;
            }
            if($text_input || $text_output) {
                $html_samples.= <<<HTML
                <div class="sample-outer">
                <div class="sample-title">
                input
                <button type = "button" class="sample-copy-btn am-btn am-btn-default am-btn-xs" data-clipboard-text = "{$text_input}"
                style = "height:20px;margin-left:10px;margin-top:-4px;padding-top:2px;padding-left: 8px;padding-right: 8px;">
                Copy
                </button>
                </div>
                <div class="sample-bg">
                    <span class="sampledata">$text_input</span>
                </div>
                <div class="sample-title">
                output
                <button type = "button" class="sample-copy-btn am-btn am-btn-default am-btn-xs" data-clipboard-text = "{$text_output}"
                style = "height:20px;margin-top:-5px;padding-top:2px;padding-left: 8px;padding-right: 8px;">
                Copy
                </button>
                </div>
                <div class="sample-bg">
                    <span class="sampledata">$text_output</span>
                </div>
                </div>
HTML;
            }
        }

        $str=sss($html_samples);
        if($str) {
            echo <<<HTML
            <h2>Samples</h2>
            <p>
            $str
            </p>
HTML;
        }
        ?>


        <?php
        $str=sss($row->hint);
        if($str) {
            echo <<<HTML
            <h2>Hint</h2>
            <p>
            $str
            </p>
HTML;
        }
        ?>

        <?php
if (!isset($_GET['cid'])) {
        $str=sss($row->author);
        if($str) {
            echo <<<HTML
            <h2>Author</h2>
            <div><p>
            <a href='problemset.php?search=$row->author'>$str</a>
            </p></div>
HTML;
        }
}
        ?>

        <?php
if (!isset($_GET['cid'])) { // hide source if the problem is in contest
    $str=sss($row->source);
    if($str) {
        echo <<<HTML
        <h2>Source</h2>
        <div><p>
        <a href='problemset.php?search=$row->source'>$str</a>
        </p></div>
HTML;
    }
}
?>

<?php
// if (isset($_GET['cid'])) {
//     $can_see_video = 0;
// }
?>

<?php if ($can_see_video || HAS_PRI("watch_solution_video")): ?>
    <h2>Solution Video</h2>
    <?php if (file_exists("upload/video/".md5($real_id)."pfb.mp4")): ?>
        <form action="solution_video.php" method="POST">
            <input type="hidden" name="pid" value="<?php echo $real_id ?>" placeholder="">
            <button class="am-btn am-btn-success am-btn-lg">Click To Watch The Video</button>
        </form>
    <?php else: ?>
        <button disabled="1" class="am-btn am-btn-default am-btn-lg">No Solution Video</button>
    <?php endif ?>
    <div style="display: block; color: grey; padding-bottom: 20px;">
        *if you see this button, it means you've submited more than <?php echo $VIDEO_SUBMIT_TIME ?> times.
    </div>
<?php endif ?>

<!-- gptcode start -->
<?php if ($isHaveGPTCode && !isset($_GET['cid'])) : ?>
    <?php
    echo <<<HTML
    <h2>GPT Hint</h2>
    <pre><code>$GPTCode</code></pre>
HTML;
    ?>
<?php endif ?>

<!-- 添加一个具有一定高度的空白 div -->
<div style="height: 20px;"></div>
<!-- gptcode end -->


    <!-- 提交等按钮 start -->
    <div class="am-text-center">
        <a href="
        <?php
        if ($pr_flag){
            echo "submitpage.php?id=$id";
            } else {
                echo "submitpage.php?cid=$cid&pid=$pid&langmask=$langmask";
            }
        ?>
            " style="color:white">
            <button type="button" class="am-btn am-btn-sm am-btn-success ">Submit</button>
        </a>
        <?php
        if(!isset($_GET['cid']) || $is_practice==1) {
            echo<<<HTML
            <a href="problemstatus.php?id={$row->problem_id}" style="color:white">
            <button type="button" class="am-btn am-btn-sm am-btn-primary ">
            Codes
            </button>
            </a>
HTML;
        }
        if (HAS_PRI("edit_".$set_name."_problem")) {
            echo<<<HTML
            <a href="/OJ/admin/problem_edit.php?id=$row->problem_id&getkey={$_SESSION['getkey']}" style='color:white'>
            <button type='button' class='am-btn am-btn-sm am-btn-danger '>
            Edit
            </button>
            </a>
            <a href="/OJ/admin/quixplorer/index.php?action=list&dir=$row->problem_id&order=name&srt=yes" style='color:white'>
            <button type='button' class='am-btn am-btn-sm am-btn-warning '>
            Test Data
            </button>
            </a>

HTML;
        }
        ?>
    </div>
    <!-- 提交等按钮 end -->

</div>

<?php require_once("footer.php"); ?>

<!-- clipboard.js START-->
<script src="/OJ/plugins/clipboard/dist/clipboard.min.js"></script>
<script>
    $(document).ready(function(){
        var clipboard = new ClipboardJS('.sample-copy-btn');
        clipboard.on('success', function(e) {
            // console.info('Action:', e.action);
            // console.info('Text:', e.text);
            // console.info('Trigger:', e.trigger);
            e.clearSelection();
        });
        clipboard.on('error', function(e) {
            // console.error('Action:', e.action);
            // console.error('Trigger:', e.trigger);
        });
    });
</script>
<!-- clipboard.js END-->

<!-- ajax for adding user's own tag -->
<script>
    $("#tagSubmit").click(function(){
        $.ajax({
            url: 'addTag.php',
            type: 'post',
            data: $("#tagForm").serialize(),
            async: false,
            success: function(data,status){
                //alert(data+"\r\n"+status);
                window.location.reload();
            },
        });
    });
</script>

<!-- highlight.js START-->
<!-- <link href="/OJ/plugins/highlight/styles/github-gist.css" rel="stylesheet"> -->
<script src="/OJ/plugins/highlight/highlight.pack.js"></script>
<script src="/OJ/plugins/highlight/highlightjs-line-numbers.min.js"></script>

<style type="text/css">
.hljs-line-numbers {
    text-align: right;
    border-right: 1px solid #ccc;
    color: #999;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
code{
    background: transparent;
}
pre.prettyprint{
    background: transparent;
}
.main{
    background-color: red !important;
}
</style>

<script>
    hljs.initHighlightingOnLoad();
    hljs.initLineNumbersOnLoad();
</script>
<!-- highlight.js END-->


<!--auto folding code START-->
<script type="text/javascript">
    $(document).ready(function(){
        $("pre code").parent().before("<button class='am-btn am-btn-block am-btn-default am-text-xs'>Toggle Code</button>");
        $("pre code").parent().hide(0);

        $("button").click(function(){
            $(this).next().toggle(100);
        });
    });
</script>
<!--auto folding code END-->

<script type="text/javascript">
    const chatbox = window.ChatBox;
    chatbox.add();
    $('#ai-code-tips').click(() => {
        if(!chatbox || !chatbox.chatCore || !chatbox.chatCore.isStop) return;
        const problem_description = `<?php echo str_replace('`', '\`', sss($row->description) . sss($row->input) . sss($row->output)) ?>`;
        const prompt = `下面是一道ACM模式下的编程题目，题目详情如下：${problem_description}，请你使用C语言解决这道题目`;
        chatbox.openAndChat(prompt, `<?php echo $row->title ?>`);
    });
</script>
