// JavaScript Document
if(!document.all)document.captureEvents(Event.MOUSEDOWN);

var rowStart = 1; // excludes the heading row from manipulations. change to 0 if there is no heading.
var moz_firstSort = true;

var col_evenColor = "#006699";
var col_oddColor = "#00968C";
var col_highlightColor = "#008299";

function init() {
	initRows();
	if(rowStart)initHeading();
}

function initHeading() {
	for(i=0;i<document.getElementsByTagName("tr")[0].childNodes.length;i++) {
		if(document.getElementsByTagName("tr")[0].childNodes[i].tagName == "TD") {
			document.getElementsByTagName("tr")[0].childNodes[i].onclick = initSort;
			document.getElementsByTagName("tr")[0].childNodes[i].style.backgroundColor = "#FFFFFF";
			document.getElementsByTagName("tr")[0].childNodes[i].style.color = "#000000";
			document.getElementsByTagName("tr")[0].childNodes[i].style.cursor = "pointer";
		}
	}
}

function initSort(e) {
	mouseX = document.all?window.event.clientX-11:e.pageX - 11;
	column = returnColumnClicked(mouseX);
	handleSort(column);
}

function initRows() {
	colorizeTableRows();
	for(i=rowStart;i<document.getElementsByTagName("tr").length;i++) {
		document.getElementsByTagName("tr")[i].id = "row" + i;
		document.getElementsByTagName("tr")[i].onclick = handleRowClick;
	}
}

function colorizeTableRows() {
	for(i=rowStart;i<document.getElementsByTagName("tr").length;i++) {
		i%2?document.getElementsByTagName("tr")[i].style.backgroundColor = col_evenColor:document.getElementsByTagName("tr")[i].style.backgroundColor = col_oddColor;
	}
}

function colorizeTableColumn(column) {
	for(i=rowStart;i<document.getElementsByTagName("tr").length;i++) {
		for(z=0;z<document.getElementsByTagName("tr")[i].childNodes.length;z++) {
			if(document.getElementsByTagName("tr")[i].childNodes[z].tagName == "TD")document.getElementsByTagName("tr")[i].childNodes[z].style.backgroundColor = "";
		}
		document.getElementsByTagName("tr")[i].childNodes[column].style.backgroundColor = col_highlightColor;
	}
}

function handleRowClick(e) {
	mouseY = document.all?window.event.clientY-11:e.pageY-11;
	row = returnRowClicked(mouseY);
	highlightTableRow(row);
}

function highlightTableRow(row) {
	colorizeTableRows();
	document.getElementsByTagName("tr")[row].style.backgroundColor = col_highlightColor;
}

function handleSort(column) {
	sortBy = new Array();
	dataID = document.all?column[1]:column[0];
	for(i=rowStart;i<document.getElementsByTagName("tr").length;i++) {
		sortBy[sortBy.length] = document.getElementsByTagName("tr")[i].childNodes[dataID].innerHTML;
	}
	if(rowStart) {
		resetHeaderColors();
		document.getElementsByTagName("tr")[0].childNodes[dataID].style.backgroundColor = col_highlightColor;
	}
	sortRowData(dataID);
}

function resetHeaderColors() {
	for(i=0;i<document.getElementsByTagName("tr")[0].childNodes.length;i++) {
		if(document.getElementsByTagName("tr")[0].childNodes[i].tagName == "TD")document.getElementsByTagName("tr")[0].childNodes[i].style.backgroundColor = "#FFFFFF";
	}
}

function sortRowData(dataID) {
	order = new Array();
	colorizeTableColumn(dataID);
	for(i=rowStart;i<document.getElementsByTagName("tr").length;i++) order[order.length] = new Array(document.getElementsByTagName("tr")[i].childNodes[dataID].innerHTML,i);
	order=order.sort();
	reorderTable(order);
}

function reorderTable(order) {
	mDiv = new Array();
	// create sorted object references
	for(i=0;i<order.length;i++) mDiv[mDiv.length] = document.getElementsByTagName("tr")[order[i][1]].cloneNode(true);
	// insert sorted TR objects
	z=0;
	for(i=rowStart;i<document.getElementsByTagName("tbody")[0].childNodes.length;i++) {
		if(document.getElementsByTagName("tbody")[0].childNodes[i].tagName == "TR") {
			try { 
				if(document.all) {
					document.getElementsByTagName("tbody")[0].insertBefore(mDiv[z],document.getElementsByTagName("tr")[i]); 
				} else {
					document.getElementsByTagName("tbody")[0].insertBefore(mDiv[z],document.getElementsByTagName("tr")[document.getElementsByTagName("tr").length]); 
				}
			} catch(err) { }
			z++;
		}
	}
	removeChildren(mDiv.length+1);
	initRows();
}

function removeChildren(startFrom) {
	err ="";
	if(document.all) {
		do { try { document.getElementsByTagName("tbody")[0].removeChild(document.getElementsByTagName("tr")[startFrom]) } catch (err) { } } while (err == "");
	} else {
		z=0;
		for(i=1;z<startFrom-1;i++) {
			if(document.getElementsByTagName("tbody")[0].childNodes[i].tagName == "TR") {
				if(moz_firstSort) {
					document.getElementsByTagName("tbody")[0].removeChild(document.getElementsByTagName("tbody")[0].childNodes[i]);
				} else {
					document.getElementsByTagName("tbody")[0].removeChild(document.getElementsByTagName("tbody")[0].childNodes[startFrom+1]);
				}
				z++;
			}
		}
	}
	if(moz_firstSort)moz_firstSort = false; // mozilla sure does suck sometimes...
}

function returnRowClicked(y) {
	for(i=rowStart;i<document.getElementsByTagName("tr").length;i++) {
		height = document.getElementsByTagName("tr")[i].offsetHeight;
		if(y>=document.getElementsByTagName("tr")[i].offsetTop && y<=document.getElementsByTagName("tr")[i].offsetTop + height)return i;
	}
}

function returnColumnClicked(x) {
	tdIncr = 0; // mozilla counts text nodes as child nodes, ie tabbed code for legibility, so returning "i" will result in incorrect TD indexes
	for(i=0;i<document.getElementsByTagName("tr")[0].childNodes.length;i++) {
		if(document.getElementsByTagName("tr")[0].childNodes[i].tagName == "TD") {
			width = document.getElementsByTagName("td")[i].offsetWidth;
			if(x>=document.getElementsByTagName("tr")[0].childNodes[i].offsetLeft && x<= document.getElementsByTagName("tr")[0].childNodes[i].offsetLeft + width) {
				columnInfo = new Array(i,tdIncr);
				return columnInfo;			
			}
			tdIncr++;
		}
	}
}

