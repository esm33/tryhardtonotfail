<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
    
    /*
    if(!isset($_SESSION['successful_login']))
        {
            header("Location: login.php");
            exit(0);
        }  
        */
        

?>
<!DOCTYPE html> 
<html>
    <head>
        <title>Discussion Board</title>
        <link rel="stylesheet" href="./style.css">
        
 

    <script type="text/javascript">

        function HandleAddDiscussionFormResponse(response)
        {
            var text = JSON.parse(response);
        //    document.getElementById("textResponse").innerHTML = response+"<p>";    
            document.getElementById("textResponse").innerHTML = "response: "+text+"<p>";
            console.log("response:", text);
            if(text.status === "error") 
	    {
		document.getElementById("textResponse").innerHTML = "error: " + text.message+"</p>";
	    }
	    if(text.status === "success" || text === 1 || text === "1") 
	    {
		//document.getElementById("textResponse").innerHTML = "status: " + text.status+"</p>";
		document.getElementById("textResponse").innerHTML = "****Comment is successfully added****";
		SendGetDiscussionsRequest();
	    }
	    
        }

        function SendAddDiscussionFormRequest(header, comment)
        {
            var request = new XMLHttpRequest();
            request.open("POST","discussion_handling_communication.php",true);
            request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            request.onreadystatechange= function ()
            {
                
                if ((this.readyState == 4)&&(this.status == 200))
                {
                    HandleAddDiscussionFormResponse(this.responseText);
                    console.log("SendAddDiscussionFormRequest function ready state done.");
                }        
            }
           
           request.send("type=new_discussion&header="+encodeURIComponent(header)+"&comment="+encodeURIComponent(comment));
            console.log("type new_discussion sent HERE");
        }

    function getDiscussionInfo()
        {
        
            const header_text_input = document.getElementById("header");
            const header_input_value = header_text_input.value;
            
            const comment_text_input = document.getElementById("comment");
            const comment_input_value = comment_text_input.value;

            
            console.log("Header: ", header_input_value);
            console.log("Comment: ", comment_input_value);

            SendAddDiscussionFormRequest(header_input_value, comment_input_value);
            console.log("SendAddDiscussionFormRequest done");
            }
    

      function HandleGetDiscussionsResponse(response)
        {
            var text = JSON.parse(response);
        //    document.getElementById("textResponse").innerHTML = response+"<p>";    
            document.getElementById("list_of_discussions").innerHTML = "response: "+text+"<p>";
            console.log("get the disccusisons response:", text);
            if(text.status === "error") 
	    {
		document.getElementById("list_of_discussions").innerHTML = "error: " + text.message+"</p>";
	    }
	    
	   else if(text.discussions !=null)
	   {
    	
	document.getElementById("list_of_discussions").innerHTML = ""; //to clear error messages or previous responses when you do another search  
		for(var i=0; i < text.discussions.length; i++)
		{
	
		document.getElementById("list_of_discussions").innerHTML += "<div class='glass-card'><h2>" + text.discussions[i].header +"</h2><p><strong>user@</strong>" + text.discussions[i].username + "<p><strong>Comment: </strong>"+ text.discussions[i].comment+"</div><br>";
		
		}
	
    	}
    else
    {
    	document.getElementById("list_of_discussions").innerHTML = "nothing<p>";
    }



        }
        
        
        function SendGetDiscussionsRequest() //header,comment
        {
            var request = new XMLHttpRequest();
            request.open("POST","discussion_handling_communication.php",true);
            request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            request.onreadystatechange= function ()
            {
                
                if ((this.readyState == 4)&&(this.status == 200))
                {
                    HandleGetDiscussionsResponse(this.responseText);
                    console.log("SendGetDiscussionsRequest function ready state done.");
                }        
            }
           
           request.send("type=get_discussions");
            console.log("type get_discussion sent HERE");
        }
    
    window.addEventListener('load', function(){
	SendGetDiscussionsRequest();
	console.log("calling SendGetDiscussionsRequest");
});
        
<!-- ================================================================================================================ -->
        </script>
    </head>
    <body>
    
    <?php include 'navigationbar.php'; ?>
    
    <div class="container">
        <div class="glass-card">
            <h1> Make a contribution to our community forum</h1>
            <form id="discussion_form">
            	 <label>User@<?php echo htmlspecialchars($_SESSION['username_profile']); ?></label>
                    <input type="hidden" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username_profile']); ?>" />
            	
            	
                <div class="input-group">
                    <label for="header">HEADER: </label>
                    <input type="text" id="header" name="header" required />
                </div>
                
                <div class="input-group">
                    <label for="comment">COMMENT: </label>
                    <input type="text" id="comment" name="comment" required/><br>
                </div>
                
                
                <button type="button" onclick="getDiscussionInfo()" class="btn">Submit Discussion</button>
		        <div id="textResponse">
		    
		    	</div>
            </form>   
        </div>
        
        
        <div class="glass-card">
        	<h1> All Dicussions</h1>
        	<div id="list_of_discussions">
        		***empty***
        	</div>
        </div>
    </div>
    </body>
    
    
</html>

