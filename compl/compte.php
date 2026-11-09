<?php
$nbdts=$bdd->query('SELECT COUNT(*) AS nombre_dts FROM candidats WHERE niveau="DTS"');
$nombre_dts=$nbdts->fetch();

$nbdtsmeem=$bdd->query('SELECT COUNT(*) AS nombre_dts_meem FROM candidats WHERE niveau="DTS" AND parcours1="MEEM"');
$nombre_dts_meem=$nbdtsmeem->fetch();

$nbdtsmeft=$bdd->query('SELECT COUNT(*) AS nombre_dts_meft FROM candidats WHERE niveau="DTS" AND parcours1="MEFT"');
$nombre_dts_meft=$nbdtsmeft->fetch();

$nbdtsmsa=$bdd->query('SELECT COUNT(*) AS nombre_dts_msa FROM candidats WHERE niveau="DTS" AND parcours1="MSA"');
$nombre_dts_msa=$nbdtsmsa->fetch();

$nbdtsrt=$bdd->query('SELECT COUNT(*) AS nombre_dts_rt FROM candidats WHERE niveau="DTS" AND parcours1="RT"');
$nombre_dts_rt=$nbdtsrt->fetch();

$nbdtstim=$bdd->query('SELECT COUNT(*) AS nombre_dts_tim FROM candidats WHERE niveau="DTS" AND parcours1="TIM"');
$nombre_dts_tim=$nbdtstim->fetch();

$nbdtsbat=$bdd->query('SELECT COUNT(*) AS nombre_dts_bat FROM candidats WHERE niveau="DTS" AND parcours1="BAT"');
$nombre_dts_bat=$nbdtsbat->fetch();

$nbdtstp=$bdd->query('SELECT COUNT(*) AS nombre_dts_tp FROM candidats WHERE niveau="DTS" AND parcours1="TP"');
$nombre_dts_tp=$nbdtstp->fetch();

$nbdtstecna=$bdd->query('SELECT COUNT(*) AS nombre_dts_tecna FROM candidats WHERE niveau="DTS" AND parcours1="TecNa"');
$nombre_dts_tecna=$nbdtstecna->fetch();

$nbdtscom=$bdd->query('SELECT COUNT(*) AS nombre_dts_com FROM candidats WHERE niveau="DTS" AND parcours1="COM"');
$nombre_dts_com=$nbdtscom->fetch();

$nbdtstghh=$bdd->query('SELECT COUNT(*) AS nombre_dts_tghh FROM candidats WHERE niveau="DTS" AND parcours1="TGH-H"');
$nombre_dts_tghh=$nbdtstghh->fetch();

$nbdtstght=$bdd->query('SELECT COUNT(*) AS nombre_dts_tght FROM candidats WHERE niveau="DTS" AND parcours1="TGH-T"');
$nombre_dts_tght=$nbdtstght->fetch();

$nbdtstba=$bdd->query('SELECT COUNT(*) AS nombre_dts_tba FROM candidats WHERE niveau="DTS" AND parcours1="TBA"');
$nombre_dts_tba=$nbdtstba->fetch();

$nbdtsgfc=$bdd->query('SELECT COUNT(*) AS nombre_dts_gfc FROM candidats WHERE niveau="DTS" AND parcours1="GFC"');
$nombre_dts_gfc=$nbdtsgfc->fetch();



