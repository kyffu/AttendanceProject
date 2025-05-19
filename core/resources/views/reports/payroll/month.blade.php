<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;" class="card-header pb-0">
        <h5 class="">Laporan Gaji <strong>{{ $username }}</strong></h5>
    </div>
    <div class="card-body">
        <div class="accordion mt-3" id="accordionExample">
            @php
                setLocale(LC_TIME, 'IND');
            @endphp
            <table class="table">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($months as $month)
                        <tr>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $year . '-' . $month->month . '-01')->formatLocalized('%B %Y') }}
                            </td>
                            <td>
                                 
                                @role(['superadmin', 'spv', 'mandor'])
                                <button type="button" class="btn btn-icon btn-dark btn-allow" data-bs-toggle="modal"
                                    data-bs-target="#modalAllowance"
                                    data-payroll="{{ Crypt::encryptString($year . '-' . $month->month . '/' . $user) }}">
                                    <span class="tf-icons bx bx-money"></span>
                                </button>
                                @endrole
                                 
                                <button type="button" class="btn btn-icon btn-primary btn-payroll"
                                    data-bs-toggle="modal" data-bs-target="#modalPayroll"
                                    data-payroll="{{ Crypt::encryptString($year . '-' . $month->month . '/' . $user) }}">
                                    <span class="tf-icons bx bx-show"></span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="card accordion-item">
                <h2 class="accordion-header" id="headingOne">

                </h2>
            </div>

        </div>
    </div>
</div>
</div>
 
<div class="modal fade" id="modalAllowance" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{route('report.payroll.allow')}}" method="POST">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Tunjangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <input type="hidden" name="payroll" id="allow">
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameWithTitle" class="form-label">Beri Tunjangan</label>
                            <select name="allow" id="" class="form-select" required>
                                <option value="">Pilih Tunjangan</option>
                                @foreach ($allowances as $allowance)
                                    <option value="{{Crypt::encryptString($allowance->id)}}" data-wolla="{{ $allowance->id }}" {{ ($allowance->quota - $allowance->used_quota) <= 0 ? 'disabled' : '' }}>{{$allowance->name}} - Rp{{number_format($allowance->amount, 0, ",", ".")}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Tunjangan</button>
                </div>
            </form>
        </div>
    </div>
</div>
 
<div class="modal fade" id="modalPayroll" tabindex="-1" style="display: none;" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header mb-0">
                <h5 class="modal-title" id="modalCenterTitle">Lihat Laporan Gaji</h5>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                    aria-label="Close">Tutup</button>
            </div>
            <hr>
            <div class="modal-body">
                <div class="text-center" id="spinner-proll" style="display: none;">
                    <div class="spinner-border spinner-border-lg text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="preport"></div>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" id="btn-print" class="btn btn-primary" data-payroll="">Cetak</button> --}}
                <button type="button" class="btn btn-dark" onclick="printDivToPDF()">Cetak</button>
                <div class="p-0" id="spinner-print" style="display: none;">
                    <div class="spinner-border spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
