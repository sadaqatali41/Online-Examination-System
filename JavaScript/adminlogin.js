function AdminValidation()
{
	var admin=document.getElementById('adminid').value;
	var pass=document.getElementById('pass').value;
	var capcha=document.getElementById('code').value;
	var patt=/["!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~"]/;
	var patt1=/[0-9]/;
	if(admin=="" || admin==null)
	{
		alert('Please Enter Your Login Id !');
		document.getElementById('adminid').focus();
		return false;
	}
	else if(admin.length<5) 
	{
		alert('Minimum Length of Login Id Should be 5 Characters !');
		document.getElementById('adminid').focus();
		return false;
	}
		else if(admin.length>10) 
	{
		alert('Maximum Length of Login Id Should be 10 Characters !');
		document.getElementById('adminid').focus();
		return false;
	}
	else if(patt.test(admin)) 
	{
		alert('Please Do not Use Any Special Characters in Your Login Id !');
		document.getElementById('adminid').focus();
		return false;
	}
	else if(!patt1.test(admin)) 
	{
		alert('Please Use Atleast one Number From [0-9] in Your Login Id !');
		document.getElementById('adminid').focus();
		return false;
	}
	else if(pass=="" || pass==null) 
	{
		alert('Please Enter Password !');
		document.getElementById('pass').focus();
		return false;
	}
	else if(pass.length<6)
	{
		alert("Minimum Password Lenght is 6 Characters !");
		document.getElementById('pass').focus();
		return false;
	}
	else if(!patt.test(pass))
	{
		alert("Please Use Atleast One Special Character in Your Password !");
		document.getElementById('pass').focus();
		return false;
	}
	else if(!patt1.test(pass))
	{
		alert("Please Use Atleast One Integer Number in Your Password !");
		document.getElementById('pass').focus();
		return false;
	}
	else if(!(/[A-Z]/).test(pass))
	{
		alert("Please Use Atleast One Upper Case Letter in Your Password !");
		document.getElementById('pass').focus();
		return false;
	}
	else if(!(/[a-z]/).test(pass))
	{
		alert("Please Use Atleast One Lower Case Letter !");
		document.getElementById('pass').focus();
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