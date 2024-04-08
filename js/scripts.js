function setLanguage(lang){
    
   $.post("../inc/loadLang.php", { lang: lang }, function(data){
       var domain = document.location.origin;
       var pathname = window.location.pathname.substr(3);
       window.location.href = domain+'/'+lang+pathname;
   });
}

function switchLang(){
    $.get("https://ipinfo.io/json", function (response) {
        var lang =  response.country.toLowerCase();
        if ((lang != 'en') && (lang != 'tw')){
            lang = 'en';
        }
           var domain = document.location.origin;
           var pathname = window.location.pathname.substr(3);
           window.location.href = domain+'/'+lang+pathname;
    }, "jsonp");
}