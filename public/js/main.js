$(document).ready(function(){
    Main.Init();
});

var Main = {
    Init: function(){
    Main.InitPlugins();
    },
    InitPlugins: function() {

        //Init date pickers
        $(".datepicker").datepicker();
    }
}