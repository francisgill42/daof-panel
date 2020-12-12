<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>:: HBL Corporate Presentation Demo ::</title>

<link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{url('css/bootstrap.css')}}" rel='stylesheet' type='text/css' />
<script src="{{url('js/jquery-1.11.1.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/fe_style.css')}}">
<link href="{{url('css/select2.min.css')}}" rel="stylesheet" />
<script src="{{url('js/select2.min.js')}}"></script>
<script src="{{url('js/bootstrap.js')}}"> </script>
<script src="{{ asset('js/app.js') }}" defer></script>



</head>
<body>


<div id="header" style="    position: fixed;
top: 0;
z-index: 999999999;">
<img src="{{asset('images/header-logo-img.png')}}" alt="">
</div>
<div class="container" style="margin-top: 50px;">
<div class="progress" style="position: fixed;
width: 53%;
z-index: 9999999999;
top: 16px;
left: 71%;
margin-left: -445px;">
<div id="pb" style="background-color: #01a896;" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">

</div>
</div>
<br  >

@if (session()->has('msg'))
<div class="alert alert-success" role="alert">
<strong>Well done!</strong> {{session('msg')}}
</div>
@endif

@if (session()->has('err'))
<div class="alert alert-danger" role="alert">
{{session('err')}}
</div>
@endif


<?php
if(count($errors) > 0){
if(in_array('The Fund Field has a duplicate value.', $errors->all() ) ){ ?>
<div class="alert alert-danger" role="alert">
The Fund Field has a duplicate value.
</div>
<?php } ?>
@foreach($errors->all() as $error)
@if($error != 'The Fund Field has a duplicate value.')
<div class="alert alert-danger" role="alert">
{{$error}}
</div>
@endif

@endforeach
<?php   } ?>

<form  method="post" action="{{url('preview')}}" enctype="multipart/form-data">
{{csrf_field()}}

<div class="form-row">
<div class="col-md-12">

<h2>1. CUSTOMER INFORMATION</h2>
<br>
</div>
<br>
<div class="form-group col-md-4">
<label>Name</label>
<input id="name"  name="name" class="form-control pb" value="{{old('name')}}" >
<p class="name_msg" style="color:red;padding:5px;"></p>
</div>

<div class="form-group col-md-4">
<label>Middle Name</label>
<input id="middle_name"  name="middle_name" class="form-control pb" value="{{old('middle_name')}}" >
<p class="middle_name_msg" style="color:red;padding:5px;"></p>
</div>

<div class="form-group col-md-4">
<label>Last Name</label>
<input id="last_name"  name="last_name" class="form-control pb" value="{{old('last_name')}}" >
<p class="last_name_msg" style="color:red;padding:5px;"></p>
</div>

<div class="form-group col-md-4">
<label>Father Name</label>
<input id="father_name"  name="father_name" class="form-control pb" value="{{old('father_name')}}" >
<p class="father_name_msg" style="color:red;padding:5px;"></p>
</div>

<div class="form-group col-md-4">
<label>Email</label>
<input id="email" type="email"  name="email" class="form-control pb" value="{{old('email')}}" >
<p class="email_msg" style="color:red;padding:5px;"></p>
</div>


<div class="form-group col-md-4">
<label>Date of Birth</label>

<input  id="dob" name="dob" type="date" max="<?php echo date('Y-m-d'); ?>" value="{{old('dob')}}"  class="form-control pb">
<p class="dob_msg" style="color:red;padding:5px;"></p>

</div>


<div class="col-md-12">

<label>Place Of Birth &nbsp&nbsp</label> 
<input  type="radio" class="pb pob_radio" name="pob_radio" value="pk"> &nbsp Pakistan
&nbsp&nbsp
<input type="radio" class="pb pob_radio" name="pob_radio"  value="o"> &nbsp Other 

<br>
<br>
</div>
<div class="form-group col-md-12 hide_class" id="show_pob_cities">
<label>Cities</label>
<select id="mycities" class="js-example-basic-single  form-control pb" name="pob_city"></select>
</div>

<div class="form-group col-md-12 hide_class" id="show_pob_city">
<label>Country</label>
<input class="form-control pb pob_country" name="pob_country">
<br>
<label>City</label>
<input class="form-control pb pob_city" name="pob_city">
</div>


