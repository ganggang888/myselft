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
	<div class="wrap jj">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('teacher/index');?>">用户列表</a></li>
			<li class="active"><a href="javascript:;">添加用户</a></li>
		</ul>
        <ul class="nav nav-tabs new" id="navs">
        <li class="active" id="1"><a href="javascript:;">测评填写</a></li>
        <li id="2"><a href="javascript:;">建议</a></li>
        <li id="3"><a href="javascript:;">测评单</a></li>
        <li id="4"><a href="javascript:;">辅食添加情况</a></li>
        </ul>
		<div class="common-form">
        
			<form method="post" class="form-horizontal J_ajaxForm" action="">
            <div class="navContent one">
            <p>姓名：<input name="data[name]" style="width:100px" type="text">性别：<input type="text" style="width:50px" name="data[sex]">年（月）龄：<input name="data[age]" style="width:100px" type="text">胎龄：<input type="text" name="data[t_age]">
            娩出方式：<input name="data[birth_type]" type="text">出生体重（KG）：<input type="text" name="data[birthWeight]">出生身长（cm）：<input type="text" name="data[birthHeight]"></p>
				<table width="100%" border="1">
  <tr>
    <td width="45" rowspan="2" align="center">目录</td>
    <td width="130" rowspan="2" align="center">名称</td>
    <td colspan="3" align="center">结果</td>
    <td rowspan="2" align="center">目录</td>
    <td colspan="9" rowspan="2" align="center">结果</td>
    </tr>
  <tr>
    <td width="40">正常</td>
    <td width="40">不正常</td>
    <td width="20">其他</td>
    </tr>
  <tr>
    <td rowspan="6" align="center">一般情况</td>
    <td align="center">面色</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[face]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[face]"></td>
    <td width="20" align="center"><textarea name="data[faceExplain]"></textarea></td>
    <td rowspan="2" align="center">神经发育系统</td>
    <td align="center">拥抱反射</td>
    <td><textarea name="data[EmbraceReflex]"></textarea></td>
    <td>握持反射</td>
    <td><textarea name="data[holdDeflection]"></textarea></td>
    <td rowspan="2">其他</td>
    <td colspan="4"><textarea name="data[ReflexOtherOne]"></textarea></td>
    </tr>
  <tr>
    <td align="center">精神</td>
    <td width="40" align="center"><input type="radio" name="data[spirit]"></td>
    <td width="40" align="center"><input type="radio" name="data[spirit]"></td>
    <td width="20" align="center"><textarea name="data[spiritExplain]"></textarea></td>
    <td align="center">爬行反射</td>
    <td><textarea name="data[CrawlingReflex
]"></textarea></td>
    <td>踏步反射</td>
    <td><textarea name="data[SteppingReflex
]"></textarea></td>
    <td colspan="4"><textarea name="data[ReflexOtherTwo]"></textarea></td>
    </tr>
  <tr>
    <td align="center">反应（哭声）</td>
    <td width="40" align="center"><input type="radio" name="data[cry]"></td>
    <td width="40" align="center"><input type="radio" name="data[cry]"></td>
    <td width="20" align="center"><textarea name="data[cryExplain]"></textarea></td>
    <td rowspan="3" align="center">体格发育</td>
    <td align="center">体重（KG）</td>
    <td colspan="2"><input type="text" name="data[weight]"></td>
    <td colspan="2">身高(CM)</td>
    <td colspan="4"><input type="text" name="data[height]"></td>
    </tr>
  <tr>
    <td align="center">体温</td>
    <td width="40" align="center"><input type="radio" name="temperature"></td>
    <td width="40" align="center"><input type="radio" name="temperature"></td>
    <td width="20" align="center"><textarea name="data[temperatureExplain]"></textarea></td>
    <td align="center">头围（CM）</td>
    <td colspan="2"><input type="text" name="data[header]"></td>
    <td colspan="2">胸围（CM）</td>
    <td colspan="4"><input type="text" name="data[bust]"></td>
    </tr>
  <tr>
    <td align="center">脉搏</td>
    <td width="40" align="center"><input type="radio" name="data[pulse]"></td>
    <td width="40" align="center"><input type="radio" name="data[pulse]"></td>
    <td width="20" align="center"><textarea name="data[pulseExplain]"></textarea></td>
    <td align="center">前囟</td>
    <td colspan="8"><input type="text" name="data[bregmatic]"></td>
    </tr>
  <tr>
    <td align="center">呼吸</td>
    <td width="40" align="center"><input type="radio" name="data[breathing]"></td>
    <td width="40" align="center"><input type="radio" name="data[breathing]"></td>
    <td width="20" align="center"><textarea name="data[breathingExplain]"></textarea></td>
    <td rowspan="2" align="center"><p>生长发育评价</p>
      <p>（百分位法）</p></td>
    <td align="center">体重/月（年）龄</td>
    <td align="center"><label>上&gt;97 <input type="radio" value="1" name="data[weightScore]"></label></td>
    <td colspan="2" align="center"><label>中上75～97 <input type="radio" value="2" name="data[weightScore]"></label></td>
    <td align="center"><label>中25～75 <input type="radio" value="3" name="data[weightScore]"></label></td>
    <td colspan="2" align="center"><label>中下3～25 <input type="radio" value="4" name="data[weightScore]"></label></td>
    <td colspan="2" align="center"><label>下&lt;3 <input type="radio" value="5" name="data[weightScore]"></label></td>
    </tr>
  <tr>
    <td rowspan="6" align="center">头面</td>
    <td align="center">颅骨</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[skull]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[skull]"></td>
    <td width="20" align="center"><textarea name="data[skullExplain]"></textarea></td>
    <td align="center">身长/月（年）龄</td>
    <td align="center"><label>上&gt;97 <input type="radio" value="1" name="data[heightScore]"></label></td>
    <td colspan="2" align="center"><label>中上75～97 <input type="radio" value="2" name="data[heightScore]"></label></td>
    <td align="center"><label>中25～75 <input type="radio" value="3" name="data[heightScore]"></label></td>
    <td colspan="2" align="center"><label>中下3～25 <input type="radio" value="4" name="data[heightScore]"></label></td>
    <td colspan="2" align="center"><label>下&lt;3 <input type="radio" value="5" name="data[heightScore]"></label></td>
    </tr>
  <tr>
    <td align="center">毛发</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[hair]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[hair]"></td>
    <td width="20" align="center"><textarea name="data[hairExplain]"></textarea></td>
    <td align="center">既往史</td>
    <td colspan="9">疾病名称：<input type="text" name="data[diseaseName
]"></td>
    </tr>
  <tr>
    <td align="center">眼脸</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[eyesFace]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[eyesFace]"></td>
    <td width="20" align="center"><textarea name="data[eyesFaceExplain]"></textarea></td>
    <td rowspan="2" align="center">家族史</td>
    <td colspan="9">遗传性疾病（名称）：<input type="text" name="data[GeneticDisease]"></td>
    </tr>
  <tr>
    <td align="center">结膜</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[conjunctiva]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[conjunctiva]"></td>
    <td width="20" align="center"><textarea name="data[conjunctivaExplain]"></textarea></td>
    <td colspan="9">过敏性疾病（名称）：<input type="text" name="data[allergicDisease]"></td>
    </tr>
  <tr>
    <td align="center">鼻</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[nose]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[nose]"></td>
    <td width="20" align="center"><textarea name="data[noseExplain]"></textarea></td>
    <td rowspan="5" align="center">喂养史</td>
    <td rowspan="2">乳类</td>
    <td>喂养方式</td>
    <td colspan="3"><input type="text" name="data[feedType]"></td>
    <td>每次喂乳次数</td>
    <td colspan="3"><input type="text" name="data[feedingTimes
]"></td>
    </tr>
  <tr>
    <td align="center">口腔（粘膜、牙）</td>
    <td width="40" align="center"><input type="radio" name="data[mouth]" value="1"></td>
    <td width="40" align="center"><input type="radio" name="data[mouth]" value="2"></td>
    <td width="20" align="center"><textarea name="data[mouthExplain]"></textarea></td>
    <td colspan="8">每次乳量:<label><input type="radio" value="1" name="data[MilkPerServing]">母乳</label><label><input type="radio" value="1" name="data[MilkPerServing]">配方奶</label></td>
    </tr>
  <tr>
    <td rowspan="3" align="center">胸部</td>
    <td align="center">外型</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[appearance]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[appearance]"></td>
    <td width="20" align="center"><textarea name="data[appearanceExplain]"></textarea></td>
    <td rowspan="3">辅食</td>
    <td>起始</td>
    <td colspan="7"><input type="text" name="data[foodsBegin]"></td>
    </tr>
  <tr>
    <td align="center">心脏（心音、心律）</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[heart]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[heart]"></td>
    <td width="20" align="center"><textarea name="data[heartExplain]"></textarea></td>
    <td>种类名称</td>
    <td colspan="7"><input type="text" name="data[foodsName]"></td>
    </tr>
  <tr>
    <td align="center">肺部（呼吸音）</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[lungs]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[lungs]"></td>
    <td width="20" align="center"><textarea name="data[LungsExplain]"></textarea></td>
    <td>量</td>
    <td colspan="3"><input type="text" name="data[amount]"></td>
    <td>食物过敏</td>
    <td colspan="3"><input type="text" name="data[foodAllergy]"></td>
    </tr>
  <tr>
    <td rowspan="5" align="center">腹部</td>
    <td align="center">外形</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[shape]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="shape"></td>
    <td width="20" align="center"><textarea name="data[shapeExplain]"></textarea></td>
    <td rowspan="6" align="center">作息习惯</td>
    <td rowspan="5">作息习惯</td>
    <td rowspan="2">睡眠                                                                                                                                                                                                                                                                                               </td>
    <td colspan="3">起床时间：<input type="text" name="data[WakeUpTime]"></td>
    <td>入睡时间</td>
    <td colspan="3"><input type="text" name="data[SleepTime]"></td>
    </tr>
  <tr>
    <td align="center">脐部</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[navel]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[navel]"></td>
    <td width="20" align="center"><textarea name="data[navelExplain]"></textarea></td>
    <td colspan="3">白天睡眠时间</td>
    <td colspan="4"><input type="text" name="data[DaytimeSleepTime]"></td>
    </tr>
  <tr>
    <td align="center">肝脏</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[liver]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[liver]"></td>
    <td width="20" align="center"><textarea name="data[liverExplain]"></textarea></td>
    <td>沐浴</td>
    <td colspan="3">频率：<input type="text" name="data[bathingFrequency]"></td>
    <td>时间</td>
    <td colspan="3"><input type="text" name="data[bathingTime]"></td>
    </tr>
  <tr>
    <td align="center">脾脏</td>
    <td width="40" align="center"><input type="radio" name="data[spleen]"></td>
    <td width="40" align="center"><input type="radio" name="data[spleen]"></td>
    <td width="20" align="center"><textarea name="data[spleenExplain]"></textarea></td>
    <td>二便</td>
    <td colspan="3">频率：<input type="text" name="data[shitFrequency]"></td>
    <td>性状</td>
    <td colspan="3"><input type="text" name="data[shitCharacter]"></td>
    </tr>
  <tr>
    <td align="center">包块及压痛</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[piece]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[piece]"></td>
    <td width="20" align="center"><textarea name="data[pieceExplain]"></textarea></td>
    <td>外出</td>
    <td colspan="3">频率:<input type="text" name="data[outgoingFrequency]"></td>
    <td>时间</td>
    <td colspan="3"><input type="text" name="data[outgoingTime]"></td>
    </tr>
  <tr>
    <td rowspan="3" align="center"><p>生殖</p>
      <p>系统</p>
      <p>肛门</p></td>
    <td align="center">外生殖器</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[genitals]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[genitals]"></td>
    <td width="20" align="center"><textarea name="data[genitalsExplain]"></textarea></td>
    <td colspan="9">&nbsp;</td>
    </tr>
  <tr>
    <td rowspan="2" align="center">肛门</td>
    <td width="40" rowspan="2" align="center"><input type="radio" value="1" name="data[anus]"></td>
    <td width="40" rowspan="2" align="center"><input type="radio" value="2" name="data[anus]"></td>
    <td width="20" rowspan="2" align="center"><textarea name="data[anusExplain]"></textarea></td>
    <td rowspan="2" align="center">父母关注的问题</td>
    <td colspan="9">1.<textarea class="big" name="data[ParentsProblemOne]"></textarea></td>
    </tr>
  <tr>
    <td colspan="9">2.<textarea class="big" name="data[ParentsProblemTwo]"></textarea></td>
    </tr>
  <tr>
    <td rowspan="2" align="center">骨骼</td>
    <td align="center">脊柱</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[spine]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[spine]"></td>
    <td width="20" align="center"><textarea name="data[spineExplain]"></textarea></td>
    <td rowspan="4" align="center">保健指导</td>
    <td colspan="9">1.<textarea class="big" name="data[HealthGuidanceOne]"></textarea></td>
    </tr>
  <tr>
    <td align="center">四肢</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[limb]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[limb]"></td>
    <td width="20" align="center"><textarea name="data[limbExplain]"></textarea></td>
    <td colspan="9">2.<textarea class="big" name="data[HealthGuidanceTwo]"></textarea></td>
    </tr>
  <tr>
    <td rowspan="2" align="center">皮肤</td>
    <td align="center">黄瘟</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[plague]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[plague]"></td>
    <td width="20" align="center"><textarea name="data[plagueExplain]"></textarea></td>
    <td colspan="9">3.<textarea class="big" name="data[HealthGuidanceThree]"></textarea></td>
    </tr>
  <tr>
    <td align="center">皮疹（红臀）</td>
    <td width="40" align="center"><input type="radio" value="1" name="data[rash]"></td>
    <td width="40" align="center"><input type="radio" value="2" name="data[rash]"></td>
    <td width="20" align="center"><textarea name="data[rashExplain]"></textarea></td>
    <td colspan="9">4.<textarea class="big" name="data[HealthGuidanceFour]"></textarea></td>
    </tr>
