@extends('layouts.admin-app')

@section('content')
<!-- main content start-->
<div id="page-wrapper">
<div class="main-page">



<div class="tables">
<div class="panel-body widget-shadow">
<h4>All Funds</h4>
<table class="table">
<thead>
<tr>
<th>Title</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php foreach($all as $al){?>
<?php if($al->id == 23 || $al->id == 24 || $al->id == 25 || $al->id == 26){?>
<tr>
<td><?php echo $al->title?></td>
<td>
<a href="{{url('add_fund1_data')}}/<?php echo $al->id?>">Edit Data</a>
</td>
</tr>
<?php }
 else {
?>
<tr>
<td><?php echo $al->title?></td>
<td>
<a href="{{url('add_fund_data')}}/<?php echo $al->id?>">Edit Data</a>
</td>
</tr>

<?php }}?>


</tbody>
</table>
{{$all->links()}}

</div>

</div>

<div class="clearfix"> </div>


</div>
</div>

@endsection
