<?php if (!defined('IS_GIUEM')) exit();?>
<div class="am-panel am-panel-secondary" data-am-scrollspy="{animation:'slide-top', delay: 300,repeat: false}">
  <div class="am-panel-hd"><h1 class="am-panel-title">机器人列表</h1></div>
  <div class="am-scrollable-vertical">
  <table class="am-table am-table-bd am-table-bdrs am-table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>名字</th>
            <th>状态</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $robot_list = get_robot_list();
    if (empty($robot_list)){
		echo '<div class="am-animation-scale-up am-text-danger am-text-center">没有机器人，请添加</div>';
	}else {
		foreach ($robot_list as $k){
			$content = "<tr><td>{$k['id']}</td><td>{$k['name']}</td><td>";
			if (tieba::islogin($k['bduss'])===true)
				$content .= '<span class="am-icon-check"></span></td></tr>';
			else
				$content .= '<span class="am-icon-close"></span></td></tr>';
		echo $content;
		}
	}
    
    ?>
    </tbody>
  </table>
  <p>PS：只会获取ID为1的消息，其他帐号为辅助小号</p>
  </div>
</div>
<div class="am-btn-group" data-am-scrollspy="{animation:'fade',repeat: false}">
<button type="button" class="am-btn am-btn-secondary" id="add-robot">添加</button>
<button type="button" class="am-btn am-btn-danger" id="del-robot">删除</button>
<button type="button" class="am-btn am-btn-success" id="edit-robot">修改</button>
</div>
<hr>

