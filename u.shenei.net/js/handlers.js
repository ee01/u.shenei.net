var formChecker = null;
function swfUploadLoaded() {
	var btnSubmit = document.getElementById("commendmusic_btn");
	btnSubmit.onclick = doSubmit;
	formChecker = window.setInterval(validateForm, 1);
	btnSubmit.disabled = true;
	validateForm();
}

function validateForm() {
	var txtSongName = document.getElementById("songname");
	var txtFileName;
	var txtSongUrl;
	var txtSongdiskUrl;
	
	var isValid = true;
	if (txtSongName.value == "") {
		isValid = false;
	}
	if(utypestate==0){
			txtFileName = document.getElementById("txtFileName");
			if (txtFileName.value == "" || txtSongName.value == "") {
				isValid = false;
			}else{
				isValid = true;
			}
	} else if(utypestate==1){
			txtSongUrl = document.getElementById("songurl");
			if (txtSongUrl.value == "" || txtSongName.value == "") {
				isValid = false;
			}else{
				isValid = true;
			}
	} else{
			txtSongdiskUrl = document.getElementById("songurldisk");
			//alert (txtSongdiskUrl.value);
			if (txtSongdiskUrl.value == "" || txtSongName.value == "") {
				isValid = false;
			}else{
				isValid = true;
			}
	}
	
	document.getElementById("commendmusic_btn").disabled = !isValid;

}

// Called by the submit button to start the upload
function doSubmit(e) {
	if (formChecker != null) {
		clearInterval(formChecker);
		formChecker = null;
	}
	
	e = e || window.event;
	if (e.stopPropagation) {
		e.stopPropagation();
	}
	e.cancelBubble = true;
	
	try {
		swfu.startUpload();
		document.getElementById("commendmusic_btn").disabled = true;
	} catch (ex) {

	}
	return false;
}

function doSubmit1(e){}

 // Called by the queue complete handler to submit the form
function uploadDone() {
	try {
		document.forms[0].submit();
	} catch (ex) {
		alert("Error submitting form");
	}
}

function fileDialogStart() {
	var txtFileName = document.getElementById("txtFileName");
	txtFileName.value = "";

	this.cancelUpload();
}



function fileQueueError(file, errorCode, message)  {
	try {
		// Handle this error separately because we don't want to create a FileProgress element for it.
		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
			alert("һ��ֻ���ϴ�һ���ļ�.");
			return;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			alert("��ѡ����ļ�̫��.");
			this.debug("����: �ļ�̫��, �ļ���: " + file.name + ", ��С: " + file.size + ", ��Ϣ: " + message);
			return;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			alert("����.");
			this.debug("����: 0��С�ļ�, �ļ���: " + file.name + ", ��С: " + file.size + ", ��Ϣ: " + message);
			return;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			alert("�������ϴ�����.");
			this.debug("����: ��֤�ļ�����, �ļ���: " + file.name + ", ��С: " + file.size + ", ��Ϣ: " + message);
			return;
		default:
			alert("����.");
			this.debug("����: " + errorCode + ", �ļ���: " + file.name + ", ��С: " + file.size + ", ��Ϣ: " + message);
			return;
		}
	} catch (e) {
	}
}

function fileQueued(file) {
	try {
		var txtFileName = document.getElementById("txtFileName");
		txtFileName.value = file.name;
	} catch (e) {
	}

}
function fileDialogComplete(numFilesSelected, numFilesQueued) {
	validateForm();
}

function uploadProgress(file, bytesLoaded, bytesTotal) {

	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		file.id = "singlefile";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
		var progress = new FileProgress(file, this.customSettings.progress_target);
		progress.setProgress(percent);
		progress.setStatus("�����ϴ������Ե�...");
	} catch (e) {
	}
}

function uploadSuccess(file, serverData) {
	try {
		file.id = "singlefile";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
		var progress = new FileProgress(file, this.customSettings.progress_target);
		progress.setComplete();
		progress.setStatus("���.");
		progress.toggleCancel(false);
		
		if (serverData === " ") {
			this.customSettings.upload_successful = false;
		} else {
			this.customSettings.upload_successful = true;
			document.getElementById("hidFileusize").value = file.size;
			document.getElementById("hidFileID").value = serverData;
		}
		
	} catch (e) {
	}
}

function uploadComplete(file) {
	try {
		if (this.customSettings.upload_successful) {
			this.setButtonDisabled(true);
			uploadDone();
		} else {
			file.id = "singlefile";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
			var progress = new FileProgress(file, this.customSettings.progress_target);
			progress.setError();
			progress.setStatus("�ļ��ܾ�");
			progress.toggleCancel(false);
			
			var txtFileName = document.getElementById("txtFileName");
			txtFileName.value = "";
			validateForm();

			alert("�ϴ�����.");
		}
	} catch (e) {
	}
}

function uploadError(file, errorCode, message) {
	try {
		
		if (errorCode === SWFUpload.UPLOAD_ERROR.FILE_CANCELLED) {
			// Don't show cancelled error boxes
			return;
		}
		
		var txtFileName = document.getElementById("txtFileName");
		txtFileName.value = "";
		validateForm();
		
		// Handle this error separately because we don't want to create a FileProgress element for it.
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
			alert("����.");
			this.debug("����, �ļ���: " + file.name + ", ��Ϣ: " + message);
			return;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			alert("��ֻ���ϴ�һ���ļ�.");
			this.debug("����: �ļ���: " + file.name + ", ��С: " + file.size + ", ��Ϣ: " + message);
			return;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			break;
		default:
			alert("����.");
			this.debug("����: " + errorCode + ", �ļ���: " + file.name + ", ��С: " + file.size + ", ��Ϣ: " + message);
			return;
		}

		file.id = "singlefile";	// This makes it so FileProgress only makes a single UI element, instead of one for each file
		var progress = new FileProgress(file, this.customSettings.progress_target);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			progress.setStatus("Upload Error");
			this.debug("����: �ļ���: " + file.name + ", ��Ϣ: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			progress.setStatus("Upload Failed.");
			this.debug("����: �ļ���: " + file.name + ", ��С: " + file.size + ", ��Ϣ: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			progress.setStatus("Server (IO) Error");
			this.debug("����: �ļ���: " + file.name + ", ��Ϣ: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			progress.setStatus("Security Error");
			this.debug("����: �ļ���: " + file.name + ", ��Ϣ: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			progress.setStatus("Upload Cancelled");
			this.debug("����: �ļ���: " + file.name + ", ��Ϣ: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			progress.setStatus("Upload Stopped");
			this.debug("����: �ļ���: " + file.name + ", ��Ϣ: " + message);
			break;
		}
	} catch (ex) {
	}
}

