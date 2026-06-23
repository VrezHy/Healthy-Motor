@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

@section('content')

<div class="flex-1 min-h-0 flex flex-col">
  @if(session('success'))
  <div class="alert-success flex-shrink-0">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    {{ session('success') }}
  </div>

  @endif
  <div class="mb-3 flex-shrink-0 flex items-center justify-between">
    <x-shared.button id="btnAddOpen" title="Tambah Data Solusi" variant="primary">+</x-shared.button>
    <div>
      <x-shared.search />
    </div>
  </div>


  <div class="rounded-2xl overflow-hidden shadow-sm flex-1 min-h-0 flex flex-col bg-white">
    <div class="table-header px-5 py-3 flex-shrink-0">
      <span class="font-bold text-sm">Tabel Data Gejala</span>
    </div>


    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0">
      @if($gejalas->isEmpty())
      <div class="flex flex-col items-center justify-center h-full w-full py-10">
        @if(request()->has('search') && !empty(request('search')))
        <div class="text-center">
          <p class="text-lg text-slate-800 font-semibold">Data tidak ditemukan</p>
          <p class="text-sm text-gray-500 mt-2">
            Tidak ada hasil untuk "{{ request('search') }}"
          </p>
        </div>
        @else
        <x-shared.empty-state-plus namaHalaman="gejala" />
        @endif

      </div>
      @else
      <table class="w-full text-sm border-collapse">
        <thead class="sticky top-0 bg-[#f0f1fa] z-10 shadow-[inset_0_-1px_0_rgba(0,0,0,0.05)]">
          <tr>
            <th class="text-left px-5 py-3 font-bold text-gray-700 w-12">No</th>
            <th class="text-left px-5 py-3 font-bold text-gray-700 w-24">Kode</th>
            <th class="text-left px-5 py-3 font-bold text-gray-700">Nama Gejala</th>
            <th class="text-left px-5 py-3 font-bold text-gray-700">Kerusakan</th>
            <th class="text-right px-5 py-3 font-bold text-gray-700 w-44">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @foreach($gejalas as $item)
          <tr class="border-b border-gray-100 hover:bg-gray-50/50">
            <td class="px-5 py-3 font-semibold text-gray-700">{{ $loop->iteration }}</td>
            <td class="px-5 py-3 font-mono font-bold text-indigo-600">{{ $item->kode_gejala }}</td>
            <td class="px-5 py-3 text-gray-700">{{ $item->nama_gejala }}</td>
            <td class="px-5 py-3">
              <span class="badge-kerusakan">{{ $item->kerusakan->nama_kerusakan ?? '-' }}</span>
            </td>
            <td class="px-5 py-3 text-right flex items-end justify-center flex-col gap-2">
              <form action="{{ route('admin.gejala.destroy', $item->id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-hapus" onclick="bukaModalHapus(this)">Hapus</button>
              </form>
              <button type="button" class="btn-ubah"
                data-id="{{ $item->id }}"
                data-kode="{{ $item->kode_gejala }}"
                data-nama="{{ $item->nama_gejala }}"
                data-kerusakan="{{ $item->kerusakan_id }}"
                onclick="openEditModal(this.dataset.id, this.dataset.kode, this.dataset.nama, this.dataset.kerusakan)">
                Ubah
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @endif
    </div>
  </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal-overlay" id="modalTambah" data-show-modal="{{ $errors->hasAny(['kode_gejala', 'nama_gejala', 'kerusakan_id']) ? 'true' : 'false' }}">
  <div class="modal-box">
    <button class="btn-close-modal" onclick="closeModal('modalTambah')">✕</button>
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">Tambah Data Gejala</h2>
    <form action="{{ route('admin.gejala.store') }}" method="POST">
      @csrf
      <div>
        <label class="modal-label">Kode Gejala</label>
        <input type="text" name="kode_gejala" class="modal-input {{ $errors->has('kode_gejala') ? 'is-invalid' : '' }}"
          value="{{ old('kode_gejala') }}" placeholder="Contoh: G001" autocomplete="off" />
        @error('kode_gejala')<p class="text-red-500 text-xs font-bold -mt-2 mb-2">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="modal-label">Nama Gejala</label>
        <input type="text" name="nama_gejala" class="modal-input {{ $errors->has('nama_gejala') ? 'is-invalid' : '' }}"
          value="{{ old('nama_gejala') }}" placeholder="Nama gejala..." autocomplete="off" />
        @error('nama_gejala')<p class="text-red-500 text-xs font-bold -mt-2 mb-2">{{ $message }}</p>@enderror
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
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">Ubah Data Gejala</h2>
    <form id="formUbah" method="POST">
      @csrf
      @method('PUT')
      <div>
        <label class="modal-label">Kode Gejala</label>
        <input type="text" name="kode_gejala" id="inputKode" class="modal-input" placeholder="Contoh: G001" autocomplete="off" />
      </div>
      <div>
        <label class="modal-label">Nama Gejala</label>
        <input type="text" name="nama_gejala" id="inputNama" class="modal-input" placeholder="Nama gejala..." autocomplete="off" />
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

<div class="custom-modal" id="hapusModal">
  <div class="custom-modal-box">
    <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 20px;">Hapus Data Gejala Ini?</h2>
    <div class="custom-modal-actions">
      <button type="button" class="custom-btn" onclick="tutupModalHapus()">batal</button>
      <button type="button" class="custom-btn" id="confirmHapus" style="background-color: #dc2626; color: white;">hapus</button>
    </div>
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

  function openEditModal(id, kode, nama, kerusakanId) {
    const form = document.getElementById('formUbah');
    form.action = '/admin/gejala/' + id;
    document.getElementById('inputKode').value = kode;
    document.getElementById('inputNama').value = nama;
    document.getElementById('selectKerusakan').value = kerusakanId;
    openModal('modalUbah');
  }

  const shouldOpenModal = document.getElementById('modalTambah').dataset.showModal === 'true';
  if (shouldOpenModal) {
    openModal('modalTambah');
  }

  let formHapus = null;

  function bukaModalHapus(button) {
    formHapus = button.closest('form');
    document.getElementById('hapusModal').style.display = 'flex';
  }

  function tutupModalHapus() {
    document.getElementById('hapusModal').style.display = 'none';
    formHapus = null;
  }

  document.getElementById('confirmHapus').addEventListener('click', function() {
    if (formHapus) {
      formHapus.submit();
    }
  });
</script>
@endpush