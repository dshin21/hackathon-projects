var url = "https://api.haystack.ai/api/image/analyze?output=json&apikey=4c200a68cae1b636cc428e2928c32ebb";
var formData = new FormData();
formData.append("Images/Oliver.jpg", **IMAGE_DATA_BLOB**);

var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() { 
    if (this.readyState == 4 && this.status == 200) { 
        var response = JSON.parse(this.response);
    }
};

xhttp.open("POST", url, true);
xhttp.send(formData);
