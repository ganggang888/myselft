<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/info/statics/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/info/statics/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/info/statics/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/info/statics/simpleboot/font-awesome/4.2.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/info/statics/simpleboot/font-awesome/4.2.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/info/",
    JS_ROOT: "statics/js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/info/statics/js/jquery.js"></script>
    <script src="/info/statics/js/wind.js"></script>
    <script src="/info/statics/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
<body class="J_scroll_fixed">
<script src="tpl_admin/simpleboot/User/Game/echarts.min.js"></script>

	<div class="wrap J_check_wrap">
		<ul class="nav nav-tabs">
        <li class="active"><a href="javascript:;">日活信息</a></li>
			<li><a href="<?php echo U('Wchat/activeMonth');?>">月活信息</a></li>
		</ul>
        <form class="well form-search">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">开始时间：<input type="text" class="J_date" id="begin" value="<?php echo ($begin); ?>" style="width:120px"/>
						结束时间：<input type="text" class="J_date" id="end" value="<?php echo ($end); ?>" style="width:120px"/>
					</span>
                   
                    <a href="javascript:;" onClick="todo()" class="btn btn-primary">搜索</a>
				  <script>
					function todo() {
						var begin = $("#begin").val();
						var end = $("#end").val();
						window.location.href='index.php?g=User&m=Wchat&a=activeDay&menuid=185&begin='+begin+'&end='+end;
					}
					</script>
				</div>
			</div>
		</form>
		<div id="main" style="width:100%; height:500px">
        
        </div>
     
	</div>
    
    
    <script>   
//初始化身长数据

var myChart = echarts.init(document.getElementById('main'));

//参数设置

option = {
	 title : {
        text: '日活情况',
		//subtext:'',
    },
    tooltip : {
        trigger: 'axis'
    },
    legend: {
		orient : 'vertical',
        data:['日活'],
		/*x:'right',
		y:'center',*/
    },
    toolbox: {
        show : true,
		default: ['toolbox'],
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: true,default: ['toolbox']}
        },
    },
    xAxis : [
        {
            type : 'category',
            boundaryGap : false,
            data : [<?php echo ($x); ?>],
			axisLabel : {
                formatter: '{value} '
            },
        }
    ],
    yAxis : [
        {
			type: 'value',
			axisLabel : {
                formatter: '{value} 人'
            },
			
        }
    ],
    series : [
		
        {
            name:'日活',
            type:'line',
            data:[<?php echo ($info); ?>],
			itemStyle : { normal: {label : {show: true}}},
        },
        
		
    ]
};
                    

 

    myChart.setOption(option);   //参数设置方法     

</script>

	<script src="/info/statics/js/common.js"></script>
	<script>
		$(function() {

			$("#navcid_select").change(function() {
				$("#mainform").submit();
			});

		});
	</script>
</body>
</html>