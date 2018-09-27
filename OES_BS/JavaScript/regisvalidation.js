function RegistrationValidation()
{
	var fname=document.getElementById("fname").value;
	var lname=document.getElementById("lname").value;
	var course=document.getElementById("course").value;
	var center=document.getElementById("center").value;
	var fname=document.getElementById("fname").value;
	var number=document.getElementById("mobile").value;
	var password1=document.getElementById("password1").value;
	var password2=document.getElementById("password2").value;
	var email=document.getElementById("email").value;
	var cont=document.getElementById("cont").value;
	var state=document.getElementById("state").value;
	var dist=document.getElementById("dist").value;
	var address=document.getElementById("address").value;
	var gender=document.getElementsByName("gender");
	var bday=document.getElementsByName("birthday");
	var capcha=document.getElementById('code').value;
	var atindex=email.indexOf('@');
	var dotindex=email.lastIndexOf('.');
	var patt=/["!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~"]/;
	var patt1=/[0-9]/;

	if (fname=="" || fname==null) 
	{
		alert("Please Enter The First Name !");
		document.getElementById("fname").focus();
		return false;
	}
	else if(/\s/.test(fname))
	{
		alert("Please don't use Space in Your First Name !");
		document.getElementById("fname").focus();
		return false;
	}
	else if(patt.test(fname))
	{
		alert("Please Don't Use Special Characters in Your First Name !");
		document.getElementById("fname").focus();
		return false;
	}
	else if(patt1.test(fname))
	{
		alert("Please Don't Use Numbers in Your First Name !");
		document.getElementById("fname").focus();
		return false;
	}
	else if(fname.length<2)
	{
		alert("First Name Should Have Atleast 2 Characters !");
		document.getElementById("fname").focus();
		return false;
	}
	else if(lname==null||lname=="")
	{
		alert("Please Enter Your Last Name !");
		document.getElementById("lname").focus();
		return false;
	}
	else if(/\s/.test(lname))
	{
		alert("Please don't use Space in Your Last Name !");
		document.getElementById("lname").focus();
		return false;
	}
	else if(patt.test(lname))
	{
		alert("Please Don't Use Special Characters in Your Last Name !");
		document.getElementById("lname").focus();
		return false;
	}
	else if(patt1.test(lname))
	{
		alert("Please Don't Use Numbers in Your Last Name !");
		document.getElementById("lname").focus();
		return false;
	}
	else if(lname.length<3)
	{
		alert("Last Name Should Have More Than 2 Characters !");
		document.getElementById("lname").focus();
		return false;
	}
	else if(course=="" || course==null)
	{
		alert("Please Select Your Course !");
		document.getElementById("course").focus();
		return false;
	}
	else if(center=="" || center==null)
	{
		alert("Please Select Your Center !");
		document.getElementById("center").focus();
		return false;
	}
	else if(number==null || number=="")
	{
		alert("Please Enter Your Mobile Number !");
		document.getElementById("mobile").focus();
		return false;
	}
	else if(/[a-zA-Z]/.test(number)) 
	{
		alert("Please don't use [a-zA-Z] Characters in Your Mobile Number !");
		document.getElementById("mobile").focus();
		return false;
	}
	else if(patt.test(number)) 
	{
		alert("Please don't use Special Characters in Your Mobie Number !");
		document.getElementById("mobile").focus();
		return false;
	}
	else if(/\s/.test(number))
	{
		alert("Please don't use Space in Your Mobie Number !");
		document.getElementById("mobile").focus();
		return false;
	}
	else if(/^[0-6]/.test(number))
	{
		alert("Please Don't Use [0-6] Numbers in Your Mobile Number !");
		document.getElementById("mobile").focus();
		return false;
	}
	else if(number.length<=9|| number.length>10)
	{
		alert("Please Enter 10 Digits Mobile Numbers !");
		document.getElementById("mobile").focus();
		return false;
	}
	else if(password1=="" || password1==null)
	{
		alert("Please Enter Your Password !");
		document.getElementById("password1").focus();
		return false;
	}
	else if(password1.length<6)
	{
		alert("Minimum Password Lenght is 6 Characters !");
		document.getElementById("password1").focus();
		return false;
	}
	else if(!patt.test(password1))
	{
		alert("Please Use Atleast One Special Character in Your Password !");
		document.getElementById("password1").focus();
		return false;	
	}
	else if(!patt1.test(password1))
	{
		alert("Please Use Atleast One Integer Number in Your Password !");
		document.getElementById("password1").focus();
		return false;
	}
	else if(!/[A-Z]/.test(password1))
	{
		alert("Please Use Atleast One Upper Case Letter in Your Password !");
		document.getElementById("password1").focus();
		return false;
	}
	else if(!/[a-z]/.test(password1))
	{
		alert("Please Use Atleast One Lower Case Letter !");
		document.getElementById("password1").focus();
		return false;
	}
	else if(password2==null || password2=="")
	{
		alert("Please Enter Your Confirmation password !");
		document.getElementById("password2").focus();
		return false;
	}
	else if(password1!=password2)
	{
		alert("Mismatch Password !");
		document.getElementById("password2").focus();
		return false;
	}
	else if(email=="" || email==null)
	{
		alert("Please Enter Your Email Address !");
		document.getElementById("email").focus();
		return false;
	}
	else if(/\s/.test(email))
	{
		alert("Please don't use Space in Your Email Address !");
		document.getElementById("email").focus();
		return false;
	}
	else if(atindex<1 || dotindex>=(email.length)-2 || (dotindex-atindex)<3)
	{
		alert("Invalid Email Address !");
		document.getElementById("email").focus();
		return false;
	}
	else if((email.match(/@/g)).length>=2)
	{
		alert("Please Don't Use Double '@' !");
		document.getElementById("email").focus();
		return false;
	}
	else if(!/^\w+([\.-]?\w+)*@[^0-9]\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
	{
		alert("It is Not a Format Of Valid Email Id !\nAnd Please Don't Use Any Special Characters !");
		document.getElementById("email").focus();
		return false;
	}
	else if(cont=="" || cont==null)
	{
		alert("Please Enter Your Country Name !");
		document.getElementById("cont").focus();
		return false;
	}
	else if(patt.test(cont)) 
	{
		alert("Please Don't Use Special Characters in Your Country !");
		document.getElementById("cont").focus();
		return false;
	}
	else if(patt1.test(cont)) 
	{
		alert("Please Don't Use Any Number in Your Country !");
		document.getElementById("cont").focus();
		return false;
	}
	else if(state=="" || state==null)
	{
		alert("Please Enter Your State Name !");
		document.getElementById("state").focus();
		return false;
	}
	else if(patt.test(state))
	{
		alert("Please Don't Use Special Characters in Your State !");
		document.getElementById("state").focus();
		return false;
	}
	else if(patt1.test(state))
	{
		alert("Please Don't Use Any Number in Your State !");
		document.getElementById("state").focus();
		return false;
	}
	else if(dist=="" || dist==null)
	{
		alert("Please Enter Your District Name !");
		document.getElementById("dist").focus();
		return false;
	}
	else if(patt.test(dist))
	{
		alert("Please Don't Use Special Characters in Your District !");
		document.getElementById("dist").focus();
		return false;
	}
	else if(patt1.test(dist))
	{
		alert("Please Don't Use Any Number in Your District !");
		document.getElementById("dist").focus();
		return false;
	}
	else if(address=="" || address==null)
	{
		alert("Please Enter Your Address !");
		document.getElementById("address").focus();
		return false;
	}
	else if(address.length<20)
	{
		alert("Address Must Contains 20 Characters with Pin Code !");
		document.getElementById("address").focus();
		return false;
	}
	else if(address.length>60)
	{
		alert("Address Must Be Lesser Than 60 Characters !");
		document.getElementById("address").focus();
		return false;
	}
	else if(!(gender[0].checked || gender[1].checked || gender[2].checked))
	{
		alert("Please Select Your Gender !");
		return false;
	}
	else if(bday[0].value=="")
	{
		alert("Please Select Your DOB !");
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
