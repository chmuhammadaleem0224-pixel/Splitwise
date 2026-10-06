<?php
namespace App\Services;
use App\Models\{Group,User}; use App\Exceptions\{GroupNotFoundException,UnauthorizedGroupAccessException,ApiException};
class GroupService {
 public function get(string $id,User $u):Group { $g=Group::find($id); if(!$g) throw new GroupNotFoundException('Group not found.',404); if(!in_array((string)$u->_id,array_map('strval',$g->member_ids??[]),true)) throw new UnauthorizedGroupAccessException('You are not a member of this group.',403); return $g; }
 public function manage(string $id,User $u):Group { $g=$this->get($id,$u); if((string)$g->owner_id!==(string)$u->_id) throw new UnauthorizedGroupAccessException('Only the group owner can manage members.',403); return $g; }
 public function create(array $data,User $u):Group { return Group::create(['name'=>$data['name'],'description'=>$data['description']??null,'owner_id'=>(string)$u->_id,'member_ids'=>[(string)$u->_id]]); }
 public function add(Group $g,string $id):Group { if(!User::find($id)) throw new ApiException('User not found.',404); $members=array_map('strval',$g->member_ids??[]); if(!in_array($id,$members,true)) {$members[]=$id;$g->member_ids=$members;$g->save();} return $g; }
 public function remove(Group $g,string $id):Group { if($id===(string)$g->owner_id) throw new ApiException('The group owner cannot be removed.',422); $g->member_ids=array_values(array_filter(array_map('strval',$g->member_ids??[]),fn($x)=>$x!==$id));$g->save();return $g; }
}
