var filterId = 1;

let table = new DataTable("#datatableModify", {
  language: {
    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/th.json",
  },
  pagingType: "simple",
  lengthChange: false,
  order: [[0, "desc"]],
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
    let columnValue = parseFloat(data[1]);
    return columnValue > 0;
  }
});
