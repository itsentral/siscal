<div class="row">
	<div class="col-sm-12 text-center">
		<video autoplay id="video"></video>
	</div>
</div>
<div class='row'>
	<div class="col-sm-12">
		<div class="form-group">
			<label class="control-label">Select Camera Source</label>
			<div>
				<select id="video-source" name="video-source">
					
				</select>
			</div>
			
		</div>
	</div>	
</div>
<div class="row">
	
	<div class="col-sm-12 col-xs-12 text-center">
		<button type="button" class="btn btn-sm bg-blue-active" id="btnScreenshot">
			<i class="fa fa-camera"></i> Ambil Via Kamera
		</button>
	</div>
	<input type="hidden" name="kategori" id="kategori" value="<?php echo $kategori;?>">
	<canvas class="is-hidden" id="canvas"></canvas>
</div>


<script>
	/*
	const video = document.querySelector("#video");
	const btnPlay = document.querySelector("#btnPlay");
	const btnPause = document.querySelector("#btnPause");
	const btnScreenshot = document.querySelector("#btnScreenshot");
	const btnChangeCamera = document.querySelector("#btnChangeCamera");
	const screenshotsContainer = document.querySelector("#screenshots");
	const canvas = document.querySelector("#canvas");
	const devicesSelect = document.querySelector("#devicesSelect");
	*/
	 const constraints = {};
	  
	   // use front face camera
	  let useFrontCamera = true;

	  // current video stream
	  let videoStream;
    $(document).ready(function(){
		if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
			console.log("enumerateDevices is not supported.");
		}
		let videoSourcesSelect = document.getElementById("video-source");
		let videoPlayer = document.getElementById("video");

		// Create Helper to ask for permission and list devices
		let MediaStreamHelper = {
			// Property of the object to store the current stream
			_stream: null,
			// This method will return the promise to list the real devices
			getDevices: function() {
				return navigator.mediaDevices.enumerateDevices();
			},
			// Request user permissions to access the camera and video
			requestStream: function() {
				if (this._stream) {
					this._stream.getTracks().forEach(track => {
						track.stop();
					});
				}

				const videoSource = videoSourcesSelect.value;
				const constraints = {					
					video: {
						deviceId: videoSource ? {exact: videoSource} : undefined,
						width: {
							min: 160,
							ideal: 240,
							max: 320,
						  },
						  height: {
							min: 160,
							ideal: 240,
							max: 320,
						  }
					}
				};
			
				return navigator.mediaDevices.getUserMedia(constraints);
			}
		};
		
		// Request streams (audio and video), ask for permission and display streams in the video element
		MediaStreamHelper.requestStream().then(function(stream){
			console.log(stream);
			// Store Current Stream
			MediaStreamHelper._stream = stream;

			// Select the Current Streams in the list of devices
			videoSourcesSelect.selectedIndex = [...videoSourcesSelect.options].findIndex(option => option.text === stream.getVideoTracks()[0].label);

			// Play the current stream in the Video element
			videoPlayer.srcObject = stream;
			
			// You can now list the devices using the Helper
			MediaStreamHelper.getDevices().then((devices) => {
				// Iterate over all the list of devices (InputDeviceInfo and MediaDeviceInfo)
				devices.forEach((device) => {
					let option = new Option();
					option.value = device.deviceId;

					// According to the type of media device
					switch(device.kind){
						// Append device to list of Cameras
						case "videoinput":
							option.text = device.label || `Camera ${videoSourcesSelect.length + 1}`;
							videoSourcesSelect.appendChild(option);
							break;
						
					}

					console.log(device);
				});
			}).catch(function (e) {
				console.log(e.name + ": " + e.message);
			});
		}).catch(function(err){
			console.error(err);
		}); 
		
		videoSourcesSelect.onchange = function(){
			MediaStreamHelper.requestStream().then(function(stream){
				MediaStreamHelper._stream = stream;
				videoPlayer.srcObject = stream;
			});
		};
		
		
		  function stopVideoStream() {
			if (videoStream) {
			  videoStream.getTracks().forEach((track) => {
				track.stop();
			  });
			}
		  }

		  // initialize
		  async function initializeCamera() {
			  stopVideoStream();
			  constraints.video.facingMode = useFrontCamera ? "user" : "environment";

			  try {
				videoStream = await navigator.mediaDevices.getUserMedia(constraints);
				videoPlayer.srcObject = videoStream;
			  } catch (err) {
				alert("Could not access the camera");
			  }
		  }

		  initializeCamera();
		  // take screenshot
		  $('#btnScreenshot').click(function(){		  
			const img = document.createElement("img");
			canvas.width = videoPlayer.videoWidth;
			canvas.height = videoPlayer.videoHeight;
			canvas.getContext("2d").drawImage(videoPlayer, 0, 0);
			//img.src = canvas.toDataURL("image/jpeg");
			
			let kategori	= $('#kategori').val();
			if(kategori=='depan'){
				$("#pic_webcam_depan").val(canvas.toDataURL("image/jpeg"));
				document.getElementById('result_camera_depan').innerHTML = '<img src="'+canvas.toDataURL("image/jpeg")+'" class="img-fluid"/>';
				$("#ContactModal").modal('hide');
				$(".btn-hapus-foto_depan").show();
			}else if(kategori=='kanan'){
				$("#pic_webcam_kanan").val(canvas.toDataURL("image/jpeg"));
				document.getElementById('result_camera_kanan').innerHTML = '<img src="'+canvas.toDataURL("image/jpeg")+'" class="img-fluid"/>';
				$("#ContactModal").modal('hide');
				$(".btn-hapus-foto_kanan").show();
			}else if(kategori=='kiri'){
				$("#pic_webcam_kiri").val(canvas.toDataURL("image/jpeg"));
				document.getElementById('result_camera_kiri').innerHTML = '<img src="'+canvas.toDataURL("image/jpeg")+'" class="img-fluid"/>';
				$("#ContactModal").modal('hide');
				$(".btn-hapus-foto_kiri").show();
			}else{
				$("#pic_webcam_back").val(canvas.toDataURL("image/jpeg"));
				document.getElementById('result_camera_back').innerHTML = '<img src="'+canvas.toDataURL("image/jpeg")+'" class="img-fluid"/>';
				$("#ContactModal").modal('hide');
				$(".btn-hapus-foto_back").show();
			}
			//console.log(canvas.toDataURL("image/jpeg"));
			//screenshotsContainer.html(img);
		  });

		 

		  // stop video stream
		

		 
		  
	});
      function stopVideoStream() {
			if (videoStream) {
			  videoStream.getTracks().forEach((track) => {
				track.stop();
			  });
			}
		  }

		  // initialize
		  async function initializeCamera() {
			  stopVideoStream();
			  constraints.video.facingMode = useFrontCamera ? "user" : "environment";

			  try {
				videoStream = await navigator.mediaDevices.getUserMedia(constraints);
				video.srcObject = videoStream;
			  } catch (err) {
				alert("Could not access the camera");
			  }
		  }
   
</script>