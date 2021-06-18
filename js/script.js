window.onload = function() {
  ButtonClick();
};

function FindPosition(oElement)
{
  if(typeof( oElement.offsetParent ) != "undefined")
  {
    for(var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent)
    {
      posX += oElement.offsetLeft;
      posY += oElement.offsetTop;
    }
      return [ posX, posY ];
    }
    else
    {
      return [ oElement.x, oElement.y ];
    }
}

function SaveLabel() {
  const urlParams = new URLSearchParams(window.location.search);
  const imgId = urlParams.get('imgId');
  var url = "labels_db.php";

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("message").innerHTML="Анотацията е запазена!";
        setTimeout(function() {
          document.getElementById("message").innerHTML = "Кликнете върху снимката или въведете координати";
      }, 2000); // <-- time in millisecond
      }
  };

  xhttp.open("POST", url, true);

  xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
  
  var X = document.getElementById('X').value;
  var Y = document.getElementById('Y').value;
  var label = document.getElementById('label').value;

  xhttp.send(JSON.stringify({
      "imgId": imgId,
      "X": X,
      "Y": Y,
      "label": label
  }));
}

var ShowLabels = true;

function HideLabels()
{
  var labels = document.getElementsByClassName("text-block");
  for(const label of labels){
    label.style.display = "none";
  }
}

function ButtonClick()
{
  var btn = document.getElementById("show_btn");
  if(ShowLabels){
    ShowAllLabels();
    btn.innerHTML = "Hide labels";
    btn.style.backgroundColor = "red";
  }else{
    HideLabels();
    btn.innerHTML = "Show labels";
    btn.style.backgroundColor = "green";
  }
  ShowLabels = !ShowLabels;
}

function UpdateLabelText(){
  var x = document.getElementById("label").value;
  document.getElementById("text").innerHTML = '<p>' + x + '</p>';
  console.log(document.getElementById("text").innerHTML);
}

function ShowAllLabels()
{
    const urlParams = new URLSearchParams(window.location.search);
    const imgId = urlParams.get('imgId');
    var url = "labels_db.php?imgId=" + imgId;
    
    var xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var myArr = JSON.parse(this.responseText);
            showLabels(myArr);
            document.getElementById("text").style.display = "block";
        }
    };

    xmlhttp.open("GET", url, true);
    xmlhttp.send();
    
    function showLabels(arr) {
      for (const label of arr) {
        
        var element = document.createElement("div");
        var paragraph = document.createElement("p");

        paragraph.innerHTML = label["text"];
        
        element.appendChild(paragraph);
        element.className = "text-block";
        
        document.getElementById("container").appendChild(element);
        var ImgPos = FindPosition(myImg);
        
        var img = document.getElementById("imgToLabel");
        element.style.left = parseFloat(label["x"]) *  + img.clientWidth + parseInt(ImgPos[0]);
        element.style.top = parseFloat(label["y"]) * img.clientHeight + parseInt(ImgPos[1]);
        element.style.display = "block";
      }
    }
}

function GetCoordinates(e)
{
  var PosX = 0;
  var PosY = 0;
  var ImgPos;
  ImgPos = FindPosition(myImg);
  if (!e) var e = window.event;
  if (e.pageX || e.pageY)
  {
    PosX = e.pageX;
    PosY = e.pageY;
  }
  else if (e.clientX || e.clientY)
    {
      PosX = e.clientX + document.body.scrollLeft
        + document.documentElement.scrollLeft;
      PosY = e.clientY + document.body.scrollTop
        + document.documentElement.scrollTop;
    }
    document.getElementById("text").style.left = PosX;
    document.getElementById("text").style.top = PosY;
  PosX = PosX - ImgPos[0];
  PosY = PosY - ImgPos[1];
  var img = document.getElementById("imgToLabel");
  let x = parseFloat(PosX) / img.clientWidth;
  let y = parseFloat(PosY) / img.clientHeight;
  x = x.toFixed(3);
  y = y.toFixed(3);
  document.getElementById("X").value = x;
  document.getElementById("Y").value = y;
  document.querySelector("#text p").innerHTML = document.getElementById("label").value;

}
