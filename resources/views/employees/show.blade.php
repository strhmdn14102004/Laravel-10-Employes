<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Informasi Pribadi</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->nama }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NIK</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->nik }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->alamat }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Lokasi</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->provinsi }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->kabupaten }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->kecamatan }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kelurahan</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $employee->kelurahan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if ($employee->photo_path)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Foto</label>
                        <div class="mt-2">
                            <img src="{{ \Storage::disk('google')->url($employee->photo_path) }}" alt="Employee Photo" class="h-40 w-40 object-cover rounded">
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('employees.edit', $employee->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>