window.addEventListener('beforeunload', function (e) {
        
    var confirmationMessage = 'Are you sure you want to refresh? Your changes may not be saved.';
    
    e.returnValue = confirmationMessage; // Legacy method for cross browser support
    return confirmationMessage; // Some browsers support this
});

var main = document.getElementById("main");

localStorage.setItem("ansn", 4);
localStorage.setItem("q",1);
localStorage.setItem("lab",1);
var addbtn = document.getElementById("add");

addbtn.onclick = function(){
    var la4 = document.createElement("label");
    var div1 = document.createElement("div");
    div1.classList.add("flexer");

    var br2 = document.createElement("br")
    main.appendChild(br2);

    var ques =document.createElement("textarea");
    var im = document.createElement("input");

    var la = document.createElement("label");

    im.type =  "file";
    im.name =  "im" + String(localStorage.getItem("q"))
    im.accept = accept=".jpg, .jpeg, .png, .gif"

    la.classList.add("form-label");
    la.innerHTML = "Question " + (parseInt(localStorage.getItem("lab")) + 1) + ":";
    la.classList.add("qla");
    main.appendChild(la);

    ques.name = "q" + String(localStorage.getItem("q"));

    ques.classList.add("tex2");
    ques.classList.add("form-control");
    ques.id = "tex";

    ques.rows = 1;
    
    localStorage.setItem("q", parseInt(localStorage.getItem("q")) + 1)
    localStorage.setItem("lab", parseInt(localStorage.getItem("lab")) + 1)
    
    div1.appendChild(ques);
    la4.appendChild(im);

    var i2 = document.createElement("i");
    i2.style = "font-size:24px";

    i2.classList.add("fa");
    i2.innerHTML = "&#xf0c6";

    la4.appendChild(i2);
    div1.appendChild(la4);

    la4.classList.add("custom-file-upload");
    main.appendChild(div1);

    var br = document.createElement("br");

    main.appendChild(br);
    for(var i = 0; i<4; i++){

        var flexer2 = document.createElement("div");
        flexer2.classList.add("flexer");

        var la2 = document.createElement("label");
        la2.classList.add("form-label");

        la2.innerHTML = "option " + parseInt(i+1) + ":";
        main.appendChild(la2);


        var ans = document.createElement("input");
        ans.name = "n" + String(parseInt(localStorage.getItem("ansn")) + i);

        ans.classList.add("form-control");
        ans.classList.add("qs");

        var radi = document.createElement("input");
        radi.name = "r" + String(parseInt(localStorage.getItem("q")) - 1 );

        radi.value = "r" + String(parseInt(localStorage.getItem("ansn")) + i);
        radi.type = "radio";
        

        flexer2.appendChild(ans);
        br = document.createElement("br");

        flexer2.appendChild(radi);
        main.appendChild(flexer2);
        
        main.appendChild(br);

    }

    
    localStorage.setItem("ansn", parseInt(localStorage.getItem("ansn")) + 4);
    document.getElementById("inp1").value =  parseInt(localStorage.getItem("q"));

    document.getElementById("inp2").value =  parseInt(localStorage.getItem("ansn")); // the next item

    var texars = document.querySelectorAll(".tex2");

    texars.forEach(function (texar) {
        texar.style.cssText = `height: ${texar.scrollHeight}px; overflow-y: hidden`;

        texar.addEventListener("input", function () {
            this.style.height = "auto";
            this.style.height = `${this.scrollHeight}px`;
        });
    });
   

}; 


localStorage.setItem("t",0);
var txt = document.getElementById("txt");

txt.onclick = function() {
    var la4 = document.createElement("label");
    var div1 = document.createElement("div");
    div1.classList.add("flexer");

    var br2 = document.createElement("br")
    main.appendChild(br2);

    var ques =document.createElement("textarea");
    var im = document.createElement("input");

    var la = document.createElement("label");

    im.type =  "file";
    im.name =  "tm" + String(localStorage.getItem("t"))
    im.accept = accept=".jpg, .jpeg, .png, .gif"

    la.classList.add("form-label");
    la.innerHTML = "Question " + (parseInt(localStorage.getItem("lab")) + 1) + ":";
    localStorage.setItem("lab", parseInt(localStorage.getItem("lab")) + 1)

    la.classList.add("qla");
    main.appendChild(la);

    ques.name = "t" + String(localStorage.getItem("t"));

    ques.classList.add("tex2");
    ques.classList.add("form-control");
    ques.id = "tex";

    ques.rows = 1;
    
    localStorage.setItem("t", parseInt(localStorage.getItem("t")) + 1)
    
    div1.appendChild(ques);
    la4.appendChild(im);

    var i2 = document.createElement("i");
    i2.style = "font-size:24px";

    i2.classList.add("fa");
    i2.innerHTML = "&#xf0c6";

    la4.appendChild(i2);
    div1.appendChild(la4);

    la4.classList.add("custom-file-upload");
    main.appendChild(div1);

    var br = document.createElement("br");

    main.appendChild(br);

    var flexer2 = document.createElement("div");
    flexer2.classList.add("flexer");

    var la2 = document.createElement("label");
    la2.classList.add("form-label");

    la2.innerHTML = "Answer:";
    main.appendChild(la2);


    var ans = document.createElement("input");
    ans.name = "tn" + String(parseInt(localStorage.getItem("t"))-1);

    ans.classList.add("form-control");
    ans.classList.add("qs");
    flexer2.appendChild(ans);

    main.appendChild(flexer2)
    gbr = document.createElement("br")
    main.appendChild(gbr)
    document.getElementById("inp3").value =  parseInt(localStorage.getItem("t"));    

    var texars = document.querySelectorAll(".tex2");

    texars.forEach(function(texar) {
        texar.style.cssText = `height: ${texar.scrollHeight}px; overflow-y: hidden`;

        texar.addEventListener("input", function() {
            this.style.height = "auto";
            this.style.height = `${this.scrollHeight}px`;
        });
    });

    
};

var texars = document.querySelectorAll(".tex2");

texars.forEach(function (texar) {
    texar.style.cssText = `height: ${texar.scrollHeight}px; overflow-y: hidden`;

    texar.addEventListener("input", function () {
        this.style.height = "auto";
        this.style.height = `${this.scrollHeight}px`;
    });
});

if(screen.width <= 1000){
    var cont = document.getElementById("cont");
    cont.style.width = "90%";
}else{
    var cont = document.getElementById("cont");
    cont.style.width = "40%";
}