</table>
<p style="margin-top:10px">家长：<input type="text" name="data[householder]"> 测评专家：<input type="text" name="data[evaluationExpert]"> 测评次数：<input type="text" name="data[evaluationNumber]"> 时间：<input type="text" name="data[endTime]"></p>

</div>
<div class="navContent two" style="display:none">
<fieldset>
					<div class="control-group">
						<label class="control-label">专家整体建议:</label>
						<div class="controls">
							<textarea name="proposal[ExpertRecommendations]"></textarea>
							<span class="must_red">*</span>
						</div>
					</div>
                    <div class="control-group">
						<label class="control-label">爸爸妈妈特殊需求:</label>
						<div class="controls">
							<textarea name="proposal[parentSpecialNeeds]"></textarea>
							<span class="must_red">*</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">下个月建议:</label>
						<div class="controls">
							<textarea name="proposal[nextMonthProposal]"></textarea><span class="must_red">相对于标准版早教内容的调整</span>
						</div>
					</div>
                    <div class="control-group">
						<label class="control-label">家长:</label>
						<div class="controls">
                        <input type="text" class="input" name="proposal[parentsName]">
						</div>
					</div>
                    <div class="control-group">
						<label class="control-label">测评员:</label>
						<div class="controls">
                        <input type="text" class="input" name="proposal[tester]">
						</div>
					</div>
                    <div class="control-group">
						<label class="control-label">育婴师:</label>
						<div class="controls">
                        <input type="text" class="input" name="proposal[nurseryTeacher]">
						</div>
					</div>
				</fieldset>
