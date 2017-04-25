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

    function ajaxFailure (ajax, exception) { 
        console.log("Error making ajax request: " + "\nStatus: " + ajax.status + "  "
          + ajax.statusText + "\nResponse: " + ajax.responseText); 
    }

    function displayCategory() {
        var type = "list_category";
        new Ajax.Request("booklist.php",
            {
            method: "post",
            crossOrigin: true,
            parameters: {type: type},
            onSuccess: function (response) {
                console.log(response);
                var category = ajax.responseXML.getElementsByTagName("category");
                for (var i = 0; i < category.length; i++) {
                    var id = category[i].getElementsByTagName("id")[0].firstChild.nodeValue;
                    var name = category[i].getElementsByTagName("name")[0].firstChild.nodeValue;
                    //make a radio button containing category
                    var radioHtml = "<input type='radio' name='choice' id='" + id + "'>" + name;
                    $("category").appendChild(radioHtml);
                }
            },
            onFailure: ajaxFailure
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
        $("list_books").addEventListener("click", function () {
            displayInCategory($('#category input[name=choice]:checked').val());
        });
    };
}());
