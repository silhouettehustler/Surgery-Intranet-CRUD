$(document).ready(function(){
    Main.Init();
});

var Main = {
    Init: function(){
    Main.Plugins.InitAll();
    Main.InitModalForm();
    },
    Plugins:{
        InitAll: function(){
            Main.Plugins.InitDatePicker();
            Main.Plugins.InitDataTable();
        },
        InitDatePicker: function(){
            //Init date pickers
            $(".datepicker").datepicker();
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
            //show modal
            $container.modal("show");

            //load content
            $.ajax({
                url:$ele.data("url"),
                type:"GET"
            }).done(function(response){
                $container.find(".modal-body").html(response);
                $container.find(".modal-title").text($ele.data("title"));
                Main.Plugins.InitDatePicker();
            }).fail(function(error){
                console.log(error.statusMessage);
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
        Destroy: function(ele){
            var $ele = $(ele);

            $.ajax({
                url:$ele.data("url"),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:"post",
                data:{
                    $id:$ele.data("id")
                }
            }).done(function(){
                Main.Helpers.DeleteRow($ele,"Successfully cancelled appointment!")
            }).fail(function(error){
                $().toastmessage('showErrorToast', error.statusMessage);
            });
        }
    }
}