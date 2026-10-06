<?php
namespace App\Services;
use App\Models\User; use App\Models\Token; use Illuminate\Support\Facades\Hash; use App\Exceptions\ApiException;
class AuthenticationService {
 public function register(array $data):array { if(User::where('email',$data['email'])->exists()) throw new ApiException('Email is already registered.',409); $u=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password'])]); return [$u,$this->issue($u)]; }
 public function login(array $data):array { $u=User::where('email',$data['email'])->first(); if(!$u || !Hash::check($data['password'],$u->password)) throw new ApiException('Invalid credentials.',401); return [$u,$this->issue($u)]; }
 public function issue(User $u):string { $token=bin2hex(random_bytes(32)); Token::create(['user_id'=>(string)$u->_id,'api_token'=>$token]); return $token; }
 public function logout(string $token):void { Token::where('api_token',$token)->delete(); }
}
