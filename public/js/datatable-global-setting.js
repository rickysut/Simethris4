// datatables-global-settings.js
$.extend(true, $.fn.dataTable.defaults, {
    language: {
        searchPlaceholder: "Cari...",
        emptyTable: "Tidak ada data yang tersedia",
        zeroRecords: "Tidak ditemukan data yang sesuai",
        processing: "Memproses...",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
        infoFiltered: "(disaring dari _MAX_ total data)",
    }
});
