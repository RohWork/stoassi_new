<footer>
    <!-- 쿠키 사용 --> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>     
    
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-2 col-xs-2" style="text-align: center" onclick="location.href='/customer/orderMenu/1/10'"><span class="glyphicon glyphicon-home"></span>&nbsp;</div>
            <div class="col-md-2 col-xs-2" style="text-align: center" onclick="history.back();"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;</div>
            <div class="col-md-2 col-xs-3" style="text-align: center" onclick="$('#myModal').modal('show');check_basket();"><span class="glyphicon glyphicon-inbox"></span>[<span id="view_basket_cnt"></span>]</div>
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
          <table id="basket_list" style="width:100%;" class="table">
              <tr>
                  <th width="10%"></th>
                  <th width="45%">메뉴명</th>
                  <th width="20%">금액</th>
                  <th width="20%">갯수</th>
                  <th width="5%"></th>
              </tr>
          </table>
            <div style="width: 100%; margin-bottom: 30px">
                <label> 메뉴가격 :  </label><span id="sum"> </span><br/>
                <label> 부가세 : </label><span id="tax"></span><br/>
                <label> 계산된 금액 : </label><span id="total"> </span>

            </div>
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-primary">구입</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
    
    $(document).ready(function(){
        check_basket();
        count_basket();
    });
    
    function count_basket(){
        if(typeof($.cookie('cnt_array')) != "undefined"){
            var cnt_array = $.cookie('cnt_array').split('/');
            var basket_cnt = 0;
            
            for( var i =0; i<cnt_array.length; i++){
                basket_cnt += Number(cnt_array[i]);
            }
            $("#view_basket_cnt").html("+"+basket_cnt);
            
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
                                                        +"<td> <input type='checkbox' name='basket_idx' id='basket_idx_"+basket[i].idx+"' value='"+basket[i].idx+"' checked='checked'  oncange='calc_basket()'/></td>"
                                                        +"<td>"+basket[i].name+"</td>"
                                                        +"<td>"+basket[i].price
                                                                +"<input type='hidden' id='basket_price_"+basket[i].idx+"' name='basket_price_"+basket[i].idx+"'  value='"+basket[i].price+"'/>"
                                                                +"<input type='hidden' id='basket_tax_"+basket[i].idx+"' name='basket_tax_"+basket[i].idx+"' value='"+basket[i].tax+"'/>"
                                                        +"</td>"
                                                        +"<td> <input type='number' name=basket_cnt[]' class='form-control' value='"+basket_array[basket[i].idx]+"' onkeyup='calc_basket();'/></td>"
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
            calc_basket();
        }else{
            $("#basket_list").html(
                    "<tr><td>장바구니의 내용이 없습니다.</td></tr>"
            );
        }
    }
    
    function calc_basket(){
        
         $("input[name='basket_idx']").each(function() {
             if($(this).is(":checked")){
                var idx = $(this).val();
                console.log(idx);
            }
         });
    
    
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
</script>