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
