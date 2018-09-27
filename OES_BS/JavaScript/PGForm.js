function PGFormValidation() 
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
	var Cname=document.getElementById('Cname').value;
	var enroll=document.getElementById('enroll').value;
	var Bname=document.getElementById('Bname').value;
	var insG=document.getElementById('insG').value;
	var yopUg=document.getElementById('yopUg').value;
	var percentUg=document.getElementById('percentUg').value;
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
	else if(Cname=="")
	{
		alert("Please Enter Your Course Name !");
		document.getElementById('Cname').focus();
		return false;
	}
	else if(Cname.length<3)
	{
		alert("Course Name Should Contain Atleast 3 Characters !");
		document.getElementById('Cname').focus();
		return false;
	}
	else if(Cname.length>20)
	{
		alert("Course Name Contain Atmost 20 Characters !");
		document.getElementById('Cname').focus();
		return false;
	}
	else if(/["!"#$%&'()*+,/:;<=>?@[\]^_`{|}~"]/.test(Cname))
	{
		alert("Please Don't Use Special Characters in Course Name !")
		document.getElementById('Cname').focus();
		return false;
	}
	else if(enroll=="")
	{
		alert("Please Enter Your Enrollment Number !");
		document.getElementById('enroll').focus();
		return false;
	}
	else if(enroll.length<10 || enroll.length>10)
	{
		alert("Please Enter Exact 10 Digits Enrollment Number !");
		document.getElementById('enroll').focus();
		return false;
	}
	else if(patt.test(enroll))
	{
		alert("Please Don't use Special Characters in Your Enrollment Number !");
		document.getElementById('enroll').focus();
		return false;
	}
	else if(Bname=="")
	{
		alert("Enter Your Branch Name !");
		document.getElementById('Bname').focus();
		return false;
	}
	else if(Bname.length<2)
	{
		alert("Minimum Branch Name is 2 Characters !");
		document.getElementById('Bname').focus();
		return false;
	}
	else if(patt.test(Bname))
	{
		alert("Please Don't use Special Characters in Your Branch Name !");
		document.getElementById('Bname').focus();
		return false;
	}
	else if(insG=="")
	{
		alert("Please Enter Your Institution/University Name !");
		document.getElementById('insG').focus();
		return false;
	}
	else if(insG.length<10)
	{
		alert("Your Institution/University Name Must Contain More Than 10 Characters !");
		document.getElementById('insG').focus();
		return false;
	}
	else if(insG.length>70)
	{
		alert("Your Institution/University Name Should Contain Less Than 70 Characters !");
		document.getElementById('insG').focus();
		return false;
	}
	else if (yopUg=="" || yopUg==null) 
	{
		alert("Please Enter Year of Passing !");
		document.getElementById('yopUg').focus();
		return false;
	}
	else if(yopUg.length>4 || yopUg.length<4)
	{
		alert("Please Enter Exact 4 Digits Number !");
		document.getElementById('yopUg').focus();
		return false;
	}
	else if(percentUg=="" || percentUg==null)
	{
		alert("Please Enter Your Percentage !");
		document.getElementById('percentUg').focus();
		return false;
	}
	else if(percentUg.length>2 || percentUg.length<2)
	{
		alert("Please Enter Percentage in 2 Digits !");
		document.getElementById('percentUg').focus();
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