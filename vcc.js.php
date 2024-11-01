<?php
$vp = $_GET['vp'];
$vu = $_GET['vu'];
$vs = $_GET['vs'];
$vm = $_GET['vm'];
$l = $_GET['l'];
?>

var vccpos=document.getElementById('<?php echo $vp ?>');
if(vccpos){
var vccbox = document.createElement('div');
var vccimg = document.createElement('img');
var vccref = document.createElement('a');
var vccinp = document.createElement('input');
vccbox.appendChild(vccimg);
vccbox.appendChild(vccref);
vccbox.appendChild(vccinp);
vccbox.style.display='block';
vccbox.style.clear='both';
vccbox.style.width='180px';
vccbox.style.height='72px';
vccbox.style.marginTop='2px';
vccbox.style.marginBottom='2px';
vccbox.style.backgroundImage='url(<?php echo $vu ?>/vcc_bg<?php echo $vs ?>.png)';
vccbox.style.backgroundPosition='left left';
vccbox.style.backgroundRepeat='no-repeat';
vccimg.src='<?php echo $vu ?>/vcode.class.php?m=<?php echo $vm ?>&l=<?php echo $l ?>';
vccimg.style.marginTop='10px';
vccimg.style.marginRight='12px';
vccimg.style.marginBottom='9px';
vccimg.style.marginLeft='14px';
vccref.href='javascript:;';
vccref.style.display='inline-block';
vccref.style.width='20px';
vccref.style.height='20px';
vccref.style.marginTop='10px';
vccref.style.marginBottom='9px';
vccinp.name='vcode';
vccinp.type='text';
vccinp.value='<?php echo $vm?>'=='string'?'Enter the above string':'Enter the equation result';
vccinp.style.borderWidth='0';
vccinp.style.color='#999';
vccinp.style.fontStyle='italic';
vccinp.style.width='152px';
vccinp.style.height='15px';
vccinp.style.marginTop='2px';
vccinp.style.marginLeft='14px';

vccpos.parentNode.insertBefore(vccbox, vccpos);
vccref.onclick = function(){vccimg.src="<?php echo $vu ?>/vcode.class.php?m=<?php echo $vm ?>&l=<?php echo $l ?>&"+Math.random();}
vccinp.onfocus = function(){ if(this.value=='Enter the above string'||this.value=='Enter the equation result'){this.style.fontStyle=''; this.style.color='';this.value='';} }
vccinp.onblur = function(){ if(this.value==''){this.style.fontStyle='italic'; this.style.color='#999'; this.value='<?php echo $vm ?>'=='string'?'Enter the above string':'Enter the equation result';} }
}