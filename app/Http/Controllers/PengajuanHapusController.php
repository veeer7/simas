<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Support\Peran;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PengajuanHapusController extends Controller
{
    private function hapusSatu(PengajuanSurat $p): void
    {
        DB::transaction(function () use ($p) {
            if (Schema::hasTable('lampiran')) {
                DB::table('lampiran')
                    ->where('attachable_id', $p->id)
                    ->where('attachable_type', $p->getMorphClass())
                    ->delete();
            }
            $p->delete();
        });
    }

    public function destroy(PengajuanSurat $pengajuan)
    {
        abort_unless(Peran::isAdmin(), 403);

        try {
            $this->hapusSatu($pengajuan);
        } catch (QueryException $e) {
            return back()->with('error', 'Gagal menghapus: data ini masih terhubung dengan data lain (riwayat approval, dll).');
        }

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function hapusDraft()
    {
        abort_unless(Peran::isAdmin(), 403);

        $jumlah = 0;
        foreach (PengajuanSurat::where('status', 'draft')->get() as $p) {
            try {
                $this->hapusSatu($p);
                $jumlah++;
            } catch (QueryException $e) {
                // lewati yang gagal
            }
        }

        return redirect()->route('pengajuan.index')->with('success', "$jumlah draft berhasil dihapus.");
    }
}