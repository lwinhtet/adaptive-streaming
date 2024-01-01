function uploadFile() {
  const fileInput = document.getElementById('file-upload');
  const titleInput = document.getElementById('title');
  const progressContainer = document.getElementById('progress');
  const progressBarInner = document.getElementById('progressBarInner');
  const progressText = document.getElementById('progressText');

  const formData = new FormData();
  formData.append('file', fileInput.files[0]);
  formData.append('title', titleInput.value);

  const xhr = new XMLHttpRequest();

  xhr.upload.onprogress = function (event) {
    if (event.lengthComputable) {
      const percentComplete = (event.loaded / event.total) * 100;
      progressBarInner.style.width = percentComplete + '%';
      progressText.textContent = percentComplete.toFixed(0) + '%';
    }
  };

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // Successful upload
        console.log('File uploaded successfully');

        // Hide progress after a short delay
        setTimeout(() => {
          progressContainer.style.display = 'none';
        }, 1000);
      } else {
        // Handle errors
        console.error('File upload failed');
      }
    }
  };

  xhr.open('POST', 'upload-video', true); // Replace with your server endpoint
  xhr.send(formData);

  // Show progress container
  progressContainer.style.display = 'block';
}
