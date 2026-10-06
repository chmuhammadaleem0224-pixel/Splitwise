<?php
namespace App\Exceptions;
use Exception;
class ApiException extends Exception { public function __construct(string $message, private int $statusCode=400, private array $details=[]) { parent::__construct($message); } public function status(): int { return $this->statusCode; } public function errors(): array { return $this->details; } }
