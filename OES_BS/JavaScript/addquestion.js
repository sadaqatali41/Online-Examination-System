function AddQuestion()
{
	var course=document.getElementById('course').value;
	var question=document.getElementById('question').value;
	var opa=document.getElementById('optiona').value;
	var opb=document.getElementById('optionb').value;
	var opc=document.getElementById('optionc').value;
	var opd=document.getElementById('optiond').value;
	var cop=document.getElementById('correctoption').value;
	var patt=/["!"#$%&'()*+,./:;<=>?@[\]^_`{|}~"]/;
	if(course=="" || course==null)
	{
		alert('Please Select Course !');
		document.getElementById('course').focus();
		return false;
	}
	else if(question=="")
	{
		alert('Please Add The Question In Text Area !');
		document.getElementById('question').focus();
		return false;
	}
	else if(question.length<10)
	{
		alert('Minimum Question Length is 10 Characters !');
		document.getElementById('question').focus();
		return false;
	}
	else if(opa=="")
	{
		alert('Please Set Option A !');
		document.getElementById('optiona').focus();
		return false;
	}
	else if(opb=="")
	{
		alert('Please Set Option B !');
		document.getElementById('optionb').focus();
		return false;
	}
	else if(opc=="")
	{
		alert('Please Set Option C !');
		document.getElementById('optionc').focus();
		return false;
	}
	else if(opd=="")
	{
		alert('Please Set Option D !');
		document.getElementById('optiond').focus();
		return false;
	}
	else if(cop=="")
	{
		alert('Please Set Correct Option !');
		document.getElementById('correctoption').focus();
		return false;
	}
}