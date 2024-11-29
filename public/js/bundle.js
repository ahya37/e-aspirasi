const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
const table = $("#tablePlace").DataTable({
  pageLength: 100,
  bLengthChange: true,
  bFilter: true,
  bInfo: true,
  processing: true,
  bServerSide: true,
  order: [[0, "desc"]],
  autoWidth: false,
  ajax: {
    url: "/item/bundle/listdata",
    type: "POST",
    data: function (q) {
      (q._token = CSRF_TOKEN)
      return q;
    },
  },
  columnDefs: [
    {
      targets: 0,
      render: function (data, type, row, meta) {
        return `<p>${row.no}</p>`;
      },
    },
    {
      targets: 1,
      render: function (data, type, row, meta) {
        return `<p>${row.name}</p>`;
      },
    },
    {
      targets: 2,
      render: function (data, type, row, meta) {
        return `<p>${row.qty} Item</p>`;
      },
    },
    {
      targets: 3,
      render: function (data, type, row, meta) {
        return `<p>${row.note}</p>`;
      },
    },
    {
      targets: 4,
      render: function (data, type, row, meta) {
        return `<p>${row.created_at}</p>`;
      },
    },
    {
      targets: 5,
      render: function (data, type, row, meta) {
        return `
                <a href="/item/bundle/edit/${row.id}" class="btn btn-sm fa fa-edit text-primary" title="Edit"></a>
                <button onclick="onDelete(this)" id="${row.id}" value="${row.id}" data-name="${row.name}" title="Hapus" class="fa fa-trash btn text-danger"></button>
              `;
      },
    },
  ],
});

// delete
function onDelete(data) {
  const id = data.id;
  const name = data.getAttribute('data-name');
  
  Swal.fire({
    title: `Yakin hapus bundel : ${name}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/item/bundle/destroy`,
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
            icon: "warning",
            title: `Gagal!`,
            showConfirmButton: false,
            width: 500,
            timer: 1500,
          });
        },
      });
    }
    const table = $("#tablePlace").DataTable();
    table.ajax.reload();
  });
}