<div class="form-group col-md-4">
<label>Mother's Maiden Name</label>

<input  id="mm_name" name="mm_name" value="{{old('mm_name')}}"  class="form-control pb">
<p class="mm_name_msg" style="color:red;padding:5px;"></p>

</div>
<div class="form-group col-md-4">
<label style="font-size: 14px;">CNIC</label>
<input  type="number" id="cnic" name="cnic" placeholder="XXXX-XXXXXXX-X" value="{{old('cnic')}}" class="form-control pb" >
<p class="cnic_msg" style="color:red;padding:5px;"></p>
</div>

<div class="col-md-4">
<label>CNIC Attachments:</label>
<input  name="cnic_file" type="file" style="padding: 3px 5px" class="form-control pb" >
<br>
</div>

<div class="form-group col-md-4">
<label>Mobile Number</label>
<input  type="number" value="{{old('cell')}}" id="cell"  name="cell" class="form-control pb" >
<p class="cell_msg" style="color:red;padding:5px;"></p>
</div>

<!--  -->

<div class="form-group col-md-4">
<label>Occupation</label>
<input  value="{{old('occupation')}}" id="occupation" name="occupation" class="form-control pb" >
<p class="occupation_msg" style="color:red;padding:5px;"></p>
</div>

<div class="form-group col-md-4">
<label>Source Of Income</label>
<input  value="{{old('soi')}}" id="soi" name="soi" class="form-control pb" >
<p class="soi_msg" style="color:red;padding:5px;"></p>
</div>
<div class="col-md-12">

<label class="">Zakat Deduction &nbsp&nbsp </label>
&nbsp(If No please attach affidavit CZ-50) &nbsp&nbsp&nbsp
<input type="radio" class="zak_ded pb" name="zak_ded" value="no">  &nbsp No 
&nbsp&nbsp
<input type="radio" class="zak_ded " name="zak_ded" value="yes"> &nbsp Yes   

<br>
  <input name="zak_file" id="zak_file" type="file" style="padding: 3px 5px" class="hide_class form-control pb">
</div>
<br>

<div class="col-md-12">

<label>Country &nbsp&nbsp</label> 
<input  type="radio" class="pb rc" name="rc" value="pk"> &nbsp Pakistan
&nbsp&nbsp
<input type="radio" class="pb rc" name="rc" value="o"> &nbsp Other 

<br>
<br>
</div>
<div class="form-group col-md-12 hide_class" id="show_resi_cities">
<label>Cities</label>
<select id="mycities_resi" class="js-example-basic-single  form-control pb"></select>
</div>

<div class="form-group col-md-12 hide_class" id="show_resi_cc">
<label>Country</label>
<input class="form-control pb resi_country" name="resi_country" >
<br>
<label>City</label>
<input class="form-control pb resi_city" name="resi_city" >
</div>



<div class="form-group col-md-12">
  <br>
<label>Address</label>
<textarea  id="address" name="address" class="form-control pb" rows="4" cols="50" placeholder="Describe yourself here...">{{old('address')}}</textarea>
<p class="address_msg" style="color:red;padding:5px;"></p>
</div>



</div>





<div class="form-row">

<div class="col-md-12">
<h2 >2. INVESTMENT DETAILS</h2>
<br>
<label>Please Note: Minimum Initial Investment is Rs. 5,000/- Minimum Subsequent Investment is Rs. 1,000/-</label>
</div>
</div>
<div class="fund2bappend">
<div class="form-row fund_row0">
<div class="col-md-12">
<label>Name of Fund</label>
</div>
<div class="col-md-11">
<select style="height: auto;"  name="fund_name[]" class="form-control funds fund_name" >
</select>
</div>
<div class="col-md-1"><button type="button" class="rem0 btn btn-primary">X</button></div>
<div class="col-md-5">
<label>Amount (Rs.)</label>
<input id="fund_amount"  type="number" name="funds[]" class="form-control amount sum"  min="1" max="99999">
<p class="amount_msg" style="color:red;padding:5px;"></p>

</div>
<div class="col-md-6">
<label>Amount in Words</label>
<input id="fund_aiw"  name="funds[]" class="form-control word" >
</div>


