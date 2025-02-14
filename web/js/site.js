$(document).ready(function () {
  $(".datatable").DataTable({
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/th.json",
    },
    pagingType: "simple",
    lengthChange: false,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excel",
        text: `<i class="material-icons small">file_download</i> ดาวน์โหลด Excel`,
        className: "text-white bg-info",
      },
    ],
  //  order: [[0, "desc"]],
  });

  $(".datatable-2").DataTable({
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/th.json",
    },
    info: false,
    lengthChange: false,
    paging: false,
    searching: false,
  });

  $(".datatable-search").DataTable({
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/th.json",
    },
    dom: "Bfrtip",
    buttons: [
      {
        extend: "print",
        text: `<i class="material-icons small">print</i> พิมพ์`,
        className: "text-white bg-info",
      },
      
    ],
    info: false,
    lengthChange: false,
    // ordering: false,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "print",
        text: `<i class="material-icons small">print</i> พิมพ์`,
        className: "text-white bg-info",
      },
    ],
  });

  $(".select2").select2({
    language: "th-TH",
  });

  // $(".datepicker").datepicker({
  //   language: "th-th",
  //   format: "yyyy-mm-dd",
  //   uiLibrary: "bootstrap4",
  // });
  $(".datepicker").flatpickr({
    locale: "th",
    dateFormat: "Y-m-d",
  });

  $(".datepicker-time").flatpickr({
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    time_24hr: true,
    locale: "th",
  });

  // $(".datepicker-month").datepicker({
  //   language: "th-th",
  //   format: "yyyy-mm",
  //   startView: "months",
  //   minViewMode: "months",
  //   uiLibrary: "bootstrap4",
  // });

  $(".datepicker-month").flatpickr({
    plugins: [
      new monthSelectPlugin({
        shorthand: true, //defaults to false
        dateFormat: "m.y", //defaults to "F Y"
        altFormat: "F Y", //defaults to "F Y"
        theme: "dark", // defaults to "light"
      }),
    ],
  });
});

function productListInRequisition() {
  $(".select2-requisition").select2({
    language: "th-TH",
    minimumInputLength: 3,
    ajax: {
      url: "/requisition/call-stock",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          q: params.term,
        };
      },
      cache: true,
    },
    templateResult: formatRepoRequisition,
  });
}

$(".select2-requisition").on("select2:select", function (e) {
  var data = e.params.data;
  var itemDetail = `${data.part_name} (${data.uom_name})`;
  var stock = `${data.stock} ${data.uom_name_main}`;
   
  var dataValue = data.item_id;
  var dataPrice = 0;
  var dataName = `${itemDetail} <i class='small'>stock: ${stock}</i>`;
  var dataUomId = data.uom_id;
  var dataUomQty = data.unit;
  var dataDepart = data.department;
  var dataDepartId = data.depart_id;

  var tableList = document.getElementById("tableList");
  var itemList = document.querySelector(
    "#item_" + dataValue + "_" + dataDepartId
  );
  if (itemList) {
    var qtyValue = document.getElementById(
      "qty_" + dataValue + "_" + dataDepartId
    );
    qtyValue.value = parseInt(qtyValue.value) + 1;
  } else {
    tableList.insertAdjacentHTML(
      "beforeend",
      `<tr id="item_${dataValue}_${dataDepartId}">
              <input type="hidden" class="total_amount_class" id="total_amount_${dataValue}" name="NewAuditDetail[${newRecord.value}][total_amount]" value="${dataPrice}">
              <input type="hidden" name="NewAuditDetail[${newRecord.value}][part_id]" value="${dataValue}">
              <input type="hidden" name="NewAuditDetail[${newRecord.value}][uom_id]" value="${dataUomId}">
              <input type="hidden" name="NewAuditDetail[${newRecord.value}][qty_uom]" value="${dataUomQty}">
              <input type="hidden" name="NewAuditDetail[${newRecord.value}][depart_id]" value="${dataDepartId}">
              <td class="align-middle">
                  <p class="mb-1">${dataName}</p>
                  <input type="hidden" name="NewAuditDetail[${newRecord.value}][audit_txt]" value="${dataName}">
                  <p class="small text-muted">${dataDepart}</p>
              </td>
              <td class="align-middle"><input type="number" step="any" id="qty_${dataValue}_${dataDepartId}" class="form-control" name="NewAuditDetail[${newRecord.value}][qty]" value="1" max="${data.stock}"></td>
              <td class="align-middle"><a type="button" class="text-danger" onclick="deleteRow(this)"><i class="material-icons">delete</i></a></td>
          </tr>`
    );
    newRecord.value = parseInt(newRecord.value) + 1;
  }
});

function formatRepoRequisition(repo) {
  if (repo.loading) {
    return repo.id;
  }
  var itemDetail = `${repo.part_name} (${repo.uom_name})`;
  var stock = `${repo.stock} ${repo.uom_name_main}`;
  let image = "/img/item.png";
  // if (repo.image != null) {
  //   image = "uploads/perperson/" + repo.image;
  // }
  var $container = $(`
  <div class='d-flex'>
    <div class="my-auto" style="width:3rem;"><img class="avatar avatar-blue" src='${image}' onerror="this.src='/img/item.png'" style="height:3rem;"/></div>
    <div class='ml-1'>
      <div class='text__name'></div>
      <div class='text__description'></div>
    </div>
  </div>`);
  $container.find(".text__name").text(`${itemDetail} | Stock ${stock}`);
  $container.find(".text__description").text(repo.department);
  return $container;
}
