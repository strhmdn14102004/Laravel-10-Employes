<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Employee Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 mt-4">
                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block font-medium text-sm text-gray-700">Nama</label>
                                <input id="nama" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="nama" required />
                            </div>

                            <!-- NIK -->
                            <div>
                                <label for="nik" class="block font-medium text-sm text-gray-700">NIK</label>
                                <input id="nik" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="nik" required />
                            </div>

                            <!-- Alamat -->
                            <div>
                                <label for="alamat" class="block font-medium text-sm text-gray-700">Alamat</label>
                                <textarea id="alamat" name="alamat" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                            </div>

                            <!-- Provinsi -->
                            <div>
                                <label for="provinsi" class="block font-medium text-sm text-gray-700">Provinsi</label>
                                <select id="provinsi" name="provinsi" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province['name'] }}" data-id="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Kabupaten/Kota -->
                            <div>
                                <label for="kabupaten" class="block font-medium text-sm text-gray-700">Kabupaten/Kota</label>
                                <select id="kabupaten" name="kabupaten" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required disabled>
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                            </div>

                            <!-- Kecamatan -->
                            <div>
                                <label for="kecamatan" class="block font-medium text-sm text-gray-700">Kecamatan</label>
                                <select id="kecamatan" name="kecamatan" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            <!-- Kelurahan -->
                            <div>
                                <label for="kelurahan" class="block font-medium text-sm text-gray-700">Kelurahan</label>
                                <select id="kelurahan" name="kelurahan" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required disabled>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>

                            <!-- Photo -->
                            <div>
                                <label for="photo" class="block font-medium text-sm text-gray-700">Foto</label>
                                <input id="photo" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="file" name="photo" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle provinsi change
            document.getElementById('provinsi').addEventListener('change', function() {
                const provinceId = this.options[this.selectedIndex].getAttribute('data-id');
                const kabupatenSelect = document.getElementById('kabupaten');
                
                if (provinceId) {
                    fetch(`/employees/regencies/${provinceId}`)
                        .then(response => response.json())
                        .then(data => {
                            kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                            data.forEach(regency => {
                                const option = document.createElement('option');
                                option.value = regency.name;
                                option.setAttribute('data-id', regency.id);
                                option.textContent = regency.name;
                                kabupatenSelect.appendChild(option);
                            });
                            kabupatenSelect.disabled = false;
                        });
                } else {
                    kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                    kabupatenSelect.disabled = true;
                    document.getElementById('kecamatan').innerHTML = '<option value="">Pilih Kecamatan</option>';
                    document.getElementById('kecamatan').disabled = true;
                    document.getElementById('kelurahan').innerHTML = '<option value="">Pilih Kelurahan</option>';
                    document.getElementById('kelurahan').disabled = true;
                }
            });

            // Handle kabupaten change
            document.getElementById('kabupaten').addEventListener('change', function() {
                const regencyId = this.options[this.selectedIndex].getAttribute('data-id');
                const kecamatanSelect = document.getElementById('kecamatan');
                
                if (regencyId) {
                    fetch(`/employees/districts/${regencyId}`)
                        .then(response => response.json())
                        .then(data => {
                            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                            data.forEach(district => {
                                const option = document.createElement('option');
                                option.value = district.name;
                                option.setAttribute('data-id', district.id);
                                option.textContent = district.name;
                                kecamatanSelect.appendChild(option);
                            });
                            kecamatanSelect.disabled = false;
                        });
                } else {
                    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    kecamatanSelect.disabled = true;
                    document.getElementById('kelurahan').innerHTML = '<option value="">Pilih Kelurahan</option>';
                    document.getElementById('kelurahan').disabled = true;
                }
            });

            // Handle kecamatan change
            document.getElementById('kecamatan').addEventListener('change', function() {
                const districtId = this.options[this.selectedIndex].getAttribute('data-id');
                const kelurahanSelect = document.getElementById('kelurahan');
                
                if (districtId) {
                    fetch(`/employees/villages/${districtId}`)
                        .then(response => response.json())
                        .then(data => {
                            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                            data.forEach(village => {
                                const option = document.createElement('option');
                                option.value = village.name;
                                option.textContent = village.name;
                                kelurahanSelect.appendChild(option);
                            });
                            kelurahanSelect.disabled = false;
                        });
                } else {
                    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                    kelurahanSelect.disabled = true;
                }
            });
        });
    </script>
    @endpush
</x-app-layout>