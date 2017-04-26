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
            parameters: {type: type, json: false},
            //contentType: "text/xml",
            onSuccess: function (ajax) {
                console.log(ajax);
                var category = ajax.responseXML.getElementsByTagName("category");
                for (var i = 0; i < category.length; i++) {
                    var id = category[i].getElementsByTagName("id")[0].firstChild.nodeValue;
                    var name = category[i].getElementsByTagName("name")[0].firstChild.nodeValue;
                    //make a radio button containing category
                    var radioHtml = "<input type='radio' name='choice' id='" + id + "' value='" + name +"'>" + name;
                    $("category").appendChild(radioHtml);
                }
            },
            onFailure: ajaxFailure
        });
    }

    function displayCategoryJson() {
        var type = "list_category";
        new Ajax.Request("booklist.php",
            {
            method: "post",
            parameters: {type: type, json: true},
            //contentType: "application/json",
            onSuccess: function (ajax) {
                console.log(ajax);
                var data = JSON.parse(ajax.responseText);
                console.log("data" + data[1].name);
                for (var i = 0; i < data.length; i++) {
                    var name = data[i].name;
                    //make a radio button containing category
                    //Work around for IE notl etting you set the names of radio buttons dynamically

                    var radioHtml = "<input type='radio' name='choice' id='" + i + "' value='" 
+ name +"'>" + name;
                    console.log(radioHtml);
                    var radioFragment = document.createElement('div');
                    radioFragment.innerHTML = radioHtml;
                    var labelHtml = "<label for='" + i + "'>"+name+"</label>";
                    var labelFragment = document.createElement('div');
                    labelFragment.innerHTML = labelHtml;
                    $("category").appendChild(radioFragment.firstChild);   
                    $("category").appendChild(labelFragment.firstChild); 
                }
            },
            onFailure: ajaxFailure
        });
    }

    function displayInCategory($category) {
        var type = "in_category";
    }

    function displayInCategoryJson($category) {
        var type = "in_category";
    }

    //Window load function
    window.onload=function () {
        //Check if the get param format is set to json
        var json = window.location.search.substr(1);
        if (json == "format=json") {
           //display available categories on load using json
           displayCategoryJson();
           //On select categories,display books in category
            $("list_books").addEventListener("click", function () {
                displayInCategoryJson($('#category input[name=choice]:checked').val());
            });
        } else {
            //display available categories on load using xml
            displayCategory();
            //On select categories,display books in category
            $("list_books").addEventListener("click", function () {
                displayInCategory($('#category input[name=choice]:checked').val());
            });
        }
        
    };
}());
