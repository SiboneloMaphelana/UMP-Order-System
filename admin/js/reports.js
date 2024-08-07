/**
 * Sends an AJAX request to fetch revenue data based on the specified filter type.
 *
 * @param {string} filterType - The type of filter to apply to the revenue data.
 * @return {void} This function does not return anything.
 */
function filterRevenue(filterType) {
  $.ajax({
    url: "model/fetch_revenue.php",
    method: "POST",
    data: { filter: filterType },

    success: function (response) {
      $("#revenueList").html(response);
    },

    error: function (xhr, status, error) {
      console.error("Error fetching revenue data:", error);
    },
  });
}
