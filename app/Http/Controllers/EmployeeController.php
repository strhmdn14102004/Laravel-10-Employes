<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $employees = Employee::where('user_id', auth()->id())->get();
        return view('employees.index', compact('employees'));
    }
    
    public function create()
    {
        // Ambil data provinsi dari API
        $provinces = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')->json();
        return view('employees.create', compact('provinces'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:employees',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        try {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $this->uploadToGoogleDrive($request->file('photo'));
            }
            
            $employee = Employee::create([
                'nama' => $validated['nama'],
                'nik' => $validated['nik'],
                'alamat' => $validated['alamat'],
                'provinsi' => $validated['provinsi'],
                'kabupaten' => $validated['kabupaten'],
                'kecamatan' => $validated['kecamatan'],
                'kelurahan' => $validated['kelurahan'],
                'photo_path' => $photoPath,
                'user_id' => Auth::id(),
            ]);
            
            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to create employee: '.$e->getMessage()]);
        }
    }
    
    public function show(Employee $employee)
    {
        $this->authorize('view', $employee);
        return view('employees.show', compact('employee'));
    }
    
    public function edit(Employee $employee)
    {
        $this->authorize('update', $employee);
        $provinces = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')->json();
        return view('employees.edit', compact('employee', 'provinces'));
    }
    
    public function update(Request $request, Employee $employee)
    {
        $this->authorize('update', $employee);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:employees,nik,'.$employee->id,
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $photoPath = $employee->photo_path;
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($photoPath) {
                $this->deleteFromGoogleDrive($photoPath);
            }
            $photoPath = $this->uploadToGoogleDrive($request->file('photo'));
        }
        
        $employee->update([
            'nama' => $validated['nama'],
            'nik' => $validated['nik'],
            'alamat' => $validated['alamat'],
            'provinsi' => $validated['provinsi'],
            'kabupaten' => $validated['kabupaten'],
            'kecamatan' => $validated['kecamatan'],
            'kelurahan' => $validated['kelurahan'],
            'photo_path' => $photoPath,
        ]);
        
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }
    
    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);
        
        if ($employee->photo_path) {
            $this->deleteFromGoogleDrive($employee->photo_path);
        }
        
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
    
    public function getRegencies($provinceId)
    {
        $regencies = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json")->json();
        return response()->json($regencies);
    }
    
    public function getDistricts($regencyId)
    {
        $districts = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$regencyId}.json")->json();
        return response()->json($districts);
    }
    
    public function getVillages($districtId)
    {
        $villages = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json")->json();
        return response()->json($villages);
    }
    
    private function uploadToGoogleDrive($file)
{
    try {
        $fileName = time().'_'.$file->getClientOriginalName();
        $filePath = 'employee-photos/'.$fileName;
        
        \Storage::disk('google')->put($filePath, file_get_contents($file));
        
        return $filePath;
    } catch (\Exception $e) {
        \Log::error('Google Drive upload failed: '.$e->getMessage());
        return null;
    }
}
    
    private function deleteFromGoogleDrive($filePath)
    {
        if (\Storage::disk('google')->exists($filePath)) {
            \Storage::disk('google')->delete($filePath);
        }
    }
}