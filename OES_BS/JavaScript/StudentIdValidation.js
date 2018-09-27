function fetchstuid(str) 
{
	var req=new XMLHttpRequest();
	req.open("post","http://localhost/OnlineExamination1/getstuid.php",true);
	req.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	req.send("stuid="+str);
	req.onreadystatechange=function() 
	{
		 if (req.readyState==4 && req.status==200)
		{
			document.getElementById('stuid').value=req.responseText;
			document.getElementById('stuid').style.color="red";
			return false;
		}
	};
}