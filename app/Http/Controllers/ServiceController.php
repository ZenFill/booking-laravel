<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Requests\StoreServiceRequest;

class ServiceController extends Controller
{
    // 1. Tampilkan Daftar Ruangan
    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    // 2. Simpan Ruangan Baru
    public function store(StoreServiceRequest $request)
    {
        // Validasi via StoreServiceRequest

        Service::create($request->all());

        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambahkan!');
    }

    // 3. Hapus Ruangan
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Layanan dihapus.');
    }
}
