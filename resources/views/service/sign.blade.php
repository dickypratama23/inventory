@extends('layouts.semantic')
@section('title', 'Sign Service Selesai')
@section('content')
<style>
.wrapper {
  position: relative;
  
  height: 300px;
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
img {
  position: absolute;
  left: 0;
  top: 0;
}

.signature-pad {
  position: absolute;
  left: 0;
  top: 0;
  width:400px;
  height:300px;
}

#imageid {
	display : none;
}
</style>

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Service</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Sign</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>DETAIL TRANSAKSI SERVICE</h2>
  </div>
  <div class="ui green segment">
   <div class="ui relaxed divided large list">
  <div class="item">
    <i class="file icon"></i>
    <div class="content">
      <a class="header">Invoice</a>
      <div class="description">{{ $transaksi->invoice }}</div>
    </div>
  </div>
  <div class="item">
    <i class="shopping cart icon"></i>
    <div class="content">
      <a class="header">Dari</a>
      <div class="description">{{ $transaksi->department->kdtk }} - {{ $transaksi->department->name }}</div>
    </div>
  </div>
  <div class="item">
    <i class="user icon"></i>
    <div class="content">
      <a class="header">PIC</a>
      <div class="description">{{ $transaksi->pic }}</div>
    </div>
  </div>
  <div class="item">
    <i class="map marker icon"></i>
    <div class="content">
      <a class="header">Keterangan</a>
      <div class="description">{{ $transaksi->note }}</div>
     </div>
   </div>

   <div class="item">
    <i class="map marker icon"></i>
    <div class="content">
      <a class="header">Barang masuk</a>
      <div class="description">{{ $transaksi->detail->count() }} Item</div>
     </div>
   </div>
  </div>
</div>


</div>

<div class="ui wrapper segment">
 <canvas id="signature-pad" class="signature-pad" width=400 height=300></canvas>
</div>

<form id="myForm" method="POST" action="{{ route('transin.signOK', $transaksi->id) }}">
 {{ csrf_field() }}
 <input name="imageData" id="imageData" type="hidden"/>
	
</form>

<button id="clear" class="ui fluid orange button">clear</button> <br>
<button id="save" class="ui fluid teal button">Simpan</button>

<br><br>



@endsection