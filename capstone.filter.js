/*
 * Filter to enable angular to trust html so it can be displayed on a page
 */
(function() {
    'use strict';
    
    // the 'capstone' here matches the name of the app
    var myApp = angular.module("capstone");
    
    // use use 'trustHtml' in the html file as the name of the filter 
    myApp.filter("trustHtml", function($sce) {
       return function(html) {
            return $sce.trustAsHtml(html);
       };
    });
}
)();
