function AddCity() 
{
	 var city=document.getElementById('city').value;
	 var address=document.getElementById('caddress').value;
	 var patt=/["!"#$%&'()*+,./:;<=>?@[\]^_`{|}~"]/;
	 if(city=="" || city==null)
	{
		alert('Please Enter The Center Name !');
		document.getElementById('city').focus();
		return false;
	}
	else if(/[0-9]/.test(city))
	{
		alert('Please don\'t use Any Number !');
		document.getElementById('city').focus();
		return false;
	}
	else if(patt.test(city))
	{
		alert('Please Don\'t use Any Special Characters In Center !');
		document.getElementById('city').focus();
		return false;
	}
	else if(address=="" || address==null)
	{
		alert('Please Enter The Center Address in Details !');
		document.getElementById('caddress').focus();
		return false;
	}
	else if(address.length<10)
	{
		alert('Minimum Length of Center Address is 10 Characters !');
		document.getElementById('caddress').focus();
		return false;
	}
}