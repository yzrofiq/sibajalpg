# VMS

## Akun
```
http://inaproc.lkpp.go.id/isb
XWk0bPQDo1
requester-lampung
```

## Tampilan
https://www.pengadaan.id/tender?key=&vendor_id=&province=

## API
1. Non Tender Selesai 
```
https://inaproc.lkpp.go.id/isb/api/6715036b-34b7-4c23-959b-fdf1c8b7bf6a/json/736986793/NonTenderSelesaiDetailSPSE/tipe/4:4/parameter/{tahun}:{lpse}
```
2. Non Tender Pengumuman
```
https://inaproc.lkpp.go.id/isb/api/f71d9aac-d8fa-41c1-84fb-f8b46a1eae58/json/736986792/NonTenderPengumumanDetailSPSE/tipe/4:4/parameter/{tahun}:{lpse}
```
3. Tender Selesai
```
https://inaproc.lkpp.go.id/isb/api/349fc588-e505-46e4-a692-451237f5682a/json/736986569/TenderSelesaiDetailSPSE/tipe/4:4/parameter/{tahun}:{lpse}
```
4. Tender Pengumuman
```
https://inaproc.lkpp.go.id/isb/api/e89f8ead-0727-4354-b937-31dc517bb989/json/736986757/TenderPengumumanDetailSPSE/tipe/4:4/parameter/{tahun}:{lpse}
```
5. Peserta Per Tender
```
https://inaproc.lkpp.go.id/isb/api/3324ec60-f4ab-4be4-ac26-7af73e2ddb48/json/736986758/PesertaPerTenderSPSE/tipe/4/parameter/{kode_integer_tender}
```
6. Jadwal Per Tender
```
https://inaproc.lkpp.go.id/isb/api/3dc2d0ac-eb7e-4c89-889d-7c59205a04f6/json/736986787/JadwalPerTenderSPSE/tipe/4/parameter/{kode_integer_tender}
```
7. Jadwal Per Non Tender
```
https://inaproc.lkpp.go.id/isb/api/0094d483-16cb-41e1-9c81-b8cc3c31e5c4/json/736986767/JadwalPerNonTenderSPSE/tipe/4/parameter/{kode_integer_non_tender}
```

## TO DO
1. BeLA - Menu di Admin untuk update BeLA (nominal) secara manual - done
2. Show Tender menggunakan Admin LTE - done
3. Show Non Tender menggunakan Admin LTE - done
4. Ada input untuk Edit Manual: Database Vendor, 10 Paket Strategis, Summary Report
5. Ada Notifikasi pas cron job ke sibajamonitoring@gmail.com
6. Tender Selesai ada di field `tgl_penetapan_pemenang` dari data Tender
7. Email, Pak Windu windups21@gmail.com
8. Username: adminbaja | Password: sibaja@PBJ2022 | dijadikan user biasa
9. Menu Manajemen User - done
