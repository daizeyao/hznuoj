<?php

/**
 * This file is created
 * by yybird
 * @2016.03.22
 * last modified
 * by yybird
 * @2016.03.22
 **/
?>

<?php $title = "Status"; ?>
<?php include "header.php" ?>

<?php

function generate_url($page)
{
    global $str2;
    $link = "status.php?";
    $link .= $str2;
    $link .= "&page=" . $page;
    return $link;
}


?>

<style>
    .am-form-inline>.am-form-group {
        margin-left: 15px;
        margin-right: 15px;
    }

    .am-form-inline {
        margin-bottom: 1.5rem;
    }
</style>
<div class="am-container">
    <div class="am-avg-md-1">
        <div class="am-g" style="margin-top: 20px; margin-bottom: 20px;">
            <ul class="am-nav am-nav-tabs">
                <li><a href="/OJ/problemset.php">Problems</a></li>
                <li class="am-active"><a href="/OJ/status.php">Status</a></li>
                <li><a href="/OJ/ranklist.php">Standings</a></li>
            </ul>
        </div>
    </div>
    <!-- 搜索框 start -->
    <div class="am-g">
        <div class="am-u-md-12">
            <form action="status.php" method="get" class="am-form am-form-inline" role="form" style="float: left;">
                <input type="hidden" name="csrf_token" value="f31605cce38e27bcb4e8a76188e92b3b">
                <div class="am-form-group"><input type="text" class="am-form-field" placeholder=" &nbsp;Problem ID" name="problem_id" value="<?php echo htmlentities($problem_id) ?>"></div>
                <div class="am-form-group">
                    <input type="text" class="am-form-field" placeholder=" &nbsp;User ID" name="user_id" value="<?php echo htmlentities($user_id) ?>">
                    <?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>"; ?>
                </div>
                <div class="am-form-group">
                    <label for="language">Language:</label>
                    <select class="am-round" id="language" name="language" data-am-selected="{searchBox: 1, maxHeight: 400}">
                        <?php
                        if (isset($_GET['language'])) $language = $_GET['language'];
                        else $language = -1;
                        if ($language < 0 || $language >= count($language_name))
                            $language = -1;
                        if ($language == -1)
                            echo "<option value='-1' selected>All</option>";
                        else
                            echo "<option value='-1'>All</option>";
                        $lang_count = count($language_ext);
                        for ($i = 0; $i < $lang_count; ++$i) {
                            $j = $language_order[$i];
                            if ($OJ_LANGMASK & (1 << $j)) {
                                if ($j == $language)
                                    echo "<option value=$j selected>$language_name[$j]</option>";
                                else
                                    echo "<option value=$j>$language_name[$j]</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class="am-form-caret"></span>
                </div>
                <div class="am-form-group">
                    <label for="jresult">Result:</label>
                    <select class="am-round" id="jresult" name="jresult" data-am-selected="{btnWidth: '100px'}">
                        <?php
                        if (isset($_GET['jresult']))
                            $jresult_get = intval($_GET['jresult']);
                        else
                            $jresult_get = -1;
                        if ($jresult_get >= 12 || $jresult_get < 0)
                            $jresult_get = -1;
                        /*if ($jresult_get!=-1){
$sql=$sql."AND `result`='".strval($jresult_get)."' ";
$str2=$str2."&jresult=".strval($jresult_get);
}*/
                        if ($jresult_get == -1)
                            echo "<option value='-1' selected>All</option>";
                        else
                            echo "<option value='-1'>All</option>";
                        for ($j = 0; $j < 12; $j++) {
                            $i = ($j + 4) % 12;
                            if ($i == $jresult_get) echo "<option value='" . strval($jresult_get) . "' selected>" . $jresult[$i] . "</option>";
                            else echo "<option value='" . strval($i) . "'>" . $jresult[$i] . "</option>";
                        }
                        ?>
                    </select>


                    <span class="am-form-caret"></span>
                </div>
                <button type="submit" class="am-btn am-btn-secondary"><span class='am-icon-filter'></span> Filter</button>
            </form>
            <form action="status.php" method="get" class="am-form am-form-inline" role="form" style="float: left;">
                <input type="hidden" name="csrf_token" value="f31605cce38e27bcb4e8a76188e92b3b">
                <button type="submit" class="am-btn am-btn-default">Reset</button>
            </form>
        </div>
    </div>
    <!-- 搜索框 end -->

    <div class="am-avg-md-1">
        <table class="am-table am-table-hover">
            <thead>
                <tr>
                    <th>Run.ID</th>
                    <th>User</th>
                    <th>Prob.ID</th>
                    <th>Result</th>
                    <th>Memory</th>
                    <th>Time</th>
                    <th>Language</th>
                    <th>Code Length</th>
                    <th>Submit Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($view_status as $row) {
                    echo "<tr>";
                    foreach ($row as $table_cell) {
                        echo "<td>";
                        echo $table_cell;
                        echo "</td>";
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


    <!-- 不用啦 -->
    <!-- <div class="am-g am-u-sm-centered am-u-sm-offset-10 am-u-sm-2">
    <ul class="am-pagination">
        <?php echo "<li><a href=\"status.php?" . htmlentities($str2) . "\">Top</a></li>&nbsp;&nbsp;";
        if (isset($_GET['prevtop']))
            echo "<li><a href=\"status.php?" . htmlentities($str2) . "&top=" . intval($_GET['prevtop']) . "\">&laquo; Previous</a></li>&nbsp;&nbsp;";
        else
            echo "<li><a href=\"status.php?" . htmlentities($str2) . "&top=" . ($top + 20) . "\">&laquo; Previous</a></li>&nbsp;&nbsp;";
        echo "<li><a href=\"status.php?" . htmlentities($str2) . "&top=" . $bottom . "&prevtop=$top\">Next &raquo;</a></li>";
        ?>
    </ul>
</div> -->



    <?php if ($view_total_page > 1): ?>
        <!-- 页标签 start -->
        <div class="am-g">
            <ul class="am-pagination am-text-center">
                <?php
                $show_page = array();
                array_push($show_page, 1);
                if ($view_total_page != 1) {
                    array_push($show_page, $view_total_page);
                }
                if ($page != $view_total_page && $page != 1) {
                    array_push($show_page, $page);
                }
                $bit = 1;
                $current_page = $page;
                while (1) {
                    $current_page -= $bit;
                    if ($current_page > 1) {
                        array_push($show_page, $current_page);
                    } else {
                        break;
                    }
                    $bit <<= 1;
                }

                $bit = 1;
                $current_page = $page;
                while (1) {
                    $current_page += $bit;
                    if ($current_page < $view_total_page) {
                        array_push($show_page, $current_page);
                    } else {
                        break;
                    }
                    $bit <<= 1;
                }
                sort($show_page);
                foreach ($show_page as $i) {
                    $link = generate_url($i);
                    if ($page == $i)
                        echo "<li class='am-active'><a href=\"$link\">{$i}</a></li>";
                    else
                        echo "<li><a href=\"$link\">{$i}</a></li>";
                }
                ?>
            </ul>
        </div>
        <!-- 页标签 end -->
    <?php endif ?>

</div>

<?php include "footer.php" ?>


<script>
    async function getSourceCode(solution_id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/OJ/api/getSourceCode.php',
                type: 'GET',
                data: {
                    solution_id: solution_id
                },
                success: function(response) {
                    if (response.source_code) {
                        resolve(response.source_code);
                    } else {
                        reject('Source code not found.');
                    }
                },
                error: function() {
                    reject('Failed to retrieve source code.');
                }
            });
        });
    }
    async function getProblemString(problem_id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/OJ/api/getProblemString.php',
                type: 'GET',
                data: {
                    problem_id: problem_id
                },
                success: function(response) {
                    if (response.problem_description) {
                        resolve(response.problem_description);
                    } else {
                        reject('Problem not found');
                    }
                },
                error: function() {
                    reject('Failed to retrieve Problem.');
                }
            });
        });
    }
    const chatbox = window.ChatBox;
    chatbox && chatbox.add();
    $('.ai-correction').click(async function() {
        const solutionId = $(this).closest('tr').find('td').eq(0).text();
        const problemId = $(this).closest('tr').find('td').eq(2).text();
        const problem_description = (await getProblemString(problemId)).replace(/`/g, '\\`');
        const source_code = await getSourceCode(solutionId);
        if (!chatbox || !chatbox.chatCore || !chatbox.chatCore.isStop) return;
        const prompt = `下面是一道ACM模式下的编程题目，题目详情如下：${problem_description}，下面是一份错误代码${source_code}，请你结合C语言相关知识，帮助学生找出代码中的错误并给出解决方案。`;
        chatbox.openAndChat(prompt, `Solution ${solutionId}纠错`, '你是C语言编程助手');
    });
</script>