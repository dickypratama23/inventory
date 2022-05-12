@extends('layouts.semantic')
@section('title', 'Trans Out GO')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Transaksi</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Grand Opening (GO)</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>TRANSAKSI OUT GO</h2>
  </div>
  <div class="ui green segment">
    <form class="ui form" action="{{ url('/GO') }}" method="POST">
     {{ csrf_field() }}

     <div class="field">
        <label>Di Tujukan Ke</label>
        <div class="ui fluid category search department">
          <div class="ui icon input">
            <input class="prompt" type="text" placeholder="Pilih Department / Toko">
            <i class="search icon"></i>
          </div>
          <div class="results"></div>
        </div>
        <input type="hidden" name="ke" class="dept" readonly>
      </div>

      <div class="field">
        <label>PIC</label>
        <input type="text" name="pic" placeholder="PIC">
      </div>

      <table class="ui celled striped table">
        <tbody>
         @foreach($GOs as $index => $go)
          @foreach($Stocks as $index => $stock)
           @if($go->barang_id == $stock->barang_id)
            @if($go->recid_sn == 1)
             @php $banyak = $go->qty_out; $out = 1; @endphp
            @else
             @php $banyak = 1; $out = $go->qty_out; @endphp
            @endif


            @for ($x = 1; $x <= $banyak; $x++)
            <tr class="@if($go->qty_out > $stock->total) negative @endif">
              <td class="collapsing">
               {{ $go->barang->name }}
               <input type="hidden" name="barang[]" value="{{ $go->barang->id }}">
              </td>
              <td class="right aligned collapsing">
               {{ $out }} of {{ $stock->total }} {{ $go->satuan }} 
               <input type="hidden" name="qty[]" value="{{ $out }}">
              </td>
              <td>
               @if($go->qty_out > $stock->total)
                @php $out_of_stock=1; @endphp
                <a class="ui red tag label">STOCK TIDAK CUKUP</a>
               @else
                @if($go->recid_sn == 1)
                 <input type="text" name="sn[]" placeholder="Serial Number / MAC Address" required>
                @else
                 <input type="hidden" name="sn[]" placeholder="Serial Number / MAC Address" readonly>
                @endif
               @endif
              </td>
            </tr>
            @endfor
            
           @endif
          @endforeach
         @endforeach
        </tbody>
        <tfoot class="full-width">
        <tr>
          <th colspan="8">
            <button class="ui positive right labeled icon button btnGo" type="submit"><i class="save icon"></i>Submit</button>
          </th>
        </tr>
      </tfoot>
      </table>
    </form>
  </div>
</div>

@endsection