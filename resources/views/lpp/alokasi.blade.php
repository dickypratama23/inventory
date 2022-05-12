@extends('layouts.semantic')
@section('title', 'Laporan Permutasi Persediaan')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Report</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Laporan Permutasi Persediaan Alokasi</div>
</div>

<div class="ui divider"></div>

@forelse($users as $index => $user)
<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LPP {{ $user->name }}</h2>
  </div>
  <div class="ui green segment">
    <table class="ui celled table responsive nowrap unstackable example" style="width:100%">
      <thead class="full-width">
        <tr>
            {{-- <th data-field="no">No</th> --}}
            <th data-field="item">Kode Barang</th>
            <th data-field="item">Barang</th>
            <th data-field="item">In</th>
            <th data-field="price">Out</th>
            <th data-field="price">BAP</th>
            <th data-field="price">ADJ</th>
            <th data-field="price">Total</th>
        </tr>
      </thead>
      <tbody>
        @forelse($lpps as $index => $barang)
          @if($user->nik == $barang->user->nik)
        <tr>
            <td class="mob_icons collapsing"><i class="plus circle green icon  mob_icon"></i> {{ $barang->barang->kode }}</td>
            <td>{{ $barang->barang->name }}</td>
            <td class="right aligned collapsing">{{ $barang->in }}</td>
            <td class="right aligned collapsing">{{ $barang->out }}</td>
            <td class="right aligned collapsing">{{ $barang->bap }}</td>
            <td class="right aligned collapsing">{{ $barang->adj }}</td>
            <td class="right aligned collapsing">{{ $barang->in - $barang->out - $barang->bap + $barang->adj }}</td>
        </tr>
          @endif
        @empty
        <tr>
            <td class="center-align" colspan="5">Tidak ada data</td>
        </tr>
        @endforelse
      </tbody>
      
    </table>
  </div>
</div>
<br>
@empty
@endforelse




@endsection