<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\PesanService;
use App\Services\PesertaService;
use App\Services\TagihanService;
use App\Services\TransaksiService;
use Illuminate\Support\Facades\Auth;
use App\Services\ProgressUserService;
use App\Services\PengajuanBiayaService;
use App\Http\Requests\PengajuanBiaya\SppRequest;
use App\Http\Requests\PengajuanBiaya\WakafRequest;
use App\Http\Requests\PengajuanBiaya\UpdateRequest;
use App\Http\Requests\PengajuanBiaya\CreateBookVeeRequest;
use App\Http\Requests\PengajuanBiaya\CreateRegulerRequest;
use App\Http\Requests\PengajuanBiaya\PengajuanBiayaRequest;

class PengajuanBiayaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PengajuanBiayaService $pengajuanBiayaService,
        private PesertaService $pesertaService,
        private TransaksiService $transaksiService,
        private TagihanService $tagihanService,
        private ProgressUserService $progressUserService,
        private PesanService $pesanService
    ) {}

    public function createBookVee(CreateBookVeeRequest $request)
    {
        $data = [
            'jurusan' => 'unggulan',
            'nominal' => $request->validated('nominal'),
        ];

        $bookVee = $this->pengajuanBiayaService->getBookVee();
        if ($bookVee['success']) {
            return $this->error('Book Vee sudah ada, silahkan edit yang sudah ada', 422, null);
        }

        $result = $this->pengajuanBiayaService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function createReguler(CreateRegulerRequest $request)
    {
        $data = [
            'jurusan' => 'reguler',
            'jenjang_sekolah' => $request->validated('jenjang_sekolah'),
            'nominal' => $request->validated('nominal'),
        ];
        $reguler = $this->pengajuanBiayaService->getReguler($data['jenjang_sekolah']);
        if ($reguler['success']) {
            return $this->error('Book Vee sudah ada, silahkan edit yang sudah ada', 422, null);
        }
        $result = $this->pengajuanBiayaService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function getAll()
    {
        $result = $this->pengajuanBiayaService->getAll();
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = [
            'jenjang_sekolah' => $request->validated('jenjang_sekolah'),
            'jurusan' => $request->validated('jurusan'),
            'nominal' => $request->validated('nominal'),
        ];

        $result = $this->pengajuanBiayaService->update($id, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function delete($id)
    {
        $result = $this->pengajuanBiayaService->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function getById($id)
    {
        $result = $this->pengajuanBiayaService->getById($id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function getByUser()
    {
        $jurusan = Auth::user()->peserta->jurusan1->jurusan;
        $jenjang = Auth::user()->peserta->jenjang_sekolah;
        if ($jurusan != 'reguler') {
            $jurusan = 'unggulan';
            $jenjang = null;
            if (Auth::user()->tagihan->where('nama_tagihan', 'book_vee')->first()?->status === 1) {
                return $this->success(['jurusan' => $jurusan], "peserta sudah membayar book vee", 200);
            }
        }
        $result = $this->pengajuanBiayaService->getByUser($jenjang, $jurusan);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function reguler()
    {
        if (Auth::user()->peserta->jurusan1->jurusan != 'reguler') {
            return $this->error('Anda tidak dapat mengajukan biaya reguler', 422, null);
        }
        $biaya = $this->pengajuanBiayaService->getByUser(Auth::user()->peserta->jenjang_sekolah, Auth::user()->peserta->jurusan1->jurusan);
        if (!$biaya['success']) {
            return $this->error($biaya['message'], 422, null);
        }

        $data = [
            'pengajuan_biaya' => $biaya['data']['nominal'],
            'status' => 'diproses'
        ];
        $result = $this->pesertaService->update(Auth::user()->id, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        $progressUser = $this->progressUserService->getByUserId(Auth::user()->id);
        if (!$progressUser['success']) {
            return $this->error($progressUser['message'], 404, null);
        }

        $updateProgress = $this->progressUserService->updateProgress($progressUser['data'], ['progress' => 3]);
        if (!$updateProgress['success']) {
            return $this->error($updateProgress['message'], 404, null);
        }

        $dataPesan = [
            'user_id' => Auth::user()->id,
            'judul' => 'Pengajuan Biaya',
            'deskripsi' => Auth::user()->peserta->nama . ' telah menyelesaikan tahapan ketiga pendaftaran yaitu pengajuan biaya',
        ];

        $pesan = $this->pesanService->create($dataPesan);

        if (!$pesan['success']) {
            return $this->success(null, $result['message'], 200);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function wakaf(WakafRequest $request)
    {
        if (Auth::user()->peserta->jurusan1->jurusan == 'reguler') {
            return $this->error('Anda tidak dapat mengajukan biaya unggulan', 422, null);
        }

        $result = $this->pesertaService->update(Auth::user()->peserta->id, ['wakaf' => $request->validated('wakaf')]);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function spp(SppRequest $request)
    {
        if (Auth::user()->peserta->jurusan1->jurusan == 'reguler') {
            return $this->error('Anda tidak dapat mengajukan biaya unggulan', 422, null);
        }

        $data = [
            'spp' => $request->validated('spp'),
            'status' => 'diproses'
        ];

        $result = $this->pesertaService->update(Auth::user()->peserta->id, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        $progressUser = $this->progressUserService->getByUserId(Auth::user()->id);
        if (!$progressUser['success']) {
            return $this->error($progressUser['message'], 404, null);
        }

        $updateProgress = $this->progressUserService->updateProgress($progressUser['data'], ['progress' => 3]);
        if (!$updateProgress['success']) {
            return $this->error($updateProgress['message'], 404, null);
        }

        $dataPesan = [
            'user_id' => Auth::user()->id,
            'judul' => 'Pengajuan Biaya',
            'deskripsi' => Auth::user()->peserta->nama . ' telah menyelesaikan tahapan ketiga pendaftaran yaitu pengajuan biaya',
        ];

        $pesan = $this->pesanService->create($dataPesan);

        if (!$pesan['success']) {
            return $this->success(null, $result['message'], 200);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function bookVee()
    {
        if (Auth::user()->peserta->jurusan1->jurusan == 'reguler') {
            return $this->error('Anda tidak dapat mengajukan biaya unggulan', 422, null);
        }

        if (Auth::user()->tagihan->where('nama_tagihan', 'book_vee')->count() > 0) {
            $existingTagihan = Auth::user()->tagihan
                ->where('nama_tagihan', 'book_vee')
                ->first();
            return $this->error('Harap Membayar book vee', 200, $existingTagihan ? [
                'qr_data' => $existingTagihan->qr_data,
                'va_number' => $existingTagihan->va_number
            ] : null);
        }
        $biaya = $this->pengajuanBiayaService->getBookVee();
        if (!$biaya['success']) {
            return $this->error($biaya['message'], 422, null);
        }

        $result = $this->pesertaService->update(Auth::user()->peserta->id, ['book_vee' => $biaya['data']['nominal']]);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        $dataTagihan = [
            'user_id' => Auth::user()->id,
            'nama_tagihan' => 'book_vee',
            'total' => $biaya['data']->nominal,
        ];

        $tagihan = $this->tagihanService->create($dataTagihan);
        if (!$tagihan['success']) {
            return $this->error($tagihan['message'], 400, null);
        }

        $dataPesan = [
            'user_id' => Auth::user()->id,
            'judul' => 'Booking Vee',
            'deskripsi' => Auth::user()->peserta->nama . ' telah mengajukan biaya book vee, harap segera melakukan pembayaran berikut ini',
        ];

        $pesan = $this->pesanService->create($dataPesan);

        if (!$pesan['success']) {
            return $this->success(null, $result['message'], 200);
        }

        return $this->success($tagihan['data'], $result['message'], 200);
    }

    public function createTagihanWakaf(WakafRequest $request)
    {
        // Validasi progress pendaftaran
        if (Auth::user()->progressUser->progress < 3) {
            return $this->error('Anda belum menyelesaikan tahapan pendaftaran', 422, null);
        }

        // Validasi tagihan wakaf yang belum dibayar
        $existingTagihan = Auth::user()->tagihan
            ->where('nama_tagihan', 'wakaf')
            ->where('status', 0)
            ->first();

        if ($existingTagihan) {
            return $this->error(
                'Harap Membayar tagihan wakaf yang belum terbayar terlebih dahulu',
                200,
                $existingTagihan->qr_data ? [
                    'qr_data' => $existingTagihan->qr_data,
                    'va_number' => $existingTagihan->va_number,
                ] : null
            );
        }

        // Validasi nominal wakaf
        $nominal = $request->validated('wakaf');
        if ($nominal <= 0) {
            return $this->error('Nominal wakaf harus lebih besar dari 0', 422, null);
        }

        // Membuat data tagihan baru
        $dataTagihan = [
            'user_id' => Auth::user()->id,
            'nama_tagihan' => 'wakaf',
            'total' => $nominal,
        ];

        // Menyimpan tagihan
        $tagihan = $this->tagihanService->create($dataTagihan);
        if (!$tagihan['success']) {
            return $this->error($tagihan['message'], 400, null);
        }

        $dataPesan = [
            'user_id' => Auth::user()->id,
            'judul' => 'Tagihan Wakaf',
            'deskripsi' => "Anda telah mengajukan tagihan wakaf sebesar Rp " . number_format($nominal, 0, ',', '.') . " terimakasih atas partisipasi anda, harap segera melakukan pembayaran berikut ini",
        ];

        $pesan = $this->pesanService->create($dataPesan);
        if (!$pesan['success']) {
            return $this->error($pesan['message'], 400, null);
        }

        return $this->success(
            null,
            'Tagihan Wakaf sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' telah berhasil dibuat',
            200
        );
    }

    public function createTagihanPengajuanBiaya(PengajuanBiayaRequest $request)
    {
        // Validasi progress pendaftaran
        if (Auth::user()->progressUser->progress < 3) {
            return $this->error('Anda belum menyelesaikan tahapan pendaftaran', 422, null);
        }

        // Validasi tagihan pengajuan biaya yang belum dibayar
        $existingTagihan = Auth::user()->tagihan
            ->where('nama_tagihan', 'pengajuan_biaya')
            ->where('status', 0)
            ->first();

        if ($existingTagihan) {
            return $this->error(
                'Harap Membayar tagihan yang belum terbayar terlebih dahulu',
                200,
                $existingTagihan->qr_data ? [
                    'qr_data' => $existingTagihan->qr_data,
                    'va_number' => $existingTagihan->va_number,
                ] : null
            );
        }

        // Validasi nominal wakaf
        $nominal = $request->validated('pengajuan_biaya');
        if ($nominal <= 0) {
            return $this->error('Nominal harus lebih besar dari 0', 422, null);
        }

        // Membuat data tagihan baru
        $dataTagihan = [
            'user_id' => Auth::user()->id,
            'nama_tagihan' => 'pengajuan_biaya',
            'total' => $nominal,
        ];

        // Menyimpan tagihan
        $tagihan = $this->tagihanService->create($dataTagihan);
        if (!$tagihan['success']) {
            return $this->error($tagihan['message'], 400, null);
        }

        $dataPesan = [
            'user_id' => Auth::user()->id,
            'judul' => 'Tagihan Biaya',
            'deskripsi' => "Anda telah mengajukan tagihan biaya sebesar Rp " . number_format($nominal, 0, ',', '.') . " terimakasih atas partisipasi anda, harap segera melakukan pembayaran berikut ini",
        ];

        $pesan = $this->pesanService->create($dataPesan);
        if (!$pesan['success']) {
            return $this->error($pesan['message'], 400, null);
        }

        return $this->success(
            null,
            'Tagihan biaya sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' telah berhasil dibuat',
            200
        );
    }
}
