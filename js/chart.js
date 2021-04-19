//enable debug console logs
var debug = true;

//ajax calls
var myRequest = null;

function CreateXmlHttpReq(handler) {
var xmlhttp = null;
xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = handler;
return xmlhttp;
}

function myHandler() {
  if (myRequest.readyState == 4 && myRequest.status == 200) {
	  if(debug){console.log(myRequest.responseText);}
  }
}

//movements retrive
var movementsObj = {};
function getMovements(){
	myRequest = CreateXmlHttpReq(myHandler);
	myRequest.open("GET","action/data.php?transaction=true", false);
	myRequest.send();
	movementsObj = JSON.parse(myRequest.responseText);
	if(debug){console.log(movementsObj);}
}
getMovements();


//get positive value
var valPos = [0,0,0,0,0,0];
for (x in movementsObj) {
	if (moment(movementsObj[x].dat_mov).isSame(moment(), 'month') && movementsObj[x].val > 0) {
	valPos[0] += movementsObj[x].val;
	}else if (moment(movementsObj[x].dat_mov).isSame(moment().subtract(1, 'months'), 'month') && movementsObj[x].val > 0) {
	valPos[1] += movementsObj[x].val;
	}else if (moment(movementsObj[x].dat_mov).isSame(moment().subtract(2, 'months'), 'month') && movementsObj[x].val > 0) {
	valPos[2] += movementsObj[x].val;
	}else if (moment(movementsObj[x].dat_mov).isSame(moment().subtract(3, 'months'), 'month') && movementsObj[x].val > 0) {
	valPos[3] += movementsObj[x].val;
	}else if (moment(movementsObj[x].dat_mov).isSame(moment().subtract(4, 'months'), 'month') && movementsObj[x].val > 0) {
	valPos[4] += movementsObj[x].val;
	}else if (moment(movementsObj[x].dat_mov).isSame(moment().subtract(5, 'months'), 'month') && movementsObj[x].val > 0) {
	valPos[5] += movementsObj[x].val;
	}
}
if(debug){console.log(valPos);}

//get negative value
var valNeg = [0,0,0,0,0,0];
for (x in movementsObj) {
	if (moment(movementsObj[x].dat_mov).isSame(moment(), 'month') && movementsObj[x].val < 0) {
	valNeg[0] += Math.abs(movementsObj[x].val);
	}else if (moment(movementsObj[x].dat_mov).isSame(moment().subtract(1, 'months'), 'month') && movementsObj[x].val < 0) {
	valNeg[1] += Math.abs(movementsObj[x].val);
	}else if (moment(movementsObj[x].dat_mov).isSame(moment().subtract(2, 'months'), 'month') && movementsObj[x].val < 0) {
	valNeg[2] += Math.abs(movementsObj[x].val);
	}else if (moment(movementsObj[x].dat_mov).isSame(moment().subtract(3, 'months'), 'month') && movementsObj[x].val < 0) {
	valNeg[3] += Math.abs(movementsObj[x].val);
	}else if (moment(movementsObj[x].dat_mov).isSame(moment().subtract(4, 'months'), 'month') && movementsObj[x].val < 0) {
	valNeg[4] += Math.abs(movementsObj[x].val);
	}else if (moment(movementsObj[x].dat_mov).isSame(moment().subtract(5, 'months'), 'month') && movementsObj[x].val < 0) {
	valNeg[5] += Math.abs(movementsObj[x].val);
	}
}
if(debug){console.log(valNeg);}

//get previous 6 months
var months = [];

for (i = 0; i < 6; i++) {
  months[Math.abs(i-5)]=moment().subtract(i, 'months').format('MMM - YYYY');
}
if(debug){console.log(months);}

//users retrive
var usersObj = {};
function getUsers(){
	myRequest = CreateXmlHttpReq(myHandler);
	myRequest.open("GET","action/data.php?users=true", false);
	myRequest.send();
	usersObj = JSON.parse(myRequest.responseText);
	if(debug){console.log(usersObj);}
}
getUsers();

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

