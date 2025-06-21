// Handle the change event for the range selection
document.getElementById('monthSelect').addEventListener('change', function () {
    const selectedRange = this.value;

    // If Custom date is selected, alert the user (you can improve it later)
    if (selectedRange === 'Custom date') {
        alert('Custom date selection feature is under development.');
        return;
    }

    // Send AJAX request using jQuery
    $.ajax({
        url: `/dashboard?filter=${selectedRange}`, // Send the selected filter to the controller
        type: 'GET',
        dataType: 'json', // Expect a JSON response
        success: function(data) {
            // Log the returned data for debugging
            console.log(data); // You can check the data returned by the server

            // Update the chart with the new data
            salesChart.data.labels = data.labels; // Update labels (months)
            salesChart.data.datasets[0].data = data.purchases; // Update purchases data
            salesChart.data.datasets[1].data = data.sales; // Update sales data
            salesChart.update(); // Redraw the chart
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error); // Handle AJAX error
        }
    });
});
