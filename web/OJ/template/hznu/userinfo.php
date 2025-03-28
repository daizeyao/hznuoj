<?php

/**
 * This file is created
 * by yybird
 * @2016.03.26
 * last modified
 * by yybird
 * @2016.04.27
 **/
?>

<?php $title = "User Info"; ?>
<?php
require_once("header.php");
?>
<style type="text/css">
  .first-col {
    width: 120px;
    white-space: nowrap;
  }

  .loading-btn {
    font-size: 20px;
    background: transparent;
    border: 0;
    border-radius: 0;
    text-transform: uppercase;
    font-weight: bold;
    padding: 15px 50px;
    position: relative;
    z-index: 1;
    color: black;
  }

  .loading-btn:hover {
    color: white;
    background: #bee9ff;
    border-radius: 10px;
  }

  .loading-btn:before {
    transition: all 0.8s cubic-bezier(0.7, -0.5, 0.25, 1.5);
    content: '';
    width: 2%;
    height: 100%;
    background: #3BB4F2;
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
    border-radius: 10px;
  }

  .loading-btn span {
    mix-blend-mode: darken;
  }

  .loading-btn:hover:before {
    background: #3BB4F2;
    width: 100%;
  }

  .am-selected-status {
    white-space: normal;
  }
</style>
<div class="am-container" style="margin-top:60px;">
  <!-- userinfo上部分 start -->
  <h1>User Statics</h1>
  <hr>
  <div class='am-g'>
    <!-- 左侧个人信息表格 start -->`
    <div class='am-u-md-4'>
      <form action="modify_userinfo.php" method="post">
        <table class="am-table am-table-striped am-table-compact am-text-center">
          <tbody>
            <tr>
              <td class="first-col">User ID</td>
              <td><?php echo htmlentities($user) ?></td>
            </tr>
            <tr>
              <td class="first-col">Nick Name</td>
              <td><?php echo htmlentities($nick) ?></td>
            </tr>
            <tr>
              <td class="first-col">Rank</td>
              <td><?php echo $Rank ?></td>
            </tr>
            <tr>
              <td class="first-col">Douqi</td>
              <td><?php echo round($strength) ?></td>
            </tr>
            <tr>
              <td class="first-col">Level</td>
              <td><?php echo $level ?></td>
            </tr>
            <tr>
              <td class="first-col">Total AC</td>
              <td><a href="status.php?user_id=<?php echo htmlentities($user) ?>&jresult=4"><?php echo $local_ac ?></a></td>
            </tr>
            <tr>
              <td class="first-col">School</td>
              <td><?php echo htmlentities($school) ?></td>
            </tr>
            <tr>
              <td class="first-col">Email</td>
              <td><a href="mailto:<?php echo htmlentities($email); ?>"><?php echo htmlentities($email) ?></a></td>
            </tr>
            <?php if (HAS_PRI("edit_user_profile")) : ?>
              <?php require_once('./include/set_post_key.php'); ?>
              <input type="hidden" name="admin_mode" value="1">
              <input type="hidden" name="user_id" value="<?php echo htmlentities($user) ?>">
              <tr>
                <td colspan="2" class="am-danger">----The followings are admin only----</td>
              </tr>
              <tr>
                <td class="first-col" style="padding-top: 10px;">Student ID</td>
                <td>
                  <input class="am-form-field" name="stu_id" type="text" value="<?php echo htmlentities($stu_id) ?>">
                </td>
              </tr>
              <tr>
                <td class="first-col" style="padding-top: 10px;">Real Name</td>
                <td>
                  <input class="am-form-field" name="real_name" type="text" value="<?php echo htmlentities($real_name) ?>">
                </td>
              </tr>
              <tr>
                <td class="first-col" style="padding-top: 10px;">Class</td>
                <td>
                  <select name="class" data-am-selected="{searchBox: 1, maxHeight: 400, btnWidth:'100%'}">
                    <?php foreach ($classList as $c) : ?>
                      <option value="<?php echo $c ?>" <?php if ($c == $class) echo "selected" ?>><?php echo $c ?></option>
                    <?php endforeach ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="first-col" style="padding-top: 10px;">Course Team</td>
                <td>
                  <select name="course_team[]" multiple data-am-selected="{searchBox: 1, maxHeight: 400, btnWidth:'100%'}" style="width: 50px;">
                    <?php foreach ($course_team_list as $team) : ?>
                      <option value="<?php echo $team['team_id'] ?>"
                        <?php if (in_array($team['team_name'], array_column($course_team, 'team_name'))) echo "selected"; ?>><?php echo $team['team_name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </td>
              </tr>
            <?php elseif (HAS_PRI("see_hidden_user_info")) : ?>
              <tr>
                <td colspan="2" class="am-danger">----The followings are admin only----</td>
              </tr>
              <tr>
                <td class="first-col">Student ID</td>
                <td><?php echo htmlentities($stu_id) ?></td>
              </tr>
              <tr>
                <td class="first-col">Real Name</td>
                <td><?php echo htmlentities($real_name) ?></td>
              </tr>
              <tr>
                <td class="first-col">Class</td>
                <td><?php echo htmlentities($class) ?></td>
              </tr>
              <tr>
                <td class="first-col">Course Team</td>
                <td><?php echo htmlentities($course_team) ?></td>
              </tr>
            <?php endif ?>
          </tbody>
        </table>
        <?php if (HAS_PRI("edit_user_profile")) : ?>
          <div class="am-text-center">
            <button class="am-btn am-btn-secondary am-radius">Submit</button>
          </div>
        <?php endif ?>
      </form>
    </div>
    <!-- 左侧个人信息表格 end -->

    <!-- 个人图表信息 start -->
    <div class="am-u-md-4">
      <div id="chart-sub" style="height: 327px; width: 100%;"></div>
    </div>
    <div class='am-u-md-4'>
      <!-- <label>用户评价</label><br> -->
      <!-- <a href="charts/show_fore.php?user=<?php echo $_GET['user'] ?>">用于教学的过程性评价详情请点这里</a> -->
      <div id='chart' style='height:327px;width:100%'></div>
    </div>

    <!-- 个人图表信息 end -->
  </div>
  <!-- userinfo上部分 end -->

  <!-- userinfo中部分 begin -->
  <div class="am-g" style="margin-top: 30px;">
    <div class="am-u-sm-12 am-text-center">
      <div class="am-u-sm-6">
        <a href="./daily_detail.php?user=<?php echo $user; ?>">
          <button class="loading-btn">点击查看每日做题详情</button>
        </a>
      </div>
      <div class="am-u-sm-6">
        <a href="./study_detail.php?user=<?php echo $user; ?>">
          <button class="loading-btn">点击查看学习里程碑</button>
        </a>
      </div>
    </div>
  </div>
  <!-- userinfo中部分 end -->

  <hr />

  <div class="am-g">
    <div class="am-u-md-12">
      <!-- userinfo下部分 start -->
      <?php if ($AC) : ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">HZNUOJ <?php echo "($AC)" ?>:</div>
          <div class="am-panel-bd">
            <?php
            echo "<b>Solved:</b><br/>";
            $sql = "SELECT set_name,set_name_show FROM problemset";
            $res = $mysqli->query($sql);
            echo "<div style='margin-left: 10px;'>";
            while ($row = $res->fetch_array()) {
              $set_name = $row['set_name'];
              $set_name_show = $row['set_name_show'];
              $cnt = count($ac_set[$set_name]);
              if ($cnt) {
                echo "$set_name_show($cnt):<br/>";

                echo "<div style='margin-left: 20px;'>";
                foreach ($ac_set[$set_name] as $pid) {
                  echo "<a href=problem.php?id=$pid> $pid </a>&nbsp;";
                }
                echo "</div>";
              }
            }
            echo "</div>";
            echo "<br />";
            echo "<div><b>Tried:</b></div>";
            foreach ($hznu_unsolved_set as $i) {
              if ($i != 0) echo "<a href=problem.php?id=" . $i . "> " . $i . " </a>&nbsp;";
            }
            echo "<br /><br />";

            //solution video START
            $sql = "SELECT DISTINCT video_id FROM solution_video_watch_log WHERE user_id='$user'";
            $res = $mysqli->query($sql);
            $solution_video_set = array();
            while ($id = $res->fetch_array()[0]) {
              array_push($solution_video_set, $id);
            }
            if (count($solution_video_set)) {
              echo "<div><b>Solution Video Watched:</b></div>";
              foreach ($solution_video_set as $id) {
                echo "<a href=problem.php?id=$id> $id </a>";
              }
              echo "<br /><br />";
            }
            //solution video END
            if (count($hznu_recommend_set)) {
              echo "<div><b>Recommended:</b></div>";
              foreach ($hznu_recommend_set as $i) {
                echo "<a href=problem.php?id=" . $i . "> " . $i . " </a>&nbsp;";
              }
            }
            ?>
          </div>
        </div>
      <?php endif ?>
      <?php if ($CF) : ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">CodeForces <?php echo "($CF)" ?>:</div>
          <div class="am-panel-bd">
            <?php
            sort($cf_solved_set);
            foreach ($cf_solved_set as $i) {
              echo "<a href='" . $VJ_URL . "/problem/viewProblem.action?id=" . $cf_vj_id[$i] . "'>" . $i . " </a>&nbsp";
            }
            ?>
          </div>
        </div>
      <?php endif ?>
      <?php if ($HDU) : ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">HDUOJ <?php echo "($HDU)" ?>:</div>
          <div class="am-panel-bd">
            <?php
            sort($hdu_solved_set);
            foreach ($hdu_solved_set as $i) {
              echo "<a href='" . $VJ_URL . "/problem/viewProblem.action?id=" . $hdu_vj_id[$i] . "'>" . $i . " </a>&nbsp";
            }
            ?>
          </div>
        </div>
      <?php endif ?>
      <?php if ($PKU) : ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">POJ <?php echo "($PKU)" ?>:</div>
          <div class="am-panel-bd">
            <?php
            sort($pku_solved_set);
            foreach ($pku_solved_set as $i) {
              echo "<a href='" . $VJ_URL . "/problem/viewProblem.action?id=" . $pku_vj_id[$i] . "'>" . $i . " </a>&nbsp";
            }
            ?>
          </div>
        </div>
      <?php endif ?>
      <?php if ($UVA) : ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">UVAOJ</div>
          <div class="am-panel-bd">
            <?php
            sort($uva_solved_set);
            foreach ($uva_solved_set as $i) {
              echo "<a href='" . $VJ_URL . "/problem/viewProblem.action?id=" . $uva_vj_id[$i] . "'>" . $i . " </a>&nbsp";
            }
            ?>
          </div>
        </div>
      <?php endif ?>
      <?php if ($ZJU) : ?>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd">ZOJ <?php echo "($ZJU)" ?>:</div>
          <div class="am-panel-bd">
            <?php
            sort($zju_solved_set);
            foreach ($zju_solved_set as $i) {
              echo "<a href='" . $VJ_URL . "/problem/viewProblem.action?id=" . $zju_vj_id[$i] . "'>" . $i . " </a>&nbsp";
            }
            ?>
          </div>
        </div>
      <?php endif ?>
      <!-- userinfo下部分 end -->
    </div>
  </div>
</div>
<?php require_once("footer.php") ?>
<!--<script src="charts/echarts.min.js"></script>-->
<script src="plugins/echarts/echarts.min.js"></script>


<?php
$chart_sub_data = "";
for ($i = 4; $i <= 11; ++$i) {
  $sql = "SELECT count(*) FROM solution WHERE result=$i AND user_id='{$_GET['user']}'";
  $res = $mysqli->query($sql);
  $cnt = $res->fetch_array()[0];
  $chart_sub_data .= "{value: $cnt, name: '{$judge_result[$i]}'},";
}
?>
<script type="text/javascript">
  var chart = echarts.init(document.getElementById('chart'));
  var option = {
    title: {
      text: "总体评价：<?php echo $avg_score ?>分",
      x: 'right',
      y: 'bottom',
    },
    tooltip: {
      trigger: 'axis',
    },
    calculable: true,
    radar: [{
      indicator: [{
          text: '题量',
          max: 100
        },
        {
          text: '难度',
          max: 100
        },
        {
          text: '活跃',
          max: 100
        },
        {
          text: '独立',
          max: 100
        }
      ],
    }],
    series: [{
      name: 'User Info',
      type: 'radar',
      tooltip: {
        trigger: 'item'
      },
      itemStyle: {
        normal: {
          areaStyle: {
            type: 'default'
          }
        }
      },
      data: [{
        value: [<?php echo $solved_score . "," . $dif_score . "," . $act_score . "," . $idp_score; ?>],
        name: '<?php echo $user ?>'
      }]
    }]
  };
  chart.setOption(option);

  var chart_sub = echarts.init(document.getElementById("chart-sub"));
  option = {
    title: {
      text: "Submissions"
    },
    tooltip: {
      trigger: 'item',
      formatter: "{b} : {c} ({d}%)"
    },
    color: [
      '#5EB95E', '#6b8e23', '#DD514C', '#F37B1D', '#b8860b',
      '#ff69b4', '#ba55d3', '#6495ed', '#ffa500', '#40e0d0',
      '#1e90ff', '#ff6347', '#7b68ee', '#00fa9a', '#ffd700',
      '#ff00ff', '#3cb371', '#87cefa', '#30e0e0', '#32cd32',
    ],
    //color : ['#5EB95E','#DD514C'],
    series: [{
      name: 'Submissions in HZNUOJ',
      type: 'pie',
      data: [
        <?php echo $chart_sub_data; ?>
      ],
      itemStyle: {
        normal: {
          label: {
            show: false,
            position: "inner",
          },
        },
        emphasis: {
          label: {
            show: false,
            position: "inner",
          },
        },

      }
    }]
  };
  chart_sub.setOption(option);


  $(window).resize(function() {
    chart.resize();
    chart_sub.resize();
  });
  $(window).ready(function() {
    chart.resize();
    chart_sub.resize();
  });
</script>