</div>

<div class="navContent three" style="display:none">
<fieldset>
<table border="1" width="100%">
  <tr>
    <td colspan="6">第<input type="text" name="evaluation[numbers]">次测评   宝宝月龄：<input type="text" name="evaluation[month]"></td>
    </tr>
  <tr>
    <td colspan="6" align="center" style="font-size:24px">纯母乳喂养</td>
    </tr>
  <tr>
    <td><label><input type="radio" value="1" name="evaluation[breastSuck]">吸出</label></td>
    <td>总奶量：<input type="text" name="evaluation[breastTotalMilk]" class="input"></td>
    <td>每顿量：<input type="text" name="evaluation[breastMeal]" class="input"></td>
    <td>间隔时间：<input type="text" name="evaluation[breastIntervalTime]" class="input"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr style="border-bottom: dotted 1px #000">
    <td><label><input type="radio" value="2" name="evaluation[breastSuck]">亲喂</label></td>
    <td>次数：<input type="text" name="evaluation[breastNumbers]" class="input"></td>
    <td>间隔时间：<input type="text" name="evaluation[breastTime]" class="input"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center" style="font-size:24px">纯人工喂养</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>总奶量：<input type="text" name="evaluation[artificialNumbers]" class="input"></td>
    <td>每顿量：<input type="text" name="evaluation[artificialEvery]" class="input"></td>
    <td>间隔时间：<input type="text" name="evaluation[artificialTime]" class="input"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>饮水情况：<input type="text" name="evaluation[artificialDrinking]" class="input"></td>
    <td>配方奶品牌：<input type="text" name="evaluation[artificialBrand]" class="input"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center" style="font-size:24px">混合喂养</td>
    </tr>
  <tr>
    <td><label><input type="radio" name="evaluation[mixed]" value="1"> 代授法</label></td>
    <td>配方奶：<input type="text" name="evaluation[mixedMeal]" class="input"> 顿</td>
    <td>每顿量：<input type="text" name="evaluation[mixedNumbers]" class="input"></td>
    <td>间隔时间：<input type="text" name="evaluation[mixedTime]" class="input"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>母乳：
      <input type="text" name="evaluation[MotherMixedMeal]" class="input"> 顿</td>
    <td>每顿量：<input type="text" name="evaluation[MotherMixedNumbers]" class="input"></td>
    <td>间隔时间：<input type="text" name="evaluation[MotherMixedTime]" class="input"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><label><input type="radio" value="2" name="evaluation[mixed]"> 补授法</label></td>
    <td>次数：<input type="text" name="evaluation[grantedMeal]" class="input">顿</td>
    <td>每顿量：<input type="text" name="evaluation[grantedNumbers]" class="input"></td>
    <td>间隔时间：<input type="text" class="input" name="evaluation[grantedTime]"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>饮水情况：<input type="text" name="evaluation[grantedDrinking]" class="input"></td>
    <td>配方奶品牌：<input type="text" name="evaluation[grantedBrand]" class="input"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr style=" border-top:dashed 1px #000">
    <td>排便情况</td>
    <td align="right">性状：</td>
    <td><input type="text" name="evaluation[defecationCharacter]" class="input"></td>
    <td align="right">频率：</td>
    <td><input type="text" name="evaluation[defecationFrequency]" class="input"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>特殊生理状况</td>
    <td><label><input type="radio" name="evaluation[Special_physiological_condition]" value="1"> 无</label></td>
    <td><label><input type="radio" name="evaluation[Special_physiological_condition]" value="2"> 有</label></td>
    <td>（湿疹、乳糖不耐受、母乳性黄瘟、早产、其他）</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>鱼肝油（维生素D）</td>
    <td align="right">品牌：</td>
    <td><input type="text" name="evaluation[Cod_Liver_Oil_brand]" class="input"></td>
    <td align="right">用量：</td>
    <td><input type="text" name="evaluation[amount_of_Cod_Liver_Oil]" class="input"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>多维元素复合剂</td>
    <td align="right">品牌：</td>
    <td><input type="text" name="evaluation[Composite_brand]" class="input"></td>
    <td align="right">用量：</td>
    <td><input type="text" name="evaluation[Compound_dose]" class="input"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>其他营养补充剂</td>
    <td align="right">品牌：</td>
    <td><input type="text" name="evaluation[Supplement_brands]" class="input"></td>
    <td align="right">用量：</td>
    <td><input type="text" name="evaluation[Supplement_dosage]" class="input"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>档案编号</td>
    <td><input type="text" class="input"></td>
    <td align="right">合同编号：</td>
    <td><input type="text" class="input"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table>

