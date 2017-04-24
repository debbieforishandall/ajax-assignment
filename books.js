//Javascript program fetches list of books categories via ajax
//And displays the books under the category selected

/*jslint devel: true */
/*jslint white: true */
/*global $, window */
/*jslint browser: true */
/*jslint this: true */
/*jslint for: true */

(function() {
    "use strict";

    function ajaxError (ajax, exception) { 
        console.log("Error making ajax request: " + "\nStatus: " + ajax.status + "  "
          + ajax.statusText + "\nResponse: " + ajax.responseText); 
    }

    function displayCategory() {
        var type = "list_category";
        $.ajax({
            url: "booklist.php",
            type: "POST",
            crossOrigin: true,
            data: {type: type},
            success: function (response) {
                console.log(response);
            },
            error: ajaxError
        });
    }

    function displayInCategory($category) {
        var type = "in_category";
    }
    //Window load function
    window.onload=function () {
        //display available categories on load
        displayCategory();
        //On select categories,display books in category
        $("#list_books").click(function () {
            displayInCategory($('#category input[name=choice]:checked').val());
        });
    };
}());