<div class="col-md-5">
<label>Payment Mode</label>
<select style="height: auto;"  name="funds[]" class="form-control" >
<option value="ibft">IBFT</option>
<option value="ch">Cheque</option>
<option value="po">Pay Order</option>
<option value="dem">Demand</option>
<option value="dra">Draft</option>
<option value="rtgs">Real Time Gross Settlement (RTGS)</option>
</select>
</div>

<div class="col-md-6">
<label>Attachments</label>
<input  name="mop_file[]" type="file" style="padding: 3px 5px" class="form-control">
</div>

<div class="col-md-5">
<br><label>Bank</label>
<input  name="funds[]" style="padding: 3px 5px" class="form-control">
</div>

<div class="col-md-6">
<br><label>Instrument Number</label>
<input  name="funds[]" style="padding: 3px 5px" class="form-control" >
</div>


</div>

</div>



<div class="form-row">
<span id="label" style="color:red; padding:5px;"></span>
<div class="col-md-12">
<p class="fund_msg" style="color:red;padding:5px;"></p>
<button type="button" id="add_fund" class="btn btn-default" name="">Add Fund</button>
&nbsp;&nbsp;&nbsp;
<button type="button" class="add_ins btn btn-primary ">Add Instrument</button>
</div>
</div>

<!-- payment mode -->

<div class="form-row mt-5">

<div class="col-md-12">
<h2 >3. FATCA Checklist</h2>
<br>

<label>For Individual & Joint Account Holders (Please write clearly using BLOCK LETTERS)</label>
<br>
<br>
<p>*if any of the below is selected as "YES" then kindly provide country specific supporting documents with details</p>
<br>

</div>
</div>

<table class="table table-bordered">
<thead class="main_thead">
<tr>
<th scope="col">Particulars</th>
<th scope="col" class="jpmix">Primary Applicant</th>
</tr>
</thead>
<tbody>


<tr>
<td width="50%">Do you have Multiple Nationalities/Passports?</td>
<td>
<label><input  type="radio" class="nat_radio" name="nat_radio" value="yes"> &nbsp;&nbsp; yes </label> 
&nbsp;&nbsp; 
<label><input  type="radio" class="nat_radio" name="nat_radio" value="no"> &nbsp;&nbsp; No </label>

<div id="nats" class="hide_class">
  <label class="form-inline">Nat 1:&nbsp;&nbsp; 
<input type="text" class="form-control" id="Nat11" name="nat[]">
</label> 

<label class="form-inline">Nat 2:&nbsp;&nbsp; 
<input type="text" class="form-control" id="Nat11" name="nat[]">
</label> 

<label class="form-inline">Nat 3:&nbsp;&nbsp; 
<input type="text" class="form-control" id="Nat11" name="nat[]">
</label> 
</div>

</td>
</tr>


<tr>
<td>Do you currently hold US green card or US Permanent Residency?</td>
<td>
<label><input type="radio" class="card_radio" name="card_radio" value="yes"> &nbsp;&nbsp; yes </label> 
&nbsp;&nbsp; 
<label><input type="radio" class="card_radio" name="card_radio" value="no"> &nbsp;&nbsp; No </label>
<label id="card" class="hide_class" class="form-inline hide_class">Card#&nbsp;&nbsp; 
<input type="text" class="form-control"  name="card"></label> 
</td>






</tr>


<tr>
<td>Are you a Tax Resident in the US?</td>
<td>
<label><input type="radio" class="tax_resi" name="tax_resi" value="yes"> &nbsp;&nbsp; yes </label> 
&nbsp;&nbsp; 
<label><input type="radio" class="tax_resi" name="tax_resi" value="no"> &nbsp;&nbsp; No </label>
</td>
</tr>

<tr>
<td>Overseas/Care-of mailing address &amp; Phone No</td>
<td>
  <input type="text" class="form-control" name="overseas[]">
  <br>
  <input type="text" class="form-control" name="overseas[]">
  <br>
  <input type="text" class="form-control" name="overseas[]">
  <br>
  <input type="text" class="form-control" name="overseas[]">
</td>



</tr>


<tr>
<td>Have you renounced your foreign citizenship or residency? </td>
<td>
<label><input type="radio" class="renounced_residency" name="renounced_residency" value="yes"> &nbsp;&nbsp; yes </label> 
&nbsp;&nbsp; 
<label><input type="radio" class="renounced_residency" name="renounced_residency" value="no"> &nbsp;&nbsp; No </label>
</td>
</tr>

 <tr>
