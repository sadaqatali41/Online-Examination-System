function UGFormValidation() 
{
	var rollno=document.getElementById('rollno').value;
	var insname=document.getElementById('insname').value;
	var board=document.getElementById('board').value;
	var yop=document.getElementById('yop').value;
	var percent=document.getElementById('percent').value;
	var rollnoInter=document.getElementById('rollnoInter').value;
	var insnameInter=document.getElementById('insnameInter').value;
	var boardInter=document.getElementById('boardInter').value;
	var boardInter=document.getElementById('boardInter').value;
	var yopInter=document.getElementById('yopInter').value;
	var percentInter=document.getElementById('percentInter').value;
	var capcha=document.getElementById('code').value;
	var patt=/["!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~"]/;
	if (rollno=="" || rollno==null) 
	{
		alert("Please Enter Your Roll Number !");
		document.getElementById('rollno').focus();
		return false;
	}
	else if(rollno.length>7 || rollno.length<7)
	{
		alert("Please Enter Exact 7 Digits Roll Number !");
		document.getElementById('rollno').focus();
		return false;
	}
	else if(insname=="")
	{
		alert("Please Enter Your College or Institution Name !");
		document.getElementById('insname').focus();
		return false;
	}
	else if(insname.length<10)
	{
		alert("Your College or Institution Name Must Contain More Than 10 Characters !");
		document.getElementById('insname').focus();
		return false;
	}
	else if(insname.length>70)
	{
		alert("Your College or Institution Name Should Contain Less Than 70 Characters !");
		document.getElementById('insname').focus();
		return false;
	}
	else if(board=="")
	{
		alert("Please Enter Your Board Name\nLike State Board/CBSE/ICSE/Others Board !");
		document.getElementById('board').focus();
		return false;
	}
	else if(board.length>15)
	{
		alert("Board Name Must Contain Less Than 10 Characters !");
		document.getElementById('board').focus();
		return false;
	}
	else if(patt.test(board))
	{
		alert("Please Don't Use Special Characters in Your Board Name !");
		document.getElementById('board').focus();
		return false;
	}
	else if (yop=="" || yop==null) 
	{
		alert("Please Enter Year of Passing !");
		document.getElementById('yop').focus();
		return false;
	}
	else if(yop.length>4 || yop.length<4)
	{
		alert("Please Enter Exact 4 Digits Number !");
		document.getElementById('yop').focus();
		return false;
	}
	else if(percent=="" || percent==null)
	{
		alert("Please Enter Your Percentage !");
		document.getElementById('percent').focus();
		return false;
	}
	else if(percent.length>2 || percent.length<2)
	{
		alert("Please Enter Percentage in 2 Digits !");
		document.getElementById('percent').focus();
		return false;
	}
	else if (rollnoInter=="" || rollnoInter==null) 
	{
		alert("Please Enter Your Roll Number !");
		document.getElementById('rollnoInter').focus();
		return false;
	}
	else if(rollnoInter.length>7 || rollnoInter.length<7)
	{
		alert("Please Enter Exact 7 Digits Roll Number !");
		document.getElementById('rollnoInter').focus();
		return false;
	}
	else if(insnameInter=="")
	{
		alert("Please Enter Your College or Institution Name !");
		document.getElementById('insnameInter').focus();
		return false;
	}
	else if(insnameInter.length<10)
	{
		alert("Your College or Institution Name Must Contain More Than 10 Characters !");
		document.getElementById('insnameInter').focus();
		return false;
	}
	else if(insnameInter.length>70)
	{
		alert("Your College or Institution Name Should Contain Less Than 70 Characters !");
		document.getElementById('insnameInter').focus();
		return false;
	}
	else if(boardInter=="")
	{
		alert("Please Enter Your Board Name\nLike State Board/CBSE/ICSE/Others Board !");
		document.getElementById('boardInter').focus();
		return false;
	}
	else if(boardInter.length>15)
	{
		alert("Board Name Must Contain Less Than 10 Characters !");
		document.getElementById('boardInter').focus();
		return false;
	}
	else if(patt.test(boardInter))
	{
		alert("Please Don't Use Special Characters in Your Board Name !");
		document.getElementById('boardInter').focus();
		return false;
	}
	else if (yopInter=="" || yopInter==null) 
	{
		alert("Please Enter Year of Passing !");
		document.getElementById('yopInter').focus();
		return false;
	}
	else if(yopInter.length>4 || yopInter.length<4)
	{
		alert("Please Enter Exact 4 Digits Number !");
		document.getElementById('yopInter').focus();
		return false;
	}
	else if(percentInter=="" || percentInter==null)
	{
		alert("Please Enter Your Percentage !");
		document.getElementById('percentInter').focus();
		return false;
	}
	else if(percentInter.length>2 || percentInter.length<2)
	{
		alert("Please Enter Percentage in 2 Digits !");
		document.getElementById('percentInter').focus();
		return false;
	}
	else if(capcha=="")
	{
		alert("Please Enter Capcha !");
		document.getElementById('code').focus();
		return false;
	}
	else if(capcha.length>5 || capcha.length<5)
	{
		alert("Please Enter 5 Digits Capcha !");
		document.getElementById('code').focus();
		return false;
	}
}