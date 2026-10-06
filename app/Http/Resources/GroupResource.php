<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class GroupResource extends JsonResource { public function toArray($request):array{return ['id'=>(string)$this->_id,'name'=>$this->name,'description'=>$this->description,'owner_id'=>(string)$this->owner_id,'member_ids'=>array_map('strval',$this->member_ids ?? []),'created_at'=>$this->created_at];} }
