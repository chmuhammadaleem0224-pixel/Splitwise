<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\Token;
class AuthenticateToken
{
 public function handle(Request $request, Closure $next) {
   $header=$request->header('Authorization','');
   if (!preg_match('/^Bearer\s+(.+)$/i',$header,$m)) return response()->json(['success'=>false,'message'=>'Authentication token is required.','errors'=>[]],401);
   $token=Token::where('api_token',$m[1])->first();
   if (!$token) return response()->json(['success'=>false,'message'=>'Invalid authentication token.','errors'=>[]],401);
   $user=\App\Models\User::find($token->user_id);
   if (!$user) return response()->json(['success'=>false,'message'=>'Authenticated user not found.','errors'=>[]],401);
   $request->setUserResolver(fn()=> $user); $request->attributes->set('auth_user',$user); return $next($request);
 }
}
