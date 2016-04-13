<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Managers\PaymentManager;
use Illuminate\Http\Request;
use Validator;

class HomeController extends Controller
{
    /** @var PaymentManager */
    protected $paymentManager;

    /**
     * Create a new controller instance.
     *
     * @param PaymentManager $paymentManager
     */
    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:0',
            ]);

            if ($validator->passes()) {
                $this->paymentManager->add(
                    $request->input('amount'),
                    $request->user()
                );

                return redirect('/home');
            } else {
                return redirect('/home')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        return view(
            'home',
            [
                'user'      => $request->user(),
                'payments'  => $this->paymentManager->getAllPaymentsForUser($request->user()),
            ]
        );
    }
}
