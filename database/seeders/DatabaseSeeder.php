<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Setting;
use App\Models\Item;
use App\Models\ItemBatch;
use App\Models\Treatment;
use App\Models\BomTemplate;
use App\Models\BomTemplateItem;
use App\Models\Patient;
use App\Models\Kunjungan;
use App\Models\RekamMedis;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionTreatment;
use App\Models\StockMutation;
use App\Models\Expense;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('stock_mutations')->truncate();
        DB::table('transaction_items')->truncate();
        DB::table('transaction_treatments')->truncate();
        DB::table('transactions')->truncate();
        DB::table('rekam_medis')->truncate();
        DB::table('kunjungans')->truncate();
        DB::table('bom_template_items')->truncate();
        DB::table('bom_templates')->truncate();
        DB::table('item_batches')->truncate();
        DB::table('items')->truncate();
        DB::table('treatments')->truncate();
        DB::table('patients')->truncate();
        DB::table('settings')->truncate();
        DB::table('expenses')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seedSettings();
        $this->seedUsers();
        $items = $this->seedItems();
        $treatments = $this->seedTreatments($items);
        $patients = $this->seedPatients();
        $this->seedKunjunganAndRekamMedis($patients);
        $this->seedTransactions($patients, $treatments, $items);
        $this->seedExpenses();
    }

    // =========================================================================
    // SETTINGS
    // =========================================================================
    private function seedSettings(): void
    {
        $settings = [
            ['key' => 'clinic_name',      'value' => 'Klinik Gigi Sehat'],
            ['key' => 'clinic_address',   'value' => 'Jl. Merdeka No. 123, Kebayoran Baru, Jakarta Selatan'],
            ['key' => 'clinic_phone',     'value' => '021-7654321'],
            ['key' => 'doctor_name',      'value' => 'drg. Budi Santoso, Sp.KG'],
            ['key' => 'clinic_email',     'value' => 'info@klinigigisehat.id'],
            ['key' => 'min_stock_alert',  'value' => '5'],
            ['key' => 'tax_percentage',   'value' => '0'],
            ['key' => 'currency',         'value' => 'IDR'],
            ['key' => 'receipt_footer',   'value' => 'Terima kasih telah mempercayakan kesehatan gigi Anda kepada kami.'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], ['value' => $s['value']]);
        }
    }

    // =========================================================================
    // USERS
    // =========================================================================
    private function seedUsers(): void
    {
        User::updateOrCreate(['email' => 'admin@klinikklinik.com'], [
            'name'     => 'drg. Budi Santoso',
            'password' => Hash::make('password'),
        ]);

        User::updateOrCreate(['email' => 'test@example.com'], [
            'name'     => 'Test User',
            'password' => Hash::make('password'),
        ]);
    }

    // =========================================================================
    // ITEMS (Bahan & Obat)
    // =========================================================================
    private function seedItems(): array
    {
        $itemsData = [
            ['name' => 'Composite Resin Anterior (A2)', 'type' => 'bahan', 'unit_use' => 'ml',    'unit_purchase' => 'botol',  'conversion_factor' => 5,   'min_stock_alert' => 3,  'purchase_price' => 120000, 'selling_price' => 180000],
            ['name' => 'Composite Resin Posterior',     'type' => 'bahan', 'unit_use' => 'ml',    'unit_purchase' => 'botol',  'conversion_factor' => 5,   'min_stock_alert' => 3,  'purchase_price' => 120000, 'selling_price' => 180000],
            ['name' => 'Bonding Agent',                 'type' => 'bahan', 'unit_use' => 'ml',    'unit_purchase' => 'botol',  'conversion_factor' => 5,   'min_stock_alert' => 2,  'purchase_price' => 80000,  'selling_price' => 130000],
            ['name' => 'Etsa Asam (Acid Etch)',         'type' => 'bahan', 'unit_use' => 'ml',    'unit_purchase' => 'botol',  'conversion_factor' => 10,  'min_stock_alert' => 2,  'purchase_price' => 60000,  'selling_price' => 95000],
            ['name' => 'Lidocaine + Epinefrin',         'type' => 'obat',  'unit_use' => 'ampul', 'unit_purchase' => 'kotak',  'conversion_factor' => 10,  'min_stock_alert' => 5,  'purchase_price' => 150000, 'selling_price' => 250000],
            ['name' => 'Calcium Hydroxide',             'type' => 'obat',  'unit_use' => 'gram',  'unit_purchase' => 'tube',   'conversion_factor' => 1,   'min_stock_alert' => 3,  'purchase_price' => 35000,  'selling_price' => 60000],
            ['name' => 'NaOCl (Sodium Hypochlorite)',   'type' => 'obat',  'unit_use' => 'ml',    'unit_purchase' => 'botol',  'conversion_factor' => 100, 'min_stock_alert' => 2,  'purchase_price' => 25000,  'selling_price' => 40000],
            ['name' => 'File Endodontik K-File',        'type' => 'bahan', 'unit_use' => 'buah',  'unit_purchase' => 'pak',    'conversion_factor' => 6,   'min_stock_alert' => 3,  'purchase_price' => 120000, 'selling_price' => 200000],
            ['name' => 'Bur Diamond Preparasi',         'type' => 'bahan', 'unit_use' => 'buah',  'unit_purchase' => 'pak',    'conversion_factor' => 5,   'min_stock_alert' => 3,  'purchase_price' => 80000,  'selling_price' => 130000],
            ['name' => 'Articulating Paper',            'type' => 'bahan', 'unit_use' => 'lembar','unit_purchase' => 'pak',    'conversion_factor' => 20,  'min_stock_alert' => 2,  'purchase_price' => 30000,  'selling_price' => 45000],
            ['name' => 'Cotton Roll',                   'type' => 'bahan', 'unit_use' => 'buah',  'unit_purchase' => 'pak',    'conversion_factor' => 50,  'min_stock_alert' => 5,  'purchase_price' => 15000,  'selling_price' => 25000],
            ['name' => 'Alkohol 70%',                   'type' => 'bahan', 'unit_use' => 'ml',    'unit_purchase' => 'botol',  'conversion_factor' => 100, 'min_stock_alert' => 2,  'purchase_price' => 12000,  'selling_price' => 20000],
            ['name' => 'Gloves Latex (Non-Steril)',     'type' => 'bahan', 'unit_use' => 'pasang','unit_purchase' => 'kotak',  'conversion_factor' => 100, 'min_stock_alert' => 10, 'purchase_price' => 50000,  'selling_price' => 80000],
            ['name' => 'Masker Medis 3-Ply',            'type' => 'bahan', 'unit_use' => 'buah',  'unit_purchase' => 'kotak',  'conversion_factor' => 50,  'min_stock_alert' => 10, 'purchase_price' => 30000,  'selling_price' => 50000],
            ['name' => 'Eugenol',                       'type' => 'obat',  'unit_use' => 'ml',    'unit_purchase' => 'botol',  'conversion_factor' => 10,  'min_stock_alert' => 2,  'purchase_price' => 50000,  'selling_price' => 80000],
        ];

        $batchData = [
            ['batch' => 'BTH-2024-001', 'stock' => 8,  'exp' => '2026-12-31'],
            ['batch' => 'BTH-2024-002', 'stock' => 6,  'exp' => '2026-10-31'],
            ['batch' => 'BTH-2024-003', 'stock' => 10, 'exp' => '2027-03-31'],
            ['batch' => 'BTH-2024-004', 'stock' => 15, 'exp' => '2026-08-31'],
            ['batch' => 'BTH-2024-005', 'stock' => 12, 'exp' => '2027-01-15'],
            ['batch' => 'BTH-2024-006', 'stock' => 5,  'exp' => '2026-06-30'],
            ['batch' => 'BTH-2024-007', 'stock' => 20, 'exp' => '2027-06-30'],
            ['batch' => 'BTH-2024-008', 'stock' => 3,  'exp' => '2025-09-30'],  // sudah expired (low stock scenario)
            ['batch' => 'BTH-2024-009', 'stock' => 7,  'exp' => '2027-02-28'],
            ['batch' => 'BTH-2024-010', 'stock' => 25, 'exp' => '2027-04-30'],
            ['batch' => 'BTH-2024-011', 'stock' => 18, 'exp' => '2026-11-30'],
            ['batch' => 'BTH-2024-012', 'stock' => 9,  'exp' => '2026-09-15'],
            ['batch' => 'BTH-2024-013', 'stock' => 40, 'exp' => '2027-05-31'],
            ['batch' => 'BTH-2024-014', 'stock' => 30, 'exp' => '2027-05-31'],
            ['batch' => 'BTH-2024-015', 'stock' => 4,  'exp' => '2026-07-31'],  // hampir expired
        ];

        $createdItems = [];
        foreach ($itemsData as $i => $data) {
            $item = Item::create(array_merge($data, ['is_active' => true]));
            $b    = $batchData[$i];
            ItemBatch::create([
                'item_id'      => $item->id,
                'batch_number' => $b['batch'],
                'stock'        => $b['stock'],
                'expiry_date'  => $b['exp'],
            ]);
            $createdItems[] = $item;
        }

        return $createdItems;
    }

    // =========================================================================
    // TREATMENTS & BOM TEMPLATES
    // =========================================================================
    private function seedTreatments(array $items): array
    {
        $treatmentsData = [
            ['name' => 'Tambal Komposit Anterior',              'category' => 'Restorasi',     'base_price' => 250000],
            ['name' => 'Tambal Komposit Posterior',             'category' => 'Restorasi',     'base_price' => 350000],
            ['name' => 'Scaling / Pembersihan Karang Gigi',    'category' => 'Preventif',     'base_price' => 300000],
            ['name' => 'Pencabutan Gigi Susu',                  'category' => 'Bedah Mulut',   'base_price' => 150000],
            ['name' => 'Pencabutan Gigi Dewasa (Simple)',       'category' => 'Bedah Mulut',   'base_price' => 250000],
            ['name' => 'Perawatan Saluran Akar (PSA)',          'category' => 'Endodontik',    'base_price' => 800000],
            ['name' => 'Pembuatan Mahkota PFM',                 'category' => 'Prostodontik',  'base_price' => 2500000],
            ['name' => 'Bleaching / Pemutihan Gigi',            'category' => 'Estetik',       'base_price' => 1500000],
            ['name' => 'Odontektomi (Gigi Bungsu)',             'category' => 'Bedah Mulut',   'base_price' => 1200000],
            ['name' => 'Konsultasi & Pemeriksaan',              'category' => 'Umum',          'base_price' => 75000],
        ];

        // item indexes: 0=CompositeAnt, 1=CompositePost, 2=Bonding, 3=Etsa, 4=Lidocaine,
        //               5=CaOH, 6=NaOCl, 7=KFile, 8=Bur, 9=ArtPaper, 10=CottonRoll,
        //               11=Alkohol, 12=Gloves, 13=Masker, 14=Eugenol
        $bomConfigs = [
            // Tambal Anterior: composite ant, bonding, etsa, cotton roll, gloves, masker
            0 => [[0,2,'ml'], [2,1,'ml'], [3,1,'ml'], [10,2,'buah'], [12,1,'pasang'], [13,1,'buah']],
            // Tambal Posterior: composite post, bonding, etsa, bur, cotton roll, gloves, masker
            1 => [[1,2,'ml'], [2,1,'ml'], [3,1,'ml'], [8,1,'buah'], [10,2,'buah'], [12,1,'pasang'], [13,1,'buah']],
            // Scaling: cotton roll, alkohol, gloves, masker
            2 => [[10,3,'buah'], [11,10,'ml'], [12,1,'pasang'], [13,1,'buah']],
            // Pencabutan Gigi Susu: lidocaine, cotton roll, gloves, masker
            3 => [[4,1,'ampul'], [10,2,'buah'], [12,1,'pasang'], [13,1,'buah']],
            // Pencabutan Gigi Dewasa: lidocaine, cotton roll, gloves, masker
            4 => [[4,2,'ampul'], [10,2,'buah'], [12,1,'pasang'], [13,1,'buah']],
            // PSA: NaOCl, K-File, CaOH, eugenol, cotton roll, gloves, masker
            5 => [[6,5,'ml'], [7,3,'buah'], [5,1,'gram'], [14,1,'ml'], [10,3,'buah'], [12,1,'pasang'], [13,1,'buah']],
        ];

        $createdTreatments = [];
        foreach ($treatmentsData as $idx => $data) {
            $treatment = Treatment::create(array_merge($data, ['is_active' => true]));
            $createdTreatments[] = $treatment;

            if (isset($bomConfigs[$idx])) {
                $bom = BomTemplate::create([
                    'treatment_id' => $treatment->id,
                    'name'         => 'BOM ' . $treatment->name,
                ]);
                foreach ($bomConfigs[$idx] as [$itemIdx, $qty, $unit]) {
                    BomTemplateItem::create([
                        'bom_template_id' => $bom->id,
                        'item_id'         => $items[$itemIdx]->id,
                        'quantity'        => $qty,
                        'unit'            => $unit,
                    ]);
                }
            }
        }

        return $createdTreatments;
    }

    // =========================================================================
    // PATIENTS
    // =========================================================================
    private function seedPatients(): array
    {
        $patientsData = [
            [
                'nama_lengkap'  => 'Siti Rahayu',
                'nik'           => '3174012505850001',
                'tanggal_lahir' => '1985-05-25',
                'jenis_kelamin' => 'P',
                'golongan_darah'=> 'A',
                'phone'         => '08121234567',
                'email'         => 'siti.rahayu@email.com',
                'alamat'        => 'Jl. Anggrek No. 12, Kebayoran Baru, Jakarta Selatan',
                'pekerjaan'     => 'Guru SD',
                'alergi'        => 'Penisilin',
            ],
            [
                'nama_lengkap'  => 'Budi Prasetyo',
                'nik'           => '3174011503780002',
                'tanggal_lahir' => '1978-03-15',
                'jenis_kelamin' => 'L',
                'golongan_darah'=> 'B',
                'phone'         => '08131234568',
                'email'         => 'budi.prasetyo@email.com',
                'alamat'        => 'Jl. Kenanga No. 5, Cilandak, Jakarta Selatan',
                'pekerjaan'     => 'Wiraswasta',
                'alergi'        => null,
            ],
            [
                'nama_lengkap'  => 'Dewi Lestari',
                'nik'           => '3174015511920003',
                'tanggal_lahir' => '1992-11-15',
                'jenis_kelamin' => 'P',
                'golongan_darah'=> 'O',
                'phone'         => '08151234569',
                'email'         => 'dewi.lestari@email.com',
                'alamat'        => 'Jl. Melati No. 7, Mampang, Jakarta Selatan',
                'pekerjaan'     => 'Karyawan Swasta',
                'alergi'        => null,
            ],
            [
                'nama_lengkap'  => 'Ahmad Fauzi',
                'nik'           => '3174010202950004',
                'tanggal_lahir' => '1995-02-02',
                'jenis_kelamin' => 'L',
                'golongan_darah'=> 'AB',
                'phone'         => '08171234570',
                'email'         => null,
                'alamat'        => 'Jl. Bougenville No. 3, Pesanggrahan, Jakarta Selatan',
                'pekerjaan'     => 'Mahasiswa',
                'alergi'        => 'Ibuprofen',
            ],
            [
                'nama_lengkap'  => 'Rina Kusuma',
                'nik'           => '3174014209880005',
                'tanggal_lahir' => '1988-09-02',
                'jenis_kelamin' => 'P',
                'golongan_darah'=> 'A',
                'phone'         => '08191234571',
                'email'         => 'rina.kusuma@email.com',
                'alamat'        => 'Jl. Dahlia No. 22, Tebet, Jakarta Selatan',
                'pekerjaan'     => 'Dokter Umum',
                'alergi'        => null,
            ],
            [
                'nama_lengkap'  => 'Hendra Wijaya',
                'nik'           => '3174011807750006',
                'tanggal_lahir' => '1975-07-18',
                'jenis_kelamin' => 'L',
                'golongan_darah'=> 'O',
                'phone'         => '08211234572',
                'email'         => 'hendra.w@email.com',
                'alamat'        => 'Jl. Cempaka No. 9, Pasar Minggu, Jakarta Selatan',
                'pekerjaan'     => 'PNS',
                'alergi'        => null,
            ],
            [
                'nama_lengkap'  => 'Indah Permata',
                'nik'           => '3174016203000007',
                'tanggal_lahir' => '2000-03-22',
                'jenis_kelamin' => 'P',
                'golongan_darah'=> 'B',
                'phone'         => '08231234573',
                'email'         => 'indah.p@email.com',
                'alamat'        => 'Jl. Flamboyan No. 14, Jagakarsa, Jakarta Selatan',
                'pekerjaan'     => 'Mahasiswa',
                'alergi'        => null,
            ],
            [
                'nama_lengkap'  => 'Joko Susilo',
                'nik'           => '3174010505680008',
                'tanggal_lahir' => '1968-05-05',
                'jenis_kelamin' => 'L',
                'golongan_darah'=> 'A',
                'phone'         => '08251234574',
                'email'         => null,
                'alamat'        => 'Jl. Mangga No. 1, Kebagusan, Jakarta Selatan',
                'pekerjaan'     => 'Pensiunan',
                'alergi'        => 'Sulfa',
            ],
            [
                'nama_lengkap'  => 'Ani Suryani',
                'nik'           => '3174012810820009',
                'tanggal_lahir' => '1982-10-28',
                'jenis_kelamin' => 'P',
                'golongan_darah'=> 'O',
                'phone'         => '08271234575',
                'email'         => 'ani.suryani@email.com',
                'alamat'        => 'Jl. Rambutan No. 6, Cilandak, Jakarta Selatan',
                'pekerjaan'     => 'Ibu Rumah Tangga',
                'alergi'        => null,
            ],
            [
                'nama_lengkap'  => 'Farhan Rizky',
                'nik'           => '3174011201970010',
                'tanggal_lahir' => '1997-01-12',
                'jenis_kelamin' => 'L',
                'golongan_darah'=> 'B',
                'phone'         => '08291234576',
                'email'         => 'farhan.r@email.com',
                'alamat'        => 'Jl. Nangka No. 17, Mampang, Jakarta Selatan',
                'pekerjaan'     => 'Karyawan Swasta',
                'alergi'        => null,
            ],
        ];

        $createdPatients = [];
        foreach ($patientsData as $data) {
            $noRm   = Patient::generateNoRM();
            $patient = Patient::create(array_merge($data, [
                'no_rm' => $noRm,
                'name'  => $data['nama_lengkap'],
                'fotos' => null,
            ]));
            $createdPatients[] = $patient;
        }

        return $createdPatients;
    }

    // =========================================================================
    // KUNJUNGAN & REKAM MEDIS
    // =========================================================================
    private function seedKunjunganAndRekamMedis(array $patients): void
    {
        $kunjunganData = [
            // [patient_index, tanggal, keluhan, status, tekanan_darah, nadi]
            [0, '2026-04-01', 'Gigi berlubang dan ngilu saat minum air dingin', 'selesai', '120/80', 72],
            [1, '2026-04-03', 'Ingin pembersihan karang gigi rutin', 'selesai', '130/85', 78],
            [2, '2026-04-08', 'Gigi terasa goyang dan gusi berdarah', 'selesai', '110/70', 68],
            [3, '2026-04-10', 'Sakit gigi bawah sebelah kiri, berdenyut-denyut', 'selesai', '125/82', 80],
            [4, '2026-04-15', 'Ingin konsultasi pemasangan kawat gigi', 'selesai', '115/75', 70],
            [5, '2026-04-17', 'Gigi depan patah karena kecelakaan', 'selesai', '135/88', 82],
            [6, '2026-04-22', 'Gigi belakang kiri atas berlubang besar', 'selesai', '118/76', 74],
            [7, '2026-04-25', 'Gusi bengkak dan ada abses di area gigi geraham', 'selesai', '140/90', 88],
            [8, '2026-05-02', 'Pemeriksaan rutin dan tambal gigi', 'selesai', '112/72', 69],
            [9, '2026-05-05', 'Gigi bungsu tumbuh miring dan sakit', 'selesai', '122/80', 76],
            [0, '2026-05-10', 'Kontrol tambal gigi sebelumnya', 'selesai', '118/78', 71],
            [2, '2026-05-12', 'Keluhan gusi masih berdarah setelah scaling', 'selesai', '108/68', 66],
            [4, '2026-05-15', 'Kontrol & pembersihan gigi rutin', 'selesai', '117/75', 70],
            [1, '2026-05-16', 'Sakit gigi molar kanan bawah', 'antrian', '128/84', 75],
            [6, '2026-05-17', 'Sakit gigi geraham kiri atas', 'sedang_diperiksa', '119/77', 73],
            [3, '2026-05-17', 'Kontrol PSA', 'antrian', '126/81', 79],
            [8, '2026-05-17', 'Gigi berlubang anterior atas', 'antrian', '110/70', 68],
            [5, '2026-05-17', 'Scaling rutin', 'antrian', '133/86', 81],
        ];

        $odontogramSamples = [
            ['16' => ['kondisi' => 'karies',   'surfaces' => ['O', 'D'], 'catatan' => ''],
             '26' => ['kondisi' => 'tambalan', 'surfaces' => ['O'],      'catatan' => 'tambalan lama'],
             '46' => ['kondisi' => 'karies',   'surfaces' => ['M', 'O'], 'catatan' => '']],

            ['14' => ['kondisi' => 'tambalan', 'surfaces' => ['O'],      'catatan' => ''],
             '24' => ['kondisi' => 'tambalan', 'surfaces' => ['O'],      'catatan' => ''],
             '36' => ['kondisi' => 'missing',  'surfaces' => [],         'catatan' => 'sudah dicabut 2022']],

            ['11' => ['kondisi' => 'karies',   'surfaces' => ['V'],      'catatan' => 'karies servikal'],
             '21' => ['kondisi' => 'karies',   'surfaces' => ['V'],      'catatan' => 'karies servikal'],
             '46' => ['kondisi' => 'tambalan', 'surfaces' => ['O', 'D'], 'catatan' => '']],

            ['36' => ['kondisi' => 'karies',   'surfaces' => ['O', 'M', 'D'], 'catatan' => 'karies profunda'],
             '46' => ['kondisi' => 'sisa_akar','surfaces' => [],             'catatan' => 'perlu pencabutan']],

            ['17' => ['kondisi' => 'tambalan', 'surfaces' => ['O'],      'catatan' => ''],
             '27' => ['kondisi' => 'tambalan', 'surfaces' => ['O'],      'catatan' => ''],
             '37' => ['kondisi' => 'tambalan', 'surfaces' => ['O', 'D'], 'catatan' => ''],
             '47' => ['kondisi' => 'tambalan', 'surfaces' => ['O', 'M'], 'catatan' => '']],

            ['11' => ['kondisi' => 'fraktur',  'surfaces' => [],         'catatan' => 'fraktur horizontal 1/3 mahkota'],
             '21' => ['kondisi' => 'karies',   'surfaces' => ['M'],      'catatan' => '']],

            ['16' => ['kondisi' => 'karies',   'surfaces' => ['O', 'M', 'D', 'L'], 'catatan' => 'indikasi PSA'],
             '46' => ['kondisi' => 'missing',  'surfaces' => [],                   'catatan' => '']],

            ['38' => ['kondisi' => 'missing',  'surfaces' => [],         'catatan' => 'dicabut'],
             '48' => ['kondisi' => 'implant',  'surfaces' => [],         'catatan' => 'implant 2023'],
             '17' => ['kondisi' => 'mahkota',  'surfaces' => [],         'catatan' => 'crown PFM 2022']],

            ['15' => ['kondisi' => 'tambalan', 'surfaces' => ['O'],      'catatan' => ''],
             '25' => ['kondisi' => 'karies',   'surfaces' => ['O'],      'catatan' => 'karies superfisial']],

            ['48' => ['kondisi' => 'karies',   'surfaces' => ['M'],      'catatan' => 'gigi bungsu impaksi mesioangular'],
             '38' => ['kondisi' => 'karies',   'surfaces' => ['M'],      'catatan' => 'gigi bungsu impaksi']],

            ['16' => ['kondisi' => 'tambalan', 'surfaces' => ['O', 'D'], 'catatan' => 'kontrol tambal baru']],

            ['11' => ['kondisi' => 'tambalan', 'surfaces' => ['V'],      'catatan' => 'kontrol karies servikal']],

            ['17' => ['kondisi' => 'tambalan', 'surfaces' => ['O'],      'catatan' => ''],
             '27' => ['kondisi' => 'tambalan', 'surfaces' => ['O'],      'catatan' => '']],
        ];

        $rekamMedisData = [
            // [kunjungan_index, diagnosis, tindakan, resep, catatan, odontogram_index]
            [0,  'Karies Media Gigi 16',                   'Preparasi kavitas dan penumpatan komposit gigi 16',          'Asam Mefenamat 500mg 3x1 tab (jika nyeri)',    'Kontrol 1 minggu',            0],
            [1,  'Kalkulus Supragingiva Generalisata',      'Scaler ultrasonik, profilaksis dengan pumice',               null,                                           'OHI diberikan',               1],
            [2,  'Gingivitis Marginalis Kronis',            'Scaling dan root planning, irigasi CHX 0.2%',               'CHX Mouthwash 2x sehari',                      'Jadwal kontrol 2 minggu',     2],
            [3,  'Karies Profunda Gigi 36 Non-vital',      'Inisiasi PSA gigi 36 — pembukaan akses, nekrotomi pulpa',   'Amoksisilin 500mg 3x1, Asam Mefenamat 500mg', 'Kunjungan PSA sesi 2',        3],
            [4,  'Maloklusi Klas I',                       'Konsultasi ortodontik, foto panoramik dirujuk',              null,                                           'Dirujuk ke Sp.Ort',           4],
            [5,  'Fraktur Mahkota Gigi 11 Ellis Klas II', 'Direct composite build-up gigi 11',                         'Asam Mefenamat 500mg 3x1 (jika nyeri)',        'Hindari gigit keras 24 jam',  5],
            [6,  'Karies Profunda Gigi 16',                'Preparasi kavitas luas, penumpatan komposit posterior 16',   'Asam Mefenamat 500mg prn',                    'Pertimbangkan PSA jika nyeri', 6],
            [7,  'Abses Periapikalis Gigi 37',             'I&D abses intraoral, pencabutan gigi 37, irigasi NaCl 0.9%','Amoksisilin 500mg 3x1, Metronidazol 500mg',  'Jahitan dilepas 5 hari',      7],
            [8,  'Karies Media Gigi 25',                   'Preparasi kavitas dan penumpatan komposit 25',               'Tidak ada',                                    'Kontrol 1 minggu',            8],
            [9,  'Impaksi Gigi 48 Posisi Mesioangular',   'Konsultasi odontektomi, foto panoramik',                     'Asam Mefenamat 500mg 3x1',                    'Jadwal odontektomi minggu depan', 9],
            [10, 'Kontrol Tambal Gigi 16 — Baik',         'Pemeriksaan oklusal, polishing permukaan tambalan',          null,                                           'Pasien puas, tidak ada keluhan', 10],
            [11, 'Gingivitis Residual Pasca Scaling',      'Scaling area interproksimal, irigasi CHX',                  'CHX Mouthwash lanjut 1 minggu',               'OHI ulang',                   11],
            [12, 'Gigi 17,27 — Kalkulus Ringan',          'Scaling supragingiva, profilaksis',                         null,                                           'Rutin scaling 6 bulan sekali', 12],
        ];

        $kunjunganObjects = [];
        foreach ($kunjunganData as $i => $row) {
            [$patientIdx, $tanggal, $keluhan, $status, $td, $nadi] = $row;
            $kunjungan = Kunjungan::create([
                'patient_id'       => $patients[$patientIdx]->id,
                'tanggal_kunjungan'=> $tanggal,
                'keluhan_utama'    => $keluhan,
                'anamnesis'        => 'Pasien datang dengan keluhan ' . lcfirst($keluhan) . '. Riwayat penyakit sistemik disangkal.',
                'tekanan_darah'    => $td,
                'nadi'             => $nadi,
                'status'           => $status,
            ]);
            $kunjunganObjects[] = $kunjungan;
        }

        foreach ($rekamMedisData as [$kunjIdx, $diag, $tindakan, $resep, $catatan, $odoIdx]) {
            $kunjungan = $kunjunganObjects[$kunjIdx];
            RekamMedis::create([
                'kunjungan_id'  => $kunjungan->id,
                'patient_id'    => $kunjungan->patient_id,
                'diagnosis'     => $diag,
                'tindakan'      => $tindakan,
                'resep'         => $resep,
                'catatan'       => $catatan,
                'odontogram_data' => $odontogramSamples[$odoIdx] ?? null,
                'foto'          => null,
            ]);
        }
    }

    // =========================================================================
    // TRANSACTIONS
    // =========================================================================
    private function seedTransactions(array $patients, array $treatments, array $items): void
    {
        $txData = [
            // [patient_index, date, treatment_indexes, item_data, payment_method]
            [0, '2026-04-01', [0],    [[0,2,180000]], 'tunai'],
            [1, '2026-04-03', [2],    [[10,3,25000],[11,10,20000]], 'tunai'],
            [2, '2026-04-08', [2],    [[10,3,25000],[12,1,80000]], 'transfer_bank'],
            [3, '2026-04-10', [5],    [[6,5,40000],[7,3,200000],[5,1,60000]], 'tunai'],
            [4, '2026-04-15', [9],    [], 'tunai'],
            [5, '2026-04-17', [0],    [[0,2,180000],[2,1,130000],[3,1,95000]], 'transfer_bank'],
            [6, '2026-04-22', [1],    [[1,2,180000],[2,1,130000],[8,1,130000]], 'tunai'],
            [7, '2026-04-25', [4],    [[4,2,250000],[10,2,25000]], 'tunai'],
            [8, '2026-05-02', [0],    [[0,2,180000],[3,1,95000]], 'qris'],
            [9, '2026-05-05', [9],    [], 'tunai'],
            [0, '2026-05-10', [9],    [], 'tunai'],
            [2, '2026-05-12', [2],    [[10,2,25000],[11,5,20000]], 'transfer_bank'],
            [4, '2026-05-15', [2],    [[12,1,80000],[13,1,50000]], 'qris'],
        ];

        foreach ($txData as $idx => $row) {
            [$patientIdx, $date, $treatmentIdxs, $itemRows, $payMethod] = $row;

            $treatmentTotal = 0;
            foreach ($treatmentIdxs as $ti) {
                $treatmentTotal += $treatments[$ti]->base_price;
            }

            $itemTotal = 0;
            foreach ($itemRows as [$ii, $qty, $price]) {
                $itemTotal += $qty * $price;
            }

            $subtotal = $treatmentTotal + $itemTotal;
            $amountPaid = $subtotal + ($payMethod === 'tunai' ? mt_rand(0, 5) * 1000 : 0);

            $txNum = 'TRX-' . str_replace('-', '', $date) . '-' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT);

            $tx = Transaction::create([
                'transaction_number' => $txNum,
                'patient_id'         => $patients[$patientIdx]->id,
                'date'               => $date,
                'subtotal'           => $subtotal,
                'total_amount'       => $subtotal,
                'status'             => 'completed',
                'payment_method'     => $payMethod,
                'amount_paid'        => $amountPaid,
                'change_amount'      => $amountPaid - $subtotal,
            ]);

            foreach ($treatmentIdxs as $ti) {
                TransactionTreatment::create([
                    'transaction_id' => $tx->id,
                    'treatment_id'   => $treatments[$ti]->id,
                    'price'          => $treatments[$ti]->base_price,
                ]);
            }

            foreach ($itemRows as [$ii, $qty, $price]) {
                TransactionItem::create([
                    'transaction_id' => $tx->id,
                    'item_id'        => $items[$ii]->id,
                    'item_type'      => $items[$ii]->type,
                    'quantity'       => $qty,
                    'price'          => $price,
                    'subtotal'       => $qty * $price,
                ]);

                $batch = $items[$ii]->batches()->first();
                if ($batch) {
                    StockMutation::create([
                        'item_id'                => $items[$ii]->id,
                        'item_batch_id'          => $batch->id,
                        'type'                   => 'out',
                        'quantity'               => $qty,
                        'reason'                 => 'Penjualan ' . $txNum,
                        'reference_transaction_id' => $tx->id,
                    ]);
                }
            }
        }
    }

    // =========================================================================
    // EXPENSES
    // =========================================================================
    private function seedExpenses(): void
    {
        $expenses = [
            ['date' => '2026-04-01', 'name' => 'Pembelian Gloves & Masker',        'description' => 'Restock APD bulanan',                    'amount' => 250000],
            ['date' => '2026-04-05', 'name' => 'Tagihan Listrik',                   'description' => 'Tagihan listrik bulan Maret 2026',        'amount' => 580000],
            ['date' => '2026-04-10', 'name' => 'Pembelian Composite Resin',         'description' => 'Restock bahan tambal 2 botol anterior, 2 botol posterior', 'amount' => 480000],
            ['date' => '2026-04-20', 'name' => 'Servis Kompresor Dental Unit',      'description' => 'Perawatan kompresor rutin 3 bulanan',      'amount' => 350000],
            ['date' => '2026-05-01', 'name' => 'Tagihan Listrik',                   'description' => 'Tagihan listrik bulan April 2026',         'amount' => 610000],
            ['date' => '2026-05-05', 'name' => 'Pembelian Lidocaine',               'description' => 'Restock anestesi 2 kotak',                 'amount' => 300000],
            ['date' => '2026-05-10', 'name' => 'Cetak Kartu Pasien & ATK',         'description' => 'Kertas, pulpen, kartu RM baru',            'amount' => 120000],
        ];

        foreach ($expenses as $e) {
            Expense::create($e);
        }
    }
}
