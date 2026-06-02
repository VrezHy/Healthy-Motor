@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')

@if(session('success'))
<div class="alert-success">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
  </svg>
  {{ session('success') }}
</div>
@endif

<div class="mb-3">
  <x-shared.button id="btnAddOpen" title="Tambah Data Solusi" variant="primary" >+</x-shared.button>
  <!-- <button class="btn-add" id="btnAddOpen" title="Tambah Data Solusi">+</button> -->
</div>

<div class="rounded-2xl overflow-hidden shadow-sm">
  <div class="table-header px-5 py-3">
    <span class="font-bold text-sm">Tabel Data Solusi</span>
  </div>
  <div class="bg-white overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="background:#f0f1fa;">
          <th class="text-left px-5 py-3 font-bold text-gray-700 w-12">No</th>
          <th class="text-left px-5 py-3 font-bold text-gray-700">Nama Solusi</th>
          <th class="text-left px-5 py-3 font-bold text-gray-700">Deskripsi</th>
          <th class="text-left px-5 py-3 font-bold text-gray-700">Kerusakan</th>
          <th class="text-right px-5 py-3 font-bold text-gray-700 w-44">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($solusies as $item)
        <tr>
          <td class="px-5 py-3 font-semibold text-gray-700">{{ $loop->iteration }}</td>
          <td class="px-5 py-3 text-gray-700 font-semibold">{{ $item->nama_solusi }}</td>
          <td class="px-5 py-3 text-gray-500 text-xs">{{ Str::limit($item->deskripsi, 60) ?? '-' }}</td>
          <td class="px-5 py-3">
            <span class="badge-kerusakan">{{ $item->kerusakan->nama_kerusakan ?? '-' }}</span>
          </td>
          <td class="px-5 py-3 text-right flex items-end shrink-0justify-center flex-col gap-2">
            <form action="{{ route('admin.solusi.destroy', $item->id) }}" method="POST"
              class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-hapus">Hapus</button>
            </form>
            <button type="button" class="btn-ubah"
              data-id="{{ $item->id }}"
              data-nama="{{ $item->nama_solusi }}"
              data-deskripsi="{{ $item->deskripsi }}"
              data-kerusakan="{{ $item->kerusakan_id }}"
              onclick="openEditModal(this.dataset.id, this.dataset.nama, this.dataset.deskripsi, this.dataset.kerusakan)">
              Ubah
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="px-5 py-8 text-center text-gray-400 font-medium">
            Belum ada data solusi. Klik <strong>+</strong> untuk menambahkan.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal-overlay" id="modalTambah">
  <div class="modal-box">
    <button class="btn-close-modal" onclick="closeModal('modalTambah')">✕</button>
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">Tambah Data Solusi</h2>
    <form action="{{ route('admin.solusi.store') }}" method="POST">
      @csrf
      <div>
        <label class="modal-label">Nama Solusi</label>
        <input type="text" name="nama_solusi" class="modal-input {{ $errors->has('nama_solusi') ? 'is-invalid' : '' }}"
          value="{{ old('nama_solusi') }}" placeholder="Nama solusi..." autocomplete="off"/>
        @error('nama_solusi')<p class="text-red-500 text-xs font-bold -mt-2 mb-2">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="modal-label">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
        <textarea name="deskripsi" class="modal-input" placeholder="Deskripsi solusi...">{{ old('deskripsi') }}</textarea>
      </div>
      <div>
        <label class="modal-label">Kerusakan Terkait</label>
        <select name="kerusakan_id" class="modal-input {{ $errors->has('kerusakan_id') ? 'is-invalid' : '' }}">
          <option value="">-- Pilih Kerusakan --</option>
          @foreach($kerusakans as $k)
            <option value="{{ $k->id }}" {{ old('kerusakan_id') == $k->id ? 'selected' : '' }}>
              {{ $k->nama_kerusakan }}
            </option>
          @endforeach
        </select>
        @error('kerusakan_id')<p class="text-red-500 text-xs font-bold -mt-2 mb-2">{{ $message }}</p>@enderror
      </div>
      <div class="flex justify-end mt-4">
        <button type="submit" class="btn-save">save</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL UBAH --}}
<div class="modal-overlay" id="modalUbah">
  <div class="modal-box">
    <button class="btn-close-modal" onclick="closeModal('modalUbah')">✕</button>
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">Ubah Data Solusi</h2>
    <form id="formUbah" method="POST">
      @csrf
      @method('PUT')
      <div>
        <label class="modal-label">Nama Solusi</label>
        <input type="text" name="nama_solusi" id="inputNama" class="modal-input" placeholder="Nama solusi..." autocomplete="off"/>
      </div>
      <div>
        <label class="modal-label">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
        <textarea name="deskripsi" id="inputDeskripsi" class="modal-input" placeholder="Deskripsi solusi..."></textarea>
      </div>
      <div>
        <label class="modal-label">Kerusakan Terkait</label>
        <select name="kerusakan_id" id="selectKerusakan" class="modal-input">
          <option value="">-- Pilih Kerusakan --</option>
          @foreach($kerusakans as $k)
            <option value="{{ $k->id }}">{{ $k->nama_kerusakan }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex justify-end mt-4">
        <button type="submit" class="btn-save">save</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function openModal(id) {
    document.getElementById(id).classList.add('active');
  }
  function closeModal(id) {
    document.getElementById(id).classList.remove('active');
  }
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
      if (e.target === this) this.classList.remove('active');
    });
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
  });

  document.getElementById('btnAddOpen').addEventListener('click', () => openModal('modalTambah'));

  function openEditModal(id, nama, deskripsi, kerusakanId) {
    const form = document.getElementById('formUbah');
    form.action = '/admin/solusi/' + id;
    document.getElementById('inputNama').value = nama;
    document.getElementById('inputDeskripsi').value = deskripsi;
    document.getElementById('selectKerusakan').value = kerusakanId;
    openModal('modalUbah');
  }

  @if($errors->has('nama_solusi') || $errors->has('kerusakan_id'))
    openModal('modalTambah');
  @endif
</script>
@endpush
