<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::latest()->get();
        return view('payment.index', compact('payments'));
    }

    public function create()
    {
        return view('payment.create');
    }

    public function store(Request $request)
    {
        // VALIDASI DINAMIS
        $rules = [
            'nama_merchant' => 'required|string|max:100',
            'jenis' => 'required|in:CASH,QRIS,TRANSFER',
        ];

        if ($request->jenis === 'QRIS') {
            $rules['barcode'] = 'required|image|mimes:png,jpg,jpeg|max:2048';
        }

        if ($request->jenis === 'TRANSFER') {
            $rules['no_rekening'] = 'required|string|max:50';
        }

        $request->validate($rules);

        // DATA DASAR
        $data = [
            'nama_merchant' => $request->nama_merchant,
            'jenis' => $request->jenis,
            'barcode' => null,
            'no_rekening' => null,
        ];

        // JIKA QRIS
        if ($request->jenis === 'QRIS') {
            $fileName = time() . '_' . $request->barcode->getClientOriginalName();
            $request->barcode->storeAs('qris', $fileName, 'public');
            $data['barcode'] = $fileName;
        }

        // JIKA TRANSFER
        if ($request->jenis === 'TRANSFER') {
            $data['no_rekening'] = $request->no_rekening;
        }

        Payment::create($data);

        return redirect()->route('payment.index')
            ->with('success', 'Payment berhasil ditambahkan');
    }

    public function update(Request $request)
    {
        $payment = Payment::findOrFail($request->id_payment);

        $request->validate([
            'nama_merchant' => 'required|string|max:100',
            'barcode' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $data = [
            'nama_merchant' => $request->nama_merchant,
        ];

        if ($request->hasFile('barcode')) {
            // hapus file lama
            if ($payment->barcode && Storage::disk('public')->exists('qris/' . $payment->barcode)) {
                Storage::disk('public')->delete('qris/' . $payment->barcode);
            }

            $fileName = time() . '_' . $request->barcode->getClientOriginalName();
            $request->barcode->storeAs('qris', $fileName, 'public');
            $data['barcode'] = $fileName;
        }

        $payment->update($data);

        return redirect()->route('payment.index')
            ->with('success', 'QRIS berhasil diperbarui');
    }

    public function toggle($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'is_active' => !$payment->is_active
        ]);

        return redirect()->route('payment.index')
            ->with('success', 'Status payment berhasil diubah');
    }
}
