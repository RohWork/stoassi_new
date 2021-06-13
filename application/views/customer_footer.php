<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-xs-3"></div>
            <div class="col-md-2 col-xs-2" style="text-align: center" onclick="location.href='/customer/orderMenu/1/10'">HOME</div>
            <div class="col-md-2 col-xs-2" style="text-align: center" onclick="history.back();">뒤로가기</div>
            <div class="col-md-2 col-xs-2">
                <select class="form-control">
                    <option value="">언어변경</option>
                    <?php
                        foreach($language_list as $lang){
                            echo "<option value='".$lang->language."'>".$lang->language_full."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-3 col-xs-3"></div>
        </div>
    </div>
</footer>