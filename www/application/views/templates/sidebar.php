<div class="banner_preview">
    Banner
    <br>
    250x300
</div>
<style>

    .banner_preview{
        background: #e5e9f2;
        border: 1px solid #d6e0ee;
        text-align: center;
        width: 250px;
        height: 300px;
        margin: 25px auto;
        padding: 40px;
        box-sizing: border-box;
        color: #7c8da8;
    }

</style>


<script id="vk_script" type="text/javascript" async ></script>

<!-- VK Widget -->
<div style="width: 250px; margin: 25px auto" id="vk_groups"></div>
<script type="text/javascript">

    var vk_script = document.getElementById('vk_script');

    if (vk_script) {

        vk_script.src = '//vk.com/js/api/openapi.js?122';

        vk_script.addEventListener('load', function function_name() {

            VK.Widgets.Group("vk_groups", {mode: 1, width: "250", height: "100", color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 9398);

        }, false)
    }

</script>
