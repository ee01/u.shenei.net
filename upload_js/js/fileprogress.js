
function FileProgress(file, targetID) {
	this.fileProgressID = file.id;
	this.opacity = 100;
	this.height = 0;
	

	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.id = this.fileProgressID;
		
		var l1	=	document.createElement("li");
		l1.style.width='120px';
		l1.appendChild(document.createTextNode(file.name));
		
		
		var l3	=	document.createElement("li");
		l3.style.width='100px';
		var daxiao	=	file.size;
		daxiao1		=	parseFloat(daxiao/1024).toFixed(2);
		if(daxiao1>1)
		{
			if(daxiao1>=1024)
			{
				daxiao1	=	parseFloat(daxiao1/1024).toFixed(2);
				daxiao	=	daxiao1+" "+"MB";
			}
			else
			{
				daxiao	=	daxiao1+" "+"KB";
			}
			
		}
		else
		{
			daxiao	=	daxiao+" "+"Byte";
		}
		l3.appendChild(document.createTextNode(daxiao));
		
		var l4		=	document.createElement("li");
		l4.style.width='150px';
		var l4div	=	document.createElement("div");
		l4.appendChild(l4div);
		
		var l5	=	document.createElement("li");
		l5.style.width='50px';
		var qh	=	document.createElement("a");
		qh.href = "#";
		qh.style.visibility = "hidden";
		qh.appendChild(document.createTextNode("取消"));
		l5.appendChild(qh);
		
		this.fileProgressWrapper.appendChild(l1);
		this.fileProgressWrapper.appendChild(l3);
		this.fileProgressWrapper.appendChild(l4);
		this.fileProgressWrapper.appendChild(l5);
		this.fileProgressElement = this.fileProgressWrapper;
		document.getElementById(targetID).appendChild(this.fileProgressWrapper);
	} else {
		this.fileProgressElement = this.fileProgressWrapper;
		this.reset();
	}

	this.height = this.fileProgressWrapper.offsetHeight;
	this.setTimer(null);
}

FileProgress.prototype.setTimer = function (timer) {
	this.fileProgressElement["FP_TIMER"] = timer;
};
FileProgress.prototype.getTimer = function (timer) {
	return this.fileProgressElement["FP_TIMER"] || null;
};



FileProgress.prototype.reset = function () {
	var li3		=this.fileProgressElement.childNodes[2];
	li3.childNodes[0].style.width = "0%";
	
	this.appear();	
};

FileProgress.prototype.setProgress = function (percentage) {
	var li3		=this.fileProgressElement.childNodes[2];
	li3.childNodes[0].className = "progressBarInProgress";
	li3.childNodes[0].style.width = percentage + "%";
	this.appear();	
};
FileProgress.prototype.setComplete = function () {
	var li3		=this.fileProgressElement.childNodes[2];
	li3.childNodes[0].style.width = "";
};
FileProgress.prototype.setError = function () {
	var oSelf = this;
	oSelf.disappear();
};
FileProgress.prototype.setCancelled = function () {
	var li3		=this.fileProgressElement.childNodes[2];
	li3.childNodes[0].style.width = "0%";
	var oSelf = this;
	this.setTimer(setTimeout(function () {
		oSelf.disappear();
	}, 0));
};
FileProgress.prototype.setStatus = function (status) {
	var li3		=this.fileProgressElement.childNodes[2];
	li3.childNodes[0].innerHTML = status;
};

FileProgress.prototype.toggleCancel = function (show, swfUploadInstance) {
	var ta	=	this.fileProgressElement.childNodes[3];
	ta.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfUploadInstance) {
		var fileID = this.fileProgressID;
		ta.childNodes[0].onclick = function () {
			swfUploadInstance.cancelUpload(fileID);
			return false;
		};
	}
};

FileProgress.prototype.appear = function () {
	if (this.getTimer() !== null) {
		clearTimeout(this.getTimer());
		this.setTimer(null);
	}
	
	if (this.fileProgressWrapper.filters) {
		try {
			this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 100;
		} catch (e) {
			this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=100)";
		}
	} else {
		this.fileProgressWrapper.style.opacity = 1;
	}
		
	this.fileProgressWrapper.style.height = "";
	
	this.height = this.fileProgressWrapper.offsetHeight;
	this.opacity = 100;
	this.fileProgressWrapper.style.display = "";
	
};


FileProgress.prototype.disappear = function () {

	var reduceOpacityBy = 15;
	var reduceHeightBy = 4;
	var rate = 30;	

	if (this.opacity > 0) {
		this.opacity -= reduceOpacityBy;
		if (this.opacity < 0) {
			this.opacity = 0;
		}

		if (this.fileProgressWrapper.filters) {
			try {
				this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = this.opacity;
			} catch (e) {
				this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=" + this.opacity + ")";
			}
		} else {
			this.fileProgressWrapper.style.opacity = this.opacity / 100;
		}
	}

	if (this.height > 0) {
		this.height -= reduceHeightBy;
		if (this.height < 0) {
			this.height = 0;
		}

		this.fileProgressWrapper.style.height = this.height + "px";
	}
	this.fileProgressWrapper.style.display = "none";
	this.setTimer(null);
};
