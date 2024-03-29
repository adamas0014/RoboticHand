<!DOCTYPE html>


<?php
    include_once 'file-functions.php';
?>

<html lang="en">
<head>
    <!-- Javascript libraries-->

    

    <!--HTML BoilerPlate Content" -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PROCTECH 4IT3 - Lab6A">
    <meta name="author" content="Adam Sokacz">
    <meta name="robots" content="index, follow">

    <!--
    BoilerPlate items for Social Media Robots
    -->
    <meta property="og:title" content="PROCTECH 4IT3"/>
    <meta property="og:type:article:author" content="Adam Sokacz"/>
    <meta property="og:type:article:section" content="Adam Sokacz 4ID3 Project"/>
    <meta property="og:url" content=""/>

    <title>Remote Monitoring</title>

    <!--
      BoilerPlate BOOTSTRAP!
   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet" href="style.css?">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,600&display=swap" rel="stylesheet">
    
    <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js" integrity="sha384-0pzryjIRos8mFBWMzSSZApWtPl/5++eIfzYmTgBBmXYdhvxPc+XcFEk+zJwDgWbP" crossorigin="anonymous"></script>


    <style>
        .centreTitle
        {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .mqttconfig{
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
    }

legend.scheduler-border {
    font-size: 1.2em !important;
    font-weight: bold !important;
    text-align: left !important;
}


 /* The switch - the box around the slider */
 .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
} 


</style>



<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
 	<script type = "text/javascript" 
         src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type = "text/javascript"></script>
    
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script>

	function onConnectionLost(){
	console.log("connection lost");
	document.getElementById("status").innerHTML = "Connection Lost";
	document.getElementById("messages").innerHTML ="Connection Lost";
	connected_flag=0;


	}
	function onFailure(message) {
		console.log("Failed");
		document.getElementById("messages").innerHTML = "Connection Failed- Retrying";
        setTimeout(MQTTconnect, reconnectTimeout);
        }
	function onMessageArrived(r_message){
        // My stuff
        console.log(r_message.payloadString);
        obj = JSON.parse(r_message.payloadString);
        document.getElementById("thumbFinger").innerHTML = obj.thumbFinger;
        document.getElementById("indexFinger").innerHTML = obj.indexFinger;
        document.getElementById("middleFinger").innerHTML = obj.middleFinger;
        document.getElementById("ringFinger").innerHTML = obj.ringFinger;
        document.getElementById("pinkyFinger").innerHTML = obj.pinkyFinger;

        let img = document.querySelector('img');
        img.src = "img/hand" + obj.thumbFinger + obj.indexFinger + obj.middleFinger + obj.ringFinger + obj.pinkyFinger + ".jpg";
        console.log(img.src);
        //NOT my stuff
		out_msg="Message received "+r_message.payloadString+"<br>";
		out_msg=out_msg+"Message received Topic "+r_message.destinationName;
		//console.log("Message received ",r_message.payloadString);
		//console.log(out_msg);
		document.getElementById("messages").innerHTML =out_msg;
		}
	function onConnected(recon,url){
	    console.log(" in onConnected " +reconn);

	}
	function onConnect() {
	  // Once a connection has been made, make a subscription and send a message.
	document.getElementById("messages").innerHTML ="Connected to "+host +"on port "+port;
	connected_flag=1
	document.getElementById("status").innerHTML = "Connected";
	console.log("on Connect "+connected_flag);
	//mqtt.subscribe("sensor1");
	//message = new Paho.MQTT.Message("Hello World");
	//message.destinationName = "sensor1";
	//mqtt.send(message);
        document.getElementById("thumbFinger").innerHTML = "-";
        document.getElementById("indexFinger").innerHTML = "-";
        document.getElementById("middleFinger").innerHTML = "-";
        document.getElementById("ringFinger").innerHTML = "-";
        document.getElementById("pinkyFinger").innerHTML = "-";

  
	  }

    function MQTTconnect() {
	document.getElementById("messages").innerHTML ="";
	var s = document.forms["connform"]["server"].value;
	var p = document.forms["connform"]["port"].value;
	if (p!="")
	{
	console.log("ports");
		port=parseInt(p);
		console.log("port" +port);
		}
	if (s!="")
	{
		host=s;
		console.log("host");
		}
	console.log("connecting to "+ host +" "+ port);
	var x=Math.floor(Math.random() * 10000); 
	var cname="orderform-"+x;
	mqtt = new Paho.MQTT.Client(host,port,cname);
	//document.write("connecting to "+ host);
	var options = {
        //useSSL:true,
        timeout: 3,
		onSuccess: onConnect,
		onFailure: onFailure,
      
     };
	
        mqtt.onConnectionLost = onConnectionLost;
        mqtt.onMessageArrived = onMessageArrived;
		//mqtt.onConnected = onConnected;

	mqtt.connect(options);
	return false;
  
 
	}
	function sub_topics(){
		document.getElementById("messages").innerHTML ="";
		if (connected_flag==0){
		out_msg="<b>Not Connected so can't subscribe</b>"
		console.log(out_msg);
		document.getElementById("messages").innerHTML = out_msg;
		return false;
		}
	var stopic= document.forms["subs"]["Stopic"].value;
	console.log("Subscribing to topic ="+stopic);
	mqtt.subscribe(stopic);
	return false;
	}
	function send_message(){
		document.getElementById("messages").innerHTML ="";
		if (connected_flag==0){
		out_msg="<b>Not Connected so can't send</b>"
		console.log(out_msg);
		document.getElementById("messages").innerHTML = out_msg;
		return false;
		}
		var msg = document.forms["smessage"]["message"].value;
		console.log(msg);

		var topic = document.forms["smessage"]["Ptopic"].value;
		message = new Paho.MQTT.Message(msg);
		if (topic=="")
			message.destinationName = "test-topic"
		else
			message.destinationName = topic;
		mqtt.send(message);
		return false;
	}

	
    </script>




