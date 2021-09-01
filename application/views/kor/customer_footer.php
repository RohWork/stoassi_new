<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-2 col-xs-2" style="text-align: center" onclick="location.href='/customer/orderMenu/1/10'">HOME</div>
            <div class="col-md-2 col-xs-2" style="text-align: center" onclick="history.back();">뒤로가기</div>
            <div class="col-md-2 col-xs-3" style="text-align: center" onclick="$('#myModal').modal('show');">장바구니</div>
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
          <table id="basket_list">
              <tr>
                  <th>CHECK</th>
                  <th>주문명</th>
                  <th>갯수</th>
              </tr>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">구입</button>
      </div>
    </div>
  </div>
</div>


<script>

</script>