<script>
    function injectSvgSprite(path) {

        var ajax = new XMLHttpRequest();
        ajax.open("GET", path, true);
        ajax.send();
        ajax.onload = function(e) {
            var div = document.createElement("div");
            div.className = 'd-none';
            div.innerHTML = ajax.responseText;
            document.body.insertBefore(div, document.body.childNodes[0]);
        }
    }
</script>
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('vendor/smooth-scroll/smooth-scroll.polyfills.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('vendor/object-fit-images/ofi.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/js/swiper.min.js"></script>
<script src="{{ asset('vendor/my/theme.js') }}"></script>

<script>
    var basePath = ''
</script>
