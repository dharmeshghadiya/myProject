<h1>Welcome To RydZilla</h1>

<script>
    var el_down = document.getElementById("GFG_DOWN");
    var res = "";
    var userAgent = navigator.userAgent.toLowerCase();
    var Android = userAgent.indexOf("android") > -1;

    if (Android) {
        res = "Android";
    } else {
        res =
            /iPad|iPhone|iPod/.test(navigator.userAgent) &&
            !window.MSStream;
    }

    (function() {
        var app = {
            launchApp: function() {
                window.location.replace("com.rydezilla://ryde-detail/?id={{ $id }}&start_date={{ $start_date }}&end_date={{ $end_date }}");
                this.timer = setTimeout(this.openWebApp, 1000);
            },

            openWebApp: function() {
                if (Android) {
                    window.location.replace("https://play.google.com/store?hl=en");
                }else{
                    window.location.replace("https://www.apple.com/shop");
                }
            }
        };

        app.launchApp();
    })();


</script>