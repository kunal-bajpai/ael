var type=0, allBase=0, resBase=0, pendBase=0, offset=3, currentPage=1, endPage, xmlComp = new XMLHttpRequest();

xmlComp.onreadystatechange = function () {
	if(this.readyState==4 && this.status==200)
	{
		resp = JSON.parse(this.responseText);
		mainDiv = document.getElementById("complaintsTable").querySelector("tbody");
		for(var i=mainDiv.childNodes.length-1;i>0;i--)
		{
			if(mainDiv.childNodes[i].id!='rowHeader' && mainDiv.childNodes[i].id!='dummyDiv' && mainDiv.childNodes[i].className!='tableHR')
				mainDiv.removeChild(mainDiv.childNodes[i]);
		}
		endPage = resp.endPage;
		currentPage = resp.currentPage;

		var currentPages = document.getElementsByClassName("currentPage");
		for(var i=0;i<currentPages.length;i++)
			currentPages[i].value = resp.currentPage;

		endPages = document.getElementsByClassName("endPage");
		for(var i=0;i<endPages.length;i++)
			endPages[i].innerHTML = resp.endPage;

		selectBox = document.getElementsByClassName("resultPerPage");
		for(var i=0;i<selectBox.length;i++)
			selectBox[i].value = offset;

		if(resp.complaints!=null)
			addToList(resp.complaints);
	}
}

function prev() {
	if(currentPage > 1)
	{
		if(type==0)
			allBase -= offset;
		else
			if(type==1)
				pendBase -= offset;
			else
				resBase -= offset;
		getProjects();
	}
}

function next() {
	if(currentPage<endPage)
	{
		if(type==0)
			allBase += offset;
		else
			if(type==1)
				pendBase += offset;
			else
				resBase += offset;
		getProjects();
	}
}

selectBox = document.getElementsByClassName("resultPerPage");
for(var i=0;i<selectBox.length;i++)
	selectBox[i].onchange = function() {
		offset = this.value;
		allBase = 0;
		resBase = 0;
		pendBase = 0;
		getProjects();
	}

currPages = document.getElementsByClassName("currentPage");
for(var i =0;i<currPages.length;i++)
{
	currPages[i].onblur = function() {
		if(this.value % 1 == 0 && this.value <= endPage && this.value >= 1)
		{
			if(type==0)
				allBase+=offset;
			else
				if(type==1)
					pendBase+=offset;
				else
					resBase+=offset;
		}
		else
			this.value = currentPage;
	}
	currPages[i].onkeyup = function(e) {
		if(e.keyCode == 13)
			this.blur();
	}
}

radios = document.getElementsByClassName("typeRadio");
for(var i=0;i<radios.length;i++)
	radios[i].onchange = function() {
		if(this.value=='0')
		{
			document.getElementById("allRadio1").checked = true;
			document.getElementById("allRadio2").checked = true;
			type = 0;
		}
		else
			if(this.value=='1')
			{
				document.getElementById("pendRadio1").checked = true;
				document.getElementById("pendRadio2").checked = true;
				type = 1;
			}
			else
			{
				document.getElementById("resRadio1").checked = true;
				document.getElementById("resRadio2").checked = true;
				type = 2;
			}
		getProjects();
	}

function addToList(projects) {
	for(var i=0;i<projects.length;i++)
	{
		div = document.getElementById("dummyDiv").cloneNode(true);
		div.id=null;
		div.style.display = 'table-row';
		div.querySelector(".type").innerHTML = projects[i].problem;
		div.querySelector(".id").innerHTML = projects[i].id;
		div.querySelector(".plant").innerHTML = projects[i].plant;
		div.querySelector(".user").innerHTML = projects[i].submit_user;
		div.querySelector(".gap").innerHTML = projects[i].gap;
		div.querySelector(".view_btn").querySelector("a").href = "complaint.php?id=" + projects[i].id;
		div.querySelector(".submitted_on").innerHTML = projects[i].submit_time;
		if(projects[i].resolve_time != 0)
			div.querySelector(".resolved_on").innerHTML = projects[i].resolve_time;
		else
			div.querySelector(".resolved_on").innerHTML = "Pending";
		document.getElementById("complaintsTable").querySelector("tbody").appendChild(div);
	}
}

function getProjects() {
	xmlComp.open("POST","ajax/getComplaints.php",true);
	xmlComp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	if(type == 0)
		xmlComp.send("type="+type+"&base="+allBase+"&offset="+offset,true);
	else
		if(type == 1)
			xmlComp.send("type="+type+"&base="+pendBase+"&offset="+offset,true);
		else
			xmlComp.send("type="+type+"&base="+resBase+"&offset="+offset,true);
}
getProjects();
