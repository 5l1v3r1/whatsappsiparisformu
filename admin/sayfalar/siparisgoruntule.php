<?php session_start(); ob_start(); ?>
<?php include 'baglantilar/database.php'; ?>
<?php
if( isset($_SESSION['yonetici']) && !empty($_SESSION['yonetici']) ){
  $records = $conn->prepare('SELECT * FROM yoneticiler WHERE id = :id');
  $records->bindParam(':id', $_SESSION['yonetici']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);
  $user = NULL;
  if( count($results) > 0){
    $user = $results;
  }
}
else
{
  header("Location: giris.php");
  die();
}
?>

<?php 
$vericek = $conn -> prepare("SELECT * FROM siparistalepleri where id = :id");
$vericek->bindParam(':id', $_GET['id']);
$vericek-> execute();
$veriyigoster = $vericek -> fetch(PDO::FETCH_ASSOC);
?>

<?php
if (isset($_POST["Gonder"])) {
  $cevap = $_POST['cevap'];
  $durum = '1';
  $guncelle = $conn->prepare("UPDATE siparistalepleri SET yoneticicevap=:yoneticicevap, durum=:durum WHERE id=:id ");
  $guncelle->execute(array(':yoneticicevap'=>$cevap , ':id'=>$_GET['id'] , ':durum'=>$durum));
  if($guncelle){
    echo '<meta http-equiv="refresh" content="2;URL=?sayfa=siparistalepleri">
    <div class="alert alert-dismissible alert-success">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Cevabınız Gönderildi!. 2 Saniye İçinde Yönlendiriliyorsunuz</strong>
    </div>';
  }else{
    echo '<div class="alert alert-dismissible alert-danger">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Hata Oluştu !</strong>
    </div>';
  }
}
?>

<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?sayfa=anasayfa">Ana Sayfa</a></li>
    <li class="breadcrumb-item active">Sipariş Talepleri</li>
  </ol>
  <div class="row">
    <div class="col-xl-12 col-sm-6 mb-3">
      <form method="post" action="">
        <div class="card mb-3">
          <div class="card-header">Mail: <?php echo $veriyigoster['eposta']; ?> - Telefon : <?php echo $veriyigoster['telefon']; ?> - Tarih: <?php echo $veriyigoster['tarih']; ?></div>
          <div class="card-body">
            <h5 class="card-title"><?php echo $veriyigoster['urun']; ?></h5>
            <p class="card-text"><?php echo $veriyigoster['kullanicinot']; ?></p>
          </div>
        </div>
        <?php if($veriyigoster["durum"]==2) {?>
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="alert alert-dismissible alert-primary"><strong><?php echo $veriyigoster['yoneticicevap']; ?></strong></div>
            </div>
          </div>
        <?php } ?> 
        <?php if($veriyigoster["durum"]==1) {?>        
          <div class="message"></div>
          <div class="row">
            <div class="col-xs-12 col-md-12 form-group">
              <select class='form-control' id='cevap' name='cevap'>
                <option class='form-control' value='Yeni'>Bekliyor</option>
                <option class='form-control' value='Onaylı'>Onaylandı</option>
                <option class='form-control' value='Hazır'>Hazırlandı</option>
                <option class='form-control' value='Kargo'>Kargolandı</option>
                <option class='form-control' value='Teslim'>Teslim Edildi</option>
                <option class='form-control' value='İptal'>İptal Edildi</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-md-12 form-group">
              <button class="btn btn-success btn-sm" name="Gonder" type="submit">Cevabı Gönder</button>
            </div>
          </div>
        <?php } ?>
      </form>
    </div>
  </div>
</div>