<td>Have you given Power of Attorney to any 
  <br>
  Person residency overseas?
<p>Please provide Attorney's Address</p>
</td>
<td>
<label><input type="radio" class="resi_in_us" value="yes" name="resi_in_us"> &nbsp;&nbsp; yes </label> 
&nbsp;&nbsp; 
<label><input type="radio" class="resi_in_us" value="no" name="resi_in_us"> &nbsp;&nbsp; No </label>
<br>
<div id="attorney" class="hide_class">
<input type="text" class="form-control" name="attorney[]"> 
<br>
<input type="text" class="form-control" name="attorney[]"> 
<br>
<input type="text" class="form-control" name="attorney[]"> 
<br>
<input type="text" class="form-control" name="attorney[]"> 
</div>
</td>
</tr>

<tr>
<td>Have you given any standing instruction to <br>transfer funds to an account in US?</td>
<td>
<label><input type="radio" class="acc_in_US" value="yes" name="acc_in_US"> &nbsp;&nbsp; yes </label> 
&nbsp;&nbsp; 
<label><input type="radio" class="acc_in_US" value="no" name="acc_in_US"> &nbsp;&nbsp; No </label>
<br>
<div id="attorney" class="hide_class">
<input type="text" class="form-control" name="attorney[]"> 
<br>
</div>
</td>
</tr>
<tr>
<td width="50%">W8BEN/W9 Forms submitteds with date of Submission.</td>
<td>
<label><input type="radio" class="w_form_my" name="w_form" value="yes"> &nbsp;&nbsp; yes </label> 
&nbsp;&nbsp; 
<label><input type="radio" class="w_form_my" name="w_form" value="no"> &nbsp;&nbsp; No </label>

<div id="f_sub" class="hide_class">

<a href="{{url('forms_pdf/W8-Form.pdf')}}" target="_blank">W8</a> (FOR NON US USERS)
<br>
<a href="{{url('forms_pdf/W9-Form.pdf')}}" target="_blank">W9</a> (FOR US USERS)
<br><br>
<input type="file" name="" style="padding: 3px 5px;" class="form-control">
</div>
</td>
</tr> 

</tbody>
</table>


<div class="form-row mt-5">

<div class="col-md-12">
<h2>4. Common Reporting Standard (CRS)</h2>
<p>(CRS Self Certification Form - Individual)</p>
<br>

<label>Please read these instructions before completing the form</label>
<br>
<p>Under the CRS, you are required to determine where you are 'tax resident'. Each jurisdiction has it's own rules for defining tax residency. in general, you will find that tax residency is the country/jurisdiciton in which you live however this may not always be the case. Special circumstances may cause you to be resident elsewhere or resident in more than one country/jurisdiction at the same time (dual residency). if you are tax resident outside the country where your account is held we may need to give the national tax authority this information, along with information relating to your accounts. That your information may also be shared between different countries tax authorities.</p>

<label>Who should complete this form</label>
<br>
<p>This form is applicable for individual account holder, sole proprieter & Single Member Private Limited. For joint or multiple account holders, each individual is required to complete this form. </p>
<p>
where you need to self-certify on behalf of an entity account holder, do not use this form. instead, you will need to fill the "Entity CRS self Certification form." Similarly, if you are a controlling person of an entity, please fill in the "CRS Self Certification form - Controlling person" instead of this form.</p>
<br>

<label>Further Information</label>
<br>
<p>if you have any questions on defining your tax residency status, please consult your external adviser(s). 
you can also find out more information on common Reporting Standard on the website of Federal Board of Revenue, accessible at the following link: http://www.fbr.gov.pk </p>
<p>you can find summaries of defined terms, and other terms, in the Appendix available with branch. 
Disclaimer: Nothing in this form shall be construed to mean provision of any legal or tax advice by HBL AML</p>
<br>



</div>
</div>

<div class="form-group">
<label>CNIC/PP/NICOP/PO/SNIC/ARC</label>
<input id="nicop" name="nicop" class="form-control" value="">
</div>

<label>Do you hold tax residency of any country / jurisdiction other than Pakistan and/or United States?</label>&nbsp;&nbsp;&nbsp;
<input type="radio" class="tax_residency" name="tax_residency" value="yes"> &nbsp; Yes 
&nbsp;&nbsp;&nbsp;
<input type="radio" class="tax_residency" name="tax_residency" value="no"> &nbsp; No.