var userName = [];
var userColor = [];
for (x in usersObj) {
	userName[x]=capitalizeFirstLetter(usersObj[x].usr_id);
	userColor[x]=usersObj[x].color;
}
if(debug){console.log(userName, userColor);}

//get negative value for users
var valUserNeg = [];
for (x in usersObj) {
	valUserNeg[x] = 0;
	for (y in movementsObj) {
		if (moment(movementsObj[y].dat_mov).isSame(moment(), 'month') && movementsObj[y].val < 0 && movementsObj[y].usr_mov == usersObj[x].usr_id) {
		valUserNeg[x] += Math.abs(movementsObj[y].val);
		}
	}
}
if(debug){console.log(valUserNeg);}

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
	s = '',
	toFixedFix = function(n, prec) {
	  var k = Math.pow(10, prec);
	  return '' + Math.round(n * k) / k;
	};
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
	s[1] = s[1] || '';
	s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Bar Chart Example
var ctx = document.getElementById("movementsMonth");
var movementsMonth = new Chart(ctx, {
  type: 'bar',
  data: {
	labels: months,
	datasets: [{
	  label: "Entrate",
	  backgroundColor: "#1cc88a",
	  hoverBackgroundColor: "#1cc88a",
	  borderColor: "#1cc88a",
	  data: valPos,
	},
	{
	  label: "Uscite",
	  backgroundColor: "#e74a3b",
	  hoverBackgroundColor: "#e74a3b",
	  borderColor: "#e74a3b",
	  data: valNeg,
	}],
  },
  options: {
	maintainAspectRatio: false,
	layout: {
	  padding: {
		left: 10,
		right: 25,
		top: 25,
		bottom: 0
	  }
	},
	scales: {
	  xAxes: [{
		time: {
		  unit: 'month'
		},
		gridLines: {
		  display: false,
		  drawBorder: false
		},
		ticks: {
		  maxTicksLimit: 6
		},
		maxBarThickness: 25,
	  }],
	  yAxes: [{
		ticks: {
		  min: 0,
		//max: 15000,
		  maxTicksLimit: 5,
		  padding: 10,
		  // Include a dollar sign in the ticks
		  callback: function(value, index, values) {
			return '\u20AC ' + number_format(value);
		  }
		},
		gridLines: {
		  color: "rgb(234, 236, 244)",
		  zeroLineColor: "rgb(234, 236, 244)",
		  drawBorder: false,
		  borderDash: [2],
		  zeroLineBorderDash: [2]
		}
	  }],
	},
	legend: {
	  display: false
	},
	tooltips: {
	  titleMarginBottom: 10,
	  titleFontColor: '#6e707e',
	  titleFontSize: 14,
	  backgroundColor: "rgb(255,255,255)",
	  bodyFontColor: "#858796",
	  borderColor: '#dddfeb',
	  borderWidth: 1,
	  xPadding: 15,
	  yPadding: 15,
	  displayColors: false,
	  caretPadding: 10,
	  callbacks: {
		label: function(tooltipItem, chart) {
		  var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
		  return datasetLabel + ': €' + number_format(tooltipItem.yLabel);
		}
	  }
	},
  }
});

// Pie Chart Example
var ctx = document.getElementById("expPerUser");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
	labels: userName,
	datasets: [{
	  data: valUserNeg,
	  backgroundColor: userColor,
	  hoverBackgroundColor: userColor,
	  hoverBorderColor: "rgba(234, 236, 244, 1)",
	}],
  },
  options: {
	maintainAspectRatio: false,
	tooltips: {
	  backgroundColor: "rgb(255,255,255)",
	  bodyFontColor: "#858796",
	  borderColor: '#dddfeb',
	  borderWidth: 1,
	  xPadding: 15,
	  yPadding: 15,
	  displayColors: false,
	  caretPadding: 10,
	},
	legend: {
	  display: false
	},
	cutoutPercentage: 80,
  },
});