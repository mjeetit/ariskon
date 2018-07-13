<div class="grid_12">
  <!-- Example table -->
  <div class="module">
    <h2><span>Chemist Vist</span></h2>
    <div class="module-table-body"> 
      <form action="" method="post" enctype="multipart/form-data">
        <table width="96%" border="0" cellspacing="1" cellpadding="2">
          <!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->
          <?php if($_SESSION['AdminDesignation']!=5) { ?>
          <tr class="even">
            <td align="left" class="bold_text">ZBM : </td>
            <td align="left"><select name="data[zbm_visit]">
                <option value="">-- Select ZBM --</option>
                <?php foreach($this->zbmDetails as $zbmDetail){
                    $select = '';
                    if($this->postData['zbm_visit']==$zbmDetail['user_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$zbmDetail['user_id']?>" <?=$select?>><?=$zbmDetail['first_name'].' '.$zbmDetail['last_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <?php } ?>
          
          <!--Regional Business Manager (RBM) Pull Down List for Filter Option-->
          <?php if($_SESSION['AdminDesignation']!=6) { ?>
          <tr class="odd">
            <td align="left" class="bold_text">RBM : </td>
            <td align="left"><select name="data[rbm_visit]">
                <option value="">-- Select RBM --</option>
                <?php foreach($this->rbmDetails as $rbmDetail){
                    $select = '';
                    if($this->postData['rbm_visit']==$rbmDetail['user_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$rbmDetail['user_id']?>" <?=$select?>><?=$rbmDetail['first_name'].' '.$rbmDetail['last_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <?php } ?>
          
          <!--Area Business Manager (ABM) Pull Down List for Filter Option-->
          <?php if($_SESSION['AdminDesignation']!=7) { ?>
          <tr class="even">
            <td align="left" class="bold_text">ABM : </td>
            <td align="left"><select name="data[abm_visit]">
                <option value="">-- Select ABM --</option>
                <?php foreach($this->abmDetails as $abmDetail){
                    $select = '';
                    if($this->postData['abm_visit']==$abmDetail['user_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$abmDetail['user_id']?>" <?=$select?>><?=$abmDetail['first_name'].' '.$abmDetail['last_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <?php } ?>
          
          <!--Business Executive (BE) Pull Down List for Filter Option-->
          <?php if($_SESSION['AdminDesignation']!=8) { ?>
          <tr class="odd">
            <td align="left" class="bold_text">BE : </td>
            <td align="left"><select name="data[be_visit]">
                <option value="">-- Select BE --</option>
                <?php foreach($this->beDetails as $beDetail){
                    $select = '';
                    if($this->postData['be_visit']==$beDetail['user_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$beDetail['user_id']?>" <?=$select?>><?=$beDetail['first_name'].' '.$beDetail['last_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <?php } ?>
          
          <!--Patchcode Pull Down List for Filter Option-->
          <tr class="even">
            <td align="left" class="bold_text">Patchcode : </td>
            <td align="left"><select name="data[patch_id]" required>
                <option value="">-- Select Patch --</option>
                <?php foreach($this->patches as $patch){
                    $select = '';
                    if($this->postData['patch_id']==$patch['patch_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$patch['patch_id']?>" <?=$select?>><?=$patch['patch_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          
          <!--Chemist Pull Down List for Filter Option-->
          <tr class="odd">
            <td align="left" class="bold_text">Chemist : </td>
            <td align="left"><select name="data[chemist_id]">
                <option value="">-- Select Chemist --</option>
                <?php foreach($this->chemists as $chemist){
                    $select = '';
                    if($this->postData['chemist_id']==$chemist['chemist_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$chemist['chemist_id']?>" <?=$select?>><?=$chemist['chemist_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          
          <!--Call Detail-->
          <tr class="even">
            <td align="left" class="bold_text">Call Detail : </td>
            <td align="left"><textarea name="data[call_detail]" rows="5" class="20" required><?=$this->postData['call_detail']?></textarea></td>
          </tr>
          
          <!--Promoted Product Pull Down List for Filter Option-->
          <?php for($i=1;$i<=5;$i++) { $rowClass = ($i%2==0) ? 'even' : 'odd'; ?>
          <tr class="<?=$rowClass?>">
            <td align="left" class="bold_text">RCPA : </td>
            <td align="left"><select name="data[product<?=$i?>]">
                <option value="">-- Select Product --</option>
                <?php foreach($this->products as $product){
                    $select = '';
                    if($this->postData['product'.$i]==$product['product_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$product['product_id']?>" <?=$select?>><?=$product['product_name']?></option>
                <?php } ?>
              </select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              Unit : <input type="text" size="5" name="data[unit<?=$i?>]" maxlength="4" value="<?=$this->postData['unit'.$i]?>" />
            </td>
          </tr>
          <?php } ?>
          
          <tr class="even">
            <td width="15%"></td>
            <td width="85%" align="left" class="bold_text"><input type="submit" name="addnewdata" class="inputbutton" value="Submit" /></td>			
          </tr>
        </table>
      </form>
      <div style="clear: both"></div>
    </div> <!-- End .module-table-body -->
  </div><!-- End .module -->
</div> <!-- End .grid_12 -->

<script type="text/ecmascript" language="javascript">
	$(document).ready(function() {
		if("<?=$_SESSION['AdminLoginID']?>"=='1') {
			$("input").attr('disabled','disabled');
			$("select").attr('disabled','disabled');
			$("textarea").attr('disabled','disabled');
		}		
	});
</script>