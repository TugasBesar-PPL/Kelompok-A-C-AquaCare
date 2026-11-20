<?php

namespace Tests\Support;

use App\Models\LaporanMasalahAir;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

trait Pbi06TestData
{
    protected function createReport(User $owner, array $attributes = []): LaporanMasalahAir
    {
        return LaporanMasalahAir::query()->create(array_merge([
            'user_id' => $owner->id,
            'titik_air_id' => null,
            'kategori_masalah' => 'Air keruh',
            'wilayah' => 'RW 01',
            'deskripsi' => 'Air terlihat keruh sejak pagi.',
            'lokasi' => [
                'latitude' => -6.2,
                'longitude' => 106.8,
                'alamat' => 'Jl. Melati No. 1',
            ],
            'foto_laporan' => 'laporan/report.png',
            'tanggal_laporan' => now(),
            'status_laporan' => 'Menunggu',
        ], $attributes));
    }

    protected function createHistoryTable(): void
    {
        Schema::create('riwayat_penanganan', function (Blueprint $table) {
            $table->id('penanganan_id');
            $table->foreignId('laporan_id')->constrained('laporan_masalah_air', 'laporan_id');
            $table->foreignId('petugas_id')->constrained('users');
            $table->timestamp('tanggal_pembaruan');
            $table->string('status_penanganan', 50);
            $table->text('catatan')->nullable();
            $table->string('foto_bukti')->nullable();
        });
    }

    protected function validReportPayload(): array
    {
        return [
            'kategori' => 'Air berbau',
            'wilayah' => 'RW 02',
            'lokasi' => [
                'latitude' => '-6.2',
                'longitude' => '106.8',
                'alamat' => 'Jl. Kenanga No. 5',
            ],
            'deskripsi' => 'Air berbau sejak pagi.',
            'foto' => UploadedFile::fake()->image('bukti.png', 20, 20),
        ];
    }

    protected function oversizedValidPng(): UploadedFile
    {
        $image = imagecreatetruecolor(1400, 1400);
        $path = tempnam(sys_get_temp_dir(), 'pbi06-photo-');

        if ($image === false || $path === false) {
            throw new RuntimeException('Unable to create the oversized PBI-06 image fixture.');
        }

        try {
            imagefilledrectangle($image, 0, 0, 1399, 1399, imagecolorallocate($image, 20, 120, 130));

            if (! imagepng($image, $path, 0, PNG_NO_FILTER)) {
                throw new RuntimeException('Unable to write the oversized PBI-06 PNG fixture.');
            }
        } finally {
            imagedestroy($image);
        }

        $imageInfo = getimagesize($path);
        $maxBytes = 5120 * 1024;

        if ($imageInfo === false || $imageInfo['mime'] !== 'image/png' || filesize($path) <= $maxBytes) {
            @unlink($path);

            throw new RuntimeException('The PBI-06 image fixture must be a valid PNG larger than 5 MB.');
        }

        return new UploadedFile($path, 'too-large.png', 'image/png', UPLOAD_ERR_OK, true);
    }
}
