<div class="row">
	<div class="col-sm-12 text-center">
		<video autoplay id="video"></video>
	</div>
</div>
<div class="row">
	<div class="col-sm-6 col-xs-12 text-right">
		 <button type="button" class="btn btn-sm bg-navy-active" id="btnChangeCamera">
			<i class="fa fa-recycle"></i> Switch Kamera
		</button>
	</div>
	<div class="col-sm-6 col-xs-12 text-left">
		<button type="button" class="btn btn-sm bg-blue-active" id="btnScreenshot">
			<i class="fa fa-camera"></i> Ambil Via Kamera
		</button>
	</div>
	<input type="hidden" name="kategori" id="kategori" value="<?php echo $kategori;?>">
	<canvas class="is-hidden" id="canvas"></canvas>
</div>


<script>
	const video = document.querySelector("#video");
	const btnPlay = document.querySelector("#btnPlay");
	const btnPause = document.querySelector("#btnPause");
	const btnScreenshot = document.querySelector("#btnScreenshot");
	const btnChangeCamera = document.querySelector("#btnChangeCamera");
	const screenshotsContainer = document.querySelector("#screenshots");
	const canvas = document.querySelector("#canvas");
	const devicesSelect = document.querySelector("#devicesSelect");
	
	 const constraints = {
		video: {
		  width: {
			min: 160,
			ideal: 240,
			max: 320,
		  },
		  height: {
			min: 160,
			ideal: 240,
			max: 320,
		  },
		},
	  };
	  
	   // use front face camera
	  let useFrontCamera = true;

	  // current video stream
	  let videoStream;
    $(document).ready(function(){
		if ('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices) {
		  console.log("Let's get this party started")
		}else {
			alert("Camera API is not available in your browser");
			return false;
		}
		
	
		  /*
		  // handle events
		  // play
		  $('#btnPlay').click(function(){
		  
			video.play();
			btnPlay.classList.add("is-hidden");
			btnPause.classList.remove("is-hidden");
		  });
		   $('#btnPause').click(function(){
		  // pause
		 
			video.pause();
			btnPause.classList.add("is-hidden");
			btnPlay.classList.remove("is-hidden");
		  });
		   */
		  // take screenshot
		  $('#btnScreenshot').click(function(){		  
			const img = document.createElement("img");
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;
			canvas.getContext("2d").drawImage(video, 0, 0);
			//img.src = canvas.toDataURL("image/jpeg");
			
			let kategori	= $('#kategori').val();
			if(kategori=='depan'){
				$("#pic_webcam_depan").val(canvas.toDataURL("image/jpeg"));
				document.getElementById('result_camera_depan').innerHTML = '<img src="'+canvas.toDataURL("image/jpeg")+'" class="img-fluid"/>';
				$("#ContactModal").modal('hide');
				$("#div_picture_depan").show();
			}else if(kategori=='kanan'){
				$("#pic_webcam_kanan").val(canvas.toDataURL("image/jpeg"));
				document.getElementById('result_camera_kanan').innerHTML = '<img src="'+canvas.toDataURL("image/jpeg")+'" class="img-fluid"/>';
				$("#ContactModal").modal('hide');
				$("#div_picture_kanan").show();
			}else if(kategori=='kiri'){
				$("#pic_webcam_kiri").val(canvas.toDataURL("image/jpeg"));
				document.getElementById('result_camera_kiri').innerHTML = '<img src="'+canvas.toDataURL("image/jpeg")+'" class="img-fluid"/>';
				$("#ContactModal").modal('hide');
				$("#div_picture_kiri").show();
			}else{
				$("#pic_webcam_back").val(canvas.toDataURL("image/jpeg"));
				document.getElementById('result_camera_back').innerHTML = '<img src="'+canvas.toDataURL("image/jpeg")+'" class="img-fluid"/>';
				$("#ContactModal").modal('hide');
				$("#div_picture_back").show();
			}
			//console.log(canvas.toDataURL("image/jpeg"));
			//screenshotsContainer.html(img);
		  });

		  // switch camera
		  $('#btnChangeCamera').click(function(){		 
				useFrontCamera = !useFrontCamera;
				initializeCamera();
		  });

		  // stop video stream
		

		  initializeCamera();
		  
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