/*global $*/("button").on('click', 'button', function(){
    $.getJSON("movie-items.json", function(obj) {
        $.each(obj, function(key, value) {
            $("ul").append("<li>"+value.Title+"</li>");
        });
    });
});