<div class="form-group crf hide_class">
<br>
<label>Upload Authorised Signature Print</label>  
<input type="file"  class="form-control" value="">

<br>
<p>In case of Yes, you are required to complete this form.<br>
In case of No, you are no longer required to complete this form.</p>

<br>
<h4 class="ioa_h">Part 1 - Identification of Accountholder</h4>
<br>
<h6>A. Name of Accountholder*</h6>
<label>Family Name or Surname(s)*</label>
<input class="form-control">
<br>
<label>Title</label>
<input class="form-control">
<br>
<label>First or Given Name* </label>
<input class="form-control">
<br>
<label>Middle Name(s)</label>
<input class="form-control">
<br>

<h6>B. Current Residential Address</h6>
<label>House/Apartment/Suite Name, Number, Street</label>
<input class="form-control">
<br>
<label>Town/City/Province/Country/State*</label>
<input class="form-control">
<br>
<label>Country*</label>
<input class="form-control">
<br>
<label>Postal Code / Zip Code (if any) </label>
<input class="form-control">
<br>
<label>P.0. Box (if any)</label>
<input class="form-control">
<br>

<h6>C. Mailing Address</h6>
<label>(Please only complete if different to the address shown in Section B)</label>
<input class="form-control">
<br>
<label>House/Apartment/Suite Name, Number, Street</label>
<input class="form-control">
<br>
<label>Town/City/Province/Country/State*</label>
<input class="form-control">
<br>
<label>Country*</label>
<input class="form-control">
<br>
<label>Postal Code / Zip Code (if any) </label>
<input class="form-control">
<br>><label>P.0. Box (if any)</label>
<input class="form-control">
<br>

<h6>D.</h6>
<label>Date of Birth *</label>
<input class="form-control">
<br>

<h6>E.</h6>
<lab><input class="form-control">
<br>
<label>Town or City of Birth</label>
<input class="form-control">
<br>
<label>Country of Birth</label>
<input class="form-control">
<br>

<label>Part 2 - Country of Tax Residence and Taxpayer Identification Number (TIN)*</label>
<br>
<p>Please fill in the country(ies) details below.</p>

<label class="pra_22">Name of Country of Tax Residence</label>
<br>
<input type="text" class="form-control" id="nocotr" name="nocotr">
<br>
<input type="text" class="form-control" id="nocotr1" name="nocotr1">
<br>
<input type="text" class="form-control" id="nocotr3" name="nocotr3">




<label class="pra_22 para222">Tax Identification (TIN)</label>
<br>
<input type="text" class="form-control" id="tif" name="tif">
<br>
<input type="text" class="form-control" id="tif1" name="tif1">
<br>
<input type="text" class="form-control" id="tif3" name="tif3">

<br>


<label class="pra_22 pra_23">If no TIN is available mention Reason A, B or C</label>
<br>
<input type="text" class="form-control" id="intamr" name="intamr">
<br>
<input type="text" class="form-control" id="intamr2" name="intamr2">
<br>
<input type="text" class="form-control" id="intamr3" name="intamr3">
<br>
<p>
Note: Additional Country(ies) of Tax Residency (if any) to be listed in a separate sheet of paper. If your Taxpayer Identification Number (TIN) or equivalent
number is unavailable, please provide the appropriate reason A, B or C where indicated below.   
</p>  


<br>
<label>Reason A</label>
<p>
The country where the Account Holder is resident does not issue TINs to its residents
</p>  

<label>Reason B</label>
<p>
The Account Holder is otherwise unable to obtain a TIN (please explain why Account Holder is unable to obtain a TIN in the below table if you have selected this reason)
</p>  

<label>Reason C</label>
<p>
No TIN is required (note: only select this reason if the authorities of the country of residence for tax purposes entered above do not required the TIN to be disclosed)
</p>
<br>
<p>
Please explain in the following relevant number box, why you are unable to obtain a TIN if you mentioned "Reason B" above.
</p>
<br>
<label>1.</label>
<input type="text" class="form-control" id="zeroone" name="zeroone">
<br>
<label>2.</label>
<input type="text" class="form-control" id="zeroone" name="zeroone">
<br>
<label>3.</label>
<input type="text" class="form-control" id="zeroone" name="zeroone">
<br>
<label>Part 3 - Declaration and Signature*</label>
<br>
<p>
I understand that the information supplied by me is covered by the full provisions of the terms and conditions governing the Account Holder's relationship with HBL AML with setting out how HBL AML may use and share this informaiton supplied by me.
<br><br>

