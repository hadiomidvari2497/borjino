<?php
function charge_methods(): array {
    return [1=>'واحدی',2=>'متراژی',3=>'نفری',4=>'نفر-متراژی',5=>'واحدی + متراژی',6=>'واحدی + نفری',7=>'واحدی + نفر-متراژی',8=>'نفری + متراژی',9=>'واحدی + متراژی + نفری',10=>'هزینه‌محور'];
}
function get_charge_settings(PDO $pdo): array {
    $row=$pdo->query('SELECT * FROM charge_settings WHERE id=1')->fetch();
    if(!$row){$pdo->exec('INSERT INTO charge_settings(id) VALUES(1)');$row=$pdo->query('SELECT * FROM charge_settings WHERE id=1')->fetch();}
    return $row;
}
function charge_resident_count(PDO $pdo,int $unitId,string $period): int {
    if(!preg_match('/^(\d{4})-(\d{2})$/',$period,$m)) return 0;
    $start=$m[1].'-'.$m[2].'-01'; $end=date('Y-m-t',strtotime($start));
    $st=$pdo->prepare('SELECT COUNT(*) FROM memberships WHERE unit_id=? AND (start_date IS NULL OR start_date<=?) AND (end_date IS NULL OR end_date>=?)');
    $st->execute([$unitId,$end,$start]); return (int)$st->fetchColumn();
}
function charge_decimal($value): string { return number_format((float)$value,2,'.',''); }
function calculate_unit_charge(PDO $pdo,array $unit,string $period,?array $settings=null): array {
    $settings=$settings?:get_charge_settings($pdo); $method=(int)$settings['calculation_method'];
    if($method<1||$method>10) throw new RuntimeException('روش محاسبه شارژ نامعتبر است.');
    $area=(float)$unit['area']; $persons=charge_resident_count($pdo,(int)$unit['id'],$period);
    $U=(float)$settings['fixed_unit_amount']; $rm=(float)$settings['area_rate']; $rn=(float)$settings['person_rate'];
    $parking=(float)$unit['parking_count']*(float)$settings['parking_rate']; $storage=(float)$unit['storage_count']*(float)$settings['storage_rate'];
    $areaPart=$area*$rm; $personPart=$persons*$rn;
    $details=['method'=>$method,'area'=>$area,'persons'=>$persons,'U'=>$U,'rm'=>$rm,'rn'=>$rn,'area_part'=>$areaPart,'person_part'=>$personPart,'parking'=>$parking,'storage'=>$storage];
    if($method===1) $base=$U;
    elseif($method===2) $base=$areaPart;
    elseif($method===3) $base=$personPart;
    elseif($method===4) $base=$areaPart*((float)$settings['area_percent']/100)+$personPart*((float)$settings['person_percent']/100);
    elseif($method===5) $base=$U+$areaPart;
    elseif($method===6) $base=$U+$personPart;
    elseif($method===7) $base=$U+$areaPart*((float)$settings['area_percent']/100)+$personPart*((float)$settings['person_percent']/100);
    elseif($method===8) $base=$personPart+$areaPart;
    elseif($method===9) $base=$U+$areaPart+$personPart;
    else {
        $st=$pdo->prepare('SELECT * FROM costs WHERE building_id=? AND cost_type=? AND (period IS NULL OR period=?) ORDER BY id');
        $st->execute([(int)$unit['building_id'],'variable',$period]); $costs=$st->fetchAll();
        $base=0; $costDetails=[];
        $totalArea=(float)$pdo->prepare('SELECT COALESCE(SUM(area),0) FROM units WHERE building_id=?')->execute([(int)$unit['building_id']]) ? 0 : 0;
        $stA=$pdo->prepare('SELECT COALESCE(SUM(area),0) FROM units WHERE building_id=?');$stA->execute([(int)$unit['building_id']]);$totalArea=(float)$stA->fetchColumn();
        $stP=$pdo->prepare('SELECT COUNT(*) FROM memberships m JOIN units u ON u.id=m.unit_id WHERE u.building_id=?');$stP->execute([(int)$unit['building_id']]);$totalPersons=(int)$stP->fetchColumn();
        $stU=$pdo->prepare('SELECT COUNT(*) FROM units WHERE building_id=?');$stU->execute([(int)$unit['building_id']]);$unitCount=(int)$stU->fetchColumn();
        foreach($costs as $c){
            $share=0;$cm=$c['allocation_method'];
            if($cm==='equal') $share=$unitCount?(float)$c['amount']/$unitCount:0;
            elseif($cm==='area') $share=$totalArea>0?(float)$c['amount']*$area/$totalArea:0;
            elseif($cm==='person') $share=$totalPersons>0?(float)$c['amount']*$persons/$totalPersons:0;
            elseif($cm==='combination'){ $a=(float)$c['allocation_area_percent']/100;$p=(float)$c['allocation_person_percent']/100;$share=($totalArea>0?(float)$c['amount']*$a*$area/$totalArea:0)+($totalPersons>0?(float)$c['amount']*$p*$persons/$totalPersons:0); }
            $base+=$share;$costDetails[]=['id'=>$c['id'],'title'=>$c['title'],'amount'=>(float)$c['amount'],'allocation'=>$cm,'share'=>$share];
        }
        $details['costs']=$costDetails;
    }
    $total=$base+($method===1?$parking+$storage:0);$details['base']=$base;$details['total']=$total;
    return ['amount'=>charge_decimal($total),'method'=>$method,'details'=>$details];
}
