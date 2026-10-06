<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;use App\Services\{GroupService,BalanceService};use App\Support\ApiResponse;
class BalanceController extends Controller {public function __construct(private GroupService $groups,private BalanceService $balances,private ApiResponse $response){} public function balances(Request $r,string $group){$g=$this->groups->get($group,$r->user());return $this->response->success('Group balances retrieved successfully.',['group_id'=>(string)$g->_id,'balances'=>$this->balances->balances($g)]);}public function debts(Request $r,string $group){$g=$this->groups->get($group,$r->user());return $this->response->success('Group debts retrieved successfully.',['group_id'=>(string)$g->_id,'debts'=>$this->balances->debts($g)]);}}
