<?php
include "header.php";
include "config.php";

// Check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the "Fetch" button is clicked
if (isset($_GET['hallticket'])) {
    $selectedHallticket = mysqli_real_escape_string($conn, $_GET['hallticket']);

    // Fetch results based on the selected hallticket
    $sql = "SELECT HALLTICKET, EXAMID, EXAMNAME FROM RESULTS 
            LEFT JOIN examsmaster ON RESULTS.EXAMID = examsmaster.EXID
            WHERE HALLTICKET = '$selectedHallticket'
            ORDER BY HALLTICKET";
    $result = $conn->query($sql);
}
?>

<!-- End Left Sidebar  -->

<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <!--<h3 class="text-primary">Dashboard</h3>-->
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Enrolled Details</li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->

<!-- ... (previous PHP and HTML code) ... -->

<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Page Content -->
    <div class="row wrapper">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Hallticket input box and Fetch button -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Enter Hallticket:</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="hallticketInput" placeholder="Enter Hallticket">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" onclick="fetchResults()">Fetch</button>
                            <!-- Loading spinner -->
                            <div id="loadingSpinner" style="display: none;">
                                <i class="fa fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ACTION</th>
                                    <th>HALLTICKET</th>
                                    <th>EXAMID</th>
                                    <th>EXAM NAME</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="resultsTableBody">
                                <?php
                                if (isset($result) && $result->num_rows > 0) {
                                    // Display the fetched results
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>ACTION BUTTONS</td>';
                                        echo '<td>' . $row['HALLTICKET'] . '</td>';
                                        echo '<td>' . $row['EXAMID'] . '</td>';
                                        echo '<td>' . $row['EXAMNAME'] . '</td>';
                                        echo '<td>ANOTHER ACTION BUTTON</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    // Display a message if no results found
                                    echo '<tr><td colspan="5">No results found.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- footer -->
<?php include "datatablefooter.php"; ?>

<script>
    // Function to fetch results based on the entered hallticket
    function fetchResults() {
        const selectedHallticket = document.getElementById("hallticketInput").value;
        if (selectedHallticket.trim() !== "") {
            // Show the loading spinner
            document.getElementById("loadingSpinner").style.display = "block";

            // Fetch results via AJAX
            const url = `fetchstudentmemos.php?hallticket=${selectedHallticket}`;
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    // Update the table body with fetched results
                    document.getElementById("resultsTableBody").innerHTML = data;
                    // Hide the loading spinner once the results are loaded
                    document.getElementById("loadingSpinner").style.display = "none";
                })
                .catch(error => console.error('Error fetching results:', error));
        }
    }
</script>
