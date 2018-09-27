function PasswordValidation2()
{
	var pass1=document.getElementById('password').value;
	var pass2=document.getElementById('password1').value;
	var capcha=document.getElementById('code').value;
	var patt=/["!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~"]/;
	var patt1=/[0-9]/;
	
	if(pass1==null||pass1=="")
	{
		alert("Please Enter Your Password !");
		document.getElementById('password').focus();
		return false;
	}
	else if(pass1.length<6)
	{
		alert("Minimum Password Lenght is 6 Characters !");
		document.getElementById('password').focus();
		return false;
	}
	else if(!patt.test(pass1))
	{
		alert("Please Use Atleast One Special Character in Your Password !");
		document.getElementById('password').focus();
		return false;
	}
	else if(!patt1.test(pass1))
	{
		alert("Please Use Atleast One Integer Number in Your Password !");
		document.getElementById('password').focus();
		return false;
	}
	else if(!(/[A-Z]/).test(pass1))
	{
		alert("Please Use Atleast One Upper Case Letter in Your Password !");
		document.getElementById('password').focus();
		return false;
	}
	else if(!(/[a-z]/).test(pass1))
	{
		alert("Please Use Atleast One Lower Case Letter !");
		document.getElementById('password').focus();
		return false;
	}
	else if(pass2==null || pass2=="")
	{
		alert("Please Enter Your Confirmation password !");
		document.getElementById('password1').focus();
		return false;
	}
	else if(pass1!=pass2)
	{
		alert("Mismatch Password !");
		document.getElementById('password1').focus();
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