</head>
<body>

<style>
.navbar-fixed {
  left: 30%; /* just an estimate of your sidebar's width */
  
}
</style>

<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
  <a class="navbar-brand" href="#"style="width: 33%;">Dashboard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon "></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto" >
    <li class="nav-item">
        <a class="nav-link" href="docs.html">Docs</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="https://github.com/adamas0014/RoboticHand"><i class="fab fa-github"></i> Github</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="mailto:rajam4@mcmaster.ca"><i class="far fa-paper-plane"></i>&nbsp;Muizz</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="mailto:rajam4@mcmaster.ca"><i class="far fa-paper-plane"></i>&nbsp;Walid</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="mailto:rajam4@mcmaster.ca"><i class="far fa-paper-plane"></i>&nbsp;Adam</a>
      </li>
    </ul>

  </div>
</nav>

    <br/><br/><br/><br/>



    <script>
        var connected_flag=0	
        var mqtt;
        var reconnectTimeout = 2000;   
    </script>
     
    

    <div class = "container-fluid mqttconfig" >
        <br><br><br>
        <form name="connform" action="" onsubmit="return MQTTconnect()">
        <table class = "table table-striped table-hover" style = "width: 50rem; vertical-align: top;">
            <thead class = "table-dark" >
                <tr>
                    <th scope = "col" colspan = "2">
                        Configure MQTT
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Status
                    </td>
                    <td>
                        <div id="status">Not Connected</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Server
                    </td>
                    <td>
                        <input type="text" name="server" value = "test.mosquitto.org"> 
                    </td>
                </tr>
                <tr>
                    <td>
                        Port
                    </td>
                    <td>
                        <input type="text" name="port" value = "8080">
                    </td>
                </tr>
                <tr>
                    <td>
                        Connect
                    </td>
                    <td>
                        <input name="conn" type="submit" value="Connect">
                        <input TYPE="button" name="discon " value="Disconnect" onclick="disconnect()">
                        </form>
                    </td>
                </tr>

                <form name="subs" action="" onsubmit="return sub_topics()">
                <tr>
                    <td>
                        Topic
                    </td>
                    <td>
                        <input type="text" name="Stopic" value = "robothand">
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Subscribe
                    </td>
                    <td>
                        <input type="submit" value="Subscribe">
                    </td>               
                </tr>
            </form>
                <tr>
                    <td>Messages</td>
                    <td>
                        Messages:<p id="messages"></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    





                
        
        <table class = "table table-hover table-md" style = "width: 50rem; vertical-align: top;">
            <thead class = "table-dark" >
               <tr>
                   <td colspan = "5" style = "font-weight: bold;">
                       Finger Data
                   </td>
               </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope = "col">Thumb</th>
                    <th scope = "col">Index</th>
                    <th scope = "col">Middle</th>
                    <th scope = "col">Ring</th>
                    <th scope = "col">Pinky</th>
                </tr>
                <tr>
                    <td>
                        <p id = "thumbFinger"></p>
                    </td>
                    <td>
                        <p id = "indexFinger"> </p>
                    </td>
                    <td>
                        <p id = "middleFinger"> </p>
                    </td>
                    <td>
                        <p id = "ringFinger"> </p>
                    </td>
                    <td>
                        <p id = "pinkyFinger"> </p>
                    </td>
                </tr>
                <tr>
                    <td colspan = "5" rowspan = "10">
                        <img src = "img/hand11111.jpg" style = "width: 40%; display: block; margin-left: auto; margin-right: auto;">
                    </td>
                </tr>
                <tr></tr>
            </tbody>
        </table>
    </form>


