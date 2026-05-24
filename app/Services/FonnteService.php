<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;
    protected bool $enabled;
    protected string $adminPhone;

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN', '');
        $this->enabled = env('FONNTE_ENABLED', false);
        $this->adminPhone = env('FONNTE_ADMIN_PHONE', '');
    }

    /**
     * Mengirim pesan via Fonnte API.
     */
    public function sendMessage(string $phone, string $message): bool
    {
        if (!$this->enabled || empty($this->token)) {
            Log::info('[Fonnte] Notifikasi dinonaktifkan atau Token kosong. Pesan tidak dikirim.', [
                'phone' => $phone,
            ]);
            return false;
        }

        // Fonnte menerima nomor lokal '08xxx' atau '628xxx'
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Default kode negara jika nomor dimulai dengan 0
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['status']) && $data['status'] === true) {
                    Log::info('[Fonnte] Pesan berhasil dikirim.', ['phone' => $phone]);
                    return true;
                }
                
                Log::warning('[Fonnte] API mengembalikan status false.', ['response' => $data]);
                return false;
            }

            Log::error('[Fonnte] Gagal mengirim pesan.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;

        } catch (\Throwable $e) {
            Log::error('[Fonnte] Exception saat mengirim pesan.', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Notifikasi pesanan dibayar pelanggan (Payment Flow).
     */
    public function notifyOrderPaid(Order $order): void
    {
        $order->loadMissing('customer');

        $nama = $order->customer->nama ?? 'Pelanggan';
        $invoice = $order->invoice_number;

        $message = "*Bay Coffee - Pesanan Dibayar*\n\n"
            . "Halo {$nama}!\n"
            . "Pesanan Anda dengan nomor invoice *{$invoice}* sudah dibayar!\n\n"
            . "Pesanan anda sedang kami siapkan.\n\n"
            . "Silahkan cek invoice pesanan anda di website Bay Coffee.\n\n"
            . "Terima kasih telah berbelanja di Bay Coffee!";
        $this->sendMessage($order->phone, $message);
    }

    /**
     * Notifikasi pesanan dikirim (Shipping Flow).
     */
    public function notifyOrderShipped(Order $order): void
    {
        $order->loadMissing('customer');

        $nama = $order->customer->nama ?? 'Pelanggan';
        $invoice = $order->invoice_number;

        $message = "*Bay Coffee - Pesanan Dikirim*\n\n"
            . "Halo {$nama}!\n"
            . "Pesanan Anda dengan nomor invoice *{$invoice}* sedang dalam perjalanan!\n\n"
            . "Estimasi tiba dalam 1-2 hari kerja.\n"
            . "Terima kasih telah berbelanja di Bay Coffee!";

        $this->sendMessage($order->phone, $message);
    }
    /**
     * Notifikasi pesanan selesai (Customer).
     */
    public function notifyOrderCompleted(Order $order): void
    {
        $order->loadMissing('customer');

        $nama = $order->customer->nama ?? 'Pelanggan';
        $invoice = $order->invoice_number;

        $message = "*Bay Coffee - Pesanan Selesai*\n\n"
            . "Halo {$nama}!\n"
            . "Pesanan Anda *{$invoice}* telah selesai.\n\n"
            . "Terima kasih telah berbelanja di Bay Coffee!\n"
            . "Kami harap Anda puas dengan produk kami.";

        $this->sendMessage($order->phone, $message);
    }

    /**
     * Notifikasi admin ada pesanan yang sudah dibayar.
     */
    public function notifyAdminNewOrder(Order $order): void
    {
        if (empty($this->adminPhone)) {
            return;
        }

        $order->loadMissing('customer');

        $nama = $order->customer->nama ?? 'Pelanggan';
        $invoice = $order->invoice_number;
        $total = number_format($order->total_price, 0, ',', '.');
        $phone = $order->phone;
        $alamat = $order->customer->alamat ?? 'Tidak ada';

        $message = "*Pesanan Telah Dibayar!*\n\n"
            . "Dari: *{$nama}*\n"
            . "No. Invoice: *{$invoice}*\n"
            . "Total: *Rp {$total}*\n"
            . "Alamat: *{$alamat}*\n"
            . "Telepon: {$phone}\n\n"
            . "Status pembayaran sudah LUNAS. Segera siapkan pesanan ini dan cek panel admin Bay Coffee.";

        $this->sendMessage($this->adminPhone, $message);
    }
}
