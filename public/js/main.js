$(document).ready(function(){
    Main.Init();
});

var Main = {
    Init: function(){
    Main.Plugins.InitAll();
    Main.InitModalForm();

    $(document).on("click","#modal-save-btn",function(){
        var $form = $(".modal-body").find("form");
        var form = $form[0];
        var formData = new FormData(form);

        $.ajax({
            url: $form.data("url"),
            data: formData,
            type: "POST",
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done(function () {
            $().toastmessage('showSuccessToast', "Changes saved!");
            $('#modal-container').modal('hide');
        }).fail(function (error) {
            $().toastmessage('showErrorToast', error.statusMessage);
        });
    })
    },
    Plugins:{
        InitAll: function(){
            Main.Plugins.InitDatePicker();
            Main.Plugins.InitDataTable();
        },
        InitDatePicker: function(){
            //Init date pickers
            $(".datepicker").datepicker({dateFormat: 'dd-mm-yy'});
        },
        InitDataTable: function(){
            //Init data table
            $(".sortable-table").DataTable( {
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',
                    'print'
                ],
                "columnDefs": [ {
                    "targets": 'no-sort',
                    "orderable": false,
                } ]
            } );
        }
    },
    InitModalForm: function(){

        $(document).on("click",".modal-container-trigger",function(){

            var $ele = $(this);
            var $container = $("#modal-container");
            var $body = $container.find(".modal-body");

            //show modal
            $container.modal("show");

            //load content
            $.ajax({
                url:$ele.data("url"),
                type:"GET"
            }).done(function(response){

                $body.html(response);
                $container.find(".modal-title").text($ele.data("title"));
                Main.Plugins.InitDatePicker();

                if ($body.find("#appointment-form")){
                    Main.Appointments.AppointmentBookingEventsInit();
                }

            }).fail(function(error){
                $().toastmessage('showErrorToast', error.statusMessage);
            });

        });

    },
    Helpers:{
        DeleteRow: function(ele,message){

            var $ele = $(ele);

            $().toastmessage('showSuccessToast', message);

            var table = $ele.closest("table").DataTable();
            table.row($ele.closest("tr")).remove().draw();
        }
    },
    Appointments : {
        GetTimeSlot: function(ele){
            var $ele = $(ele);
            var $form = $ele.closest("form");
            var $id = $form.find("#employee_id");
            var $timeSlots = $("#start_time");
            var date = $('#date').val();

            if ($id === undefined){$().toastmessage('showInfoToast', "Please select GP, to see the available time slots");}

            $.ajax({
                url: "/appointment/timeSlots/"+$id.val()+"/"+date,
                type:"GET"
            }).done(function(response){
                $timeSlots.html(response);
            }).fail(function(error){
                $().toastmessage('showErrorToast', error.statusMessage);
            });

        },
        GetAvailableDoctors: function(ele){

            var $ele = $(ele);
            $.ajax({
                url: "/appointment/getAvailableStaff/"+$ele.val(),
                type:"GET"
            }).done(function(response){
                $('#employee_id').html(response);
            }).fail(function(error){
                $().toastmessage('showErrorToast', error.statusMessage);
            });

        },
        AppointmentBookingEventsInit: function(){

            //appointment datetime change
            $(document).on("change","#appointment-form #date",function(){
                $('#time_slot').html("");
                Main.Appointments.GetAvailableDoctors($(this));
            });

            //appointment gp id change
            $(document).on("change","#appointment-form #employee_id",function(){
                Main.Appointments.GetTimeSlot($(this));
            });
        },
        Destroy: function(ele){
            var $ele = $(ele);

            $.ajax({
                url:$ele.data("url"),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:"post",
                data:{
                    id:$ele.data("id")
                }
            }).done(function(){
                Main.Helpers.DeleteRow($ele,"Successfully cancelled appointment!")
            }).fail(function(error){
                $().toastmessage('showErrorToast', error.statusMessage);
            });
        }

    }
}