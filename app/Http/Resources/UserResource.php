<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class UserResource extends JsonResource { public function toArray($request):array{return ['id'=>(string)$this->_id,'name'=>$this->name,'email'=>$this->email,'created_at'=>$this->created_at];} }
