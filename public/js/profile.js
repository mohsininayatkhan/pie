$(document).ready(function() {
    $('.hyperspan').hover(function() {
        $(this).closest('.ads-list').css("background-color", "#f3f8f9");
    });
    
    $('.hyperspan').mouseleave(function() {
        $(this).closest('.ads-list').css("background-color", "");
    });
    
});
