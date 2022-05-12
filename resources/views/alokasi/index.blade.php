@extends('layouts.semantic')
@section('title', 'Laporan Transaksi Out')
@section('content')


<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Report</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Laporan Alokasi</div>
</div>

<div class="ui divider"></div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN ALOKASI</h2>
  </div>
  <div class="ui green segment">
    <table id="exssample" class="ui striped selectable celled table fold-table exportAlokasi" style="width:100%">
      <thead class="full-width">
        <tr>
            <th data-field="action">No.</th>
            <th data-field="item">Invoice</th>
            <th data-field="item">Ke</th>
            <th data-field="price">Item</th>
            <th data-field="price">Note</th>
            <th data-field="action">Action</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1 @endphp
        @forelse ($transaksi as $row)                    
          <tr class="view" style="cursor: pointer;">
            <td class="center aligned collapsing">{{ $no++ }}</td>
            <td class="collapsing">
                <ul class="ui list">
                    <li>Invoice : {{ substr($row->invoice, -10) }}</li>
                    <li>Tanggal : {{ $row->created_at->format('d/m/Y') }}</li>
                    <li>Jam : {{ $row->created_at->format('H:i:s') }}</li>
                </ul>
            </td>

            <td class="collapsing">
                <ul class="ui list">
                    <li>Ke : {{ $row->department->kdtk }}</li>
                    <li>PIC : {{ $row->pic }}</li>
                    <li>Pembuat : {{ $row->user->nik }} | {{ $row->user->name }}</li>
                    <li>Approve : @foreach ($row->detail as $detail) @endforeach {{ $detail->user->nik }} | {{ $detail->user->name }}</li>
                </ul>
            </td>

            <td class="collapsing">
                <ol class="ui list">
                    @foreach ($row->detail as $detail)
                      <li value="*">
                        {{ $detail->barang->name }} ({{ $detail->qty }} {{ $detail->barang->satuan}})
                        @if($detail->Serial_number != '')
                          <ol>
                            <li value="-">S/N : <strong>{{ $detail->Serial_number }}</strong></li>
                          </ol>
                        @endif
                      </li>
                    @endforeach
                </ol>
            </td>

            <td>{{ $row->note }}</td>

            <td class="center aligned collapsing">
                @if($row->sign == '')
                  <a href="{{ route('transout.sign', $row->id) }}" class="ui teal icon button">
                    <i class="icons">
                      <i class="pencil alternate icon"></i>
                      <i class="file alternate outline icon"></i>
                    </i>
                  </a>
                @else
                  @if($row->status == 2)
                  <a href="{{ route('transout.print', $row->id) }}" target="_blank" class="ui teal icon button">
                      <i class="print icon"></i>
                  </a>
                  @endif
                @endif
            </td>

          </tr>
          
        @empty
          <tr>
            <td class="center aligned" colspan="9">Tidak ada data</td>
          </tr>
        @endforelse
        
      </tbody>
      
    </table>
    
  </div>
</div>















@endsection