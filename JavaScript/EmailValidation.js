function fetchemail(str)
{
	var req=new XMLHttpRequest();
	req.open("post","http://localhost/OES_BS/getemail.php",true);
	req.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	req.send("email="+str);
	req.onreadystatechange=function() 
	{
		 if (req.readyState==4 && req.status==200)
		{
			document.getElementById('email').value=req.responseText;
			document.getElementById('email').style.color="magenta";
			return false;
		}
	};
}