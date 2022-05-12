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

  <form class="ui form" action="{{ route('service.report2.filter') }}" method="POST">
    <h4 class="ui dividing header">Filter</h4>
    <div class="fields">
      <div class="five wide field">
        <label>Periode</label>
        <div class="ui left icon input">
          <input type="text" placeholder="Pilih periode..." name="filter[periode]" data-plugin-datepicker
            class="datepicker_periode" value="{{ $PERIODE_FIL ?? date('Y-m') }}">
          <i class="calendar icon"></i>
        </div>
      </div>
    </div>

    <button type="submit" class="ui button" tabindex="0">Filter</button>
  </form>
</div>


<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN SERVICE (MASUK)</h2>
  </div>
  <div class="ui green segment">
    <table id="exampddle" class="ui striped selectable celled table fold-table nowrap exportServices"
      style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">No</th>
          <th data-field="item">Barang</th>

          <th data-field="item">Toko</th>
          <th data-field="price">Detail</th>
          <th data-field="price">Masuk</th>
          <th data-field="price">Selesai</th>
          <th data-field="price">Ambil</th>
          <th data-field="price">Action</th>
        </tr>
      </thead>
      <tbody>

        @foreach ($data as $index => $row)
        <tr>
          <td>{{ ++$index }}</td>
          <td>
            Barang : {{ $row['barang'] }} <br>
            SN : {{ $row['sn'] }} <br>
            Invoice In : {{ $row['invoice'] }} <br>
            Invoice Out : {{ $row['invoice2'] }}
          </td>
          <td>{{ $row['dept'] }}</td>
          <td>
            Kerusakan : {{ $row['kerusakan'] }} <br><br>

            Penggantian : <br>
            @foreach ($row['penggantian'] as $ganti)
            {{ $ganti['spart'] }} ({{ $ganti['qty'] }} Pcs) <br>
            @endforeach
          </td>
          <td>{{ $row['masuk'] }}</td>
          <td>{{ $row['selesai'] }}</td>
          <td>{{ $row['keluar'] }}</td>
          <td>
            <a href="{{ route('service.print', $row['id']) }}" target="_blank" class="ui teal icon button">
              <i class="print icon"></i>
            </a>

            @if($row['lampiran'])
            <a href="{{ url('storage/photo_sj/' . $row['lampiran']) }}" target="_blank" class="ui grey icon button">
              <i class="paperclip icon"></i>
            </a>
            @else
            <button class="ui violet icon button upload_sj" data-id-transaksi="{{ $row['id'] }}">
              <i class="upload icon"></i>
            </button>
            @endif
          </td>
        </tr>
        @endforeach



      </tbody>

    </table>
  </div>
</div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN SERVICE (SELESAI)</h2>
  </div>
  <div class="ui green segment">
    <table id="exampddle" class="ui striped selectable celled table fold-table nowrap exportServices"
      style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">No</th>
          <th data-field="item">Barang</th>

          <th data-field="item">Toko</th>
          <th data-field="price">Detail</th>
          <th data-field="price">Masuk</th>
          <th data-field="price">Selesai</th>
          <th data-field="price">Ambil</th>
          <th data-field="price">Action</th>
        </tr>
      </thead>
      <tbody>

        @foreach ($data2 as $index => $row)
        <tr>
          <td>{{ ++$index }}</td>
          <td>
            Barang : {{ $row['barang'] }} <br>
            SN : {{ $row['sn'] }} <br>
            Invoice In : {{ $row['invoice'] }} <br>
            Invoice Out : {{ $row['invoice2'] }}
          </td>
          <td>{{ $row['dept'] }}</td>
          <td>
            Kerusakan : {{ $row['kerusakan'] }} <br><br>

            Penggantian : <br>
            @foreach ($row['penggantian'] as $ganti)
            {{ $ganti['spart'] }} ({{ $ganti['qty'] }} Pcs) <br>
            @endforeach
          </td>
          <td>{{ $row['masuk'] }}</td>
          <td>{{ $row['selesai'] }}</td>
          <td>{{ $row['keluar'] }}</td>
          <td>
            <a href="{{ route('service.print', $row['id']) }}" target="_blank" class="ui teal icon button">
              <i class="print icon"></i>
            </a>
            @if($row['lampiran'])
            <a href="{{ url('storage/photo_sj/' . $row['lampiran']) }}" target="_blank" class="ui grey icon button">
              <i class="paperclip icon"></i>
            </a>
            @else
            <button class="ui violet icon button upload_sj" data-id-transaksi="{{ $row['id'] }}">
              <i class="upload icon"></i>
            </button>
            @endif
          </td>
        </tr>
        @endforeach



      </tbody>

    </table>
  </div>
</div>

<div class="ui stacked segments">
  <div class="ui right aligned segment">
    <h2>LAPORAN SERVICE (AMBIL)</h2>
  </div>
  <div class="ui green segment">
    <table id="exampddle" class="ui striped selectable celled table fold-table nowrap exportServices"
      style="width:100%">
      <thead class="full-width">
        <tr>
          <th data-field="no">No</th>
          <th data-field="item">Barang</th>

          <th data-field="item">Toko</th>
          <th data-field="price">Detail</th>
          <th data-field="price">Masuk</th>
          <th data-field="price">Selesai</th>
          <th data-field="price">Ambil</th>
          <th data-field="price">Action</th>
        </tr>
      </thead>
      <tbody>

        @foreach ($data3 as $index => $row)
        <tr>
          <td>{{ ++$index }}</td>
          <td>
            Barang : {{ $row['barang'] }} <br>
            SN : {{ $row['sn'] }} <br>
            Invoice In : {{ $row['invoice'] }} <br>
            Invoice Out : {{ $row['invoice2'] }}
          </td>
          <td>{{ $row['dept'] }}</td>
          <td>
            Kerusakan : {{ $row['kerusakan'] }} <br><br>

            Penggantian : <br>
            @foreach ($row['penggantian'] as $ganti)
            {{ $ganti['spart'] }} ({{ $ganti['qty'] }} Pcs) <br>
            @endforeach
          </td>
          <td>{{ $row['masuk'] }}</td>
          <td>{{ $row['selesai'] }}</td>
          <td>{{ $row['keluar'] }}</td>
          <td>
            <a href="{{ route('service.print', $row['id']) }}" target="_blank" class="ui teal icon button">
              <i class="print icon"></i>
            </a>
            @if($row['lampiran'])
            <a href="{{ url('storage/photo_sj/' . $row['lampiran']) }}" target="_blank" class="ui grey icon button">
              <i class="paperclip icon"></i>
            </a>
            @else
            <button class="ui violet icon button upload_sj" data-id-transaksi="{{ $row['id'] }}">
              <i class="upload icon"></i>
            </button>
            @endif
          </td>
        </tr>
        @endforeach



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