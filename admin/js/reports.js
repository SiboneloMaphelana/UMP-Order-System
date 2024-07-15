// This function is triggered when the user selects a filter option in the revenue report.
// It sends an AJAX request to the server to fetch the revenue data based on the selected filter.
// The filterType parameter specifies the type of filter to be applied.
// The AJAX request is made to the 'model/fetch_revenue.php' file using the POST method.
// The data parameter is an object that contains the filter option selected by the user.
// In the success callback function, the response received from the server is used to update
// the revenue list on the webpage. The response is inserted into the element with the id 'revenueList'.
// If the AJAX request fails, an error message is logged to the console.
function filterRevenue(filterType) {
    $.ajax({
        // URL of the server file to send the request to.
        url: 'model/fetch_revenue.php',
        
        // HTTP method to be used for the request.
        method: 'POST',
        
        // Data to be sent to the server.
        data: { filter: filterType },
        
        // Callback function to be executed if the request is successful.
        success: function(response) {
            // Update the revenue list on the webpage with the fetched data.
            $('#revenueList').html(response); 
        },
        
        // Callback function to be executed if the request fails.
        error: function(xhr, status, error) {
            console.error('Error fetching revenue data:', error);
        }
    });
}