I acknowledge that the information contained in this form and information regarding the Account Holder and any Reportable Account(s) may be provided either directly
or indirectly to the tax authorities of the country/jurisdiction in which this account(s) is are maintained and exchanged with tax authorities of another country/jurisdiction or countries/jurisdictions pursuant to intergovernmental agreements to exchange financial account information.

<br><br>
I hereby declare and confirm that all information provided in this Self Certification Form is to the best of my/our knowledge and belief, correct, I certify that I am the account Holder (or I am authorised to sign for the Account Holder) in respect of all the account(s) to which this form relates.
<br><br>
accurate and complete in all respects.
<br><br>
I/We hereby indemnify and hold HBL AML and it's directors, officers, representatives and employees harmless from all costs, expenses, losses, demages, liability, penalties incurred, suffered and/or imposed on HBL AML as a result of any suits, proceedings and/or litigation arising out of or in any manner connected with this Self Certification form and/or the information supplied hereby.
<br><br>
in case of change in any information provided through this form, I undertake to immediately notify HBL AML of the same and provide an Updated Self-Certification Form to HBL AML within thirty (30) days.

</p>
<br>
<label>Name</label>
<input type="text" class="form-control" id="name_22" name="name_22">
<br>
<label>Upload Signature print</label>
<input type="file" class="form-control">
<br>
<label>Date</label>
<input type="date" id="date2" class="form-control" placeholder="" name="date2">
<br>
<p>
Note: If you are not the account holder please indicate the capacity in which you are signing the form. If signing under a power of attorney please
also attached a certified copy of the power of attorney.
</p>
<br>
<label>Capacity *</label>
<input type="text" id="cpcity" class="form-control" name="cpcity">
</div>



<div class="form-row" style="margin-bottom: 50px;">


<br>
<h2>DECLARATION</h2>
<br>
<label>1. I/We will not claim repatriation from Pakistan of Dividends and Sale proceeds of the units except as permissible under the Rules of the State Bank of Pakistan or Ministry of Finance, Government of Pakistan. </label>
<br>
<label>2. I/We hereby acknowledge having read and understood the relevant Trust Deed(s) and Offering Documents(s) and guidelines (on the back of this Form) that govern this transaction and further acknowledge having understood the risks involved and I/We agree to abide by the terms and conditions therein. </label>


<div class="col-md-12">
<div class="bridge"></div>
<!-- <button type="button" data-toggle="modal" data-target="#preview" id="checkout" class="btn btn-primary submit">Submit</button> -->
<button type="submit" class="btn btn-primary submit">Submit</button>
</div>
</div>

</form>
</div>

<footer>
<div class="col-sm-4 weblink">
<img src="{{asset('images/glob-icon.png')}}" alt="">
<p><a href="http://hblasset.com/" target="_blank">www.hblasset.com</a></p>

<p><a class="" href="{{url('/')}}">DashBoard</a></p>
<p><a class="" href="{{url('welcome')}}">Home</a></p>


</div>
<div class="col-sm-4 copyright"><p>©<?php echo date('Y');?> HBL Asset Management.</p></div>
<div class="col-sm-4 social">
<a href="#"><img src="{{asset('images/linkedin-icon.png')}}" alt=""></a>
<a href="#"><img src="{{asset('images/facebook-icon.png')}}" alt=""></a>
<a href="#"><img src="{{asset('images/twitter-icon.png')}}" alt=""></a>
<p> Follow Us On :</p>
<p>  <a href="{{ route('logout') }}"
onclick="event.preventDefault();
document.getElementById('logout-form').submit();">
<i class="fa fa-sign-out"></i>Logout &nbsp;</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
{{ csrf_field() }}
</form>
</p>
</div>
<div class="check"></div>
<div class="mop_check"></div>

