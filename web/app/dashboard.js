var total  = 0;
$(document).ready(function() {
    $('#departments').text('กำลังโหลดข้อมูล...');
    
    $.ajax({
        url: 'adit',
        type: 'GET',
        success: function(response) {
            let data = JSON.parse(response);
            $.each(data, function(key, value) {
                show(value.id,value.department);
            //    console.log(show(value.id,value.department));
            });
            $('#departments').html('');
        }
    });


    
    function show(id,name) {
    $.ajax({
        url: 'dashboard-data?id=' + id ,
        type: 'GET',
        success: function(response) {
            let data = JSON.parse(response);
        //    console.log(data);
            //
                
                var itemHTML = '<div class="col-6 col-md-3">' +
                    '<a href="/report/current-stock?department_id=' + id + '">' +
                    '<div class="card shadow border-0 bg-template mb-2">' +
                    '<div class="card-body">' +
                    '<div class="row">' +
                    '<div class="col">' +
                    '<span class="mb-2 font-weight-normal">' + name + '</span>' +
                    '</div>' +
                    '<div class="col-auto text-right">' +
                    '<span class="font-weight-normal">' + data.totalItem + ' สินค้า</span>' +
                    '<p class="small">' + data.totalPrice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + ' บาท</p>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</a>' +
                    '</div>';
                $('#departments').append(itemHTML);
                total = total + data.totalPrice;
                $('#totalPrice').text(total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

            //$('#departments').append(itemHTML);
            //$('#departments').html(response);
        }
    });
    }
});