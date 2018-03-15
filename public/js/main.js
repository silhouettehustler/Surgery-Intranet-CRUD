$(document).ready(function(){
    Main.Init();
});

var Main = {
    Init: function(){
    Main.InitPlugins();
    Main.InitModalForm();
    },
    InitPlugins: function() {

        //Init date pickers
        $(".datepicker").datepicker();
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
                Main.InitPlugins();
            }).fail(function(error){
                console.log(error.statusMessage);
            });

        });

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
            }).done(function(response){
                console.log(response);
                $ele.closest("tr").fadeOut("slow",function(){
                    $(this).remove();
                });
            }).fail(function(error){
                console.log(error.statusMessage)
            });
        }
    }
}