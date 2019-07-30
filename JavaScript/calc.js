var x;
var y;
function squareroot(){
	x=Number(document.calc.input.value);
	if(x<0)
	{
		alert("Negative Number Is Not Allowed.!!!");
	}
	else
	{
		document.calc.input.value=Math.sqrt(x);
	}
}
function logbase2(){
	x=Number(document.calc.input.value);
	if(x<0)
	{
		alert("Negative Number Is Not Allowed.!!!");
	}
	else if(x==0)
	{
		alert("Upper Value Of Log Can't Be Equal Zero!!");
	}
	else
	{
		document.calc.input.value=Math.log2(x);
	}
}
function logbase10(){
	x=Number(document.calc.input.value);
	if(x<0)
	{
		alert("Negative Number Is Not Allowed.!!!");
	}
	else if(x==0)
	{
		alert("Upper Value Of Log Can't Be Equal Zero!!");
	}
	else
	{
		document.calc.input.value=Math.log10(x);
	}
}
function logbaseE(){
	x=Number(document.calc.input.value);
	if(x<0)
	{
		alert("Negative Number Is Not Allowed.!!!");
	}
	else if(x==0)
	{
		alert("Upper Value Of Log Can't Be Equal Zero!!");
	}
	else
	{
		document.calc.input.value=Math.log(x);
	}
}
function power(){
	var x=Number(prompt("Enter X Value of Pow(x,y)?","Number Should Be Integer."));
	var y=Number(prompt("Enter Y Value of Pow(x,y)?","Number Should Be Integer."));
	document.calc.input.value=Math.pow(x,y);;
}