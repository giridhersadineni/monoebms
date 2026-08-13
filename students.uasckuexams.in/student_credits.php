<?php
include "header.php";
include "config.php";

// Check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

    // Fetch available exams from the database
    $examQuery = "SELECT DISTINCT(scheme) as scheme FROM  students";
    $examResult = $conn->query($examQuery);
    
    // Check if the "Fetch" button is clicked
    if (isset($_GET['scheme'])) {
        $scheme = intval($_GET['scheme']);
        // Fetch results based on the selected exam ID
        $sql = "SELECT * from student_credits left join students on students.haltckt = student_credits.HALLTICKET and students.scheme = $scheme 
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
                    <!-- Exam selection drop-down and Fetch button -->
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Select Exam:</label>
                        <div class="col-md-4">
                            <select class="form-control" id="schemeSelect">
                                <option value="">Select Scheme</option>
                                <?php
                                // Populate the drop-down with available exams
                                while ($examRow = $examResult->fetch_assoc()) {
                                    echo '<option value="' . $examRow['scheme'] . '">' . $examRow['scheme'] . '</option>';
                                }
                                ?>
                            </select>
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
                                    <th>STUDENT NAME</th>
                                    <th>STUDENT CREDITS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="resultsTableBody">
                                <!-- Results will be dynamically loaded here based on the selected exam -->
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
    // Function to fetch results based on the selected exam ID
    function fetchResults() {
        const schemeSelected = document.getElementById("schemeSelect").value;
        if (schemeSelected) {
            // Show the loading spinner
            document.getElementById("loadingSpinner").style.display = "block";

            // Fetch results via AJAX
            const url = `fetch_studentcredits.php?scheme=${schemeSelected}`;
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

