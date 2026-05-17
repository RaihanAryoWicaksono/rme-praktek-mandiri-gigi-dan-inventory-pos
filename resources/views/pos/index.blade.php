<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('POS / Kasir Baru') }}
        </h2>
    </x-slot>

    <div x-data="posSystem()">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            <!-- Left Column: Form Input -->
            <div class="lg:col-span-8">
                <div class="bg-white/70 backdrop-blur-lg border border-white/40 shadow-xl rounded-2xl overflow-hidden">

                    <!-- Section 1: Identitas Pasien -->
                    <div class="p-5 border-b border-gray-100">
                        <label class="flex items-center gap-2 text-sm font-bold text-teal-700 mb-3">
                            <span class="w-5 h-5 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-black">1</span>
                            Identitas Pasien
                        </label>
                        <select x-model="patient_id" class="w-full border-gray-200 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm bg-white/80 text-sm">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->no_rm }} — {{ $patient->name }} @if($patient->phone) ({{ $patient->phone }}) @endif</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Section 2: Tindakan & Bahan -->
                    <div class="p-5 border-b border-gray-100">
                        <label class="flex items-center gap-2 text-sm font-bold text-teal-700 mb-3">
                            <span class="w-5 h-5 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-black">2</span>
                            Tindakan &amp; Bahan Terpakai
                        </label>

                        <div class="flex gap-2 mb-4">
                            <div class="flex-1">
                                <select x-model="selected_treatment_id" @change="selected_template_id = ''" class="w-full border-gray-200 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm bg-white/80 text-sm">
                                    <option value="">-- Tambah Tindakan --</option>
                                    @foreach($treatments as $tr)
                                        <option value="{{ $tr->id }}">{{ $tr->name }} (Rp {{ number_format($tr->base_price, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1" style="display: none;" x-show="available_templates.length > 0">
                                <select x-model="selected_template_id" class="w-full border-gray-200 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm bg-white/80 text-sm">
                                    <option value="">-- Pilih Variasi Bahan (Wajib) --</option>
                                    <template x-for="tmpl in available_templates" :key="tmpl.id">
                                        <option :value="tmpl.id" x-text="tmpl.name"></option>
                                    </template>
                                </select>
                            </div>
                            <button @click="addTreatment()" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-bold text-sm whitespace-nowrap">+ Tambah</button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(tr, trIdx) in cart_treatments" :key="trIdx">
                                <div class="bg-white border border-teal-100 rounded-xl overflow-hidden shadow-sm">
                                    <div class="flex justify-between items-center bg-teal-50 px-4 py-2.5">
                                        <span class="font-bold text-teal-900 text-sm">
                                            <span x-text="tr.name"></span>
                                            <span x-show="tr.template_name" class="ml-2 px-2 py-0.5 bg-teal-100 text-teal-800 text-xs rounded" x-text="tr.template_name"></span>
                                        </span>
                                        <div class="flex items-center gap-3">
                                            <span class="font-bold text-gray-900 text-sm" x-text="formatCurrency(tr.price)"></span>
                                            <button @click="removeTreatment(trIdx)" class="text-red-400 hover:text-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="px-4 py-3 space-y-2">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pemakaian Bahan:</p>
                                        <template x-for="(item, itemIdx) in cart_items.filter(i => i.source_treatment_idx === trIdx)" :key="itemIdx">
                                            <div class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                                                <div>
                                                    <span class="text-sm" :class="item.is_out_of_stock ? 'text-gray-400 italic' : 'text-gray-700'" x-text="item.name"></span>
                                                    <span x-show="item.is_out_of_stock" class="ml-2 px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-black rounded uppercase">Stok Habis</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs text-gray-400">Rp</span>
                                                    <input type="number" step="1" x-model="item.price" :disabled="item.is_out_of_stock" class="w-24 text-right p-1 border-gray-200 rounded text-sm font-bold text-teal-600 disabled:bg-gray-100 disabled:text-gray-400" title="Harga Satuan">
                                                    <span class="text-xs text-gray-400">x</span>
                                                    <input type="number" step="0.01" x-model="item.quantity" :disabled="item.is_out_of_stock" class="w-16 text-right p-1 border-gray-200 rounded text-sm font-bold disabled:bg-gray-100 disabled:text-gray-400">
                                                    <span class="text-xs text-gray-400 w-8" x-text="item.unit"></span>
                                                    <button @click="removeItem(item.temp_id)" class="text-gray-300 hover:text-red-500 text-lg leading-none">&times;</button>
                                                </div>
                                            </div>
                                        </template>
                                        <div x-show="cart_items.filter(i => i.source_treatment_idx === trIdx).length === 0" class="text-xs text-gray-400 italic">Tidak ada bahan terdaftar.</div>

                                        <div class="flex gap-2 pt-1" x-data="{ local_item_id: '' }">
                                            <select x-model="local_item_id" class="flex-1 text-xs border-gray-200 focus:border-teal-500 focus:ring-teal-500 rounded bg-white py-1.5 px-2">
                                                <option value="">+ Tambah Item Tambahan</option>
                                                <template x-for="it in items_master.filter(i => ['bahan', 'produk', 'obat'].includes(i.type))" :key="it.id">
                                                    <option :value="it.id"
                                                            :disabled="parseFloat(it.batches_sum_stock || 0) <= 0"
                                                            x-text="`${it.name} (${it.unit_use}) ${parseFloat(it.batches_sum_stock || 0) <= 0 ? '[HABIS]' : ''}`">
                                                    </option>
                                                </template>
                                            </select>
                                            <button @click="if(local_item_id) { addMaterialToTreatment(trIdx, local_item_id); local_item_id = ''; }" class="px-3 py-1 bg-teal-100 text-teal-700 rounded text-xs font-bold hover:bg-teal-200 transition">Tambahkan</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Section 3: Obat / Produk Tambahan -->
                    <div class="p-5">
                        <label class="flex items-center gap-2 text-sm font-bold text-teal-700 mb-3">
                            <span class="w-5 h-5 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-black">3</span>
                            Obat / Produk / Bahan Tambahan
                        </label>
                        <div class="flex gap-2 mb-3">
                            <div class="flex-1">
                                <select x-model="selected_item_id" class="w-full border-gray-200 focus:border-teal-500 focus:ring-teal-500 rounded-lg shadow-sm bg-white/80 text-sm">
                                    <option value="">-- Tambah Obat/Produk/Bahan --</option>
                                    @foreach($items as $it)
                                        <option value="{{ $it->id }}" {{ $it->batches_sum_stock <= 0 ? 'disabled' : '' }}>
                                            {{ $it->name }} ({{ $it->unit_use }})
                                            @if($it->batches_sum_stock <= 0) [STOK HABIS] @else (Sisa: {{ (float)$it->batches_sum_stock }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button @click="addManualItem()" class="px-4 py-2 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition font-bold text-sm whitespace-nowrap">+ Tambah</button>
                        </div>

                        <div class="space-y-2">
                            <template x-for="(item, idx) in cart_items.filter(i => i.source_treatment_idx === null)" :key="idx">
                                <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200 shadow-sm" :class="item.is_out_of_stock ? 'opacity-60 bg-gray-50' : ''">
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-900 text-sm" x-text="item.name"></p>
                                        <template x-if="item.is_out_of_stock">
                                            <span class="text-[10px] font-black text-red-600 uppercase">Stok Habis</span>
                                        </template>
                                    </div>
                                    <div class="w-32">
                                        <input type="number" x-model="item.price" :disabled="item.is_out_of_stock" placeholder="Harga" class="w-full text-right p-1 border-gray-200 rounded text-sm font-bold disabled:bg-gray-100">
                                    </div>
                                    <div class="flex items-center gap-1 w-24">
                                        <input type="number" step="1" x-model="item.quantity" :disabled="item.is_out_of_stock" class="w-full text-right p-1 border-gray-200 rounded text-sm font-bold disabled:bg-gray-100">
                                        <span class="text-xs text-gray-400 w-8 shrink-0" x-text="item.unit"></span>
                                    </div>
                                    <button @click="removeItem(item.temp_id)" class="text-red-400 hover:text-red-600 text-xl leading-none">&times;</button>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- Recent Transactions Table -->
                <div class="mt-4">
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-900">5 Transaksi Terbaru</h3>
                            <a href="{{ route('transactions.index') }}" class="text-xs text-teal-600 font-semibold hover:text-teal-800">Lihat Semua</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($recentTransactions as $trx)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-5 py-3 whitespace-nowrap">
                                                <span class="text-sm font-bold text-teal-600">{{ $trx->transaction_number }}</span>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500">
                                                {{ $trx->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap">
                                                <p class="text-sm text-gray-900 font-medium">{{ $trx->patient->name }}</p>
                                                <p class="text-xs font-mono text-teal-600">{{ $trx->patient->no_rm }}</p>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm font-black text-gray-900 text-right">
                                                Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap text-center text-sm font-medium">
                                                <a href="{{ route('transactions.show', $trx->id) }}" class="text-teal-600 hover:text-teal-900 bg-teal-50 px-3 py-1 rounded-md transition">Detail</a>
                                                <a href="{{ route('transactions.print', $trx->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition ml-2">Cetak</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-5 py-4 text-sm text-gray-400 text-center italic">
                                                Belum ada transaksi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Payment Summary -->
            <div class="lg:col-span-4 h-fit sticky top-4">
                <div class="bg-teal-600 text-white shadow-2xl rounded-2xl overflow-hidden relative">
                    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full pointer-events-none"></div>

                    <div class="p-5 border-b border-teal-500">
                        <h3 class="text-base font-bold">Ringkasan Pembayaran</h3>
                    </div>

                    <div class="p-5 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-teal-100">Subtotal</span>
                            <span class="font-bold" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="flex justify-between items-end border-t border-teal-500 pt-3">
                            <span class="text-teal-100 text-sm">Total Akhir</span>
                            <span class="text-2xl font-black" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>

                    <div class="px-5 pb-5 space-y-3">
                        <div class="bg-teal-700/50 p-4 rounded-xl border border-teal-500 space-y-3">
                            <div>
                                <label class="block text-[10px] font-bold text-teal-200 uppercase tracking-widest mb-2">Metode Pembayaran</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="payment_method" value="cash" class="sr-only peer">
                                        <div class="py-2 text-center rounded-lg border border-teal-400 text-sm font-bold peer-checked:bg-white peer-checked:text-teal-700 hover:bg-teal-500 transition">CASH</div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="payment_method" value="qris" class="sr-only peer">
                                        <div class="py-2 text-center rounded-lg border border-teal-400 text-sm font-bold peer-checked:bg-white peer-checked:text-teal-700 hover:bg-teal-500 transition">QRIS</div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-teal-200 uppercase tracking-widest mb-1">Nominal Bayar</label>
                                <input type="number" x-model="amount_paid" @input="calculateChange()" class="w-full text-right p-2 border-none rounded-lg bg-white/20 focus:bg-white/30 focus:ring-0 text-white font-black text-xl placeholder-teal-300" placeholder="0">
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-teal-200 font-bold uppercase tracking-widest text-[10px]">Kembalian</span>
                                <span class="font-black text-white text-lg" :class="change < 0 ? 'text-rose-300' : ''" x-text="formatCurrency(change)"></span>
                            </div>
                        </div>

                        <button @click="submitTransaction()"
                                :disabled="busy || cart_treatments.length === 0 || !patient_id || amount_paid < total"
                                class="w-full py-3.5 bg-white text-teal-700 rounded-xl font-bold shadow-lg hover:bg-teal-50 transition active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <span x-show="!busy">SIMPAN TRANSAKSI</span>
                            <span x-show="busy">Memproses...</span>
                        </button>
                        <a href="{{ route('pos.index') }}" class="block text-center text-teal-200 text-sm hover:text-white transition">Batalkan</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function posSystem() {
            return {
                busy: false,
                patient_id: '',
                date: '{{ date('Y-m-d') }}',
                selected_treatment_id: '',
                selected_template_id: '',
                selected_item_id: '',

                
                treatments_master: @json($treatments),
                items_master: @json($items),
                
                cart_treatments: [],
                cart_items: [],
                payment_method: 'cash',
                amount_paid: 0,
                change: 0,
                
                formatCurrency(val) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
                },

                get subtotal() {
                    let totalT = this.cart_treatments.reduce((sum, t) => sum + parseFloat(t.price), 0);
                    let totalI = this.cart_items.reduce((sum, i) => sum + (parseFloat(i.price) * parseFloat(i.quantity)), 0);
                    return totalT + totalI;
                },

                get total() {
                    return this.subtotal;
                },

                calculateChange() {
                    this.change = parseFloat(this.amount_paid || 0) - this.total;
                },

                get available_templates() {
                    if (!this.selected_treatment_id) return [];
                    const master = this.treatments_master.find(t => t.id == this.selected_treatment_id);
                    return master && master.bom_templates ? master.bom_templates : [];
                },

                addTreatment() {
                    if (!this.selected_treatment_id) return;
                    
                    const master = this.treatments_master.find(t => t.id == this.selected_treatment_id);
                    if (!master) return;

                    let templateToUse = null;
                    if (master.bom_templates && master.bom_templates.length > 0) {
                        if (master.bom_templates.length === 1) {
                            templateToUse = master.bom_templates[0];
                        } else {
                            if (!this.selected_template_id) {
                                alert("Tindakan ini memiliki beberapa variasi bahan. Silakan pilih Variasi Bahan terlebih dahulu.");
                                return;
                            }
                            templateToUse = master.bom_templates.find(t => t.id == this.selected_template_id);
                        }
                    }

                    const currentIdx = this.cart_treatments.length;
                    
                    // Add treatment to cart
                    this.cart_treatments.push({
                        id: master.id,
                        name: master.name,
                        price: master.base_price,
                        template_name: templateToUse ? templateToUse.name : null
                    });

                    // Load BOM Items
                    if (templateToUse) {
                        templateToUse.items.forEach(bomItem => {
                            const currentStock = parseFloat(bomItem.item.batches_sum_stock || 0);
                            this.cart_items.push({
                                temp_id: Date.now() + Math.random(),
                                item_id: bomItem.item_id,
                                name: bomItem.item.name,
                                quantity: currentStock > 0 ? bomItem.quantity : 0,
                                price: parseFloat(bomItem.item.selling_price) || 0,
                                unit: bomItem.unit,
                                source_treatment_idx: currentIdx,
                                is_out_of_stock: currentStock <= 0
                            });
                        });
                    }

                    this.selected_treatment_id = '';
                    this.selected_template_id = '';
                },

                removeTreatment(idx) {
                    this.cart_treatments.splice(idx, 1);
                    // Also remove items associated with this treatment
                    // Note: indexes shift, so we need careful cleaning or use stable IDs
                    this.cart_items = this.cart_items.filter(i => i.source_treatment_idx !== idx);
                    // Shift other source indexes down
                    this.cart_items.forEach(i => {
                        if (i.source_treatment_idx > idx) i.source_treatment_idx--;
                    });
                },

                addManualItem() {
                    if (!this.selected_item_id) return;
                    const item = this.items_master.find(i => i.id == this.selected_item_id);
                    if (!item) return;

                    this.cart_items.push({
                        temp_id: Date.now() + Math.random(),
                        item_id: item.id,
                        name: item.name,
                        quantity: 1,
                        price: parseFloat(item.selling_price) || 0, 
                        unit: item.unit_use,
                        source_treatment_idx: null,
                        is_out_of_stock: parseFloat(item.batches_sum_stock || 0) <= 0
                    });
                    this.selected_item_id = '';
                },

                removeItem(tempId) {
                    this.cart_items = this.cart_items.filter(i => i.temp_id !== tempId);
                },

                addMaterialToTreatment(trIdx, itemId) {
                    const item = this.items_master.find(i => i.id == itemId);
                    if (!item) return;

                    this.cart_items.push({
                        temp_id: Date.now() + Math.random(),
                        item_id: item.id,
                        name: item.name,
                        quantity: 1,
                        price: parseFloat(item.selling_price) || 0,
                        unit: item.unit_use,
                        source_treatment_idx: trIdx,
                        is_out_of_stock: parseFloat(item.batches_sum_stock || 0) <= 0
                    });
                },

                async submitTransaction() {
                    if (this.busy) return;
                    this.busy = true;

                    try {
                        const response = await fetch('{{ route('pos.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                patient_id: this.patient_id,
                                date: this.date,

                                payment_method: this.payment_method,
                                amount_paid: this.amount_paid,
                                treatments: this.cart_treatments,
                                items: this.cart_items
                            })
                        });

                        const result = await response.json();
                        if (result.success) {
                            window.location.href = result.redirect;
                        } else {
                            alert(result.message || 'Terjadi kesalahan.');
                            this.busy = false;
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Gagal menyimpan transaksi.');
                        this.busy = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>
