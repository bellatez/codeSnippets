public function index()
    {
       //obtain the date range for the balancesheet 
        $format = 'Y/m/d';
        $now = date($format);
        $to = date($format, strtotime("+30 days"));
        $constraints = [
            'from' => $now,
            'to' => $to
        ];
        //count each item in the various lists and display on the index page
        $productcount=product::all()->Where('status', 1)->where('user_id', Auth::user()->id)->count();
        $finishedcount=product::all()->Where('status', 0)->where('user_id', Auth::user()->id)->count();
        $debtcount=debt::all()->Where('user_id', Auth::user()->id)->count();
        $transactioncount=transaction::all()->Where('user_id', Auth::user()->id)->count();
        $balancesheet = $this->getBalanceSheet($constraints);        

        return view('analysis.index', compact('productcount', 'finishedcount', 'debtcount', 'transactioncount', 'balancesheet', ['searchingVals' => $constraints]));
    }

    //return all data from transactions table lying between a certain providing date range
    private function getBalanceSheet($constraints) {
        $balancesheet = transaction::where('date', '>=', $constraints['from'])
                        ->where('date', '<=', $constraints['to'])
                        ->Where('user_id', Auth::user()->id)
                        ->get();
        return $balancesheet;
    }

    //calculate total income and expenditure for a certain date range and produce invoice
    public function invoice(Request $request) {
        $constraints = [
            'from' => $request['from'],
            'to' => $request['to']
        ];

        $balancesheet = $this->getBalanceSheet($constraints);
        $sumincome = $balancesheet->sum('income');
        $sumexpense = $balancesheet->sum('expenditure');
        return view('analysis.balancesheet', ['balancesheet'=>$balancesheet, 'searchingVals' => $constraints, 'resultfrom'=>$constraints['from'], 'resultto'=>$constraints['to'], 'incometotal'=> $sumincome, 'expensetotal'=> $sumexpense]);
    }
    
     public function modify($id)
    {
        /*the explode function returns an array of strings which each element in the array 
            being the element after a comma
            pluck() function takes the name of all the products under that user and parse to 
            an array using toArray() functio
            array_diff compares products in that array with the itemlist array and returns the
            all items in products that are not in itemlist
        */
        $debt = debt::findOrFail($id);
        $itemlist = $debt->items;
        $itemlist = explode(',',$itemlist);
        $products = product::where('user_id', Auth::user()->id)->pluck('name')->toArray();
        $newItem  = array_diff($products, $itemlist);
        return $result;

    }
