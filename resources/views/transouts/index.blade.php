@extends('layouts.semantic')
@section('title', 'Laporan Transaksi Out')
@section('content')


<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Report</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Laporan Transaksi Out</div>
</div>

<div class="ui divider"></div>

<div class="ui placeholder segment">

  <form class="ui form" action="{{ route('transout.filter') }}">
    <h4 class="ui dividing header">Filter</h4>
    <div class="fields">

      <div class="three wide field">
        <label>Periode</label>
        <div class="ui left icon input">
          <input type="text" value="{{ $PERIODE_FIL }}" placeholder="Pilih periode..." name="filter[periode]"
            data-plugin-datepicker class="datepicker_periode">
          <i class="calendar icon"></i>
        </div>
      </div>

      <div class="five wide field">
        <label>Dari</label>
        <div class="ui fluid category search department">
          <div class="ui icon input">
            <input class="prompt" type="text" placeholder="Pilih Department / Toko / Supplier">
            <i class="search icon"></i>
          </div>
          <div class="results"></div>
        </div>
        <input type="hidden" name="filter[ke]" class="dept" readonly>
      </div>

      <div class="five wide field">
        <label>Barang</label>
        <div class="ui fluid category search barang">
          <div class="ui icon input">
            <input class="prompt" type="text" placeholder="Pilih Barang">
            <i class="search icon"></i>
          </div>
          <div class="results"></div>
        </div>
        <input type="hidden" name="_method" value="PUT" class="form-control">
        <input type="hidden" name="filter[barang]" class="barang_id" readonly>
      </div>

    </div>

    <button type="submit" class="ui button" tabindex="0">Filter</button>
  </form>
</div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN TRANSAKSI OUT</h2>
  </div>
  <div class="ui green segment">
    <table id="exsssample" class="ui striped selectable celled table fold-table exportOuts" style="width:100%">
      <thead class="full-width">
        <tr>
          {{-- <th data-field="no" class="one wide">Status</th> --}}
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
        @foreach ($transaksi as $row)
        <tr class="view" style="cursor: pointer;">
          {{-- <td class="collapsing"><a
              class="ui @if($row->status == 9) red @elseif($row->status == 2) green @else orange @endif ribbon label">@if($row->status
              == 9) Rejected @elseif($row->status == 2) Approved @else Pending @endif</a></> --}}
          <td class="center aligned collapsing">{{ $no++ }}</td>
          <td class="collapsing">
            <ul class="ui list">
              <li>Invoice : {{ substr($row->invoice, -10) }}</li>
              <li>Tanggal : {{ $row->created_at->format('d/m/Y') }}</li>
              <li>Jam : {{ $row->created_at->format('H:i:s') }}</li>
              <li>Jenis : @if(substr($row->invoice, 0, 3) == "ALO") <strong>ALOKASI</strong> @else <strong>OUT
                  BIASA</strong> @endif</li>
            </ul>
          </td>

          <td class="collapsing">
            <ul class="ui list">
              <li>Ke : {{ $row->department->kdtk }}</li>
              <li>PIC : {{ $row->pic }}</li>
              <li>Pembuat : {{ $row->user->nik }} | {{ $row->user->name }}</li>
              <li>Approve : @foreach ($row->detail as $detail) @endforeach {{ $detail->user->nik }} |
                {{ $detail->user->name }}</li>
            </ul>
          </td>

          <td class="collapsing">
            <ol class="ui list">
              @foreach ($row->detail as $detail)
              <li value="*">
                @if(Session('nik')==2013122105 && $detail->to_assembly == 0)
                <a href="{{ route('transout.assembly', $detail->id) }}" class="item">
                  <i class="clipboard check icon"></i>
                </a>
                @else
                (A)
                @endif
                @if(Session('nik')==2013122105 && $detail->to_gl == 0)
                <a href="{{ route('transout.gl', $detail->id) }}" class="item">
                  <i class="balance scale icon"></i>
                </a>
                @else
                (G)
                @endif
                {{ $detail->barang->name }} ({{ $detail->qty }}
                {{ $detail->barang->satuan}})
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

            @if($row->lampiran)
            <a href="{{ url('storage/photo_sj/' . $row->lampiran) }}" target="_blank" class="ui grey icon button">
              <i class="paperclip icon"></i>
            </a>
            @else
            <button class="ui violet icon button upload_sj" data-id-transaksi="{{ $row->id }}">
              <i class="upload icon"></i>
            </button>
            @endif
            @endif
            @endif
          </td>
        </tr>

        @endforeach

      </tbody>

    </table>
    Note:
    (A) = Assembly,
    (G) = GL
  </div>
