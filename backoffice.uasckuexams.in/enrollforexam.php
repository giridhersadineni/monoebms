



  <form action="test.php" method="post">
          <h2>Select Your Papers</h2>
          <div id="formcontent">
              <h2>Compulsory Papers</h2>
           </div>
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



        <input type="submit" name="submit" value="Finish Selection">


    </form>


    <script>
        var form=document.getElementById('formcontent');
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
                    form.innerHTML+= '<input type="checkbox" name="subs[]"  value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }


            });

            }



        }
        xhr.open("GET",url,true);
        xhr.send();



    </script>
</div>
</div>
</div>
    </div><!--end of page wrapper-->







<?php include "footer.php";?>