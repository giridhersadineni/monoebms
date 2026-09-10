<?php include "header.php";?>
<div class="page-wrapper">


<!-- Container fluid  -->

<!-- Start Page Content -->
<style>
input{
    font-size:1.4em;
    color:black;
}
</style>
<div class="page-wrapper">

<div class="Container">
    <div class="row">
  <div class="col-md-9">
  <form action="test.php" method="post" >
          <h3>Select subjects you are appearing</h3>
          <h3>Regular Students should select all subjects</h3>
          <h4>Supplementary Students should select only Failed Exams</h4>
          <div id="formcontent">
              <h2>Compulsory Papers</h2>           
           <div id="E1">
               <h3>Select your Elective 1</h3>
           </div> 
           <div id="E2">
               
           <h3>Select your Elective 2</h3>
            </div> 
            <div id="E3">
                   
               <h3>Select your Elective 3</h3>
           </div> 
           <div id="E3">
               <h3>Select your Elective 4</h3>
           </div>

        </div>

        <input type="submit" value="Finish Selection">
    

        <span id="formend"></span>
    </form>


    <script>
    function loadSubjects(){
        var f=document.getElementById('formcontent');
        var e1=document.getElementById('E1');
        var e2=document.getElementById('E2');
        var e3=document.getElementById('E3');
        var url="../api/course/getsubjects.php?course=BA&group=JSP&medium=em";
        console.log(url);
        var xhr=new XMLHttpRequest();
        xhr.onreadystatechange=function(){
            if(this.readyState==4 && this.status==200){
           
                var papers=JSON.parse(xhr.responseText);
            papers.forEach(paper=> {
                if(paper.PAPERGROUP=="E1"){
                    
                    e1.innerHTML+= '<input type="radio" name="E1"  required value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E2"){
                
                    e2.innerHTML+= '<input type="radio" name="E2" required value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E3"){

                    e3.innerHTML+= '<input type="radio" name="E3" required value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else{
                    f.innerHTML+= '<input type="checkbox" name="subs[]"  value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }


            }
            
            );
            
            
            }
            


        }
        xhr.open("GET",url,true);
        xhr.send();
        // var prependtext='<form action="test.php" method="post" name="paperform">';
        // var contentindiv=f.innerHTML;
        // prependtext+=contentindiv;
        // prependtext+='</form>';
        // form.innerHTML=prependtext;
        
    }
    document.addEventListener('DOMContentLoaded', loadSubjects, false);

    function formsubmit(){
        alert("form ids"+document.forms.length+"form name"+document.forms[0].name);
        document.forms[2].submit();
    }
    </script>
</div>
</div>
</div>
    </div><!--end of page wrapper-->
 






<?php include "footer.php";?>