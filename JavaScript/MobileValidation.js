function fetchmobile(str)
{
	var req=new XMLHttpRequest();
	req.open("post","http://localhost/OES_BS/getmobile.php",true);
	req.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	req.send("number="+str);
	req.onreadystatechange=function() 
	{
		 if (req.readyState==4 && req.status==200)
		{
			document.getElementById('mobile').value=req.responseText;
			document.getElementById('mobile').style.color="blue";
			return false;
		}
	};
}