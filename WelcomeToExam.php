<?php
session_start();
include 'header.php';
date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['welcomeToExam'])) 
{
	header('location:http://localhost/OES_BS/login.php');
}
date_default_timezone_set("Asia/Kolkata");
$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'online_examination_system');
$q="select course_id from course where name='$_SESSION[course]'";
$result=mysqli_query($con,$q);
$c_id=mysqli_fetch_array($result);

$query="select question_id,name,optionA,optionB,optionC,optionD from questions where course_id=".$c_id['course_id'];
$result1=mysqli_query($con,$query);
$row=mysqli_num_rows($result1);

$result2=mysqli_query($con,"select * from exam where stuid='$_SESSION[studentid]'");


?>
<!DOCTYPE html>
<html>
<head>
	<!--<link rel="stylesheet" type="text/CSS" href="CSS/welcometoexam.css">-->
	<link rel="stylesheet" type="text/css" href="CSS/calc.css">
	<script type="text/javascript" src="JavaScript/jquery.js"></script>
	<script src="javaScript/calc.js"></script>
	<script type = "text/javascript" >
	   function preventBack(){window.history.forward();}
	    setTimeout("preventBack()", 0);
	    window.onunload=function(){null};
	</script>
	<script type="text/javascript">
		window.addEventListener("keydown",disable);
		function disable()
		{
			document.onkeydown = function (e) 
			{
				return false;
			}
		}
	</script>

<script type="text/javascript">
function ShowRemainingTime()
{
			var t=localStorage.getItem("curTime");
			var hours=Math.floor(t/3600);
			var minutes=Math.floor((t-(hours*3600))/60);
			var seconds=t%60;
			var hrs=check(hours);
			var mint=check(minutes);
			var sec=check(seconds);
			if(t<=0)
			{
				clearTimeout(tm);
				localStorage.removeItem("curTime");
				document.getElementById("form1").submit();
			}
			else
			{
				document.getElementById("countdowntimer").innerHTML ="Remaining Time:- "+hrs+"h "+mint+"m "+sec+"s";
			}
			t--;
			var t=localStorage.setItem("curTime",t--);
			var tm= setTimeout(function(){ShowRemainingTime()},1000);
}
function check(sms)
{
	if(sms<10)
	{
		sms="0"+sms;
	}
		return sms;
}

</script>
<script type="text/javascript">
	var myVar = setInterval(myTimer,1000);
	function myTimer() 
	{
		var d =new Date();
		document.getElementById("currenttime").innerHTML ="The Current Time:- "+d.toLocaleTimeString();
	}
</script>

	<script type="text/javascript">
		
		function SignOut()
		{
			var a=confirm("Have You Finished Your Examination ?");
			if(!a)
			{
				return false;
			}
			else
			{
				var b=confirm("Do You Want To Sign Out/Submit ?");
				if(!b)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
		}
	</script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$("#calculator").click(function(){
			if($(this).html()=="Show Calculator")
			{
				$(this).html("Hide Calculator");
				$(".main").show();
			}
			else
			{
				$(this).html("Show Calculator");
				$(".main").hide();
			}

		});
	});	

</script>
<script>
			
	function validate(str,id)
	{
		var req=new XMLHttpRequest();
		req.open("get","http://localhost/OES_BS/request.php?ans="+str+"&id="+id,true);
		req.send();
	}
		
</script>
	<style type="text/css">
		
		div.main{
			display: none;
			width: 300px;
		}
	div#examStart{
	background-color: lightgray;
	border-radius: 5px;
	width: 60%;
	margin: auto;
	max-height: 450px;
	overflow-y: scroll;
}
h4{
	background-color: cyan;
	border: 2px solid purple;
	border-radius: 5px;
	padding: 5px;
}
	</style>

