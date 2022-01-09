// Add second account to credit
// Note 'Add' button removed upon this event
const addDetail = document.getElementById('addPaymentDetail');
if (addDetail != null) {
	// only in 'create' NOT 'update'
	addDetail.addEventListener('click',(event) => {
		document.getElementById('detail1').style.display='block';
		document.getElementById('addPaymentDetail').style.display = 'none';

	});
}

// hide second account to credit.
const removeDetail = document.getElementById('removePaymentDetail1');
if (removeDetail != null) {
	// only in 'create' NOT 'update'
	removeDetail.addEventListener('click',(event) => {
		document.getElementById('detail1').style.display = 'none';
		document.getElementById('addPaymentDetail').style.display = 'block';
	});
}


// Payment searchName does it exist
const searchName = document.getElementById('payments-searchname');
searchName.addEventListener('blur',(event) => {
	if (document.getElementById('payments-paidby_id').value < 0) {
		document.getElementById('newNameWarning').innerHTML = 'Warning, NOT an existing person';
	}
});

// BEFOREVALIDATE
$("#charges-payments").on('beforeValidate',function(event){
	// if paymentDetails[1] not used, then to prevent client validation errors, delete it.
	if (document.getElementById('paymentdetails-1-searchname').value.length==0 &&
				document.getElementById('paymentdetails-1-amount').value.length==0 &&
					document.getElementById('paymentdetails-1-memo').value.length==0) {

			document.getElementById('detail1').remove();
	}
	return true;

})


// Payment total
const amounts = document.querySelectorAll('.money');
const cash = document.querySelector('#payments-cash');
const check = document.querySelector('#payments-check');
const charge = document.querySelector('#payments-charge');
const paymentsTotal = document.querySelector('#payments-total');
var payments = 0;
var credits = 0;
amounts.forEach(function(item) {
	item.addEventListener('blur',(event) => {
		payments = 0;
		[cash,check,charge].forEach(function (fld) {
			if (fld.value && !isNaN(+fld.value) ) {
				payments = parseFloat(payments) + parseFloat(fld.value);
			}
		});
		paymentsTotal.value =  payments.toFixed(2);
		document.querySelector('#payments-amountsdiff').value = payments - credits;
		document.getElementById("payments-total").dispatchEvent(new Event("blur"));
	});
});
const check2 = document.querySelector('#payments-check');
check2.addEventListener('blur',(event) => {
	document.querySelector('#payments-checkno').dispatchEvent(new Event("blur"));
});

// Total credit amounts
const credit = document.querySelectorAll('.amount');
credit.forEach(function(item) {
	item.addEventListener('blur',(event) => {
		credits = 0;
		credit.forEach(function (fld) {
			if (fld.value && !isNaN(+fld.value) ) {
				credits = parseFloat(credits) + parseFloat(fld.value);
			}
		});
		document.querySelector('#payments-amountsdiff').value = payments - credits;
		document.getElementById("payments-total").dispatchEvent(new Event("blur"));
	});
});


// Initial form
document.getElementById("payments-account").dispatchEvent(new Event("change"));