<table class = "table table-hover table-md" style = "width: 50rem; vertical-align: top;">
            <thead class = "table-dark" >
               <tr>
                   <td colspan = "5" style = "font-weight: bold;">
                       Saved Data
                   </td>
               </tr>
            </thead>
            <tbody>
                <form action="<? $_SERVER['PHP_SELF'] ?>?option=record" method = "post"> 
                <tr>
                    <td colspan = "1">
                        Collect Data
                    </td>
                    <td colspan = "2">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                          </label>
                    </td>
                    <td>
                        Delete Data
                    </td>
                    <td> <button type = "submit" name = "button">Button</button>
                    </td>                    
                </tr>
                <tr>
                    <td colspan = "5">


                        <?php
                        
                            $dataArr = readCSV("collectedData.csv");
                            $thumbArr = array();
                            $indexArr = array();
                            $middleArr = array();
                            $ringArr = array();
                            $pinkyArr = array();
                            for($i = 0; $i < count($dataArr); $i++){
                                $thumbArr[$i] = array("y" => $dataArr[$i][0], "label" => $i);
                                $indexArr[$i] = array("y" => $dataArr[$i][1], "label" => $i);
                                $middleArr[$i] = array("y" => $dataArr[$i][2], "label" => $i);
                                $ringArr[$i] = array("y" => $dataArr[$i][3], "label" => $i);
                                $pinkyArr[$i] = array("y" => $dataArr[$i][4], "label" => $i);

                            }
                            
 
                        ?>
                        <script>
                            window.onload = function () {
                            
                             var chartThumb = new CanvasJS.Chart("chartContainerThumb", {
                                 title: {
                                     text: "Thumb"
                                 },
                                 axisY: {
                                     title: "0 = Open, 1 = Closed"
                                 },
                                 data: [{
                                     type: "line",
                                     dataPoints:  <?php echo json_encode($thumbArr, JSON_NUMERIC_CHECK); ?>
                                 }]
                             });
                             chartThumb.render();

                             }

                        </script>
                        <div id="chartContainerThumb" style="height: 370px; width: 100%;"></div>
        
                    </td>
                </tr>       





                </form>
            </tbody>
        </table>







</div>









<br><br><br><br><br><br>
<footer class="footer mt-auto py-3 bg-dark">
    <div class="container">
        <span class="text-muted cont"> <p  style = "text-align: center;">4ID3 Robot Hand Project 2022</p></span>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</html>




