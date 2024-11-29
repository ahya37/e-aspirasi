$(function () {
  $("#tablePlace").DataTable({
    processing: true,
    pageLength:200,
    language: {
      processing: "<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>",
    },
    serverSide: true,
    ordering: true,
    ajax: {
      url: "/tugas/listdata",
    },
    columns: [
      { data: "id", name: "id" },
      { data: "nomor", name: "nomor" },
      { data: "nama", name: "nama" },
      {
        data: "action",
        name: "action",
        orderable: false,
        searchable: false,
        width: "15%",
      },
    ],
    order: [[1, "asc"]],
    columnDefs: [
      {
        targets: [0],
        visible: false,
      },
    ],
  });
});

function onDelete(data) {
  const id = data.id;
  const nomor = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin hapus nomor : ${nomor}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/tugas/delete`,
        method: "POST",
        cache: false,
        data: {
          id: id,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
          Swal.fire({
            position: "center",
            icon: "success",
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
        },
        error: function (error) {
          Swal.fire({
            position: "center",
            icon: "danger",
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
        },
      });
    }
    const table = $("#tablePlace").DataTable();
    table.ajax.reload();
  });
}