</footer>
<script type="text/javascript" src="{{url('js/amount_to_word.js')}}"></script>
<script type="text/javascript" src="{{url('js/progress_bar.js')}}"></script>
<script type="text/javascript" src="{{url('js/functions.js')}}"></script>
<script type="text/javascript">

$(document).ready(function() {   

    $('.hide_class').hide();  

    $('.rc').on( "change", function() {
      
       var res_city= $('#mycities_resi').val();
        $('.resi_country').val('pk');
        $('.resi_city').val(res_city);
      show_hide($(this).val(),'pk','#show_resi_cities','#show_resi_cc');    
    });


    $('.pob_radio').on( "change", function() {


      if($(this).val() == 'pk'){
        var pob_city= $('#mycities').val();
        $('.pob_country').val('pk');
        $('.pob_city').val(pob_city);
      }

      show_hide($(this).val(),'pk','#show_pob_cities','#show_pob_city');    
    });

    $('.zak_ded').on( "change", function() {
      yes_no($(this).val(),'no','#zak_file');
    });
    $('.nat_radio').on( "change", function() {
      yes_no($(this).val(),'yes','#nats');
    });

    $('.card_radio').on( "change", function() {
    yes_no($(this).val(),'yes','#attorney');
    });

    $('.resi_in_us').on( "change", function() {
    yes_no($(this).val(),'yes','#attorney');
    });


    $('.w_form_my').change(function() {
    yes_no($(this).val(),'yes','#f_sub');
    });

    $('.tax_residency').change(function() {
    yes_no($(this).val(),'yes','.crf');
    });


    



    
    

var fund_titles = <?php echo json_encode($fund_titles);?>;
var fts = [];
for(var ft in fund_titles){
fts.push([fund_titles[ft].id,fund_titles[ft].title]);
}
  $.each(fts,function(key,value){
    $('.fund_name').append('<option value="'+value[0]+'">'+value[1]+'</option>');
  }); 
// if (funds_array != null) {
//   fts = fts.filter(ele => {
//   return !funds_ids.includes(""+ele[0]);
// });

//     var fa = JSON.parse(funds_array);
//     var i = 100;
//   for(f in fa){

//     i++;

//   $('.fund2bappend').append('<div class="form-row remove fund_row'+i+'"><div class="col-md-11"><label>Name of Fund</label><select style="height:auto;" name="funds[]" class="form-control funds'+i+' fund_name"><option value="'+fa[f][0]+'">'+fa[f][1]+'</option></select></div><div class="col-md-4"><label>Amount (Rs.)</label><input name="funds[]" value="'+fa[f][2]+'" class="form-control sum amount'+i+'"><p class="amount_msg'+i+'" style="color:red;padding:5px;"></p></div><div class="col-md-4"><label>Amount in Words</label><input name="funds[]" value="'+fa[f][3]+'" class="form-control word'+i+'" ></div><div class="col-md-1 form-inline" style="margin-top:4px;"><button type="button" value="'+i+'" class="btn btn-primary rem'+i+'">X</button></div><div class="col-md-5"><label>Payment Mode</label><select style="height: auto;"  name="funds[]" class="form-control" ><option value="ibft:0">IBFT</option><option value="Cheque:0">Cheque</option><option value="Pay Order:0">Pay Order</option><option value="Demand:0">Demand</option><option value="Draft:0">Draft</option><option value="Real Time Gross Settlement (RTGS):0">Real Time Gross Settlement (RTGS)</option></select></div><div class="col-md-6"><label>Attachments</label><input  name="mop_files[]" type="file" style="padding: 3px 5px" class="form-control"></div><div class="col-md-5"><br><label>Bank</label><input  name="funds[]" style="padding: 3px 5px" class="form-control"></div><div class="col-md-6"><br><label>Instrument Number</label><input  name="funds[]" style="padding: 3px 5px" class="form-control" ></div></div>');

//   delItem('.rem'+i,'.fund_row'+i);
//   toWords('amount' + i,'word' + i);

//   $('.amount'+i).keyup(function(){
//   toWords($('.amount').val(),'word'+i);    
//   });



//   $.each(fts,function(key,value){
//     if(value[0] != fa[f][0]){
//           $('.funds'+i).append('<option value="'+value[0]+'">'+value[1]+'</option>');
//     }

// });
// }    
// }
// else{



// }


var i = 0;

$('.add_ins').click(function(){
i++;
var html = '<br><div class="form-row remove fund_row'+i+'"><div class="col-md-5"><label>Amount (Rs.)</label><input type="hidden" value="1" name="sub_fields[]"><input name="funds[]" class="form-control sum amount'+i+'"><p class="amount_msg'+i+'" style="color:red;padding:5px;"></p></div><div class="col-md-6"><label>Amount in Words</label><input name="funds[]" class="form-control word'+i+'" ></div><div class="col-md-1"><button style="margin-top: 28%;" type="button" class="rem'+i+' btn btn-primary">X</button></div><div class="col-md-5"><label>Payment Mode</label><select style="height: auto;"  name="funds[]" class="form-control" ><option value="ibft">IBFT</option><option value="ch">Cheque</option><option value="po">Pay Order</option><option value="dem">Demand</option><option value="dra">Draft</option><option value="rtgs">Real Time Gross Settlement (RTGS)</option></select></div><div class="col-md-6"><label>Attachments</label><input  name="mop_file[]" type="file" style="padding: 3px 5px" class="form-control"></div><div class="col-md-5"><br><label>Bank</label><input  name="funds[]" style="padding: 3px 5px" class="form-control"></div><div class="col-md-6"><br><label>Instrument Number</label><input  name="funds[]" style="padding: 3px 5px" class="form-control" ></div></div></div>';
$('.fund2bappend').append(html);  
delItem('.rem'+i,'.fund_row'+i);
checkit('sum','amount_msg' + i);
toWords('amount' + i,'word' + i);
});
$('#add_fund').click(function(){
i++;
var html = '<br><div class="form-row remove fund_row'+i+'"><div class="col-md-12"><label>Name of Fund</label></div><div class="col-md-11"><select style="height:auto;" name="fund_name[]" class="form-control funds'+i+' fund_name"></select></div><div class="col-md-1"><button type="button" class="rem'+i+' btn btn-primary">X</button></div><div class="col-md-5"><label>Amount (Rs.)</label><input name="funds[]" class="form-control sum amount'+i+'"><p class="amount_msg'+i+'" style="color:red;padding:5px;"></p></div><div class="col-md-6"><label>Amount in Words</label><input name="funds[]" class="form-control word'+i+'" ></div><div class="col-md-5"><label>Payment Mode</label><select style="height: auto;"  name="funds[]" class="form-control" ><option value="ibft">IBFT</option><option value="ch">Cheque</option><option value="po">Pay Order</option><option value="dem">Demand</option><option value="dra">Draft</option><option value="rtgs">Real Time Gross Settlement (RTGS)</option></select></div><div class="col-md-6"><label>Attachments</label><input  name="mop_file[]" type="file" style="padding: 3px 5px" class="form-control"></div><div class="col-md-5"><br><label>Bank</label><input  name="funds[]" style="padding: 3px 5px" class="form-control"></div><div class="col-md-6"><br><label>Instrument Number</label><input  name="funds[]" style="padding: 3px 5px" class="form-control" ></div></div></div>';
$('.fund2bappend').append(html);

delItem('.rem'+i,'.fund_row'+i);
checkit('sum','amount_msg' + i);
toWords('amount' + i,'word' + i);

if(i == 1){
fts = $.grep(fts, function(n) {
return n[0] !== parseInt($('.fund_name').val());

});
}

$('.fund_name').trigger('select');
$('.fund_name').select(function(){
var current = $(this).val();
fts = $.grep(fts, function(n) {
return n[0] !== parseInt(current);
});

});

$.each(fts,function(key,value){
$('.funds' + i).append('<option value="'+value[0]+'">'+value[1]+'</option>');  
});

$('.rem'+i).click(function(){
localStorage.setItem('id',this.value);
var get_id = localStorage.getItem('id');
$('.check').trigger('click');

});
;



});

});



$('#name').val('FRANCIS');
$('#middle_name').val('AKHTER');
$('#last_name').val('GILL');
$('#father_name').val('EJAZ');
$('#mm_name').val('TEST');
$('#email').val('francisgill42@gmail.com');
$('#dob').val('2018-12-31');
$('#cnic').val(4210140790685);
$('#cell').val(03108559858);
$('#occupation').val('DEV');
$('#soi').val('SALARY');
$('#address').val('TESTING ADDRESS');



</script>

</body>
</html>
