Luce = {};

Luce.root = "http://localhost/lucehackathon/";

Luce.suggest = function() {    
    jQuery.post(Luce.root + "luce/index/suggest", {"type" : "test"}, Luce.suggestResponse);
};

Luce.suggestResponse = function(response, a, b) {
    jQuery("div#luce-suggest-response").html(response);
};

(function($) {
    $(document).ready(function() {
        $("#luce-suggest-button").click(Luce.suggest);
        
    });
    
})(jQuery)