function AddCourse()
{
	var course=document.getElementById('course').value;
	var cc=document.getElementById('course_catagory').value;
	var patt=/["!"#$%&'*+,/:;<=>?@[\]^_`{|}~"]/;
	if(cc=="" || cc==null)
	{
		alert('Please Selcet Course Category !');
		document.getElementById('course_catagory').focus();
		return false;
	}
	else if(course=="" || course==null)
	{
		alert('Please Enter The Course Name !');
		document.getElementById('course').focus();
		return false;
	}
	else if(/[0-9]/.test(course))
	{
		alert('Please don\'t use Any Number !');
		document.getElementById('course').focus();
		return false;
	}
	else if(patt.test(course))
	{
		alert('Please Don\'t use Any Special Characters In Course !');
		document.getElementById('course').focus();
		return false;
	}
}