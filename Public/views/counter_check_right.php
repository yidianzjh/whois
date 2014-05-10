<script src="<?php echo $js_path ?>counter_check_domain.js"></script>

<!--主体开始-->
<div class="content">
    <div class="contontTop">
        <div class="logosch">
            <a href="/" class="contlogo">查询网</a>
            <div class="con_searchBox">
                <p class="chooseR"><input type="radio" name="schwhois" id="schwhois" checked/><label for="schwhois" class="active">查询whois</label></p>
                <p class="chooseR"><input type="radio" name="schwhois" id="whoissch" /><label for="whoissch">whois反查</label></p>
                <div class="con_searchInput">
                    <input id="key" type="text" class="con_inputBox" value="请输入要查询的域名"  />
                    <input id="schBtn" type="button" class="con_schBtn"/>
                </div>
            </div>
        </div>
    </div>
    <div class="conMain">
        <div class="fxBox">
            <div class="fxTitle"><p class="bdColor">关键词：<span id="domain">
				<?php echo $key ?></span><span>反查域名</span>结果数量（<font color="#000"><?php echo $count ?></font>）</p></div>
            <table cellspacing="0" cellpadding="0" width="100%" class="fxResult nobreak">
                <tr class="headtb"><th>域名</th><th>注册商</th><th>注册者</th><th>注册时间</th><th>过期时间</th></tr>
                <?php
                //var_dump($record);
                foreach ($record as $data_tmp)
                {
//var_dump($data_tmp);
                ?>
                <tr><th><a href='/index.php/search_domain/index/<?php echo $data_tmp['domain_name'] ?>' target='_blank'><?php echo $data_tmp['domain_name'] ?></a></th>
                    <th><?php echo $data_tmp['registrar'] ?></th><th><?php echo $data_tmp['owner_organization'] ?></th>
                    <th><?php echo $data_tmp['creation_date'] ?></th><th><?php echo $data_tmp['expiration_date'] ?></th></tr>
                <?php
                }
                ?>
            </table>
            <div class="page_bx">共<?php echo $count ?>条记录,当前从<?php echo ($page*$page_size+1)."-".($page*$page_size+$n) ?>,本页显示<?php echo $n ?>条<b><?php echo ($page+1)."/".($page_num+1) ?></b>&nbsp;
            
            <?php
                if ($page > 0)
                {
                echo "<a  href=\"/index.php/counter_check_domain/index/" . $key . "/0\">首页</a>";
                echo "<a  href=\"/index.php/counter_check_domain/index/" . $key . "/" . ($page - 1) . "\">上一页</a>";
                }
                for ($i=0; $i <= $page_num; $i++)
                {
                    if ($i == $page)
                    {
                        echo ($i+1);
                    }
                    else
                    {
                        echo "<a  href=\"/index.php/counter_check_domain/index/" . $key . "/" . $i . "\">" . ($i + 1) . "</a>";
                    }
                }

                if ($page < $page_num)
                {
                    echo "<a  href=\"/index.php/counter_check_domain/index/" . $key . "&page=" . ($page + 1) . "\">下一页</a>";
                    echo "<a  href=\"/index.php/counter_check_domain/index/" . $key . "/" . $page_num . "\">末页</a>";

                }
            ?>
        </div>
        <div class="bdline"></div>
    </div>
</div>
<!--主体结束-->
