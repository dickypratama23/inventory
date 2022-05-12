@extends('layouts.semantic')
@section('title', 'Laporan Permutasi Persediaan')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">LPP</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Buat Permintaan (Cabang & Ho)</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>Buat Permintaan (Cabang)</h2>
  </div>
  <div class="ui green segment">
    <table id="exampless" class="ui celled table responsive nowrap unstackable permintaan_cabang" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="item">Barang</th>
          <th data-field="item">Acost</th>
          <th data-field="item">Jenis</th>
          <th data-field="item">Spd</th>
          <th data-field="item">Avg</th>
          <th data-field="item">Buffer</th>
          <th data-field="item">Max</th>
          <th data-field="price">Reorder</th>
          <th data-field="price">Saldo</th>
          <th data-field="price">Ft</th>
          <th data-field="price">Proses GA</th>
          <th data-field="price">Target</th>
          <th class="center aligned" data-field="price"><i class="minus icon"></i></th>
          <th data-field="price">Rupiah</th>
        </tr>
      </thead>
      <tbody>
        @php $total_ga = 0; @endphp
        @foreach($cabang as $index => $barang)
        @php $total_ga += $barang->barang->acost * $barang->kurang; @endphp
        <tr>
          <td>{{ $barang->barang->name }}</td>
          <td class="right aligned collapsing">{{ number_format($barang->barang->acost,2) }}</td>
          <td class="collapsing">{{ $barang->jenis }}</td>
          <td class="right aligned collapsing">{{ $barang->ttl1 }}</td>
          <td class="right aligned collapsing">{{ $barang->avg }}</td>
          <td class="right aligned collapsing">{{ $barang->buffer }}</td>
          <td class="right aligned collapsing">{{ $barang->max }}</td>
          <td class="right aligned collapsing">{{ $barang->min }}</td>
          <td class="right aligned collapsing">{{ $barang->saldo }}</td>
          <td class="right aligned collapsing">{{ $barang->ft }}</td>
          <td class="right aligned collapsing">{{ $barang->belum }}</td>
          <td class="right aligned collapsing">{{ $barang->minta }}</td>
          <td class="right aligned positive collapsing">
            {{ str_replace('-','',$barang->kurang) }}
          </td>
          <td class="right aligned collapsing">{{ number_format($barang->barang->acost * $barang->kurang,2) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot class="full-width">
        <tr>
          <th colspan="14">
            {{-- <a href="{{ route('lpp.permintaan.proses', 'cabang') }}" class="ui teal small button">
            Proses GA ( Rp. {{ number_format($total_ga,2) }})
            </a> --}}
          </th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>Buat Permintaan (HO)</h2>
  </div>
  <div class="ui green segment">
    <table id="exampless" class="ui celled table responsive nowrap unstackable permintaan_ho" style="width:100%">
      <thead class="full-width">
        <tr>

          <th data-field="item">Barang</th>
          <th data-field="item">Acost</th>
          <th data-field="item">Jenis</th>
          <th data-field="item">Spd</th>
          <th data-field="item">Avg</th>
          <th data-field="item">Buffer</th>
          <th data-field="item">Max</th>
          <th data-field="price">Reorder</th>
          <th data-field="price">Saldo</th>
          <th data-field="price">Ft</th>
          <th data-field="price">P. HO</th>
          <th data-field="price">Target</th>
          <th class="center aligned" data-field="price"><i class="minus icon"></i></th>
          <th data-field="price">Rupiah</th>
        </tr>
      </thead>
      <tbody>
        @php $total_ho = 0; @endphp
        @foreach($ho as $index => $barang)
        @php $total_ho += $barang->barang->acost * $barang->kurang; @endphp
        <tr>
          <td>{{ $barang->barang->name }}</td>
          <td class="right aligned collapsing">{{ number_format($barang->barang->acost,2) }}</td>
          <td class="collapsing">{{ $barang->jenis }}</td>
          <td class="right aligned collapsing">{{ $barang->ttl1 }}</td>
          <td class="right aligned collapsing">{{ $barang->avg }}</td>
          <td class="right aligned collapsing">{{ $barang->buffer }}</td>
          <td class="right aligned collapsing">{{ $barang->max }}</td>
          <td class="right aligned collapsing">{{ $barang->min }}</td>
          <td class="right aligned collapsing">{{ $barang->saldo }}</td>
          <td class="right aligned collapsing">{{ $barang->ft }}</td>
          <td class="right aligned collapsing">{{ $barang->belum }}</td>
          <td class="right aligned collapsing">{{ $barang->minta }}</td>
          <td class="right aligned positive collapsing">
            {{ $barang->kurang }}
          </td>
          <td class="right aligned collapsing">{{ number_format($barang->barang->acost * $barang->kurang,2) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot class="full-width">
        <tr>
          <th colspan="14">
            {{-- <a href="{{ route('lpp.permintaan.proses', 'HO') }}" class="ui teal small button">
            Proses HO ( Rp. {{ number_format($total_ho,2) }})
            </a> --}}
          </th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<br>

@endsection