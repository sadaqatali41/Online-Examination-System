function PasswordValidation1()
{
	var email=document.getElementById("email").value;
	var capcha=document.getElementById('code').value;
	var atindex=email.indexOf('@');
	var dotindex=email.lastIndexOf('.');
	
	if(email=="" || email==null)
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