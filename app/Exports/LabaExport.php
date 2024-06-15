<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LabaExport implements FromView
{
    protected $tanggal;

    protected $laba;

    protected $transactions;

    public function __construct($tanggal, $laba, $transactions)
    {
        $this->tanggal = $tanggal;
        $this->laba = $laba;
        $this->transactions = $transactions;
    }

    public function view(): View
    {
        $tanggal = $this->tanggal;
        $laba = $this->laba;
        $transactions = $this->transactions;

        return view('includes.report-excel', compact('tanggal', 'laba', 'transactions'));
    }
}