</fieldset>
</div>

<div class="navContent four" style="display:none">
<fieldset>
<table border="1" width="100%">
  <tr>
    <td colspan="4">辅食添加情况</td>
    </tr>
  <tr>
    <td><label><input type="radio" name="supplement[no]" value="1"> 未添加</label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><label><input type="radio" name="supplement[no]" value="2"> 已添加（已添加的种类）</label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><label><input type="radio" name="supplement[grain]" value="1"> 谷类/米粉</label></td>
    <td><label><input type="radio" name="supplement[vegetables]" value="1"> 蔬菜</label></td>
    <td><label><input type="radio" name="supplement[Fruits]" value="1"> 水果</label></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><label><input type="radio" name="supplement[eggs]" value="1"> 蛋类</label></td>
    <td><label><input type="radio" name="supplement[fish]" value="1"> 鱼类</label></td>
    <td><label> <input type="radio" name="supplement[viscera]" value="1"> 动物内脏</label></td>
    <td><label> <input type="radio" name="supplement[poultry]" value="1"> 禽类</label></td>
  </tr>
  <tr>
    <td><label> <input type="radio" name="supplement[livestock]" value="1"> 畜类</label></td>
    <td><label> <input type="radio" name="supplement[shrimp]"> 虾类</label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><label> <input type="radio" name="supplement[Bean_Products]" value="1"> 豆类及其制品</label></td>
    <td><label> <input type="radio" name="supplement[Bacteria_algae]" value="1"> 菌藻类</label></td>
    <td><label> <input type="radio" name="supplement[Dairy]" value="1"> 乳制品</label></td>
    <td><label> <input type="radio" name="supplement[nut]" value="1"> 坚果</label></td>
  </tr>
  <tr>
    <td>举例具体品种：</td>
    <td colspan="3"><textarea name="supplement[Specific_varieties]"></textarea></td>
    </tr>
  <tr>
    <td>食物过敏：</td>
    <td colspan="3"><textarea name="supplement[food_allergy]"></textarea></td>
    </tr>
  <tr>
    <td>宝宝的餐次：</td>
    <td><input type="text" name="supplement[baby_meal]" class="input"> 餐  <input type="text" name="supplement[spot]" class="input"> 点</td>
    <td><label><input type="radio" value="1" name="supplement[fruits_type]"> 水果</label></td>
    <td><label><input type="radio" value="1" name="supplement[pastry]"> 糕点</label></td>
  </tr>
  <tr>
    <td>宝宝食物的性状：</td>
    <td colspan="3"><input type="text" name="supplement[food_characteristics]"></td>
    </tr>
  <tr>
    <td>宝宝的食欲：</td>
    <td><label><input type="radio" name="supplement[AppetiteType]" value="1"> 尚佳</label></td>
    <td><label><input type="radio" name="supplement[AppetiteType]" value="2"> 一般</label></td>
    <td><label><input type="radio" name="supplement[AppetiteType]" value="3"> 不佳</label></td>
  </tr>
  <tr>
    <td>宝宝吃饭的速度：</td>
    <td><label><input type="radio" value="1" name="supplement[eat_time]"> 5-10分钟</label></td>
    <td><label><input type="radio" value="2" name="supplement[eat_time]"> 10-20分钟</label></td>
    <td><label><input type="radio" value="3" name="supplement[eat_time]"> 20-30分钟</label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label><input type="radio" value="4" name="supplement[eat_time]"> 30分钟</label>以上</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>其他：</td>
    <td colspan="3"><textarea name="supplement[others]" style="width:80%"></textarea></td>
    </tr>
</table>

</fieldset>
</div>
				<div class="form-actions">
				  <button type="submit" onClick="check()" class="btn btn-primary btn_submit J_ajax_submit_btn">添加</button>
					<a class="btn" href="/info/index.php/User/SixForOne">返回</a>
			  </div>
			</form>
		</div>
	</div>
	<script src="/info/statics/js/common.js"></script>
    <style>
    table .big{ width:90%}
	table td{ padding:10px 10px}
	.nav.nav-tabs.new{}
    </style>
<script>
$(function(){
	$("#navs a").click(function(){
		var id = $(this).parent().attr("id");
		$("#navs li").attr("class","");
		$(this).parent().attr("class","active");
		$(".navContent").hide();
		if (id == 1){
			$(".navContent.one").show();
		} else if (id == 2) {
			$(".navContent.two").show();
		} else if (id == 3) {
			$(".navContent.three").show();
		} else if (id == 4) {
			$(".navContent.four").show();
		}
		
		
	});
})

</script>
</body>
</html>