function paymentValidation(){
	var pay=document.getElementsByName('payment');
	var card=document.getElementById('cardnumber').value;
	var cvv=document.getElementById('cvv').value;
	var month=document.getElementById('month').value;
	var year=document.getElementById('year').value;
	var bank=document.getElementById('bank').value;
	if(pay[0].checked)
	{
		document.getElementById('cardnumber').focus();
		if(card==""){
			alert("Please Enter Your Card Numbers.");
			document.getElementById('cardnumber').focus();
			return false;
		}
		if(/^[-+\.]/.test(card)==true){
			alert("Please Don't Use (-) in Card Numbers.");
			document.getElementById('cardnumber').focus();
			return false;
		}
		if(card.length>16 || card.length<16){
			alert("Please Enter Exact 16 Digits Card Numbers.");
			document.getElementById('cardnumber').focus();
			return false;
		}
		if(card.length==16){
			document.getElementById('cvv').focus();
			if(cvv==""){
			alert("Please Enter 3 Digits CVV Number.");
			return false;
		}
		}
		if(cvv.length>3 || cvv.length<3){
			alert("Please Enter Exact 3 Digits CVV Number.");
			document.getElementById('cvv').focus();
			return false;
		}
		if(/^-/.test(cvv)==true){
			alert("Please Don't Use (-) in CVV Number.");
			document.getElementById('cvv').focus();
			return false;
		}
		if(month==""){
			alert("Please Select Your Card Expiry Month.");
			return false;
		}
		if(year==""){
			alert("Please Select Your Card Expiry Year.");
			return false;
		}
		alert("Your Payment Has Been Processed.");
		return true;
	}
	else if(pay[1].checked)
	{
		if(bank==""){
			alert("Please Select Your Bank Name.");
			document.getElementById('bank').focus();
			return false;
		}
		alert("Your Payment Has Been Processed.");
		return true;
	}
	else
	{
		alert("Please Select AnyOne Payment Option? ");
		return false;
	}
}