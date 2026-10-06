<?php
namespace Tests\Unit;
use Tests\TestCase;
use App\Services\ExpenseService;
use App\Exceptions\InvalidExpenseSplitException;
class ExpenseSplitTest extends TestCase {
 private function split(float $amount,string $type,array $parts):array { $service=new ExpenseService();$ref=new \ReflectionClass($service);$m=$ref->getMethod('split');$m->setAccessible(true);return $m->invoke($service,$amount,$type,$parts); }
 public function test_equal_split_is_calculated():void {$r=$this->split(100,'equal',[['user_id'=>'1'],['user_id'=>'2'],['user_id'=>'3']]);$this->assertSame(33.33,$r[0]['amount']);$this->assertSame(33.33,$r[1]['amount']);$this->assertSame(33.34,$r[2]['amount']);}
 public function test_exact_split_must_equal_total():void {$this->expectException(InvalidExpenseSplitException::class);$this->split(100,'exact',[['user_id'=>'1','amount'=>40],['user_id'=>'2','amount'=>50]]);}
 public function test_percentage_split_must_equal_hundred():void {$this->expectException(InvalidExpenseSplitException::class);$this->split(100,'percentage',[['user_id'=>'1','percentage'=>40],['user_id'=>'2','percentage'=>50]]);}
 public function test_percentage_split_creates_amounts():void {$r=$this->split(1000,'percentage',[['user_id'=>'1','percentage'=>25],['user_id'=>'2','percentage'=>75]]);$this->assertSame(250.0,$r[0]['amount']);$this->assertSame(750.0,$r[1]['amount']);}
}
