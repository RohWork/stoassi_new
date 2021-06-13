<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-xs-3"></div>
            <div class="col-md-4 col-xs-2" style="text-align: center" onclick="location.href='/customer/orderMenu/1/10'">HOME</div>
            <div class="col-md-4 col-xs-2" style="text-align: center" onclick="history.back();">뒤로가기</div>
            <div class="col-md-4 col-xs-2">
                <select>
                    <?php
                        foreach($language_list as $lang){
                            echo "<option value=".$lang->language.">".$lang->language_full."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-2 col-xs-3"></div>
        </div>
    </div>
</footer>