</div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN TRANSAKSI OUT OPR</h2>
  </div>
  <div class="ui green segment">
    <table id="" class="ui striped selectable celled table fold-table exportOuts" style="width:100%">
      <thead class="full-width">
        <tr>
          {{-- <th data-field="no" class="one wide">Status</th> --}}
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
        @forelse ($transaksiOPR as $row)
        <tr class="view" style="cursor: pointer;">
          {{-- <td class="collapsing"><a
              class="ui @if($row->status == 9) red @elseif($row->status == 2) green @else orange @endif ribbon label">@if($row->status
              == 9) Rejected @elseif($row->status == 2) Approved @else Pending @endif</a></> --}}
          <td class="center aligned collapsing">{{ $no++ }}</td>
          <td class="collapsing">
            <ul class="ui list">
              <li>Invoice : {{ substr($row->invoice, -10) }}</li>
              <li>Tanggal : {{ $row->created_at->format('d/m/Y') }}</li>
              <li>Jam : {{ $row->created_at->format('H:i:s') }}</li>
              <li>Jenis : @if(substr($row->invoice, 0, 3) == "ALO") <strong>ALOKASI</strong> @else <strong>OUT
                  BIASA</strong> @endif</li>
            </ul>
          </td>

          <td class="collapsing">
            <ul class="ui list">
              <li>Ke : {{ $row->department->kdtk }}</li>
              <li>PIC : {{ $row->pic }}</li>
              <li>Pembuat : {{ $row->user->nik }} | {{ $row->user->name }}</li>
              {{-- <li>Approve : @foreach ($row->detail as $detail) @endforeach {{ $detail->user->nik }} |
                {{ $detail->user->name }}</li> --}}
            </ul>
          </td>

          <td class="collapsing">
            <ol class="ui list">
              @foreach ($row->detail as $detail)
              <li value="*">
                @if(Session('nik')==2013122105 && $detail->to_assembly == 0)
                <a href="{{ route('transout.assembly', $detail->id) }}" class="item">
                  <i class="clipboard check icon"></i>
                </a>
                @else
                (A)
                @endif
                @if(Session('nik')==2013122105 && $detail->to_gl == 0)
                <a href="{{ route('transout.gl', $detail->id) }}" class="item">
                  <i class="balance scale icon"></i>
                </a>
                @else
                (G)
                @endif
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




<div class="ui mini modal">
  <i class="close icon"></i>
  <div class="header">
    Pilih Scan Surat Jalan Untuk Di Upload
  </div>
  <div class="content">
    <form action="{{ route('transout.upload') }}" method="POST" enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="description">

        <div class="twelve wide field">
          <div class="ui action input">
            <input type="hidden" id="id-transaksi" name="id_transaksi">
            <input type="text" id="_attachmentName">
            <label for="attachmentName" class="ui icon button btn-file">
              <i class="upload icon"></i>
              <input type="file" id="attachmentName" name="attachmentName" style="display: none">
            </label>
          </div>
        </div>

      </div>
  </div>

  <div class="actions">
    <div class="ui black deny button">
      Keluar
    </div>
    <button class="ui positive right labeled icon button">
      Upload
      <i class="checkmark icon"></i>
    </button>
    </form>
  </div>
</div>









@endsection