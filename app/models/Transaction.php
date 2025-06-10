<?php
namespace App\Models;

class Transaction extends Model
{
    const TYPE_INCOME = 'income';
    const TYPE_EXPENSE = 'expense';
    
    public function isIncome()
    {
        return $this->type === self::TYPE_INCOME;
    }
    
    public function isExpense()
    {
        return $this->type === self::TYPE_EXPENSE;
    }
    
    public function getFormattedAmount()
    {
        $prefix = $this->isIncome() ? '+' : '-';
        return $prefix . number_format($this->amount, 2);
    }
    
    public function getFormattedDate()
    {
        return date('M j, Y', strtotime($this->date));
    }
}
