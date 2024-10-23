document.addEventListener("DOMContentLoaded", function () {
  let pendingProcessingCount = 0;
  let currentPage = 1;
  let totalPages = 1;

  fetchOrders(currentPage);

  const eventSource = new EventSource("controllers/sse_server.php");
  eventSource.onmessage = function (event) {
    const data = JSON.parse(event.data);
    if (data.message) {
      console.log(data.message);
    } else {
      const order = data;
      if (order.status === "cancelled") {
        removeOrderFromTable(order.id);
      } else {
        addOrderToTable(order);
        if (order.status === "pending" || order.status === "processing") {
          pendingProcessingCount++;
          updateBadgeCount(pendingProcessingCount);
        }
      }
    }
  };

  function fetchOrders(page) {
    fetch(`controllers/fetch_existing_orders.php?page=${page}`)
      .then((response) => response.json())
      .then((data) => {
        const orders = data.orders;
        totalPages = data.totalPages;

        document.querySelector("#ordersTable tbody").innerHTML = "";

        orders.forEach((order) => addOrderToTable(order));
        pendingProcessingCount = orders.filter(
          (order) => order.status === "pending" || order.status === "processing"
        ).length;
        updateBadgeCount(pendingProcessingCount);

        updatePaginationControls();
      })
      .catch((error) => console.error("Error fetching orders:", error));
  }

  function addOrderToTable(order) {
    const ordersTableBody = document.querySelector("#ordersTable tbody");
    const orderRow = document.createElement("tr");
    orderRow.id = `order-${order.id}`;
    orderRow.innerHTML = `
            <td>${order.id}</td>
            <td>R${order.total_amount}</td>
            <td>${order.status}</td>
            <td>${new Date(order.order_date).toLocaleString()}</td>
            <td>${new Date(order.completed_at).toLocaleString()}</td>
            <td>
                <button class="btn btn-info btn-sm" onclick="viewOrderDetails(${
                  order.id
                })">View</button>
                <button class="btn btn-primary btn-sm" onclick="showUpdateStatusModal(${
                  order.id
                })">Update</button>
            </td>
        `;
    ordersTableBody.appendChild(orderRow);
  }

  function removeOrderFromTable(orderId) {
    const orderRow = document.getElementById(`order-${orderId}`);
    if (orderRow) {
      orderRow.remove();

      pendingProcessingCount--;
      updateBadgeCount(pendingProcessingCount);
    }
  }

  function updateBadgeCount(count) {
    const badge = document.getElementById("notificationBadge");
    badge.textContent = count;
  }

  function updatePaginationControls() {
    document.getElementById("prevPage").disabled = currentPage === 1;
    document.getElementById("nextPage").disabled = currentPage === totalPages;
  }

  document.getElementById("prevPage").addEventListener("click", function () {
    if (currentPage > 1) {
      currentPage--;
      fetchOrders(currentPage);
    }
  });

  document.getElementById("nextPage").addEventListener("click", function () {
    if (currentPage < totalPages) {
      currentPage++;
      fetchOrders(currentPage);
    }
  });

  window.viewOrderDetails = function (orderId) {
    console.log("View Order Details clicked for order ID:", orderId);

    fetch(`controllers/fetch_order_details.php?order_id=${orderId}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error(
            "Network response was not ok. Status: " + response.statusText
          );
        }
        return response.json();
      })
      .then((order) => {
        console.log("Order details received:", order);
        if (order.error) {
          throw new Error(order.error);
        }

        const modalBody = document.querySelector("#viewOrderModal .modal-body");
        modalBody.innerHTML = `
                    <p><strong>Order ID:</strong> ${order.id}</p>
                    <p><strong>Total Amount:</strong> R${order.total_amount}</p>
                    <p><strong>Status:</strong> ${order.status}</p>
                    <p><strong>Order Date:</strong> ${new Date(
                      order.order_date
                    ).toLocaleString()}</p>
                    <p><strong>Items:</strong></p>
                    <ul>
                        ${order.items
                          .map(
                            (item) =>
                              `<li>${item.name} - R${item.price} x ${item.quantity}</li>`
                          )
                          .join("")}
                    </ul>
                `;

        const viewOrderModal = new bootstrap.Modal(
          document.getElementById("viewOrderModal")
        );
        viewOrderModal.show();
      })
      .catch((error) => {
        console.error("Error fetching order details:", error);
        alert("Failed to fetch order details. Please try again later.");
      });
  };

  window.showUpdateStatusModal = function (orderId) {
    document.getElementById("orderIdToUpdate").value = orderId;
    const updateStatusModal = new bootstrap.Modal(
      document.getElementById("updateStatusModal")
    );
    updateStatusModal.show();
  };

  document.getElementById("updateStatusForm").addEventListener("submit", function (event) {
      event.preventDefault();
      const formData = new FormData(this);
      fetch("controllers/update_order_status.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((result) => {
          if (result.success) {
            showSuccessAlert("Order status updated successfully!", closeModal);
            fetchOrders(currentPage);
          } else {
            showErrorAlert("Error updating order status.");
          }
        })
        .catch((error) => console.error("Error updating order status:", error));
    });

  // Function to show success alert
  function showSuccessAlert(message, callback) {
    Swal.fire({
      icon: "success",
      title: "Success!",
      text: message,
      showConfirmButton: false,
      timer: 2000, // Auto dismiss after 3 seconds
    }).then(() => {
      if (callback) callback(); // Call the callback to close the modal
    });
  }

  // Function to show error alert
  function showErrorAlert(message) {
    Swal.fire({
      icon: "error",
      title: "Oops!",
      text: message,
      showConfirmButton: false,
      timer: 3000, // Auto dismiss after 3 seconds
    });
  }

  // Function to close the modal
  function closeModal() {
    const updateStatusModal = bootstrap.Modal.getInstance(
      document.getElementById("updateStatusModal")
    );
    updateStatusModal.hide(); // Hide the modal
  }

  /*eventSource.onerror = function (event) {
    console.error("Error:", event);
  };*/
});
