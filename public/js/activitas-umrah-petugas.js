let urlTourcode = "/api/getdataumrah";
let urlPetugas = "/api/getdatapetugas";

// finish
function onFinish(data) {
  const id = data.id;
  const pembimbing = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin selesai tugas pembimbing : ${pembimbing}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/aktivitas/petugas/finish`,
        method: "POST",
        cache: false,
        data: {
          id: id,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
          let messages = "";
          if (data.data.data === "status") {
            messages = "warning";
          } else {
            messages = "success";
          }
          Swal.fire({
            position: "center",
            icon: `${messages}`,
            title: `${data.data.message}`,
            showConfirmButton: false,
            width: 500,
            timer: 900,
          });
          const table = $("#data").DataTable();
          table.ajax.reload();
        },
      });
    }
  });
}

$(".datepicker").datepicker(
  {
    format: "MM",
    viewMode: "months",
    minViewMode: "months",
    autoclose: true,
  },
  ($.fn.datepicker.dates["en"] = {
    days: [
      "Sunday",
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
    ],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
    months: [
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Augustus",
      "September",
      "Oktober",
      "November",
      "Desember",
    ],
    monthsShort: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "Mei",
      "Jun",
      "Jul",
      "Agu",
      "Sep",
      "Okt",
      "Nov",
      "Des",
    ],
    today: "Today",
    clear: "Clear",
    format: "MM",
    titleFormat: "MM" /* Leverages same syntax as 'format' */,
    weekStart: 0,
  })
);

let tourcode = "";
let pembimbingId = "";
let month = "";
let year = "";

async function allMonth() {
  $("#dates").val("");
  month = "";
  year = "";
  tourcode = "";
  pembimbingId = "";
  urlTourcode = "/api/getdataumrah";
  urlPetugas = "/api/getdatapetugas";
  // $('#pembimbing').empty();
  // $('#tourcode').empty();

  table.ajax.reload(null, false);
  initialSelectTorucode(urlTourcode);
  initialSelectPembimbing(urlPetugas);
}

initialSelectTorucode(urlTourcode);
initialSelectPembimbing(urlPetugas);

// FUNGSI SELECT2
function initialSelectTorucode(urlTourcode) {
  $(".tourcode").select2({
    theme: "bootstrap4",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
      ? "100%"
      : "style",
    placeholder: "Pilih Tourcode",
    allowClear: Boolean($(this).data("allow-clear")),
    ajax: {
      dataType: "json",
      url: urlTourcode,
      delay: 250,
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              text: item.tourcode,
              id: item.tourcode,
            };
          }),
        };
      },
    },
  });
}

$(".pembimbing").select2({
  theme: "bootstrap4",
  width: $(this).data("width")
    ? $(this).data("width")
    : $(this).hasClass("w-100")
    ? "100%"
    : "style",
  placeholder: "Pilih Petugas",
  allowClear: Boolean($(this).data("allow-clear")),
  ajax: {
    dataType: "json",
    url: urlPetugas,
    delay: 250,
    processResults: function (data) {
      return {
        results: $.map(data, function (item) {
          return {
            text: item.nama,
            id: item.id,
          };
        }),
      };
    },
  },
});

function initialSelectPembimbing() {
  $(".pembimbing").select2({
    theme: "bootstrap4",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
      ? "100%"
      : "style",
    placeholder: "Pilih Petugas",
    allowClear: Boolean($(this).data("allow-clear")),
    ajax: {
      dataType: "json",
      url: urlPetugas,
      delay: 250,
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              text: item.nama,
              id: item.id,
            };
          }),
        };
      },
    },
  });
}

$(".filter").on("changeDate", async function (selected) {
  const monthSelected = selected.date.getMonth() + 1;
  const yearSelected = selected.date.getFullYear();
  month = monthSelected;
  year = yearSelected;
  (urlTourcode = `/api/getdataumrahbymonth/${month}/${year}`),
    initialSelectTorucode(urlTourcode);
  // GET PEMBIMBING BERDASARKAN BULAN
  urlPetugas = `/api/getdatapetugas/umrah/${month}/${year}`;
  initialSelectPembimbing(urlPetugas);
  table.ajax.reload(null, false);
});

$(".tourcode").on("change", function () {
  tourcode = $("select[name=tourcode] option").filter(":selected").val();
  table.ajax.reload(null, false);
});

$(".pembimbing").on("change", function () {
  tourcode = "";
  $("#tourcode").empty();
  pembimbingId = $("select[name=pembimbing] option").filter(":selected").val();
  // GET DATA TOURCODE BERDASARKAN PEMBIMBING
  urlTourcode = `/api/getdataumrah/${pembimbingId}`;
  initialSelectTorucode(urlTourcode);
  table.ajax.reload(null, false);
});

const table = $("#data").DataTable({
  pageLength: 100,
  bLengthChange: true,
  bFilter: true,
  bInfo: true,
  processing: true,
  bServerSide: true,
  order: [[0, "desc"]],
  autoWidth: false,
  ajax: {
    url: "/api/petugas/dt/aktivitas",
    type: "POST",
    data: function (q) {
      q.tourcode = tourcode;
      q.month = month;
      q.year = year;
      q.pembimbing = pembimbingId;
      return q;
    },
  },
  columnDefs: [
    {
      targets: 0,
      render: function (data, type, row, meta) {
        return `<p>${row.tourcode}</p>`;
      },
    },
    {
      targets: 1,
      render: function (data, type, row, meta) {
        return `<p>${row.petugas} (${row.status_tugas})</p>`;
      },
    },
    // {
    //   targets: 2,
    //   render: function (data, type, row, meta) {
    //     if (row.status === "finish") {
    //       let nilai_akhir = calculateGrade(row.nilai_akhir);
    //       if (nilai_akhir === "A") {
    //         return `<span class='text-success'>${nilai_akhir}</span>`;
    //       }
    //       if (nilai_akhir === "B") {
    //         return `<span class='text-primary'>${nilai_akhir}</span>`;
    //       }
    //       if (nilai_akhir === "C") {
    //         return `<span class='text-warning'>${nilai_akhir}</span>`;
    //       }
    //       if (nilai_akhir === "D") {
    //         return `<span class='text-primary'>B</span>`;
    //       }
    //     }else{
    //       return `<span>Dalam proses mengerjakan</span>`;
    //     }
    //   },
    // },
    {
      targets: 2,
      render: function (data, type, row, meta) {
        const btnSelesai =
          row.status === `active`
            ? `<button onclick="onFinish(this)" id="${row.id}" value="${row.petugas}" class="btn btn-sm btn-primary">Selesai</button>`
            : "";
        return `
                <a href='/aktivitas/petugas/detail/${row.id}' class="btn btn-sm btn-primary">Detail Tugas</a>
                ${btnSelesai}
                <button onclick="onDelete(this)" id="${row.id}" value="${row.petugas}" class="btn btn-sm text-danger"><i class='fa fa-trash'></i></button>
        `;
      },
    },
  ],
});

// <a href='/aktivitas/report/tugas/${row.id}' class="btn btn-sm btn-primary">Cetak </a>

// function calculateGrade(data) {
//   let grade = "Dalam prosess";
//   if (data >= 909 && data >= 957) {
//     grade = "A";
//   }
//   if (data >= 814 && data <= 908) {
//     grade = "B";
//   }
//   if (data >= 622 && data <= 813) {
//     grade = "C";
//   }
//   if (data <= 621) {
//     grade = "D";
//   }

//   return grade;
// }

// finish
function onDelete(data) {
  const id = data.id;
  const petugas = data.value;
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
  Swal.fire({
    title: `Yakin hapus petugas : ${petugas}?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/aktivitas/petugas/delete/tugas`,
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
      });
      // const table = $("#data").DataTable();
      table.ajax.reload();
    }
  });
}
