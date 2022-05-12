@extends('layouts.semantic')
@section('title', 'Laporan Service')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Service</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Laporan Service</div>
</div>

<div class="ui divider"></div>

<div class="ui placeholder segment">

  <form class="ui form">
    <h4 class="ui dividing header">Filter (IN PROGRESS)</h4>
    <div class="fields">
      <div class="five wide field">
        <label>Periode</label>
        <div class="ui left icon input">
          <input type="text" placeholder="Pilih periode..." name="filter[periode]" data-plugin-datepicker
            class="datepicker_periode">
          <i class="calendar icon"></i>
        </div>
      </div>
    </div>

    <div class="ui button" tabindex="0">Filter</div>
  </form>
</div>


















<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN SERVICE</h2>
  </div>
  <div class="ui green segment">
    <table id="exampddle" class="ui striped selectable celled table fold-table exportServices" style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">No</th>
          <th data-field="item">Barang</th>

          <th data-field="item">Toko</th>
          <th data-field="price">Penggantian</th>
          <th data-field="price">Masuk</th>
          <th data-field="price">Selesai</th>
          <th data-field="price">Lama Service</th>
          <th data-field="price">Ambil</th>
          <th data-field="price">Action</th>
        </tr>
      </thead>
      <tbody>

        @php $no = 1 @endphp
        @forelse ($service as $row)
        @foreach ($service_in as $row_in)
        @if($row_in->invoice == $row->inv_relation)
        <tr class="view" style="cursor: pointer;">
          <td class="center aligned collapsing">{{ $no++ }}</>

          <td class="collapsing">
            <div class="ui list">
              <div class="item">
                <div class="header">
                  {{ $row->barang->name }}
                  @foreach ($row_in->detail as $detail)
                  @if($detail->barang_id == $row->barang_id)
                  @if($detail->cads == 1)
                  <div class="ui blue horizontal label">Cad</div>
                  @endif
                  @if($detail->ho == 2)
                  <div class="ui yellow horizontal label"> HO </div>
                  @endif
                  @endif
                  @endforeach
                  {{ $row->id }}
                </div>
                @if($row->department->kdtk == 'EDP')
                S/N :
                @foreach ($row_in->detail as $detail)
                @if($detail->barang_id == $row->barang_id)
                {{ $detail->Serial_number }}
                <!--({{ $detail->note }})--> </br>
                S/N Assembly: {{ $detail->sn_assembly }}
                @endif
                @endforeach
                @else
                S/N :
                @foreach ($row_in->detail as $detail)
                @if($detail->barang_id == $row->barang_id)
                {{ $detail->Serial_number }}
                <!--({{ $detail->note }})--></br>
                S/N Assembly: {{ $detail->sn_assembly }}
                @endif
                @endforeach
                @endif

                <!--<br>
				Pembawa : {{ $row->pic }}-->

              </div>
            </div>
          </td>

          <td class="collapsing">
            {{ $row->department->kdtk }}
          </td>

          <td class="">
            @if($row->detail->count() > 0)
            @foreach ($row->detail as $detail)
            <ul class="ui list">
              <li>
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
                {{ $detail->barang->name }} ({{ $detail->qty }}X) {{ $detail->id }}
              </li>
            </ul>
            @endforeach
            @endif
          </td>

          <td class="center aligned collapsing">
            {{ $row_in->created_at->format('d-m-Y') }} <br>
            ({{ $row_in->created_at->diffForHumans() }})
          </td>
          <td class="center aligned collapsing">
            {{ $row->created_at->format('d-m-Y') }} <br>
            ({{ $row->created_at->diffForHumans() }})
          </td>
          <td class="center aligned collapsing">
            {{ str_replace('after', '', $row->created_at->diffForHumans($row_in->created_at)) }}</td>
          <td class="center aligned collapsing">
            {{ $row->updated_at->format('d-m-Y') }} <br>
            ({{ $row->updated_at->diffForHumans() }})
          </td>

          <td class="center aligned collapsing">
            @if($row->pic == 'NIK - NAMA PERSONIL TOKO' )
            <a href="{{ route('service.ambil') }}" target="_blank" class="ui red icon button">
              <i class="dolly icon"></i>
            </a>
            @else
            @if($row->sign == '' )
            <a href="{{ route('service.sign', $row->id) }}" class="ui blue icon button">
              <i class="icons">
                <i class="pencil alternate icon"></i>
                <i class="file alternate outline icon"></i>
              </i>
            </a>
            @else
            <a href="{{ route('service.print', $row->id) }}" target="_blank" class="ui teal icon button">
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
        @endif
        @endforeach
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
    <form action="{{ route('service.upload') }}" method="POST" enctype="multipart/form-data">
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