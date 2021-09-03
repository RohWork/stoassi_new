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
          <table id="basket_list" style="width:100%">
              <tr>
                  <th width="20%"></th>
                  <th width="35%"></th>
                  <th width="35%"></th>
                  <th width="10%"></th>
              </tr>
          </table>
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
                                                        +"<td> <input type='checkbox' name='basket_idx' id='basket_idx_"+basket[i].idx+"' value='"+basket[i].idx+"'/></td>"
                                                        +"<td>"+basket[i].name+"</td>"
                                                        +"<td> <input type='number' name=basket_cnt[]' class='form-control' value='"+basket_array[basket[i].idx]+"'</td>"
                                                        +"<td> <input type='button' onclick='remove_basket()' value='삭제'/></td>"+
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

        }else{
            $("#basket_list").html(
                    "<tr><td>장바구니의 내용이 없습니다.</td></tr>"
            );
        }
    }
    
    function remove_basket(){
        var check_val = new Array();
                
        $("input:checkbox[name='basket_idx']").each(function(idx){
            if(idx != 0){
                check_val.push($(this).val());
            }
        });
        
        console.log(check_val);
    
    
    }
</script>