<script>
    var acc = document.getElementsByClassName("accordion");
    var i;
    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel_custom = this.nextElementSibling;
            if (panel_custom.style.maxHeight){
                panel_custom.style.maxHeight = null;
            } else {
                panel_custom.style.maxHeight = panel_custom.scrollHeight + "px";
            }
        });
    }
</script>
<script>
    var acc = document.getElementsByClassName("accordion2");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel_custom = this.nextElementSibling;
            if (panel_custom.style.maxHeight){
                panel_custom.style.maxHeight = null;
            } else {
                panel_custom.style.maxHeight = panel_custom.scrollHeight + "px";
            }
        });
    }
</script>
<script>
    function myFunction_nav() {
        var element = document.getElementById("mySidenav");
        element.classList.toggle("mySidenav");
    }	
</script>
<script>
    /* When the user clicks on the button,
    toggle between hiding and showing the dropdown content */
    function myFunction_drop() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {

            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>

<script>
function myFunctiondiv() {
   var element = document.getElementById("mySidenav");
   element.classList.remove("mySidenav");
}
</script>