//Pour tableau DTSS
$nbdtss=$bdd->query('SELECT COUNT(*) AS nombre_dtss FROM candidats WHERE niveau="DTSS"');
$nombre_dtss=$nbdtss->fetch();
$nbdtsssera=$bdd->query('SELECT COUNT(*) AS nombre_dtss_sera FROM candidats WHERE niveau="DTSS" AND parcours1="SERA"');
$nombre_dtss_sera=$nbdtsssera->fetch();
$nbdtssmure=$bdd->query('SELECT COUNT(*) AS nombre_dtss_mure FROM candidats WHERE niveau="DTSS" AND parcours1="MURE"');
$nombre_dtss_mure=$nbdtssmure->fetch();
$nbdtssadr=$bdd->query('SELECT COUNT(*) AS nombre_dtss_adr FROM candidats WHERE niveau="DTSS" AND parcours1="ADR"');
$nombre_dtss_adr=$nbdtssadr->fetch();
$nbdtssirm=$bdd->query('SELECT COUNT(*) AS nombre_dtss_irm FROM candidats WHERE niveau="DTSS" AND parcours1="IRM"');
$nombre_dtss_irm=$nbdtssirm->fetch();
$nbdtssccibat=$bdd->query('SELECT COUNT(*) AS nombre_dtss_ccibat FROM candidats WHERE niveau="DTSS" AND parcours1="CCI-BAT"');
$nombre_dtss_ccibat=$nbdtssccibat->fetch();
$nbdtssccitp=$bdd->query('SELECT COUNT(*) AS nombre_dtss_ccitp FROM candidats WHERE niveau="DTSS" AND parcours1="CCI-TP"');
$nombre_dtss_ccitp=$nbdtssccitp->fetch();
$nbdtsstan=$bdd->query('SELECT COUNT(*) AS nombre_dtss_tan FROM candidats WHERE niveau="DTSS" AND parcours1="TAN"');
$nombre_dtss_tan=$nbdtsstan->fetch();
$nbdtsstan=$bdd->query('SELECT COUNT(*) AS nombre_dtss_tan FROM candidats WHERE niveau="DTSS" AND parcours1="TAN"');
$nombre_dtss_tan=$nbdtsstan->fetch();
$nbdtssdpt=$bdd->query('SELECT COUNT(*) AS nombre_dtss_dpt FROM candidats WHERE niveau="DTSS" AND parcours1="DPT"');
$nombre_dtss_dpt=$nbdtssdpt->fetch();
$nbdtsstci=$bdd->query('SELECT COUNT(*) AS nombre_dtss_tci FROM candidats WHERE niveau="DTSS" AND parcours1="TCI"');
$nombre_dtss_tci=$nbdtsstci->fetch();
$nbdtsscca=$bdd->query('SELECT COUNT(*) AS nombre_dtss_cca FROM candidats WHERE niveau="DTSS" AND parcours1="CCA"');
$nombre_dtss_cca=$nbdtsscca->fetch();
$nbdtsscgc=$bdd->query('SELECT COUNT(*) AS nombre_dtss_cgc FROM candidats WHERE niveau="DTSS" AND parcours1="CGC"');
$nombre_dtss_cgc=$nbdtsscgc->fetch();



$nbing=$bdd->query('SELECT COUNT(*) AS nombre_ing FROM candidats WHERE niveau="INGENIORAT"');
$nombre_ing=$nbing->fetch();
$nbingtami=$bdd->query('SELECT COUNT(*) AS nombre_ing_tami FROM candidats WHERE niveau="INGENIORAT" AND parcours1="TAM-I"');
$nombre_ing_tami=$nbingtami->fetch();
$nbingtamn=$bdd->query('SELECT COUNT(*) AS nombre_ing_tamn FROM candidats WHERE niveau="INGENIORAT" AND parcours1="TAM-N"');
$nombre_ing_tamn=$nbingtamn->fetch();
$nbingnte=$bdd->query('SELECT COUNT(*) AS nombre_ing_nte FROM candidats WHERE niveau="INGENIORAT" AND parcours1="NTE"');
$nombre_ing_nte=$nbingnte->fetch();
$nbingice=$bdd->query('SELECT COUNT(*) AS nombre_ing_ice FROM candidats WHERE niveau="INGENIORAT" AND parcours1="ICE"');
$nombre_ing_ice=$nbingice->fetch();
$nbingmeo=$bdd->query('SELECT COUNT(*) AS nombre_ing_meo FROM candidats WHERE niveau="INGENIORAT" AND parcours1="MEO"');
$nombre_ing_meo=$nbingmeo->fetch();
?>