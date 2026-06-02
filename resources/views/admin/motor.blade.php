@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/motor.css') }}">
@endpush

@section('content')

<div class="flex-1 min-h-0 flex flex-col">
  {{-- Info Banner --}}
  <div class="info-banner flex-shrink-0">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z" />
    </svg>
    Data motor di bawah bersumber dari <strong>log riwayat mekanik</strong>. Untuk menambah / mengubah data, gunakan
    <a href="{{ route('mekanik.riwayat') }}" class="underline ml-1">Dashboard Mekanik</a>.
  </div>

  {{-- Box Tabel --}}
  <div class="rounded-2xl overflow-hidden shadow-sm flex-1 min-h-0 flex flex-col bg-white">
    
    <div class="table-header px-5 py-3 flex-shrink-0">
      <span class="font-bold text-sm">Data Motor</span>
    </div>

    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0">
      @if($motors->isEmpty())
        <x-shared.empty-state />
      @else
        <table class="data-motor-table w-full text-sm border-collapse">
          <thead class="sticky top-0 bg-[#f0f1fa] z-10 shadow-[inset_0_-1px_0_rgba(0,0,0,0.05)]">
            <tr>
              <th style="width: 70px;">No</th>
              <th>Nama Pelanggan</th>
              <th>Nomor Polisi</th>
              <th>Kerusakan</th>
              <th style="width: 140px;">Status</th>
            </tr>
          </thead>

          <tbody>
            @foreach($motors as $item)
              <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                <td class="font-semibold text-gray-500">{{ $loop->iteration }}</td>

                <td class="font-semibold text-gray-800">
                  {{ $item->nama_pelanggan ?? '-' }}
                </td>

                <td class="font-mono text-gray-700">
                  {{ $item->nomor_polisi ?? '-' }}
                </td>

                <td class="text-gray-700">
                  {{ $item->nama_kerusakan ?? '-' }}
                </td>

                <td>
                  @if($item->status === 'pending')
                    <span class="badge-pending">Pending</span>
                  @elseif($item->status === 'proses')
                    <span class="badge-proses">Proses</span>
                  @elseif($item->status === 'selesai')
                    <span class="badge-selesai">Selesai</span>
                  @else
                    <span class="badge-pending">{{ ucfirst($item->status ?? 'Pending') }}</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>

</div>

@endsection