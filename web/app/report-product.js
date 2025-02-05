var filterId = 1;

let table = new DataTable("#datatableModify", {
  language: {
    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/th.json",
  },
  pagingType: "simple",
  lengthChange: false,

  dom: "Bfrtip",
  buttons: [
    {
      extend: "print",
      text: `<i class="material-icons small">print</i> พิมพ์`,
      className: "text-white bg-info",
    },
    {
      extend: "excel",
      text: `<i class="material-icons small">file_download</i> Excel`,
      className: "text-white bg-success",
    },
  ],
  order: [[8, "desc"]],
  columnDefs: [
    {
      targets: [0, 1, 2],
      orderable: false,
    },
  ],
});

document.querySelectorAll("input.custom-control-input").forEach((el) => {
  el.addEventListener(
    el.type === "text" ? "keyup" : "change",
    () => ((filterId = el.value), table.draw())
  );
});

DataTable.ext.search.push(function (settings, data, dataIndex) {
  if (filterId == "1") {
    return true;
  } else {
    if (parseFloat(data[3]) > 0) {
      return true;
    } else if (parseFloat(data[4]) > 0) {
      return true;
    } else if (parseFloat(data[5]) > 0) {
      return true;
    } else if (parseFloat(data[6]) > 0) {
      return true;
    } else if (parseFloat(data[7]) > 0) {
      return true;
    }
    return false;
  }
});