</head>
<body onload="ShowRemainingTime()" oncontextmenu="return false;">
	<script type="text/javascript">
		var timer=30*60;
		if(!localStorage.getItem("curTime"))
		{
			localStorage.setItem("curTime",timer);	
		}
	</script>
	<div class="container-fluid">
			<nav class="navbar navbar-inverse">
				<div class="row">
					<div class="col-md-3">
						<button type="submit" id="calculator" class="navbar-btn btn btn-success">Show Calculator</button>
					</div>
					<div class="col-md-3">
						<p style="color: white;font-size: 15px;" id="currenttime" class="navbar-text"></p>
					</div>
					<div class="col-md-3">
						<p class="navbar-text" style="color: yellow;font-weight: bold;font-size: 15px;">
							Hello.. <?php echo ucwords("$_SESSION[fname] $_SESSION[lname]");  ?></p>
					</div>
					<div class="col-md-3">
						<p style="font-size: 15px;color: white;" id="countdowntimer" class="navbar-text"></p>
					</div>
				</div>
			</nav>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3">
				<div class="main">
					<table class="table table-bordered">
					<form name="calc" id="form2">
					<tr class="warning">
						<th><h4 class="text-center text-success">Simple Calculator</h4></th>
					</tr>
					<tr>
						<th><input type="text" name="input" placeholder="Enter Input Value"></th>
					</tr>
					<tr>
						<td> 
							<input type="button" value=" ( " class="common" onclick="document.calc.input.value += '('">
							<input type="button" value=" CLR " id="clear" onclick="document.calc.input.value = ''">
							<input type="button" value=" SQR " class="root" onclick="document.calc.input.value =Math.pow(input.value,2)">
							<input type="button" value=" SQRT " class="root" id="sqrt" onclick="squareroot()">
							<input type="button" value=" CUB " class="root" onclick="document.calc.input.value =Math.pow(input.value,3)">
							<input type="button" value=" CBRT " class="root" id="cbrt" onclick="document.calc.input.value =Math.cbrt(input.value)">
							<input type="button" value=" X^X " class="pow"  onclick="document.calc.input.value =Math.pow(input.value,input.value)">
							<br/>
							<input type="button" value=" ) " class="common" onclick="document.calc.input.value += ')'">
							<input type="button" value=" 1 " class="number" onclick="document.calc.input.value += '1'">
							<input type="button" value=" 2 " class="number" onclick="document.calc.input.value += '2'">
							<input type="button" value=" 3 " class="number" onclick="document.calc.input.value += '3'">
							<input type="button" value=" + " class="arith" onclick="document.calc.input.value += '+'">
							<input type="button" value=" % " class="arith" onclick="document.calc.input.value += '%'">
							<input type="button" value=" EXP " class="exp" onclick="document.calc.input.value = Math.exp(input.value)">
							<br/>
							<input type="button" value=" PI " class="common" onclick="document.calc.input.value =(input.value)*Math.PI ">
							<input type="button" value=" 4 " class="number" onclick="document.calc.input.value += '4'">
							<input type="button" value=" 5 " class="number" onclick="document.calc.input.value += '5'">
							<input type="button" value=" 6 " class="number" onclick="document.calc.input.value += '6'">
							<input type="button" value=" - " class="arith" onclick="document.calc.input.value += '-'">
							<input type="button" value=" SIN " class="trig" onclick="document.calc.input.value =Math.sin(input.value)">
							<input type="button" value=" Log2" class="log" onclick="logbase2()">
							<br/>
							<input type="button" value=" ABS " class="common" onclick="document.calc.input.value =Math.abs(input.value)">
							<input type="button" value=" 7 " class="number" onclick="document.calc.input.value += '7'">
							<input type="button" value=" 8 " class="number" onclick="document.calc.input.value += '8'">
							<input type="button" value=" 9 " class="number" onclick="document.calc.input.value += '9'">
							<input type="button" value=" X " class="arith" onclick="document.calc.input.value += '*'">
							<input type="button" value=" COS " class="trig" onclick="document.calc.input.value =Math.cos(input.value)">
							<input type="button" value=" Log10 " class="log" onclick="logbase10()">
							<br/>
							<input type="button" value=" POW " class="common" onclick="power()">
							<input type="button" value=" &bull; " id="dot" onclick="document.calc.input.value += '.'">
							<input type="button" value=" 0 " class="number" onclick="document.calc.input.value += '0'">
							<input type="button" value=" = " id="result" onclick="document.calc.input.value=eval(document.calc.input.value)">
							<input type="button" value=" / " class="arith" onclick="document.calc.input.value += '/'">
							<input type="button" value=" TAN " class="trig" onclick="document.calc.input.value =Math.tan(input.value)">
							<input type="button" value=" LogE " class="log" onclick="logbaseE()">
						</td>	
					</tr>
					</form>
				</table>
				</div>
			</div>
			<div class="col-md-9">
				<div id="examStart">
	<form action="ExamResult.php" method="post" id="form1">
		<?php
		if(mysqli_num_rows($result2)>0)
		{
			$i=1;
			while ( $i<=$row) 
			{	
				$record=mysqli_fetch_array($result1);
				$record1=mysqli_fetch_array($result2);

			?>
					<h4>Question-<?php echo $i; ?>. 
						<?php echo $record['name']; ?></h4>
					<span style="margin-left: 30px;">A. <input type="radio" name="ques<?php echo $i;?>" value="<?php echo $record['optionA'];?>" onclick="validate(this.value,<?php echo $record['question_id']; ?>)"
						<?php if($record['optionA']==$record1['chooseoption']) echo "checked"; ?>>&nbsp;
						<?php echo $record['optionA'];?>
					</span><br>
					<span style="margin-left: 30px;">B. <input type="radio" name="ques<?php echo $i;?>" value="<?php echo $record['optionB'];?>" onclick="validate(this.value,<?php echo $record['question_id']; ?>)"
						<?php if($record['optionB']==$record1['chooseoption']) echo "checked"; ?>>&nbsp;
						<?php echo $record['optionB'];?>
					</span><br>
					<span style="margin-left: 30px;">C. <input type="radio" name="ques<?php echo $i;?>" value="<?php echo $record['optionC'];?>" onclick="validate(this.value,<?php echo $record['question_id']; ?>)"
						<?php if($record['optionC']==$record1['chooseoption']) echo "checked"; ?>>&nbsp;
						<?php echo $record['optionC'];?>
					</span><br>
					<span style="margin-left: 30px;">D. <input type="radio" name="ques<?php echo $i;?>" value="<?php echo $record['optionD'];?>" onclick="validate(this.value,<?php echo $record['question_id']; ?>)"
						<?php if($record['optionD']==$record1['chooseoption']) echo "checked"; ?>>&nbsp;
						<?php echo $record['optionD'];?></span>

			<?php
					$i++;
				}

			?>
				

		<?php

		}
		else
		{
			$i=1;
			while ( $i<=$row) 
			{	
				$record=mysqli_fetch_array($result1);
				$record1=mysqli_fetch_array($result2);
				
				mysqli_query($con,"insert into exam (stuid,Ques_id) values('$_SESSION[studentid]','$record[question_id]')");

			?>
					<h4>Question-<?php echo $i; ?>. 
						<?php echo $record['name']; ?></h4>
					<span style="margin-left: 30px;">A. <input type="radio" name="ques<?php echo $i;?>" value="<?php echo $record['optionA'];?>" onclick="validate(this.value,<?php echo $record['question_id']; ?>)">&nbsp;
						<?php echo $record['optionA'];?>
					</span><br>
					<span style="margin-left: 30px;">B. <input type="radio" name="ques<?php echo $i;?>" value="<?php echo $record['optionB'];?>" onclick="validate(this.value,<?php echo $record['question_id']; ?>)">&nbsp;
						<?php echo $record['optionB'];?>
					</span><br>
					<span style="margin-left: 30px;">C. <input type="radio" name="ques<?php echo $i;?>" value="<?php echo $record['optionC'];?>" onclick="validate(this.value,<?php echo $record['question_id']; ?>)">&nbsp;
						<?php echo $record['optionC'];?>
					</span><br>
					<span style="margin-left: 30px;">D. <input type="radio" name="ques<?php echo $i;?>" value="<?php echo $record['optionD'];?>" onclick="validate(this.value,<?php echo $record['question_id']; ?>)">&nbsp;
						<?php echo $record['optionD'];?></span>

			<?php
					$i++;
				}
			?>
		
				
		<?php
		}

		$_SESSION['c_id']=$c_id['course_id'];

	?>
	<br>
	<div class="text-right" style="margin-bottom: 0px;">
		<input type="submit" value="FinishExam" onclick="return SignOut()" class="btn btn-success">
	</div>
	</form>
</div>
			</div>
		</div>
	</div>
</body>
</html>