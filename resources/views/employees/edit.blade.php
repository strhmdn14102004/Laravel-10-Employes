<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 mt-4">
                            <!-- Nama -->
                            <div>
                                <x-label for="nama" :value="__('Nama')" />
                                <x-input id="nama" class="block mt-1 w-full" type="text" name="nama" value="{{ $employee->nama }}" required />
                            </div>

                            <!-- NIK -->
                            <div>
                                <x-label for="nik" :value="__('NIK')" />
                                <x-input id="nik" class="block mt-1 w-full" type="text" name="nik" value="{{ $employee->nik }}" required />
                            </div>

                            <!-- Alamat -->
                            <div>
                                <x-label for="alamat" :value="__('Alamat')" />
                                <textarea id="alamat" name="alamat" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ $employee->alamat }}</textarea>
                            </div>

                            <!-- Provinsi -->
                            <div>
                                <x-label for="provinsi" :value="__('Provinsi')" />
                                <select id="provinsi" name="provinsi" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province['name'] }}" data-id="{{ $province['id'] }}" {{ $employee->provinsi == $province['name'] ? 'selected' : '' }}>{{ $province['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Kabupaten/Kota -->
                            <div>
                                <x-label for="kabupaten" :value="__('Kabupaten/Kota')" />
                                <select id="kabupaten" name="kabupaten" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required {{ !$employee->provinsi ? 'disabled' : '' }}>
                                    <option value="">Pilih Kabupaten/Kota</option>
                                    @if ($employee->provinsi)
                                        <option value="{{ $employee->kabupaten }}" selected>{{ $employee->kabupaten }}</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Kecamatan -->
                            <div>
                                <x-label for="kecamatan" :value="__('Kecamatan')" />
                                <select id="kecamatan" name="kecamatan" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required {{ !$employee->kabupaten ? 'disabled' : '' }}>
                                    <option value="">Pilih Kecamatan</option>
                                    @if ($employee->kabupaten)
                                        <option value="{{ $employee->kecamatan }}" selected>{{ $employee->kecamatan }}</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Kelurahan -->
                            <div>
                                <x-label for="kelurahan" :value="__('Kelurahan')" />
                                <select id="kelurahan" name="kelurahan" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required {{ !$employee->kecamatan ? 'disabled' : '' }}>
                                    <option value="">Pilih Kelurahan</option>
                                    @if ($employee->kecamatan)
                                        <option value="{{ $employee->kelurahan }}" selected>{{ $employee->kelurahan }}</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Photo -->
                            <div>
                                <x-label for="photo" :value="__('Foto')" />
                                @if ($employee->photo_path)
                                    <div class="mb-2">
                                        <img src="{{ \Storage::disk('google')->url($employee->photo_path) }}" alt="Employee Photo" class="h-20 w-20 object-cover rounded">
                                    </div>
                                @endif
                                <x-input id="photo" class="block mt-1 w-full" type="file" name="photo" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-button>
                                {{ __('Update') }}
                            </x-button>
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

            // Jika provinsi sudah dipilih, load kabupaten
            const provinsiSelect = document.getElementById('provinsi');
            if (provinsiSelect.value) {
                const event = new Event('change');
                provinsiSelect.dispatchEvent(event);
            }
        });
    </script>
    @endpush
</x-app-layout>