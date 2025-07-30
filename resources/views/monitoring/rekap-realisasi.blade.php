@extends('layouts.adminlte')

@push('style')
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafb;
        -webkit-tap-highlight-color: transparent;
    }

    .container {
        padding: 16px;
        max-width: 1400px;
        margin: auto;
    }

    h1 {
        color: #1d4ed8;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        padding: 0 8px;
    }

    /* Enhanced Filter Form */
    #filter-form {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
        margin-bottom: 20px;
        background-color: #ffffff;
        padding: 16px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    @media (min-width: 600px) {
        #filter-form {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 200px) {
        #filter-form {
            grid-template-columns: auto auto 1fr;
            align-items: end;
        }
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }

    select {
        padding: 12px 14px;
        font-size: 15px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background-color: #ffffff;
        cursor: pointer;
        width: 100%;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 18px;
        transition: border-color 0.2s;
    }

    select:focus {
        border-color: #1d4ed8;
        outline: none;
    }

    /* Enhanced Export Button */
    .export-btn {
        padding: 12px 16px;
        background-color: #1d4ed8;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 15px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        touch-action: manipulation;
    }

    .export-btn:active {
        transform: scale(0.98);
        background-color: #1e3a8a;
    }

    /* Table Styles */
    .table-wrapper {
        overflow-x: auto;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        color: #334155;
        min-width: 1200px;
    }

    thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background-color: #e2e8f0;
        padding: 12px;
        border: 1px solid #cbd5e1;
        font-weight: bold;
        text-align: center;
    }

    thead tr:first-child th {
        background-color: #f1f5f9;
    }

    thead .bg-green { background-color: #bbf7d0; }
    thead .bg-yellow { background-color: #fef9c3; }
    thead .bg-blue { background-color: #bfdbfe; }
    thead .bg-red { background-color: #fecaca; color: #991b1b; }
    thead .bg-orange { background-color: #fed7aa; }

    tbody td {
        padding: 12px;
        border: 1px solid #e2e8f0;
        text-align: center;
        vertical-align: middle;
    }

    tbody td.text-left {
        text-align: left;
    }

    tbody td.text-right {
        text-align: right;
    }

    tbody tr:hover {
        background-color: #f8fafc;
    }

    .total-row {
        background-color: #fef9c3;
        font-weight: bold;
    }

    .no-data {
        text-align: center;
        font-style: italic;
        color: #64748b;
        padding: 20px;
    }

    /* Mobile Card View */
    .card-view {
        display: none;
        flex-direction: column;
        gap: 12px;
        padding: 8px;
    }

    .card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 16px;
        border-left: 5px solid #1d4ed8;
        transition: transform 0.1s ease;
        touch-action: manipulation;
    }

    .card:active {
        transform: scale(0.98);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-title {
        font-weight: bold;
        color: #1d4ed8;
        font-size: 16px;
        flex: 1;
    }

    .card-number {
        background-color: #e2e8f0;
        border-radius: 14px;
        padding: 4px 10px;
        font-size: 14px;
        font-weight: 600;
        min-width: 28px;
        text-align: center;
    }

    .card-section {
        margin-bottom: 14px;
    }

    .section-title {
        font-weight: 600;
        font-size: 14px;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        font-size: 16px;
    }

    .section-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .data-item {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 12px;
        transition: transform 0.1s ease;
    }

    .data-item:active {
        transform: scale(0.96);
    }

    .data-label {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 6px;
    }

    .data-value {
        font-weight: 600;
        font-size: 14px;
    }

    .data-value.amount {
        color: #1d4ed8;
    }

    .total-card {
        background-color: #fef9c3;
        border-left-color: #f59e0b;
    }

    /* Responsive Adjustments */
    @media (max-width: 899px) {
        .table-wrapper {
            display: none;
        }
        
        .card-view {
            display: flex;
        }
        
        h1 {
            font-size: 22px;
        }
    }

    @media (min-width: 900px) {
        .container {
            padding: 24px;
        }
        
        h1 {
            font-size: 28px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate mobile cards from table data
    function generateMobileCards() {
        const cardContainer = document.querySelector('.card-view');
        const table = document.querySelector('table tbody');
        
        if (!table || !cardContainer) return;
        
        cardContainer.innerHTML = '';
        
        const rows = table.querySelectorAll('tr:not(.total-row)');
        const totalRow = table.querySelector('tr.total-row');
        
        if (rows.length === 0) {
            cardContainer.innerHTML = `
                <div class="card">
                    <div class="no-data">Tidak ada data tersedia untuk tahun {{ request('tahun') }}.</div>
                </div>
            `;
            return;
        }
        
        // Create cards for each row
        rows.forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            const card = document.createElement('div');
            card.className = 'card';
            card.innerHTML = `
                <div class="card-header">
                    <div class="card-title">${cells[1].textContent}</div>
                    <div class="card-number">${cells[0].textContent}</div>
                </div>
                
                <div class="card-section">
                    <div class="section-title" style="color: #10b981;">
                        <i class="fas fa-gavel"></i>
                        Tender
                    </div>
                    <div class="section-content">
                        <div class="data-item">
                            <div class="data-label">Paket</div>
                            <div class="data-value">${cells[2].textContent}</div>
                        </div>
                        <div class="data-item">
                            <div class="data-label">Nilai</div>
                            <div class="data-value amount">${cells[3].textContent}</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-section">
                    <div class="section-title" style="color: #f59e0b;">
                        <i class="fas fa-handshake"></i>
                        Non-Tender
                    </div>
                    <div class="section-content">
                        <div class="data-item">
                            <div class="data-label">Paket</div>
                            <div class="data-value">${cells[4].textContent}</div>
                        </div>
                        <div class="data-item">
                            <div class="data-label">Nilai</div>
                            <div class="data-value amount">${cells[5].textContent}</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-section">
                    <div class="section-title" style="color: #3b82f6;">
                        <i class="fas fa-shopping-cart"></i>
                        E-Katalog
                    </div>
                    <div class="section-content">
                        <div class="data-item">
                            <div class="data-label">Paket</div>
                            <div class="data-value">${cells[6].textContent}</div>
                        </div>
                        <div class="data-item">
                            <div class="data-label">Nilai</div>
                            <div class="data-value amount">${cells[7].textContent}</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-section">
                    <div class="section-title" style="color: #ef4444;">
                        <i class="fas fa-hands-helping"></i>
                        Swakelola
                    </div>
                    <div class="section-content">
                        <div class="data-item">
                            <div class="data-label">Paket</div>
                            <div class="data-value">${cells[8].textContent}</div>
                        </div>
                        <div class="data-item">
                            <div class="data-label">Nilai</div>
                            <div class="data-value amount">${cells[9].textContent}</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-section">
                    <div class="section-title" style="color: #f97316;">
                        <i class="fas fa-store"></i>
                        Toko Daring
                    </div>
                    <div class="section-content">
                        <div class="data-item">
                            <div class="data-label">Paket</div>
                            <div class="data-value">${cells[10].textContent}</div>
                        </div>
                        <div class="data-item">
                            <div class="data-label">Nilai</div>
                            <div class="data-value amount">${cells[11].textContent}</div>
                        </div>
                    </div>
                </div>
            `;
            cardContainer.appendChild(card);
        });
        
        // Add total card if exists
        if (totalRow) {
            const totalCells = totalRow.querySelectorAll('td');
            const totalCard = document.createElement('div');
            totalCard.className = 'card total-card';
            totalCard.innerHTML = `
                <div class="card-header">
                    <div class="card-title">TOTAL KESELURUHAN</div>
                </div>
                
                <div class="card-section">
                    <div class="section-title" style="color: #10b981;">
                        <i class="fas fa-gavel"></i>
                        Tender
                    </div>
                    <div class="section-content">
                        <div class="data-item">
                            <div class="data-label">Paket</div>
                            <div class="data-value">${totalCells[2].textContent}</div>
                        </div>
                        <div class="data-item">
                            <div class="data-label">Nilai</div>
                            <div class="data-value amount">${totalCells[3].textContent}</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-section">
                    <div class="section-title" style="color: #f59e0b;">
                        <i class="fas fa-handshake"></i>
                        Non-Tender
                    </div>
                    <div class="section-content">
                        <div class="data-item">
                            <div class="data-label">Paket</div>
                            <div class="data-value">${totalCells[4].textContent}</div>
                        </div>
                        <div class="data-item">
                            <div class="data-label">Nilai</div>
                            <div class="data-value amount">${totalCells[5].textContent}</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-section">
                    <div class="section-title" style="color: #3b82f6;">
                        <i class="fas fa-shopping-cart"></i>
                        E-Katalog
                    </div>
                    <div class="section-content">
                        <div class="data-item">
                            <div class="data-label">Paket</div>
                            <div class="data-value">${totalCells[6].textContent}</div>
                        </div>
                        <div class="data-item">
                            <div class="data-label">Nilai</div>
                            <div class="data-value amount">${totalCells[7].textContent}</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-section">
                    <div class="section-title" style="color: #ef4444;">
                        <i class="fas fa-hands-helping"></i>
                        Swakelola
                    </div>
                    <div class="section-content">
                        <div class="data-item">
                            <div class="data-label">Paket</div>
                            <div class="data-value">${totalCells[8].textContent}</div>
                        </div>
                        <div class="data-item">
                            <div class="data-label">Nilai</div>
                            <div class="data-value amount">${totalCells[9].textContent}</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-section">
                    <div class="section-title" style="color: #f97316;">
                        <i class="fas fa-store"></i>
                        Toko Daring
                    </div>
                    <div class="section-content">
                        <div class="data-item">
                            <div class="data-label">Paket</div>
                            <div class="data-value">${totalCells[10].textContent}</div>
                        </div>
                        <div class="data-item">
                            <div class="data-label">Nilai</div>
                            <div class="data-value amount">${totalCells[11].textContent}</div>
                        </div>
                    </div>
                </div>
            `;
            cardContainer.appendChild(totalCard);
        }
    }
    
    // Generate cards on load and window resize
    generateMobileCards();
    window.addEventListener('resize', generateMobileCards);
    
    // Enhanced touch feedback for all interactive elements
    document.querySelectorAll('select, .export-btn, .card, .data-item').forEach(el => {
        el.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
        });
        el.addEventListener('touchend', function() {
            this.style.transform = '';
        });
    });
});
</script>
@endpush

@section('content')
<div class="container">
    <h1>Rekapitulasi Realisasi Pengadaan Selesai</h1>

    <!-- Filter Form -->
    <form method="GET" id="filter-form">
        <div class="filter-group">
            <label for="tahun">Tahun:</label>
            <select name="tahun" id="tahun" onchange="document.getElementById('filter-form').submit()">
                <option value="">-- Pilih Tahun --</option>
                @foreach([2024, 2025] as $th)
                    <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label for="satker">Nama Satker:</label>
            <select name="satker" id="satker" onchange="document.getElementById('filter-form').submit()">
                <option value="">-- Semua Satker --</option>
                @foreach($listSatker as $satker)
                    <option value="{{ $satker }}" {{ request('satker') == $satker ? 'selected' : '' }}>{{ $satker }}</option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('monitoring.rekap.realisasi.pdf', ['tahun' => $tahun]) }}" 
           target="_blank" 
           class="export-btn">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </form>

    <!-- Desktop Table View -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Satker</th>
                    <th colspan="2" class="bg-green">Tender</th>
                    <th colspan="2" class="bg-yellow">Non-Tender</th>
                    <th colspan="2" class="bg-blue">E-Katalog</th>
                    <th colspan="2" class="bg-red">Swakelola</th>
                    <th colspan="2" class="bg-orange">Toko Daring</th>
                </tr>
                <tr>
                    <th>Paket</th><th>Nilai</th>
                    <th>Paket</th><th>Nilai</th>
                    <th>Paket</th><th>Nilai</th>
                    <th>Paket</th><th>Nilai</th>
                    <th>Paket</th><th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = [
                        'tender_paket' => 0, 'tender_nilai' => 0,
                        'nontender_paket' => 0, 'nontender_nilai' => 0,
                        'ekatalog_paket' => 0, 'ekatalog_nilai' => 0,
                        'swakelola_paket' => 0, 'swakelola_nilai' => 0,
                        'tokodaring_paket' => 0, 'tokodaring_nilai' => 0,
                    ];
                @endphp

                @forelse ($data as $i => $row)
                    @php
                        $total['tender_paket'] += $row['total_paket_tender'];
                        $total['tender_nilai'] += $row['total_nilai_tender'];
                        $total['nontender_paket'] += $row['total_paket_nontender'];
                        $total['nontender_nilai'] += $row['total_nilai_nontender'];
                        $total['ekatalog_paket'] += $row['total_paket_ekatalog'];
                        $total['ekatalog_nilai'] += $row['total_nilai_ekatalog'];
                        $total['swakelola_paket'] += $row['total_paket_swakelola'];
                        $total['swakelola_nilai'] += $row['total_nilai_swakelola'];
                        $total['tokodaring_paket'] += $row['total_paket_tokodaring'];
                        $total['tokodaring_nilai'] += $row['total_nilai_tokodaring'];
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="text-left">{{ $row['nama_satker'] }}</td>
                        <td>{{ $row['total_paket_tender'] }}</td>
                        <td class="text-right">{{ number_format($row['total_nilai_tender'], 0, ',', '.') }}</td>
                        <td>{{ $row['total_paket_nontender'] }}</td>
                        <td class="text-right">{{ number_format($row['total_nilai_nontender'], 0, ',', '.') }}</td>
                        <td>{{ $row['total_paket_ekatalog'] }}</td>
                        <td class="text-right">{{ number_format($row['total_nilai_ekatalog'], 0, ',', '.') }}</td>
                        <td>{{ $row['total_paket_swakelola'] }}</td>
                        <td class="text-right">{{ number_format($row['total_nilai_swakelola'], 0, ',', '.') }}</td>
                        <td>{{ $row['total_paket_tokodaring'] }}</td>
                        <td class="text-right">{{ number_format($row['total_nilai_tokodaring'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="no-data">Tidak ada data tersedia untuk tahun {{ request('tahun') }}.</td>
                    </tr>
                @endforelse

                @if(count($data) > 0)
                    <tr class="total-row">
                        <td colspan="2">TOTAL</td>
                        <td>{{ $total['tender_paket'] }}</td>
                        <td class="text-right">{{ number_format($total['tender_nilai'], 0, ',', '.') }}</td>
                        <td>{{ $total['nontender_paket'] }}</td>
                        <td class="text-right">{{ number_format($total['nontender_nilai'], 0, ',', '.') }}</td>
                        <td>{{ $total['ekatalog_paket'] }}</td>
                        <td class="text-right">{{ number_format($total['ekatalog_nilai'], 0, ',', '.') }}</td>
                        <td>{{ $total['swakelola_paket'] }}</td>
                        <td class="text-right">{{ number_format($total['swakelola_nilai'], 0, ',', '.') }}</td>
                        <td>{{ $total['tokodaring_paket'] }}</td>
                        <td class="text-right">{{ number_format($total['tokodaring_nilai'], 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="card-view"></div>
</div>
@endsection