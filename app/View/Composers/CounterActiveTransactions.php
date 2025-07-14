<?php

// app/Http/ViewComposers/NavigationComposer.php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Transactions;

class CounterActiveTransactions
{
    public function compose(View $view)
    {
        // Get the count of active transactions
        $activeTransactionsCount = Transactions::where('status', 'draft')->count();
        // Pass the count to the view
        $view->with('activeTransactionsCount', $activeTransactionsCount);
    }
}   