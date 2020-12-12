<script>
$.ajax({
	url: "http://10.6.209.252:5000/service.asmx/CreateLead?OwnerEmail=raheel.khawer@hblasset.com&Name='test'&LeadEmail='test@gmail.com'&MobileNumber='000'&City='k'&Fund='test'&&CNICORNTN=4210140790685",
type: 'GET',
dataType: 'jsonp',
crossDomain: true,
cache:false,
headers: {
'Access-Control-Allow-Origin': '*',
'Content-Type': 'text/xml',
"Access-Control-Allow-Credentials" : true // Required for cookies, authorization headers with HTTPS

},

success: function(data) {    
console.log(data);
},
});     

</script>