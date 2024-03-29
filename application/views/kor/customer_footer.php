<footer>
    <!-- 쿠키 사용 --> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>     
    
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-2 col-xs-2" style="text-align: center" onclick="location.href='/customer/orderMenu/1/10'"><span class="glyphicon glyphicon-home"></span>&nbsp;</div>
            <div class="col-md-2 col-xs-2" style="text-align: center" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;</div>
            <div class="col-md-2 col-xs-3" style="text-align: center" onclick="$('#myModal').modal('show');check_basket();"><span class="glyphicon glyphicon-inbox"></span><span id="view_basket_cnt"></span>&nbsp;</div>
            <div class="col-md-2 col-xs-5">
                <form method="post" id="change_form" action="<?=explode("?",$_SERVER['REQUEST_URI'])[0]?>">
                    <select class="form-control" id="lang_change" name="language" onchange="this.form.submit()">
                        <option value="">언어변경</option>
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
            <div class="col-md-2 "></div>
        </div>
    </div>
</footer>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">장바구니</h4>
      </div>
      <div class="modal-body">
        <form id="basket_form">
            <table id="basket_list" style="width:100%;" class="table">
                <tr>
                    <th width="10%"></th>
                    <th width="35%">메뉴명</th>
                    <th width="20%">금액</th>
                    <th width="30%">갯수</th>
                    <th width="5%"></th>
                </tr>
            </table>
            <div style="width: 100%; margin-bottom: 30px">
                <label> 메뉴가격 :  </label><span id="basket_sum"> </span><br/>
                <label> 부가세 : </label><span id="basket_tax"></span><br/>
                <label> 계산된 금액 : </label><span id="basket_total"> </span>
                <input type="hidden" name="basket_shop_idx" id="basket_shop_idx" value="<?=!empty($shop_idx)?$shop_idx:""?>"/>
                <input type="hidden" name="basket_place" id="basket_place" value="<?=!empty($place)? $place: ""?>"/>
            </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="submit_basket()">구입</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
    
    $(document).ready(function(){
        check_basket();
        count_basket();
        setTimeout(function(){
            calc_basket();
        },500);
    });
    
    function count_basket(){
        if(typeof($.cookie('cnt_array')) != "undefined"){
            var cnt_array = $.cookie('cnt_array').split('/');
            var basket_cnt = 0;
            
            for( var i =0; i<cnt_array.length; i++){
                basket_cnt += Number(cnt_array[i]);
            }
            $("#view_basket_cnt").html("[+"+basket_cnt+"]");
            
        }
        
    }
    
    function check_basket(){
        if(typeof($.cookie('menu_array')) != "undefined"){
            
            $(".value_tr").detach();
            
            var menu_array = $.cookie('menu_array').split('/');
            var cnt_array = $.cookie('cnt_array').split('/');
            
            var basket_array = new Array();
            
            for(var i =0; i<menu_array.length; i++){
                basket_array[menu_array[i]] = cnt_array[i]
            }
            
            $.ajax({
                    url:'/customer/getMenuList',
                    type:'post',
                    data: {
                        menu_array : $.cookie('menu_array'),
                    },
                    success:function(data){
                        var basket = data.return;
                        
                        for(var i=0; i<basket.length; i++){
                            
                            $("#basket_list").append("<tr class='value_tr'>"
                                                        +"<td> <input type='checkbox' name='basket_idx[]' id='basket_idx_"+basket[i].idx+"' value='"+basket[i].idx+"' checked='checked'  onchange='calc_basket()'/></td>"
                                                        +"<td>"+basket[i].name+"</td>"
                                                        +"<td>"+basket[i].price
                                                                +"<input type='hidden' id='basket_price_"+basket[i].idx+"' name='basket_price_"+basket[i].idx+"'  value='"+basket[i].price+"'/>"
                                                                +"<input type='hidden' id='basket_tax_"+basket[i].idx+"' name='basket_tax_"+basket[i].idx+"' value='"+basket[i].tax+"'/>"
                                                        +"</td>"
                                                        +"<td class='input-group'>"           
                                                        +"<input type='text' name=basket_cnt[]' id='basket_cnt_"+basket[i].idx+"' readonly class='form-control' value='"+basket_array[basket[i].idx]+"' style='width:50px' size='3' maxlength='3' />&nbsp;"
                                                        +"<button type='button' class='btn btn-primary' onclick='cnt_change("+basket[i].idx+",1)' style='width:40px'> + </button>&nbsp;"
                                                        +"<button type='button' class='btn btn-primary' onclick='cnt_change("+basket[i].idx+",2)' style='width:40px'> - </button>"
                                                        +"</td>"
                                                        +"<td> <button type='button' onclick='remove_basket("+basket[i].idx+")' class='btn btn-danger'>X</button></td>"+
                                                        "</tr>"
                                                    );
                        }
                    },
                    error: function(xhr,status,error) {
                        console.log(xhr,status,error);
                        alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                        return false;
                    }	
            });
            
            setTimeout(function(){
                calc_basket();
            },500);
        }else{
            $("#basket_list").html(
                    "<tr class='value_tr'><td>장바구니의 내용이 없습니다.</td></tr>"
            );
        }
    }
    
    function calc_basket(){
          var total_sum = 0;
          var total_tax = 0;
        
         $("input[name='basket_idx[]']").each(function() {
             if($(this).is(":checked")){
                var idx = $(this).val();
                var price = $("#basket_price_"+idx).val();
                var tax = $("#basket_tax_"+idx).val();
                var cnt = $("#basket_cnt_"+idx).val();
                
                total_sum += Number(price) * Number(cnt);
                total_tax += (Number(price) * (Number(tax) / 100)) * Number(cnt);
            }
            
         });
         
         $("#basket_sum").html(total_sum);
         $("#basket_tax").html(total_tax);
         $("#basket_total").html(total_sum + total_tax);
    }
    
    function remove_basket(idx){
        var menu_array = $.cookie('menu_array').split('/');
        var cnt_array = $.cookie('cnt_array').split('/');
    
        var del_idx = menu_array.indexOf(""+idx);
        
        if(del_idx != -1){
            var menu_cnt_string = "";
            var menu_idx_string = "";


            menu_array.splice(del_idx,1);
            cnt_array.splice(del_idx,1);

            for(var j=1;j<menu_array.length;j++){    //장바구니에서 변경될게 없는 상품
                if(menu_array[j] != ""){
                    menu_cnt_string += "/"+cnt_array[j];
                    menu_idx_string += "/"+menu_array[j];
                }
            }

            $.cookie('menu_array', menu_idx_string, {path: '/' });
            $.cookie('cnt_array', menu_cnt_string, {path: '/' });

            check_basket();
            count_basket();
        }else{
            alert('존재하지 않는 주문명입니다.');
        }
        
    }
    
    function submit_basket(){
        
        $.ajax({
                url:'/customer/setSubmitBasket',
                type:'post',
                data: $("#basket_form").serialize(),
                success:function(data){
                    alert('처리완료');
                    location.reload();
                },
                error: function(xhr,status,error) {
                    console.log(xhr,status,error);
                    alert("네트워크 오류!! 관리자에게 문의 주세요!!");
                    return false;
                }	
            });
        
    }
    
    function cnt_change(idx,mode){
            
        var idx_cnt =  $("#basket_cnt_"+idx).val();
        console.log(idx+"/"+idx_cnt);
        if(mode == 1 ){
           idx_cnt++;
           $("#basket_cnt_"+idx).val(idx_cnt);
        }else{
            idx_cnt --;

            if(idx_cnt < 0){
                alert("갯수를 확인하세요.");
                return false;
            }else{
               $("#basket_cnt_"+idx).val(idx_cnt);
            }
        }
        calc_basket();
    }
</script>