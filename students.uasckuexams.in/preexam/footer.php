<!-- footer -->
            
            <!-- End footer -->
       
        <!-- End Page wrapper  -->
    </div>
    <footer class="footer">2018 all rights reserved @ SYS Technology</footer>
    <!-- End Wrapper -->
    <!-- All Jquery -->
     <script src="https://www.uascku.ac.in/ebms/js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="https://www.uascku.ac.in/ebms/bootstrap/js/popper.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="https://www.uascku.ac.in/ebms/js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="https://www.uascku.ac.in/ebms/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="https://www.uascku.ac.in/ebms/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="https://www.uascku.ac.in/ebms/js/scripts.js"></script>
    <script src="https://www.uascku.ac.in/ebms/js/lib/datatables/datatables.min.js"></script>
    <script src="https://www.uascku.ac.in/ebms/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://www.uascku.ac.in/ebms/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://www.uascku.ac.in/ebms/js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://www.uascku.ac.in/ebms/js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://www.uascku.ac.in/ebms/js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://www.uascku.ac.in/ebms/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://www.uascku.ac.in/ebms/js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://www.uascku.ac.in/ebms/js/lib/datatables/datatables-init.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  
    
    <?php 
        if(isset($_GET['message'])){
            if($_GET['message']=="gradeadded"){
            echo "<script>"."swal({title: 'Good job!',text: '"."Grade Addedd Successfully"."',icon: 'success',button: 'Aww yiss!',});" . "</script>";
            }
        }
    ?>

</body>

</html>






