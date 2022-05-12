@extends('layouts.semantic')
@section('title', 'Histori')
@section('content')

<div class="ui breadcrumb">
  <a class="section">Home</a>
  <i class="right angle icon divider"></i>
  <a class="section">Report</a>
  <i class="right angle icon divider"></i>
  <div class="active section">Histori</div>
</div>

<div class="ui divider"></div>

<div class="ui grid">
  <div class="thirteen wide column">
    <div class="ui stacked segments">
      <div class="ui right aligned segment">
        <h2>HISTORI</h2>
      </div>
      <div class="ui green segment">
        <table id="example" class="ui striped selectable celled table fold-table nowrap scroll-horizontal"
          style="width:100%">
          <thead class="full-width">
            <tr>
              <th data-field="no">No</th>
              <th data-field="no">Tipe</th>
              <th data-field="item">Barang</th>
              <th style="display: {{ $tipe == 'service' ? '' : 'none' }}" data-field="item">Serial Number / MAC
              </th>
              <th style="display: {{ $tipe == 'service' ? '' : 'none' }}" data-field="item">Kerusakan</th>
              <th data-field="item">Qty</th>
              <th data-field="item">Toko</th>
              <th data-field="price">Masuk</th>
              <th data-field="price">Selesai</th>
              <th data-field="price">Keluar</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($r_data as $index => $row)
            <tr>
              <td>{{ ++$index }}</td>
              <td>{{ strtoupper($tipe) }} </td>
              <td>{{ $row['barang'] }}</td>
              <td style="display: {{ $tipe == 'service' ? '' : 'none' }}">{{ $row['sn'] }}</td>
              <td style="display: {{ $tipe == 'service' ? '' : 'none' }}">{{ $row['kerusakan'] }}</td>
              <td>{{ $row['qty'] }}</td>
              <td>{{ $row['department'] }}</td>
              <td>{{ $row['masuk'] }}</td>
              <td>{{ $row['selesai'] }}</td>
              <td>{{ $row['keluar'] }}</td>
            </tr>
            @endforeach

          </tbody>

        </table>
      </div>
    </div>
  </div>

  <div class="three wide column">
    <div class="ui tiny header">Filter</div>
    <div class="ui divider"></div>

    <form class="ui form" action="{{ route('histori_filter') }}" method="POST">
      {{ csrf_field() }}
      <div class="field">
        <select class="ui fluid dropdown" name="tipe">
          <option value="">Pilih Tipe</option>
          <option value="masuk" {{ $tipe=='masuk' ? 'selected' : '' }}>MASUK</option>
          <option value="keluar" {{ $tipe=='keluar' ? 'selected' : '' }}>KELUAR</option>
          <option value="service" {{ $tipe=='service' ? 'selected' : '' }}>SERVICE</option>
        </select>
      </div>

      <div class="field">
        <div class="ui left aligned category search barang">
          <div class="ui icon input">
            <input class="prompt" type="text" placeholder="Pilih Barang">
          </div>
          <div class="results"></div>
        </div>
        <input type="hidden" name="id_barang" class="barang_id" readonly>
      </div>

      <button class="ui primary fluid button" type="submit">Filter</button>
    </form>



  </div>
</div>

<script>
  window.addEventListener('load', function () {
    
  $('.scroll-horizontal').DataTable({
      "scrollX": true,
      //"order": [1],
    });

  });
</script>

@endsection