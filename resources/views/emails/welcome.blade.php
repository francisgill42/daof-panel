<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>

<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
/*th, td {
  padding: 15px;
}
*/

.container
{
	width: 50%;
	margin: 0 auto;
}


</style>

<body>

<div class="container">
<!-- <strong>Dear Muhammad Abdul Qudoos,</strong>  -->

<strong>Dear {{$data['customer_name']}},</strong> 
@if($data['qq'] == 'pk')
<p>Thank you for opening your digital investment account with HBL Asset Management Ltd.</p>
@endif
<p>We have received your information. Our investment advisor will contact you in 48 hours in case of any further information required.</p>

<p>Kindly note the following credentials:</p>


<table style="width:100%">
  <tr>
  	
   <th colspan="2" style="padding: 15px; background-color: #01a896; color: #fff; text-align: left;"> Customer Credentials</th>	
   
  </tr>
  <tr>
    <td style="padding: 15px;">Name</td>
    <td style="padding: 15px;">{{$data['customer_name']}}</td>
    
  </tr>
  <tr>
    <td style="padding: 15px;">Temporary Account No.</td>
    <td style="padding: 15px;">{{$data['cnic']}}</td>
  </tr>
</table>

<p>Our customers are our most valuable asset. It will be a pleasure for us to help you with any query or information that you may have. Please feel free to contact us on Toll Free No. 0800 42526 (during business hours) or email us on <a href="#">info@hblasset.com </a>  in case of any queries related to your account.
</p>

<p>Regards,</p>



<b>Investor Services Department</b>
<br>
<b>HBL Asset Management Ltd.</b>


</div>

</body>
</html>