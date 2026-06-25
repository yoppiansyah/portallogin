// --- PROSES INJEKSI MENGGUNAKAN REST API MIKROTIK V7 (cURL) ---
        $chr_ip   = '202.10.41.185';
        $chr_port = '8728'; // <-- UBAH KE PORT API MIKROTIK (SUDAH PASTI OPEN)
        $username = 'api_portal';
        $password = 'AksesPortal2026';

        // Data JSON yang dikirim ke MikroTik untuk Add Address List
        $data = json_encode([
            'list' => 'Akses_OLT',
            'address' => $user_ip,
            'timeout' => '02:00:00'
        ]);

        // UBAH PROTOKOL MENJADI HTTPS:// KARENA REST API DI PORT 8728 WAJIB SSL/TLS BADAAN MIKROTIK
        $url = "https://{$chr_ip}:{$chr_port}/rest/ip/firewall/address-list/add";
