import DataTable from "datatables.net-dt";

document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#usersTable");
    if (!table) return;

    new DataTable(table, {
        paging: true,
        searching: true,
        ordering: true,
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: 3 }, // kolom ke-4 = AKSI
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: { previous: "‹", next: "›" },
        },
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#carsTable");
    if (!table) return;

    new DataTable(table, {
        paging: true,
        searching: true,
        ordering: true,
        pageLength: 10,
        columnDefs: [{ orderable: false, targets: [3, 4] }],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: { previous: "‹", next: "›" },
        },
    });
});
