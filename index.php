
<html>
	<head>
		<title>Chunked upload file example</title>
		
		
		 <script src="/js/jquery-3.3.1.min.js"></script>	
		 <script src="/bootstrap/js/bootstrap.min.js"></script>
         <link media="all" type="text/css" rel="stylesheet" href="https://shop2.3sferi.com/bootstrap/css/bootstrap.min.css">

		 
		  <link media="all" type="text/css" rel="stylesheet" href="https://shop2.3sferi.com/font-awesome-css/all.css">  
		  <script src="/js/fontawesome-js/all.js"></script>	
	</head>
	<body>
	<style>
	 #file_upld, .progress-stat{display:none;}
	</style>
	 <div class='content'>
	 <div class='container text-center'>
	  <h1 class='text-lg-center text-sm-center text-md-center' style='font-size:25px; margin-top:30%;'>Upload file</h1>
	  <div class='row justify-content-center' style="padding:20px;">
	   <button class='btn btn-primary upload_btn' type='button'><i class='fas fa-upload'></i> Select file</button>
	  </div>
	  <h2 class='progress-stat text-md-center' style="margin-top:50px;font-size:23px;"></h2>
	  <h2 class='error text-danger text-md-center' style="margin-top:50px;font-size:23px;"></h2>
	 </div>
	  <input type='file' value='' id='file_upld'>
	</div>
	
	</body>

</html>
  

<script>
var oper = true;
$(".upload_btn").click(function(){ 
 if(oper!==false)$("#file_upld").trigger("click"); 
});


/*Check if file was target*/

$("#file_upld").change(function(){ 
 var file = $(this)[0].files[0];
 
 if(typeof file=='undefined')alert("File  ");
 if(oper!==false){
  $(".progress-stat").fadeIn(300);  
  upload_file(file);
 } 
});


function upload_file(file){        
    var loaded = 0, 
		step = 1000, 
		total = file.size, 
		start = 0,   
    reader = new FileReader(),  
		blob = file.slice(start, step), 
		last_chunk = getCookie("last_chunk"), 
		prog_bar = $(".progress-stat");
    
	oper=false;
    if(last_chunk!="")loaded = parseInt(last_chunk);
     
    blob = file.slice(loaded, loaded+step); 
    reader.readAsDataURL(blob);   

    reader.onload = function(e){            
      var d = e.target.result, formdata = new FormData, progress = 0; 
      
      formdata.append('data', d); // a chunk of data geted from target file and encoded using base64
      formdata.append('loaded',loaded);
      formdata.append('filename', file.name);
     
      $.ajax({ 
	   url:"upload.php", 
	   type: 'POST', 
	   data: formdata,  
	   processData: false,	
	   contentType: false,
	   success: function(data){
   
		loaded+=step;        
        progress = parseInt((loaded/total)*100);
        if(progress>100)progress=100;
         
		prog_bar.html("Loaded: "+progress+"%");
        if(loaded<=total){    
		 setCookie("last_chunk", loaded, 365);
         blob = file.slice(loaded, loaded+step);  
         reader.readAsDataURL(blob);        
        }else{ 
		  loaded = total; 
		  prog_bar.html("<div class='text-success'>Uploading is fisnished</div>"); 
		  
		  oper=true;
		  remove_cookie("last_chunk");	
		}
     }, 
	 error: function() {
      $(".error").html("Something is wrong with upload process!");
	 }	
     });
    };
}


 function setCookie(cname, cvalue, exdays){
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
 }

 function getCookie(cname){
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ')c = c.substring(1);
    if(c.indexOf(name) == 0)return c.substring(name.length, c.length);
  }
  return "";
 }

 function remove_cookie(name){ document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;'; }
 
</script>

