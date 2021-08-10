<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-xs-3"></div>
            <div class="col-md-2 col-xs-2" style="text-align: center" onclick="location.href='/customer/orderMenu/1/10'">HOME</div>
            <div class="col-md-2 col-xs-2" style="text-align: center" onclick="history.back();">BACK</div>
            <div class="col-md-2 col-xs-2">
                <form method="post" id="change_form">
                    <select class="form-control" id="lang_change" name="language">
                        <option value="">LANGUAGE</option>
                        <?php
                            foreach($language_list as $lang){
                                echo "<option value='".$lang->language."'>".$lang->language_full."</option>";
                            }
                        ?>
                    </select>

                    <input type="hidden" name="place" id="place" value="<?= !empty($place)? $place : "" ?>"/>
                    <input type="hidden" name="menu_idx" id="menu_idx" value="<?= !empty($menu_idx)? $place : ""?>"/>
                </form>
            </div>
            <div class="col-md-3 col-xs-3"></div>
        </div>
    </div>
</footer>
<div style="position: absolute">
    test
    
</div>


<script>
    $("#lang_change").on("change", function(){
        $("#change_form")[0].submit();
    });
</script>